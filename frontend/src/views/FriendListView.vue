<template>
  <v-container class="d-flex justify-center friend-list">
    <v-progress-circular
      v-if="loading"
      color="primary" class="ma-auto"
      indeterminate
    ></v-progress-circular>

    <div v-else>
      <v-card
        title="Friends list"
        flat
      >

        <template v-slot:text>
          <v-text-field
            v-model="search"
            label="Search friends"
          />
        </template>

        <v-data-table
          v-model:page="page"
          :headers="headers"
          :items="items"
          :search="search"
          :page="page"
          :items-per-page="5"
          :items-per-page-options="[]"
          hide-default-footer
        >
          <template #item.actions="{ item }">
            <img
              src="@/assets/icons/send-message.svg"
              :title="`Send message to ${item.name}`"
              alt="Send message"
              width="22"
              height="22"
              style="cursor: pointer"
              @click="goToMessages(item.id)"
            />
          </template>

        </v-data-table>

        <v-pagination
          v-model="page"
          :length="Math.ceil(items.length / 5)"
          class="mt-4"
          total-visible="5"
        ></v-pagination>

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
import { useMessagesNavigation } from '@/composables/useMessagesNavigation'

const { goToMessages } = useMessagesNavigation()

const baseUrl = import.meta.env.VITE_API_BASE_URL

const loading = ref(false)
const token = localStorage.getItem('token')

const errorMessage = ref('')

const search = ref('')
const items = ref([])
const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Name', key: 'name' },
  { title: '', key: 'actions', sortable: false },
]
const page = ref(1)

// Fetch friends from API
async function fetchFriends() {
  loading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.get(`${baseUrl}/api/friends`, {
      params: {
      },
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    const data = response.data

    if (data.success === true) {
      items.value = data.data
    } else {
      errorMessage.value = data.message || 'Something went wrong.'
    }
  } catch (error) {
    errorMessage.value = errorHandling(error.response)
  } finally {
    loading.value = false
  }
}

onMounted(fetchFriends)

</script>
