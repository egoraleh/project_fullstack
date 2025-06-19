<template>
  <div class="sign-container">
    <h2 class="sign-header">Регистрация</h2>
    <form class="sign-form" @submit.prevent="register">
      <div class="form-group">
        <label for="firstName">Имя:</label>
        <input type="text" id="firstName" v-model="form.firstName" autocomplete="off" required />
      </div>

      <div class="form-group">
        <label for="lastName">Фамилия:</label>
        <input type="text" id="lastName" v-model="form.lastName" autocomplete="off" required />
      </div>

      <div class="form-group">
        <label for="phone">Телефон:</label>
        <input type="text" id="phone" ref="phoneInput" placeholder="+7 (9__) ___-__-__" autocomplete="off" required />
      </div>

      <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" id="email" v-model="form.email" autocomplete="off" required />
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
import api from '../../services/axios';

export default {
  name: 'Register',
  data() {
    return {
      form: {
        firstName: '',
        lastName: '',
        phone: '',
        email: '',
        password: '',
        repeatedPassword: '',
      }
    }
  },
  mounted() {
    const input = this.$refs.phoneInput;
    const template = '+7 (9__) ___-__-__';
    const digitIndexes = [...template].map((ch, i) => (ch === '_' ? i : -1)).filter(i => i >= 0);
    const maxUserDigits = digitIndexes.length;

    function buildValue(userDigits) {
      const arr = template.split('');
      userDigits.forEach((d, idx) => {
        arr[digitIndexes[idx]] = d;
      });
      const last = userDigits.length ? digitIndexes[userDigits.length - 1] : digitIndexes[0] - 1;
      return arr.slice(0, last + 1).join('');
    }

    function setCaret(pos) {
      window.requestAnimationFrame(() => {
        input.setSelectionRange(pos, pos);
      });
    }

    input.addEventListener('focus', () => {
      if (!input.value) {
        input.value = '+7 (9';
        setCaret(input.value.length);
      }
    });

    input.addEventListener('blur', () => {
      if (input.value === '+7 (9') {
        input.value = '';
        this.form.phone = '';
      }
    });

    input.addEventListener('input', () => {
      let allDigits = input.value.replace(/\D/g, '').split('');

      if (allDigits[0] === '7') allDigits.shift();
      if (allDigits[0] === '9') allDigits.shift();

      const userDigits = allDigits.slice(0, maxUserDigits);

      input.value = buildValue(userDigits);
      setCaret(input.value.length);

      this.phoneNumber = input.value;
    });
  },
  methods: {
    async register() {
      if (this.form.password !== this.form.repeatedPassword) {
        alert('Пароли не совпадают.');
        return;
      }

      try {
        const response = await api.post('/register', {
          firstName: this.form.firstName,
          lastName: this.form.lastName,
          phone: this.form.phone,
          email: this.form.email,
          password: this.form.password
        });

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