<template>
  <div class="error-page">
    <h1>401</h1>
    <p>Для доступа к этой странице требуется авторизация.</p>
    <p><router-link to="/login" class="error-link">Войти в аккаунт</router-link></p>
  </div>
</template>

<script>
export default {
  name: "Unauthorized",
};
</script>
