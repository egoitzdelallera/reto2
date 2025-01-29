import { ref, computed, onMounted } from 'vue';
import axios from 'axios';


export default function useIncidencias() {
    const incidencias = ref([]);
    const searchQuery = ref('');
    const incidencia = ref(null);
    const loading = ref(false);
    const error = ref(null);
    const message = ref(null); // Nuevo ref para el mensaje

    const loadIncidencias = async () => {
        loading.value = true;
        error.value = null;
        try {
          const token = localStorage.getItem('jwt_token');
          const userDataString = localStorage.getItem('user_data'); // Obtener la información del usuario
      
          if (!token) {
            throw new Error("No JWT token found");
          }
      
          const userData = userDataString ? JSON.parse(userDataString) : null; // Parsea el JSON o asigna null
          const isAdmin = userData?.role === 'admin'; // Determinar si el usuario es admin
      
          const response = await axios.get('http://localhost:8000/api/incidencias', {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
      
          // Filtrar incidencias si el usuario no es admin
          if (!isAdmin) {
            incidencias.value = response.data.filter(
              (incidencia) =>
                incidencia.estado !== 'resuelta' && incidencia.estado !== 'cancelada'
            );
          } else {
            incidencias.value = response.data; // Mantener todas las incidencias si es admin
          }
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
            const userData = JSON.parse(localStorage.getItem('user_data'));
            const userId = userData?.id;

            if (!userId) {
                alert('No se pudo obtener el id del usuario');
                return;
            }

            const responseFinalizar = await axios.post(
                `http://localhost:8000/api/fases/${faseId}/finalizar`,
                { descripcion, userId },
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
            //alert('No se pudo finalizar la fase');
        }
    };

    const finalizarFaseYIncidencia = async (faseId, descripcion, incidenciaId) => {
        try {
          const token = localStorage.getItem('jwt_token');
          const userData = JSON.parse(localStorage.getItem('user_data'));
          const userId = userData ? userData.id : null;
      
          if (!userId) {
            alert('No se pudo obtener el id del usuario');
            return;
          }
      
          descripcion = String(descripcion).trim();


          // Verificar que la descripción no esté vacía
          if (!descripcion || typeof descripcion !== 'string') {
            alert('La descripción debe ser una cadena no vacía');
            return;
          }
      
          // Primero finalizar la fase
          const responseFinalizarFase = await axios.post(
            `http://localhost:8000/api/fases/${faseId}/finalizar`,
            { descripcion, userId },
            {
              headers: {
                Authorization: `Bearer ${token}`,
              },
            }
          );
      
          console.log('Fase finalizada:', responseFinalizarFase.data);
      
          // Ahora finalizar la incidencia
          const responseFinalizarIncidencia = await axios.put(
            `http://localhost:8000/api/incidencias/${incidenciaId}/finalizar`,
            { descripcion, userId },
            {
              headers: {
                Authorization: `Bearer ${token}`,
              },
            }
          );
      
          console.log('Incidencia finalizada:', responseFinalizarIncidencia.data);
      
          alert('La fase y la incidencia han sido finalizadas correctamente.');
          loadIncidencias(); // Recargar las incidencias para reflejar los cambios
      
        } catch (error) {
          console.error('Error al finalizar la fase o la incidencia:', error.response ? error.response.data : error);
          //alert('No se pudo finalizar la fase o la incidencia');
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
        const userDataString = localStorage.getItem('user_data');
        const userData = userDataString ? JSON.parse(userDataString) : null;
        const isAdmin = userData?.role === 'admin';
        
        if(isAdmin){
            return incidencias.value;
        }
    
        return incidencias.value.filter(incidencia => incidencia.estado !== 'resuelta' && incidencia.estado !== 'cancelada')
    });

    

    const createIncidencia = async (incidenciaData, selectedMachine,selectedTipoAveria) => {
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
              id_tipo_averia:selectedTipoAveria.id_tipo_averia,
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
        finalizarYCrearFase,
        asignarTecnicoAFase,
        finalizarFaseYIncidencia,
        loading,
        error,
        message,
    };
}