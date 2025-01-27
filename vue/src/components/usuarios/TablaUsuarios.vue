<template>
    <div class="container">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>FOTO</th>
              <th>NOMBRE</th>
              <th>APELLIDO</th>
              <th>CORREO</th>
              <th>CAMPUS</th>
              <th>ROL</th>
  
              <th>ESTADO</th>
              <th>ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in usuarios" :key="user.id_usuario">
              <td>
                <i class="bi bi-person-circle fs-2"></i>
              </td>
              <td>{{ user.nombre }}</td>
              <td>{{ user.apellido }}</td>
              <td>{{ user.correo }}</td>
              <td>{{ user.campus.nombre }}</td>
              <td>
                <span class="badge" :class="getRoleBadgeClass(user.rol)">
                  {{ user.rol }}
                </span>
              </td>
  
              <td>
                <span
                  class="badge"
                  :class="
                    user.estado === 'Habilitado' || user.estado === true ? 'bg-success' : 'bg-danger'
                  "
                >
                  {{
                    user.estado === 'Habilitado' || user.estado === true
                      ? 'Habilitado'
                      : 'Deshabilitado'
                  }}
                </span>
              </td>
              <td>
                <div class="btn-group">
                  <button
                    class="btn btn-sm"
                    :class="user.estado ? 'btn-outline-danger' : 'btn-outline-success'"
                    @click="confirmarDeshabilitarUsuario(user)"
                  >
                    <i class="bi" :class="user.estado ? 'bi-person-x' : 'bi-person-check'"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-primary" @click="editarUsuario(user)">
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