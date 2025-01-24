import {ref, computed, onMounted} from 'vue';
import axios from 'axios';


export default function useIncidencias() {
    const incidencias = ref([]);
    const searchQuery = ref('');
    const incidencia = ref(null);

    const loadIncidencias = async () => {
        try{
            const token = localStorage.getItem('jwt_token');
            const response = await axios.get('http://localhost:8000/api/incidencias', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
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
            incidencia.value = response.data;
            return incidencia.value;
        } catch(error) {
            console.error('Error al cargar la incidencia', error);
            throw error;
        }
    }

    const finalizarYCrearFase = async (faseId, descripcion, incidenciaId) => {
        try {
            const token = localStorage.getItem('jwt_token');
            const responseFinalizar = await axios.post(
                `http://localhost:8000/api/fases/${faseId}/finalizar`,
                { descripcion },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );
            console.log('Respuesta de la API al finalizar la fase:', responseFinalizar.data);
            
            const responseCrear = await axios.post(
                `http://localhost:8000/api/fases`,
                {id_incidencia: incidenciaId},
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );

            console.log('Respuesta de la API al crear la nueva fase:', responseCrear.data);


            alert('Fase finalizada y nueva fase creada con exito.');
            loadIncidencias();
        } catch (error) {
            console.error('Error al finalizar la fase', error.response ? error.response.data : error);
            alert('No se pudo finalizar la fase');
        }
    };

    const asignarTecnicoAFase = async (faseId) => {
        try {
            const token = localStorage.getItem('jwt_token');
            const userData = localStorage.getItem('user_data');
            const tecnicoId = JSON.parse(userData).id;

            const fase = incidencia.value.fases_incidencias.find((fase) => fase.id_fase_incidencia === faseId);
            
            if (!fase) {
                console.error('Fase no encontrada');
                return;
            }

            if (fase.estado === 'Completada') {
                alert('No se pueden asignar tecnicos a una fase completada');
                return;
            }

            console.log('Tecnico:', tecnicoId);
            console.log('Fase:', faseId);

            await axios.post(
                `http://localhost:8000/api/fases/${faseId}/tecnicos`,
                { id_tecnico: tecnicoId },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );
            alert('Tecnico asignado con exito');
            loadIncidencias();
        } catch (error) {
            console.error('Error al asignar tecnico a la fase', error);
            alert('No se pudo asignar el tecnico a la fase');
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
            return 'bg-red-500 text-secondary ';
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
        finalizarYCrearFase,
        asignarTecnicoAFase,
    };
}