<template>
  <div class="sign-container">
    <h2 class="sign-header">Регистрация</h2>
    <form class="sign-form" @submit.prevent="register">
      <div class="form-group">
        <label for="firstname">Имя:</label>
        <input type="text" id="firstname" v-model="form.firstname" required />
      </div>

      <div class="form-group">
        <label for="lastname">Фамилия:</label>
        <input type="text" id="lastname" v-model="form.lastname" required />
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="form.email" required />
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
        firstname: '',
        lastname: '',
        email: ''
      }
    }
  },
  methods: {
    async register() {
      try {
        const response = await axios.post('/api/register', this.form);
        alert('Регистрация успешна: ' + response.data.message);
      } catch (error) {
        console.error(error);
        alert('Произошла ошибка при регистрации.');
      }
    }
  }
}
</script>