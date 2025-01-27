<template>
    <div class="container bg-primary">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th class="bg-secondary text-info">ID</th>
              <th class="bg-secondary text-info">NOMBRE</th>
              <th class="bg-secondary text-info">DESCRIPCIÓN</th>
              <th class="bg-secondary text-info">TALLER</th>
              <th class="bg-secondary text-info">PRIORIDAD</th>
              <th class="bg-secondary text-info">ESTADO</th>
              <th class="bg-secondary text-info">ACCIONES</th>
            </tr>
          </thead>
          <tbody class="table-light">
            <tr v-for="maquina in maquinas" :key="maquina.id_maquina" class="cursor-pointer">
              <td class="font-medium bg-primary">{{ maquina.id_maquina }}</td>
              <td class="font-medium bg-primary">{{ maquina.nombre }}</td>
              <td class="font-medium bg-primary">{{ maquina.descripcion }}</td>
              <td class="font-medium bg-primary">
                {{ getTallerName(maquina.id_taller) }}
              </td>
              <td class="bg-primary">
                <span :class="getPrioridadClass(maquina.prioridad)">
                  {{ maquina.prioridad }}
                </span>
              </td>
              <td class="bg-primary">
                <span :class="getEstadoClass(maquina.estado)">
                  {{ maquina.estado }}
                </span>
              </td>
              <td class="bg-primary">
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-info bg-secondary text-info" @click="cambiarEstado(maquina)">
                    <i class="bi" :class="maquina.estado === 'Habilitado' ? 'bi-x-circle' : 'bi-check-circle'"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-info bg-secondary text-info" @click="editarMaquina(maquina)">
                    <i class="bi bi-pencil"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import useMaquinas from '@/composables/useMaquinas';
  const { getEstadoClass, getPrioridadClass } = useMaquinas();
  const props = defineProps({
    maquinas: {
      type: Array,
      required: true
    },
      talleres: {
      type: Array,
      required: true
    }
  });
  const emit = defineEmits(['cambiar-estado', 'editar-maquina']);
  // Función para obtener el nombre de un taller
  const getTallerName = (tallerId) => {
      const taller = props.talleres.find(t => t.id_taller === tallerId);
      return taller ? taller.nombre : 'Unknown';
  };
  const cambiarEstado = (maquina) => {
    emit('cambiar-estado', maquina);
  };
  
  const editarMaquina = (maquina) => {
    emit('editar-maquina', maquina);
  };
  </script>