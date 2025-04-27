<template>
  <div class="edit-profile-page">
    <h1 class="profile-header">Редактировать профиль</h1>

    <form @submit.prevent="saveProfile">
      <div class="form-group">
        <label>Имя</label>
        <input class="profile-input" type="text" v-model="name" required />
      </div>

      <div class="form-group">
        <label>Фамилия</label>
        <input class="profile-input" type="text" v-model="surname" required />
      </div>

      <div class="form-group">
        <label>Email</label>
        <input class="profile-input" type="email" v-model="email" required />
      </div>

      <div class="form-group">
        <label>Ссылка на аватар</label>
        <input class="profile-input" type="url" v-model="avatarUrl" />
      </div>

      <div class="button-group">
        <button class="profile-button__cancel" type="button" @click="cancelEdit">Отмена</button>
        <button class="profile-button__submit" type="submit">Сохранить</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: "EditProfile",
  data() {
    return {
      name: 'Егор',
      surname: 'Иванов',
      email: 'egor@example.com',
      avatarUrl: 'https://via.placeholder.com/150'
    }
  },
  methods: {
    saveProfile() {
      this.$router.push('/profile');
    },
    cancelEdit() {
      this.$router.push('/profile');
    }
  }
}
</script>