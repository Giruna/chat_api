<template>
  <v-container class="d-flex justify-center users">
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
        <v-text-field
          v-model="search"
          label="Search users"
          @input="userSearch"
        />

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

          <template #item.actions="{ item }">
            <div v-if="item.friend_status === null" class="text-center">
              <img
                src="@/assets/icons/add-friend.svg"
                :title="`Add ${item.name} as friend`"
                alt="Send message"
                width="22"
                height="22"
                style="cursor: pointer"
                @click="addFriend(item)"
              />
            </div>
            <div v-if="item.friend_status === 'accepted'" class="text-center">
              <img
                src="@/assets/icons/send-message.svg"
                :title="`Send message to ${item.name}`"
                alt="Send message"
                width="22"
                height="22"
                style="cursor: pointer"
                @click="goToMessages(item.id)"
              />
            </div>
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
import api from "@/plugins/axios.ts";
import { useMessagesNavigation } from '@/composables/useMessagesNavigation'

const { goToMessages } = useMessagesNavigation()

const loading = ref(false)
const page = ref(1)
const perPage = ref(10)
const search = ref('')
const token = localStorage.getItem('token')

const actionsMessage = ref('')
const errorMessage = ref('')
const items = ref([])
const lastPage = ref(1)
const total = ref(0)

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Name', key: 'name' },
  { title: 'Friend status', key: 'friend_status' },
  { title: '', key: 'actions', sortable: false },
]

// Fetch users from API
async function fetchUsers() {
  loading.value = true
  errorMessage.value = ''

  try {
    const response = await api.get(`/api/users`, {
      params: {
        page: page.value,
        per_page: perPage.value,
        search: search.value,
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
  } catch (error: any) {
    errorMessage.value = error.message
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
    case 'rejected':
      return 'red'
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
    case 'rejected':
      return 'Rejected'
    default:
      return 'Not friend'
  }
}

async function addFriend(item) {
  try {
    const response = await api.post(
      '/api/friend-request/send',
      {
        receiver_id: item.id,
      },
    )

    const data = response.data

    if (data.success === true) {
      actionsMessage.value = data.message

      await fetchUsers()
    } else {
      errorMessage.value = data.message || 'Something went wrong.'
    }
  } catch (error: any) {
    errorMessage.value = error.message
  }
}

let searchTimer: any = null
function userSearch() {
  clearTimeout(searchTimer)

  searchTimer = setTimeout(() => {
    page.value = 1
    fetchUsers()
  }, 500)
}

</script>
