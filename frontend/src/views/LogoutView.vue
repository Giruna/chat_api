<script setup lang="ts">
import {onMounted} from "vue";
import router from "@/router/index.ts";
import {removeLoginData} from "@/utils/auth.js";

onMounted(() => {
  removeLoginData()
  // Redirect to Sign in page
  router.push('/')
})
</script>

<template>
  <v-container class="d-flex justify-center messages">
    <h1>LOGOUT page</h1>
  </v-container>
</template>


<style>
@media (min-width: 1024px) {
  .messages {
    min-height: 100vh;
    display: flex;
  }
}
</style>
