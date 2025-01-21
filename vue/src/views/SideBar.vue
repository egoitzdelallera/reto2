<template>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <div class="sidebar p-2" :class="{ 'collapsed': isCollapsed }">
    <h1 class="mb-4 text-center">
      <a href="#" class="logo">
        <span class="logo-full"></span>
        <span class="logo-short">E.</span>
      </a>
    </h1>
    <nav class="nav flex-column">
      <div v-for="(item, index) in updatedMenuItems" :key="index" class="nav-item"
           @mouseenter="showSubmenu(index)"
           @mouseleave="hideSubmenu(index)">
        <a href="#" class="nav-link" @click.prevent="navigateTo(item.route)">
          <i :class="item.icon"></i>
          <span class="nav-link-text">{{ item.title }}</span>
        </a>
        <div v-if="item.submenu" class="submenu" :class="{ 'show': isHovered[index] }">
          <h2 class="submenu-title">{{ item.submenuTitle }}</h2>
          <p class="submenu-subtitle">{{ item.submenuSubtitle }}</p>
          <h3 class="submenu-section-title">{{ item.sectionTitle }}</h3>
          <div class="workshops-container">
            <div v-for="(subItem, subIndex) in item.submenu" :key="subIndex" class="item-card" @click="navigateTo(subItem.route, subItem.role)">
              <span>{{ subItem.name }}</span>
              <span v-if="subItem.active" class="activity-dot"></span>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
import { useRouter } from 'vue-router';
import '../assets/css/Nav.css';
import useTalleres from '@/composables/useTalleres';
import { onMounted, ref, reactive } from 'vue';

export default {
  setup() {
    const router = useRouter();
    const { talleres, fetchTalleres } = useTalleres();
    const isCollapsed = ref(true);
    const activeSubmenu = ref(null);
    const submenuTimeout = ref(null);

      const menuItems = ref([
          {
              title: 'Incidencias',
              icon: 'bi bi-shield',
              route: '/incidencias',
              submenuTitle: 'Gestión de Incidencias',
              submenuSubtitle: 'Selecciona una sección.',
              sectionTitle: 'Incidencias Activas',
              submenu: [
                  { name: 'Todas', active: false, route: '/incidencias' }
                  
              ]
          },
          {
              title: 'Mantenimientos',
              icon: 'bi bi-calendar-event',
              route: '/mantenimientos',
              submenuTitle: 'Talleres de Mekatrónica',
              submenuSubtitle: 'Selecciona el taller.',
              sectionTitle: 'Talleres',
              submenu: [
                  { name: 'Todos los Talleres', active: true, route: '/mantenimientos' }
              ]
          },
          {
              title: 'Máquinas',
              icon: 'bi bi-pc-display-horizontal',
              route: '/maquinas',
              submenuTitle: 'Inventario de Máquinas',
              submenuSubtitle: 'Gestión de equipamiento.',
              sectionTitle: 'Equipos',
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
              route: '/usuarios',
              submenuTitle: 'Gestión de Usuarios',
              submenuSubtitle: 'Administración de accesos.',
              sectionTitle: 'Usuarios del Sistema',
              submenu: [
                  { name: 'Todos', active: false, route: '/usuarios', role: 'Todos' },
                  { name: 'Administradores', active: false, route: '/usuarios', role: 'Admin' },
                  { name: 'Técnicos', active: false, route: '/usuarios', role: 'Técnico' },
                  { name: 'Operarios', active: false, route: '/usuarios', role: 'Operario' },
              ]
          },
          {
              title: 'Perfil',
              icon: 'bi bi-person',
              route: '/perfil',
              submenuTitle: 'Mi Perfil',
              submenuSubtitle: 'Gestión de cuenta.',
              sectionTitle: 'Configuración',
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
              submenu: [
                  { name: 'Guías', active: false, route: '/ayuda/guias' },
                  { name: 'FAQ', active: false, route: '/ayuda/faq' },
                  { name: 'Contacto', active: false, route: '/ayuda/contacto' },
                  { name: 'Reportar Error', active: false, route: '/ayuda/reportar-error' }
              ]
          }
      ]);

    const isHovered = reactive(menuItems.value.map(() => false));

    const navigateTo = (route, role) => {
      if (route === '/usuarios') {
        router.push({ path: route, query: { role: role || 'Todos' } });
      } else if (route) {
        router.push(route);
      }
    };
      onMounted(() => {
          fetchTalleres();
      });

    return { navigateTo, talleres, isCollapsed, activeSubmenu, submenuTimeout, menuItems, isHovered };
  },
  computed: {
    updatedMenuItems() {
        const talleresSubmenu = (this.talleres || []).map(taller => ({
        name: taller.nombre,
        active: true,
        route: `/mantenimientos/taller/${taller.id_taller}`
      }));
       const incidenciasTalleres = (this.talleres || []).map(taller => ({
         name: taller.nombre,
         active: true,
         route: `/incidencias/taller/${taller.id_taller}`
       }));
      return this.menuItems.map((item) => {
        if (item.title === 'Mantenimientos') {
          return { ...item, submenu: [...item.submenu, ...talleresSubmenu] };
        }else if(item.title === 'Incidencias'){
          return { ...item, submenu: [...item.submenu, ...incidenciasTalleres] };
        }
        return item;
      });
    }
  },
  methods: {
      showSubmenu(index) {
          clearTimeout(this.submenuTimeout);
          this.submenuTimeout = setTimeout(() => {
              this.isHovered.fill(false)
            this.isHovered[index] = true;
          }, 100);
      },
      hideSubmenu(index) {
          clearTimeout(this.submenuTimeout);
          this.submenuTimeout = setTimeout(() => {
            this.isHovered[index] = false;
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