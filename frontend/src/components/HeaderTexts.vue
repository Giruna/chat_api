<script setup lang="ts">
defineProps<{
  msg: string
  username: string | null
}>()
</script>

<template>
  <div class="greetings">
    <h1 class="orange">{{ msg }}</h1>
    <h3>Hi {{ username ?? 'Guest' }}!<br>
      Ready to chat, laugh, and make some new friends?
      Dive into Chatterboxerino and start the conversation.<br>
      Chatterboxerino is where the fun begins!
    </h3>
  </div>
</template>

<style scoped>
h1 {
  font-weight: 500;
  font-size: 2.6rem;
  position: relative;
  top: -10px;
}

h3 {
  font-size: 1.2rem;
}

.greetings h1,
.greetings h3 {
  text-align: center;
}

@media (min-width: 1024px) {
  .greetings h1,
  .greetings h3 {
    text-align: left;
  }
}
</style>
