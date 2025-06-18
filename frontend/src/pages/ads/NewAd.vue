<template>
  <div class="add-ad">
    <h2 class="add-ad__header">Добавить объявление</h2>
    <form class="add-ad__form" @submit.prevent="submitAd">
      <div class="form-group">
        <label class="form-group__label">Название:</label>
        <input class="form-group__input" v-model="title" required />
      </div>

      <div class="form-group">
        <label class="form-group__label">Цена:</label>
        <input class="form-group__input" type="text" v-model="price" @input="onPriceInput" required />
      </div>

      <div class="form-group">
        <label class="form-group__label">Описание:</label>
        <textarea class="form-group__textarea" v-model="description" required></textarea>
      </div>

      <div class="form-group">
        <label class="form-group__label">Адрес:</label>
        <input class="form-group__input" v-model="address" required />
      </div>

      <div class="form-group">
        <label class="form-group__label">Категория:</label>
        <select class="form-group__select" v-model="category_id" required>
          <option value="1">Электроника</option>
          <option value="2">Одежда</option>
          <option value="3">Авто</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-group__label">Изображение:</label>
        <input class="form-group__file" type="file" @change="handleFile" accept="image/*" />
        <div v-if="preview" class="form-group__preview">
          <img :src="preview" alt="preview" />
        </div>
      </div>

      <div class="add-ad__actions">
        <button type="submit" class="add-ad__submit">Опубликовать</button>
        <button type="button" class="add-ad__cancel" @click="$router.back()">Отмена</button>
      </div>
    </form>
  </div>
</template>

<script>
import api from '../../services/axios'
import { useAuthStore } from "../../stores/authStore";

export default {
  name: 'NewAd',
  data() {
    return {
      title: '',
      user_id: useAuthStore().$state.user.id,
      price: '',
      description: '',
      address: '',
      category_id: 1,
      preview: null,
      selectedFile: null
    }
  },
  methods: {
    onPriceInput(e) {
      this.price = e.target.value.replace(/\D+/g, '')
    },
    handleFile(event) {
      const file = event.target.files[0];
      if (file) {
        this.selectedFile = file;
        const reader = new FileReader();
        reader.onload = e => this.preview = e.target.result;
        reader.readAsDataURL(file);
      }
    },
    async submitAd() {
      const parsedPrice = parseInt(this.price, 10) || 0;
      let imageUrl = '';
      if (this.selectedFile) {
        const formData = new FormData();
        formData.append('image', this.selectedFile);
        try {
          const res = await api.post('/ads/upload-image', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
          imageUrl = res.data.imageUrl;
        } catch {
          return alert('Ошибка при загрузке изображения');
        }
      }
      const adData = {
        title: this.title,
        price: parsedPrice,
        description: this.description,
        address: this.address,
        category_id: this.category_id,
        user_id: this.user_id,
        image_url: imageUrl
      };
      try {
        await api.post('/ads/new', adData);
        this.$router.push('/');
      } catch {
        alert('Ошибка при добавлении объявления');
      }
    }
  }
}
</script>