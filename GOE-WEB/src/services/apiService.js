import axios from "axios"

const API_URL = import.meta.env.VITE_API_URL || "/api"

const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
})

// Add token to requests
api.interceptors.request.use(
  config => {
    const token = localStorage.getItem("token")
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  error => Promise.reject(error)
)

// Handle response errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    const originalRequest = error.config

    // Ignorar verificación o renovación de token
    const shouldIgnore =
      originalRequest.url.includes("/usuario/verify") ||
      originalRequest.url.includes("/usuario/refresh")

    if (error.response?.status === 401 && !shouldIgnore) {
      localStorage.removeItem("token")
      window.location.href = "/login"
    }

    return Promise.reject(error)
  }
)

export const apiService = {
  // Users
  async getUsers() {
    const response = await api.get("/usuario")
    return { success: true, data: { userData: response.data.data } }
  },

  async createUser(userData) {
    const response = await api.post("/users", userData)
    return response.data
  },

  async updateUser(id, userData) {
    const response = await api.put(`/users/${id}`, userData)
    return response.data
  },

  async deleteUser(id) {
    const response = await api.delete(`/users/${id}`)
    return response.data
  },

  // Students
  async getStudents() {
    const response = await api.get("/usuario/students")
    return { success: true, data: { studentsData: response.data.data } }
  },

  async getStudentsByCourse(courseId) {
    const response = await api.get(`/usuario/estudiantes/${courseId}`)
    return { success: true, data: { studentsData: response.data.data } }
  },

  // Courses
  async getCourses() {
    const response = await api.get("/curso")
    return { success: true, data: { CursosData: response.data.data } }
  },

    async getCoursesMat() {
    const response = await api.get("/curso/all")
    return { success: true, data: { CursosData: response.data.data } }
  },

  async createCourse(courseData) {
    const response = await api.post("/courses", courseData)
    return response.data
  },

  async updateCourse(id, courseData) {
    const response = await api.put(`/courses/${id}`, courseData)
    return response.data
  },

  async deleteCourse(id) {
    const response = await api.delete(`/courses/${id}`)
    return response.data
  },

  // Subjects
  async getSubjects() {
    const response = await api.get("/subjects")
    return response.data
  },

  async createSubject(subjectData) {
    const response = await api.post("/subjects", subjectData)
    return response.data
  },

  async updateSubject(id, subjectData) {
    const response = await api.put(`/subjects/${id}`, subjectData)
    return response.data
  },

  async deleteSubject(id) {
    const response = await api.delete(`/subjects/${id}`)
    return response.data
  },

  // Attendance
  async getAttendance(filters = {}) {
    const params = new URLSearchParams(filters)
    const response = await api.get(`/attendance?${params}`)
    return response.data
  },

  async createAttendance(attendanceData) {
    const response = await api.post("/attendance", attendanceData)
    return response.data
  },

  async updateAttendance(id, attendanceData) {
    const response = await api.put(`/attendance/${id}`, attendanceData)
    return response.data
  },

  // Observers
  async getObservers() {
    const response = await api.get("/observador")
    //console.log("Observers fetched:", response.data.data)
    return { success: true, data: { observerData: response.data.data } }
  },

  async getObserversStudent(documento) {
    const response = await api.get(`/observador/estudiante/${documento}`)
    console.log("Observers fetched:", response.data.data)
    return { success: true, data: { observerData: response.data.data } }
  },

  async createObserver(observerData) {
    const response = await api.post("/observador", observerData)
    return response.data
  },

  async updateObserver(id, observerData) {
    const response = await api.put(`/observador/${id}`, observerData)
    return response.data
  },

  async deleteObserver(id) {
    const response = await api.delete(`/observador/${id}`)
    return response.data
  },

  // Email
  async sendEmail(emailData) {
    const response = await api.post("/email/send", emailData)
    return response.data
  },
}
