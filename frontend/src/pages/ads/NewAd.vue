<template>
  <div class="add-ad">
    <h2>Добавить объявление</h2>
    <form @submit.prevent="submitAd">
      <div>
        <label>Название:</label>
        <input v-model="title" required />
      </div>

      <div>
        <label>Цена:</label>
        <input type="number" v-model="price" required />
      </div>

      <div>
        <label>Описание:</label>
        <textarea v-model="description" required></textarea>
      </div>

      <div>
        <label>Адрес:</label>
        <input v-model="address" required />
      </div>

      <div>
        <label>Категория:</label>
        <select v-model="category_id" required>
          <option value="1">Электроника</option>
          <option value="2">Одежда</option>
          <option value="3">Авто</option>
        </select>
      </div>

      <div>
        <label>Изображение:</label>
        <input type="file" @change="handleFile" accept="image/*" />
        <div v-if="preview">
          <img :src="preview" alt="preview" style="max-width: 200px; margin-top: 10px" />
        </div>
      </div>

      <button type="submit">Опубликовать</button>
    </form>
  </div>
</template>

<script>
import api from '../../services/axios'
import {useAuthStore} from "../../stores/authStore";

export default {
  name: 'NewAd',

  data() {
    return {
      title: '',
      user_id: useAuthStore().$state.user.id,
      price: 0,
      description: '',
      address: '',
      category_id: 1,
      imageUrl: '',
      preview: null
    }
  },

  methods: {
    async handleFile(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.preview = e.target.result;
        };
        reader.readAsDataURL(file);
        this.selectedFile = file; // сохраним файл
      }
    },

    async submitAd() {
      let imageUrl = '';

      if (this.selectedFile) {
        const formData = new FormData();
        formData.append('image', this.selectedFile);

        try {
          const res = await api.post('/ads/upload-image', formData, {
            headers: {'Content-Type': 'multipart/form-data'}
          });
          imageUrl = res.data.imageUrl;
        } catch (e) {
          console.error(e);
          alert('Ошибка при загрузке изображения');
          return;
        }
      }

      const adData = {
        title: this.title,
        price: this.price,
        description: this.description,
        address: this.address,
        category_id: this.category_id,
        user_id: this.user_id,
        image_url: imageUrl
      };

      try {
        await api.post('/ads/new', adData);
        alert('Объявление успешно добавлено!');
        this.$router.push('/');
      } catch (e) {
        console.error(e);
        alert('Ошибка при добавлении объявления');
      }
    }
  }
}
</script>