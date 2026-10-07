/* Self-contained .xlsx reader for schedule values; no workbook code is executed. */
(() => {
  'use strict';
  const A = window.ASUE;
  const form = document.querySelector('[data-schedule-upload]');
  if (!A || !form || !A.can('schedules')) return;
  const $ = selector => document.querySelector(selector);
  const fields = [
    ['date', 'Օր / ամսաթիվ', ['օր', 'ամսաթիվ', 'date', 'day']],
    ['time', 'Ժամ', ['ժամ', 'ժամը', 'time']],
    ['group', 'Խումբ', ['խումբ', 'group']],
    ['subject', 'Առարկա', ['առարկա', 'subject', 'course']],
    ['teacher', 'Դասախոս', ['դասախոս', 'teacher', 'lecturer']],
    ['room', 'Լսարան', ['լսարան', 'room']]
  ];
  let workbook = null, pending = null, loadVersion = 0;
  const message = text => A.status($('[data-status]'), text);
  const fail = error => A.status($('[data-status]'), error.message || 'Ֆայլը չի հաջողվել կարդալ։', true);
  const elements = (root, name) => [...root.getElementsByTagNameNS('*', name)];
  const xml = bytes => {
    const doc = new DOMParser().parseFromString(new TextDecoder().decode(bytes), 'application/xml');
    if (elements(doc, 'parsererror').length || doc.doctype) throw new Error('Excel ֆայլի կառուցվածքը վնասված է։');
    return doc;
  };
  const invalidate = () => {
    pending = null; $('[data-publish-schedule]').hidden = true;
    $('[data-schedule-preview]').replaceChildren(); A.status($('[data-publish-status]'), '');
  };
  async function unzip(buffer) {
    const view = new DataView(buffer), bytes = new Uint8Array(buffer);
    let end = -1;
    for (let i = buffer.byteLength - 22; i >= Math.max(0, buffer.byteLength - 65557); i--) {
      if (view.getUint32(i, true) === 0x06054b50) { end = i; break; }
    }
    if (end < 0 || view.getUint16(end + 4, true) !== 0) throw new Error('Ընտրեք վավեր, չգաղտնագրված .xlsx ֆայլ։');
    const count = view.getUint16(end + 10, true); let offset = view.getUint32(end + 16, true);
    if (count > 1500) throw new Error('Ֆայլը չափազանց բարդ է։ Օգտագործեք միայն ժամանակացույցը պարունակող ֆայլ։');
    const entries = new Map(); let total = 0;
    for (let i = 0; i < count; i++) {
      if (offset + 46 > end || view.getUint32(offset, true) !== 0x02014b50) throw new Error('Excel ֆայլը վնասված է։');
      const flags = view.getUint16(offset + 8, true), method = view.getUint16(offset + 10, true);
      const compressed = view.getUint32(offset + 20, true), size = view.getUint32(offset + 24, true);
      const nameSize = view.getUint16(offset + 28, true), extra = view.getUint16(offset + 30, true), comment = view.getUint16(offset + 32, true);
      const local = view.getUint32(offset + 42, true);
      const name = new TextDecoder().decode(bytes.slice(offset + 46, offset + 46 + nameSize));
      total += size;
      if ((flags & 1) || total > 30 * 1024 * 1024 || size > 20 * 1024 * 1024) throw new Error('Ֆայլը գաղտնագրված է կամ գերազանցում է թույլատրելի ծավալը։');
      if (name.endsWith('.xml') || name.endsWith('.rels')) entries.set(name, { method, compressed, size, local });
      offset += 46 + nameSize + extra + comment;
    }
    return async name => {
      const entry = entries.get(name); if (!entry) return null;
      const { local, compressed, size, method } = entry;
      if (local + 30 > buffer.byteLength || view.getUint32(local, true) !== 0x04034b50) throw new Error('Ֆայլի տվյալները վնասված են։');
      const start = local + 30 + view.getUint16(local + 26, true) + view.getUint16(local + 28, true);
      if (start + compressed > buffer.byteLength) throw new Error('Ֆայլը ամբողջական չէ։');
      const input = bytes.slice(start, start + compressed);
      if (method === 0) return input;
      if (method !== 8 || typeof DecompressionStream === 'undefined') throw new Error('Այս ֆայլը կարդալու համար օգտագործեք դիտարկիչի վերջին տարբերակը։');
      const reader = new Blob([input]).stream().pipeThrough(new DecompressionStream('deflate-raw')).getReader();
      const chunks = []; let length = 0;
      for (;;) {
        const { value, done } = await reader.read(); if (done) break;
        length += value.length;
        if (length > size || length > 20 * 1024 * 1024) { await reader.cancel(); throw new Error('Ֆայլի բացված ծավալը գերազանցում է սահմանը։'); }
        chunks.push(value);
      }
      if (length !== size) throw new Error('Ֆայլը վնասված է։');
      const output = new Uint8Array(length); let index = 0;
      chunks.forEach(chunk => { output.set(chunk, index); index += chunk.length; });
      return output;
    };
  }
  async function readWorkbook(file) {
    if (!/\.xlsx$/i.test(file.name) || file.size > 5 * 1024 * 1024) throw new Error('Ընտրեք մինչև 5 ՄԲ ծավալով .xlsx ֆայլ։');
    const get = await unzip(await file.arrayBuffer());
    const bookBytes = await get('xl/workbook.xml'), relBytes = await get('xl/_rels/workbook.xml.rels');
    if (!bookBytes || !relBytes) throw new Error('Ֆայլը Excel աշխատագիրք չէ։');
    const book = xml(bookBytes), rels = xml(relBytes);
    const sharedBytes = await get('xl/sharedStrings.xml');
    const shared = sharedBytes ? elements(xml(sharedBytes), 'si').map(si => elements(si, 't').map(t => t.textContent).join('')) : [];
    const sheets = [];
    for (const sheet of elements(book, 'sheet')) {
      const rid = sheet.getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'id');
      const rel = elements(rels, 'Relationship').find(r => r.getAttribute('Id') === rid);
      if (!rel || rel.getAttribute('TargetMode') === 'External') continue;
      const target = rel.getAttribute('Target');
      const path = new URL(target, 'https://xlsx.local/xl/workbook.xml').pathname.slice(1);
      if (!path.startsWith('xl/worksheets/')) continue;
      const raw = await get(path); if (!raw) continue;
      const rows = elements(xml(raw), 'row');
      if (rows.length > 5001) throw new Error('Մեկ աշխատաթերթում թույլատրվում է առավելագույնը 5000 տվյալների տող։');
      const matrix = rows.map(row => {
        const values = [];
        const cells = elements(row, 'c');
        if (cells.length > 100) throw new Error('Աշխատաթերթում թույլատրվում է առավելագույնը 100 սյունակ։');
        cells.forEach(cell => {
          const ref = cell.getAttribute('r'); const letters = ref?.match(/^[A-Z]+/)?.[0];
          if (!letters) throw new Error('Բջջի հասցեն բացակայում է։');
          let col = 0; for (const letter of letters) col = col * 26 + letter.charCodeAt(0) - 64;
          if (col > 100) throw new Error('Օգտագործեք ժամանակացույցի առաջին 100 սյունակները։');
          if (elements(cell, 'f').length) throw new Error('Ժամանակացույցի ֆայլում բանաձևերի փոխարեն տեղադրեք պատրաստի արժեքները։');
          const type = cell.getAttribute('t'); const raw = elements(cell, 'v')[0]?.textContent ?? '';
          const value = type === 's' ? shared[Number(raw)] ?? '' : type === 'inlineStr' ? elements(cell, 't').map(t => t.textContent).join('') : raw;
          if (value.length > 1000) throw new Error('Բջջի արժեքը չի կարող գերազանցել 1000 նիշը։');
          values[col - 1] = value.trim();
        });
        return values;
      }).filter(row => row.some(Boolean));
      if (matrix.length) sheets.push({ name: sheet.getAttribute('name'), matrix });
    }
    if (!sheets.length) throw new Error('Ֆայլում տվյալներ պարունակող աշխատաթերթ չի գտնվել։');
    return { sheets, date1904: ['1', 'true'].includes(elements(book, 'workbookPr')[0]?.getAttribute('date1904')) };
  }
  function showMapping() {
    invalidate(); const target = $('[data-column-mapping]'); target.replaceChildren();
    const sheet = workbook?.sheets[Number(form.elements.sheet.value)]; if (!sheet) return;
    const headers = sheet.matrix[0];
    target.append(A.node('h3', 'Սյունակների համապատասխանեցում'), A.node('p', 'Առաջին ոչ դատարկ տողը դիտարկվում է որպես վերնագրերի տող։ Բոլոր վեց դաշտերը պարտադիր են։'));
    const grid = A.node('div', undefined, 'form-grid');
    fields.forEach(([key, title, aliases]) => {
      const label = A.node('label', title, 'field'); const select = A.node('select'); select.name = `column_${key}`; select.required = true;
      select.add(new Option('Ընտրել սյունակը', ''));
      headers.forEach((header, index) => { if (header) select.add(new Option(header, String(index))); });
      const found = headers.findIndex(header => aliases.includes(A.normalized(header)));
      if (found >= 0) select.value = String(found);
      label.append(select); grid.append(label);
    });
    target.append(grid); message(`«${sheet.name}»՝ ${sheet.matrix.length - 1} տվյալների տող։`);
  }
  form.elements.file.addEventListener('change', async () => {
    const version = ++loadVersion; invalidate(); workbook = null;
    $('[data-column-mapping]').replaceChildren(); $('[data-sheet-field]').hidden = true;
    const file = form.elements.file.files[0]; if (!file) { message('Ընտրեք Excel ֆայլը։'); return; }
    message('Ֆայլը կարդացվում է…');
    try {
      A.requirePermission('schedules'); const result = await readWorkbook(file);
      if (version !== loadVersion) return;
      workbook = result; form.elements.sheet.replaceChildren();
      workbook.sheets.forEach((sheet, i) => form.elements.sheet.add(new Option(sheet.name, String(i))));
      $('[data-sheet-field]').hidden = false; showMapping();
    } catch (error) { if (version === loadVersion) fail(error); }
  });
  form.elements.sheet.addEventListener('change', showMapping);
  form.addEventListener('input', invalidate);
  form.addEventListener('change', invalidate);
  const excelDate = value => {
    if (!/^\d+(\.\d+)?$/.test(value)) return value;
    const serial = Number(value); if (serial < 1 || serial > 100000) return value;
    const epoch = Date.UTC(workbook.date1904 ? 1904 : 1899, workbook.date1904 ? 0 : 11, workbook.date1904 ? 1 : 30);
    return new Date(epoch + Math.floor(serial) * 86400000).toISOString().slice(0, 10);
  };
  const excelTime = value => {
    if (!/^0?\.\d+$/.test(value)) return value;
    const minutes = Math.round(Number(value) * 24 * 60);
    if (minutes >= 1440) return value;
    return `${String(Math.floor(minutes / 60)).padStart(2, '0')}:${String(minutes % 60).padStart(2, '0')}`;
  };
  function validExamDate(value) {
    const iso = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    const local = value.match(/^(\d{2})[./](\d{2})[./](\d{4})$/);
    const parts = iso ? [Number(iso[1]), Number(iso[2]), Number(iso[3])] : local ? [Number(local[3]), Number(local[2]), Number(local[1])] : null;
    if (!parts) return false;
    const date = new Date(Date.UTC(parts[0], parts[1] - 1, parts[2]));
    return date.getUTCFullYear() === parts[0] && date.getUTCMonth() === parts[1] - 1 && date.getUTCDate() === parts[2];
  }
  form.addEventListener('submit', event => {
    event.preventDefault(); invalidate();
    try {
      A.requirePermission('schedules');
      if (!workbook) throw new Error('Նախ վերբեռնեք վավեր Excel ֆայլ։');
      const faculty = form.elements.faculty.value.trim();
      if (!faculty) throw new Error('Նշեք ֆակուլտետը։');
      const mapping = fields.map(([key]) => form.elements[`column_${key}`].value);
      if (mapping.some(value => value === '') || new Set(mapping).size !== fields.length) throw new Error('Յուրաքանչյուր դաշտի համար ընտրեք առանձին սյունակ։');
      const matrix = workbook.sheets[Number(form.elements.sheet.value)].matrix;
      const rows = matrix.slice(1).map((source, index) => {
        const row = {};
        fields.forEach(([key], i) => row[key] = source[Number(mapping[i])] ?? '');
        if (fields.some(([key]) => !row[key])) throw new Error(`Տող ${index + 2}․ լրացրեք բոլոր պարտադիր դաշտերը։`);
        row.date = excelDate(row.date); row.time = excelTime(row.time);
        if (!/^([01]?\d|2[0-3]):[0-5]\d(?:\s*[-–—]\s*([01]?\d|2[0-3]):[0-5]\d)?$/.test(row.time)) throw new Error(`Տող ${index + 2}․ ժամը պետք է լինի 09:00 կամ 09:00–10:20 ձևաչափով։`);
        if (form.elements.kind.value === 'exam' && !validExamDate(row.date)) throw new Error(`Տող ${index + 2}․ քննության համար նշեք վավեր ամսաթիվ՝ YYYY-MM-DD կամ DD.MM.YYYY ձևաչափով։`);
        return row;
      });
      if (!rows.length) throw new Error('Աշխատաթերթը տվյալների տողեր չունի։');
      pending = { id: A.id(), kind: form.elements.kind.value, level: form.elements.level.value, faculty, filename: form.elements.file.files[0].name, rows, publishedAt: new Date().toISOString() };
      $('[data-schedule-preview]').append(A.scheduleTable(rows.slice(0, 100), `${faculty} · ${rows.length} գրառում (նախադիտում՝ մինչև 100)`));
      $('[data-publish-schedule]').hidden = false;
      message('Ստուգեք տվյալները։ Հրապարակումը կփոխարինի այս ֆակուլտետի նույն տեսակի և կրթական մակարդակի նախորդ ժամանակացույցը։');
    } catch (error) { fail(error); }
  });
  function renderUploads() {
    const list = $('[data-upload-list]'); list.replaceChildren();
    const schedules = A.records('schedules');
    if (!schedules.length) list.append(A.node('p', 'Վերբեռնված ժամանակացույցներ դեռ չկան։'));
    schedules.forEach(item => {
      const row = A.node('article', undefined, 'workflow-record');
      row.append(A.node('h3', item.faculty), A.node('p', `${item.kind === 'exam' ? 'Քննագրաֆիկ' : 'Դասացուցակ'} · ${item.level === 'master' ? 'Մագիստրատուրա' : 'Բակալավրիատ'} · ${item.rows.length} գրառում · ${item.filename}`));
      row.append(A.link('Դիտել հրապարակված էջը →', `${item.kind === 'exam' ? 'exam-schedules.html' : 'schedules.html'}?level=${item.level}`));
      list.append(row);
    });
  }
  $('[data-publish-schedule]').addEventListener('click', () => {
    try {
      A.requirePermission('schedules'); if (!pending) throw new Error('Նախ ստեղծեք նախադիտում։');
      const rest = A.records('schedules').filter(item => !(item.faculty === pending.faculty && item.kind === pending.kind && item.level === pending.level));
      A.write('schedules', [...rest, pending]);
      $('[data-publish-schedule]').hidden = true; pending = null; renderUploads();
      A.status($('[data-publish-status]'), 'Ժամանակացույցը հրապարակվել է այս դիտարկիչում։');
    } catch (error) { A.status($('[data-publish-status]'), error.message, true); }
  });
  renderUploads();
})();
