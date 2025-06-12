import axios from "axios"

const API_URL = import.meta.env.VITE_API_URL || "/api"

const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
  },
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token")
  if (token) {
    config.headers.authorization = `Bearer ${token}`
  }
  return config
})

export const authService = {
  async login(email, clave) {
    try {
      const response = await api.post("/usuario/login", { email, clave })
      const { user, token } = response.data.data

      return {
        success: true,
        data: { user, token }
      }
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || "Credenciales incorrectas"
      }
    }
  },

  async refreshToken(token) {
    try {
      const response = await api.post("/usuario/refresh", {
        headers: { authorization: `Bearer ${token}` }
      })
      return {
        success: true,
        token: response.data.token,
      }
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || "Error al refrescar el token"
      }
    }
  },

  async verifyToken(token) {
    try {
      const response = await api.post("/usuario/verify", {
        headers: { authorization: `Bearer ${token}` }
      })
      console.log(response.data.user)
      return { success: true, data: response.data.user }
    } catch (error) {
      throw error
    }
  },


  async forgotPassword(email) {
    try {
      const response = await api.post("/usuario/forgot-password", { email })
      return response.data
    } catch (error) {
      throw error
    }
  },

  async resetPassword(token, password) {
    try {
      const response = await api.post("/usuario/reset-password", { token, password })
      return response.data
    } catch (error) {
      throw error
    }
  }
}
