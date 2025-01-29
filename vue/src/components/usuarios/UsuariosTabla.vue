<template>
    <div class="container-fluid bg-primary p-5">
         <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>
       <CabeceraTabla @cambiar-rol="cambiarRol" @buscar="actualizarBusqueda" :selected-role="selectedRole" @abrir-modal-nuevo-usuario="abrirModalNuevoUsuario" />
        <TablaUsuarios :usuarios="usuariosMostrados" @confirmar-deshabilitar-usuario="confirmarDeshabilitarUsuario" @editar-usuario="editarUsuario" />
         <Paginacion :total-paginas="totalPaginas" :pagina-actual="paginaActual" @cambiar-pagina="cambiarPagina" />
        <ModalCambiarEstado
            v-if="mostrarModalCambiarEstado"
             :user="usuarioSeleccionado"
             @cerrar-modal="cerrarModalCambiarEstado"
             @estado-cambiado="recargarUsuarios"
        />
         <ModalEditarUsuario
            v-if="mostrarModalEditarUsuario"
            :user="usuarioSeleccionado"
            @cerrar-modal="cerrarModalEditarUsuario"
            @usuario-editado="recargarUsuarios"
        />
        <ModalNuevoUsuario
            v-if="mostrarModalNuevoUsuario"
            @cerrar-modal="cerrarModalNuevoUsuario"
            @usuario-creado="recargarUsuarios"
            :campus="campus"
        />
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import CabeceraTabla from './CabeceraTabla.vue';
import TablaUsuarios from './TablaUsuarios.vue';
import Paginacion from './Paginacion.vue';
import ModalCambiarEstado from './ModalCambiarEstado.vue';
import ModalEditarUsuario from './ModalEditarUsuario.vue';
import ModalNuevoUsuario from './ModalNuevoUsuario.vue';
import useUsuarios from '@/composables/useUsuarios';
import useCampus from '@/composables/useCampus';

const props = defineProps({
    initialRole: {
        type: String,
        default: 'Todos'
    }
});

const { users, fetchUsers, error } = useUsuarios();
const { campus, fetchCampus } = useCampus();
const paginaActual = ref(1);
const TAMANO_PAGINA = 10;
const busqueda = ref('');
const usuariosFiltrados = ref([]);
const selectedRole = ref(props.initialRole)
const mostrarModalCambiarEstado = ref(false);
const mostrarModalEditarUsuario = ref(false);
const mostrarModalNuevoUsuario = ref(false);
const usuarioSeleccionado = ref(null);

// Calcula los usuarios a mostrar por pagina
const usuariosMostrados = computed(() => {
    const inicio = (paginaActual.value - 1) * TAMANO_PAGINA;
    const fin = inicio + TAMANO_PAGINA;
    return usuariosFiltrados.value.slice(inicio, fin);
});

// Calcula el numero total de paginas
const totalPaginas = computed(() => {
    return Math.ceil(usuariosFiltrados.value.length / TAMANO_PAGINA);
});

// Función para cambiar de página
const cambiarPagina = (pagina) => {
    paginaActual.value = pagina;
};
// Funcion para cambiar el rol seleccionado
const cambiarRol = (role) => {
  selectedRole.value = role;
  paginaActual.value = 1;
  filtrarUsuarios();
}
// Función para actualizar la búsqueda
const actualizarBusqueda = (query) => {
    busqueda.value = query;
    paginaActual.value = 1;
    filtrarUsuarios();
};
//Filtra los usuarios segun la busqueda
const filtrarUsuarios = () => {
    if(busqueda.value){
        usuariosFiltrados.value = users.value.filter(user => {
            return user.nombre.toLowerCase().includes(busqueda.value.toLowerCase()) ||
                user.apellido.toLowerCase().includes(busqueda.value.toLowerCase()) ||
                user.correo.toLowerCase().includes(busqueda.value.toLowerCase());
        });
    } else{
        usuariosFiltrados.value = [...users.value];
    }
};
// Confirmar deshabilitar usuario
const confirmarDeshabilitarUsuario = (user) => {
    usuarioSeleccionado.value = user;
    mostrarModalCambiarEstado.value = true;
}
const cerrarModalCambiarEstado = () => {
    mostrarModalCambiarEstado.value = false;
    usuarioSeleccionado.value = null;
};
// Editar usuario
const editarUsuario = (user) => {
    usuarioSeleccionado.value = user;
    mostrarModalEditarUsuario.value = true;
};
const cerrarModalEditarUsuario = () => {
  mostrarModalEditarUsuario.value = false;
  usuarioSeleccionado.value = null;
};
// Funcion para abrir modal nuevo usuario
const abrirModalNuevoUsuario = () => {
  mostrarModalNuevoUsuario.value = true;
}
const recargarUsuarios = async () => {
  await fetchUsers(selectedRole.value);
}
// Función para cerrar el modal de nuevo usuario
const cerrarModalNuevoUsuario = () => {
    mostrarModalNuevoUsuario.value = false;
};

onMounted(async () => {
    await fetchCampus();
    console.log("Campus from UsuariosTabla: ", campus.value);
    await fetchUsers(selectedRole.value);

});

watch(users, (newUsers) => {
    usuariosFiltrados.value = [...newUsers];
    filtrarUsuarios();
}, { immediate: true });
watch(selectedRole, () => {
   fetchUsers(selectedRole.value);
})

</script>