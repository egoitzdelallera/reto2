<template>
  <div class="container-fluid mt-4">
    <div class="row row-cols-1 row-cols-md-2 g-4">
      <!-- Campus Section -->
      <div class="col">
        <div class="card custom-card h-100">
          <div class="card-header custom-card-header d-flex align-items-center">
            <h5 class="card-title mb-0 text-dark me-auto">Campus</h5>
            <button @click="showAddItemPopup('campus')" class="btn btn-sm btn-secondary bg-info w-25">
              <i class="bi bi-plus-lg"></i> Añadir
            </button>
          </div>
          <div class="card-body">
            <div v-if="campusLoading" class="text-center">
              <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
            <div v-else-if="campusError" class="alert alert-danger">{{ campusError }}</div>
            <ul v-else class="list-group list-group-flush">
              <li
                v-for="item in displayedCampus"
                :key="item.id_campus"
                class="list-group-item d-flex justify-content-between align-items-center custom-list-item"
              >
                <span class="text-dark">{{ item.nombre }}</span>
                <div>
                  <button
                    @click="showDisableConfirmPopup(item, 'campus')"
                    class="btn btn-sm btn-outline-secondary me-1"
                  >
                     <i :class="item.estado === 'Deshabilitado' ? 'bi-toggle-off' : 'bi-toggle-on'" class="bi"></i>
                  </button>
                  <button @click="showModifyPopup(item, 'campus')" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil"></i>
                  </button>
                </div>
              </li>
            </ul>
            <div v-if="campus.length > 6" class="d-flex justify-content-center">
              <button
                @click="toggleShowAllCampus"
                class="btn btn-link btn-sm mt-2 text-secondary custom-show-more-btn"
              >
                {{ showAllCampus ? 'Mostrar menos' : 'Mostrar más' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Talleres Section -->
      <div class="col">
        <div class="card custom-card h-100">
          <div class="card-header custom-card-header d-flex align-items-center">
            <h5 class="card-title mb-0 text-dark me-auto">Talleres</h5>
            <button @click="showAddItemPopup('talleres')" class="btn btn-sm btn-secondary  bg-info w-25">
              <i class="bi bi-plus-lg"></i> Añadir
            </button>
          </div>
          <div class="card-body">
            <input
              v-model="searchQuery"
              type="text"
              class="form-control mb-3 custom-input"
              placeholder="Buscar taller por nombre"
            />
            <div v-if="talleresLoading" class="text-center">
              <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
            <div v-else-if="talleresError" class="alert alert-danger">{{ talleresError }}</div>
            <ul v-else class="list-group list-group-flush">
              <li
                v-for="taller in displayedTalleres"
                :key="taller.id"
                class="list-group-item d-flex justify-content-between align-items-center custom-list-item"
              >
                <span class="text-dark">{{ taller.nombre }}</span>
                <div>
                  <button
                    @click="showDisableConfirmPopup(taller, 'talleres')"
                    class="btn btn-sm btn-outline-secondary me-1"
                  >
                    <i :class="taller.estado === 'Deshabilitado' ? 'bi-toggle-off' : 'bi-toggle-on'" class="bi"></i>
                  </button>
                  <button @click="showModifyPopup(taller, 'talleres')" class="btn btn-sm btn-outline-secondary ">
                    <i class="bi bi-pencil"></i>
                  </button>
                </div>
              </li>
            </ul>
            <div v-if="filteredTalleres.length > 6" class="d-flex justify-content-center">
              <button
                @click="toggleShowAllTalleres"
                class="btn btn-link btn-sm mt-2 text-secondary custom-show-more-btn"
              >
                {{ showAllTalleres ? 'Mostrar menos' : 'Mostrar más' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Tipo de Avería Section -->
      <div class="col">
        <div class="card custom-card h-100">
          <div class="card-header custom-card-header d-flex align-items-center">
            <h5 class="card-title mb-0 text-dark me-auto">Tipos de Avería</h5>
            <button @click="showAddItemPopup('tipoAverias')" class="btn btn-sm btn-secondary  bg-info w-25">
              <i class="bi bi-plus-lg"></i> Añadir
            </button>
          </div>
          <div class="card-body">
            <div v-if="tipoAveriaLoading" class="text-center">
              <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
            <div v-else-if="tipoAveriaError" class="alert alert-danger">{{ tipoAveriaError }}</div>
            <ul v-else class="list-group list-group-flush">
              <li
                v-for="tipoAveria in displayedTipoAverias"
                :key="tipoAveria.id_tipo_averia"
                class="list-group-item d-flex justify-content-between align-items-center custom-list-item"
              >
                <span class="text-dark">{{ tipoAveria.nombre }}</span>
                <div>
                  <button
                    @click="showDisableConfirmPopup(tipoAveria, 'tipoAverias')"
                    class="btn btn-sm btn-outline-secondary me-1" title="cambiar estado"
                  >
                    <i :class="tipoAveria.estado === 'Deshabilitado' ? 'bi-toggle-off' : 'bi-toggle-on'" class="bi"></i>
                  </button>
                  <button @click="showModifyPopup(tipoAveria, 'tipoAverias')" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil"></i>
                  </button>
                </div>
              </li>
            </ul>
            <div v-if="tipoAverias.length > 6" class="d-flex justify-content-center">
              <button
                @click="toggleShowAllTipoAverias"
                class="btn btn-link btn-sm mt-2 text-secondary custom-show-more-btn"
              >
                {{ showAllTipoAverias ? 'Mostrar menos' : 'Mostrar más' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Tipos de Mantenimiento Section -->
      <div class="col">
        <div class="card custom-card h-100">
          <div class="card-header custom-card-header d-flex align-items-center">
            <h5 class="card-title mb-0 text-dark me-auto">Tipos de Mantenimiento</h5>
            <button @click="showAddItemPopup('tiposMantenimiento')" class="btn btn-sm btn-secondary">
              <i class="bi bi-plus-lg"></i> Añadir
            </button>
          </div>
          <div class="card-body">
            <div v-if="tipoMantenimientoLoading" class="text-center">
              <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
            <div v-else-if="tipoMantenimientoError" class="alert alert-danger">{{ tipoMantenimientoError }}</div>
            <ul v-else class="list-group list-group-flush">
              <li
                v-for="tipo in displayedTiposMantenimiento"
                :key="tipo.id_tipo_mantenimiento"
                class="list-group-item d-flex justify-content-between align-items-center custom-list-item"
              >
                <span class="text-dark">{{ tipo.nombre }}</span>
                <div>
                  <button
                    @click="showDisableConfirmPopup(tipo, 'tiposMantenimiento')"
                    class="btn btn-sm btn-outline-secondary me-1"
                  >
                    <i :class="tipo.estado === 'Deshabilitado' ? 'bi-toggle-off' : 'bi-toggle-on'" class="bi"></i>
                  </button>
                  <button @click="showModifyPopup(tipo, 'tiposMantenimiento')" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil"></i>
                  </button>
                </div>
              </li>
            </ul>
            <div v-if="tiposMantenimiento.length > 6" class="d-flex justify-content-center">
              <button
                @click="toggleShowAllTiposMantenimiento"
                class="btn btn-link btn-sm mt-2 text-secondary custom-show-more-btn"
              >
                {{ showAllTiposMantenimiento ? 'Mostrar menos' : 'Mostrar más' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modify Modal -->
    <div
      class="modal fade"
      id="modifyModal"
      tabindex="-1"
      aria-labelledby="modifyModalLabel"
      aria-hidden="true"
      ref="modifyModal"
    >
      <div class="modal-dialog">
        <div class="modal-content custom-modal">
          <div class="modal-header custom-modal-header">
            <h5 class="modal-title text-dark" id="modifyModalLabel">Modificar {{ currentSection }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
           <div class="modal-body">
              <input
                  v-if="currentSection !== 'talleres' && modifyingItem"
                  v-model="modifyingItem.nombre"
                  class="form-control custom-input"
                  placeholder="Nombre del item"
              />
              <template v-if="currentSection === 'talleres'">
                  <input
                      v-if="modifyingItem"
                      v-model="modifyingItem.nombre"
                      class="form-control custom-input mb-3"
                      placeholder="Nombre del taller"
                      />
                      <!-- Select Box for Campus -->
                      <select
                         v-model="selectedCampus"
                          class="form-select custom-input mb-3"
                         @change="onCampusChange"
                      >
                          <option value="" disabled selected>Seleccionar Campus</option>
                          <option
                              v-for="campusItem in campus"
                              :key="campusItem.id_campus"
                              :value="campusItem.id_campus"
                          >
                          {{ campusItem.nombre }}
                         </option>
                      </select>
              </template>
          
           <textarea
              v-if="currentSection === 'tiposMantenimiento' && modifyingItem"
              v-model="modifyingItem.descripcion"
              class="form-control custom-input mt-3"
              placeholder="Descripción del tipo de mantenimiento"
              rows="3"
            ></textarea>
            <div v-if="errorMessage" class="alert alert-danger mt-3">{{ errorMessage }}</div>
          </div>
          <div class="modal-footer custom-modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="saveModification">Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Item Modal -->
    <div
      class="modal fade"
      id="addItemModal"
      tabindex="-1"
      aria-labelledby="addItemModalLabel"
      aria-hidden="true"
      ref="addItemModal"
    >
      <div class="modal-dialog">
        <div class="modal-content custom-modal">
          <div class="modal-header custom-modal-header">
            <h5 class="modal-title text-dark" id="addItemModalLabel">Añadir {{ currentSection }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Formulario para añadir talleres -->
            <template v-if="currentSection === 'talleres'">
              <input
                v-model="newItemName"
                class="form-control custom-input mb-3"
                placeholder="Nombre del nuevo taller"
                required
              />
              <!-- Modified Select Box -->
               <select
                  v-model="selectedCampus"
                  class="form-select custom-input mb-3"
                  @change="onCampusChange"
                >
                  <option value="" disabled selected>Seleccionar Campus</option>
                  <option
                    v-for="campusItem in campus"
                    :key="campusItem.id_campus"
                    :value="campusItem.id_campus"
                  >
                    {{ campusItem.nombre }}
                  </option>
                </select>
              <!-- End Modified Select Box -->
            </template>

            <!-- Formulario para añadir otros items -->
             <template v-else>
              <input v-model="newItemName" class="form-control custom-input mb-3" placeholder="Nombre del nuevo item" required>
             <input v-if="currentSection === 'campus'" v-model="newItemLocation" class="form-control custom-input mb-3" placeholder="Ubicación del campus">
            
                <textarea v-if="currentSection === 'tiposMantenimiento'" v-model="newItemDescription" class="form-control custom-input mb-3" placeholder="Descripción del tipo de mantenimiento" rows="3"></textarea>
              
              
          </template>
            <div v-if="errorMessage" class="alert alert-danger mt-3">{{ errorMessage }}</div>
          </div>
          <div class="modal-footer custom-modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="confirmAddItem">Añadir</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Disable Confirm Modal -->
    <div
      class="modal fade"
      id="disableConfirmModal"
      tabindex="-1"
      aria-labelledby="disableConfirmModalLabel"
      aria-hidden="true"
      ref="disableConfirmModal"
    >
      <div class="modal-dialog">
        <div class="modal-content custom-modal">
          <div class="modal-header custom-modal-header">
            <h5 class="modal-title text-dark" id="disableConfirmModalLabel">
              Confirmar
              {{ disablingItem && disablingItem.estado === 'Deshabilitado' ? 'Habilitación' : 'Deshabilitación' }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>
              ¿Está seguro de que desea
              {{ disablingItem && disablingItem.estado === 'Deshabilitado' ? 'habilitar' : 'deshabilitar' }} el item
              "{{ disablingItem ? disablingItem.nombre : '' }}"?
            </p>
          </div>
          <div class="modal-footer custom-modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="confirmDisable">Confirmar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Modal } from 'bootstrap';
import useCampus from '@/composables/useCampus';
import useTalleres from '@/composables/useTalleres';
import useTipoAveria from '@/composables/useTipoAveria';
import useTiposMantenimiento from '@/composables/useTiposMantenimiento';

const {
    campus,
    loading: campusLoading,
    error: campusError,
    fetchCampus,
    updateCampus,
     toggleCampusStatus,
    createCampus
} = useCampus();
const {
    talleres,
    filteredTalleres,
    loading: talleresLoading,
    error: talleresError,
    fetchTalleres,
    createTaller,
      updateTaller,
    toggleTallerStatus,
    searchQuery,
    filterTalleres
} = useTalleres();
const {
    tipoAverias,
    loading: tipoAveriaLoading,
    error: tipoAveriaError,
    fetchTipoAverias,
    createTipoAveria,
    updateTipoAveria,
    toggleTipoAveriaStatus
} = useTipoAveria();
const {
    tiposMantenimiento,
    loading: tipoMantenimientoLoading,
    error: tipoMantenimientoError,
    fetchTiposMantenimiento,
    createTipoMantenimiento,
    updateTipoMantenimiento,
    toggleTipoMantenimientoStatus
} = useTiposMantenimiento();

const modifyingItem = ref(null);
const modifyModal = ref(null);
const addItemModal = ref(null);
const disableConfirmModal = ref(null);
const newItemName = ref('');
const newItemLocation = ref('');
const newItemDescription = ref('');
const currentSection = ref('');
const disablingItem = ref(null);
const currentSectionDisable = ref('');
const errorMessage = ref('');

const showAllCampus = ref(false);
const showAllTalleres = ref(false);
const showAllTipoAverias = ref(false);
const showAllTiposMantenimiento = ref(false);

const displayedCampus = computed(() =>
    showAllCampus.value ? campus.value : campus.value.slice(0, 6)
);
const displayedTalleres = computed(() =>
    showAllTalleres.value ? filteredTalleres.value : filteredTalleres.value.slice(0, 6)
);
const displayedTipoAverias = computed(() =>
    showAllTipoAverias.value ? tipoAverias.value : tipoAverias.value.slice(0, 6)
);
const displayedTiposMantenimiento = computed(() =>
    showAllTiposMantenimiento.value
        ? tiposMantenimiento.value
        : tiposMantenimiento.value.slice(0, 6)
);

let modifyModalInstance = null;
let addModalInstance = null;
let disableModalInstance = null;

onMounted(() => {
    // Initialize modal instances after the DOM is mounted
    modifyModalInstance = new Modal(document.getElementById('modifyModal'));
    addModalInstance = new Modal(document.getElementById('addItemModal'));
    disableModalInstance = new Modal(document.getElementById('disableConfirmModal'));

    // Fetch data
    fetchCampus();
    fetchTalleres();
    fetchTipoAverias();
    fetchTiposMantenimiento();
});

  const showModifyPopup = (item, section) => {
        console.log('Item to modify:', item);
    modifyingItem.value = { ...item };
    currentSection.value = section;
    if (currentSection.value === 'talleres') {
      selectedCampus.value = item.id_campus;
    }
      if (modifyModalInstance) {
          modifyModalInstance.show();
      }
  };

const saveModification = async () => {
    if (modifyingItem.value) {
        let updateFunction;
        let fetchFunction;
        let updateData = { nombre: modifyingItem.value.nombre };
        let itemId;

        switch (currentSection.value) {
            case 'campus':
                updateFunction = updateCampus;
                fetchFunction = fetchCampus;
                updateData.ubicacion = modifyingItem.value.ubicacion;
                itemId = modifyingItem.value.id_campus;
                break;
            case 'talleres':
                updateFunction = updateTaller;
                fetchFunction = fetchTalleres;
                updateData = {
                    nombre: modifyingItem.value.nombre,
                    id_campus: selectedCampus.value
                };
                itemId = modifyingItem.value.id_taller;
                break;
            case 'tipoAverias':
                updateFunction = updateTipoAveria;
                fetchFunction = fetchTipoAverias;
                itemId = modifyingItem.value.id_tipo_averia;
                break;
            case 'tiposMantenimiento':
                updateFunction = updateTipoMantenimiento;
                fetchFunction = fetchTiposMantenimiento;
                updateData.descripcion = modifyingItem.value.descripcion;
                itemId = modifyingItem.value.id_tipo_mantenimiento;
                break;
            default:
                return;
        }
         try {
                if (!itemId) {
                    throw new Error(
                        `Item ID is undefined for ${currentSection.value}: ${JSON.stringify(
                            modifyingItem.value
                        )}`
                    );
                }
                console.log('Datos de modificación:', currentSection.value, itemId, updateData);
                console.log('Modifying item:', modifyingItem.value);
                await updateFunction(itemId, updateData);
                if (modifyModalInstance) {
                    modifyModalInstance.hide();
                }
                await fetchFunction();
                errorMessage.value = '';
            } catch (error) {
                console.error(`Error al actualizar el ${currentSection.value}:`, error);
                errorMessage.value = `Error al actualizar el ${currentSection.value}: ${error.message}`;
            }
    }
};


const toggleShowAllCampus = () => (showAllCampus.value = !showAllCampus.value);
const toggleShowAllTalleres = () => (showAllTalleres.value = !showAllTalleres.value);
const toggleShowAllTipoAverias = () => (showAllTipoAverias.value = !showAllTipoAverias.value);
const toggleShowAllTiposMantenimiento = () =>
    (showAllTiposMantenimiento.value = !showAllTiposMantenimiento.value);

const showAddItemPopup = (section) => {
    currentSection.value = section;
    newItemName.value = '';
    newItemLocation.value = '';
    newItemDescription.value = '';
    selectedCampus.value = null;
    errorMessage.value = '';
    if (addModalInstance) {
        addModalInstance.show();
    }
};
  const selectedCampus = ref(null);
const onCampusChange = (event) => {
      selectedCampus.value = parseInt(event.target.value);
  };
const confirmAddItem = async () => {
  errorMessage.value = '';
  if (!newItemName.value.trim()) {
      errorMessage.value = 'Por favor, ingrese un nombre para el item.';
      return;
  }

  if (currentSection.value === 'talleres' && !selectedCampus.value) {
    errorMessage.value = 'Por favor, seleccione un campus.';
    return;
  }
  
  let addFunction;
  let fetchFunction;
  let itemData;
  
  switch (currentSection.value) {
      case 'campus':
          addFunction = createCampus;
          fetchFunction = fetchCampus;
          itemData = {
              nombre: newItemName.value.trim(),
              ubicacion: newItemLocation.value.trim(),
              disabled: false
          };
          break;
      case 'talleres':
          addFunction = createTaller;
          fetchFunction = fetchTalleres;
           itemData = {
              nombre: newItemName.value.trim(),
               id_campus: selectedCampus.value,
               id_responsable: 1,
              disabled: false
          };
          break;
          
      case 'tipoAverias':
          addFunction = createTipoAveria;
          fetchFunction = fetchTipoAverias;
          itemData = {
              nombre: newItemName.value.trim(),
              disabled: false
          };
          break;
      case 'tiposMantenimiento':
          addFunction = createTipoMantenimiento;
          fetchFunction = fetchTiposMantenimiento;
          itemData = {
              nombre: newItemName.value.trim(),
              descripcion: newItemDescription.value.trim(),
              disabled: false
          };
          break;
      default:
          errorMessage.value = 'Sección no reconocida';
          return;
  }

    try {
          // Log the data being sent
          console.log('Data to be submitted:', itemData);
          await addFunction(itemData);

            if (addModalInstance) {
                addModalInstance.hide();
            }
            newItemName.value = '';
            newItemLocation.value = '';
            newItemDescription.value = '';
            selectedCampus.value = null;
           await fetchFunction();
        } catch (error) {
          console.error('Error al añadir el item', error);
            errorMessage.value = 'Error al añadir el item: ' + error.message;
        }
    };
const showDisableConfirmPopup = (item, section) => {
    disablingItem.value = item;
    currentSectionDisable.value = section;
    if (disableModalInstance) {
        disableModalInstance.show();
    }
};

const confirmDisable = async () => {
    if (!disablingItem.value) return;

    let toggleFunction;
    let fetchFunction;
    let itemId;

    console.log("Current Section:", currentSectionDisable.value);

    switch (currentSectionDisable.value) {
        case 'campus':
            toggleFunction = toggleCampusStatus;
            fetchFunction = fetchCampus;
            itemId = disablingItem.value.id_campus;
            break;
        case 'talleres':
            toggleFunction = toggleTallerStatus;
            fetchFunction = fetchTalleres;
            itemId = disablingItem.value.id_taller;
            break;
        case 'tipoAverias':
            toggleFunction = toggleTipoAveriaStatus;
            fetchFunction = fetchTipoAverias;
            itemId = disablingItem.value.id_tipo_averia;
            break;
        case 'tiposMantenimiento':
            toggleFunction = toggleTipoMantenimientoStatus;
            fetchFunction = fetchTiposMantenimiento;
            itemId = disablingItem.value.id_tipo_mantenimiento;
            break;
        default:
            console.log("No valid section");
            return;
    }

    try {
        if (!itemId) {
            throw new Error(`Item ID is undefined for ${currentSectionDisable.value}`);
        }
        await toggleFunction(itemId);
        if (disableModalInstance) {
            disableModalInstance.hide();
        }
        disablingItem.value = null;
        await fetchFunction();
    } catch (error) {
        console.error('Error disabling/enabling item:', error);
        errorMessage.value = `Error al cambiar el estado del item: ${error.message}`;
    }
};
</script>

<style scoped>
.custom-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.custom-card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 0.75rem 1.25rem;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    display: flex;
    align-items: center;
}

.custom-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.list-group-item {
    background-color: #fff;
    transition: background-color 0.3s ease;
    border: none;
}

.custom-list-item:hover {
    background-color: #f0f0f0;
}

.custom-input {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.custom-input:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.container-fluid {
    width: 97%;
    margin-left: 3%;
}

.custom-modal .modal-content {
    border-radius: 8px;
}

.custom-modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 0.75rem 1.25rem;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.custom-modal-footer {
    border-top: 1px solid #e0e0e0;
    background-color: #f8f9fa;
}
</style>

