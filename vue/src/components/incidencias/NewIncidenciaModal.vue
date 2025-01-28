<template>
    <div class="modal fade " :class="{ 'show d-block': showModal }" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content bg-primary">
          <div class="modal-header">
            <h5 class="modal-title">Nueva Incidencia</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="incidencia-titulo" class="form-label">Título</label>
              <input
                type="text"
                class="form-control"
                id="incidencia-titulo"
                v-model="newIncidencia.descripcion"
                required
              />
            </div>
            <div class="mb-3">
              <label for="incidencia-taller" class="form-label">Taller</label>
              <select class="form-select" id="incidencia-taller" v-model="selectedTaller" required @change="updateFilteredMachines">
                <option value="">Selecciona un taller</option>
                <option v-for="taller in talleres" :key="taller.id_taller" :value="taller">
                  {{ taller.nombre }}
                </option>
              </select>
            </div>
            <div class="mb-3">
              <label for="incidencia-maquina" class="form-label">Máquina</label>
              <select class="form-select" id="incidencia-maquina" v-model="selectedMachine" required>
                 <option value="">Selecciona una máquina</option>
                <option v-for="maquina in filteredMaquinas" :key="maquina.id_maquina" :value="maquina">
                  {{ maquina.nombre }}
                </option>
              </select>
            </div>
            <div class="mb-3">
              <label for="incidencia-tipo-averia" class="form-label">Tipo de Avería</label>
              <select
                class="form-select"
                id="incidencia-tipo-averia"
                v-model="selectedTipoAveria"
                required
              >
                <option value="">Selecciona un tipo de avería</option>
                <option v-for="tipoAveria in tipoAverias" :key="tipoAveria.id_tipo_averia" :value="tipoAveria">
                  {{ tipoAveria.nombre }}
                </option>
              </select>
            </div>
            <div class="mb-3">
              <label for="incidencia-gravedad" class="form-label">Gravedad</label>
              <select
                class="form-select"
                id="incidencia-gravedad"
                v-model="newIncidencia.gravedad"
                required
              >
                <option value="Maquina parada">Maquina parada</option>
                <option value="Maquina en Marcha">Maquina en Marcha</option>
                <option value="Aviso">Aviso</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="incidencia-multimedia" class="form-label">Subir Multimedia</label>
              <input 
                type="file" 
                class="form-control" 
                id="incidencia-multimedia" 
                @change="handleFileChange"
              />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="createIncidencia">Guardar</button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showModal" class="modal-backdrop fade show"></div>
    </template>
    
    <script setup>
    import { reactive, computed } from 'vue';
    import useIncidencias from '@/composables/useIncidencias';
    
    const props = defineProps({
      showModal: Boolean,
      talleres: Array,
      maquinas: Array,
      filteredMaquinas: Array,
      tipoAverias: Array,
      filters: Object,
       selectedTaller: Object,
       selectedMachine: Object,
       selectedTipoAveria:Object
    });
    const emit = defineEmits(['close-modal', 'create-incidencia', 'update:selectedTaller', 'update:selectedMachine', 'update:selectedTipoAveria']);
    
    const newIncidencia = reactive({
      descripcion: '',
      gravedad: '',
      multimedia: [],
    });
    
    const selectedTaller = computed({
        get: () => props.selectedTaller,
        set: (value) => emit('update:selectedTaller', value)
      });
      const selectedMachine = computed({
        get: () => props.selectedMachine,
        set: (value) => emit('update:selectedMachine', value)
      });
    const selectedTipoAveria = computed({
        get: () => props.selectedTipoAveria,
        set: (value) => emit('update:selectedTipoAveria', value)
    });
    
    
    const closeModal = () => {
      emit('close-modal');
    };

    const handleFileChange = (event) => {
      const files = event.target.files;
      if (files.length > 0) {
        // Aquí almacenamos solo el primer archivo en la propiedad reactiva
        newIncidencia.multimedia = files[0];  // Asignamos el primer archivo
      }
    };

    const { createIncidencia: createIncidenciaApi } = useIncidencias();

    
    const createIncidencia = async () => {
      const formData = new FormData();
      const userData = JSON.parse(localStorage.getItem('user_data'));

      // Añadimos los datos normales de la incidencia
      formData.append('descripcion', newIncidencia.descripcion);
      formData.append('gravedad', newIncidencia.gravedad);
      formData.append('id_maquina', selectedMachine.value.id_maquina);
      formData.append('id_tipo_averia', selectedTipoAveria.value.id_tipo_averia);
      formData.append('id_creador', userData.id);

      // Añadimos los archivos multimedia
      if (newIncidencia.multimedia) {
        formData.append('multimedia', newIncidencia.multimedia);
      }

      for (let [key, value] of formData.entries()) {
          console.log(`${key}: ${value}`);
      }

      // Llamamos a la función de la API de incidencias
      try {
        await createIncidenciaApi(formData);  // Usamos la función del composable
        closeModal();
      } catch (error) {
        console.error('Error creando la incidencia:', error);
      }
    };
    const updateFilteredMachines = () => {
          emit('update:selectedMachine', null)
      };
    </script>
    
    <style scoped>
    .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    outline: 0;
    }
    
    .modal.show {
    display: block;
    }
    
    .modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: #000;
    }
    
    .modal-backdrop.fade {
    opacity: 0;
    }
    
    .modal-backdrop.show {
    opacity: 0.5;
    }
    </style>