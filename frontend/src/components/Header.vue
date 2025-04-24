<template>
  <header>
    <div class="header-container">
      <h1 class="logo">Доска объявлений</h1>
      <nav>
        <router-link to="/">Главная</router-link>
        <router-link to="/about">О проекте</router-link>
        <router-link to="/ads">Объявления</router-link>
        <router-link to="/register">Регистрация</router-link>
        <router-link to="/login">Вход</router-link>
      </nav>
    </div>
  </header>
</template>

<style scoped>
header {
  background: #33cccc;
  color: #fff;
  padding: 20px 0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-size: 1.8rem;
  font-weight: 600;
  margin: 0;
}

nav {
  display: flex;
  gap: 20px;
}

nav a {
  color: #fff;
  text-decoration: none;
  font-size: 1.05rem;
  transition: color 0.3s ease;
}

nav a:hover {
  color: #710a0d;
}
</style>
