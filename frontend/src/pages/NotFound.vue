<template>
  <div class="not-found">
    <h1>404</h1>
    <p>Страница не найдена</p>
    <p><router-link to="/">Вернуться на главную</router-link></p>
  </div>
</template>

<script>
export default {
  name: "NotFound",
};
</script>

<style scoped>
.not-found {
  text-align: center;
  margin-top: 50px;
}
h1 {
  font-size: 50px;
  color: #f44336;
}
p {
  font-size: 20px;
}
router-link {
  text-decoration: none;
  color: #2196F3;
  font-size: 18px;
}
</style>
