"use client"

import { useState, useEffect } from "react"
import { Search, Plus, Filter, Download, Eye, Edit, Trash2, BookOpen, Clock, User } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"
import LoadingSpinner from "../../components/ui/LoadingSpinner"
import { useAuth } from "../../context/AuthContext"
import toast from "react-hot-toast"

const SubjectsPage = () => {
  const { user } = useAuth()
  const [subjects, setSubjects] = useState([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [selectedGrade, setSelectedGrade] = useState("")
  const [selectedArea, setSelectedArea] = useState("")
  const [showModal, setShowModal] = useState(false)
  const [selectedSubject, setSelectedSubject] = useState(null)
  const [modalMode, setModalMode] = useState("view")

  useEffect(() => {
    const fetchSubjects = async () => {
      setLoading(true)
      try {
        await new Promise((resolve) => setTimeout(resolve, 1000))
        const mockSubjects = [
          {
            id: 1,
            nombre: "Matemáticas",
            codigo: "MAT001",
            area: "Ciencias Exactas",
            grados: ["9", "10", "11"],
            horas_semanales: 5,
            docente: "Prof. García Martínez",
            descripcion: "Álgebra, geometría, trigonometría y cálculo básico",
            estado: "activo",
          },
          {
            id: 2,
            nombre: "Física",
            codigo: "FIS001",
            area: "Ciencias Naturales",
            grados: ["10", "11"],
            horas_semanales: 4,
            docente: "Prof. Rodríguez López",
            descripcion: "Mecánica, termodinámica, electricidad y magnetismo",
            estado: "activo",
          },
          {
            id: 3,
            nombre: "Química",
            codigo: "QUI001",
            area: "Ciencias Naturales",
            grados: ["10", "11"],
            horas_semanales: 4,
            docente: "Prof. Martínez Silva",
            descripcion: "Química general, orgánica e inorgánica",
            estado: "activo",
          },
          {
            id: 4,
            nombre: "Español",
            codigo: "ESP001",
            area: "Humanidades",
            grados: ["6", "7", "8", "9", "10", "11"],
            horas_semanales: 4,
            docente: "Prof. López González",
            descripcion: "Literatura, gramática, redacción y comprensión lectora",
            estado: "activo",
          },
          {
            id: 5,
            nombre: "Inglés",
            codigo: "ING001",
            area: "Idiomas",
            grados: ["6", "7", "8", "9", "10", "11"],
            horas_semanales: 3,
            docente: "Prof. Smith Johnson",
            descripcion: "Gramática, vocabulario, conversación y comprensión",
            estado: "activo",
          },
          {
            id: 6,
            nombre: "Historia",
            codigo: "HIS001",
            area: "Ciencias Sociales",
            grados: ["6", "7", "8", "9"],
            horas_semanales: 3,
            docente: "Prof. Pérez Ramírez",
            descripcion: "Historia universal, de Colombia y contemporánea",
            estado: "inactivo",
          },
        ]
        setSubjects(mockSubjects)
      } catch (error) {
        toast.error("Error al cargar materias")
      } finally {
        setLoading(false)
      }
    }

    fetchSubjects()
  }, [])

  const filteredSubjects = subjects.filter((subject) => {
    const matchesSearch =
      subject.nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
      subject.codigo.toLowerCase().includes(searchTerm.toLowerCase()) ||
      subject.docente.toLowerCase().includes(searchTerm.toLowerCase())
    const matchesGrade = !selectedGrade || subject.grados.includes(selectedGrade)
    const matchesArea = !selectedArea || subject.area === selectedArea

    return matchesSearch && matchesGrade && matchesArea
  })

  const handleViewSubject = (subject) => {
    setSelectedSubject(subject)
    setModalMode("view")
    setShowModal(true)
  }

  const handleEditSubject = (subject) => {
    setSelectedSubject(subject)
    setModalMode("edit")
    setShowModal(true)
  }

  const handleCreateSubject = () => {
    setSelectedSubject(null)
    setModalMode("create")
    setShowModal(true)
  }

  const handleDeleteSubject = async (subjectId) => {
    if (window.confirm("¿Estás seguro de que deseas eliminar esta materia?")) {
      try {
        setSubjects(subjects.filter((s) => s.id !== subjectId))
        toast.success("Materia eliminada exitosamente")
      } catch (error) {
        toast.error("Error al eliminar materia")
      }
    }
  }

  const exportToExcel = () => {
    toast.success("Exportando materias a Excel...")
  }

  const getAreaColor = (area) => {
    const colors = {
      "Ciencias Exactas": "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200",
      "Ciencias Naturales": "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
      Humanidades: "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200",
      Idiomas: "bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200",
      "Ciencias Sociales": "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
      Artes: "bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200",
    }
    return colors[area] || "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
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
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Materias</h1>
          <p className="text-gray-600 dark:text-gray-400">Gestiona las materias del plan de estudios</p>
        </div>
        <div className="flex gap-2 mt-4 sm:mt-0">
          <Button onClick={exportToExcel} variant="outline">
            <Download className="w-4 h-4 mr-2" />
            Exportar
          </Button>
          {user?.id_rol == "104" && (
            <Button onClick={handleCreateSubject}>
              <Plus className="w-4 h-4 mr-2" />
              Nueva Materia
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
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Materias</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">{subjects.length}</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-green-500 p-3 rounded-lg">
              <BookOpen className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Materias Activas</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {subjects.filter((s) => s.estado === "activo").length}
              </p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-purple-500 p-3 rounded-lg">
              <Clock className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Horas Semanales</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {subjects.reduce((sum, subject) => sum + subject.horas_semanales, 0)}
              </p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-orange-500 p-3 rounded-lg">
              <User className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Áreas</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {new Set(subjects.map((s) => s.area)).size}
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
            <Input
              placeholder="Buscar por nombre, código o docente..."
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
            <option value="6">Grado 6</option>
            <option value="7">Grado 7</option>
            <option value="8">Grado 8</option>
            <option value="9">Grado 9</option>
            <option value="10">Grado 10</option>
            <option value="11">Grado 11</option>
          </select>
          <select
            value={selectedArea}
            onChange={(e) => setSelectedArea(e.target.value)}
            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="">Todas las áreas</option>
            <option value="Ciencias Exactas">Ciencias Exactas</option>
            <option value="Ciencias Naturales">Ciencias Naturales</option>
            <option value="Humanidades">Humanidades</option>
            <option value="Idiomas">Idiomas</option>
            <option value="Ciencias Sociales">Ciencias Sociales</option>
            <option value="Artes">Artes</option>
          </select>
          <Button variant="outline">
            <Filter className="w-4 h-4 mr-2" />
            Filtros
          </Button>
        </div>
      </div>

      {/* Subjects Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredSubjects.map((subject) => (
          <div
            key={subject.id}
            className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow"
          >
            <div className="flex items-start justify-between mb-4">
              <div className="flex-1">
                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-1">{subject.nombre}</h3>
                <p className="text-sm text-gray-600 dark:text-gray-400 mb-2">{subject.codigo}</p>
                <span className={`px-2 py-1 text-xs font-medium rounded-full ${getAreaColor(subject.area)}`}>
                  {subject.area}
                </span>
              </div>
              <span
                className={`px-2 py-1 text-xs font-medium rounded-full ${
                  subject.estado === "activo"
                    ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                    : "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
                }`}
              >
                {subject.estado}
              </span>
            </div>

            <div className="space-y-3 mb-4">
              <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <User className="w-4 h-4 mr-2" />
                {subject.docente}
              </div>

              <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <Clock className="w-4 h-4 mr-2" />
                {subject.horas_semanales} horas semanales
              </div>

              <div className="text-sm text-gray-600 dark:text-gray-400">
                <p>
                  <strong>Grados:</strong>
                </p>
                <div className="flex flex-wrap gap-1 mt-1">
                  {subject.grados.map((grado, index) => (
                    <span key={index} className="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-xs rounded">
                      {grado}°
                    </span>
                  ))}
                </div>
              </div>

              <div className="text-sm text-gray-600 dark:text-gray-400">
                <p className="line-clamp-2">{subject.descripcion}</p>
              </div>
            </div>

            <div className="flex gap-2">
              <Button size="sm" variant="outline" onClick={() => handleViewSubject(subject)} className="flex-1">
                <Eye className="w-4 h-4 mr-1" />
                Ver
              </Button>
              {user?.id_rol == "104" && (
                <>
                  <Button size="sm" variant="outline" onClick={() => handleEditSubject(subject)}>
                    <Edit className="w-4 h-4" />
                  </Button>
                  <Button
                    size="sm"
                    variant="outline"
                    onClick={() => handleDeleteSubject(subject.id)}
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

      {filteredSubjects.length === 0 && (
        <div className="text-center py-12">
          <BookOpen className="w-12 h-12 text-gray-400 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">No se encontraron materias</h3>
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
                    ? "Nueva Materia"
                    : modalMode === "edit"
                      ? "Editar Materia"
                      : "Información de la Materia"}
                </h2>
                <button
                  onClick={() => setShowModal(false)}
                  className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                >
                  ×
                </button>
              </div>

              {selectedSubject && modalMode === "view" && (
                <div className="space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre de la Materia
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedSubject.nombre}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código</label>
                      <p className="text-gray-900 dark:text-white">{selectedSubject.codigo}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Área</label>
                      <span
                        className={`px-3 py-1 text-sm font-medium rounded-full ${getAreaColor(selectedSubject.area)}`}
                      >
                        {selectedSubject.area}
                      </span>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Horas Semanales
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedSubject.horas_semanales}</p>
                    </div>
                    <div className="md:col-span-2">
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Docente</label>
                      <p className="text-gray-900 dark:text-white">{selectedSubject.docente}</p>
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Grados</label>
                    <div className="flex flex-wrap gap-2">
                      {selectedSubject.grados.map((grado, index) => (
                        <span
                          key={index}
                          className="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full"
                        >
                          Grado {grado}°
                        </span>
                      ))}
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Descripción
                    </label>
                    <p className="text-gray-900 dark:text-white leading-relaxed">{selectedSubject.descripcion}</p>
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

export default SubjectsPage
