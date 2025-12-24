<template>
  <v-container class="d-flex justify-center login">
    <v-card class="pa-6" width="400">
      <v-card-title class="text-h5 text-center mb-4">
        Sign in
      </v-card-title>

      <v-card-text>
        <v-form @submit.prevent="onSubmit">
          <v-text-field v-model="email" label="Email" prepend-icon="mdi-account"
            outlined dense required
          />

          <v-text-field v-model="password" label="Password" type="password" prepend-icon="mdi-lock"
            outlined dense required
          />

          <v-btn type="submit" color="secondary" block class="mt-4">
            Sign in
          </v-btn>
        </v-form>

        <!-- Error message -->
        <v-alert v-if="errorMessage" type="error" class="mt-4" border="start" variant="tonal"
          closable
        >
          {{ errorMessage }}
        </v-alert>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import api from '@/plugins/axios'
import router from "@/router/index.ts";
import {saveLoginData} from "@/utils/auth.js";

const email = ref('')
const password = ref('')
const errorMessage = ref('')

async function onSubmit() {
  errorMessage.value = '' // clear previous error

  try {
    const response = await api.post(`/api/login`, {
      email: email.value,
      password: password.value
    })

    const data = response.data

    if (data.success === true) {
      saveLoginData(data)
      await router.push('/users')
    } else {
      errorMessage.value = data.message || 'Login failed.'
    }
  } catch (error: any) {
    errorMessage.value = error.message
  }
}
</script>
