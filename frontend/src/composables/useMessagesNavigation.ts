import { useRouter } from 'vue-router'

export function useMessagesNavigation() {
  const router = useRouter()

  function goToMessages(friendId: number) {
    router.push({ name: 'messages', params: { friendId } })
  }

  return {
    goToMessages,
  }
}
