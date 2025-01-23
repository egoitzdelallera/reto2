// composables/useTiposMantenimiento.js

import { ref } from 'vue';

const useTiposMantenimiento = () => {
  const tiposMantenimiento = ref([]);
  const error = ref(null);
  const loading = ref(false);


  const fetchTiposMantenimiento = async () => {
      loading.value = true;
    try {
      const response = await fetch('http://localhost:8000/api/tipos-mantenimiento'); // Asegúrate de que esta ruta es correcta
      if (!response.ok) {
        throw new Error('Error al obtener los tipos de mantenimiento');
      }
      const data = await response.json();
      tiposMantenimiento.value = data.tiposMantenimiento;
         loading.value = false;
    } catch (err) {
      error.value = err.message || 'Error desconocido al obtener los tipos de mantenimiento';
      loading.value = false;
    }
  };

  return {
    tiposMantenimiento,
      fetchTiposMantenimiento,
      error,
      loading
  };
};

export default useTiposMantenimiento;