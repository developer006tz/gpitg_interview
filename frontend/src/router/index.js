import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/products',
      name: 'ProductIndex',
      component: () => import('../views/products/ProductIndex.vue')
    },
    {
      path: '/products/create',
      name: 'ProductCreate',
      component: () => import('../views/products/ProductCreate.vue')
    },
    {
      path: '/products/:id/edit',
      name: 'ProductEdit',
      component: () => import('../views/products/ProductEdit.vue')
    }
  ]
})

export default router
