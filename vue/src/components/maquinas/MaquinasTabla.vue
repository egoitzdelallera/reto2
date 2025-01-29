<template>
  <div class="container-fluid bg-primary p-5 relative w-full h-screen">
    <CabeceraTabla @abrir-modal="abrirModal" @buscar="actualizarBusqueda" />
    <TablaMaquinas :maquinas="maquinasMostradas" @cambiar-estado="cambiarEstado" @editar-maquina="editarMaquina"
    :talleres="talleres" />
    <Paginacion :total-paginas="totalPaginas" :pagina-actual="paginaActual" @cambiar-pagina="cambiarPagina" />
     <ModalNuevaMaquina
       v-if="mostrarModalNuevaMaquina"
       @cerrar-modal="cerrarModalNuevaMaquina"
       @maquina-creada="recargarMaquinas"
       :talleres="talleres"
       />
      <ModalCambiarEstado
        v-if="mostrarModalCambiarEstado"
        :maquina="maquinaSeleccionada"
        @cerrar-modal="cerrarModalCambiarEstado"
        @estado-cambiado="recargarMaquinas"
      />
     <ModalEditarMaquina
       v-if="mostrarModalEditarMaquina"
       :maquina="maquinaSeleccionada"
       @cerrar-modal="cerrarModalEditarMaquina"
       @maquina-editada="recargarMaquinas"
       :talleres="talleres"
    />
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import CabeceraTabla from './CabeceraTabla.vue';
import TablaMaquinas from './TablaMaquinas.vue';
import Paginacion from './Paginacion.vue';
import ModalNuevaMaquina from './ModalNuevaMaquina.vue'
import ModalCambiarEstado from './ModalCambiarEstado.vue';
import ModalEditarMaquina from './ModalEditarMaquina.vue';
import useMaquinas from '@/composables/useMaquinas';
import useTalleres from '@/composables/useTalleres';

const { maquinas, fetchMaquinas } = useMaquinas();
const { talleres, fetchTalleres } = useTalleres();
const paginaActual = ref(1);
const TAMANO_PAGINA = 10;
const busqueda = ref('');
const maquinasFiltradas = ref([]);
const mostrarModalNuevaMaquina = ref(false);
const mostrarModalCambiarEstado = ref(false);
const mostrarModalEditarMaquina = ref(false);
const maquinaSeleccionada = ref(null);
// Calcula las maquinas a mostrar por pagina
const maquinasMostradas = computed(() => {
  const inicio = (paginaActual.value - 1) * TAMANO_PAGINA;
  const fin = inicio + TAMANO_PAGINA;
  return maquinasFiltradas.value.slice(inicio, fin);
});
// Calcula el numero total de paginas
const totalPaginas = computed(() => {
    return Math.ceil(maquinasFiltradas.value.length / TAMANO_PAGINA);
});

// Funcion para abrir el modal de nueva maquina
const abrirModal = () => {
  mostrarModalNuevaMaquina.value = true;
};
// Funcion para cerrar el modal de nueva maquina
const cerrarModalNuevaMaquina = () => {
  mostrarModalNuevaMaquina.value = false;
};
const recargarMaquinas = async () => {
    await fetchMaquinas();
};

// Funcion para cambiar de pagina
const cambiarPagina = (pagina) => {
    paginaActual.value = pagina;
};
// Función para actualizar la búsqueda
const actualizarBusqueda = (query) => {
    busqueda.value = query;
    paginaActual.value = 1;
    filtrarMaquinas();
};
//Filtra las maquinas segun la busqueda
const filtrarMaquinas = () => {
  if(busqueda.value){
    maquinasFiltradas.value = maquinas.value.filter(maquina => {
        return maquina.nombre.toLowerCase().includes(busqueda.value.toLowerCase()) ||
            maquina.descripcion.toLowerCase().includes(busqueda.value.toLowerCase()) ||
            getTallerNombre(maquina.id_taller).toLowerCase().includes(busqueda.value.toLowerCase());
        });
    } else{
       maquinasFiltradas.value = [...maquinas.value];
  }
};

// Función para cambiar el estado de una máquina
const cambiarEstado = (maquina) => {
    maquinaSeleccionada.value = maquina;
    mostrarModalCambiarEstado.value = true;
};
const cerrarModalCambiarEstado = () => {
  mostrarModalCambiarEstado.value = false;
  maquinaSeleccionada.value = null;
}
// Función para editar una máquina
const editarMaquina = (maquina) => {
  maquinaSeleccionada.value = maquina;
  mostrarModalEditarMaquina.value = true;
}
const cerrarModalEditarMaquina = () => {
  mostrarModalEditarMaquina.value = false;
  maquinaSeleccionada.value = null;
}
// Función para obtener el nombre de un taller
const getTallerNombre = (tallerId) => {
    const taller = talleres.value.find(t => t.id_taller === tallerId);
    return taller ? taller.nombre : 'Unknown';
};

onMounted(async () => {
  await fetchTalleres();
    await fetchMaquinas();
});

watch(maquinas, (newMaquinas) => {
    maquinasFiltradas.value = [...newMaquinas];
    filtrarMaquinas();
}, {immediate: true});

</script>