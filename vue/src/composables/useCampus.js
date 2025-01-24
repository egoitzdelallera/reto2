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
        loading.value = true;
        error.value = null;
        try {
            const token = localStorage.getItem('jwt_token');
              if (!token) {
                  throw new Error('No se encontró un token');
                }
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
            const response = await axios.patch(`${API_URL}/${campusId}`,updatedCampus , {
               headers: {
                   'Authorization': `Bearer ${token}`
                   }
              });
            console.log("Response from patch:", response.data)
            return response.data;
          }catch (err) {
             console.error('Error updating campus:', err);
             error.value = 'Error al actualizar el campus';
             console.log('Response Data (updateCampus):', err.response?.data);
             throw new Error('Error al actualizar el campus', {cause: err});
        } finally {
            loading.value = false;
        }
    };

    return {
        campus,
        loading,
        error,
        fetchCampus,
        createCampus,
        updateCampus
    };
}