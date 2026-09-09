/* ===========================================================
   TALANTA VOCATIONAL INSTITUTE — Shared Site Chrome
   Injects header (full mega-menu) and footer on every page
   with relative links so the site works when opened locally.
   =========================================================== */

const TOPBAR_HTML = `
<div class="topbar">
  <div class="container">
    <div class="topbar-links">
      <a href="#" class="contact-phone">📞 <span data-contact="phone">0767 42 41 20</span></a>
      <a href="mailto:info@talanta.ac.tz" class="contact-email">✉ <span data-contact="email">info@talanta.ac.tz</span></a>
      <a href="contacts.html" class="contact-address">📍 <span data-contact="address">Mbagala Zakhem, Temeke, Dar es Salaam</span></a>
    </div>
    <div class="lang-switch" id="langSwitch">
      <button data-lang="en" class="active">EN</button>
      <button data-lang="sw">SW</button>
    </div>
  </div>
</div>`;

const HEADER_HTML = `
<header class="site-header">
  <div class="container nav-row">
    <a href="index.html" class="brand">
      <img src="images/talanta-logo.png" alt="Talanta Vocational Institute logo" data-emphasis>
      <span class="brand-text">
        <span class="name">TALANTA</span><br>
        <span class="tag">Skills for Life!</span>
      </span>
    </a>

    <nav class="main-nav" id="mainNav">
      <ul>
        <li><a href="index.html">Home</a></li>

        <li class="has-dropdown">
          <button>About Us <span class="chevron">▾</span></button>
          <div class="dropdown mega">
            <a href="about.html#history">Our History &amp; Establishment</a>
            <a href="about.html#vision-mission">Vision, Mission &amp; Objectives</a>
            <a href="about.html#core-values">Core Values &amp; Goals</a>
            <a href="about.html#structure">Organization Structure</a>
            <a href="about.html#leadership">Leaders &amp; Management Team</a>
            <a href="about.html#advisory-board">Advisory Board Members</a>
            <a href="about.html#staff">Staff List &amp; Profiles</a>
            <a href="about.html#policies">Talanta Guidelines &amp; Policies</a>
            <a href="about.html#strategic-plan">Strategic Plan 2026–2030</a>
            <a href="about.html#accreditation">Accreditations</a>
            <a href="about.html#quality">Our Quality Standards</a>
            <a href="policies.html">All 50 Institutional Policies</a>
             <a href="documentary-gallery.html">Documentary Gallery</a>
             <a href="about.html#gallery">Documentary Gallery</a>
          </div>
        </li>

        <li class="has-dropdown">
          <button>Explore Us <span class="chevron">▾</span></button>
          <div class="dropdown">
            <a href="explore.html#ceo-message">Message from the CEO</a>
            <a href="explore.html#principal-message">Message from the Principal</a>
            <a href="explore.html#leadership">Leadership &amp; Management</a>
            <a href="explore.html#internationalization">Internationalization</a>
            <a href="explore.html#awards">Awards &amp; Achievements</a>
            <a href="explore.html#sports">Sports, Games &amp; Leisure</a>
            <a href="explore.html#international-students">International Students</a>
          </div>
        </li>

        <li class="has-dropdown">
          <button>Why Talanta <span class="chevron">▾</span></button>
          <div class="dropdown">
            <a href="why-study.html#unique">Our Unique Aspects</a>
            <a href="why-study.html#benefits">Academic &amp; Career Benefits</a>
            <a href="academics.html">Available Programmes</a>
            <a href="why-study.html#career">Career Prospects</a>
            <a href="why-study.html#campus-life">Campus Life</a>
            <a href="why-study.html#testimonials">Testimonials</a>
            <a href="why-study.html#financial-aid">Financial Aid</a>
          </div>
        </li>

        <li class="has-dropdown">
          <button>Academics <span class="chevron">▾</span></button>
          <div class="dropdown mega">
            <a href="academics.html#programs">Programmes Available</a>
            <a href="academics.html#how-to-apply">How to Apply</a>
            <a href="academics.html#fees">Fees Structure &amp; Payment</a>
            <a href="academics.html#calendar">Academic Calendar</a>
            <a href="academics.html#catalog">Course Catalog</a>
            <a href="academics.html#outcomes">Learning Outcomes</a>
            <a href="academics.html#resources">Student Resources</a>
            <a href="academics.html#forms">Policies &amp; Forms</a>
          </div>
        </li>

        <li class="has-dropdown">
          <button>Admissions <span class="chevron">▾</span></button>
          <div class="dropdown mega">
            <a href="admissions.html#requirements">Entry Requirements</a>
            <a href="admissions.html#apply">Online Application</a>
            <a href="admissions.html#fees">Course Fees</a>
            <a href="admissions.html#deadlines">Deadlines</a>
            <a href="admissions.html#guide">Student's Guide</a>
            <a href="admissions.html#faq">FAQ Section</a>
            <a href="admissions.html#prospectus">Prospectus</a>
            <a href="admissions.html#results">Admission Results</a>
          </div>
        </li>

        <li class="has-dropdown">
          <button>Services <span class="chevron">▾</span></button>
          <div class="dropdown mega">
            <a href="ict-services.html">ICT Services &amp; Systems</a>
            <a href="library.html">Library Services</a>
            <a href="research.html">Research &amp; Innovation</a>
            <a href="consultancy.html">Consultancy Services</a>
            <a href="offices.html">Offices &amp; Facilities</a>
            <a href="students-life.html">Student Life</a>
          </div>
        </li>

        <li class="has-dropdown">
          <button>News <span class="chevron">▾</span></button>
          <div class="dropdown">
            <a href="news.html#institute-news">Institute News</a>
            <a href="news.html#events">Events Calendar</a>
            <a href="news.html#gallery">Photo &amp; Video Gallery</a>
            <a href="news.html#press">Press Releases</a>
            <a href="news.html#newsletter">Newsletter Subscription</a>
          </div>
        </li>

        <li class="has-dropdown">
          <button>Community <span class="chevron">▾</span></button>
          <div class="dropdown">
            <a href="alumni.html">Alumni Portal</a>
            <a href="collaborations.html">Collaborations &amp; Partners</a>
            <a href="students-life.html#international">International Students</a>
          </div>
        </li>

        <li><a href="contacts.html">Contacts</a></li>
        <li><a href="https://talanta.ac.tz:2096/" target="_blank" rel="noopener" class="cta-link" data-emphasis>Webmail</a></li>
        <li><a href="https://wa.me/255767424120" target="_blank" rel="noopener" class="cta-link" data-emphasis>Apply Now</a></li>
      </ul>
    </nav>

    <button class="mobile-toggle" id="mobileToggle" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>`;

const FOOTER_HTML = `
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <img src="images/talanta-logo.png" alt="Talanta logo">
      <p>Bridging Tanzania's skills gap through hands-on vocational training in Mbagala, Dar es Salaam. Registered under NACTVET/TVET.</p>
      <div class="social-row">
        <a href="#" data-provider="facebook" target="_blank" rel="noopener" aria-label="Facebook" class="social-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
            <path fill="#1877F2" d="M22 12.07C22 6.48 17.52 2 11.93 2S2 6.48 2 12.07C2 17.02 5.66 21.16 10.44 21.94v-7.03H8.08v-2.84h2.36V9.41c0-2.33 1.39-3.62 3.52-3.62 1.02 0 2.09.18 2.09.18v2.3h-1.18c-1.16 0-1.52.72-1.52 1.46v1.75h2.59l-.41 2.84h-2.18V21.94C18.34 21.16 22 17.02 22 12.07z"/>
          </svg>
        </a>
        <a href="#" data-provider="instagram" target="_blank" rel="noopener" aria-label="Instagram" class="social-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
            <defs>
              <linearGradient id="instagramGrad" x1="0" x2="1" y1="0" y2="1">
                <stop offset="0%" stop-color="#f58529"/>
                <stop offset="50%" stop-color="#dd2a7b"/>
                <stop offset="100%" stop-color="#8134af"/>
              </linearGradient>
            </defs>
            <rect x="3" y="3" width="18" height="18" rx="5" fill="url(#instagramGrad)" />
            <circle cx="12" cy="12" r="3.4" fill="#ffffff" />
            <circle cx="12" cy="12" r="1.6" fill="#dd2a7b" />
            <circle cx="17" cy="7" r="0.9" fill="#ffffff" />
          </svg>
        </a>
        <a href="#" data-provider="linkedin" target="_blank" rel="noopener" aria-label="LinkedIn" class="social-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
            <path fill="#0A66C2" d="M20.45 20.45h-3.55v-5.4c0-1.29-.02-2.95-1.8-2.95-1.8 0-2.07 1.4-2.07 2.86v5.5H9.44V9h3.41v1.56h.05c.48-.9 1.66-1.85 3.42-1.85 3.66 0 4.34 2.41 4.34 5.55v6.19zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM6.98 20.45H3.7V9h3.28v11.45z"/>
          </svg>
        </a>
        <a href="#" data-provider="youtube" target="_blank" rel="noopener" aria-label="YouTube" class="social-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
            <path fill="#FF0000" d="M23.5 6.2a2.8 2.8 0 0 0-1.96-1.98C19.88 3.6 12 3.6 12 3.6s-7.88 0-9.54.62A2.8 2.8 0 0 0 .5 6.2 29.3 29.3 0 0 0 0 12a29.3 29.3 0 0 0 .5 5.8 2.8 2.8 0 0 0 1.96 1.98C4.12 20.4 12 20.4 12 20.4s7.88 0 9.54-.62a2.8 2.8 0 0 0 1.96-1.98A29.3 29.3 0 0 0 24 12a29.3 29.3 0 0 0-.5-5.8z"/>
            <path fill="#FFFFFF" d="M9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
          </svg>
        </a>
        <a href="#" data-provider="twitter" target="_blank" rel="noopener" aria-label="X / Twitter" class="social-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
            <path fill="#1DA1F2" d="M22.46 6c-.77.35-1.6.59-2.46.7a4.2 4.2 0 0 0-7.16 3.83A11.94 11.94 0 0 1 3.15 4.6a4.2 4.2 0 0 0 1.3 5.6c-.64-.02-1.26-.2-1.79-.5v.05c0 2.08 1.48 3.82 3.45 4.22-.36.1-.74.15-1.13.15-.28 0-.55-.03-.81-.08.56 1.76 2.17 3.04 4.08 3.08A8.44 8.44 0 0 1 2 19.54a11.9 11.9 0 0 0 6.29 1.84c7.55 0 11.68-6.26 11.68-11.68l-.01-.53A8.18 8.18 0 0 0 22.46 6z"/>
          </svg>
        </a>
        <a href="https://wa.me/255767424120" data-provider="whatsapp" target="_blank" rel="noopener" aria-label="WhatsApp" class="social-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
            <path fill="#25D366" d="M20.52 3.48A11.94 11.94 0 0 0 12 0C5.37 0 .03 5.34.03 12c0 2.11.55 4.09 1.6 5.86L0 24l6.35-1.67A11.95 11.95 0 0 0 12 24c6.63 0 11.97-5.34 11.97-12 0-1.95-.42-3.79-1.45-5.52zM12 21.5c-1.7 0-3.36-.44-4.8-1.26l-.34-.2-3.77 1 .99-3.66-.22-.37A8.6 8.6 0 0 1 3.5 12c0-4.7 3.82-8.5 8.5-8.5 4.69 0 8.5 3.8 8.5 8.5 0 4.7-3.81 8.5-8.5 8.5z"/>
            <path fill="#ffffff" d="M16.4 14.6c-.24-.12-1.44-.71-1.66-.79-.22-.08-.38-.12-.54.12-.16.24-.6.79-.73.95-.13.16-.26.18-.5.06-.24-.12-1.02-.38-1.94-1.2-.72-.64-1.2-1.44-1.34-1.7-.14-.26-.015-.4.1-.52.1-.1.24-.26.36-.39.12-.13.16-.22.24-.36.08-.13.04-.24-.02-.36-.06-.12-.54-1.3-.74-1.77-.2-.47-.41-.4-.55-.4-.14 0-.31-.02-.48-.02-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.72 2.64 4.16 3.7 2.44 1.06 2.44.7 2.88.66.44-.04 1.44-.66 1.64-1.3.2-.64.2-1.18.14-1.3-.06-.12-.22-.18-.46-.3z"/>
          </svg>
        </a>
      </div>
    </div>
    <div>
      <h4>Institute</h4>
      <a href="about.html">About Us</a>
      <a href="explore.html">Explore Us</a>
      <a href="why-study.html">Why Study at Talanta</a>
      <a href="academics.html">Academics</a>
      <a href="admissions.html">Admissions</a>
      <a href="news.html">News &amp; Events</a>
    </div>
    <div>
      <h4>Services</h4>
      <a href="ict-services.html">ICT Services</a>
      <a href="library.html">Library Services</a>
      <a href="research.html">Research &amp; Innovation</a>
      <a href="consultancy.html">Consultancy</a>
      <a href="alumni.html">Alumni Portal</a>
      <a href="collaborations.html">Collaborations</a>
    </div>
    <div>
      <h4>Quick Links</h4>
      <a href="https://talanta.ac.tz:2096/" target="_blank" rel="noopener">Webmail Login ↗</a>
      <a href="https://www.heslb.go.tz" target="_blank" rel="noopener">HESLB ↗</a>
      <a href="https://www.nacte.go.tz" target="_blank" rel="noopener">NACTE ↗</a>
      <a href="https://necta.go.tz" target="_blank" rel="noopener">NECTA ↗</a>
      <a href="https://www.tcu.go.tz" target="_blank" rel="noopener">TCU ↗</a>
      <a href="contacts.html">All Quick Links →</a>
    </div>
  </div>
  <div class="container">
    <div class="visitor-counter" id="visitorCounter">
      <span>TODAY: <b id="vc-today">--</b></span>
      <span>THIS WEEK: <b id="vc-week">--</b></span>
      <span>ONLINE NOW: <b id="vc-online">--</b></span>
    </div>
  </div>
  <div class="container footer-bottom">
    <span>© 2026 Talanta Vocational Institute. All rights reserved.</span>
    <span>P.O. Box 13356, Dar es Salaam · Registered under NACTVET/TVET</span>
  </div>
</footer>

<a href="https://wa.me/255767424120" class="wa-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">💬</a>
<div class="chat-bubble" id="chatBubble"><span class="dot"></span> Live Chat — Ask us anything</div>
`;

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('topbarMount').outerHTML = TOPBAR_HTML;
  document.getElementById('headerMount').outerHTML = HEADER_HTML;
  document.body.insertAdjacentHTML('beforeend', FOOTER_HTML);

  // Wire footer social links from data/contact.json when available
  fetch('/data/contact.json').then(r => r.ok ? r.json() : null).then(cfg => {
    if (!cfg) return;
    const map = {
      facebook: cfg.facebook || '',
      instagram: cfg.instagram || '',
      linkedin: cfg.linkedin || '',
      youtube: cfg.youtube || '',
      twitter: cfg.twitter || '',
      whatsapp: cfg.whatsapp ? ('https://wa.me/' + cfg.whatsapp.replace(/[^0-9]/g, '')) : (cfg.whatsapp_link || '')
    };
    document.querySelectorAll('.social-row a.social-icon').forEach(a => {
      const p = a.dataset.provider;
      if (p && map[p]) {
        a.href = map[p];
        if (p === 'whatsapp') {
          // also wire floating WA button
          const wa = document.querySelector('.wa-float');
          if (wa) wa.href = map[p];
        }
      }
    });
    // Wire topbar phone/email/address
    if (cfg.phone) {
      const ph = document.querySelector('.topbar .contact-phone');
      if (ph) { ph.href = 'tel:+' + cfg.phone.replace(/[^0-9]/g,''); ph.querySelector('[data-contact="phone"]').textContent = cfg.phone; }
      const wa = document.querySelector('.wa-float'); if (wa && cfg.whatsapp) wa.href = 'https://wa.me/' + cfg.whatsapp.replace(/[^0-9]/g,'');
    }
    if (cfg.email) {
      const em = document.querySelector('.topbar .contact-email');
      if (em) { em.href = 'mailto:' + cfg.email; em.querySelector('[data-contact="email"]').textContent = cfg.email; }
    }
    if (cfg.address) {
      const ad = document.querySelector('.topbar .contact-address');
      if (ad) ad.querySelector('[data-contact="address"]').textContent = cfg.address;
    }
  }).catch(()=>{});

  // Mobile menu toggle
  // Load site enhancements (gallery, emphasis, reveal) if available
    const enh = document.createElement('script'); enh.src = 'js/site-enhancements.js'; enh.defer = true; document.body.appendChild(enh);
  const toggle = document.getElementById('mobileToggle');
  const nav = document.getElementById('mainNav');
  toggle.addEventListener('click', () => {
    nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
    nav.style.flexDirection = 'column';
    nav.style.position = 'absolute';
    nav.style.top = '100%';
    nav.style.left = '0';
    nav.style.right = '0';
    nav.style.background = '#fff';
    nav.style.padding = '10px';
    nav.style.boxShadow = '0 12px 24px rgba(0,0,0,.12)';
  });

  // Mobile dropdown tap-to-open
  document.querySelectorAll('.has-dropdown > button').forEach(btn => {
    btn.addEventListener('click', (e) => {
      if (window.innerWidth <= 980) {
        e.preventDefault();
        btn.parentElement.classList.toggle('open');
      }
    });
  });

  // Language switch (EN/SW) — simple visual toggle, ready for full i18n wiring later
  document.querySelectorAll('#langSwitch button').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#langSwitch button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.documentElement.setAttribute('lang', btn.dataset.lang);
      if (btn.dataset.lang === 'sw') {
        alert('Kiswahili: Tafsiri kamili ya tovuti inakamilishwa. Bado unaweza kuvinjari kwa Kiingereza.');
      }
    });
  });

  // Live visitor counter (illustrative — swap for real analytics once deployed)
  const seed = { today: 47, week: 312, online: 3 };
  document.getElementById('vc-today').textContent = seed.today;
  document.getElementById('vc-week').textContent = seed.week;
  document.getElementById('vc-online').textContent = seed.online;
  setInterval(() => {
    const el = document.getElementById('vc-online');
    const val = Math.max(1, seed.online + Math.round(Math.random()*2-1));
    el.textContent = val;
  }, 6000);

  // Live chat bubble — opens WhatsApp for now (placeholder for full widget later)
  document.getElementById('chatBubble').addEventListener('click', () => {
    window.open('https://wa.me/255767424120', '_blank');
  });

  // Highlight current nav section
  const path = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.main-nav a[href$="'+path+'"]').forEach(a => a.style.fontWeight='800');
});
