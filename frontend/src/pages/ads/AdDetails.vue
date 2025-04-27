<template>
  <div class="ad-details-container" v-if="ad">
    <h1 class="ad-title">{{ ad.title }}</h1>
    <p class="ad-description">{{ ad.description }}</p>
    <p class="ad-price">Цена: {{ ad.price }} ₽</p>
    <p class="ad-category">Категория: {{ ad.category }}</p>

    <router-link to="/" class="back-link">← Назад к объявлениям</router-link>
  </div>

  <div class="ad-details-container" v-else>
    <p>Объявление не найдено.</p>
    <router-link to="/" class="back-link">← Назад к объявлениям</router-link>
  </div>
</template>

<script>
export default {
  name: "AdDetails",
  data() {
    return {
      ad: null,
      ads: [
        { id: 1, title: "Продам велосипед", description: "Отличное состояние", price: 7000, category: "Транспорт" },
        { id: 2, title: "Айфон 12", description: "Как новый, полный комплект", price: 45000, category: "Электроника" },
        { id: 3, title: "Аренда квартиры", description: "2-комнатная, центр города", price: 25000, category: "Недвижимость" },
      ]
    };
  },
  created() {
    const adId = parseInt(this.$route.params.id);
    this.ad = this.ads.find(item => item.id === adId);
  }
};
</script>