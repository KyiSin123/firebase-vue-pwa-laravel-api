importScripts('/js/umd.js');

const { openDB } = self.idb;
const CACHE_NAME = "pre-cache";
const API_CACHE_NAME = "runtime-cache";

const API_URLS = ["/api", "/storage/uploads", "/store"];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(["/", "/index.html", "/manifest.json"]);
    })
  );
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME && cache !== API_CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

self.addEventListener("fetch", (event) => {
  const { request } = event;

  // Check if the request is an API request
  if (API_URLS.some((url) => request.url.includes(url))) {
      event.respondWith(fetchAndCache(request));
  } 
  else {
    event.respondWith(cacheFirst(request));
  }
});

async function fetchAndCache(request) {
  const cache = await caches.open(API_CACHE_NAME);
  try {
    const response = await fetch(request);
    cache.put(request, response.clone());
    return response;
  } catch (error) {
    const cachedResponse = await cache.match(request);
    return (
      cachedResponse ||
      new Response(null, { status: 404, statusText: "Not found" })
    );
  }
}

async function cacheFirst(request) {
  const cacheResponse = await caches.match(request);
  return cacheResponse || fetch(request);
}

self.addEventListener('sync', event => {
  if (event.tag == 'sync-posts') {
    event.waitUntil(syncPosts());
  }
})

async function syncPosts() {
  const db = await openDB('offline-database', 1, {
    upgrade(db) {
        if (!db.objectStoreNames.contains('pendingData')) {
            db.createObjectStore('pendingData', { keyPath: 'id' });
        }
    },
  });
  // const database = await openDB('offline-deleted-posts-database', 1, {
  //   upgrade(db) {
  //       db.createObjectStore('deletingData', { keyPath: 'id' });
  //   },
  // });
  const posts = await db.getAll('pendingData');
  // const deletedPosts = await database.getAll('deletingData');
  const postsIdsToRemove = [];
  const deletedIdsToRemove = [];
  // const token = await getTokenFromIndexedDB();
  // if(posts != null) {
    let i = 0;
    await Promise.all(posts.map(async ({ method, ...post }) => {
      console.log(i);
      try {
        const response = await fetch('http://127.0.0.1:8000/api/simple', {
          method: method,
          body: JSON.stringify(post),
          headers: {
            'Content-Type': 'application/json',
            Authorization: 'Bearer 32|OXfn3ypFRUC0HSmLX3VaYDbGNeYJkoMTCAvL2IHq',
          }
        });
        if (response.ok) {
          console.log('ok');
          postsIdsToRemove.push(post.id);
          // i = i + 1;
          // postsIdsToRemove.push(i);
            // await deleteDB('offline-database');
        }
      } catch (error) { console.log('sync error', error) }
    }));
    const tx = db.transaction('pendingData', 'readwrite');
    for (const id of postsIdsToRemove) {
      tx.store.delete(id);
    }
    await tx.done;
  // } 
  // if(deletedPosts != null) {
  //   await Promise.all(deletedPosts.map(async ({method, ...post}) => {
  //     try {
  //       const response = await fetch(`http://127.0.0.1:8000/api/simple/${post.id}`, {
  //         method: method,
  //         headers: {
  //           'Content-Type': 'application/json',
  //           Authorization: `Bearer ${token}`,
  //         }
  //       });
  //       if (response.ok) {
  //         deletedIdsToRemove.push(post.id);
  //       }
  //     } catch (error) { 
  //       console.log('sync error', error) 
  //     }
  //   }));
  //   const tx = database.transaction('deletingData', 'readwrite');
  //   for (const id of deletedIdsToRemove) {
  //     tx.store.delete(id);
  //   }
  //   await tx.done;
  // }
}

async function getTokenFromIndexedDB() {
  const db = await openDB('auth-database', 1);
  const tx = db.transaction('auth', 'readonly');
  const store = tx.objectStore('auth');
  const token = await store.get('token');
  return token;
}
