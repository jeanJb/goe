"use client"

import { useState, useEffect } from "react"
import { Users, BookOpen, Calendar, TrendingUp, Bell, Clock, CheckCircle, AlertTriangle, Filter } from "lucide-react"
import LoadingSpinner from "../../components/ui/LoadingSpinner"
import { useAuth } from "../../context/AuthContext"
import { apiService } from "../../services/apiService"

const HomePage = () => {
  const { user } = useAuth()
  const [stats, setStats] = useState(null)
  const [users, setUsers] = useState([])
  const [recentActivity, setRecentActivity] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const fetchUsers = async () => {
      setLoading(true)
      try {
        const response = await apiService.getUsers()
        //console.log("users: ", response.data.userData[0])
        setUsers(response.data.userData[0])
      } catch (error) {
        toast.error("Error al cargar usuarios")
      } finally {
        setLoading(false)
      }
    }

    fetchUsers()
  }, [])

  useEffect(() => {
    const fetchDashboardData = async () => {
      setLoading(true)
      try {
        // Simulate API call
        await new Promise((resolve) => setTimeout(resolve, 1000))

        const mockStats = {
          totalStudents: 245,
          totalTeachers: 18,
          totalCourses: 12,
          attendanceRate: 92.5,
        }

        const mockActivity = [
          {
            id: 1,
            type: "attendance",
            message: "Asistencia registrada para 11° A - Matemáticas",
            time: "Hace 2 horas",
            icon: CheckCircle,
            color: "text-green-500",
          },
          {
            id: 2,
            type: "observer",
            message: "Nuevo observador positivo para Ana María Rodríguez",
            time: "Hace 3 horas",
            icon: Bell,
            color: "text-blue-500",
          },
          {
            id: 3,
            type: "alert",
            message: "3 estudiantes con tardanzas recurrentes",
            time: "Hace 5 horas",
            icon: AlertTriangle,
            color: "text-yellow-500",
          },
          {
            id: 4,
            type: "course",
            message: "Nuevo curso creado: 6° C",
            time: "Ayer",
            icon: BookOpen,
            color: "text-purple-500",
          },
        ]

        setStats(mockStats)
        setRecentActivity(mockActivity)
      } catch (error) {
        console.error("Error fetching dashboard data:", error)
      } finally {
        setLoading(false)
      }
    }

    fetchDashboardData()
  }, [])

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <LoadingSpinner />
      </div>
    )
  }

  return (
    <div className="space-y-6">
      {/* Welcome Section */}
      <div className="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg p-6 text-white">
        <h1 className="text-2xl font-bold mb-2">¡Bienvenid@ de vuelta, {user?.nombre1 || "Usuario"}!</h1>
        <p className="text-primary-100">
          {user?.id_rol === 104
            ? "Panel de administración del sistema GOE"
            : user?.id_rol === 102
              ? "Gestiona tus clases y estudiantes"
              : user?.id_rol === 101
                ? "Revisa tu progreso académico"
                : "Sistema de Gestión Escolar"}
        </p>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-blue-500 p-3 rounded-lg">
              <Users className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Estudiantes</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">{users.filter((u) => u.id_rol == "101").length}</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-green-500 p-3 rounded-lg">
              <BookOpen className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Docentes</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">{users.filter((u) => u.id_rol === 102).length}</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-purple-500 p-3 rounded-lg">
              <Calendar className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Cursos Activos</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats?.totalCourses}</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-orange-500 p-3 rounded-lg">
              <TrendingUp className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">% Asistencia</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats?.attendanceRate}%</p>
            </div>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Recent Activity */}
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Actividad Reciente</h2>
          </div>
          <div className="p-6">
            <div className="space-y-4">
              {recentActivity.map((activity) => {
                const IconComponent = activity.icon
                return (
                  <div key={activity.id} className="flex items-start">
                    <div className={`p-2 rounded-lg ${activity.color} bg-opacity-10`}>
                      <IconComponent className={`w-4 h-4 ${activity.color}`} />
                    </div>
                    <div className="ml-3 flex-1">
                      <p className="text-sm text-gray-900 dark:text-white">{activity.message}</p>
                      <p className="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                        <Clock className="w-3 h-3 mr-1" />
                        {activity.time}
                      </p>
                    </div>
                  </div>
                )
              })}
            </div>
          </div>
        </div>

        {/* Quick Actions */}
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Acciones Rápidas</h2>
          </div>
          <div className="p-6">
            <div className="grid grid-cols-2 gap-4">
              {user?.e === "admin" && (
                <>
                  <button className="p-4 text-left rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <Users className="w-6 h-6 text-blue-500 mb-2" />
                    <p className="text-sm font-medium text-gray-900 dark:text-white">Gestionar Usuarios</p>
                  </button>
                  <button className="p-4 text-left rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <BookOpen className="w-6 h-6 text-green-500 mb-2" />
                    <p className="text-sm font-medium text-gray-900 dark:text-white">Crear Curso</p>
                  </button>
                </>
              )}

              {(user?.e === "admin" || user?.e === "docente") && (
                <>
                  <button className="p-4 text-left rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <CheckCircle className="w-6 h-6 text-purple-500 mb-2" />
                    <p className="text-sm font-medium text-gray-900 dark:text-white">Tomar Asistencia</p>
                  </button>
                  <button className="p-4 text-left rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <Bell className="w-6 h-6 text-orange-500 mb-2" />
                    <p className="text-sm font-medium text-gray-900 dark:text-white">Crear Observador</p>
                  </button>
                </>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Calendar Widget */}
      <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div className="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Próximos Eventos</h2>
        </div>
        <div className="p-6">
          <div className="space-y-4">
            <div className="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
              <div className="bg-blue-500 text-white p-2 rounded-lg mr-3">
                <Calendar className="w-4 h-4" />
              </div>
              <div className="flex-1">
                <p className="text-sm font-medium text-gray-900 dark:text-white">Reunión de Docentes</p>
                <p className="text-xs text-gray-600 dark:text-gray-400">Viernes 26 de Enero - 3:00 PM</p>
              </div>
            </div>

            <div className="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
              <div className="bg-green-500 text-white p-2 rounded-lg mr-3">
                <BookOpen className="w-4 h-4" />
              </div>
              <div className="flex-1">
                <p className="text-sm font-medium text-gray-900 dark:text-white">Entrega de Notas</p>
                <p className="text-xs text-gray-600 dark:text-gray-400">Lunes 29 de Enero</p>
              </div>
            </div>

            <div className="flex items-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
              <div className="bg-purple-500 text-white p-2 rounded-lg mr-3">
                <Users className="w-4 h-4" />
              </div>
              <div className="flex-1">
                <p className="text-sm font-medium text-gray-900 dark:text-white">Día de la Familia</p>
                <p className="text-xs text-gray-600 dark:text-gray-400">Sábado 3 de Febrero</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default HomePage
