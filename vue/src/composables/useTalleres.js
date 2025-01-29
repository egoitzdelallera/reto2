// useTalleres.js
import { ref } from 'vue';
import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

export default function useTalleres() {
    const talleres = ref([]);
    const filteredTalleres = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const searchQuery = ref('');

    const fetchTalleres = async () => {
        loading.value = true;
        error.value = null;
        try {
             const token = localStorage.getItem('jwt_token');
            if (!token) {
                throw new Error('No se encontró un token');
            }
            const response = await axios.get(`${API_URL}/talleres`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            });
            talleres.value = response.data;
              filteredTalleres.value = [...talleres.value];

        } catch (err) {
            error.value = err.response?.data?.message || err.message || 'Failed to fetch talleres';
            console.error('Error fetching talleres:', err);
            console.log('Response Data (fetchTalleres):', err.response?.data);
        } finally {
            loading.value = false;
        }
    };


    const createTaller = async (tallerData) => {
         loading.value = true
          error.value = null
        try {
             const token = localStorage.getItem('jwt_token');
            if (!token) {
                throw new Error('No se encontró un token');
            }
            const response = await axios.post(`${API_URL}/talleres`, tallerData, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            });
            talleres.value.push(response.data);
              filteredTalleres.value = [...talleres.value];
            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || err.message || "Failed to create taller";
            console.error("Error creating taller:", err);
              console.log('Response Data (createTaller):', err.response?.data);
             throw new Error("Error al crear el taller", { cause: err });
        } finally {
            loading.value = false
        }
    };
    const updateTaller = async (id, tallerData) => {
      loading.value = true;
      error.value = null;
      try {
           const token = localStorage.getItem('jwt_token');
          if (!token) {
              throw new Error('No se encontró un token');
          }
          console.log('Updating taller:', id, tallerData);
          const response = await axios.patch(`${API_URL}/talleres/${id}`, tallerData, {
               headers: {
                   'Authorization': `Bearer ${token}`,
              },
          });
           console.log("Update response:", response)
         const index = talleres.value.findIndex((item) => item.id === id);
        if (index !== -1) {
            talleres.value[index] = { ...talleres.value[index], ...response.data };
        }
        filterTalleres();
          return response.data;
      } catch (err) {
          console.error("Error updating taller:", err.response || err);
          error.value = err.response?.data?.message || "Failed to update taller";
          throw new Error(error.value, {cause: err});
      } finally {
          loading.value = false;
      }
    };

    const toggleTallerStatus = async (id) => {
        loading.value = true;
        error.value = null;
        try {
             const token = localStorage.getItem('jwt_token');
              if (!token) {
                   throw new Error('No se encontró un token');
              }
            const response = await axios.patch(`${API_URL}/talleres/${id}/toggle-status`,{}, {
              headers: {
                  'Authorization': `Bearer ${token}`,
              },
            });
             console.log("toggleTallerStatus:", response)
           const index = talleres.value.findIndex((c) => c.id === id)
            if (index !== -1) {
              talleres.value[index] = { ...talleres.value[index], ...response.data }
            }
             filterTalleres()
             return response.data;
        } catch (err) {
              console.error("Error in toggleTallerStatus:", err.response || err);
              error.value =
                err.response?.data?.message || "Error al actualizar el estado del taller";
            throw new Error(error.value);
        } finally {
              loading.value = false;
       }
    };
 const filterTalleres = () => {
        if (!searchQuery.value) {
            filteredTalleres.value = [...talleres.value];
        } else {
            filteredTalleres.value = talleres.value.filter(taller =>
                taller.nombre.toLowerCase().includes(searchQuery.value.toLowerCase())
            );
        }
    };
    return {
        talleres,
          filteredTalleres,
        loading,
        error,
        fetchTalleres,
        createTaller,
          updateTaller,
          toggleTallerStatus,
          searchQuery,
          filterTalleres
    };
}