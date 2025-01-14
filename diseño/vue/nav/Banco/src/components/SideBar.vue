<template>
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
          <span class="nav-link-icon me-3">❗</span>
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
</template>


  /*data() {
    return {
      
      activeSubmenu: null,
      submenuTimeout: null,
      isCollapsed: true,
      menuItems: [
        {
          title: 'Incidencias',
          submenuTitle: 'Gestión de Incidencias',
          submenuSubtitle: 'Selecciona una sección.',
          sectionTitle: 'Incidencias Activas',
          isHovered: false,
          submenu: [
            { name: 'Pendientes', active: true },
            { name: 'En Proceso', active: true },
            { name: 'Resueltas', active: false },
            { name: 'Todas', active: false }
          ]
        },
        {
          title: 'Mantenimientos',
          submenuTitle: 'Talleres de Mekatrónica',
          submenuSubtitle: 'Selecciona el taller.',
          sectionTitle: 'Talleres',
          isHovered: false,
          submenu: [
            { name: 'Todos los Talleres', active: true },
            { name: 'Taller A', active: true },
            { name: 'Taller B', active: false },
            { name: 'Taller C', active: false },
            { name: 'Taller D', active: false },
            { name: 'Taller E', active: false },
            { name: 'Taller F', active: false },
            { name: 'Taller G', active: false },
            { name: 'Taller H', active: true }
          ]
        },
        {
          title: 'Técnicos',
          submenuTitle: 'Equipo Técnico',
          submenuSubtitle: 'Gestión de técnicos.',
          sectionTitle: 'Personal Técnico',
          isHovered: false,
          submenu: [
            { name: 'Disponibles', active: true },
            { name: 'En Servicio', active: true },
            { name: 'Calendario', active: false },
            { name: 'Informes', active: false }
          ]
        },
        {
          title: 'Máquinas',
          submenuTitle: 'Inventario de Máquinas',
          submenuSubtitle: 'Gestión de equipamiento.',
          sectionTitle: 'Equipos',
          isHovered: false,
          submenu: [
            { name: 'Activas', active: true },
            { name: 'Mantenimiento', active: true },
            { name: 'Historial', active: false },
            { name: 'Inventario', active: false }
          ]
        },
        {
          title: 'Usuarios',
          submenuTitle: 'Gestión de Usuarios',
          submenuSubtitle: 'Administración de accesos.',
          sectionTitle: 'Usuarios del Sistema',
          isHovered: false,
          submenu: [
            { name: 'Administradores', active: false },
            { name: 'Técnicos', active: false },
            { name: 'Operarios', active: false },
            { name: 'Permisos', active: false }
          ]
        },
        {
          title: 'Perfil',
          submenuTitle: 'Mi Perfil',
          submenuSubtitle: 'Gestión de cuenta.',
          sectionTitle: 'Configuración',
          isHovered: false,
          submenu: [
            { name: 'Datos Personales', active: false },
            { name: 'Preferencias', active: false },
            { name: 'Seguridad', active: false },
            { name: 'Notificaciones', active: false }
          ]
        },
        {
          title: 'Ayuda',
          submenuTitle: 'Centro de Ayuda',
          submenuSubtitle: 'Soporte y recursos.',
          sectionTitle: 'Recursos',
          isHovered: false,
          submenu: [
            { name: 'Guías', active: false },
            { name: 'FAQ', active: false },
            { name: 'Contacto', active: false },
            { name: 'Reportar Error', active: false }
          ]
        }
      ]
    }
  },*/
  <script>
  import { useRouter } from 'vue-router'
  
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
    return {
      activeSubmenu: null,
      submenuTimeout: null,
      isCollapsed: true,
      menuItems: [
        {
          title: 'Incidencias',
          route: '/incidencias',
          submenuTitle: 'Gestión de Incidencias',
          submenuSubtitle: 'Selecciona una sección.',
          sectionTitle: 'Incidencias Activas',
          isHovered: false,
          submenu: [
          { name: 'Todas', active: false, route: '/incidencias' },
            { name: 'Pendientes', active: true, route: '/incidencias/pendientes' },
            { name: 'En Proceso', active: true, route: '/incidencias/en-proceso' },
            { name: 'Resueltas', active: false, route: '/incidencias/resueltas' }
            
          ]
        },
        {
          title: 'Mantenimientos',
          route: '/mantenimientos',
          submenuTitle: 'Talleres de Mekatrónica',
          submenuSubtitle: 'Selecciona el taller.',
          sectionTitle: 'Talleres',
          isHovered: false,
          submenu: [
            { name: 'Todos los Talleres', active: true, route: '/mantenimientos' },
            { name: 'Taller A', active: true, route: '/mantenimientos/taller-a' },
            { name: 'Taller B', active: false, route: '/mantenimientos/taller-b' },
            { name: 'Taller C', active: false, route: '/mantenimientos/taller-c' },
            { name: 'Taller D', active: false, route: '/mantenimientos/taller-d' },
            { name: 'Taller E', active: false, route: '/mantenimientos/taller-e' },
            { name: 'Taller F', active: false, route: '/mantenimientos/taller-f' },
            { name: 'Taller G', active: false, route: '/mantenimientos/taller-g' },
            { name: 'Taller H', active: true, route: '/mantenimientos/taller-h' }
          ]
        },
        {
          title: 'Técnicos',
          route: '/tecnicos',
          submenuTitle: 'Equipo Técnico',
          submenuSubtitle: 'Gestión de técnicos.',
          sectionTitle: 'Personal Técnico',
          isHovered: false,
          submenu: [
            { name: 'Disponibles', active: true, route: '/tecnicos/disponibles' },
            { name: 'En Servicio', active: true, route: '/tecnicos/en-servicio' },
            { name: 'Calendario', active: false, route: '/tecnicos/calendario' },
            { name: 'Informes', active: false, route: '/tecnicos/informes' }
          ]
        },
        {
          title: 'Máquinas',
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
*{
  font-family:
    'Inter',
    system-ui,
    -apple-system,
    'Segoe UI',
    sans-serif;
  font-size: 15px;
  text-rendering: optimizeLegibility;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  
}
a{
  text-decoration: none;
  color: inherit;
}
.sidebar {
  width: 200px;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  background: white;
  border-right: 1px solid #e5e7eb;
  transition: width 0.3s ease;
  overflow: hidden;
  z-index: 1000;
  display: flex;
  justify-content: flex-start;
  flex-direction: column;
}

.sidebar:not(:hover) {
  width: 60px;
}

.nav-item {
  position: relative;
  border-top-right-radius: 40px;
  border-bottom-right-radius: 40px;
 
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  text-decoration: none;
  color: #333;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 40px;
  visibility: visible;
}

.logo-full {
  display: none;
  
}

.logo-short {
  display: block;
 
}

.sidebar:not(.collapsed) .logo-full {
  display: block;
}

.sidebar:not(.collapsed) .logo-short {
  display: none;
}



.nav-link {
  color: #333;
  padding: 0.75rem 1rem;
  white-space: nowrap;
  display: flex;
  align-items: center;
  width: 95%;

}

.nav-link-text {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.sidebar:not(:hover) .nav-link-text {
  opacity: 0;
  transform: translateX(-20px);
}

.submenu {
  display: none;
  position: fixed;
  left: 0px;
  top: 0;
  width: 300px;
  height: 100vh;
  background-color: #e0e0e0;
  padding: 2rem;
  opacity: 0;
  transition:  0.3s;
  box-shadow: inset 5px 0px 10px -4px rgba(0, 0, 0, 0.3);
  -webkit-box-shadow: inset 5px 0px 10px -4px rgba(0, 0, 0, 0.3);
}

.submenu.show {
  display: block;
  opacity: 1;
  left: 190px;
}

.workshop-card, .item-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: transform 0.2s;
        }

.workshop-card:hover, .item-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.activity-dot {
  width: 8px;
  height: 8px;
  background-color: #0d6efd;
  border-radius: 50%;
}

.submenu-title {
  color: #0d6efd;
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  position: sticky;
  top: 0;
  background-color: transparent;
  padding: 1rem 0;
  z-index: 10;
}

.submenu-subtitle {
  color: #000;
  margin-bottom: 2rem;
  padding: 0 1rem;
}

.submenu-section-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  position: sticky;
  top: 70px;
  padding: 0.5rem 0;
  z-index: 9;
  color: #000;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  text-decoration: none;
  color: #333;
}

.workshops-container {
  max-height: calc(100vh - 280px);
  overflow-y: auto;
  padding-right: 1rem;
}

.workshops-container::-webkit-scrollbar {
  width: 14px;
  border-radius: 4px;
}

.workshops-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
  width: 10px;
}

.workshops-container::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
  border: 5px solid transparent;
  background-clip: content-box;
}

.item-card span {
  color: #000;
}
</style>