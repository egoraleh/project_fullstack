<template>
  <div class="not-found">
    <h1>404</h1>
    <p>Страница не найдена</p>
    <p><router-link to="/about" class="go-home-link">Вернуться на главную</router-link></p>
  </div>
</template>

<script>
export default {
  name: "NotFound",
};
</script>