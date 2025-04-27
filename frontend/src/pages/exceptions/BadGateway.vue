<template>
  <div class="error-page">
    <h1>502</h1>
    <p>Сервер недоступен. Возможно, ведутся технические работы.</p>
    <p><router-link to="/" class="error-link">На главную</router-link></p>
  </div>
</template>

<script>
export default {
  name: "BadGateway",
};
</script>