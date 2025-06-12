"use client"

import { useState, useEffect } from "react"
import { Calendar, Users, Plus, Filter, Download, Eye, Edit, Clock, CheckCircle, XCircle } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"
import LoadingSpinner from "../../components/ui/LoadingSpinner"
import { useAuth } from "../../context/AuthContext"
import toast from "react-hot-toast"

const AttendancePage = () => {
  const { user } = useAuth()
  const [attendanceRecords, setAttendanceRecords] = useState([])
  const [loading, setLoading] = useState(true)
  const [selectedDate, setSelectedDate] = useState(new Date().toISOString().split("T")[0])
  const [selectedCourse, setSelectedCourse] = useState("")
  const [selectedSubject, setSelectedSubject] = useState("")
  const [showCreateModal, setShowCreateModal] = useState(false)
  const [showViewModal, setShowViewModal] = useState(false)
  const [selectedRecord, setSelectedRecord] = useState(null)

  // Mock data
  useEffect(() => {
    const fetchAttendance = async () => {
      setLoading(true)
      try {
        await new Promise((resolve) => setTimeout(resolve, 1000))
        const mockRecords = [
          {
            id: 1,
            fecha: "2024-01-15",
            grado: "11",
            curso: "A",
            materia: "Matemáticas",
            docente: "Prof. García",
            total_estudiantes: 25,
            presentes: 23,
            ausentes: 2,
            tardanzas: 1,
            justificados: 1,
            hora_registro: "08:30",
            trimestre: "I",
          },
          {
            id: 2,
            fecha: "2024-01-15",
            grado: "10",
            curso: "B",
            materia: "Ciencias",
            docente: "Prof. Rodríguez",
            total_estudiantes: 28,
            presentes: 26,
            ausentes: 2,
            tardanzas: 0,
            justificados: 2,
            hora_registro: "09:15",
            trimestre: "I",
          },
          {
            id: 3,
            fecha: "2024-01-14",
            grado: "9",
            curso: "A",
            materia: "Español",
            docente: "Prof. Martínez",
            total_estudiantes: 22,
            presentes: 20,
            ausentes: 2,
            tardanzas: 2,
            justificados: 0,
            hora_registro: "10:00",
            trimestre: "I",
          },
        ]
        setAttendanceRecords(mockRecords)
      } catch (error) {
        toast.error("Error al cargar asistencias")
      } finally {
        setLoading(false)
      }
    }

    fetchAttendance()
  }, [])

  const filteredRecords = attendanceRecords.filter((record) => {
    const matchesDate = !selectedDate || record.fecha === selectedDate
    const matchesCourse = !selectedCourse || `${record.grado}${record.curso}` === selectedCourse
    const matchesSubject = !selectedSubject || record.materia === selectedSubject

    return matchesDate && matchesCourse && matchesSubject
  })

  const handleCreateAttendance = () => {
    setShowCreateModal(true)
  }

  const handleViewRecord = (record) => {
    setSelectedRecord(record)
    setShowViewModal(true)
  }

  const exportToExcel = () => {
    toast.success("Exportando asistencias a Excel...")
  }

  const getAttendancePercentage = (presentes, total) => {
    return ((presentes / total) * 100).toFixed(1)
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
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Asistencias</h1>
          <p className="text-gray-600 dark:text-gray-400">Gestiona el registro de asistencias</p>
        </div>
        <div className="flex gap-2 mt-4 sm:mt-0">
          <Button onClick={exportToExcel} variant="outline">
            <Download className="w-4 h-4 mr-2" />
            Exportar
          </Button>
          {(user?.id_rol == "104" || user?.id_rol == "102") && (
            <Button onClick={handleCreateAttendance}>
              <Plus className="w-4 h-4 mr-2" />
              Nueva Asistencia
            </Button>
          )}
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-green-500 p-3 rounded-lg">
              <CheckCircle className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Presentes Hoy</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">69</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-red-500 p-3 rounded-lg">
              <XCircle className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Ausentes Hoy</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">6</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-yellow-500 p-3 rounded-lg">
              <Clock className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Tardanzas</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">3</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-blue-500 p-3 rounded-lg">
              <Users className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">% Asistencia</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">92%</p>
            </div>
          </div>
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha</label>
            <Input type="date" value={selectedDate} onChange={(e) => setSelectedDate(e.target.value)} />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Curso</label>
            <select
              value={selectedCourse}
              onChange={(e) => setSelectedCourse(e.target.value)}
              className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Todos los cursos</option>
              <option value="11A">11° A</option>
              <option value="11B">11° B</option>
              <option value="10A">10° A</option>
              <option value="10B">10° B</option>
              <option value="9A">9° A</option>
              <option value="9B">9° B</option>
            </select>
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Materia</label>
            <select
              value={selectedSubject}
              onChange={(e) => setSelectedSubject(e.target.value)}
              className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Todas las materias</option>
              <option value="Matemáticas">Matemáticas</option>
              <option value="Ciencias">Ciencias</option>
              <option value="Español">Español</option>
              <option value="Inglés">Inglés</option>
              <option value="Historia">Historia</option>
            </select>
          </div>
          <div className="flex items-end">
            <Button variant="outline" className="w-full">
              <Filter className="w-4 h-4 mr-2" />
              Filtrar
            </Button>
          </div>
        </div>
      </div>

      {/* Attendance Records */}
      <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead className="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Fecha
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Curso
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Materia
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Docente
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Asistencia
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Hora
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody className="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              {filteredRecords.map((record) => (
                <tr key={record.id} className="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{record.fecha}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {record.grado}° {record.curso}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {record.materia}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {record.docente}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <div className="flex items-center">
                      <div className="flex-1">
                        <div className="flex items-center justify-between text-sm">
                          <span className="text-green-600 dark:text-green-400">
                            {record.presentes}/{record.total_estudiantes}
                          </span>
                          <span className="text-gray-500 dark:text-gray-400">
                            {getAttendancePercentage(record.presentes, record.total_estudiantes)}%
                          </span>
                        </div>
                        <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                          <div
                            className="bg-green-500 h-2 rounded-full"
                            style={{
                              width: `${getAttendancePercentage(record.presentes, record.total_estudiantes)}%`,
                            }}
                          ></div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {record.hora_registro}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div className="flex gap-2">
                      <Button size="sm" variant="outline" onClick={() => handleViewRecord(record)}>
                        <Eye className="w-4 h-4" />
                      </Button>
                      {(user?.id_rol == "104" || user?.id_rol == "102") && (
                        <Button size="sm" variant="outline">
                          <Edit className="w-4 h-4" />
                        </Button>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {filteredRecords.length === 0 && (
        <div className="text-center py-12">
          <Calendar className="w-12 h-12 text-gray-400 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
            No se encontraron registros de asistencia
          </h3>
          <p className="text-gray-600 dark:text-gray-400">Intenta ajustar los filtros de búsqueda</p>
        </div>
      )}

      {/* Create Attendance Modal */}
      {showCreateModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div className="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
            <div className="p-6">
              <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-4">Nueva Asistencia</h2>
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
                  <Input type="date" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso</label>
                  <select className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Seleccionar curso</option>
                    <option value="11A">11° A</option>
                    <option value="11B">11° B</option>
                    <option value="10A">10° A</option>
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Materia</label>
                  <select className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Seleccionar materia</option>
                    <option value="Matemáticas">Matemáticas</option>
                    <option value="Ciencias">Ciencias</option>
                    <option value="Español">Español</option>
                  </select>
                </div>
              </div>
              <div className="flex justify-end gap-2 mt-6">
                <Button variant="outline" onClick={() => setShowCreateModal(false)}>
                  Cancelar
                </Button>
                <Button onClick={() => setShowCreateModal(false)}>Continuar</Button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* View Record Modal */}
      {showViewModal && selectedRecord && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div className="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div className="p-6">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-xl font-bold text-gray-900 dark:text-white">Detalle de Asistencia</h2>
                <button
                  onClick={() => setShowViewModal(false)}
                  className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                >
                  ×
                </button>
              </div>

              <div className="grid grid-cols-2 gap-4 mb-6">
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
                  <p className="text-gray-900 dark:text-white">{selectedRecord.fecha}</p>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso</label>
                  <p className="text-gray-900 dark:text-white">
                    {selectedRecord.grado}° {selectedRecord.curso}
                  </p>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Materia</label>
                  <p className="text-gray-900 dark:text-white">{selectedRecord.materia}</p>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Docente</label>
                  <p className="text-gray-900 dark:text-white">{selectedRecord.docente}</p>
                </div>
              </div>

              <div className="grid grid-cols-4 gap-4 mb-6">
                <div className="text-center">
                  <div className="text-2xl font-bold text-green-600 dark:text-green-400">
                    {selectedRecord.presentes}
                  </div>
                  <div className="text-sm text-gray-600 dark:text-gray-400">Presentes</div>
                </div>
                <div className="text-center">
                  <div className="text-2xl font-bold text-red-600 dark:text-red-400">{selectedRecord.ausentes}</div>
                  <div className="text-sm text-gray-600 dark:text-gray-400">Ausentes</div>
                </div>
                <div className="text-center">
                  <div className="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                    {selectedRecord.tardanzas}
                  </div>
                  <div className="text-sm text-gray-600 dark:text-gray-400">Tardanzas</div>
                </div>
                <div className="text-center">
                  <div className="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {selectedRecord.justificados}
                  </div>
                  <div className="text-sm text-gray-600 dark:text-gray-400">Justificados</div>
                </div>
              </div>

              <div className="flex justify-end">
                <Button onClick={() => setShowViewModal(false)}>Cerrar</Button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

export default AttendancePage
