<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink, RouterView } from 'vue-router'
import HeaderTexts from './components/HeaderTexts.vue'

const isLoggedIn = ref(!!localStorage.getItem('token'))
const username = ref<string | null>(localStorage.getItem('username'))

const syncAuthState = () => {
  isLoggedIn.value = !!localStorage.getItem('token')
  username.value = localStorage.getItem('username')
}

window.addEventListener('auth-changed', syncAuthState)
</script>

<template>
  <header>
    <img alt="Chatterboxerino logo" class="logo" src="@/assets/logo.jpg" />

    <div class="wrapper">
      <HeaderTexts
        msg="Chatterboxerino!"
        :username="username"
      />

      <nav>
        <div v-if="!isLoggedIn">
          <RouterLink to="/">Sign in</RouterLink>
          <RouterLink to="/signup">Join now</RouterLink>
        </div>

        <div v-if="isLoggedIn">
          <RouterLink to="/users">Users list</RouterLink>
          <RouterLink to="/friend-list">Friend list</RouterLink>
          <RouterLink to="/friend-requests">Friend requests</RouterLink>
          <RouterLink to="/messages">Messages</RouterLink>
          <RouterLink to="/logout">Logout</RouterLink>
        </div>
      </nav>
    </div>
  </header>

  <RouterView />
</template>

<style scoped>
header {
  line-height: 1.5;
  max-height: 100vh;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
  width: 125px;
  height: 90px;
  border-radius: 50%;
  border: rgba(var(--primary-item-color--orange), 0.9) 1px solid;
  object-fit: cover;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: var(--primary-item-selected);
  font-weight: bold;
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  border-left: 1px solid var(--color-border);
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;

    padding: 1rem 0;
    margin-top: 1rem;
  }
}
</style>
