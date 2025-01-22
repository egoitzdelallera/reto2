import {ref, computed, onMounted} from 'vue';
import axios from 'axios';

export default function useIncidencias() {
    const incidencias = ref([]);
    const searchQuery = ref('');

    const loadIncidencias = async () => {
        try{
            const token = localStorage.getItem('jwt_token');
            const response = await axios.get('http://localhost:8000/api/incidencias', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            console.log('Datos de la API:', response.data);
            incidencias.value = response.data;
        } catch(error) {
            console.error('Error al cargar las incidencias', error);
        }
    };

    const getIncidenciaById = async (id) => {
        try{
            const token = localStorage.getItem('jwt_token');
            const response = await axios.get(`http://localhost:8000/api/incidencias/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            return response.data;
        } catch(error) {
            console.error('Error al cargar la incidencia', error);
            throw error;
        }
    }

    const filteredIncidencias = computed(() => {
        if (!searchQuery.value) {
            return incidencias.value;
        }

        return incidencias.value.filter((incidencia) => {
            const lowerCaseQuery = searchQuery.value.toLowerCase();
            return (
              incidencia.descripcion.toLowerCase().includes(lowerCaseQuery) ||
              incidencia.estado.toLowerCase().includes(lowerCaseQuery) ||
              incidencia.maquina.toLowerCase().includes(lowerCaseQuery) ||
              incidencia.operario.toLowerCase().includes(lowerCaseQuery) ||
              incidencia.tecnico.toLowerCase().includes(lowerCaseQuery) ||
              incidencia.gravedad.toLowerCase().includes(lowerCaseQuery)
            );
        });
    });
    

    const getEstadoClass = (estado) => {
        switch (estado) {
            case 'En Proceso':
                return 'bg-blue-100 text-blue-800';
            case 'Abierta':
                return 'bg-gray-100 text-gray-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    const getGravedadClass = (gravedad) => {
        switch (gravedad) {
            case 'Maquina parada':
                return 'bg-red-100 text-red-800';
            case 'Maquina en Marcha':
                return 'bg-orange-100 text-orange-800';
            case 'Aviso':
                return 'bg-yellow-100 text-yellow-800';
            case 'Mantenimiento':
                return 'bg-gray-100 text-gray-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    // Agregar función para retornar colores según la prioridad
    const getPrioridadClass = (prioridad) => {
        console.log('Prioridad:', prioridad); // Verifica el valor de prioridad en la consola
        switch (prioridad) {
          case 'Alta':
            return 'bg-red-500 text-white';
          case 'Media':
            return 'bg-yellow-500 text-white';
          case 'Baja':
            return 'bg-green-500 text-white';
          default:
            return 'bg-gray-500 text-white'; // Clase predeterminada si no se encuentra coincidencia
        }
      };

    onMounted(() => {
        loadIncidencias();
    });

    return {
        incidencias,
        searchQuery,
        filteredIncidencias,
        loadIncidencias,
        getEstadoClass,
        getGravedadClass,
        getPrioridadClass,
        getIncidenciaById,
    };
}