<template>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <div class="sidebar p-2" :class="{ 'collapsed': isCollapsed }">
    <h1 class="mb-4 text-center">
      <a href="#" class="logo">
        <span class="logo-full">NovaByte</span>
        <span class="logo-short">N.</span>
      </a>
    </h1>
    <nav class="nav flex-column">
      <div v-for="(item, index) in menuItems" :key="index" class="nav-item"
           @mouseenter="showSubmenu(index)"
           @mouseleave="hideSubmenu(index)">
        <a href="#" class="nav-link" @click.prevent="navigateTo(item.route)">
          <i :class="item.icon" ></i>
          <span class="nav-link-text">{{ item.title }}</span>
        </a>
        <div v-if="item.submenu" class="submenu" :class="{ 'show': item.isHovered }">
          <h2 class="submenu-title">{{ item.submenuTitle }}</h2>
          <p class="submenu-subtitle">{{ item.submenuSubtitle }}</p>
          <h3 class="submenu-section-title">{{ item.sectionTitle }}</h3>
          <div class="workshops-container">
            <div v-for="(subItem, subIndex) in item.submenu" :key="subIndex" class="item-card" @click="navigateTo(subItem.route)">
              <span>{{ subItem.name }}</span>
              <span v-if="subItem.active" class="activity-dot"></span>
            </div>
          </div>
        </div>
      </div>
    </nav>

  </div>

  <div class="table-body">
        <div
          v-for="(taller, i) in filteredTalleres"
          :key="i"
          class="table-row cursor-pointer"
          @click="goToTaller(taller.id_taller)"
        >
          <div class="font-medium">{{ taller.nombre }}</div>
          

        </div>
      </div>
</template>


<script>
import { useRouter } from 'vue-router'
import '../assets/css/Nav.css' // Asegúrate de que la ruta sea correcta



export default {
  

  setup() {
    const router = useRouter()

    const navigateTo = (route) => {
      if (route) {
        router.push(route)
      }
    }

    return { navigateTo }
  },
  data() {
     const talleres =  [
         { name: 'Taller Mecánico 2', id: 1 },
          { name: 'Taller Eléctrico Secundario', id: 2 },
          { name: 'Taller de Desarrollo Web', id: 3 },
            { name: 'Almacén Auxiliar', id: 4 },
         { name: 'Laboratorio de Electrónica', id: 5 },
         { name: 'Taller de Mantenimiento Pesado', id: 6 },
             { name: 'Taller de Carpintería', id: 7 },
            { name: 'Laboratorio de Química', id: 8 }
     ];
    return {
      activeSubmenu: null,
      submenuTimeout: null,
      isCollapsed: true,
      menuItems: [
        {
          title: 'Incidencias',
          icon: 'bi bi-shield',
          route: '/incidencias',
          submenuTitle: 'Gestión de Incidencias',
          submenuSubtitle: 'Selecciona una sección.',
          sectionTitle: 'Incidencias Activas',
          isHovered: false,
          submenu: [
             { name: 'Todos los Talleres', active: true, route: '/incidencias' },
            ...talleres.map(taller => ({
                   name: taller.name,
                   active: true,
                   route: `/incidencias/taller/${taller.id}`
                 })),
          ]
        },
        {
          title: 'Mantenimientos',
          icon: 'bi bi-calendar-event',
          route: '/mantenimientos',
          submenuTitle: 'Talleres de Mekatrónica',
          submenuSubtitle: 'Selecciona el taller.',
          sectionTitle: 'Talleres',
          isHovered: false,
          submenu: [
            { name: 'Todos los Talleres', active: true, route: '/mantenimientos' },
             ...talleres.map(taller => ({
                   name: taller.name,
                   active: true,
                  route: `/mantenimientos/taller/${taller.id}`
                 })),
          ]
        },

        {
          title: 'Máquinas',
          icon: 'bi bi-pc-display-horizontal',
          route: '/maquinas',
          submenuTitle: 'Inventario de Máquinas',
          submenuSubtitle: 'Gestión de equipamiento.',
          sectionTitle: 'Equipos',
          isHovered: false,
          submenu: [
            { name: 'Activas', active: true, route: '/maquinas/activas' },
            { name: 'Mantenimiento', active: true, route: '/maquinas/mantenimiento' },
            { name: 'Historial', active: false, route: '/maquinas/historial' },
            { name: 'Inventario', active: false, route: '/maquinas/inventario' }
          ]
        },
        {
          title: 'Usuarios',
          icon: 'bi bi-people',
          submenuTitle: 'Gestión de Usuarios',
          submenuSubtitle: 'Administración de accesos.',
          sectionTitle: 'Usuarios del Sistema',
          isHovered: false,
          submenu: [
            { name: 'Todos', active: false, route: '/usuarios' },
            { name: 'Administradores', active: false, route: '/usuarios/administradores' },
            { name: 'Técnicos', active: false, route: '/usuarios/tecnicos' },
            { name: 'Operarios', active: false, route: '/usuarios/operarios' },

          ]
        },
        {
          title: 'Perfil',
          icon: 'bi bi-person',
          route: '/perfil',
          submenuTitle: 'Mi Perfil',
          submenuSubtitle: 'Gestión de cuenta.',
          sectionTitle: 'Configuración',
          isHovered: false,
          submenu: [
            { name: 'Datos Personales', active: false, route: '/perfil/datos-personales' },
            { name: 'Preferencias', active: false, route: '/perfil/preferencias' },
            { name: 'Seguridad', active: false, route: '/perfil/seguridad' },
            { name: 'Notificaciones', active: false, route: '/perfil/notificaciones' }
          ]
        },
        {
          title: 'Ayuda',
          icon: 'bi bi-info-circle',
          route: '/ayuda',
          submenuTitle: 'Centro de Ayuda',
          submenuSubtitle: 'Soporte y recursos.',
          sectionTitle: 'Recursos',
          isHovered: false,
          submenu: [
            { name: 'Guías', active: false, route: '/ayuda/guias' },
            { name: 'FAQ', active: false, route: '/ayuda/faq' },
            { name: 'Contacto', active: false, route: '/ayuda/contacto' },
            { name: 'Reportar Error', active: false, route: '/ayuda/reportar-error' }
          ]
        }
      ]
    }
  },

  methods: {
    showSubmenu(index) {
      clearTimeout(this.submenuTimeout);
      this.submenuTimeout = setTimeout(() => {
        this.menuItems.forEach((item, i) => {
          if (i === index) {
            item.isHovered = true;
          } else {
            item.isHovered = false;
          }
        });
      }, 100); // 0.5 second delay
    },
    hideSubmenu(index) {
      clearTimeout(this.submenuTimeout);
      this.submenuTimeout = setTimeout(() => {
        this.menuItems[index].isHovered = false;
      }, 100);
    },
    toggleSidebar() {
      this.isCollapsed = !this.isCollapsed;
    }
  },
};
</script>

<style scoped>

</style>