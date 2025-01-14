// Almacén  que usa los datos de los clientes para identificarlos.
import { ref } from 'vue'
import { defineStore } from 'pinia'
import clientes from '../datos/clientes.json'

export const useClientesStore = defineStore('clientes', () => {
    // Lista de todos los clientes
    const listaClientes = ref(clientes)
    // Cliente obtenido a partir del id y de la contraseña.
    const unCliente = ref(null);

    function fUnCliente(obj)
    {
      // Buscar un cliente con un id y una contraseña
        unCliente.value = listaClientes.value.find((c)=>c.id == obj.id && c.password == obj.password)
    }

  return {fUnCliente,unCliente}
})