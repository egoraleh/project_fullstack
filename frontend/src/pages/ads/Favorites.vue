<template>
  <div class="my-ads">
    <h1 class="my-ads__title">Избранные объявления</h1>

    <div v-if="favorites.length > 0" class="my-ads__list">
      <AdCard v-for="ad in favorites" :key="ad.id" :ad="ad" />
    </div>

    <p v-else class="my-ads__empty">У вас пока нет избранных объявлений.</p>
  </div>
</template>

<script>
import api from '../../services/axios'
import { useAuthStore } from '../../stores/authStore'
import AdCard from '../../components/AdCard.vue'

export default {
  name: 'Favorites',
  components: { AdCard },
  data() {
    return {
      favorites: []
    }
  },
  async created() {
    try {
      const user = useAuthStore().$state.user
      if (!user) {
        alert('Пожалуйста, войдите в систему для просмотра избранного')
        this.$router.push('/login')
        return
      }

      const res = await api.get('/favorites')
      this.favorites = res.data
    } catch (e) {
      console.error(e)
      alert('Не удалось загрузить избранные объявления')
    }
  }
}
</script>