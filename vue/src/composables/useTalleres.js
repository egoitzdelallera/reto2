import { ref, computed } from "vue";
import axios from "axios";

export default function useTalleres() {
  const talleres = ref([]);
  const searchQuery = ref("");
  const loading = ref(false);
  const error = ref(null);

  const fetchTalleres = async () => {
    loading.value = true;
    error.value = null;
    try {
      const token = localStorage.getItem("jwt_token");
      const response = await axios.get("http://localhost:8000/api/talleres", {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      talleres.value = response.data;
    } catch (err) {
      error.value = err;
      console.error("Error al cargar los talleres", err);
    } finally {
      loading.value = false;
    }
  };

  const createTaller = async (tallerData) => {
    loading.value = true;
    error.value = null;
    try {
      const token = localStorage.getItem("jwt_token");
      const response = await axios.post("http://localhost:8000/api/talleres", tallerData, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      talleres.value.push(response.data);
    } catch (err) {
      error.value = err;
      console.error("Error al crear el taller", err);
    } finally {
      loading.value = false;
    }
  };

  const updateTaller = async (id, tallerData) => {
    loading.value = true
    error.value = null
    try {
      console.log("Updating taller:", id, tallerData)
      const response = await axios.put(`http://localhost:8000/api/talleres/${id}`, tallerData)
      console.log("Update response:", response)
      const index = talleres.value.findIndex((item) => item.id_taller === id)
      if (index !== -1) {
        talleres.value[index] = response.data
      }
      return response.data
    } catch (e) {
      error.value = "Error updating taller"
      console.error("Error updating taller:", e.response || e)
      throw e
    } finally {
      loading.value = false
    }
  }
  
  const filteredTalleres = computed(() => {
    if (!searchQuery.value) {
      return talleres.value;
    }

    return talleres.value.filter((taller) => {
      const lowerCaseQuery = searchQuery.value.toLowerCase();
      return taller.nombre.toLowerCase().includes(lowerCaseQuery);
    });
  });

  return {
    talleres,
    filteredTalleres,
    fetchTalleres,
    createTaller,
    updateTaller,
    loading,
    error,
    searchQuery,
  };
}
