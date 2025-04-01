import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/Components/Home.vue';
import Login from '@/Components/Login.vue';
import Register from '@/Components/Register.vue';
import ProjectView from '@/Components/ProjectView.vue';
import Dashboard from '../components/Dashboard.vue';

const routes = [
  {
        path: '/',
        name: 'home',
        component: Home, 
        meta: { requiresAuth: false }, 
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
  },
  {
    path: '/projects/:id',
    name: 'project-view',
    component: ProjectView,
    props: true, 
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Check if the user is authenticated before accessing the dashboard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  if (to.matched.some(record => record.meta.requiresAuth) && !token) {
    next('/');
  } else {
    next();
  }
});

export default router;
