<template>
  <div class="ad-card">
    <h3>{{ ad.title }}</h3>
    <p class="description">{{ ad.description }}</p>
    <p class="price">{{ ad.price }} ₽</p>
    <router-link :to="'/ad=' + ad.id" class="details-link">Подробнее</router-link>
  </div>
</template>

<script setup>
defineProps({
  ad: {
    type: Object,
    required: true
  }
})
</script>