// Import CSS Tailwind v4 (wajib supaya Vite bundling CSS)
import '../css/app.css';

import './bootstrap';
import Alpine from 'alpinejs';

// Import Haptic Feedback untuk mobile experience yang lebih satisfying
import './haptic-feedback';

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

// Page Transition Loader
(() => {
  const LOADER_ID = 'page-loader';
  const getLoader = () => document.getElementById(LOADER_ID);

  const show = () => {
    const el = getLoader();
    if (!el) return;
    el.classList.remove('hidden');
    if (!el.classList.contains('flex')) el.classList.add('flex');
  };

  const hide = () => {
    const el = getLoader();
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
  };

  const isModifiedClick = (e) => e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0;

  const shouldIgnoreLink = (a, e) => {
    if (!a) return true;
    if (isModifiedClick(e) || e.defaultPrevented) return true;
    const href = a.getAttribute('href') || '';
    if (href.startsWith('#') || href.startsWith('javascript:')) return true;
    if (a.hasAttribute('download')) return true;
    const target = (a.getAttribute('target') || '').toLowerCase();
    if (target && target !== '_self') return true;
    if (a.dataset.noLoader != null) return true;

    try {
      const url = new URL(a.href, window.location.href);
      if (url.origin !== window.location.origin) return true; // external
      const curr = new URL(window.location.href);
      if (url.href === curr.href) return true; // same URL
    } catch {
      return true;
    }
    return false;
  };

  const shouldIgnoreForm = (form) => {
    if (!form) return true;
    if (form.dataset.noLoader != null) return true;
    const target = (form.getAttribute('target') || '').toLowerCase();
    if (target && target !== '_self') return true;
    return false;
  };

  // Link clicks
  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[href]');
    if (!a || shouldIgnoreLink(a, e)) return;
    show();
  }, true);

  // Form submits
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (shouldIgnoreForm(form)) return;
    show();
  }, true);

  // Address bar / back-forward nav
  window.addEventListener('beforeunload', () => show());

  // Ensure hidden when page is ready or restored from bfcache
  window.addEventListener('load', hide);
  window.addEventListener('pageshow', (e) => { if (e.persisted) hide(); });
})();
