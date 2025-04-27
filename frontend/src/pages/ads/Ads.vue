<template>
  <div class="home-container">
    <h1 class="page-title">Доска объявлений</h1>

    <div class="search-bar">
      <input type="text" v-model="searchQuery" placeholder="Поиск по объявлениям..." />
      <button @click="fetchAds">Поиск</button>
    </div>

    <div class="filters">
      <select v-model="selectedCategory">
        <option value="">Все категории</option>
        <option v-for="category in categories" :key="category" :value="category">
          {{ category }}
        </option>
      </select>

      <select v-model="selectedSort">
        <option value="newest">Сначала новые</option>
        <option value="cheapest">Сначала дешёвые</option>
        <option value="expensive">Сначала дорогие</option>
      </select>
    </div>

    <div class="ads-list">
      <div v-for="ad in filteredAds" :key="ad.id" class="ad-card">
        <h3>{{ ad.title }}</h3>
        <p>{{ ad.description }}</p>
        <p class="price">{{ ad.price }} ₽</p>
        <router-link :to="'/ad=' + ad.id" class="details-link">Подробнее</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      searchQuery: "",
      selectedCategory: "",
      selectedSort: "newest",
      categories: ["Транспорт", "Недвижимость", "Электроника", "Одежда", "Услуги"],
      ads: [
        { id: 1, title: "Продам велосипед", description: "Отличное состояние", price: 7000, category: "Транспорт" },
        { id: 2, title: "Айфон 12", description: "Как новый, полный комплект", price: 45000, category: "Электроника" },
        { id: 3, title: "Аренда квартиры", description: "2-комнатная, центр города", price: 25000, category: "Недвижимость" },
      ]
    };
  },
  computed: {
    filteredAds() {
      let result = this.ads;

      if (this.searchQuery) {
        result = result.filter(ad =>
            ad.title.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }

      if (this.selectedCategory) {
        result = result.filter(ad => ad.category === this.selectedCategory);
      }

      if (this.selectedSort === "cheapest") {
        result.sort((a, b) => a.price - b.price);
      } else if (this.selectedSort === "expensive") {
        result.sort((a, b) => b.price - a.price);
      } else {
        result.sort((a, b) => b.id - a.id);
      }

      return result;
    }
  },
  methods: {
    fetchAds() {
      console.log("Поиск по запросу:", this.searchQuery);
    }
  }
};
</script>