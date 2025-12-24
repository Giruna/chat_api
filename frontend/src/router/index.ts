import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '@/views/LoginView.vue'
import LogoutView from '@/views/LogoutView.vue'
import SignUpView from '@/views/SignUpView.vue'
import UsersView from '@/views/UsersView.vue'
import MessagesView from "@/views/MessagesView.vue"
import FriendRequestView from "@/views/FriendRequestView.vue";
import FriendListView from "@/views/FriendListView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
      meta: { guestOnly: true },
    },
    {
      path: '/signup',
      name: 'signup',
      component: SignUpView,
      meta: { guestOnly: true },
    },
    {
      path: '/logout',
      name: 'logout',
      component: LogoutView,
      meta: { requiresAuth: true },
    },
    {
      path: '/users',
      name: 'users',
      component: UsersView,
      meta: { requiresAuth: true },
    },
    {
      path: '/friend-list',
      name: 'friend-list',
      component: FriendListView,
      meta: { requiresAuth: true },
    },
    {
      path: '/friend-requests',
      name: 'friend-requests',
      component: FriendRequestView,
      meta: { requiresAuth: true },
    },
    {
      path: '/messages/:friendId?',
      name: 'messages',
      component: MessagesView,
      props: true,
      meta: { requiresAuth: true },
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      redirect: () => {
        const isLoggedIn = !!localStorage.getItem('token')

        return isLoggedIn
          ? { name: 'users' }
          : { name: 'login' }
      },
    },
  ],
})

router.beforeEach((to) => {
  const isLoggedIn = !!localStorage.getItem('token')

  if (to.meta.requiresAuth && !isLoggedIn) {
    return { name: 'login' }
  }

  if (to.meta.guestOnly && isLoggedIn) {
    return { name: 'users' }
  }
})

export default router
