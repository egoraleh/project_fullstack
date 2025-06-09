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
export default {
  name: 'About',
  data() {
    return {
      username: 'гость',
      aboutHtml: null,
    }
  },
  mounted() {
    const storedUser = localStorage.getItem('username');
    if (storedUser) {
      this.username = storedUser;
    }
    this.fetchAboutHtml();
  },
  methods: {
    async fetchAboutHtml() {
      try {
        const response = await fetch(`${import.meta.env.VITE_BACKEND_URL}/api/about-page`);
        if (!response.ok) throw new Error('Ошибка загрузки данных');
        this.aboutHtml = await response.text();
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>