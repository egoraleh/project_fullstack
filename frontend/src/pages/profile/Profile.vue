<template>
  <div class="profile-page">
    <h1 class="profile-header">Профиль пользователя</h1>

    <div class="avatar-block">
      <img :src="avatarUrl" alt="Аватар" class="avatar" />
    </div>

    <div class="info-block">
      <p><strong>Имя:</strong> {{ name }}</p>
      <p><strong>Фамилия:</strong> {{ surname }}</p>
      <p><strong>Email:</strong> {{ email }}</p>
    </div>

    <button class="profile-button" @click="goToEditProfile">Изменить профиль</button>
  </div>
</template>

<script>
export default {
  name: "Profile",
  data() {
    return {
      name: 'Егор',
      surname: 'Иванов',
      email: 'egor@example.com',
      avatarUrl: 'https://png.pngtree.com/thumb_back/fw800/background/20230610/pngtree-picture-of-a-blue-bird-on-a-black-background-image_2937385.jpg' // временное изображение
    }
  },
  methods: {
    goToEditProfile() {
      // тут можно сделать переход на страницу редактирования профиля или открыть модалку
      this.$router.push('/edit-profile') // если используешь vue-router
    }
  }
}
</script>