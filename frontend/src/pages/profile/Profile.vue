<template>
  <div class="profile-page">
    <h1 class="profile-header">Профиль пользователя</h1>

    <div class="avatar-block">
      <img v-if="avatarUrl" :src="avatarUrl" alt="Аватар" class="avatar" />
    </div>

    <div class="info-block">
      <p><strong>Имя:</strong> {{ name }}</p>
      <p><strong>Фамилия:</strong> {{ surname }}</p>
      <p><strong>Email:</strong> {{ email }}</p>
      <p><strong>Телефон:</strong> {{ phoneNumber }}</p>
    </div>

    <button class="profile-button" @click="goToEditProfile">Изменить профиль</button>
  </div>
</template>

<script>
import api from "../../services/axios"
import { useAuthStore } from "../../stores/authStore"

export default {
  name: "Profile",

  data() {
    const store = useAuthStore()

    return {
      store,
      userId: store.user?.id || null,
      name: store.user?.name || '',
      surname: store.user?.surname || '',
      email: store.user?.email || '',
      phoneNumber: store.user?.phoneNumber || '',
      avatarUrl: null
    }
  },

  async mounted() {
    await this.store.fetchCurrentUser()

    this.userId      = this.store.user?.id
    this.name        = this.store.user?.name
    this.surname     = this.store.user?.surname
    this.email       = this.store.user?.email
    this.phoneNumber = this.store.user?.phoneNumber

    await this.loadAvatar()
  },

  beforeUnmount() {
    if (this.avatarUrl) {
      URL.revokeObjectURL(this.avatarUrl)
    }
  },

  methods: {
    async loadAvatar() {
      if (!this.userId) return
      try {
        const response = await api.get(`/user/avatar/${this.userId}`, {
          responseType: 'blob'
        })
        this.avatarUrl = URL.createObjectURL(response.data)
      } catch (error) {
        console.error('Не удалось загрузить аватар:', error)
      }
    },

    goToEditProfile() {
      this.$router.push('/edit-profile')
    }
  }
}
</script>