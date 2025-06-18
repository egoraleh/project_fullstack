<template>
  <div class="home-container">
    <h1 class="page-title">Доска объявлений</h1>

    <div class="search-bar">
      <input type="text" v-model="searchQuery" placeholder="Поиск по объявлениям..." />
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
      <AdCard v-for="ad in filteredAds" :key="ad.id" :ad="ad" />
    </div>
  </div>
</template>

<script>
import api from '../../services/axios'
import AdCard from "../../components/AdCard.vue"

export default {
  name: "Ads",
  components: { AdCard },
  data() {
    return {
      searchQuery: "",
      selectedCategory: "",
      selectedSort: "newest",
      categories: ["Транспорт", "Недвижимость", "Электроника", "Одежда", "Услуги"],
      ads: []
    };
  },
  computed: {
    filteredAds() {
      let result = this.ads

      if (this.searchQuery) {
        result = result.filter(ad =>
            ad.title.toLowerCase().includes(this.searchQuery.toLowerCase())
        )
      }

      if (this.selectedCategory) {
        result = result.filter(ad => ad.category === this.selectedCategory)
      }

      if (this.selectedSort === "cheapest") {
        result.sort((a, b) => a.price - b.price)
      } else if (this.selectedSort === "expensive") {
        result.sort((a, b) => b.price - a.price)
      } else {
        result.sort((a, b) => b.id - a.id)
      }

      return result
    }
  },
  methods: {
    async fetchAds() {
      try {
        const res = await api.get('/ads')
        this.ads = res.data
      } catch (error) {
        console.error('Ошибка при загрузке объявлений:', error)
        alert('Не удалось загрузить объявления')
      }
    }
  },
  created() {
    this.fetchAds()
  }
}
</script>