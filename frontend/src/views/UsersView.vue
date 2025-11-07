<template>
  <v-container class="d-flex justify-center messages">
    <v-progress-circular
      v-if="loading"
      color="primary" class="ma-auto"
      indeterminate
    ></v-progress-circular>

    <div v-else>
      <v-card
        title="Users list"
        flat
      >
        <!-- Users' list -->
        <v-data-table
          :headers="headers"
          :items="items"
          class="elevation-1"
          hide-default-footer
        >
          <template #item.friend_status="{ item }">
            <v-chip
              :color="getStatusColor(item.friend_status)"
              dark
              label
            >
              {{ getStatusLabel(item.friend_status) }}
            </v-chip>
          </template>

        </v-data-table>

        <!-- Pagination -->
        <v-card-actions class="justify-center mt-4">
          <v-pagination
            v-model="page"
            :length="lastPage"
            @update:modelValue="fetchUsers"
            total-visible="5"
          />
        </v-card-actions>

      </v-card>

      <!-- Error message -->
      <v-alert v-if="errorMessage" type="error" class="mt-4" border="start" variant="tonal"
        closable
      >
        {{ errorMessage }}
      </v-alert>

    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import {errorHandling} from "@/utils/errorHandling.js";

const baseUrl = import.meta.env.VITE_API_BASE_URL

const loading = ref(false)
const page = ref(1)
const perPage = ref(5)
const search = ref('')
const token = localStorage.getItem('token')

const errorMessage = ref('')
const items = ref([])
const lastPage = ref(1)
const total = ref(0)

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Name', key: 'name' },
  { title: 'Friend status', key: 'friend_status' },
]

// Fetch users from API
async function fetchUsers() {
  loading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.get(`${baseUrl}/api/users`, {
      params: {
        page: page.value,
        per_page: perPage.value,
        search: search.value,
      },
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    const data = response.data

    if (data.success === true) {
      items.value = data.data
      page.value = data.current_page
      lastPage.value = data.last_page
      total.value = data.total
    } else {
      errorMessage.value = data.message || 'Something went wrong.'
    }
  } catch (error) {
    errorMessage.value = errorHandling(error.response)
  } finally {
    loading.value = false
  }
}

onMounted(fetchUsers)

function getStatusColor(status: string | null) {
  switch (status) {
    case 'accepted':
      return 'green'
    case 'pending':
      return 'orange'
    default:
      return 'grey'
  }
}

function getStatusLabel(status: string | null) {
  switch (status) {
    case 'accepted':
      return 'Friend'
    case 'pending':
      return 'Pending'
    default:
      return 'Not friend'
  }
}
</script>

<style>
@media (min-width: 1024px) {
  .messages {
    min-height: 80vh;
    display: flex;
  }
}
</style>
