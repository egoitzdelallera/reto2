// useTiposMantenimiento.js
import { ref } from "vue"
import axios from "axios"

const API_URL = "http://localhost:8000/api"

export default function useTiposMantenimiento() {
  const tiposMantenimiento = ref([])
  const filteredTiposMantenimiento = ref([])
  const loading = ref(false)
  const error = ref(null)
  const searchQuery = ref("")

  const fetchTiposMantenimiento = async () => {
    loading.value = true
    error.value = null
    try {
      const token = localStorage.getItem("jwt_token")
      if (!token) {
        throw new Error("No se encontró un token")
      }
      const response = await axios.get(`${API_URL}/tipos-mantenimiento`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      tiposMantenimiento.value = response.data
      filteredTiposMantenimiento.value = [...tiposMantenimiento.value]
    } catch (err) {
      error.value = err.response?.data?.message || err.message || "Failed to fetch tipos de mantenimiento"
      console.error("Error fetching tipos de mantenimiento:", err)
      console.log("Response Data (fetchTiposMantenimiento):", err.response?.data)
    } finally {
      loading.value = false
    }
  }
  const createTipoMantenimiento = async (tipoMantenimientoData) => {
    loading.value = true
    error.value = null
    try {
      const token = localStorage.getItem("jwt_token")
      if (!token) {
        throw new Error("No se encontró un token")
      }
      const response = await axios.post(`${API_URL}/tipo-mantenimientos`, tipoMantenimientoData, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      tiposMantenimiento.value.push(response.data)
      filteredTiposMantenimiento.value = [...tiposMantenimiento.value]
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || err.message || "Failed to create tipo de mantenimiento"
      console.error("Error creating tipo de mantenimiento:", err)
      console.log("Response Data (createTipoMantenimiento):", err.response?.data)
      throw new Error("Error al crear el tipo de mantenimiento", { cause: err })
    } finally {
      loading.value = false
    }
  }

  const updateTipoMantenimiento = async (id, tipoMantenimientoData) => {
    loading.value = true
    error.value = null
    try {
      const token = localStorage.getItem("jwt_token")
      if (!token) {
        throw new Error("No se encontró un token")
      }
      console.log("Updating Tipo de mantenimiento:", id, tipoMantenimientoData)
      const response = await axios.patch(`${API_URL}/tipo-mantenimiento/${id}`, tipoMantenimientoData, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      console.log("Update response:", response)
      const index = tiposMantenimiento.value.findIndex((item) => item.id === id)
      if (index !== -1) {
        tiposMantenimiento.value[index] = { ...tiposMantenimiento.value[index], ...response.data }
      }
      filterTiposMantenimiento()
      return response.data
    } catch (err) {
      console.error("Error updating Tipo de mantenimiento:", err.response || err)
      error.value = err.response?.data?.message || err.message || "Failed to update Tipo de mantenimiento"
      throw new Error(error.value, { cause: err })
    } finally {
      loading.value = false
    }
  }

  const toggleTipoMantenimientoStatus = async (id) => {
    loading.value = true
    error.value = null
    try {
      const token = localStorage.getItem("jwt_token")
      if (!token) {
        throw new Error("No se encontró un token")
      }
      const response = await axios.patch(
        `${API_URL}/tipo-mantenimientos/${id}/toggle-status`,
        {},
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        },
      )
      console.log("toggleTipoMantenimientoStatus response:", response)
      const index = tiposMantenimiento.value.findIndex((t) => t.id === id)
      if (index !== -1) {
        tiposMantenimiento.value[index] = { ...tiposMantenimiento.value[index], ...response.data }
      }
      filterTiposMantenimiento()
      return response.data
    } catch (err) {
      console.error("Error in toggleTipoMantenimientoStatus:", err.response || err)
      error.value = err.response?.data?.message || "Error al actualizar el estado del Tipo de mantenimiento"
      throw new Error(error.value)
    } finally {
      loading.value = false
    }
  }

  const filterTiposMantenimiento = () => {
    if (!searchQuery.value) {
      filteredTiposMantenimiento.value = [...tiposMantenimiento.value]
    } else {
      filteredTiposMantenimiento.value = tiposMantenimiento.value.filter((tipo) =>
        tipo.nombre.toLowerCase().includes(searchQuery.value.toLowerCase()),
      )
    }
  }

  return {
    tiposMantenimiento,
    filteredTiposMantenimiento,
    loading,
    error,
    fetchTiposMantenimiento,
    createTipoMantenimiento,
    updateTipoMantenimiento,
    toggleTipoMantenimientoStatus,
    searchQuery,
    filterTiposMantenimiento,
  }
}

