import { ref } from "vue"
import axios from "axios"

export default function useTipoAveria() {
  const tipoAverias = ref([])
  const loading = ref(false)
  const error = ref(null)

  const fetchTipoAverias = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await axios.get("http://localhost:8000/api/tipos-averia")
      tipoAverias.value = response.data
    } catch (err) {
      error.value = err.response?.data?.message || err.message || "Failed to fetch tipo averias"
      console.error("Error fetching tipo averias:", err)
    } finally {
      loading.value = false
    }
  }

  const createTipoAveria = async (tipoAveriaData) => {
    loading.value = true
    error.value = null
    try {
      const response = await axios.post("http://localhost:8000/api/tipo-averias", tipoAveriaData)
      tipoAverias.value.push(response.data)
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || err.message || "Failed to create tipo averia"
      console.error("Error creating tipo averia:", err)
      throw error.value
    } finally {
      loading.value = false
    }
  }

  const updateTipoAveria = async (id, tipoAveriaData) => {
    loading.value = true
    error.value = null
    try {
      console.log("Updating tipo averia:", id, tipoAveriaData)
      const response = await axios.put(`http://localhost:8000/api/tipo-averias/${id}`, tipoAveriaData)
      console.log("Update response:", response)
      const index = tipoAverias.value.findIndex((item) => item.id_tipo_averia === id)
      if (index !== -1) {
        tipoAverias.value[index] = response.data
      }
      return response.data
    } catch (err) {
      console.error("Error updating tipo averia:", err.response || err)
      error.value = err.response?.data?.message || err.message || "Failed to update tipo averia"
      throw error.value
    } finally {
      loading.value = false
    }
  }

  return {
    tipoAverias,
    loading,
    error,
    fetchTipoAverias,
    createTipoAveria,
    updateTipoAveria,
  }
}

