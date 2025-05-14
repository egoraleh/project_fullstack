<template>
  <div class="about">
    <div class="greeting">
      <h1>Здравствуйте, {{ username }}!</h1>
    </div>
    <div v-if="aboutData" class="about-platform">
      <h2 class="about-header">{{ aboutData.title }}</h2>
      <div v-html="aboutData.description"></div>
    </div>
    <div v-else>
      <p>Загрузка...</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'About',
  data() {
    return {
      username: 'гость',
      aboutData: null,
    }
  },
  computed: {
    isGuest() {
      return this.username === 'гость'
    },
  },
  mounted() {
    const storedUser = localStorage.getItem('username')
    if (storedUser) {
      this.username = storedUser
    }
    this.fetchAboutData()
  },
  methods: {
    async fetchAboutData() {
      try {
        const response = await fetch(`${import.meta.env.VITE_BACKEND_URL}/api/about-page`)
        if (!response.ok) {
          throw new Error('Ошибка загрузки данных')
        }
        this.aboutData = await response.json()
      } catch (error) {
        console.error(error)
      }
    },
  },
}
</script>