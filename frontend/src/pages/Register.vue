<template>
  <div class="register-container">
    <h2 class="register-header">Регистрация</h2>
    <form class="register-form" @submit.prevent="register">
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

      <button type="submit" class="register-btn">Зарегистрироваться</button>
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

<style scoped>
.register-container {
  max-width: 400px;
  margin: 150px auto;
  padding: 20px;
  background: #f0f9f9;
  border-radius: 12px;
  box-shadow: 0 0 12px rgba(0, 128, 128, 0.1);
}

.register-header {
  text-align: center;
  color: #33cccc;
}

.register-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.register-form .form-group {
  display: flex;
  flex-direction: column;
}

.register-form input {
  padding: 8px 10px;
  border: 1px solid #99cccc;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.3s;
}

.register-btn {
  padding: 10px ;
  margin-top: 15px;
  background: #33cccc;
  border: none;
  color: #fff;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.3s;
}

.register-btn:hover {
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
