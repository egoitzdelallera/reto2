import { ref, computed } from 'vue';
import axios from 'axios';

export default function useTalleres() {
  const talleres = ref([]);
  const searchQuery = ref('');
  const loading = ref(false);
  const error = ref(null);


    const fetchTalleres = async () => {
        loading.value = true;
        error.value = null;
        try {
            const token = localStorage.getItem('jwt_token');
            const response = await axios.get('http://localhost:8000/api/talleres', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            console.log('Datos de la API:', response.data);
            talleres.value = response.data;
        } catch (err) {
            error.value = err;
            console.error('Error al cargar los talleres', err); // Log the error object, not just error
        } finally {
            loading.value = false;
        }
    };

  const filteredTalleres = computed(() => {
    if (!searchQuery.value) {
      return talleres.value;
    }

    return talleres.value.filter((taller) => {
      const lowerCaseQuery = searchQuery.value.toLowerCase();
      return taller.nombre.toLowerCase().includes(lowerCaseQuery);
    });
  });

  return {
    talleres,
    filteredTalleres,
    fetchTalleres, // Renamed loadTalleres to fetchTalleres
    loading,
    error,
    searchQuery,
  };
}