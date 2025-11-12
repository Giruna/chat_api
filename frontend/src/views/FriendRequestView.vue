<template>
  <v-container class="d-flex justify-center friend-requests">
    <v-progress-circular
      v-if="loading"
      color="primary" class="ma-auto"
      indeterminate
    ></v-progress-circular>

    <div v-else>
      <v-card title="Friend Requests" flat>
        <!-- Requests' list -->
        <v-data-table
          :headers="headers"
          :items="items"
          class="elevation-1"
          hide-default-footer
        >
          <!-- Actions -->
          <template #item.actions="{ item }">
            <v-btn
              color="green"
              variant="tonal"
              size="small"
              class="mr-2"
              @click="acceptRequest(item)"
            >
              Accept
            </v-btn>
            <v-btn
              color="red"
              variant="tonal"
              size="small"
              @click="rejectRequest(item)"
            >
              Reject
            </v-btn>
          </template>
        </v-data-table>

      </v-card>

      <!-- Actions message -->
      <v-alert
        v-if="actionsMessage"
        type="success"
        class="mt-4"
        border="start"
        variant="tonal"
        closable
      >
        {{ actionsMessage }}
      </v-alert>

      <!-- Error message -->
      <v-alert
        v-if="errorMessage"
        type="error"
        class="mt-4"
        border="start"
        variant="tonal"
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
const token = localStorage.getItem('token')

const actionsMessage = ref('')
const errorMessage = ref('')
const items = ref([])

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Name', key: 'name' },
  { title: 'Actions', key: 'actions', sortable: false },
]

// Fetch users from API
async function fetchUsersWithFriendRequest() {
  loading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.get(`${baseUrl}/api/friend-request/received`, {
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

// Handle Accept / Reject actions
async function acceptRequest(item: any) {
  try {
    const response = await axios.post(
      `${baseUrl}/api/friend-request/accept`,
      {
        sender_id: item.id,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    )

    const data = response.data

    if (data.success === true) {
      actionsMessage.value = data.message

      await fetchUsersWithFriendRequest()
    } else {
      errorMessage.value = data.message || 'Something went wrong.'
    }
  } catch (error) {
    errorMessage.value = errorHandling(error.response)
  }
}

async function rejectRequest(item: any) {
  try {
    const response = await axios.post(
      `${baseUrl}/api/friend-request/reject`,
      {
        sender_id: item.id,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    )

    const data = response.data

    if (data.success === true) {
      actionsMessage.value = data.message

      await fetchUsersWithFriendRequest()
    } else {
      errorMessage.value = data.message || 'Something went wrong.'
    }
  } catch (error) {
    errorMessage.value = errorHandling(error.response)
  }
}

onMounted(fetchUsersWithFriendRequest)

</script>
