<script setup>
  /* Función: realizar una transferencia bancaria. Necesitamos la cuenta origen, destino y la cantidad.
   La cuenta destino puede ser una de las del cliente u otra cuenta nacional.La fecha suponemos que es la del sistema. */
  import {ref} from 'vue';

  import TransaccionCuentaDestinoMia from '../components/TransaccionCuentaDestinoMia.vue'
  import TransaccionCuentaDestinoOtra from '../components/TransaccionCuentaDestinoOtra.vue'
  import TransaccionCantidad from '../components/TransaccionCantidad.vue'
  import TransaccionCuentaOrigen from '../components/TransaccionCuentaOrigen.vue'

  import {useCuentasStore} from '../stores/cuentas';

  // Componente dinámico. Transferencia a una cuenta del cliente o a otra.
  const opciones = {
    TransaccionCuentaDestinoMia,
    TransaccionCuentaDestinoOtra
  }
  const seleccion = ref('')

  // Almacén con las cuentas para obtener las cuentas del usuario identificado.
  
  const cuentasStore = useCuentasStore();
  const cuentas = ref([]);
  cuentasStore.fCuentasUnCliente(localStorage.getItem('idCliente'));
  cuentas.value = cuentasStore.listaCuentasCliente;
  // No tiene otra cuenta propia para hacer la transferencia.
  if (cuentas.value.length == 1)
    seleccion.value = 'TransaccionCuentaDestinoOtra';
  
  // Datos de la transacción
  const cuentaOrigen=ref('');
  const cuentaDestino = ref('');
  const cantidad = ref(0);

  //const ver = ref(false);
  // Después de indicar la cuenta origen, mostrar los radios para indicar si la cuenta destino es del cliente o es otra.
  const mostrarRadios=ref(false);
  // Cuando tengamos la cuenta origen y destino, mostraremos la caja para la cantidad
  const verCantidad = ref(false);

  // Opción seleccionada a través de los radios. Cuenta destino del cliente u otra.
  const opcionSeleccionada = ref('');
  function fTipoCuentaDestino()
  {
    // Dependiendo del radio seleccionado, se carga un componente u otro.
    if (opcionSeleccionada.value == "mia")
      seleccion.value = 'TransaccionCuentaDestinoMia';
    else
      seleccion.value = 'TransaccionCuentaDestinoOtra';
  }

  function fGuardarOrigen(index)
  {
    // Guardar la cuenta origen y mostrar los radios.
    mostrarRadios.value = true;
    cuentaOrigen.value=cuentas.value[index].numeroCuenta
  }

  function fCuentaDestino(cuenta){
    // Guarda la cuenta destino y muestra la caja para teclear la cantidad.
    cuentaDestino.value = cuenta
    verCantidad.value = true;
  }

  function fCantidad(cant){
    // Recoge el importe del transacción.
    cantidad.value = cant;

    // Ya tenemos todos los datos. Enviamos para añadir el movimiento al almacén.
    const obj={
      "cuentaOrigen": cuentaOrigen.value,
      "cuentaDestino": cuentaDestino.value,
      "cantidad":cantidad.value
   }
   cuentasStore.fAnadirMovimiento(obj);
}
</script>

<template>
  <TransaccionCuentaOrigen>
      <p>Selecciona la cuenta origen de la transacción</p>
      <ul v-for="(cuenta,index) of cuentas" :key="index">
        <li @click="fGuardarOrigen(index)">{{ cuenta.numeroCuenta }} </li>
      </ul>

      <!-- Un div no lleva v-if-->
      <template v-if="mostrarRadios && cuentas.length > 1">
        <div>
          <input type="radio"  value="mia" v-model="opcionSeleccionada" @change="fTipoCuentaDestino"/>
          <label>A una de mis cuentas</label>
          <br>
          <input type="radio"  value="otra" v-model="opcionSeleccionada" @change="fTipoCuentaDestino"/>
          <label>A otra cuenta</label>
        </div>
      </template>
      
  </TransaccionCuentaOrigen>

  <component :is="opciones[seleccion]" :cuentaOrigen="cuentaOrigen" @destino="fCuentaDestino"></component> 
  
  <TransaccionCantidad v-if="verCantidad"  @cantidad="fCantidad"></TransaccionCantidad>
</template>
