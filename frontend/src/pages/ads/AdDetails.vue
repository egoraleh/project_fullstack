<template>
  <div class="ad-details-container" v-if="ad">
    <h1 class="ad-title">{{ ad.title }}</h1>

    <img v-if="imageUrl" :src="imageUrl" alt="Фото объявления" class="ad-details-image" />

    <p class="ad-price">Цена: {{ ad.price }} ₽</p>
    <p class="ad-category">Категория: {{ categoryName }}</p>
    <p class="ad-description">Описание: {{ ad.description }}</p>

    <div class="ad-owner">
      <h3>Информация о продавце</h3>
      <p>Имя: {{ owner.name }}</p>
      <p>Телефон: {{ owner.phone_number }}</p>
    </div>

    <div v-if="isOwner" class="owner-actions">
      <router-link :to="`/ad/${ad.id}/edit`" class="btn btn-primary">Изменить</router-link>
      <button @click="deleteAd" class="btn btn-danger">Удалить</button>
    </div>

    <div v-if="isAuthenticated && !isOwner">
      <button
          v-if="!isFavorite"
          @click="addToFavorites"
          class="btn btn-outline-primary"
      >
        Добавить в избранное
      </button>
      <button
          v-else
          @click="removeFromFavorites"
          class="btn btn-outline-danger"
      >
        Убрать из избранного
      </button>
    </div>
    <div v-if="isAuthenticated && !isOwner" class="review-form">
      <h3>Оставить отзыв</h3>
      <form @submit.prevent="submitReview">
        <label>
          Оценка:
          <select v-model="newReview.rating">
            <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
          </select>
        </label>
        <textarea v-model="newReview.text" placeholder="Ваш отзыв..." required></textarea>
        <button type="submit" class="btn btn-success">Отправить</button>
      </form>
    </div>

    <div class="ad-reviews">
      <h3>Отзывы</h3>
      <div v-if="reviews.length">
        <ReviewCard
            v-for="review in reviews"
            :key="review.id"
            :review="review"
            :currentUserId="currentUser?.id"
            @delete-review="deleteReview"
        />
      </div>
      <p v-else>Пока отзывов нет.</p>
    </div>

    <router-link to="/" class="back-link">← Назад к объявлениям</router-link>
  </div>

  <div class="ad-details-container" v-else>
    <p>Объявление не найдено.</p>
    <router-link to="/" class="back-link">← Назад к объявлениям</router-link>
  </div>
</template>

<script>
import api from '../../services/axios';
import ReviewCard from '../../components/ReviewCard.vue';
import { useAuthStore } from '../../stores/authStore';

export default {
  name: "AdDetails",
  components: { ReviewCard },
  data() {
    return {
      ad: null,
      imageUrl: null,
      categoryName: '',
      owner: {},
      reviews: [],
      isFavorite: false,
      newReview: {
        rating: 5,
        text: ''
      },
      loading: false,
      error: null,
    };
  },
  computed: {
    currentUser() {
      return useAuthStore().user;
    },
    isOwner() {
      return this.currentUser && this.ad && this.ad.user_id === this.currentUser.id;
    },
    isAuthenticated() {
      return this.currentUser !== null;
    }
  },
  methods: {
    async checkFavoriteStatus() {
      if (!this.ad?.id || !this.isAuthenticated) return;
      try {
        const res = await api.get(`/ads/favorites/${this.ad.id}/status`);
        this.isFavorite = res.data.isFavorite;
      } catch (error) {
        console.error('Ошибка при проверке статуса избранного:', error);
      }
    },

    async deleteReview(reviewId) {
      if (!confirm('Удалить отзыв?')) return;

      try {
        await api.post(`/ads/reviews/delete/${reviewId}`);
        await this.loadReviews();
      } catch (error) {
        console.error('Ошибка при удалении отзыва:', error);
      }
    },

    async addToFavorites() {
      try {
        await api.post(`/ads/favorites/add`, { adId: this.ad.id });
        this.isFavorite = true;
      } catch (error) {
        console.error('Ошибка при добавлении в избранное:', error);
      }
    },

    async removeFromFavorites() {
      try {
        await api.post(`/ads/favorites/remove`, { adId: this.ad.id });
        this.isFavorite = false;
      } catch (error) {
        console.error('Ошибка при удалении из избранного:', error);
      }
    },

    async loadImage() {
      if (!this.ad?.id) return;
      try {
        const response = await api.get(`/ad/${this.ad.id}/image`, { responseType: 'blob' });
        if (this.imageUrl) URL.revokeObjectURL(this.imageUrl);
        this.imageUrl = URL.createObjectURL(response.data);
      } catch (error) {
        console.error('Не удалось загрузить изображение:', error);
        this.imageUrl = null;
      }
    },
    async loadCategoryName() {
      if (!this.ad.category_id) return;
      try {
        const response = await api.get(`/categories/${this.ad.category_id}`);
        this.categoryName = response.data.name;
      } catch {
        this.categoryName = 'Неизвестно';
      }
    },
    async loadOwner() {
      if (!this.ad.user_id) return;
      try {
        const response = await api.get(`/users/${this.ad.user_id}`);
        this.owner = response.data;
      } catch {
        this.owner = { name: 'Неизвестно', phone: 'Неизвестно' };
      }
    },
    async loadReviews() {
      try {
        const response = await api.get(`/ads/${this.ad.id}/reviews`);
        this.reviews = response.data;
      } catch (error) {
        console.error('Ошибка при загрузке отзывов:', error);
      }
    },
    async submitReview() {
      try {
        const reviewData = {
          rating: this.newReview.rating,
          text: this.newReview.text,
          receiver_id: this.ad.user_id
        };

        await api.post(`/ads/${this.ad.id}/reviews/add`, reviewData);
        this.newReview.rating = 5;
        this.newReview.text = '';
        await this.loadReviews();
      } catch (error) {
        console.error('Ошибка при отправке отзыва:', error);
      }
    },
    async deleteAd() {
      if (!confirm('Удалить объявление?')) return;
      try {
        await api.post(`/ads/delete/${this.ad.id}`);
        this.$router.push('/');
      } catch (error) {
        console.error('Ошибка при удалении:', error);
      }
    }
  },
  async created() {
    const adId = this.$route.params.id;
    this.loading = true;
    try {
      const res = await api.get(`/ads/${adId}`);
      this.ad = res.data;

      await Promise.all([
        this.loadImage(),
        this.loadCategoryName(),
        this.loadOwner(),
        this.loadReviews()
      ]);

      if (!this.isOwner) {
        await this.checkFavoriteStatus();
      }
    } catch (err) {
      console.error('Ошибка при загрузке объявления:', err);
      this.error = 'Объявление не найдено.';
    } finally {
      this.loading = false;
    }
  },
  beforeUnmount() {
    if (this.imageUrl) URL.revokeObjectURL(this.imageUrl);
  }
};
</script>