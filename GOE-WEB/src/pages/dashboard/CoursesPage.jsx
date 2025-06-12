"use client"

import { useState, useEffect } from "react"
import { Search, Plus, Filter, Download, Eye, Edit, Trash2, Users, BookOpen, User } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"
import LoadingSpinner from "../../components/ui/LoadingSpinner"
import { useAuth } from "../../context/AuthContext"
import toast from "react-hot-toast"
import { apiService } from "../../services/apiService"

const CoursesPage = () => {
  const { user } = useAuth()
  const [courses, setCourses] = useState([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [selectedGrade, setSelectedGrade] = useState("")
  const [showModal, setShowModal] = useState(false)
  const [selectedCourse, setSelectedCourse] = useState(null)
  const [modalMode, setModalMode] = useState("view")

  useEffect(() => {
    const fetchCourses = async () => {
      setLoading(true)
      try {
        const response = await apiService.getCourses()
        console.log("Cursos:", response.data.CursosData)
        setCourses(response.data.CursosData[0])
      } catch (error) {
        toast.error("Error al cargar cursos")
      } finally {
        setLoading(false)
      }
    }

    fetchCourses()
  }, [])

  const filteredCourses = courses.filter((course) => {
    const searchLower = searchTerm.toLowerCase().trim();

    const matchesSearch =
      (course.grado?.toString() || "").includes(searchTerm) ||
      (course.salon?.toLowerCase() || "").includes(searchLower);
      /* course.nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
      course.director.toLowerCase().includes(searchTerm.toLowerCase()) */
    const matchesGrade = !selectedGrade || course.grado === selectedGrade

    return matchesSearch && matchesGrade
  })

  const handleViewCourse = (course) => {
    setSelectedCourse(course)
    setModalMode("view")
    setShowModal(true)
  }

  const handleEditCourse = (course) => {
    setSelectedCourse(course)
    setModalMode("edit")
    setShowModal(true)
  }

  const handleCreateCourse = () => {
    setSelectedCourse(null)
    setModalMode("create")
    setShowModal(true)
  }

  const handleDeleteCourse = async (courseId) => {
    if (window.confirm("¿Estás seguro de que deseas eliminar este curso?")) {
      try {
        setCourses(courses.filter((c) => c.grado !== courseId))
        toast.success("Curso eliminado exitosamente")
      } catch (error) {
        toast.error("Error al eliminar curso")
      }
    }
  }

  const exportToExcel = () => {
    toast.success("Exportando cursos a Excel...")
  }

  const getOccupancyPercentage = (students, capacity) => {
    return ((students / capacity) * 100).toFixed(1)
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <LoadingSpinner />
      </div>
    )
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Cursos</h1>
          <p className="text-gray-600 dark:text-gray-400">Gestiona los cursos y grados</p>
        </div>
        <div className="flex gap-2 mt-4 sm:mt-0">
          <Button onClick={exportToExcel} variant="outline">
            <Download className="w-4 h-4 mr-2" />
            Exportar
          </Button>
          {user?.id_rol === 104 && (
            <Button onClick={handleCreateCourse}>
              <Plus className="w-4 h-4 mr-2" />
              Nuevo Curso
            </Button>
          )}
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-blue-500 p-3 rounded-lg">
              <BookOpen className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Cursos</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">{courses.length}</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-green-500 p-3 rounded-lg">
              <Users className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Estudiantes</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {courses.reduce((sum, course) => sum + course.total_estudiantes, 0)}
              </p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-purple-500 p-3 rounded-lg">
              <User className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Cursos Activos</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {courses.filter((c) => c.estado === "activo" ? "" : 0) .length}
              </p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-orange-500 p-3 rounded-lg">
              <BookOpen className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Ocupación Promedio</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {courses.length > 0
                  ? Math.round(
                    courses.reduce((sum, course) => sum + (course.total_estudiantes / 30) * 100, 0) /
                    courses.length,
                  )
                  : 0}
                %
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
            <Input
              placeholder="Buscar por nombre o director..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10"
            />
          </div>
          <select
            value={selectedGrade}
            onChange={(e) => setSelectedGrade(e.target.value)}
            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="">Todos los grados</option>
            {courses.map((course) =>(
              <option value={course.grado}>{course.grado}</option>
            ))}
          </select>
          <Button variant="outline">
            <Filter className="w-4 h-4 mr-2" />
            Filtros
          </Button>
        </div>
      </div>

      {/* Courses Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredCourses.map((course) => (
          <div
            key={course.grado}
            className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow"
          >
            <div className="flex items-start justify-between mb-4">
              <div>
                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-1">{course.nombre ? "" : 0}</h3>
                <p className="text-sm text-gray-600 dark:text-gray-400">
                  Grado {course.grado}
                </p>
              </div>
              <span
                className={`px-2 py-1 text-xs font-medium rounded-full ${course.estado === "activo"
                    ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                    : "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
                  }`}
              >
                {course.estado ? "" : 0}
              </span>
            </div>

            <div className="space-y-3 mb-4">
              <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <User className="w-4 h-4 mr-2" />
                {course.nombre ? course.nombre : "indefinido"}
              </div>

              <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <Users className="w-4 h-4 mr-2" />
                {course.total_estudiantes ? "" : 10}/{30} estudiantes
              </div>

              <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div
                  className="bg-blue-500 h-2 rounded-full"
                  style={{
                    width: `${getOccupancyPercentage(course.total_estudiantes ? "" : 10, 30)}%`,
                  }}
                ></div>
              </div>
              <p className="text-xs text-gray-500 dark:text-gray-400">
                {getOccupancyPercentage(course.total_estudiantes ? "" : 10, 30)}% ocupación
              </p>

              <div className="text-sm text-gray-600 dark:text-gray-400">
                <p>
                  <strong>Aula:</strong> {course.salon}
                </p>
                <p>
                  <strong>Horario:</strong> {course.horario ? "" : 0}
                </p>
              </div>

              <div className="text-sm text-gray-600 dark:text-gray-400">
                <p>
                  <strong>Materias:</strong>
                </p>
                <div className="flex flex-wrap gap-1 mt-1">
                  {/* {course.materias.slice(0, 3).map((materia, index) => (
                    <span key={index} className="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-xs rounded">
                      {materia}
                    </span>
                  ))}
                  {course.materias.length > 3 && (
                    <span className="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-xs rounded">
                      +{course.materias.length - 3} más
                    </span>
                  )} */}
                </div>
              </div>
            </div>

            <div className="flex gap-2">
              <Button size="sm" variant="outline" onClick={() => handleViewCourse(course)} className="flex-1">
                <Eye className="w-4 h-4 mr-1" />
                Ver
              </Button>
              {user?.id_rol === 104 && (
                <>
                  <Button size="sm" variant="outline" onClick={() => handleEditCourse(course)}>
                    <Edit className="w-4 h-4" />
                  </Button>
                  <Button
                    size="sm"
                    variant="outline"
                    onClick={() => handleDeleteCourse(course.grado)}
                    className="text-red-600 hover:text-red-700"
                  >
                    <Trash2 className="w-4 h-4" />
                  </Button>
                </>
              )}
            </div>
          </div>
        ))}
      </div>

      {filteredCourses.length === 0 && (
        <div className="text-center py-12">
          <BookOpen className="w-12 h-12 text-gray-400 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">No se encontraron cursos</h3>
          <p className="text-gray-600 dark:text-gray-400">Intenta ajustar los filtros de búsqueda</p>
        </div>
      )}

      {/* Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div className="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div className="p-6">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                  {modalMode === "create"
                    ? "Nuevo Curso"
                    : modalMode === "edit"
                      ? "Editar Curso"
                      : "Información del Curso"}
                </h2>
                <button
                  onClick={() => setShowModal(false)}
                  className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                >
                  ×
                </button>
              </div>

              {selectedCourse && modalMode === "view" && (
                <div className="space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre del Curso
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedCourse.grado}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Grado y Curso
                      </label>
                      <p className="text-gray-900 dark:text-white">
                        {selectedCourse.grado}
                      </p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Director de Curso
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedCourse.directo ? "" : 0}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Estudiantes
                      </label>
                      <p className="text-gray-900 dark:text-white">
                        {selectedCourse.total_estudiantes ? "" : 10} / {30}
                      </p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Aula</label>
                      <p className="text-gray-900 dark:text-white">{selectedCourse.salon}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Horario</label>
                      <p className="text-gray-900 dark:text-white">{selectedCourse.horario ? "" : 0}</p>
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Materias</label>
                    <div className="flex flex-wrap gap-2">
                        {/* {selectedCourse.materias.map((materia, index) => (
                          <span
                            key={index}
                            className="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full"
                          >
                            {materia}
                          </span>
                        ))} */}
                    </div>
                  </div>
                </div>
              )}

              <div className="flex justify-end gap-2 mt-6">
                <Button variant="outline" onClick={() => setShowModal(false)}>
                  {modalMode === "view" ? "Cerrar" : "Cancelar"}
                </Button>
                {modalMode !== "view" && (
                  <Button onClick={() => setShowModal(false)}>{modalMode === "create" ? "Crear" : "Guardar"}</Button>
                )}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

export default CoursesPage
