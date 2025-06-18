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
        <label>Номер телефона</label>
        <input class="profile-input" type="text" v-model="phoneNumber" ref="phoneInput" placeholder="+7 (9__) ___-__-__" required>
      </div>

      <div class="form-group">
        <label>Аватар</label>
        <input class="profile-input" type="file" @change="onAvatarSelected" accept="image/*" />
      </div>

      <div v-if="avatarPreview" class="avatar-preview">
        <img :src="avatarPreview" alt="Превью аватара" class="avatar" />
      </div>

      <div class="button-group">
        <button class="profile-button__cancel" type="button" @click="cancelEdit">Отмена</button>
        <button class="profile-button__submit" type="submit">Сохранить</button>
      </div>
    </form>
  </div>
</template>

<script>
import api from "../../services/axios"
import { useAuthStore } from "../../stores/authStore"

export default {
  name: "EditProfile",

  data() {
    const store = useAuthStore()

    return {
      store,
      userId: store.user?.id || null,
      name: store.user?.name || '',
      surname: store.user?.surname || '',
      email: store.user?.email || '',
      phoneNumber: store.user?.phone_number || '',
      avatarFile: null,
      avatarPreview: null
    }
  },

  async mounted() {
    await this.store.fetchCurrentUser()

    this.userId  = this.store.user?.id
    this.name    = this.store.user?.name
    this.surname = this.store.user?.surname
    this.email   = this.store.user?.email
    this.phoneNumber = this.store.user?.phoneNumber

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
        this.phoneNumber = '';
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

  beforeUnmount() {
    if (this.avatarPreview) {
      URL.revokeObjectURL(this.avatarPreview)
    }
  },

  methods: {
    onAvatarSelected(event) {
      const file = event.target.files[0]
      if (file) {
        if (this.avatarPreview) {
          URL.revokeObjectURL(this.avatarPreview)
        }
        this.avatarFile = file
        this.avatarPreview = URL.createObjectURL(file)
      }
    },

    async saveProfile() {
      try {
        const formData = new FormData()
        formData.append("name", this.name)
        formData.append("surname", this.surname)
        formData.append("email", this.email)
        formData.append("phone_number", this.phoneNumber)

        if (this.avatarFile) {
          formData.append("avatar", this.avatarFile)
        }

        await api.post(`/user/${this.userId}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        await this.store.fetchCurrentUser()
        this.$router.push('/profile')

      } catch (error) {
        console.error('Ошибка при сохранении профиля:', error)
      }
    },

    cancelEdit() {
      this.$router.push('/profile')
    }
  }
}
</script>