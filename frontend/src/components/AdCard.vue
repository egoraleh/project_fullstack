<template>
  <router-link :to="`/ad=${ad.id}`" class="ad-card">
    <img
        v-if="imageUrl"
        :src="imageUrl"
        alt="Изображение объявления"
        class="ad-card__image"
    />
    <div class="ad-card__content">
      <h3 class="ad-card__title">{{ ad.title }}</h3>
      <p class="ad-card__description">{{ ad.description }}</p>
      <p class="ad-card__price">{{ ad.price }} ₽</p>
    </div>
  </router-link>
</template>

<script>
import api from '../services/axios'

export default {
  name: 'AdCard',
  props: {
    ad: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      imageUrl: null
    }
  },
  watch: {
    'ad.id': {
      handler() {
        this.loadImage()
      },
      immediate: true
    }
  },
  methods: {
    async loadImage() {
      if (!this.ad?.id) return

      try {
        const response = await api.get(`/ad/${this.ad.id}/image`, {
          responseType: 'blob'
        })
        this.imageUrl = URL.createObjectURL(response.data)
      } catch (error) {
        console.error('Не удалось загрузить изображение объявления:', error)
      }
    }
  },
  mounted() {
    this.loadImage()
  }
}
</script>