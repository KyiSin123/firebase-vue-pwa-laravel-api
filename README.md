# Vue PWA With Firebase and Laravel API

## About 

This is simple post CRUD using Vue js 3 for testing PWA with firebase and laravel api.

## Installation

Clone the repo locally:
```
git clone https://github.com/KyiSin-SCM/firebase-vue-laravel.git
```

## For generating api using Laravel
### Requirements
- PHP 8.0
- MySQL 8

`cd` into bakcend and install dependencies. Run below command one by one.
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

### Configuration in `.env` file

Database **eg.**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vue_pwa
DB_USERNAME=root
DB_PASSWORD=
```

Generate FCM(Firebase Cloud Messaging) key and sender id in this link.
https://firebase.google.com/
```
FCM_SERVER_KEY=
FCM_SENDER_ID=
```

## Database Migration

Run database migrations:
```
php artisan migrate
```

Run database seeder:
```
php artisan db:seed
```

## Run Server

Run the dev server:
```
php artisan serve
```

## Client environment set up(Vue)

In firebase-messaging-sw.js and App.vue, use your firebase app config.

```bash
const firebaseConfig = {
    apiKey:
    authDomain:
    projectId:
    storageBucket:
    messagingSenderId:
    appId:
    measurementId:
};
```

`cd` into vue-pwa-notification and install dependencies.

```
npm install
```

## Run client server in production mode(To check service worker)

```
npm run build
```

```
npx http-server dist/
```

Visit below url:
```
http://127.0.0.1:8081
```
