(() => {
  const body = document.body;
  const menu = document.querySelector('.menu-panel');
  const open = document.querySelector('[data-menu-open]');
  const close = document.querySelector('[data-menu-close]');
  const search = document.querySelector('.search-overlay');
  const searchOpen = document.querySelector('[data-search-open]');
  const searchClose = document.querySelector('[data-search-close]');

  const toggleMenu = (show) => {
    if (!menu) return;
    menu.classList.toggle('open', show);
    menu.setAttribute('aria-hidden', show ? 'false' : 'true');
    body.classList.toggle('no-scroll', show);
  };
  const toggleSearch = (show) => {
    if (!search) return;
    search.classList.toggle('open', show);
    search.setAttribute('aria-hidden', show ? 'false' : 'true');
    body.classList.toggle('no-scroll', show);
    if (show) setTimeout(() => search.querySelector('input')?.focus(), 50);
  };

  open?.addEventListener('click', () => toggleMenu(true));
  close?.addEventListener('click', () => toggleMenu(false));
  searchOpen?.addEventListener('click', () => toggleSearch(true));
  searchClose?.addEventListener('click', () => toggleSearch(false));
  menu?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => toggleMenu(false)));


  const menuMainItems = [...document.querySelectorAll('.menu-main-item')];
  const menuPanels = [...document.querySelectorAll('.two-step-panel')];
  const activateMenuPanel = (index) => {
    menuMainItems.forEach((item, i) => item.classList.toggle('active', i === index));
    menuPanels.forEach((panel, i) => panel.classList.toggle('active', i === index));
  };
  menuMainItems.forEach((item, index) => {
    item.addEventListener('mouseenter', () => activateMenuPanel(index));
    item.addEventListener('focus', () => activateMenuPanel(index));
    item.addEventListener('click', () => activateMenuPanel(index));
  });

  document.querySelectorAll('.top-nav-item').forEach(item => {
    const link = item.querySelector('.top-nav-link');
    link?.addEventListener('click', (event) => {
      if (window.matchMedia('(hover: none)').matches && window.innerWidth > 1180 && !item.classList.contains('menu-open')) {
        event.preventDefault();
        document.querySelectorAll('.top-nav-item.menu-open').forEach(other => other.classList.remove('menu-open'));
        item.classList.add('menu-open');
      }
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { toggleMenu(false); toggleSearch(false); }
  });

  document.querySelectorAll('[data-scroll]').forEach(btn => {
    btn.addEventListener('click', () => document.querySelector(btn.dataset.scroll)?.scrollIntoView({behavior:'smooth'}));
  });

  const hero = document.querySelector('.slider-hero');
  if (hero) {
    const slides = [...hero.querySelectorAll('.hero-slide')];
    const dots = [...hero.querySelectorAll('.hero-dot')];
    let i = 0, timer;
    const show = (n) => {
      i = (n + slides.length) % slides.length;
      slides.forEach((s,j)=>s.classList.toggle('active',j===i));
      dots.forEach((d,j)=>{d.classList.toggle('active',j===i); d.setAttribute('aria-current',j===i?'true':'false');});
    };
    const start = () => { clearInterval(timer); timer=setInterval(()=>show(i+1),6500); };
    dots.forEach((d,j)=>d.addEventListener('click',()=>{show(j);start();}));
    show(0); start();
  }

  const reveal = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, {threshold:.12});
  document.querySelectorAll('.reveal').forEach(el => reveal.observe(el));
})();
