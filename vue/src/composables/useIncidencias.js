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
              (incidencia.maquina && incidencia.maquina.nombre.toLowerCase().includes(lowerCaseQuery))||
              (incidencia.creador && incidencia.creador.nombre.toLowerCase().includes(lowerCaseQuery)) ||
              (incidencia.tecnico && incidencia.tecnico.nombre.toLowerCase().includes(lowerCaseQuery)) ||
              incidencia.gravedad.toLowerCase().includes(lowerCaseQuery)
            );
        });
    });

    

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
        createIncidencia,
        getIncidenciaById,
        loading,
        error,
        message,
    };
}