<template>
  <div class="about">
    <div class="greeting">
      <h1>Здравствуйте, {{ username }}!</h1>
    </div>
    <div v-if="aboutHtml" v-html="aboutHtml"></div>
    <div v-else>
      <p>Загрузка...</p>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../../stores/authStore'
import api from '../../services/axios'

export default {
  name: 'About',
  data() {
    return {
      aboutHtml: null
    }
  },
  computed: {
    username() {
      const authStore = useAuthStore()
      return authStore.username
    }
  },
  mounted() {
    this.fetchAboutHtml()
  },
  methods: {
    async fetchAboutHtml() {
      try {
        const response = await api.get('/about-page')
        this.aboutHtml = response.data
      } catch (error) {
        console.error('Ошибка загрузки about:', error)
        this.aboutHtml = '<p>Не удалось загрузить страницу.</p>'
      }
    }
  }
}
</script>