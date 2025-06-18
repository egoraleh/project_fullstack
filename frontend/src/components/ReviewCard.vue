<template>
  <div class="review-card">
    <div class="review-header">
      <img
          v-if="avatarUrl"
          :src="avatarUrl"
          alt="Аватар пользователя"
          class="review-avatar"
      />
      <div class="review-user-info">
        <h3 class="review-username">{{ username }}</h3>
        <div class="review-rating">
          <span
              v-for="n in 5"
              :key="n"
              class="star"
              :class="{ 'filled': n <= review.rating }"
          >★</span>
        </div>
      </div>

      <button
          v-if="currentUserId === review.author_id"
          @click="handleDelete"
          class="review-delete-btn"
      >
        ✕
      </button>
    </div>

    <p class="review-text">
      {{ review.text }}
    </p>
  </div>
</template>

<script>
export default {
  name: "ReviewCard",
  props: {
    review: {
      type: Object,
      required: true
    },
    currentUserId: {
      type: Number,
      required: false,
      default: null
    }
  },
  data() {
    return {
      username: 'Загрузка...',
      avatarUrl: null
    };
  },
  mounted() {
    this.fetchUserInfo();
  },
  methods: {
    async fetchUserInfo() {
      try {
        const userResponse = await fetch(`/api/users/${this.review.author_id}`);
        if (!userResponse.ok) {
          throw new Error('Ошибка загрузки данных пользователя');
        }
        const userData = await userResponse.json();
        this.username = userData.name;

        const avatarResponse = await fetch(`/api/user/avatar/${this.review.author_id}`);
        if (avatarResponse.ok) {
          const blob = await avatarResponse.blob();
          this.avatarUrl = URL.createObjectURL(blob);
        } else {
          this.avatarUrl = '/uploads/avatars/default.jpg';
        }
      } catch (error) {
        console.error('Ошибка загрузки информации о пользователе:', error);
        this.username = 'Неизвестный пользователь';
        this.avatarUrl = '/uploads/avatars/default.jpg';
      }
    },
    handleDelete() {
      this.$emit('delete-review', this.review.id);
    }
  }
};
</script>