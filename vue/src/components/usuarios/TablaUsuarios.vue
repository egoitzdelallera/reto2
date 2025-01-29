<template>
    <div class="container bg-primary">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th class="bg-secondary text-info">FOTO</th>
              <th class="bg-secondary text-info">NOMBRE</th>
              <th class="bg-secondary text-info">APELLIDO</th>
              <th class="bg-secondary text-info">CORREO</th>
              <th class="bg-secondary text-info">CAMPUS</th>
              <th class="bg-secondary text-info">ROL</th>
              <th class="bg-secondary text-info">ESTADO</th>
              <th class="bg-secondary text-info">ACCIONES</th>
            </tr>
          </thead>
          <tbody class="table-light">
            <tr v-for="user in usuarios" :key="user.id_usuario">
              <td class="font-medium bg-primary">
                <i class="bi bi-person-circle fs-2"></i>
              </td>
              <td class="font-medium bg-primary">{{ user.nombre }}</td>
              <td class="font-medium bg-primary">{{ user.apellido }}</td>
              <td class="font-medium bg-primary">{{ user.correo }}</td>
              <td class="font-medium bg-primary">{{ user.campus.nombre }}</td>
              <td class="font-medium bg-primary">
                <span class="badge" :class="getRoleBadgeClass(user.rol)">
                  {{ user.rol }}
                </span>
              </td>
  
              <td class="font-medium bg-primary">
                <span
                  class="badge"
                  :class="
                    user.estado === 'Habilitado' || user.estado === true ? 'bg-black text-white fw-normal' : 'badge bg-secondary text-black fw-normal'
                  "
                >
                  {{
                    user.estado === 'Habilitado' || user.estado === true
                      ? 'Habilitado'
                      : 'Deshabilitado'
                  }}
                </span>
              </td>
                <td class="bg-primary">
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-info bg-secondary text-info" @click="confirmarDeshabilitarUsuario(user)" title="cambiar estado">
                    <i class="bi" :class="user.estado ? 'bi-person-x' : 'bi-person-check'"></i>
                    </button>
                  <button class="btn btn-sm btn-outline-info bg-secondary text-info" @click="editarUsuario(user)" title="editar usuario">
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
  import useUsuarios from '@/composables/useUsuarios';
  const { getRoleBadgeClass } = useUsuarios();
  const props = defineProps({
      usuarios: {
          type: Array,
          required: true
      }
  });
  const emit = defineEmits(['confirmar-deshabilitar-usuario', 'editar-usuario']);
  
  const confirmarDeshabilitarUsuario = (user) => {
    emit('confirmar-deshabilitar-usuario', user);
  }
  const editarUsuario = (user) => {
    emit('editar-usuario', user);
  }
  </script>