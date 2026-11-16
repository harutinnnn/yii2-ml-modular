/* Local prototype workflows. A server must enforce identity and permissions in production. */
(() => {
  'use strict';
  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];
  const prefix = 'asue.review.v1.';
  const roles = {
    student: { id: 'student-demo', home: 'student-dashboard.html', permissions: ['request'] },
    teacher: { id: 'teacher-demo', home: 'teacher-dashboard.html', permissions: ['submit-publication', 'edit-cv'] },
    admin: { id: 'admin-demo', home: 'admin-dashboard.html', permissions: ['quick-links', 'schedules'] },
    reviewer: { id: 'reviewer-demo', home: 'admin-dashboard.html', permissions: ['approve-publication'] }
  };
  const read = (key, fallback) => {
    try { const value = JSON.parse(localStorage.getItem(prefix + key)); return value === null ? fallback : value; }
    catch { return fallback; }
  };
  const write = (key, value) => localStorage.setItem(prefix + key, JSON.stringify(value));
  const records = key => { const value = read(key, []); return Array.isArray(value) ? value : []; };
  const session = () => {
    try { const role = sessionStorage.getItem(prefix + 'role'); return roles[role] ? { role, ...roles[role] } : null; }
    catch { return null; }
  };
  const can = permission => !!session()?.permissions.includes(permission);
  const requirePermission = permission => { if (!can(permission)) throw new Error('Այս գործողության համար անհրաժեշտ է համապատասխան իրավասություն։'); };
  const status = (el, message, error = false) => {
    if (!el) return;
    el.textContent = message; el.dataset.error = String(error);
  };
  const node = (tag, text, className) => {
    const el = document.createElement(tag);
    if (text !== undefined) el.textContent = text;
    if (className) el.className = className;
    return el;
  };
  const link = (text, href) => { const a = node('a', text); a.href = href; return a; };
  const button = (text, action) => { const b = node('button', text, 'btn secondary'); b.type = 'button'; b.addEventListener('click', action); return b; };
  const safeURL = value => {
    try { const url = new URL(value); return ['https:', 'http:'].includes(url.protocol) ? url.href : null; }
    catch { return null; }
  };
  const id = () => crypto.randomUUID();
  const normalized = text => String(text ?? '').normalize('NFC').toLocaleLowerCase().trim();
  const labels = { pending: 'Սպասում է հաստատման', approved: 'Հաստատված', rejected: 'Մերժված' };
  const page = location.pathname.split('/').pop() || 'index.html';
  const teacherId = document.body.dataset.teacherId || 'teacher-demo';
  const guard = () => {
    const allowed = document.body.dataset.private?.split(' ');
    if (!allowed) return true;
    if (!session() || !allowed.includes(session().role)) {
      const preferred = allowed[0] === 'teacher' ? 'teacher-login.html' : allowed[0] === 'student' ? 'student-login.html' : 'admin-login.html';
      location.replace(`${preferred}?next=${encodeURIComponent(page + location.hash)}`);
      return false;
    }
    document.body.dataset.access = 'granted';
    return true;
  };
  if (!guard()) return;
  window.addEventListener('pageshow', guard);
  window.ASUE = { read, write, records, session, can, requirePermission, node, link, button, status, id, normalized };

  if (session()) {
    $$('.header-actions a[href="login.html"]').forEach(a => { a.href = session().home; a.textContent = 'Անձնական էջ'; });
    if (session().role === 'student') $$('a[href="student-login.html"]').forEach(a => { a.href = 'student-dashboard.html'; a.textContent = 'Ուսանողի անձնական էջ'; });
  }

  $$('[data-logout]').forEach(a => a.addEventListener('click', event => {
    event.preventDefault(); sessionStorage.removeItem(prefix + 'role'); location.replace('login.html');
  }));
  $('[data-login]')?.addEventListener('submit', event => {
    event.preventDefault();
    const form = event.currentTarget;
    try {
      const role = form.elements.role.value;
      if (!roles[role]) throw new Error('Ընտրեք օգտվողի տեսակը։');
      sessionStorage.setItem(prefix + 'role', role);
      const requested = new URLSearchParams(location.search).get('next');
      const permitted = {
        student: ['student-dashboard.html'], teacher: ['teacher-dashboard.html'],
        admin: ['admin-dashboard.html', 'schedule-upload.html'], reviewer: ['admin-dashboard.html']
      };
      const destination = requested && permitted[role].includes(requested.split('#')[0]) ? requested : roles[role].home;
      location.assign(destination);
    } catch { status($('[data-status]', form), 'Փորձնական մուտքը պահանջում է դիտարկիչի պահեստի հասանելիություն։', true); }
  });

  // Two genuine tabs, including keyboard and legacy deep-link support.
  const tabs = $$('.profile-tabs [role="tab"]');
  if (tabs.length) {
    const activate = (index, updateHash = false) => {
      tabs.forEach((tab, i) => {
        const active = i === index;
        tab.setAttribute('aria-selected', String(active)); tab.tabIndex = active ? 0 : -1;
        document.getElementById(tab.getAttribute('aria-controls')).hidden = !active;
      });
      if (updateHash) history.replaceState(null, '', index ? '#research' : '#bio');
    };
    tabs.forEach((tab, index) => {
      tab.addEventListener('click', () => activate(index, true));
      tab.addEventListener('keydown', event => {
        let target;
        if (['ArrowRight', 'ArrowLeft'].includes(event.key)) target = 1 - index;
        if (event.key === 'Home') target = 0;
        if (event.key === 'End') target = tabs.length - 1;
        if (target !== undefined) { event.preventDefault(); activate(target, true); tabs[target].focus(); }
      });
    });
    const hashTab = () => activate(['#research', '#publications', '#research-panel'].includes(location.hash) ? 1 : 0);
    hashTab(); window.addEventListener('hashchange', hashTab);
  }
  $$('[data-staff-list]').forEach(list => {
    const extra = [...list.children].slice(5);
    if (!extra.length) return;
    const details = node('details', undefined, 'staff-overflow');
    details.append(node('summary', `Մնացած դասախոսները (${extra.length})`));
    const rest = node('ul', undefined, 'staff-list'); extra.forEach(el => rest.append(el));
    details.append(rest); list.after(details);
  });
  $('[data-directory-filter]')?.addEventListener('input', event => {
    let count = 0;
    $$('.contact-directory tbody tr').forEach(row => {
      row.hidden = !normalized(row.textContent).includes(normalized(event.target.value));
      if (!row.hidden) count++;
    });
    $('[data-directory-empty]').hidden = count !== 0;
  });

  const config = window.ASUE_CONFIG || { quickLinks: [], pages: [] };
  const quickOptions = [...config.pages];
  config.quickLinks.forEach(item => { if (!quickOptions.some(p => p.href === item.href)) quickOptions.push(item); });
  const validQuickLinks = value => Array.isArray(value) && value.length > 0 && value.every(item => item && quickOptions.some(p => p.href === item.href));
  const quickLinks = () => { const stored = read('quickLinks', null); return validQuickLinks(stored) ? stored : config.quickLinks; };
  const renderQuickLinks = () => $$('[data-quick-links]').forEach(container => {
    const footer = !!container.closest('footer');
    container.replaceChildren(node(footer ? 'h4' : 'div', 'Արագ հղումներ', footer ? undefined : 'meta-label'));
    const list = node('div', undefined, footer ? undefined : 'link-list');
    quickLinks().forEach(item => list.append(link(item.label, item.href))); container.append(list);
  });
  renderQuickLinks();
  const quickForm = $('[data-quick-links-editor]');
  const fillQuickEditor = () => {
    if (!quickForm) return;
    const fields = $('[data-quick-link-fields]', quickForm); fields.replaceChildren();
    for (let i = 0; i < 4; i++) {
      const label = node('label', undefined, 'quick-link-row'); label.append(node('span', String(i + 1)));
      const select = node('select'); select.name = `link${i}`; select.required = true;
      quickOptions.forEach(item => select.add(new Option(item.label, item.href)));
      select.value = quickLinks()[i]?.href || config.quickLinks[i].href;
      label.append(select); fields.append(label);
    }
  };
  fillQuickEditor();
  if (quickForm) {
    if (!can('quick-links')) quickForm.closest('section').hidden = true;
    quickForm.addEventListener('submit', event => {
      event.preventDefault();
      try {
        requirePermission('quick-links');
        const selected = $$('select', quickForm).map(select => quickOptions.find(item => item.href === select.value));
        if (new Set(selected.map(item => item.href)).size !== selected.length) throw new Error('Ընտրեք չկրկնվող էջեր։');
        write('quickLinks', selected); renderQuickLinks(); status($('[data-status]', quickForm), 'Արագ հղումները պահպանվել են այս դիտարկիչում։');
      } catch (error) { status($('[data-status]', quickForm), error.message, true); }
    });
    $('[data-reset-quick-links]')?.addEventListener('click', () => {
      try { requirePermission('quick-links'); write('quickLinks', config.quickLinks); fillQuickEditor(); renderQuickLinks(); status($('[data-status]', quickForm), 'Սկզբնական հղումները վերականգնվել են։'); }
      catch (error) { status($('[data-status]', quickForm), error.message, true); }
    });
  }

  const renderRequests = () => {
    const target = $('[data-request-list]'); if (!target) return;
    target.replaceChildren();
    const requests = records('requests').filter(item => item.owner === session()?.id);
    if (!requests.length) target.append(node('p', 'Դիմումներ դեռ չկան։'));
    requests.slice().reverse().forEach(item => {
      const article = node('article', undefined, 'workflow-record');
      article.append(node('h3', item.subject), node('p', `${item.type} · ${new Date(item.createdAt).toLocaleDateString('hy-AM')} · Պահպանված է այս դիտարկիչում`, 'status'), node('p', item.description));
      target.append(article);
    });
  };
  renderRequests();
  $('[data-request-form]')?.addEventListener('submit', event => {
    event.preventDefault(); const form = event.currentTarget;
    try {
      requirePermission('request');
      const data = Object.fromEntries(new FormData(form));
      Object.keys(data).forEach(key => data[key] = data[key].trim());
      if (!data.type || !data.subject || !data.description) throw new Error('Լրացրեք բոլոր դաշտերը։');
      write('requests', [...records('requests'), { ...data, id: id(), owner: session().id, createdAt: new Date().toISOString() }]);
      form.reset(); renderRequests(); status($('[data-status]', form), 'Դիմումը պահպանվել է այս դիտարկիչում։ Իրական ուղարկման ծառայությունը դեռ միացված չէ։');
    } catch (error) { status($('[data-status]', form), error.message, true); }
  });

  const publicationForm = $('[data-publication-form]');
  const renderPublications = () => {
    const all = records('publications');
    $$('[data-publications]').forEach(list => {
      list.replaceChildren();
      const published = all.filter(item => item.owner === teacherId && item.status === 'approved' && item.type === list.dataset.publications);
      if (!published.length) list.append(node('li', 'Հաստատված հրապարակումներ դեռ չկան։'));
      published.forEach(item => {
        const li = node('li'); li.append(node('h4', item.title), node('p', `${item.authors} · ${item.publisher} · ${item.year}`));
        if (item.identifier) li.append(node('p', item.identifier));
        const url = safeURL(item.url); if (url) li.append(link('Բացել հրապարակումը →', url));
        list.append(li);
      });
    });
    const mine = $('[data-my-publications]');
    if (mine) {
      mine.replaceChildren();
      const owned = all.filter(item => item.owner === session()?.id);
      if (!owned.length) mine.append(node('p', 'Ներկայացված նյութեր դեռ չկան։'));
      owned.slice().reverse().forEach(item => {
        const article = node('article', undefined, 'workflow-record');
        article.append(node('h3', item.title), node('p', labels[item.status], 'status'));
        if (item.reason) article.append(node('p', `Մերժման պատճառ՝ ${item.reason}`));
        article.append(button('Խմբագրել և կրկին ներկայացնել', () => {
          for (const [key, value] of Object.entries(item)) if (publicationForm.elements.namedItem(key)) publicationForm.elements.namedItem(key).value = value;
          publicationForm.dataset.editing = item.id;
          publicationForm.elements.title.focus();
          status($('[data-status]', publicationForm), 'Փոփոխությունները կուղարկվեն նոր հաստատման։');
        })); mine.append(article);
      });
    }
    const queue = $('[data-review-queue]');
    if (queue) {
      queue.replaceChildren();
      if (!can('approve-publication')) {
        queue.append(node('p', 'Հաստատման համար մուտք գործեք «Հրապարակումների հաստատող» իրավասությամբ։'));
        return;
      }
      const pending = all.filter(item => item.status === 'pending');
      if (!pending.length) queue.append(node('p', 'Հաստատման սպասող հրապարակումներ չկան։'));
      pending.forEach(item => {
        const article = node('article', undefined, 'workflow-record');
        article.append(node('h3', item.title), node('p', `${item.type === 'book' ? 'Գիրք' : 'Հոդված'} · ${item.authors} · ${item.year} · ${item.publisher}`));
        if (item.identifier) article.append(node('p', item.identifier));
        article.append(node('p', `Ներկայացնող՝ Աննա Հարությունյան · ${item.database}`));
        if (safeURL(item.url)) { const a = link('Դիտել նյութը', safeURL(item.url)); a.target = '_blank'; a.rel = 'noopener noreferrer'; article.append(a); }
        const label = node('label', 'Մերժման պատճառ (պարտադիր մերժման դեպքում)', 'field');
        const reason = node('input'); reason.maxLength = 1000; label.append(reason); article.append(label);
        const actions = node('div', undefined, 'workflow-actions');
        const review = approved => {
          try {
            requirePermission('approve-publication');
            if (!approved && !reason.value.trim()) { reason.focus(); throw new Error('Նշեք մերժման պատճառը։'); }
            const current = records('publications'); const record = current.find(p => p.id === item.id);
            if (!record || record.status !== 'pending' || record.updatedAt !== item.updatedAt) throw new Error('Նյութը փոխվել է։ Թարմացրեք էջը և վերանայեք այն։');
            record.status = approved ? 'approved' : 'rejected'; record.reason = approved ? '' : reason.value.trim();
            record.reviewedBy = session().id; record.reviewedAt = new Date().toISOString();
            write('publications', current); renderPublications();
            status($('[data-review-status]'), approved ? 'Հրապարակումը հաստատվել է և հասանելի է դասախոսի էջում։' : 'Հրապարակումը մերժվել է։ Պատճառը հասանելի է դասախոսին։');
          } catch (error) { status($('[data-review-status]'), error.message, true); }
        };
        actions.append(button('Հաստատել', () => review(true)), button('Մերժել', () => review(false))); article.append(actions); queue.append(article);
      });
    }
  };
  renderPublications();
  publicationForm?.addEventListener('submit', event => {
    event.preventDefault(); const form = event.currentTarget;
    try {
      requirePermission('submit-publication');
      const data = Object.fromEntries(new FormData(form));
      Object.keys(data).forEach(key => data[key] = data[key].trim());
      if (['title', 'authors', 'type', 'year', 'publisher', 'url'].some(key => !data[key])) throw new Error('Լրացրեք բոլոր պարտադիր դաշտերը։');
      if (!['article', 'book'].includes(data.type) || !Number.isInteger(Number(data.year)) || Number(data.year) < 1900 || Number(data.year) > 2100) throw new Error('Ստուգեք տեսակը և հրապարակման տարին։');
      if (!safeURL(data.url)) throw new Error('Նշեք վավեր http կամ https հղում։');
      const all = records('publications'); const editing = form.dataset.editing;
      const existing = editing ? all.find(item => item.id === editing && item.owner === session().id) : null;
      if (editing && !existing) throw new Error('Նյութը չի գտնվել։ Թարմացրեք էջը։');
      const record = { ...data, id: existing?.id || id(), owner: session().id, status: 'pending', updatedAt: new Date().toISOString() };
      write('publications', [...all.filter(item => item.id !== record.id), record]);
      form.reset(); delete form.dataset.editing; renderPublications();
      status($('[data-status]', form), 'Նյութը պահպանված է և սպասում է լիազորված օգտվողի հաստատմանը։');
    } catch (error) { status($('[data-status]', form), error.message, true); }
  });
  const renderCV = () => {
    const cv = read(`cv.${teacherId}`, {});
    $$('[data-cv]').forEach(el => el.textContent = cv[el.dataset.cv] || 'Չի լրացվել');
  };
  renderCV();
  const cvForm = $('[data-cv-form]');
  if (cvForm) {
    const cv = read('cv.teacher-demo', {});
    $$('textarea', cvForm).forEach(input => input.value = cv[input.name] || '');
    cvForm.addEventListener('submit', event => {
      event.preventDefault();
      try {
        requirePermission('edit-cv'); const cv = Object.fromEntries(new FormData(cvForm));
        Object.keys(cv).forEach(key => cv[key] = cv[key].trim());
        write('cv.teacher-demo', cv); status($('[data-status]', cvForm), 'Մասնագիտական տվյալները պահպանվել են։');
      } catch (error) { status($('[data-status]', cvForm), error.message, true); }
    });
  }

  const scheduleTable = (rows, caption = '') => {
    const table = node('table', undefined, 'schedule-table');
    if (caption) table.append(node('caption', caption));
    const thead = node('thead'); const tr = node('tr');
    ['Օր / ամսաթիվ', 'Ժամ', 'Խումբ', 'Առարկա', 'Դասախոս', 'Լսարան'].forEach(text => { const th = node('th', text); th.scope = 'col'; tr.append(th); });
    thead.append(tr); table.append(thead); const tbody = node('tbody');
    rows.forEach(row => { const tr = node('tr'); ['date', 'time', 'group', 'subject', 'teacher', 'room'].forEach(key => tr.append(node('td', row[key]))); tbody.append(tr); });
    table.append(tbody); return table;
  };
  window.ASUE.scheduleTable = scheduleTable;
  const scheduleRoot = $('[data-schedule-kind]');
  const renderSchedules = () => {
    if (!scheduleRoot) return;
    const form = $('form', scheduleRoot); const results = $('[data-schedule-results]');
    const all = records('schedules').filter(item => item.kind === scheduleRoot.dataset.scheduleKind);
    const selectedFaculty = form.elements.faculty.value;
    form.elements.faculty.replaceChildren(new Option('Բոլորը', ''));
    [...new Set(all.map(item => item.faculty))].sort().forEach(name => form.elements.faculty.add(new Option(name, name)));
    form.elements.faculty.value = [...form.elements.faculty.options].some(o => o.value === selectedFaculty) ? selectedFaculty : '';
    const { level, faculty, group } = form.elements; let count = 0; results.replaceChildren();
    all.filter(item => (!level.value || item.level === level.value) && (!faculty.value || item.faculty === faculty.value)).forEach(item => {
      const rows = item.rows.filter(row => normalized(row.group).includes(normalized(group.value)));
      count += rows.length;
      if (rows.length) results.append(scheduleTable(rows, `${item.faculty} · ${item.level === 'master' ? 'Մագիստրատուրա' : 'Բակալավրիատ'}`));
    });
    status($('[data-schedule-count]'), count ? `Գտնվել է ${count} գրառում։` : all.length ? 'Ընտրված պայմաններով գրառումներ չկան։' : 'Ժամանակացույց դեռ հրապարակված չէ։');
  };
  if (scheduleRoot) {
    const form = $('form', scheduleRoot); const level = new URLSearchParams(location.search).get('level');
    if (['bachelor', 'master'].includes(level)) form.elements.level.value = level;
    form.addEventListener('input', renderSchedules); form.addEventListener('submit', e => e.preventDefault()); renderSchedules();
  }
  if (session()?.role === 'reviewer' && page === 'admin-dashboard.html') {
    $$('.module-main > section').forEach(section => { if (section.id !== 'publication-review' && !section.classList.contains('module-hero')) section.hidden = true; });
    $$('.module-header nav a').forEach(a => a.hidden = true);
  }

  const searchForm = $('[data-site-search]');
  const renderSearch = () => {
    if (!searchForm || !window.ASUE_SEARCH_INDEX) return;
    const q = normalized(searchForm.elements.q.value); const category = searchForm.elements.category.value;
    const result = window.ASUE_SEARCH_INDEX.filter(item => (!category || item.category === category) && q.split(/\s+/).every(word => normalized(item.title + ' ' + item.text).includes(word)));
    const target = $('[data-search-results]'); target.replaceChildren();
    status($('[data-search-count]'), `Գտնվել է ${result.length} արդյունք։`);
    result.forEach(item => {
      const article = node('article', undefined, 'search-result'); const title = node('h3'); title.append(link(item.title, item.href));
      article.append(node('div', item.category, 'type'), title, node('p', item.text.slice(0, 200) + (item.text.length > 200 ? '…' : '')), link('Բացել →', item.href)); target.append(article);
    });
  };
  if (searchForm) {
    const params = new URLSearchParams(location.search);
    searchForm.elements.q.value = params.get('q') || ''; searchForm.elements.category.value = params.get('category') || '';
    searchForm.addEventListener('submit', event => {
      event.preventDefault(); const params = new URLSearchParams(new FormData(searchForm)); history.replaceState(null, '', `search.html?${params}`); renderSearch();
    });
    searchForm.elements.category.addEventListener('change', renderSearch);
    window.addEventListener('asue-search-ready', renderSearch); renderSearch();
  }
  window.addEventListener('storage', () => { renderPublications(); renderCV(); renderQuickLinks(); renderSchedules(); });
})();
