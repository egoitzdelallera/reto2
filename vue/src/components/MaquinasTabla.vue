<template>
    <div class="incidencias-container bg-primary pt-5 relative">
      <h1 class="text-2xl font-bold px-3 mb-6">Máquinas</h1>
  
      <!-- Botón Nueva Máquina -->
      <div class="mb-4 px-3 flex justify-end">
        <button @click="openModal" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
          Nueva Máquina
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
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  import { SearchIcon } from 'lucide-vue-next';
  import useMaquinas from '@/composables/useMaquinas';
  import useTalleres from '@/composables/useTalleres';
  
  
  const {
    filteredMaquinas,
    searchQuery,
    getEstadoClass,
    getPrioridadClass,
    createMaquina,
    fetchMaquinas,
  } = useMaquinas();
  const {talleres, fetchTalleres} = useTalleres();
  const router = useRouter();
  
  // Modal state
  const isModalOpen = ref(false);
  const newMaquina = ref({
    nombre: '',
    descripcion: '',
    id_taller: '',
    prioridad: 'Baja',
    estado: 'Habilitado' // Default state for new machine
  });
  
  //Alertas
  const alertMessage = ref(null);
  const alertClass = ref(null);
  
  
  const openModal = () => {
    isModalOpen.value = true;
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
      console.log("data to send:", newMaquina.value);
      try{
        await createMaquina(newMaquina.value);
        alertClass.value = 'bg-green-200 text-green-800 border-2 border-green-800 p-2 rounded'
        alertMessage.value = 'Maquina creada correctamente';
        await fetchMaquinas()
        setTimeout(() =>{
            closeModal();
            alertMessage.value = null;
          },2000)
      }catch (error){
         alertClass.value = 'bg-red-200 text-red-800 border-2 border-red-800 p-2 rounded'
         alertMessage.value = error.message;
         setTimeout(()=>{
           alertMessage.value = null;
         },2000)
      }
  };
  
  const goToMaquina = (id) => {
    router.push({ name: 'MaquinaIndividual', params: { id: id } });
  };
  
  onMounted(async () => {
        await fetchTalleres();
        console.log('Talleres fetched:', talleres.value);
    });
  </script>
  
  <style scoped>
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