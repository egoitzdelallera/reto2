<<<<<<< HEAD
// composables/useCampus.js
import { ref } from 'vue';
import axios from 'axios';

const API_URL = 'http://localhost:8000/api/campus'; // Reemplaza con tu URL real

export default function useCampus() {
    const campus = ref([]);
    const loading = ref(false);
    const error = ref(null);


    const fetchCampus = async () => {
        loading.value = true;
        error.value = null;
        try {
            const token = localStorage.getItem('jwt_token');
                if (!token) {
                 throw new Error('No se encontró un token');
                }
              const response = await axios.get(API_URL,{
                   headers: {
                       'Authorization': `Bearer ${token}`
                   }
                });
                console.log("response campus", response.data.campus);
            campus.value = response.data.campus;
        } catch (err) {
              console.error('Error fetching campus:', err);
             error.value = 'No se pudieron cargar los campus';
              console.log('Response Data (fetchCampus):', err.response?.data);
        } finally {
            loading.value = false;
        }
    };

   const createCampus = async (newCampus) => {
=======
import { ref } from 'vue';
import axios from 'axios';

const API_URL = 'http://localhost:8000/api'; // URL base de la API

export default function useCampus() {
  const campus = ref([]);
  const loading = ref(false);
  const error = ref(null);

  const fetchCampus = async () => {
    loading.value = true;
    error.value = null;
    try {
        const token = localStorage.getItem('jwt_token');
        if (!token) {
           throw new Error('No se encontró un token');
        }
      const response = await axios.get(`${API_URL}/campus`,{ // URL correcta sin concatenar ni reemplazar
            headers: {
                'Authorization': `Bearer ${token}`
            }
       });

       if (response.data && Array.isArray(response.data)) {
            campus.value = response.data;
       } else {
         throw new Error('La respuesta de la API no contiene un array de campus');
       }

       console.log("response campus", response); // Para depurar la respuesta

    } catch (err) {
       console.error('Error fetching campus:', err);
       error.value = err.message || 'No se pudieron cargar los campus';
      console.log('Response Data (fetchCampus):', err.response?.data);
    } finally {
      loading.value = false;
    }
  };

    const createCampus = async (newCampus) => {
>>>>>>> b013408ae9fd8d85b418a9e119a35853755c4589
        loading.value = true;
        error.value = null;
        try {
            const token = localStorage.getItem('jwt_token');
              if (!token) {
                  throw new Error('No se encontró un token');
                }
<<<<<<< HEAD
            const response = await axios.post(API_URL, newCampus,{
                 headers: {
                       'Authorization': `Bearer ${token}`
                   }
            });
             console.log('response create campus', response.data);
            return response.data;
        } catch (err) {
              console.error('Error creating campus:', err);
              error.value = err.message;
                console.log('Response Data (createCampus):', err.response?.data);
=======
            const response = await axios.post(`${API_URL}/campus`, newCampus, {
                 headers: {
                       'Authorization': `Bearer ${token}`,
                   },
                });
              console.log('response create campus', response);
            return response.data;
        } catch (err) {
              console.error('Error creating campus:', err);
             error.value = err.message || 'Error al crear el campus';
              console.log('Response Data (createCampus):', err.response?.data);
>>>>>>> b013408ae9fd8d85b418a9e119a35853755c4589
            throw new Error('Error al crear el campus', {cause: err});
        } finally {
            loading.value = false;
        }
    };
     const updateCampus = async (campusId, updatedCampus) => {
         loading.value = true;
          error.value = null;
        try{
            const token = localStorage.getItem('jwt_token');
             if (!token) {
                 throw new Error('No se encontró un token');
                }
                console.log("data sent to api:", updatedCampus);
<<<<<<< HEAD
            const response = await axios.patch(`${API_URL}/${campusId}`,updatedCampus , {
=======
            const response = await axios.patch(`${API_URL}/campus/${campusId}`,updatedCampus , {
>>>>>>> b013408ae9fd8d85b418a9e119a35853755c4589
               headers: {
                   'Authorization': `Bearer ${token}`
                   }
              });
<<<<<<< HEAD
            console.log("Response from patch:", response.data)
            return response.data;
          }catch (err) {
             console.error('Error updating campus:', err);
             error.value = 'Error al actualizar el campus';
=======
            console.log("Response from patch:", response)
            return response.data;
          }catch (err) {
             console.error('Error updating campus:', err);
            error.value = err.message || 'Error al actualizar el campus';
>>>>>>> b013408ae9fd8d85b418a9e119a35853755c4589
             console.log('Response Data (updateCampus):', err.response?.data);
             throw new Error('Error al actualizar el campus', {cause: err});
        } finally {
            loading.value = false;
        }
    };

<<<<<<< HEAD
    return {
        campus,
        loading,
        error,
        fetchCampus,
        createCampus,
        updateCampus
    };
=======
  return {
    campus,
    loading,
    error,
    fetchCampus,
    createCampus,
    updateCampus,
  };
>>>>>>> b013408ae9fd8d85b418a9e119a35853755c4589
}