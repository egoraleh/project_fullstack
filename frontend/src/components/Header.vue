<template>
  <header>
    <h1>Доска объявлений</h1>
    <nav>
      <router-link to="/">Главная</router-link>
      <router-link to="/about">О проекте</router-link>
      <router-link to="/ads">Объявления</router-link>
      <router-link to="/register">Регистрация</router-link>
    </nav>
  </header>
</template>

<style scoped>
header {
  padding: 20px;
  background: #007BFF;
  color: white;
}
nav a {
  margin-right: 10px;
  color: white;
  text-decoration: none;
}
</style>
