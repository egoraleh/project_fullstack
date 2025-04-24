<template>
  <div class="login-container">
    <h2 class="login-header">Вход</h2>
    <form class="login-form" @submit.prevent="login">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="form.email" required />
      </div>

      <div class="form-group">
        <label for="password">Пароль:</label>
        <input type="password" id="password" v-model="form.password" required />
      </div>

      <button type="submit" class="login-btn">Войти</button>

      <p class="text-center">
        Нет аккаунта?
        <router-link to="/register">Зарегистрироваться</router-link>
      </p>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  data() {
    return {
      form: {
        email: '',
        password: ''
      }
    }
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('/api/login', this.form);
        alert('Вход успешен: ' + response.data.message);
      } catch (error) {
        console.error(error);
        alert('Ошибка при входе.');
      }
    }
  }
}
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 150px auto;
  padding: 20px;
  background: #f0f9f9;
  border-radius: 12px;
  box-shadow: 0 0 12px rgba(0, 128, 128, 0.1);
}

.login-header {
  text-align: center;
  color: #33cccc;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.login-form .form-group {
  display: flex;
  flex-direction: column;
}

.login-form input {
  padding: 8px 10px;
  border: 1px solid #99cccc;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.3s;
}

.login-btn {
  padding: 10px;
  margin-top: 15px;
  background: #33cccc;
  border: none;
  color: #fff;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.3s;
}

.login-btn:hover {
  background: #00b3b3;
}

.text-center {
  text-align: center;
  margin-top: 10px;
}

.text-center a {
  color: #006699;
  text-decoration: none;
  font-weight: bold;
}

.text-center a:hover {
  text-decoration: underline;
}
</style>
