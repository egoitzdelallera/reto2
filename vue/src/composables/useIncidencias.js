import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default function useIncidencias() {
    const incidencias = ref([]);
    const searchQuery = ref('');
    const loading = ref(false);
    const error = ref(null);
    const message = ref(null); // Nuevo ref para el mensaje

    const loadIncidencias = async () => {
        loading.value = true;
        error.value = null;
        try {
            const token = localStorage.getItem('jwt_token');
            if (!token) {
                throw new Error("No JWT token found");
            }
            const response = await axios.get('http://localhost:8000/api/incidencias', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            console.log('Datos de la API (incidencias):', response.data);
            incidencias.value = response.data;
        } catch (err) {
            error.value = err;
            console.error('Error al cargar las incidencias', err);
        } finally {
            loading.value = false;
        }
    };

    const filteredIncidencias = computed(() => {
        if (!searchQuery.value) {
            return incidencias.value;
        }

        return incidencias.value.filter((incidencia) => {
            const lowerCaseQuery = searchQuery.value.toLowerCase();
            return (
              incidencia.descripcion.toLowerCase().includes(lowerCaseQuery) ||
              incidencia.estado.toLowerCase().includes(lowerCaseQuery) ||
              (incidencia.maquina && incidencia.maquina.nombre.toLowerCase().includes(lowerCaseQuery))||
              (incidencia.creador && incidencia.creador.nombre.toLowerCase().includes(lowerCaseQuery)) ||
              (incidencia.tecnico && incidencia.tecnico.nombre.toLowerCase().includes(lowerCaseQuery)) ||
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
            case 'Pendiente':
                return 'bg-yellow-100 text-yellow-800';
            case 'Resuelta':
                 return 'bg-green-100 text-green-800';
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

    const getPrioridadClass = (prioridad) => {
        console.log('Prioridad:', prioridad);
        switch (prioridad) {
            case 'Alta':
                return 'bg-red-500 text-secondary ';
            case 'Media':
                return 'bg-yellow-500 text-white';
            case 'Baja':
                return 'bg-green-500 text-white';
            default:
                return 'bg-gray-500 text-white';
        }
    };

    const createIncidencia = async (incidenciaData, selectedMachine) => {
        loading.value = true;
        error.value = null;
        message.value = null; // Reset message before request
        try {
            const token = localStorage.getItem('jwt_token');
            if (!token) {
                throw new Error("No JWT token found");
            }

            const newIncidencia = {
              id_maquina: selectedMachine.id_maquina,
              descripcion: incidenciaData.descripcion,
              gravedad: incidenciaData.gravedad,
              estado: 'Abierta', // Estado por defecto
              id_creador: 1, //  usuario que crea la incidencia
              fecha_ini: new Date().toISOString(), // Fecha actual
            };


            const response = await axios.post('http://localhost:8000/api/incidencias', newIncidencia, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
             if(response.status === 201){
                message.value = "Incidencia creada correctamente";
            }
            console.log('Incidencia creada:', response.data);
            incidencias.value.push(response.data);
           return response.data;
        } catch (err) {
            error.value = err;
            console.error('Error al crear la incidencia', err);
           message.value = "Error al crear la incidencia, revisa los datos."
            throw err;
        } finally {
            loading.value = false;
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
        createIncidencia,
        loading,
        error,
        message,
    };
}