
/* Level UP Premium V4 • interações reforçadas inspiradas na sensação visual da PURM */
(()=>{
  'use strict';
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const body = document.body;
  const doc = document.documentElement;

  function addRevealTargets(){
    const selectors=[
      'section','article','.benefits-grid > div','.benefits-row > div','.crm-metrics > div','.crm-col','.crm-ticket','.school-results-band > div','.faq details','.faq-items details','.case-card','.case-photo','.real-photo','.contact-image','.contact-copy','.booking-panel','.school-test-grid > div'
    ];
    document.querySelectorAll(selectors.join(',')).forEach((el)=>{
      if(!el.classList.contains('lu-reveal')) el.classList.add('lu-reveal');
    });
  }

  function scrollUI(){
    const progress=document.createElement('div');
    progress.className='lu-scroll-progress';
    progress.setAttribute('aria-hidden','true');
    body.appendChild(progress);
    const back=document.getElementById('lu-back-to-top');
    let ticking=false;
    const update=()=>{
      const max=Math.max(1,doc.scrollHeight-window.innerHeight);
      const pos=window.scrollY;
      progress.style.transform=`scaleX(${Math.max(0,Math.min(1,pos/max))})`;
      body.classList.toggle('lu-scrolled',pos>18);
      if(back) back.classList.toggle('is-visible',pos>650);
      ticking=false;
    };
    const onScroll=()=>{ if(!ticking){ ticking=true; requestAnimationFrame(update); } };
    addEventListener('scroll',onScroll,{passive:true});
    addEventListener('resize',onScroll,{passive:true});
    update();
    back?.addEventListener('click',()=>scrollTo({top:0,behavior:reduced?'auto':'smooth'}));
  }

  function revealOnScroll(){
    if(reduced || !('IntersectionObserver' in window)) return;
    body.classList.add('lu-motion-ready');
    const targets=[...document.querySelectorAll('.lu-reveal')];
    const io=new IntersectionObserver((entries)=>{
      entries.forEach((entry)=>{
        if(entry.isIntersecting){
          entry.target.classList.add('lu-visible');
          io.unobserve(entry.target);
        }
      });
    },{threshold:.08,rootMargin:'0px 0px -30px 0px'});
    targets.forEach((el,i)=>{el.style.setProperty('--lu-delay',`${(i%6)*55}ms`);io.observe(el);});
  }

  function navHelpers(){
    const menus=[...document.querySelectorAll('.menu,.navlinks,.nav-links')];
    const toggles=[...document.querySelectorAll('.menu-toggle,.mobile-menu')];
    document.addEventListener('keydown',e=>{if(e.key==='Escape'){menus.forEach(m=>m.classList.remove('open'));toggles.forEach(t=>t.setAttribute('aria-expanded','false'));}});
    const links=[...document.querySelectorAll('header nav a[href^="#"],header .navlinks a[href^="#"],header .nav-links a[href^="#"]')];
    const ids=[...new Set(links.map(a=>a.getAttribute('href').slice(1)))];
    if('IntersectionObserver' in window){
      const sections=ids.map(id=>document.getElementById(id)).filter(Boolean);
      const observer=new IntersectionObserver((entries)=>{
        entries.forEach((ent)=>{
          if(ent.isIntersecting){
            links.forEach(a=>a.getAttribute('href')===`#${ent.target.id}` ? a.setAttribute('aria-current','location') : a.removeAttribute('aria-current'));
          }
        });
      },{rootMargin:'-26% 0px -60% 0px',threshold:0});
      sections.forEach(s=>observer.observe(s));
    }
  }

  function tilt(selector, max=7){
    if(reduced) return;
    document.querySelectorAll(selector).forEach((card)=>{
      card.style.transformStyle='preserve-3d';
      let raf=0;
      function move(e){
        const r=card.getBoundingClientRect();
        const px=(e.clientX-r.left)/r.width;
        const py=(e.clientY-r.top)/r.height;
        const rx=(0.5-py)*max;
        const ry=(px-0.5)*max;
        cancelAnimationFrame(raf);
        raf=requestAnimationFrame(()=>{ card.style.transform=`perspective(1000px) rotateX(${rx.toFixed(2)}deg) rotateY(${ry.toFixed(2)}deg) translateY(-4px)`; });
      }
      function leave(){ cancelAnimationFrame(raf); card.style.transform=''; }
      card.addEventListener('pointermove',move);
      card.addEventListener('pointerleave',leave);
    });
  }


  function setupSchoolHeroType(){
    const title=document.querySelector('.theme-school .school-hero h1');
    if(!title || title.dataset.fxReady==='1') return;
    title.dataset.fxReady='1';
    const html = title.innerHTML
      .replace(/<br\s*\/?>/gi,'\n')
      .replace(/<em>(.*?)<\/em>/gi,'<span class="accent">$1</span>');
    const div=document.createElement('div');
    div.innerHTML=html;
    const raw=(div.textContent||'').trim();
    const lines=raw.split(/\n+/).map(s=>s.trim()).filter(Boolean);
    title.innerHTML='';
    lines.forEach((line,i)=>{
      const isAccent=/em 2027\.?$/i.test(line) || /^em 2027\.?$/i.test(line);
      const span=document.createElement('span');
      span.className='line';
      const inner=document.createElement('span');
      inner.className='inner';
      inner.textContent=line;
      inner.style.setProperty('--line-delay',`${150 + i*180}ms`);
      if(i===lines.length-1 && /2027/.test(line)) inner.innerHTML = line.replace(/(em 2027\.?)/i,'<em>$1</em>');
      span.appendChild(inner);
      title.appendChild(span);
    });
  }

  function magneticButtons(){
    if(reduced) return;
    document.querySelectorAll('.btn,.button,.booking-next,.booking-back').forEach((btn)=>{
      let raf=0;
      const force=10;
      btn.addEventListener('pointermove',(e)=>{
        const r=btn.getBoundingClientRect();
        const x=((e.clientX-r.left)/r.width - .5)*force;
        const y=((e.clientY-r.top)/r.height - .5)*force;
        cancelAnimationFrame(raf);
        raf=requestAnimationFrame(()=>{ btn.style.transform=`translate(${x.toFixed(1)}px,${y.toFixed(1)}px)`; });
      });
      btn.addEventListener('pointerleave',()=>{ cancelAnimationFrame(raf); btn.style.transform=''; });
    });
  }

  setupSchoolHeroType();
  addRevealTargets();
  scrollUI();
  revealOnScroll();
  navHelpers();
  tilt('.benefits-grid > div, .benefits-row > div, .school-service-grid article, .real-service-grid article, .case-card, .crm-screen, .booking-panel', 5);
  tilt('.hero-main, .hero-side, .hero-media, .real-photo, .case-photo, .contact-image', 3.5);
  magneticButtons();
})();
