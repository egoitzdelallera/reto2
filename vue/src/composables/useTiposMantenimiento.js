import { ref } from "vue"
import axios from "axios"

export default function useTiposMantenimiento() {
  const tiposMantenimiento = ref([])
  const loading = ref(false)
  const error = ref(null)

  const fetchTiposMantenimiento = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await axios.get("http://localhost:8000/api/tipos-mantenimiento")
      tiposMantenimiento.value = response.data
    } catch (e) {
      error.value = "Error fetching tipos mantenimiento"
      console.error(e)
    } finally {
      loading.value = false
    }
  }

  const createTipoMantenimiento = async (tipoMantenimientoData) => {
    loading.value = true
    error.value = null
    try {
      const response = await axios.post("http://localhost:8000/api/tipo-mantenimientos", tipoMantenimientoData)
      tiposMantenimiento.value.push(response.data)
      return response.data
    } catch (e) {
      error.value = "Error creating tipo mantenimiento"
      console.error(e)
      throw e
    } finally {
      loading.value = false
    }
  }

  const updateTipoMantenimiento = async (id, tipoMantenimientoData) => {
    loading.value = true
    error.value = null
    try {
      console.log("Updating tipo mantenimiento:", id, tipoMantenimientoData)
      const response = await axios.put(`http://localhost:8000/api/tipo-mantenimientos/${id}`, tipoMantenimientoData)
      console.log("Update response:", response)
      const index = tiposMantenimiento.value.findIndex((item) => item.id === id)
      if (index !== -1) {
        tiposMantenimiento.value[index] = response.data
      }
      return response.data
    } catch (e) {
      error.value = "Error updating tipo mantenimiento"
      console.error("Error updating tipo mantenimiento:", e.response || e)
      throw e
    } finally {
      loading.value = false
    }
  }

  return {
    tiposMantenimiento,
    loading,
    error,
    fetchTiposMantenimiento,
    createTipoMantenimiento,
    updateTipoMantenimiento,
  }
}

