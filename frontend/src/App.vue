<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink, RouterView } from 'vue-router'
import HeaderTexts from './components/HeaderTexts.vue'

const isLoggedIn = ref(!!localStorage.getItem('token'))

window.addEventListener('auth-changed', () => {
  isLoggedIn.value = !!localStorage.getItem('token')
})
</script>

<template>
  <header>
    <img alt="Chatterboxerino logo" class="logo" src="@/assets/logo.jpg" />

    <div class="wrapper">
      <HeaderTexts msg="Chatterboxerino!" />

      <nav>
        <RouterLink v-if="!isLoggedIn" to="/">Sign in</RouterLink>
        <RouterLink v-if="!isLoggedIn" to="/signup">Join now</RouterLink>

        <RouterLink v-if="isLoggedIn" to="/users">Users list</RouterLink>
        <RouterLink v-if="isLoggedIn" to="/friend-requests">Friend requests</RouterLink>
        <RouterLink v-if="isLoggedIn" to="/messages">Messages</RouterLink>
        <RouterLink v-if="isLoggedIn" to="/logout">Logout</RouterLink>
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
  border: rgba(240, 153, 5, 0.9) 1px solid;
  object-fit: cover;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: var(--color-text);
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
