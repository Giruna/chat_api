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
  <v-container class="d-flex justify-center logout">
    <h1>LOGOUT page</h1>
  </v-container>
</template>
