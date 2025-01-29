<template>
  <div
    :class="['filter-panel bg-secondary', 'col-md-3', 'p-4', 'shadow-sm', 'overflow-auto', 'vh-100']"
     style="background-color: #f0f8ff; margin-top: 0;"
    :style="{ transform: showFilters ? 'translateX(0)' : 'translateX(-100%)' }"
  >
    <transition name="fade">
      <div v-if="showFilters" class="filter-panel-content">
        <h2 class="h4 mb-4">Filtros</h2>
        <button @click="$emit('toggle')" class="btn-close position-absolute top-0 end-0 m-2"
          aria-label="Cerrar filtros"></button>

        <!-- Filtros -->
        <div class="filters">
          <!-- Filtro de Gravedad -->
          <div class="mb-3">
            <button
              class="btn btn-outline-info w-100 text-start d-flex justify-content-between align-items-center"
              @click="toggleFilter('gravedad')"
            >
              <span>GRAVEDAD</span>
              <i :class="['bi', openFilters.gravedad ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
            </button>
            <div class="mt-2" v-show="openFilters.gravedad">
              <div v-for="option in filters.gravedad" :key="option.value" class="form-check">
                <input
                  :id="'gravedad-' + option.value"
                  type="checkbox"
                  :value="option.value"
                  v-model="selectedFilters.gravedad"
                  class="form-check-input bg-secondary"
                />
                <label :for="'gravedad-' + option.value" class="form-check-label  px-2 text-black">{{
                  option.label
                }}</label>
              </div>
            </div>
          </div>

          <!-- Filtro de Prioridad -->
          <div class="mb-3">
            <button
              class="btn btn-outline-info w-100 text-start d-flex justify-content-between align-items-center"
              @click="toggleFilter('prioridad')"
            >
              <span>PRIORIDAD</span>
              <i :class="['bi', openFilters.prioridad ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
            </button>
            <div class="mt-2" v-show="openFilters.prioridad">
              <div v-for="option in filters.prioridad" :key="option.value" class="form-check">
                <input
                  :id="'prioridad-' + option.value"
                  type="checkbox"
                  :value="option.value"
                  v-model="selectedFilters.prioridad"
                  class="form-check-input bg-secondary"
                />
                <label :for="'prioridad-' + option.value" class="form-check-label  px-2 text-black">{{
                  option.label
                }}</label>
              </div>
            </div>
          </div>

          <!-- Filtro de Taller -->
          <div class="mb-3">
            <button
              class="btn btn-outline-info w-100 text-start d-flex justify-content-between align-items-center"
              @click="toggleFilter('taller')"
            >
              <span>TALLER</span>
              <i :class="['bi', openFilters.taller ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
            </button>
            <div class="mt-2" v-show="openFilters.taller">
              <div v-for="taller in filteredTalleres" :key="taller.id_taller" class="form-check">
                <input
                  :id="'taller-' + taller.id_taller"
                  type="checkbox"
                  :value="taller.nombre"
                  v-model="selectedFilters.taller"
                  class="form-check-input bg-secondary"
                />
                <label :for="'taller-' + taller.id_taller" class="form-check-label  px-2 text-black">{{
                  taller.nombre
                }}</label>
              </div>
            </div>
          </div>

          <!-- Filtro de Estado -->
          <div class="mb-3">
            <button
              class="btn btn-outline-info w-100 text-start d-flex justify-content-between align-items-center"
              @click="toggleFilter('estado')"
            >
              <span>ESTADO</span>
              <i :class="['bi', openFilters.estado ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
            </button>
            <div class="mt-2" v-show="openFilters.estado">
              <div v-for="option in filters.estado" :key="option.value" class="form-check">
                <input
                  :id="'estado-' + option.value"
                  type="checkbox"
                  :value="option.value"
                  v-model="selectedFilters.estado"
                  class="form-check-input bg-secondary"
                />
                <label :for="'estado-' + option.value" class="form-check-label text-black px-2">{{
                  option.label
                }}</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue';

const props = defineProps({
  showFilters: Boolean,
  filters: Object,
  talleres: Array,
  selectedFilters: Object,
});

const emit = defineEmits(['toggle']);
const openFilters = reactive({
    gravedad: false,
    prioridad: false,
    taller: false,
    estado: false,
  });
const toggleFilter = (filterName) => {
  openFilters[filterName] = !openFilters[filterName];
};
const filteredTalleres = computed(() => {
      return props.talleres ? props.talleres.filter(taller => taller.estado === 'Habilitado') : [];
  });
</script>

<style scoped>
.filter-panel {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: 1001;
  transition: transform 0.3s ease-out;
  transform: translateX(-100%);
}

.filter-panel-content {
  position: relative;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
}
</style>