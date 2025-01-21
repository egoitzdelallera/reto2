import {ref, computed, onMounted} from 'vue';
import axios from 'axios';

export default function useMaquinas() {
    const maquinas = ref([]);
    const searchQuery = ref('');

    const loadMaquinas = async () => {
        try{
            const token = localStorage.getItem('jwt_token');
            const response = await axios.get('http://localhost:8000/api/maquinas', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            console.log('Datos de la API:', response.data);
            maquinas.value = response.data;
        } catch(error) {
            console.error('Error al cargar las Maquinas', error);
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
                maquina.id_taller.toLowerCase().includes(lowerCaseQuery) ||
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
        loadMaquinas();
    });

    return {
        maquinas,
        searchQuery,
        filteredMaquinas,
        loadMaquinas,
        getEstadoClass,
        getPrioridadClass,
    };
}