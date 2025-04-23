<template>
  <div class="card">
    <h3>{{ ad.title }}</h3>
    <p>{{ ad.description }}</p>
  </div>
</template>

<script setup>
defineProps(['ad'])
</script>

<style scoped>
.card {
  border: 1px solid #ccc;
  padding: 15px;
  margin-bottom: 10px;
}
</style>
