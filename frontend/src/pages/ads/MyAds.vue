<template>
  <div class="my-ads">
    <h1 class="my-ads__title">Мои объявления</h1>

    <router-link to="/ads/new" class="my-ads__add-btn">+ Добавить объявление</router-link>

    <div v-if="ads.length > 0" class="my-ads__list">
      <AdCard v-for="ad in ads" :key="ad.id" :ad="ad" />
    </div>

    <p v-else class="my-ads__empty">У вас пока нет объявлений.</p>
  </div>
</template>

<script>
import api from '../../services/axios'
import { useAuthStore } from '../../stores/authStore'
import AdCard from '../../components/AdCard.vue'

export default {
  name: 'MyAds',
  components: { AdCard },
  data() {
    return {
      ads: []
    }
  },
  async created() {
    try {
      const userId = useAuthStore().$state.user.id
      const res = await api.get(`/user/ads/${userId}`)
      this.ads = res.data
    } catch (e) {
      console.error(e)
      alert('Не удалось загрузить объявления')
    }
  }
}
</script>