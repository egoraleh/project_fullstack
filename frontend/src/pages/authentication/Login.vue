<template>
  <div class="sign-container">
    <h2 class="sign-header">Вход</h2>
    <form class="sign-form" @submit.prevent="login">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="form.email" required />
      </div>

      <div class="form-group">
        <label for="password">Пароль:</label>
        <input type="password" id="password" autocomplete="off" v-model="form.password" required />
      </div>

      <div class="form-group">
        <input type="checkbox" id="remember" v-model="form.remember" />
        <label for="remember">Запомнить меня</label>
      </div>

      <button type="submit" class="sign-btn">Войти</button>

      <p class="text-center">
        Нет аккаунта?
        <router-link to="/register">Зарегистрироваться</router-link>
      </p>
    </form>
  </div>
</template>

<script>
import api from '../../services/axios';

export default {
  name: 'Login',
  data() {
    return {
      form: {
        email: '',
        password: '',
        remember: false
      }
    }
  },
  methods: {
    async login() {
      try {
        const response = await api.post('/login', this.form);
        alert('Вход успешен: ' + response.data.message);
        this.$router.push('/');
      } catch (error) {
        console.error(error);
        alert('Ошибка при входе.');
      }
    }
  }
}
</script>