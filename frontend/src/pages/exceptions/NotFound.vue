<template>
  <div class="error-page">
    <h1>404</h1>
    <p>Кажется, вы пытаетесь пройти по несуществующему адресу.</p>
    <p><router-link to="/" class="error-link">Вернуться на главную</router-link></p>
  </div>
</template>

<script>
export default {
  name: "NotFound",
};
</script>