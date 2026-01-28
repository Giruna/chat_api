<template>
  <v-container class="d-flex justify-center signup">
    <v-card class="pa-6" width="400">
      <v-card-title class="text-h5 text-center mb-4">
        Join now
      </v-card-title>

      <v-card-text>
        <v-form @submit.prevent="onSubmit">
          <v-text-field v-model="name" label="Name" prepend-icon="mdi-account"
            outlined dense required
          />

          <v-text-field v-model="email" label="Email" prepend-icon="mdi-account"
            outlined dense required
          />

          <v-text-field v-model="password" label="Password" type="password" prepend-icon="mdi-lock"
            outlined dense required
          />

          <v-text-field v-model="password_confirmation" label="Password" type="password" prepend-icon="mdi-lock"
            outlined dense required
          />

          <v-btn type="submit" color="secondary" block class="mt-4">
            Join
          </v-btn>
        </v-form>

        <!-- Success message -->
        <v-alert
          v-if="successMessage"
          type="success"
          class="mt-4"
          border="start"
          variant="tonal"
          closable
        >
          {{ successMessage }}
          <br />
          Redirecting to login in {{ countdown }} seconds...
        </v-alert>

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
import api from "@/plugins/axios.ts";
import router from "@/router/index.ts";

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const errorMessage = ref('')
const successMessage = ref('')
const countdown = ref(10)
let countdownTimer: ReturnType<typeof setInterval> | null = null

async function onSubmit() {
  errorMessage.value = ''
  successMessage.value = ''

  try {
    const response = await api.post(`/api/register`, {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    })

    const data = response.data

    if (data.success === true) {
      successMessage.value = data.message || 'Registration successful!'
      startCountdown()
    } else {
      errorMessage.value = data.message || 'Registration failed.'
    }
  } catch (error: any) {
    errorMessage.value = error.message
  }
}

function startCountdown() {
  countdown.value = 20

  countdownTimer = setInterval(() => {
    countdown.value--
    if (countdown.value <= 0) {
      clearInterval(countdownTimer!)
      router.push('/')
    }
  }, 1000)
}
</script>
