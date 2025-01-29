import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const API_URL = 'http://localhost:8000/api/users';

export default function useUsuarios() {
    const users = ref([]);
    const isLoading = ref(false);
    const error = ref(null);
     const router = useRouter();
    const getRoleBadgeClass = (rol) => {
        const classes = {
        'Administrador': 'bg-info text-primary',
        'Tecnico': 'bg-secondary text-black',
        'Operario': 'bg-primary text-info border border-secondary'
        }
        return classes[rol] || 'bg-secondary'
    };
    const fetchUsers = async (role) => {
        isLoading.value = true;
        error.value = null;
        try{
              const token = localStorage.getItem('jwt_token');
              if (!token) {
                error.value = 'No se encontró un token';
                router.push("/login");
                 throw new Error('No se encontró un token');
                }
            const headers = {
                'Authorization': `Bearer ${token}`
            };
            const response = await axios.get(API_URL, {
            params: {
              page: 1,
              limit: 1000,
              role: role !== 'Todos' ? role : undefined
            },
            headers: headers
            });
            console.log("Response from fetch users useUsuarios:", response.data)
              const filteredUsersByRole = role !== 'Todos'
            ? response.data.filter(user => user.rol === role)
            : response.data;
            users.value = filteredUsersByRole;
            }
          catch (err){
           error.value = err.message;
           console.error("Error fetching data", err);
            console.log("Response data from fetchUsers:", err.response?.data);
        }
        finally {
         isLoading.value = false;
        }
    };
     return {
       users,
       isLoading,
       error,
       getRoleBadgeClass,
      fetchUsers
      };
    }