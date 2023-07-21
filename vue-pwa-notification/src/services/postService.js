// export default {
//     const API_URL = 'http://127.0.0.1:8000/api/simple',

//     async createPost(post) {
//       return fetch(PostService.API_URL, {
//         method: 'POST',
//         body: JSON.stringify({
//           id: post.id,
//           title: post.title,
//           body: post.body,
//         }),
//         headers: {
//           'Content-Type': 'application/json',
//            Authorization: 'Bearer ' + localStorage.getItem('token')
//         }
//       }).catch(error => {
//         if (error.response && error.response.status === 422) {
//             throw new Error(JSON.stringify(error.response.data.errors));
//           } else {
//             this.savePostInOffline(post, 'POST');
//             throw error;
//           }
//       });
//     },
  
//     async updatePost(post) {
//       return fetch(PostService.API_URL, {
//         method: 'PUT',
//         body: JSON.stringify({
//             id: post.id,
//             title: post.title,
//             body: post.body,
//         }),
//         headers: {
//           'Content-Type': 'application/json',
//            Authorization: 'Bearer ' + localStorage.getItem('token')
//         }
//       }).catch(error => {
//         this.savePostInOffline(post, 'PATCH');
//         throw error;
//       });
//     }

//     async savePostInOffline(post, method) {
//         console.log(data);
//         const db = await this.openTweetsDB();        
//         const tx = db.transaction('pendingData', 'readwrite');
//         tx.store.put({ ...post, method });
//         await tx.done;
//     }

//     openTweetsDB() {
//         return openDB('offline-database', 1, {
//           upgrade(db) {
//             db.createObjectStore('pendingData', { keyPath: 'id' });
//           }
//         });
//       }
//   }