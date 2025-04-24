<template>
  <div class="home">
    <div class="greeting">
      <h1>Здравствуйте, {{ username }}!</h1>
    </div>

    <div class="about-platform">
      <h2 class="home-header">О нашем сервисе</h2>
      <p>
        Добро пожаловать на онлайн-доску объявлений! Здесь вы можете быстро и удобно покупать и продавать товары, а также предлагать и находить услуги.
      </p>
      <p>
        Наш сайт позволяет каждому пользователю размещать свои объявления в разных категориях: от недвижимости и автомобилей до электроники и бытовых услуг.
      </p>
      <p>
        Благодаря удобному поиску и фильтрам вы легко найдёте именно то, что вам нужно. А если хотите продать или предложить что-то своё — просто <router-link to="/register" class="register-link">зарегистрируйтесь</router-link> и разместите объявление, это займёт всего пару минут!
      </p>
      <p>
        Мы стремимся создать безопасную и удобную площадку для общения между продавцами и покупателями. Добавляйте товары в избранное, связывайтесь с авторами объявлений и находите выгодные предложения поблизости.
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Home',
  data() {
    return {
      username: 'гость',
    }
  },
  computed: {
    isGuest() {
      return this.username === 'гость'
    },
  },
  mounted() {
    const storedUser = localStorage.getItem('username')
    if (storedUser) {
      this.username = storedUser
    }
  },
}
</script>

<style scoped>
.home {
  max-width: 800px;
  margin: 30px auto;
  padding: 30px 20px;
  font-family: 'Roboto', sans-serif;
  color: #333;
}

.greeting {
  text-align: center;
  margin-bottom: 30px;
}

.greeting h1 {
  font-size: 3rem;
  color: #33cccc;
}

.home-header {
  font-size: 2rem;
  color: #33cccc;
  margin-bottom: 20px;
}

.about-platform p {
  font-size: 1.05rem;
  line-height: 1.7;
  margin-bottom: 15px;
}

.register-link {
  color: #00b3b3;
  text-decoration: none;
  font-weight: 500;
}

.register-link:hover {
  text-decoration: underline;
}
</style>
