import { ref, computed } from "vue"
import axios from "axios"

const API_URL = "http://localhost:8000/api"

export default function useTipoAveria() {
  const tipoAverias = ref([])
  const filteredTipoAverias = ref([])
  const loading = ref(false)
  const error = ref(null)
  const searchQuery = ref("")

  const fetchTipoAverias = async () => {
    loading.value = true
    error.value = null
    try {
      const token = localStorage.getItem("jwt_token")
      if (!token) {
        throw new Error("No se encontró un token")
      }
      const response = await axios.get(`${API_URL}/tipos-averia`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      tipoAverias.value = response.data
      filteredTipoAverias.value = response.data
    } catch (err) {
      console.error("Error fetching tipo averias:", err)
      error.value = err.response?.data?.message || "Error al obtener los tipos de avería"
    } finally {
      loading.value = false
    }
  }

  const createTipoAveria = async (tipoAveriaData) => {
    loading.value = true
    error.value = null
    try {
      const token = localStorage.getItem("jwt_token")
      if (!token) {
        throw new Error("No se encontró un token")
      }
      const response = await axios.post(`${API_URL}/tipo-averias`, tipoAveriaData, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      tipoAverias.value.push(response.data)
      return response.data
    } catch (err) {
      console.error("Error creating tipo averia:", err)
      error.value = err.response?.data?.message || "Error al crear el tipo de avería"
      throw new Error(error.value)
    } finally {
      loading.value = false
    }
  }

  const updateTipoAveria = async (id, tipoAveriaData) => {
    loading.value = true
    error.value = null
    try {
      const token = localStorage.getItem("jwt_token")
      if (!token) {
        throw new Error("No se encontró un token")
      }
      const response = await axios.put(`${API_URL}/tipo-averias/${id}`, tipoAveriaData, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      const index = tipoAverias.value.findIndex((item) => item.id_tipo_averia === id)
      if (index !== -1) {
        tipoAverias.value[index] = { ...tipoAverias.value[index], ...response.data }
      }
      return response.data
    } catch (err) {
      console.error("Error updating tipo averia:", err)
      error.value = err.response?.data?.message || "Error al actualizar el tipo de avería"
      throw new Error(error.value)
    } finally {
      loading.value = false
    }
  }

  const toggleTipoAveriaStatus = async (id) => {
    loading.value = true
    error.value = null
    try {
      const token = localStorage.getItem("jwt_token")
      if (!token) {
        throw new Error("No se encontró un token")
      }
      const response = await axios.patch(
        `${API_URL}/tipo-averias/${id}/toggle-status`,
        {},
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        },
      )
      console.log("toggleTipoAveriaStatus:", response)
      const index = tipoAverias.value.findIndex((t) => t.id_tipo_averia === id)
      if (index !== -1) {
        tipoAverias.value[index] = { ...tipoAverias.value[index], ...response.data }
      }
      return response.data
    } catch (err) {
      console.error("Error in toggleTipoAveriaStatus:", err)
      error.value = err.response?.data?.message || "Error al actualizar el estado del tipo de avería"
      throw new Error(error.value)
    } finally {
      loading.value = false
    }
  }

  const filterTipoAverias = computed(() => {
    if (!searchQuery.value) {
      return tipoAverias.value
    }
    return tipoAverias.value.filter((tipoAveria) =>
      tipoAveria.nombre.toLowerCase().includes(searchQuery.value.toLowerCase()),
    )
  })

  return {
    tipoAverias,
    filteredTipoAverias,
    loading,
    error,
    searchQuery,
    fetchTipoAverias,
    createTipoAveria,
    updateTipoAveria,
    toggleTipoAveriaStatus,
    filterTipoAverias,
  }
}

