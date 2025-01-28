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
            const response = await axios.get(`${API_URL}/campus`, {
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
        loading.value = true;
        error.value = null;
        try {
            const token = localStorage.getItem('jwt_token');
            if (!token) {
                throw new Error('No se encontró un token');
            }
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
            throw new Error('Error al crear el campus', { cause: err });
        } finally {
            loading.value = false;
        }
    };
    const updateCampus = async (id, campusData) => {
      loading.value = true
      error.value = null
      try {
        const token = localStorage.getItem('jwt_token');
          if (!token) {
              throw new Error('No se encontró un token');
           }
          console.log("Updating campus with ID:", id, "and data:", campusData)
          const response = await axios.patch(`${API_URL}/campus/${id}`, campusData, {
            headers: {
                  'Authorization': `Bearer ${token}`,
              },
          })
          console.log("Update response:", response)

          const index = campus.value.findIndex((c) => c.id_campus === id)
          if (index !== -1) {
              campus.value[index] = { ...campus.value[index], ...response.data }
          }

          return response.data
      } catch (err) {
          console.error("Error in updateCampus:", err.response || err)
          error.value = err.response?.data?.message || "Error al actualizar el campus"
          throw new Error(error.value, {cause: err})
      } finally {
          loading.value = false
      }
  }
  const toggleCampusStatus = async (id) => {
    loading.value = true;
    error.value = null;
    try {
        const token = localStorage.getItem('jwt_token');
        if (!token) {
            throw new Error('No se encontró un token');
        }
        const response = await axios.patch(`${API_URL}/campus/${id}/toggle-status`,{}, { // removed the disabled property from the body
          headers: {
              'Authorization': `Bearer ${token}`,
          },
        });
        console.log("toggleCampusStatus:", response)
        const index = campus.value.findIndex((c) => c.id_campus === id)
         if (index !== -1) {
            campus.value[index] = { ...campus.value[index], ...response.data }
        }
         return response.data
       } catch (err) {
        console.error("Error in toggleCampusStatus:", err.response || err)
         error.value = err.response?.data?.message || "Error al actualizar el estado del campus"
         throw new Error(error.value)
     } finally {
        loading.value = false
     }

};



    return {
        campus,
        loading,
        error,
        fetchCampus,
        createCampus,
        updateCampus,
        toggleCampusStatus,
    };
}