import axios from "axios";

const API_URL = "http://192.168.1.145:3000/api"; // Ajusta la URL si usas un servidor en red

export const usuarioServices = {
    getAll: async () => {
        try {
            const response = await axios.get(`${API_URL}/usuario`);
            return response.data;
        } catch (error) {
            console.error("Error al obtener los usuarios:", error);
            throw error;
        }
    },

    getById : async (id: string) => {
        try {
            const response = await axios.get(`${API_URL}/usuario/${id}`);
            return response.data;
        } catch (error) {
            console.error("Error al obtener el usuario:", error);
            throw error;
        }
    },

    create: async (usuarioData: any) => {
        try {
            const response = await axios.post(`${API_URL}/usuario`, usuarioData);
            return response.data;
        } catch (error) {
            console.error("Error al crear el usuario:", error);
            throw error;
        }
    },

    update: async (id: string, usuarioData: any) => {
        try {
            const response = await axios.put(`${API_URL}/usuario/${id}`, usuarioData);
            return response.data;
        } catch (error) {
            console.error("Error al actualizar el usuario:", error);
            throw error;
        }
    },

    delete: async (id: string) => {
        try {
            const response = await axios.delete(`${API_URL}/usuario/${id}`);
            return response.data;
        } catch (error) {
            console.error("Error al eliminar el usuario:", error);
            throw error;
        }
    },
    // Servicio API para conectar con el backend Node.js
    login: async (email: string, password: string) => {
        try {
            const response = await axios.post(`${API_URL}/auth/login`, { email, password });
            return response.data;
        } catch (error) {
            console.error("Error al iniciar sesión:", error);
            throw error;
        }
    }
}

// Servicio API para conectar con el backend PHP
import type { User } from "../context/AuthContext"

// Interfaz para las respuestas de la API
interface ApiResponse<T> {
    success: boolean
    data?: T
    message?: string
    error?: string
}

// Función para manejar errores de red
const handleNetworkError = (error: any): ApiResponse<null> => {
    console.error("Error de red:", error)

    // Proporcionar mensajes más específicos según el tipo de error
    let errorMessage = "Error de conexión. Por favor verifica tu conexión a internet."

    if (error.message) {
        if (error.message.includes("Network request failed")) {
            errorMessage =
                "No se pudo conectar con el servidor. Verifica tu conexión a internet o si el servidor está disponible."
        } else if (error.message.includes("Timeout")) {
            errorMessage = "La solicitud ha excedido el tiempo de espera. Verifica tu conexión a internet."
        }
    }

    return {
        success: false,
        error: errorMessage,
    }
}

// Función para realizar peticiones a la API con timeout
const fetchApi = async <T>(
    endpoint: string,
    method: 'GET' | 'POST' | 'PUT' | 'DELETE' = 'GET',
    body?: any,
    token?: string
)
    : Promise<ApiResponse<T>> => {
    try {
        const headers: HeadersInit = {
            "Content-Type": "application/json",
        }

        // Si hay token de autenticación, lo añadimos a los headers
        if (token) {
            headers["Authorization"] = `Bearer ${token}`
        }

        const config: RequestInit = {
            method,
            headers,
            body: body ? JSON.stringify(body) : undefined,
        }

        try {
            // Crear una promesa de timeout
            const timeoutPromise = new Promise((_, reject) => {
                setTimeout(() => reject(new Error("Timeout")), 15000) // Aumentar a 15 segundos
            })

            // Realizar la petición con timeout
            const fetchPromise = fetch(`${API_URL}/${endpoint}`, config)

            // Usar Promise.race con un try/catch interno
            const response = (await Promise.race([fetchPromise, timeoutPromise])) as Response

            const data = await response.json()

            if (!response.ok) {
                return {
                    success: false,
                    error: data.message || `Error ${response.status}: ${response.statusText}`
                };
            }

            return {
                success: true,
                data: data.data,
                message: data.message
            };
        } catch (error: any) {
            // Manejar específicamente errores de timeout y red
            if (error.message === "Timeout") {
                return {
                    success: false,
                    error: "La solicitud ha excedido el tiempo de espera. Verifica tu conexión a internet."
                };
            }

            if (error.message && error.message.includes("Network request failed")) {
                return {
                    success: false,
                    error: "Error de conexión. Verifica que estés conectado a internet y que el servidor esté disponible."
                };
            }

            return handleNetworkError(error);
        }
    } catch (error: any) {
        return handleNetworkError(error);
    }
}

// Servicio de autenticación
export const authService = {
    // Iniciar sesión
    login: async (email: string, password: string): Promise<ApiResponse<User>> => {
        return fetchApi<User>("auth/login", "POST", { email, password })
    },

    // Cerrar sesión
    logout: async (token: string): Promise<ApiResponse<null>> => {
        return fetchApi<null>("auth/logout", "POST", {}, token)
    },

    // Verificar token
    verifyToken: async (token: string): Promise<ApiResponse<User>> => {
        return fetchApi<User>("auth/verify", "GET", undefined, token)
    },
}

// Servicio de observadores
export const observadoresService = {
    // Obtener todos los observadores
    getAll: async (token: string): Promise<ApiResponse<any[]>> => {
        return fetchApi<any[]>("observadores", "GET", undefined, token)
    },

    // Obtener observadores por estudiante
    getByEstudiante: async (estudianteId: string, token: string): Promise<ApiResponse<any[]>> => {
        return fetchApi<any[]>(`observadores/estudiante/${estudianteId}`, "GET", undefined, token)
    },

    // Crear un nuevo observador
    create: async (observadorData: any, token: string): Promise<ApiResponse<any>> => {
        return fetchApi<any>("observadores", "POST", observadorData, token)
    },

    // Obtener detalle de un observador
    getById: async (id: string, token: string): Promise<ApiResponse<any>> => {
        return fetchApi<any>(`observadores/${id}`, "GET", undefined, token)
    },

    // Actualizar un observador
    update: async (id: string, observadorData: any, token: string): Promise<ApiResponse<any>> => {
        return fetchApi<any>(`observadores/${id}`, "PUT", observadorData, token)
    },

    // Eliminar un observador
    delete: async (id: string, token: string): Promise<ApiResponse<any>> => {
        return fetchApi<any>(`observadores/${id}`, "DELETE", undefined, token)
    },
}

// Servicio de asistencias
export const asistenciasService = {
    // Obtener todas las asistencias
    getAll: async (token: string): Promise<ApiResponse<any[]>> => {
        return fetchApi<any[]>("asistencias", "GET", undefined, token)
    },

    // Obtener asistencias por curso
    getByCurso: async (cursoId: string, token: string): Promise<ApiResponse<any[]>> => {
        return fetchApi<any[]>(`asistencias/curso/${cursoId}`, "GET", undefined, token)
    },

    // Registrar asistencia
    create: async (asistenciaData: any, token: string): Promise<ApiResponse<any>> => {
        return fetchApi<any>("asistencias", "POST", asistenciaData, token)
    },

    // Actualizar asistencia
    update: async (id: string, asistenciaData: any, token: string): Promise<ApiResponse<any>> => {
        return fetchApi<any>(`asistencias/${id}`, "PUT", asistenciaData, token)
    },

    // Eliminar una asistencia
    delete: async (id: string, token: string): Promise<ApiResponse<any>> => {
        return fetchApi<any>(`asistencias/${id}`, "DELETE", undefined, token)
    },
}

// Servicio de estudiantes
export const estudiantesService = {
    // Obtener todos los estudiantes
    getAll: async (token: string): Promise<ApiResponse<any[]>> => {
        return fetchApi<any[]>("estudiantes", "GET", undefined, token)
    },

    // Obtener estudiantes por curso
    getByCurso: async (cursoId: string, token: string): Promise<ApiResponse<any[]>> => {
        return fetchApi<any[]>(`estudiantes/curso/${cursoId}`, "GET", undefined, token)
    },

    // Obtener estudiantes a cargo de un docente
    getByDocente: async (docenteId: string, token: string): Promise<ApiResponse<any[]>> => {
        return fetchApi<any[]>(`estudiantes/docente/${docenteId}`, "GET", undefined, token)
    },

    // Obtener perfil de estudiante
    getById: async (id: string, token: string): Promise<ApiResponse<any>> => {
        return fetchApi<any>(`estudiantes/${id}`, "GET", undefined, token)
    },

    // Actualizar perfil de estudiante
    update: async (id: string, estudianteData: any, token: string): Promise<ApiResponse<any>> => {
        return fetchApi<any>(`estudiantes/${id}`, "PUT", estudianteData, token)
    },
}

// Servicio de perfil
export const profileService = {
    // Obtener perfil del usuario
    getProfile: async (token: string): Promise<ApiResponse<User>> => {
        return fetchApi<User>("profile", "GET", undefined, token)
    },

    // Actualizar perfil
    updateProfile: async (profileData: Partial<User>, token: string): Promise<ApiResponse<User>> => {
        return fetchApi<User>("profile", "PUT", profileData, token)
    },
}

