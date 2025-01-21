<template>
    <div class="incidencias-container bg-primary pt-5">
      <h1 class="text-2xl font-bold px-3 mb-6">Máquinas</h1>
  
      <!-- Barra de búsqueda -->
      <div class="search-container mb-6">
        <div class="relative">
          <input
            type="text"
            placeholder="Buscar..."
            v-model="searchQuery"
            class="search-input"
          />
          <SearchIcon class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
        </div>
      </div>
  
      <!-- Tabla -->
      <div class="table-container">
        <table class="table-auto">
          <thead>
            <tr class="table-lineas bg-secondary">
              <th>ID</th>
              <th>NOMBRE</th>
              <th>DESCRIPCIÓN</th>
              <th>TALLER</th>
              <th>PRIORIDAD</th>
              <th>ESTADO</th>
            </tr>
          </thead>
          <tbody class="table-body">
            <tr
              v-for="(maquina, i) in filteredMaquinas"
              :key="i"
              class="table-lineas cursor-pointer"
              @click="goToMaquina(maquina.id_maquina)"
            >
              <td class="font-medium">{{ maquina.id_maquina }}</td>
              <td class="font-medium">{{ maquina.nombre }}</td>
              <td class="font-medium">{{ maquina.descripcion }}</td>
              <td class="font-medium">{{ maquina.id_taller }}</td>
              <td>
                <span :class="getPrioridadClass(maquina.prioridad)">
                  {{ maquina.prioridad }}
                </span>
              </td>
              <td>
                <span :class="getEstadoClass(maquina.estado)">
                  {{ maquina.estado }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useRouter } from 'vue-router';
  import { SearchIcon } from 'lucide-vue-next';
  import useMaquinas from '@/composables/useMaquinas';
  
  const {
    filteredMaquinas,
    searchQuery,
    getEstadoClass,
    getPrioridadClass,
  } = useMaquinas();
  
  const router = useRouter();
  
  const goToMaquina = (id) => {
    router.push({name: 'MaquinaIndividual', params: { id: id}});
  };
  </script>
  
  <style scoped>
  
  </style>
  
  