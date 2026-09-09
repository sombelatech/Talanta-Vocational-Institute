/* Site enhancements: gallery lightbox, emphasis highlights, scroll reveal */
(function(){
  // Inject minimal CSS
  const css = `
  .gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px}
  .gallery-item{display:block;overflow:hidden;border-radius:6px;border:1px solid var(--line);background:#fff}
  .gallery-item img{width:100%;height:180px;object-fit:cover;display:block;transition:transform .28s}
  .gallery-item figcaption{padding:8px 10px;font-size:13px;color:var(--text-soft);background:transparent}
  .gallery-item{position:relative}
  .gallery-item figcaption{position:relative}
  .gallery-item:hover img{transform:scale(1.04)}
  .lightbox-overlay{position:fixed;inset:0;background:rgba(0,0,0,.85);display:flex;align-items:center;justify-content:center;z-index:9999}
  .lightbox-content{max-width:90%;max-height:90%;position:relative}
  .lightbox-content img{max-width:100%;max-height:100%;display:block;border-radius:6px}
  .lightbox-close{position:absolute;top:-18px;right:-18px;background:#fff;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer}
  .emphasis{animation:empha 1.6s ease-in-out infinite;}
  @keyframes empha{0%{box-shadow:0 0 0 0 rgba(244,168,30,0.0)}50%{box-shadow:0 6px 24px 6px rgba(244,168,30,0.12)}100%{box-shadow:0 0 0 0 rgba(244,168,30,0.0)}}
  .reveal{opacity:0;transform:translateY(18px);transition:opacity .6s ease, transform .6s ease}
  .reveal.visible{opacity:1;transform:none}
  `;
  const s = document.createElement('style'); s.appendChild(document.createTextNode(css)); document.head.appendChild(s);

  // Lightbox
  let lbOverlay = null;
  function openLightbox(src, alt){
    if(lbOverlay) return;
    lbOverlay = document.createElement('div'); lbOverlay.className='lightbox-overlay';
    const content = document.createElement('div'); content.className='lightbox-content';
    const img = document.createElement('img'); img.src = src; img.alt = alt||'';
    const close = document.createElement('button'); close.className='lightbox-close'; close.innerHTML='✕';
    close.addEventListener('click', closeLightbox);
    lbOverlay.addEventListener('click', (e)=>{ if(e.target===lbOverlay) closeLightbox(); });
    content.appendChild(img); content.appendChild(close); lbOverlay.appendChild(content); document.body.appendChild(lbOverlay);
    document.body.style.overflow='hidden';
  }
  function closeLightbox(){ if(!lbOverlay) return; lbOverlay.remove(); lbOverlay=null; document.body.style.overflow=''; }
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeLightbox(); });

  // Attach gallery listeners
  function initGallery(){
    document.querySelectorAll('.gallery-item img').forEach(img=>{
      img.style.cursor='zoom-in';
      img.addEventListener('click', ()=>openLightbox(img.src, img.alt));
      img.setAttribute('loading','lazy');
      // set aria-describedby for caption if present
      const fig = img.closest('figure');
      if(fig){ const cap = fig.querySelector('figcaption'); if(cap){ const id = 'cap-'+Math.random().toString(36).slice(2,9); cap.id = id; img.setAttribute('aria-describedby', id); }}
    });
  }

  // Emphasis helper: data-emphasis attribute toggles pulse on hover
  function initEmphasis(){
    document.querySelectorAll('[data-emphasis]').forEach(el=>{
      el.classList.add('emphasis');
      el.addEventListener('mouseenter', ()=>el.classList.add('emphasis'));
      el.addEventListener('mouseleave', ()=>el.classList.remove('emphasis'));
    });
    // Auto-mark primary CTAs when no explicit data-emphasis is present
    document.querySelectorAll('.btn-gold, .cta-link').forEach(el=>{
      if(!el.hasAttribute('data-emphasis')) el.setAttribute('data-emphasis','');
    });
  }

  // Scroll reveal
  function initReveal(){
    const obs = new IntersectionObserver(entries=>{
      entries.forEach(e=>{ if(e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
    },{threshold:0.12});
    document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
  }

  document.addEventListener('DOMContentLoaded', ()=>{ initGallery(); initEmphasis(); initReveal(); });
})();
