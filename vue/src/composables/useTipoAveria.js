import { ref } from 'vue';
import axios from 'axios';

export default function useTipoAveria() {
  const tipoAverias = ref([]);
  const loading = ref(false);
  const error = ref(null);

  const fetchTipoAverias = async () => {
    loading.value = true;
    error.value = null;
    try {
      const response = await axios.get('http://localhost:8000/api/tipos-averia');
      tipoAverias.value = response.data;
    } catch (err) {
      error.value = err.message || 'Failed to fetch tipo averias';
    } finally {
      loading.value = false;
    }
  };

  return {
    tipoAverias,
    loading,
    error,
    fetchTipoAverias,
  };
}