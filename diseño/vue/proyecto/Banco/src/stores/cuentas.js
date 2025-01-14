// Almacén con las cuentas de todos los clientes.
import { ref } from 'vue'
import { defineStore } from 'pinia'
import cuentas from '../datos/cuentas.json'

export const useCuentasStore = defineStore('cuentas', () => {
    // Todas las cuentas de todos los clientes.
    const datos = ref(cuentas);
    // Array con las cuentas de un cliente.
    const listaCuentasCliente = ref([])
    // Cuenta a modificar
    const cuenta = ref(null);

    function fCuentasUnCliente(id)
    {
      // Buscar las cuentas de un cliente a partir de su id
        listaCuentasCliente.value = datos.value.find((cli) => cli.id == id)?.cuentas || [];
    }

    function fAnadirMovimiento(obj)
    {
      // Añadir movimiento la cuenta origen.
      cuenta.value = listaCuentasCliente.value.find((cta)=>cta.numeroCuenta == obj.cuentaOrigen);
      const movimiento={
              "fecha": new Date(),
              "tipo": "Transferencia",
              "importe": obj.cantidad
          }
      cuenta.value.movimientos.push(movimiento)
      // Actualizar saldo
      cuenta.value.saldo = cuenta.value.saldo - obj.cantidad;

      // En destino también?
      cuenta.value = listaCuentasCliente.value.find((cta)=>cta.numeroCuenta == obj.cuentaDestino);
      console.log('cuenta' + obj.cuentaDestino)
      if (cuenta.value != null){
        const m={
              "fecha": new Date(),
              "tipo": "Transferencia",
              "importe": obj.cantidad
          }
         cuenta.value.movimientos.push(m)
        // Actualizar saldo
        cuenta.value.saldo = cuenta.value.saldo + obj.cantidad;
      }
    }

  return {listaCuentasCliente,fCuentasUnCliente,fAnadirMovimiento}
})