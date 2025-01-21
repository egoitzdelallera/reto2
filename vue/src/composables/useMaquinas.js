import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default function useMaquinas() {
    const maquinas = ref([]);
    const searchQuery = ref('');
    const loading = ref(false);
    const error = ref(null);


    const fetchMaquinas = async () => {
        loading.value = true;
        error.value = null;
      try {
        const token = localStorage.getItem('jwt_token');
        const response = await axios.get('http://localhost:8000/api/maquinas', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
            console.log('Datos de la API:', response.data);
            maquinas.value = response.data;
      } catch (err) {
            error.value = err;
        console.error('Error al cargar las Maquinas', err);
      } finally {
            loading.value = false;
        }
    };

     const createMaquina = async (maquinaData) => {
        loading.value = true;
        error.value = null;
    try {
      const token = localStorage.getItem('jwt_token');
        const response = await axios.post('http://localhost:8000/api/maquinas', maquinaData, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        console.log('Maquina creada:', response.data);
        return response.data;
    } catch (err) {
          error.value = err;
        console.error('Error al crear la máquina', err);
        throw new Error(err.response?.data?.message || 'Error creating machine');
      } finally {
        loading.value = false;
    }
  };


    const filteredMaquinas = computed(() => {
        if (!searchQuery.value) {
            return maquinas.value;
        }

        return maquinas.value.filter((maquina) => {
            const lowerCaseQuery = searchQuery.value.toLowerCase();
            return (
                maquina.id_maquina.toLowerCase().includes(lowerCaseQuery) ||
                maquina.nombre.toLowerCase().includes(lowerCaseQuery) ||
                maquina.descripcion.toLowerCase().includes(lowerCaseQuery) ||
                String(maquina.id_taller).toLowerCase().includes(lowerCaseQuery) || // Cast id_taller to string
                maquina.prioridad.toLowerCase().includes(lowerCaseQuery) ||
                maquina.estado.toLowerCase().includes(lowerCaseQuery)
            );
        });
    });
    

    const getEstadoClass = (estado) => {
        switch (estado) {
            case 'Habilitado':
                return 'badge bg-success text-white';
            case 'Deshabilitado':
                return 'badge bg-dark text-white';
            default:
                return 'badge bg-info text-white';
        }
    };

    // Agregar función para retornar colores según la prioridad
    const getPrioridadClass = (prioridad) => {
        console.log('Prioridad:', prioridad); // Verifica el valor de prioridad en la consola
        switch (prioridad) {
            case 'Alta':
                return 'badge bg-danger text-white';
            case 'Media':
                return 'badge bg-warning text-dark';
            case 'Baja':
                return 'badge bg-info text-white';
            default:
                return 'badge bg-info text-white';
        }
    };

    onMounted(() => {
      fetchMaquinas();
    });


    return {
        maquinas,
        searchQuery,
        filteredMaquinas,
        fetchMaquinas, // Replaced loadMaquinas with fetchMaquinas
        getEstadoClass,
        getPrioridadClass,
        createMaquina, // Added createMaquina
        loading,
        error,
    };
}