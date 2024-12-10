import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/Quiz',
    },
    {
      path: '/Quiz',
      name: 'Quiz',
      meta: { title: 'Quiz', middleware: [] },
      component: () => import('@/views/Quiz.vue'),
    },
    {
      path: '/Summary',
      name: 'Summary',
      meta: { title: 'Summary', middleware: [] },
      component: () => import('@/views/Summary.vue'),
    },
    {
      path: '/Activity',
      name: 'Activity',
      meta: { title: 'Activity', middleware: ['auth'] },
      component: () => import('@/views/Activity.vue'),
    },
    {
      path: '/Response',
      name: 'Response',
      meta: { title: 'Response', middleware: ['auth'] },
      component: () => import('@/views/Response.vue'),
    },

  ],
})

router.beforeEach(async (to, from, next) => {
  document.title = to.meta.title + ' :: ' + import.meta.env.VITE_APP_NAME;

  const auth = useAuthStore();
  if (!auth.isLoggedIn) {
    await auth.fetchUser();
  }

  if (to.meta.middleware.includes('guest') && auth.isLoggedIn) next({ name: 'Activity' });
  else if (
    to.meta.middleware.includes('verified') &&
    auth.isLoggedIn &&
    !auth.user.email_verified_at
  ) next({ name: 'verify-email' });
  else if (to.meta.middleware.includes('auth') && !auth.isLoggedIn) next({ name: 'Quiz' });
  else next();


  
});


export default router
