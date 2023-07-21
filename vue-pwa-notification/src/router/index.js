import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import List from '../components/posts/List.vue'
import Create from '../components/posts/Create.vue'
import Edit from '../components/posts/Edit.vue'
import Login from '../components/auth/Login.vue'
import Register from '../components/auth/Register.vue'
import SimpleCreate from '../components/simple/Create.vue'
import SimpleList from '../components/simple/Index.vue'
import SimpleEdit from '../components/simple/Edit.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: {
      requiresAuth: false
    },
  },
  {
    path: '/post/list',
        name: 'post_list',
        component: List, 
        meta: {
          requiresAuth: true
        },
  },
  {
    path: '/post/create',
    name: 'post_create',
    component: Create, 
    meta: {
      requiresAuth: true
    },
  },
  {
    path: '/post/edit/:id?',
    name: 'post_edit',
    component: Edit, 
    meta: {
      requiresAuth: true
    },
  },
  {
    path: '/login',
    name: 'login',
    component: Login, 
    meta: {
      requiresAuth: false
    },
  },
  {
    path: '/register',
    name: 'register',
    component: Register, 
    meta: {
      requiresAuth: false
    },
  },
  {
    path: '/list',
        name: 'simple_list',
        component: SimpleList, 
        meta: {
          requiresAuth: true
        },
  },
  {
    path: '/create',
    name: 'simple_create',
    component: SimpleCreate, 
    meta: {
      requiresAuth: true
    },
  },
  {
    path: '/edit/:id?',
    name: 'simple_edit',
    component: SimpleEdit, 
    meta: {
      requiresAuth: true
    },
  },
]

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !localStorage.getItem('token')) {
    next('/login');
  } else if (to.meta.requiresAuth && localStorage.getItem('token')) {
    next();
  } else {
    next();
  }
});

export default router
