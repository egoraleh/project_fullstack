<template>
  <div class="error-page">
    <h1>500</h1>
    <p>Произошла внутренняя ошибка сервера. Пожалуйста, попробуйте позже.</p>
    <p><router-link to="/" class="error-link">Вернуться на главную</router-link></p>
  </div>
</template>

<script>
export default {
  name: "ServerError",
};
</script>