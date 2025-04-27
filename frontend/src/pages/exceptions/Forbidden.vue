<template>
  <div class="error-page">
    <h1>403</h1>
    <p>У вас нет доступа к этой странице.</p>
    <p><router-link to="/" class="error-link">Вернуться на главную</router-link></p>
  </div>
</template>

<script>
export default {
  name: "Forbidden",
};
</script>