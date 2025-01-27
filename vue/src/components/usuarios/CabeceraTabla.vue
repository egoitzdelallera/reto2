<template>
    <div class="row text-white py-3 mb-4">
    <div class="col">
        <h2 class="text-2xl font-bold mx-5 px-3 mb-6">Usuarios: {{ selectedRole }}</h2>
    </div>
    <div class="container mb-4 bg-primary">
        <div class="row align-items-center">
        <div class="col-md-3">
            <button class="btn btn-dark" @click="abrirModalNuevoUsuario">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
            </button>
        </div>
        <div class="col-md-6 d-flex justify-content-center">
            <div class="btn-group" role="group" aria-label="Filtrar por rol">
            <button
                @click="cambiarRol('Todos')"
                class="btn"
                :class="selectedRole === 'Todos' ? 'btn-info text-primary' : 'btn-outline-info'"
                style="box-shadow: none;"
            >Todos</button>
            <button
                @click="cambiarRol('Administrador')"
                class="btn"
                :class="selectedRole === 'Administrador' ? 'btn-info text-primary' : 'btn-outline-info'"
                style="box-shadow: none;"
            >Administradores</button>
            <button
                @click="cambiarRol('Técnico')"
                class="btn"
                :class="selectedRole === 'Técnico' ? 'btn-info text-primary' : 'btn-outline-info'"
                style="box-shadow: none;"
            >Técnicos</button>
            <button
                @click="cambiarRol('Operario')"
                class="btn"
                :class="selectedRole === 'Operario' ? 'btn-info text-primary' : 'btn-outline-info'"
                style="box-shadow: none;"
            >Operarios</button>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search"></i>
            </span>
            <input
                type="text"
                class="form-control border-start-0"
                placeholder="Buscar..."
                v-model="searchQuery"
                @input="handleSearch"
            />
            </div>
        </div>
        </div>
    </div>
    </div>
</template>
<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const searchQuery = ref('');
const emit = defineEmits(['cambiar-rol', 'buscar', 'abrir-modal-nuevo-usuario']);
const props = defineProps({
    selectedRole: {
        type: String,
        required: true
    }
})
const handleSearch = () => {
  emit('buscar', searchQuery.value);
};
const cambiarRol = (role) => {
  emit('cambiar-rol', role);
};
const abrirModalNuevoUsuario = () => {
  emit('abrir-modal-nuevo-usuario');
}
</script>