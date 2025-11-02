// Service Worker for MANCE PWA
const CACHE_NAME = 'mance-v1';
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/manifest.json',
  '/offline.html'
];

// Install event - cache static assets
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
      .catch(err => console.log('Cache error:', err))
  );
  self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log('Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch event - serve from cache when offline
self.addEventListener('fetch', event => {
  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    return;
  }

  const url = new URL(event.request.url);

  // Skip cross-origin requests
  if (url.origin !== location.origin) {
    return;
  }

  // Bypass SW for realtime voice endpoints and telpon page
  if (url.pathname.startsWith('/api/voice') || url.pathname.startsWith('/telpon')) {
    // Let browser hit network directly, no caching/interception
    return;
  }

  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Cache hit - return response
        if (response) {
          return response;
        }

        // Clone the request
        const fetchRequest = event.request.clone();

        return fetch(fetchRequest).then(response => {
          // Check if valid response
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }

          // Clone the response
          const responseToCache = response.clone();

          // Cache dynamic content
          caches.open(CACHE_NAME)
            .then(cache => {
              // Don't cache API responses or auth pages
              const reqUrl = fetchRequest.url;
              if (!reqUrl.includes('/api/') && !reqUrl.includes('/login') && !reqUrl.includes('/register')) {
                cache.put(event.request, responseToCache);
              }
            });

          return response;
        });
      })
      .catch(() => {
        const accept = event.request.headers.get('accept') || '';
        if (accept.includes('text/html')) {
          return caches.match('/offline.html');
        }
        // Return JSON error for non-HTML requests to avoid "Failed to fetch"
        return new Response(JSON.stringify({ error: 'offline', message: 'Network unavailable' }), {
          status: 503,
          headers: { 'Content-Type': 'application/json' }
        });
      })
  );
});

// Background sync for offline actions
self.addEventListener('sync', event => {
  if (event.tag === 'sync-complaints') {
    event.waitUntil(syncComplaints());
  }
});

// Sync offline complaints when back online
async function syncComplaints() {
  try {
    const cache = await caches.open(CACHE_NAME);
    const requests = await cache.keys();

    const offlineRequests = requests.filter(request =>
      request.url.includes('/complaints') && request.method === 'POST'
    );

    for (const request of offlineRequests) {
      try {
        await fetch(request.clone());
        await cache.delete(request);
      } catch (error) {
        console.error('Sync failed for:', request.url);
      }
    }
  } catch (error) {
    console.error('Sync error:', error);
  }
}

// Push notification handler
self.addEventListener('push', event => {
  const options = {
    body: event.data ? event.data.text() : 'Ada notifikasi baru!',
    icon: '/images/icons/icon-192x192.png',
    badge: '/images/icons/badge-72x72.png',
    vibrate: [200, 100, 200],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'view',
        title: 'Lihat',
        icon: '/images/icons/checkmark.png'
      },
      {
        action: 'close',
        title: 'Tutup',
        icon: '/images/icons/xmark.png'
      }
    ]
  };

  event.waitUntil(
    self.registration.showNotification('MANCE', options)
  );
});

// Notification click handler
self.addEventListener('notificationclick', event => {
  event.notification.close();

  if (event.action === 'view') {
    event.waitUntil(
      clients.openWindow('/notifications')
    );
  }
});

// Periodic background sync (for checking new complaints/news)
self.addEventListener('periodicsync', event => {
  if (event.tag === 'check-updates') {
    event.waitUntil(checkForUpdates());
  }
});

async function checkForUpdates() {
  try {
    const response = await fetch('/api/check-updates');
    const data = await response.json();

    if (data.hasUpdates) {
      self.registration.showNotification('MANCE', {
        body: data.message,
        icon: '/images/icons/icon-192x192.png',
        badge: '/images/icons/badge-72x72.png'
      });
    }
  } catch (error) {
    console.error('Update check failed:', error);
  }
}
