<template>
  <div class="sign-container">
    <h2 class="sign-header">Регистрация</h2>
    <form class="sign-form" @submit.prevent="register">
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" id="email" v-model="form.email" required />
      </div>

      <div class="form-group">
        <label for="password">Пароль:</label>
        <input type="password" id="password" v-model="form.password" required />
      </div>

      <div class="form-group">
        <label for="repeatedPassword">Подтверждение пароля:</label>
        <input type="password" id="repeatedPassword" v-model="form.repeatedPassword" required />
      </div>

      <button type="submit" class="sign-btn">Зарегистрироваться</button>
      <p class="text-center">
        Уже есть аккаунт?
        <router-link to="/login">Войти</router-link>
      </p>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Register',
  data() {
    return {
      form: {
        email: '',
        password: '',
        repeatedPassword: '',
      }
    }
  },
  methods: {
    async register() {
      if (this.form.password !== this.form.repeatedPassword) {
        alert('Пароли не совпадают.');
        return;
      }

      try {
        const response = await axios.post('/api/register', {
          email: this.form.email,
          password: this.form.password
        });

        alert('Регистрация успешна: ' + response.data.message);
        this.$router.push('/login');

      } catch (error) {
        console.error(error);
        if (error.response && error.response.data && error.response.data.error) {
          alert('Ошибка: ' + error.response.data.error);
        } else {
          alert('Произошла ошибка при регистрации.');
        }
      }
    }
  }
}
</script>
