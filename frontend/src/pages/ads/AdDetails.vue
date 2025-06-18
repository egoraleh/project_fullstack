<template>
  <div class="ad-details-container" v-if="ad">
    <h1 class="ad-title">{{ ad.title }}</h1>
    <img
        v-if="imageUrl"
        :src="imageUrl"
        alt="Фото объявления"
        class="ad-details-image"
    />
    <p class="ad-description">{{ ad.description }}</p>
    <p class="ad-price">Цена: {{ ad.price }} ₽</p>
    <p class="ad-category">Категория: {{ categoryName }}</p>

    <router-link to="/" class="back-link">← Назад к объявлениям</router-link>
  </div>

  <div class="ad-details-container" v-else>
    <p>Объявление не найдено.</p>
    <router-link to="/" class="back-link">← Назад к объявлениям</router-link>
  </div>
</template>

<script>
import api from '../../services/axios'

export default {
  name: "AdDetails",
  data() {
    return {
      ad: null,
      imageUrl: null,
      categoryName: '',
      loading: false,
      error: null,
    };
  },
  methods: {
    async loadImage() {
      if (!this.ad?.id) return;

      try {
        const response = await api.get(`/ad/${this.ad.id}/image`, {
          responseType: 'blob'
        });
        if (this.imageUrl) {
          URL.revokeObjectURL(this.imageUrl);
        }
        this.imageUrl = URL.createObjectURL(response.data);
      } catch (error) {
        console.error('Не удалось загрузить изображение объявления:', error);
        this.imageUrl = null;
      }
    },

    async loadCategoryName() {
      if (!this.ad.category_id) return;

      try {
        const response = await api.get(`/categories/${this.ad.category_id}`);
        this.categoryName = response.data.name;
      } catch (error) {
        console.error('Не удалось загрузить название категории:', error);
        this.categoryName = 'Неизвестно';
      }
    }
  },

  async created() {
    const adId = this.$route.params.id;
    this.loading = true;
    try {
      const res = await api.get(`/ads/${adId}`);
      this.ad = res.data;

      await this.loadImage();
      await this.loadCategoryName();

    } catch (err) {
      console.error('Ошибка при загрузке объявления:', err);
      this.error = 'Объявление не найдено.';
    } finally {
      this.loading = false;
    }
  },

  beforeUnmount() {
    if (this.imageUrl) {
      URL.revokeObjectURL(this.imageUrl);
    }
  }
};
</script>