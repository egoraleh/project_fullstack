<template>
  <div>
    <h2>Регистрация</h2>
    <form @submit.prevent="register">
      <div>
        <label for="firstname">Имя:</label>
        <input type="text" id="firstname" v-model="form.firstname" />
      </div>
      <div>
        <label for="lastname">Фамилия:</label>
        <input type="text" id="lastname" v-model="form.lastname" />
      </div>
      <div>
        <label for="age">Возраст:</label>
        <input type="number" id="age" v-model="form.age" />
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="form.email" />
      </div>
      <button type="submit">Зарегистрироваться</button>
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
        age: '',
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
