<template>
  <v-container class="d-flex justify-center messages">
    <v-row>
      <!-- Friends list -->
      <v-col cols="3">
        <v-card flat title="Your friends">
          <v-progress-circular
            v-if="loadingFriends"
            color="primary"
            class="ma-auto"
            indeterminate
          />
          <v-list v-else>
            <v-list-item
              v-for="friend in friends"
              :key="friend.id"
              @click="selectFriend(friend)"
              :class="{'bg-grey-lighten-3': selectedFriend?.id === friend.id}"
            >
              <v-list-item-title>{{ friend.name }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>

      <!-- Conversation -->
      <v-col cols="9">
        <v-card flat>
          <v-card-title>
            <span v-if="selectedFriend">
              Conversation with <strong>{{ selectedFriend.name }}</strong>
            </span>
            <span v-else>Select a friend to start chatting</span>
          </v-card-title>

          <v-divider />

          <v-card-text>
            <!-- Conversation messages -->
            <div class="messages-container" v-if="selectedFriend">
              <v-progress-circular
                v-if="loadingMessages"
                color="primary"
                class="ma-auto"
                indeterminate
              />
              <div v-else>
                <div
                  v-for="msg in messages"
                  :key="msg.id"
                  :class="msg.sender_id === userId ? 'sent' : 'received'"
                >
                  <div class="message-bubble">
                    {{ msg.message }}
                    <div class="timestamp">
                      {{ formatDate(msg.created_at) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </v-card-text>

          <!-- Pagination -->
          <v-card-actions v-if="selectedFriend && lastPage > 1" class="justify-center">
            <v-pagination
              v-model="page"
              :length="lastPage"
              total-visible="5"
              @update:modelValue="fetchConversation"
            />
          </v-card-actions>

          <!-- Message input -->
          <v-card-actions v-if="selectedFriend">
            <v-text-field
              v-model="newMessage"
              label="Type a message..."
              variant="outlined"
              density="comfortable"
              class="flex-grow-1"
              hide-details
              @keyup.enter="sendMessage"
            />
            <v-btn color="primary" @click="sendMessage" :disabled="sending">
              Send
            </v-btn>
          </v-card-actions>

          <!-- Alerts -->
          <v-alert
            v-if="errorMessage"
            type="error"
            class="mt-2"
            border="start"
            variant="tonal"
            closable
          >
            {{ errorMessage }}
          </v-alert>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { errorHandling } from '@/utils/errorHandling.js'
import { useRoute } from 'vue-router'

const baseUrl = import.meta.env.VITE_API_BASE_URL
const token = localStorage.getItem('token')
const userId = Number(localStorage.getItem('userid'))

// reactive state
const friends = ref([])
const selectedFriend = ref(null)
const messages = ref([])
const newMessage = ref('')
const loadingFriends = ref(false)
const loadingMessages = ref(false)
const sending = ref(false)
const errorMessage = ref('')

const page = ref(1)
const perPage = 5
const lastPage = ref(1)
const total = ref(0)

const route = useRoute()
const initialFriendId = route.params.friendId

// fetch friends
async function fetchFriends() {
  loadingFriends.value = true
  errorMessage.value = ''

  try {
    const response = await axios.get(`${baseUrl}/api/friends`, {
      headers: { Authorization: `Bearer ${token}` },
    })

    const data = response.data
    if (data.success === true) {
      friends.value = data.data
    } else {
      errorMessage.value = data.message || 'Failed to load friends.'
    }
  } catch (error) {
    errorMessage.value = errorHandling(error.response)
  } finally {
    loadingFriends.value = false
  }
}

// fetch conversation (paginated)
async function fetchConversation() {
  if (!selectedFriend.value) return

  loadingMessages.value = true
  errorMessage.value = ''
  messages.value = []

  try {
    const response = await axios.get(
      `${baseUrl}/api/messages/${selectedFriend.value.id}`,
      {
        params: {
          page: page.value,
          per_page: perPage,
        },
        headers: { Authorization: `Bearer ${token}` },
      }
    )

    const data = response.data
    if (data.success === true && data.data) {
      messages.value = data.data
      lastPage.value = data.last_page
      total.value = data.total
    } else {
      errorMessage.value = data.message || 'No messages found.'
    }
  } catch (error) {
    errorMessage.value = errorHandling(error.response)
  } finally {
    loadingMessages.value = false
  }
}

// select friend
function selectFriend(friend: any) {
  selectedFriend.value = friend
  page.value = 1
  fetchConversation()
}

// send message
async function sendMessage() {
  if (!selectedFriend.value || !newMessage.value.trim()) return

  sending.value = true
  errorMessage.value = ''

  try {
    const response = await axios.post(
      `${baseUrl}/api/messages/send`,
      {
        message: newMessage.value,
        receiver_id: selectedFriend.value.id
      },
      { headers: { Authorization: `Bearer ${token}` } }
    )

    const data = response.data
    if (data.success === true) {
      newMessage.value = ''
      fetchConversation() // reload
    } else {
      errorMessage.value = data.message || 'Could not send message.'
    }
  } catch (error) {
    errorMessage.value = errorHandling(error.response)
  } finally {
    sending.value = false
  }
}

function formatDate(datetime: string) {
  const date = new Date(datetime)
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}

onMounted(async () => {
  await fetchFriends()

  if (route.params.friendId) {
    const id = Number(route.params.friendId)
    const friend = friends.value.find(f => f.id === id)

    if (friend) {
      selectFriend(friend)
    }
  }
})

</script>

<style scoped>
.messages-page {
  min-height: 80vh;
}
.messages-container {
  max-height: 60vh;
  overflow-y: auto;
  padding: 10px;
}
.sent {
  text-align: right;
  margin: 5px 0;
}
.received {
  text-align: left;
  margin: 5px 0;
}
.message-bubble {
  display: inline-block;
  padding: 8px 12px;
  border-radius: 16px;
  max-width: 70%;
  word-break: break-word;
}
.sent .message-bubble {
  background-color: var(--primary-item-selected);
  color: white;
}
.timestamp {
  font-size: 0.7rem;
  color: gray;
  margin-top: 2px;
}
.received .message-bubble {
  background-color: #f1f1f1;
  color: black;
}
.sent .message-bubble .timestamp {
  color:white;
}
</style>
