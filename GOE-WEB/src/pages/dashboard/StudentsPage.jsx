"use client"

import { useState, useEffect } from "react"
import { Search, Plus, Filter, Download, Eye, Edit, Trash2, User, Phone, Mail } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"
import LoadingSpinner from "../../components/ui/LoadingSpinner"
import { useAuth } from "../../context/AuthContext"
import toast from "react-hot-toast"
import { apiService } from "../../services/apiService"

const StudentsPage = () => {
  const { user } = useAuth()
  const [students, setStudents] = useState([])
  const [cursos, setCursos] = useState([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [selectedGrade, setSelectedGrade] = useState("")
  const [selectedCourse, setSelectedCourse] = useState("")
  const [showModal, setShowModal] = useState(false)
  const [selectedStudent, setSelectedStudent] = useState(null)
  const [modalMode, setModalMode] = useState("view") // view, edit, create

  // Mock data - replace with API call
  useEffect(() => {
    const fetchStudents = async () => {
      setLoading(true)
      try {
        const response = await apiService.getStudents()
        setStudents(response.data.studentsData[0])
      } catch (error) {
        toast.error("Error al cargar estudiantes")
      } finally {
        setLoading(false)
      }
    }

    fetchStudents()
  }, [])

  useEffect(() => {
    const fetchCursos = async () => {
      setLoading(true)
      try {
        const response = await apiService.getCourses()

        if (response.success) {
          console.log("Cursos:", response.data.CursosData[0])
          setCursos(response.data.CursosData[0])
        }
      } catch (error) {
        console.error("Error al obtener los cursos:", error)
      } finally {
        setLoading(false)
      }
    }

    fetchCursos()
  }, [])

  const filteredStudents = students.filter((student) => {
    const searchLower = searchTerm.toLowerCase().trim();

    const matchesSearch =
      (student.nombre1?.toLowerCase() || "").includes(searchLower) ||
      (student.nombre2?.toLowerCase() || "").includes(searchLower) ||
      (student.apellido1?.toLowerCase() || "").includes(searchLower) ||
      (student.apellido2?.toLowerCase() || "").includes(searchLower) ||
      (student.documento?.toString() || "").includes(searchTerm); // Búsqueda exacta para documento
    const matchesCourse = !selectedCourse || student.grado == selectedCourse

    return matchesSearch && matchesCourse
  })

  const handleViewStudent = (student) => {
    setSelectedStudent(student)
    setModalMode("view")
    setShowModal(true)
  }

  const handleEditStudent = (student) => {
    setSelectedStudent(student)
    setModalMode("edit")
    setShowModal(true)
  }

  const handleCreateStudent = () => {
    setSelectedStudent(null)
    setModalMode("create")
    setShowModal(true)
  }

  const handleDeleteStudent = async (studentId) => {
    if (window.confirm("¿Estás seguro de que deseas eliminar este estudiante?")) {
      try {
        // API call to delete student
        setStudents(students.filter((s) => s.documento !== studentId))
        toast.success("Estudiante eliminado exitosamente")
      } catch (error) {
        toast.error("Error al eliminar estudiante")
      }
    }
  }

  const exportToExcel = () => {
    toast.success("Exportando a Excel...")
    // Implement Excel export logic
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
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Estudiantes</h1>
          <p className="text-gray-600 dark:text-gray-400">Gestiona la información de los estudiantes</p>
        </div>
        <div className="flex gap-2 mt-4 sm:mt-0">
          <Button onClick={exportToExcel} variant="outline">
            <Download className="w-4 h-4 mr-2" />
            Exportar
          </Button>
          {(user?.id_rol == "104") && (
            <Button onClick={handleCreateStudent}>
              <Plus className="w-4 h-4 mr-2" />
              Nuevo Estudiante
            </Button>
          )}
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
            <Input
              placeholder="Buscar por nombre o documento..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10"
            />
          </div>
          <select
            value={selectedCourse}
            onChange={(e) => setSelectedCourse(e.target.value)}
            className="px-5 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="">Todos los cursos</option>
            {cursos.map((curso) => (
              <option value={curso.grado}>{curso.grado}</option>  
            ))}
          </select>
          <Button variant="outline">
            <Filter className="w-4 h-4 mr-2" />
            Filtros
          </Button>
        </div>
      </div>

      {/* Students Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredStudents.map((student) => (
          <div
            key={student.documento}
            className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow"
          >
            <div className="flex items-start justify-between mb-4">
              <div className="flex items-center">
                <img
                  src={`/IMG/${student.foto || "user.png"}`} 
                  alt={`${student.nombre1} ${student.apellido1}`}
                  className="w-12 h-12 rounded-full object-cover mr-3"
                />
                <div>
                  <h3 className="font-semibold text-gray-900 dark:text-white">
                    {student.nombre1} {student.nombre2} {student.apellido1} {student.apellido2}
                  </h3>
                  <p className="text-sm text-gray-600 dark:text-gray-400">
                    {student.grado}
                  </p>
                </div>
              </div>
              <span
                className={`px-2 py-1 text-xs font-medium rounded-full ${student.activo === 1
                    ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                    : "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
                  }`}
              >
                {student.activo === 1 ? "Activo" : "Inactivo"}
              </span>
            </div>

            <div className="space-y-2 mb-4">
              <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <User className="w-4 h-4 mr-2" />
                {student.documento}
              </div>
              <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <Mail className="w-4 h-4 mr-2" />
                {student.email}
              </div>
              <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <Phone className="w-4 h-4 mr-2" />
                {student.telefono}
              </div>
            </div>

            <div className="flex gap-2">
              <Button size="sm" variant="outline" onClick={() => handleViewStudent(student)} className="flex-1">
                <Eye className="w-4 h-4 mr-1" />
                Ver
              </Button>
              {(user?.id_rol == "104") && (
                <>
                  <Button size="sm" variant="outline" onClick={() => handleEditStudent(student)}>
                    <Edit className="w-4 h-4" />
                  </Button>
                  <Button
                    size="sm"
                    variant="outline"
                    onClick={() => handleDeleteStudent(student.documento)}
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

      {filteredStudents.length === 0 && (
        <div className="text-center py-12">
          <User className="w-12 h-12 text-gray-400 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">No se encontraron estudiantes</h3>
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
                    ? "Nuevo Estudiante"
                    : modalMode === "edit"
                      ? "Editar Estudiante"
                      : "Información del Estudiante"}
                </h2>
                <button
                  onClick={() => setShowModal(false)}
                  className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                >
                  ×
                </button>
              </div>

              {selectedStudent && modalMode === "view" && (
                <div className="space-y-6">
                  <div className="flex items-center">
                    <img
                      src={selectedStudent.avatar || "/placeholder.svg"}
                      alt={`${selectedStudent.nombre1} ${selectedStudent.apellido1}`}
                      className="w-20 h-20 rounded-full object-cover mr-4"
                    />
                    <div>
                      <h3 className="text-xl font-semibold text-gray-900 dark:text-white">
                        {selectedStudent.nombre1} {selectedStudent.nombre2} {selectedStudent.apellido1}{" "}
                        {selectedStudent.apellido2}
                      </h3>
                      <p className="text-gray-600 dark:text-gray-400">
                        Grado {selectedStudent.grado}
                      </p>
                    </div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Documento
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedStudent.documento}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                      <p className="text-gray-900 dark:text-white">{selectedStudent.email}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Teléfono
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedStudent.telefono}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha de Nacimiento
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedStudent.fecha_naci}</p>
                    </div>
                    <div className="md:col-span-2">
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Dirección
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedStudent.direccion}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Acudiente
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedStudent.nom_acu}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Teléfono Acudiente
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedStudent.telefono_acu}</p>
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

export default StudentsPage
