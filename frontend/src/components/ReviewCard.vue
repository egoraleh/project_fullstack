<template>
  <div class="review-card">
    <div class="review-header">
      <img
          v-if="review.avatarUrl"
          :src="review.avatarUrl"
          alt="Аватар пользователя"
          class="review-avatar"
      />
      <div class="review-user-info">
        <h3 class="review-username">{{ review.username }}</h3>
        <div class="review-rating">
          <span
              v-for="n in 5"
              :key="n"
              class="star"
              :class="{ 'filled': n <= review.rating }"
          >★</span>
        </div>
      </div>
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
    }
  }
};
</script>