// Import CSS Tailwind v4 (wajib supaya Vite bundling CSS)
import '../css/app.css';

import './bootstrap';
import Alpine from 'alpinejs';

// Expose Alpine
window.Alpine = Alpine;
Alpine.start();

// PWA Service Worker Registration
/*
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/service-worker.js')
      .then(() => console.log('ServiceWorker registration successful'))
      .catch(err => console.log('ServiceWorker registration failed: ', err));
  });
}
  */

// Install prompt for PWA
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;

  const installButton = document.getElementById('install-app');
  if (installButton) {
    installButton.style.display = 'block';
    installButton.addEventListener('click', () => {
      deferredPrompt.prompt();
      deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          console.log('User accepted the install prompt');
        }
        deferredPrompt = null;
      });
    });
  }
});

// --- Global Page Loader (3D) ---
// Membuat overlay loader 3D dan menampilkannya setiap akan berpindah halaman.
// Ringan, tanpa dependency tambahan. Disable per-element dengan: data-no-loader="true"

(() => {
  const LOADER_ID = 'page-loader';

  const buildLoaderEl = () => {
    const div = document.createElement('div');
    div.id = LOADER_ID;
    div.className = 'page-loader';
    div.setAttribute('aria-hidden', 'true');
    div.innerHTML = `
      <div class="loader-container">
        <div class="loader-cube">
          <div class="loader-cube__face face--front"></div>
          <div class="loader-cube__face face--back"></div>
          <div class="loader-cube__face face--right"></div>
          <div class="loader-cube__face face--left"></div>
          <div class="loader-cube__face face--top"></div>
          <div class="loader-cube__face face--bottom"></div>
        </div>

        <div class="loader-orbit"></div>

        <div class="loader-text text-gray-800 text-sm tracking-wider">
          Memuat
          <span class="loader-dots">
            <span class="loader-dot"></span>
            <span class="loader-dot"></span>
            <span class="loader-dot"></span>
          </span>
        </div>
      </div>
    `;
    return div;
  };

  const ensureLoader = () => {
    let el = document.getElementById(LOADER_ID);
    if (!el) {
      el = buildLoaderEl();
      document.body.appendChild(el);
    }
    return el;
  };

  const showLoader = () => {
    const el = ensureLoader();
    el.classList.add('active');
  };

  const hideLoader = () => {
    const el = document.getElementById(LOADER_ID);
    if (el) el.classList.remove('active');
  };

  const shouldIgnoreLink = (a) => {
    if (!a) return true;
    const href = a.getAttribute('href');
    if (!href || href.trim() === '' || href.startsWith('#')) return true;
    const target = (a.getAttribute('target') || '').toLowerCase();
    if (target === '_blank') return true;
    if (a.hasAttribute('download')) return true;
    if (href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) return true;
    if (a.dataset && (a.dataset.noLoader === '1' || a.dataset.noLoader === 'true')) return true;
    return false;
  };

  // Tampilkan loader saat klik link biasa
  document.addEventListener('click', (e) => {
    const a = e.target.closest('a');
    if (!a) return;
    if (e.defaultPrevented) return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button === 1) return;
    if (shouldIgnoreLink(a)) return;

    try {
      const nextUrl = new URL(a.href, window.location.href);
      if (nextUrl.href === window.location.href) return; // sama persis
    } catch (_) {
      // ignore parsing error
    }

    showLoader();
  }, true);

  // Tampilkan loader saat submit form
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;
    if (form.hasAttribute('data-no-loader')) return;
    showLoader();
  }, true);

  // Saat halaman akan unload (misal refresh/navigate via browser)
  window.addEventListener('beforeunload', () => {
    showLoader();
  });

  // Jika kembali dari bfcache (Back/Forward), pastikan loader disembunyikan
  window.addEventListener('pageshow', (e) => {
    if (e.persisted) hideLoader();
  });

  // Pastikan tersembunyi setelah halaman selesai load
  window.addEventListener('load', hideLoader);

  // API global opsional
  window.appPageLoader = { show: showLoader, hide: hideLoader };
})();
