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

<style scoped>
.not-found {
  text-align: center;
  margin-top: 12%;
}

.not-found h1 {
  font-size: 3rem;
  color: #33cccc;
  margin-bottom: 20px;
}

.not-found p {
  font-size: 1.2rem;
  color: #333;
  margin-bottom: 20px;
}

.go-home-link {
  color: #00b3b3;
  font-size: 1.2rem;
  text-decoration: none;
  font-weight: 500;
}

.go-home-link:hover {
  text-decoration: underline;
}
</style>
