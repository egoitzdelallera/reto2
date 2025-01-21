<template>
    <div class="incidencias-container bg-primary pt-5 relative">
        <h1 class="text-2xl font-bold px-3 mb-6">Máquinas</h1>

        <div class="col-md-3">
            <button class="btn btn-dark" @click="openModal">
                <i class="bi bi-plus-circle me-2"></i>Nueva Máquina
            </button>
        </div>
        <!-- Barra de búsqueda -->
        <div class="search-container mb-6">
            <div class="relative">
                <input
                    type="text"
                    placeholder="Buscar..."
                    v-model="searchQuery"
                    class="search-input"
                    @input="handleSearch"
                />
                <SearchIcon class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5"/>
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
                    <th>ACCIONES</th>
                </tr>
                </thead>
                <tbody class="table-body">
                <tr
                    v-for="maquina in displayedMaquinas"
                    :key="maquina.id_maquina"
                    class="table-lineas cursor-pointer"
                    >
                    <td class="font-medium">{{ maquina.id_maquina }}</td>
                    <td class="font-medium">{{ maquina.nombre }}</td>
                    <td class="font-medium">{{ maquina.descripcion }}</td>
                    <td class="font-medium">
                        {{ getTallerName(maquina.id_taller) }}
                    </td>
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
                        <td>
                        <div class="btn-group">
                            <button
                            class="btn btn-sm"
                            :class="maquina.estado === 'Habilitado' ? 'btn-outline-danger' : 'btn-outline-success'"
                            @click="cambiarEstado(maquina)"
                            >
                           <i class="bi" :class="maquina.estado === 'Habilitado' ? 'bi-x-circle' : 'bi-check-circle'"></i>
                           </button>
                        <button
                            class="btn btn-sm btn-outline-primary"
                             @click="editarMaquina(maquina)"
                            >
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
            <!-- Loading spinner -->
            <div v-if="loading" class="text-center my-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
             <!-- Pagination -->
             <nav v-if="totalPages > 1" class="mt-4">
            <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button class="page-link text-black" @click="changePage(currentPage - 1)" aria-label="Previous">
                <span aria-hidden="true">«</span>
                </button>
            </li>
            <li v-for="pageNumber in totalPages" :key="pageNumber" class="page-item" :class="{ active: currentPage === pageNumber }">
                <button class="page-link text-black" @click="changePage(pageNumber)">{{ pageNumber }}</button>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <button class="page-link text-black" @click="changePage(currentPage + 1)" aria-label="Next">
                <span aria-hidden="true">»</span>
                </button>
            </li>
            </ul>
        </nav>

        <!-- Modal Nueva Máquina -->
        <div v-if="isModalOpen" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header border-b mb-4 pb-2">
                    <h5 class="text-xl font-bold">Nueva Máquina</h5>
                    <button @click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitNewMaquina">
                        <div class="mb-3">
                            <label for="newNombre" class="form-label block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" id="newNombre" v-model="newMaquina.nombre" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="mb-3">
                            <label for="newDescripcion" class="form-label block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="newDescripcion" v-model="newMaquina.descripcion" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="newTaller" class="form-label block text-sm font-medium text-gray-700">Taller</label>
                            <select id="newTaller" v-model="newMaquina.id_taller" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option v-for="taller in talleres" :key="taller.id_taller" :value="taller.id_taller">
                                    {{ taller.nombre }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="newPrioridad" class="form-label block text-sm font-medium text-gray-700">Prioridad</label>
                            <select id="newPrioridad" v-model="newMaquina.prioridad" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="Alta">Alta</option>
                                <option value="Media">Media</option>
                                <option value="Baja">Baja</option>
                            </select>
                        </div>
  
                        <div v-if="alertMessage" class="mt-4 text-center" :class="alertClass">
                            {{alertMessage}}
                        </div>
          
                    </form>
                </div>
                <div class="modal-footer border-t pt-4 flex justify-end">
                    <button type="button" @click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancelar</button>
                    <button type="submit" @click="submitNewMaquina" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </div>
        </div>
          <!-- Modal para Confirmar deshabilitar usuario -->
           <div v-if="isToggleStatusModalOpen" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header border-b mb-4 pb-2">
                    <h5 class="text-xl font-bold">Cambiar estado</h5>
                    <button @click="closeToggleStatusModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                 <p>¿Estás seguro de que quieres cambiar el estado de la máquina: <strong>{{ maquinaToToggle?.nombre }}</strong>?</p>
                    <div v-if="toggleAlertMessage" class="alert" :class="toggleAlertClass">
                        {{ toggleAlertMessage }}
                    </div>
                </div>
                <div class="modal-footer border-t pt-4 flex justify-end">
                     <button type="button" @click="closeToggleStatusModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancelar</button>
                    <button type="button" class="btn btn-danger" @click="toggleStatus">Cambiar estado</button>
                </div>
            </div>
        </div>
          <!-- Modal para Editar maquina -->
          <div v-if="isEditMaquinaModalOpen" class="modal-overlay">
             <div class="modal-container">
                <div class="modal-header border-b mb-4 pb-2">
                    <h5 class="text-xl font-bold">Editar Maquina</h5>
                    <button @click="closeEditMaquinaModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitEditMaquina">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" id="editNombre" v-model="maquinaToEdit.nombre" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="mb-3">
                            <label for="editDescripcion" class="form-label block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="editDescripcion" v-model="maquinaToEdit.descripcion" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editTaller" class="form-label block text-sm font-medium text-gray-700">Taller</label>
                            <select id="editTaller" v-model="maquinaToEdit.id_taller" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option v-for="taller in talleres" :key="taller.id_taller" :value="taller.id_taller">
                                    {{ taller.nombre }}
                                </option>
                            </select>
                        </div>
                         <div class="mb-3">
                            <label for="editPrioridad" class="form-label block text-sm font-medium text-gray-700">Prioridad</label>
                            <select id="editPrioridad" v-model="maquinaToEdit.prioridad" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="Alta">Alta</option>
                                <option value="Media">Media</option>
                                <option value="Baja">Baja</option>
                            </select>
                        </div>

                    <div v-if="editAlertMessage" class="mt-4 text-center" :class="editAlertClass">
                        {{ editAlertMessage }}
                    </div>

                    </form>
                </div>
                <div class="modal-footer border-t pt-4 flex justify-end">
                    <button type="button" @click="closeEditMaquinaModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancelar</button>
                    <button type="submit" class="btn btn-primary" @click="submitEditMaquina">Guardar Cambios</button>
                </div>
             </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { SearchIcon } from 'lucide-vue-next';
import useMaquinas from '@/composables/useMaquinas';
import useTalleres from '@/composables/useTalleres';
import axios from 'axios'
    const {
        maquinas,
        searchQuery,
        filteredMaquinas,
        getEstadoClass,
        getPrioridadClass,
        createMaquina,
        fetchMaquinas,
        loading,
        error
    } = useMaquinas();
    const { talleres, fetchTalleres } = useTalleres();
    const router = useRouter();
    const API_URL = 'http://localhost:8000/api/maquinas'
   // Modal state
   const isModalOpen = ref(false);
   const newMaquina = ref({
    nombre: '',
    descripcion: '',
    id_taller: '',
    prioridad: 'Baja',
    estado: 'Habilitado' // Default state for new machine
   });
    // Modal para acciones
    const isToggleStatusModalOpen = ref(false);
    const maquinaToToggle = ref(null);
    const toggleAlertMessage = ref(null);
    const toggleAlertClass = ref('');
    const isEditMaquinaModalOpen = ref(false);
    const maquinaToEdit = ref({});
    const editAlertMessage = ref(null);
    const editAlertClass = ref('');
   //Alertas
   const alertMessage = ref(null);
   const alertClass = ref(null);
    // Pagination
    const currentPage = ref(1);
    const PAGE_SIZE = 10;
    const displayedMaquinas = ref([]);
    
    // Calculate total number of pages
    const totalPages = computed(() => {
      return Math.ceil(maquinas.value.length / PAGE_SIZE);
    });
    
    // Update displayed machines
    const updateDisplayedMaquinas = () => {
        const start = (currentPage.value - 1) * PAGE_SIZE;
        const end = start + PAGE_SIZE;
        displayedMaquinas.value = maquinas.value.slice(start, end);
    };
   
   const openModal = () => {
       console.log('openModal function called')
       isModalOpen.value = true;
       console.log('isModalOpen value:', isModalOpen.value);
   };
   
   const closeModal = () => {
    isModalOpen.value = false;
    resetForm();
    alertMessage.value = null;
   };
   
   const resetForm = () => {
    newMaquina.value = {
      nombre: '',
      descripcion: '',
      id_taller: '',
      prioridad: 'Baja',
      estado: 'Habilitado'
    };
   };
   
   const submitNewMaquina = async () => {
    
      try{
        await createMaquina(newMaquina.value);
        alertClass.value = 'bg-green-200 text-green-800 border-2 border-green-800 p-2 rounded'
        alertMessage.value = 'Maquina creada correctamente';
        await fetchMaquinas()
        setTimeout(() =>{
            closeModal();
            alertMessage.value = null;
          },1000)
      }catch (error){
         alertClass.value = 'bg-red-200 text-red-800 border-2 border-red-800 p-2 rounded'
         alertMessage.value = error.message;
         setTimeout(()=>{
           alertMessage.value = null;
         },1000)
      }
   };
   
   const goToMaquina = (id) => {
    router.push({ name: 'MaquinaIndividual', params: { id: id } });
   };
   
   const getTallerName = (tallerId) => {
    const taller = talleres.value.find(t => t.id_taller === tallerId);
    return taller ? taller.nombre : 'Unknown';
   };
    const changePage = (page) => {
        currentPage.value = page;
        updateDisplayedMaquinas();
      }
         const handleSearch = () => {
            currentPage.value = 1;
            fetchMaquinas();
         };
        // Confirmar cambiar estado maquina
const cambiarEstado = (maquina) => {
    console.log("cambiarEstado called with:", maquina)
    maquinaToToggle.value = maquina;
    isToggleStatusModalOpen.value = true;

};
const closeToggleStatusModal = () => {
    isToggleStatusModalOpen.value = false;
    toggleAlertMessage.value = null;
    toggleAlertClass.value = '';
};
// Cambiar estado maquina
const toggleStatus = async () => {
    try {
            let token = localStorage.getItem('jwt_token');

            if (token === null || token === undefined || token === '') {
                console.error('Error: Token JWT is null, undefined, or empty.');
                toggleAlertClass.value = 'alert-danger';
                toggleAlertMessage.value = 'Error de autenticación. Token inválido';
                setTimeout(() => {
                toggleAlertMessage.value = null;
                toggleAlertClass.value = '';
                    closeToggleStatusModal();
                    }, 2000);
                return; // Salir de la función si el token no es válido
            }

      const headers = {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
      };
      await axios.patch(`${API_URL}/${maquinaToToggle.value.id_maquina}/toggle-status`, {}, {
        headers: headers
      });

        maquinaToToggle.value.estado = maquinaToToggle.value.estado === 'Habilitado' ? 'Deshabilitado' : 'Habilitado';

        const maquinaIndex = maquinas.value.findIndex(maquina => maquina.id_maquina === maquinaToToggle.value.id_maquina);
        if (maquinaIndex !== -1) {
            maquinas.value[maquinaIndex].estado = maquinaToToggle.value.estado;
            updateDisplayedMaquinas();
        }
    toggleAlertClass.value = 'alert-success';
    toggleAlertMessage.value = 'Estado de la maquina cambiado con exito';

    }catch(error){
        console.error('Error al cambiar estado de la maquina:', error);
        console.error('Response Data (toggleStatus):', error.response?.data);
        toggleAlertClass.value = 'alert-danger';
        toggleAlertMessage.value = 'Error al cambiar el estado de la maquina';
    }finally {
      setTimeout(() => {
         toggleAlertMessage.value = null;
        toggleAlertClass.value = '';
           closeToggleStatusModal();
        }, 2000);
     }
};
// Editar maquina
const editarMaquina = (maquina) => {
  console.log("editarMaquina called with:", maquina);
  maquinaToEdit.value = { ...maquina };
    editAlertMessage.value = null; // Resetear mensaje de alerta
    editAlertClass.value = '';
  isEditMaquinaModalOpen.value = true;
}
const closeEditMaquinaModal = () => {
    isEditMaquinaModalOpen.value = false;
      editAlertMessage.value = null; // Resetear mensaje de alerta
        editAlertClass.value = '';
}
// Función para enviar formulario de edición
const submitEditMaquina = async () => {
        console.log("Data to update:", maquinaToEdit.value);
      try {
        const token = localStorage.getItem('jwt_token');
        const headers = {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        };
          const dataToUpdate = {
            nombre: maquinaToEdit.value.nombre,
             descripcion: maquinaToEdit.value.descripcion,
            id_taller: maquinaToEdit.value.id_taller,
            prioridad: maquinaToEdit.value.prioridad,
        };
         console.log("URL to update:",`${API_URL}/${maquinaToEdit.value.id_maquina}`);
         const response = await axios.post(`${API_URL}/${maquinaToEdit.value.id_maquina}`, dataToUpdate, {
            headers: headers
          });

           const maquinaIndex = maquinas.value.findIndex(maquina => maquina.id_maquina === maquinaToEdit.value.id_maquina);
            if (maquinaIndex !== -1) {
              maquinas.value[maquinaIndex] = { ...maquinaToEdit.value, ...response.data.maquina};
                 updateDisplayedMaquinas();
        }

         editAlertClass.value = 'alert-success';
          editAlertMessage.value = 'Maquina actualizada con éxito';
      }catch (error) {
        console.error('Error al actualizar la máquina:', error);
          console.error('Response Data (submitEditMaquina):', error.response?.data);
          editAlertClass.value = 'alert-danger';
          editAlertMessage.value = 'Error al actualizar la máquina';
        } finally {
          setTimeout(() => {
              editAlertMessage.value = null;
              editAlertClass.value = '';
              closeEditMaquinaModal();
         }, 2000);
        }
}
    onMounted(async () => {
        await fetchTalleres();
        await fetchMaquinas();
        updateDisplayedMaquinas();
    });
   </script>
   
<style scoped>
.table th {
  white-space: nowrap;
}

.table td {
  vertical-align: middle;
}
/* Animación para nuevos elementos */
.table tr {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000; /* Ensure modal is on top of other content */
}

.modal-container {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    max-width: 600px;
    padding: 1.5rem;
    position: relative;
    width: 90%;
}
</style>