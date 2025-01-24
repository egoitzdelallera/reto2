// composables/useTiposMantenimiento.js

import { ref } from 'vue';
import axios from 'axios'; // Importa axios

const useTiposMantenimiento = () => {
  const tiposMantenimiento = ref([]);
  const error = ref(null);
  const loading = ref(false);

  const fetchTiposMantenimiento = async () => {
    loading.value = true;
    try {
      const response = await axios.get('http://localhost:8000/api/tipos-mantenimiento'); // Usa axios.get
      tiposMantenimiento.value = response.data;
      console.log("Tipos de Mantenimiento Obtenidos:", tiposMantenimiento.value); // Imprime en la consola
      loading.value = false;
    } catch (err) {
      error.value = err.message || 'Error desconocido al obtener los tipos de mantenimiento';
      console.error("Error al obtener tipos de mantenimiento:", error.value);  // Imprime el error en la consola
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