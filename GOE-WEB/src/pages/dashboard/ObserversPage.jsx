"use client"

import { useState, useEffect } from "react"
import { Search, Plus, Filter, Download, Eye, Edit, Trash2, AlertTriangle, CheckCircle, Info } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"
import LoadingSpinner from "../../components/ui/LoadingSpinner"
import { useAuth } from "../../context/AuthContext"
import { format } from 'date-fns';
import { es } from 'date-fns/locale';
import toast from "react-hot-toast"
import { apiService } from "../../services/apiService"
import { use } from "react"

const ObserversPage = () => {
  const { user } = useAuth()
  const [observers, setObservers] = useState([])
  const [cursos, setCursos] = useState([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [selectedType, setSelectedType] = useState("")
  const [selectedCourse, setSelectedCourse] = useState("")
  const [showModal, setShowModal] = useState(false)
  const [selectedObserver, setSelectedObserver] = useState(null)
  const [modalMode, setModalMode] = useState("view")

  // Añade estos estados al componente
  const [formData, setFormData] = useState({
    documento: '',
    grado: '',
    falta: '',
    descripcion_falta: '',
    compromiso: '',
    seguimiento: '',
    fecha: new Date().toISOString()
  });

  const handleCreateObserver = () => {
    setFormData({
      idobservador: '',
      documento: '',
      grado: '',
      falta: '',
      descripcion_falta: '',
      compromiso: '',
      seguimiento: '',
      fecha: new Date().toISOString()
    });
    setModalMode("create");
    setShowModal(true);
  }

  const handleEditObserver = (observer) => {
    setFormData({
      documento: observer.documento,
      grado: observer.grado,
      falta: observer.falta,
      descripcion_falta: observer.descripcion_falta,
      compromiso: observer.compromiso,
      seguimiento: observer.seguimiento,
      fecha: observer.fecha
    });
    setSelectedObserver(observer);
    setModalMode("edit");
    setShowModal(true);
  }

  useEffect(() => {
    const fetchObservers = async () => {
      setLoading(true)
      try {
        if (user?.id_rol == 104 || user?.id_rol == 102) {
          var response = await apiService.getObservers()
          var result = (response.data.observerData[0])
        }else{
          var response = await apiService.getObserversStudent(user?.documento)
          var result = (response.data.observerData)
        }

        if (response.success) {
          console.log("Observadores obtenidos:", response.data.observerData[0])
          setObservers(result)
        }
      } catch (error) {
        console.error("Error al obtener observadores:", error)
        toast.error("Error al cargar observadores")
      } finally {
        setLoading(false)
      }
    }

    fetchObservers()
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

  const filteredObservers = observers.filter((observer) => {
    if (!observer) return false;

    // Normalizar el término de búsqueda
    const searchLower = searchTerm.toLowerCase().trim();

    // Si no hay término de búsqueda, solo aplicar los otros filtros
    if (!searchLower) {
      const matchesType = !selectedType || observer.falta === selectedType;
      const matchesCourse = !selectedCourse || `${observer.grado}` === selectedCourse;
      return matchesType && matchesCourse;
    }

    // Buscar en todos los campos relevantes
    const matchesSearch =
      (observer.nombre1?.toLowerCase() || "").includes(searchLower) ||
      (observer.nombre2?.toLowerCase() || "").includes(searchLower) ||
      (observer.apellido1?.toLowerCase() || "").includes(searchLower) ||
      (observer.apellido2?.toLowerCase() || "").includes(searchLower) ||
      (observer.documento?.toString() || "").includes(searchTerm) || // Búsqueda exacta para documento
      (observer.descripcion_falta?.toLowerCase() || "").includes(searchLower);

    const matchesType = !selectedType || observer.falta === selectedType;
    const matchesCourse = !selectedCourse || `${observer.grado}` === selectedCourse;

    return matchesSearch && matchesType && matchesCourse;
  });

  const handleSubmit = async () => {
    try {
      setLoading(true);

      if (modalMode === "create") {
        const response = await apiService.createObserver(formData);
        if (response.success) {
          toast.success("Observador creado exitosamente");
          setObservers([...observers, response.data]);
        }
      } else if (modalMode === "edit") {
        const response = await apiService.updateObserver(selectedObserver.idobservador, formData);
        if (response.success) {
          toast.success("Observador actualizado exitosamente");
          setObservers(observers.map(o =>
            o.idobservador === selectedObserver.idobservador ? response.data : o
          ));
        }
      }

      setShowModal(false);
    } catch (error) {
      console.error("Error al guardar observador:", error);
      toast.error("Error al guardar observador");
    } finally {
      setLoading(false);
    }
  }

  const handleViewObserver = (observer) => {
    setSelectedObserver(observer)
    setModalMode("view")
    setShowModal(true)
  }

  /*   const handleEditObserver = (observer) => {
      setSelectedObserver(observer)
      setModalMode("edit")
      setShowModal(true)
    }
  
    const handleCreateObserver = () => {
      setSelectedObserver(null)
      setModalMode("create")
      setShowModal(true)
    } */

  const handleDeleteObserver = async (observerId) => {
    if (window.confirm("¿Estás seguro de que deseas eliminar este observador?")) {
      try {

        const response = await apiService.deleteObserver(observerId)
        console.log("delete:", response)
        toast.success("Observador eliminado exitosamente")
      } catch (error) {
        toast.error("Error al eliminar observador")
      }
    }
  }

  const getTypeIcon = (type) => {
    switch (type) {
      case "Leve":
        return <CheckCircle className="w-5 h-5 text-green-500" />
      case "Grave":
        return <AlertTriangle className="w-5 h-5 text-red-500" />
      case "Regular":
        return <Info className="w-5 h-5 text-blue-500" />
      default:
        return <Info className="w-5 h-5 text-gray-500" />
    }
  }

  const getTypeColor = (type) => {
    switch (type) {
      case "Leve":
        return "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
      case "Grave":
        return "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
      case "Regular":
        return "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
      default:
        return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
    }
  }

  const exportToExcel = () => {
    toast.success("Exportando observadores a Excel...")
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
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Observadores</h1>
          <p className="text-gray-600 dark:text-gray-400">Gestiona los observadores estudiantiles</p>
        </div>
        <div className="flex gap-2 mt-4 sm:mt-0">
          <Button onClick={exportToExcel} variant="outline">
            <Download className="w-4 h-4 mr-2" />
            Exportar
          </Button>
          {(user?.id_rol == "104" || user?.id_rol == "102") && (
            <Button onClick={handleCreateObserver}>
              <Plus className="w-4 h-4 mr-2" />
              Nuevo Observador
            </Button>
          )}
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-green-500 p-3 rounded-lg">
              <CheckCircle className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Leves</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {observers.filter((o) => o.falta === "Leve").length}
              </p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-red-500 p-3 rounded-lg">
              <AlertTriangle className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Graves</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {observers.filter((o) => o.falta === "Grave").length}
              </p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-blue-500 p-3 rounded-lg">
              <Info className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Regulares</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {observers.filter((o) => o.falta == "Regular").length}
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
              placeholder="Buscar por estudiante o descripción..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10"
            />
          </div>
          <select
            value={selectedType}
            onChange={(e) => setSelectedType(e.target.value)}
            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="">Todos los tipos</option>
            <option value="Leve">Leve</option>
            <option value="Regular">Regular</option>
            <option value="Grave">Grave</option>
          </select>
          {(use?.id_rol == 104 || user?.id_rol == 102) && (
            <select
              value={selectedCourse}
              onChange={(e) => setSelectedCourse(e.target.value)}
              className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Todos los cursos</option>
              {cursos.map((curso) => (
                <option value={curso.grado}>{curso.grado}</option>
              ))}
            </select>
          )}
          <Button variant="outline">
            <Filter className="w-4 h-4 mr-2" />
            Filtros
          </Button>
        </div>
      </div>

      {/* Observers List */}
      <div className="space-y-4">
        {filteredObservers.map((observer) => (
          <div
            key={observer.idobservador}
            className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow"
          >
            <div className="flex items-start justify-between">
              <div className="flex-1">
                <div className="flex items-center mb-2">
                  {getTypeIcon(observer.falta)}
                  <span className={`ml-2 px-2 py-1 text-xs font-medium rounded-full ${getTypeColor(observer.falta)}`}>
                    {observer.falta.charAt(0).toUpperCase() + observer.falta.slice(1)}
                  </span>
                  <span className="ml-4 text-sm text-gray-600 dark:text-gray-400">
                    {format(new Date(observer.fecha), 'PPPP', { locale: es })}
                  </span>
                </div>

                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-1">{observer.nombre1} {observer.nombre2} {observer.apellido1} {observer.apellido2}</h3>

                <div className="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                  <span>
                    {observer.grado}
                  </span>
                  {/* <span className="mx-2">•</span>
                  <span>{observer.materia}</span> */}
                  <span className="mx-2">•</span>
                  <span>{observer.seguimiento}</span>
                </div>

                <p className="text-gray-700 dark:text-gray-300 leading-relaxed">{observer.descripcion_falta}</p>
              </div>

              <div className="flex gap-2 ml-4">
                <Button size="sm" variant="outline" onClick={() => handleViewObserver(observer)}>
                  <Eye className="w-4 h-4" />
                </Button>
                {(user?.id_rol == "104" || user?.id_rol == "102") && (
                  <>
                    <Button size="sm" variant="outline" onClick={() => handleEditObserver(observer)}>
                      <Edit className="w-4 h-4" />
                    </Button>
                    <Button
                      size="sm"
                      variant="outline"
                      onClick={() => handleDeleteObserver(observer.idobservador)}
                      className="text-red-600 hover:text-red-700"
                    >
                      <Trash2 className="w-4 h-4" />
                    </Button>
                  </>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>

      {filteredObservers.length === 0 && (
        <div className="text-center py-12">
          <Info className="w-12 h-12 text-gray-400 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">No se encontraron observadores</h3>
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
                    ? "Nuevo Observador"
                    : modalMode === "edit"
                      ? "Editar Observador"
                      : "Detalle del Observador"}
                </h2>
                <button
                  onClick={() => setShowModal(false)}
                  className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                >
                  ×
                </button>
              </div>

              {modalMode === "view" ? (
                // Vista de detalle (existente)
                <div className="space-y-4">
                  <div className="flex items-center">
                    {getTypeIcon(selectedObserver.falta)}
                    <span
                      className={`ml-2 px-3 py-1 text-sm font-medium rounded-full ${getTypeColor(selectedObserver.falta)}`}
                    >
                      {selectedObserver.falta.charAt(0).toUpperCase() + selectedObserver.falta.slice(1)}
                    </span>
                  </div>

                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Estudiante
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedObserver.nombre1} {selectedObserver.nombre2} {selectedObserver.apellido1} {selectedObserver.apellido2}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Documento
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedObserver.documento}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso</label>
                      <p className="text-gray-900 dark:text-white">
                        {selectedObserver.grado}
                      </p>
                    </div>
                    {/* <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Materia</label>
                      <p className="text-gray-900 dark:text-white">{selectedObserver.materia}</p>
                    </div> */}
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Docente</label>
                      <p className="text-gray-900 dark:text-white">{selectedObserver.seguimiento}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha y Hora
                      </label>
                      <p className="text-gray-900 dark:text-white">
                        {format(new Date(selectedObserver.fecha), 'PPPP', { locale: es })}
                      </p>
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Descripción
                    </label>
                    <p className="text-gray-900 dark:text-white leading-relaxed">{selectedObserver.descripcion_falta}</p>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Compromiso
                    </label>
                    <p className="text-gray-900 dark:text-white leading-relaxed">{selectedObserver.compromiso}</p>
                  </div>
                </div>
              ) : (
                // Formulario para crear/editar
                <form className="space-y-4">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Documento del Estudiante
                      </label>
                      {modalMode === "create"
                        ?
                        (
                          <Input
                            type="text"
                            value={formData.documento}
                            onChange={(e) => setFormData({ ...formData, documento: e.target.value })}
                            placeholder="Número de documento"
                            required
                          />
                        )
                        : modalMode === "edit"
                          ?
                          (
                            <Input
                              type="text"
                              value={formData.documento}
                              onChange={(e) => setFormData({ ...formData, documento: e.target.value })}
                              placeholder="Número de documento"
                              required
                              readOnly
                            />
                          )
                          : ""}

                    </div>

                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Curso
                      </label>
                      <select
                        value={formData.grado}
                        onChange={(e) => setFormData({ ...formData, grado: e.target.value })}
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
                        required
                      >
                        <option value="">Seleccionar curso</option>
                        {cursos.map((curso) => (
                          <option key={curso.id} value={curso.grado}>
                            {curso.grado}
                          </option>
                        ))}
                      </select>
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Tipo de Falta
                    </label>
                    <div className="grid grid-cols-3 gap-2">
                      {['Leve', 'Regular', 'Grave'].map((type) => (
                        <label key={type} className="flex items-center">
                          <input
                            type="radio"
                            name="falta"
                            value={type}
                            checked={formData.falta === type}
                            onChange={() => setFormData({ ...formData, falta: type })}
                            className="mr-2"
                            required
                          />
                          <span className="text-sm">{type}</span>
                        </label>
                      ))}
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Descripción de la Falta
                    </label>
                    <textarea
                      value={formData.descripcion_falta}
                      onChange={(e) => setFormData({ ...formData, descripcion_falta: e.target.value })}
                      className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
                      rows={3}
                      placeholder="Describe detalladamente la situación..."
                      required
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Compromiso del Estudiante
                    </label>
                    <textarea
                      value={formData.compromiso}
                      onChange={(e) => setFormData({ ...formData, compromiso: e.target.value })}
                      className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
                      rows={2}
                      placeholder="Compromisos acordados con el estudiante..."
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Docente Reportante
                    </label>
                    <Input
                      type="text"
                      value={formData.seguimiento}
                      onChange={(e) => setFormData({ ...formData, seguimiento: e.target.value })}
                      placeholder="Nombre del docente"
                      required
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                      Fecha y Hora
                    </label>
                    <Input
                      type="datetime-local"
                      value={format(new Date(formData.fecha), "yyyy-MM-dd'T'HH:mm")}
                      onChange={(e) => setFormData({ ...formData, fecha: new Date(e.target.value).toISOString() })}
                      required
                    />
                  </div>
                </form>
              )}

              <div className="flex justify-end gap-2 mt-6">
                <Button variant="outline" onClick={() => setShowModal(false)}>
                  {modalMode === "view" ? "Cerrar" : "Cancelar"}
                </Button>
                {modalMode !== "view" && (
                  <Button onClick={handleSubmit}>
                    {modalMode === "create" ? "Crear" : "Guardar"}
                  </Button>
                )}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

export default ObserversPage
