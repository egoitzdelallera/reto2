<template>
  <div class="modal fade" :class="{ 'show d-block': showModal }" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content bg-primary">
        <div class="modal-header">
          <h5 class="modal-title">Nuevo Mantenimiento</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="mantenimiento-titulo" class="form-label">Título</label>
            <input
              type="text"
              class="form-control"
              id="mantenimiento-titulo"
              v-model="newMantenimiento.descripcion"
              required
            />
          </div>
          <div class="mb-3">
            <label for="mantenimiento-taller" class="form-label">Taller</label>
            <div
              class="mt-2"
              style="
                                  max-height: 150px;
                                  overflow-y: auto;
                                  border: 1px solid #ced4da;
                                  padding: 5px;
                                  border-radius: 5px;
                                "
            >
              <div v-for="taller in filteredTalleres" :key="taller.id_taller" class="form-check">
                <input
                  :id="'taller-mantenimiento-' + taller.id_taller"
                  type="checkbox"
                  :value="taller"
                  v-model="selectedMantenimientoTaller"
                  class="form-check-input"
                />
                <label :for="'taller-mantenimiento-' + taller.id_taller" class="form-check-label">{{
                  taller.nombre
                }}</label>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="mantenimiento-maquina" class="form-label">Máquina</label>
             <div
              class="mt-2"
              style="
                                  max-height: 250px;
                                  overflow-y: auto;
                                  border: 1px solid #ced4da;
                                  padding: 5px;
                                  border-radius: 5px;
                                "
            >
              <div
                v-for="maquina in filteredMachines"
                :key="maquina.id_maquina"
                class="form-check"
              >
                <input
                  :id="'maquina-mantenimiento-' + maquina.id_maquina"
                  type="checkbox"
                  :value="maquina"
                  v-model="selectedMantenimientoMachine"
                  class="form-check-input"
                />
                <label :for="'maquina-mantenimiento-' + maquina.id_maquina" class="form-check-label">{{
                  maquina.nombre
                }}</label>
              </div>
            </div>
          </div>
           <div class="mb-3">
            <label for="mantenimiento-frecuencia-tipo" class="form-label">Frecuencia</label>
            <select
              class="form-select"
              id="mantenimiento-frecuencia-tipo"
              v-model="newMantenimiento.frecuenciaTipo"
              required
            >
              <option value="">Selecciona una frecuencia</option>
              <option value="Dias">Día(s)</option>
              <option value="Semanas">Semana(s)</option>
              <option value="Meses">Mes(es)</option>
              <option value="Años">Año(s)</option>
            </select>
          </div>
          <div class="mb-3" v-if="newMantenimiento.frecuenciaTipo">
            <label for="mantenimiento-frecuencia-cantidad" class="form-label">Cantidad</label>
            <input
              type="number"
              class="form-control"
              id="mantenimiento-frecuencia-cantidad"
              v-model.number="newMantenimiento.frecuenciaCantidad"
              required
            />
          </div>
           <div v-if="newMantenimiento.frecuenciaTipo && newMantenimiento.frecuenciaCantidad" class="mb-3">
               <div class="mt-2">
                <span>Cada {{ newMantenimiento.frecuenciaCantidad }} {{ newMantenimiento.frecuenciaTipo.toLowerCase() }}</span>
                <span class="text-muted d-block mt-1" > ({{ calculateTotalDays }} días)</span>
              </div>
          </div>
          <div class="mb-3">
            <label for="mantenimiento-tipo" class="form-label">Tipo de Mantenimiento</label>
            <select
              class="form-select"
              id="mantenimiento-tipo"
              v-model="selectedTipoMantenimiento"
              required
            >
              <option value="">Selecciona un tipo de mantenimiento</option>
              <option v-for="tipo in filteredTiposMantenimiento" :key="tipo.id_tipo_mantenimiento" :value="tipo.id_tipo_mantenimiento">
                {{ tipo.nombre }}
              </option>
            </select>
          </div>
          <div class="mb-3" v-if="newMantenimiento.frecuencia === 'Semanal'">
            <label for="mantenimiento-fecha-semanal" class="form-label">Día de la semana</label>
            <input
              type="date"
              class="form-control"
              id="mantenimiento-fecha-semanal"
              v-model="newMantenimiento.fechaSemanal"
              required
            />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">
            Cancelar
          </button>
          <button type="button" class="btn btn-primary" @click="createMantenimiento">Guardar</button>
        </div>
      </div>
    </div>
  </div>
  <div v-if="showModal" class="modal-backdrop fade show"></div>
</template>

<script setup>
import { reactive, computed } from 'vue';

const props = defineProps({
  showModal: Boolean,
  talleres: Array,
  maquinas: Array,
    tiposMantenimiento: Array,
   selectedMantenimientoTaller: Array,
   selectedMantenimientoMachine: Array,
   selectedTipoMantenimiento:String,
});
const emit = defineEmits(['close-modal', 'create-mantenimiento', 'update:selectedMantenimientoTaller', 'update:selectedMantenimientoMachine', 'update:selectedTipoMantenimiento']);

const newMantenimiento = reactive({
  descripcion: '',
  frecuenciaTipo: null,
  frecuenciaCantidad: null,
  frecuencia: null,
  fechaSemanal: null,
  multimedia: null,
});

const selectedMantenimientoTaller = computed({
    get: () => props.selectedMantenimientoTaller,
    set: (value) => emit('update:selectedMantenimientoTaller', value)
  });
const selectedMantenimientoMachine = computed({
    get: () => props.selectedMantenimientoMachine,
    set: (value) => emit('update:selectedMantenimientoMachine', value)
  });
    const selectedTipoMantenimiento = computed({
        get: () => props.selectedTipoMantenimiento,
        set: (value) => emit('update:selectedTipoMantenimiento', value)
    });
 const filteredTalleres = computed(() => {
      return props.talleres ? props.talleres.filter(taller => taller.estado === 'Habilitado') : [];
  });

 const filteredMachines = computed(() => {
   if (!selectedMantenimientoTaller.value || selectedMantenimientoTaller.value.length === 0) {
        return [];
    }

  const selectedTallerIds = selectedMantenimientoTaller.value.map(taller => taller.id_taller);
    return props.maquinas
        ? props.maquinas.filter(maquina =>
            selectedTallerIds.includes(maquina.id_taller) && maquina.estado === 'Habilitado'
        )
        : [];
  });
   const filteredTiposMantenimiento = computed(() => {
        return props.tiposMantenimiento ? props.tiposMantenimiento.filter(tipo => tipo.estado === 'Habilitado') : [];
    });
const closeModal = () => {
  emit('close-modal');
};

const createMantenimiento = () => {
  emit('create-mantenimiento', { ...newMantenimiento, frecuencia: calculateTotalDays.value});
};

const calculateTotalDays = computed(() => {
  let days = 0;
  switch (newMantenimiento.frecuenciaTipo) {
    case 'Dias':
      days = newMantenimiento.frecuenciaCantidad;
      break;
    case 'Semanas':
      days = newMantenimiento.frecuenciaCantidad * 7;
      break;
    case 'Meses':
      days = newMantenimiento.frecuenciaCantidad * 30;
      break;
    case 'Años':
      days = newMantenimiento.frecuenciaCantidad * 365;
      break;
    default:
      days = 0;
  }
  return days;
});
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