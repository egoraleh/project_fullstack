<template>
  <header>
    <div class="header-container">
      <h1 class="logo">AdPoint</h1>
      <nav>
        <router-link to="/">Главная</router-link>

        <template v-if="user">
          <router-link to="/profile">Профиль</router-link>
          <router-link to="/ads">Мои объявления</router-link>
          <router-link to="/ads/favorites">Избранные</router-link>
          <a href="#" @click.prevent="logout">Выйти</a>
        </template>

        <template v-else>
          <router-link to="/register">Регистрация</router-link>
          <router-link to="/login">Вход</router-link>
        </template>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '../stores/authStore'
import { computed } from 'vue'

const authStore = useAuthStore()

const user = computed(() => authStore.user)

function logout() {
  authStore.logout()
}
</script>