//Importation
import { createRouter, createWebHistory } from 'vue-router';

//Routes
const routes = [
  {
    path: '/',
    name: 'home-page',
    component: () => import('@v/HomePage.vue'),
  },
  {
    path: '/login',
    name: 'login-screen',
    component: () => import('@v/LoginScreen.vue'),
  },
  {
    path:'/register',
    name: 'register-screen',
    component: () => import('@v/RegisterScreen.vue'),
  },
  {
    path: '/create-store',
    name: 'create-store',
    component: () => import('@v/CreateStore.vue'),
  },
  {
    path: '/dashboard/:userId',
    name: 'dashboard',
    component: () => import('@/views/DashBoard.vue'),
  },
  {
    path: '/404',
    name: 'error-page',
    component: () => import('@v/ErrorPage.vue'),
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/404',
  },
  {
    path: '/get',
    name: 'get',
    component: () => import('@v/TesteGet.vue'),

  },
];

//Router instance
const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;