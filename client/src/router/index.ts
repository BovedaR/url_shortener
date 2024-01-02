import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/urls',
      name: 'urls',
      component: () => import('../views/UrlList.vue'),
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/urls/form/:id?',
      name: 'urls-form',
      component: () => import('../views/UrlForm.vue'),
      props: true,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/urlcollections/form/:id?',
      name: 'urlcollections-form',
      component: () => import('../views/UrlCollectionForm.vue'),
      props: true,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginPage.vue'),
      meta: {
        hideNavigation: true
      }
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/urls'
    }
  ]
})

router.beforeEach((to, _from, next) => {
  if (to.meta.requiresAuth && !localStorage.getItem('token')) next('/login');
  next();
});


export default router
