"use client"

import { useState, useEffect } from "react"
import { Search, Plus, Filter, Download, Eye, Edit, Trash2, User, Mail, Phone, Shield } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"
import LoadingSpinner from "../../components/ui/LoadingSpinner"
import { useAuth } from "../../context/AuthContext"
import toast from "react-hot-toast"
import { apiService } from "../../services/apiService"

const UsersPage = () => {
  const { user } = useAuth()
  const [users, setUsers] = useState([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [selectedRole, setSelectedRole] = useState("")
  const [selectedStatus, setSelectedStatus] = useState("")
  const [showModal, setShowModal] = useState(false)
  const [selectedUser, setSelectedUser] = useState(null)
  const [modalMode, setModalMode] = useState("view")

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

  const filteredUsers = users.filter((user) => {
    const searchLower = searchTerm.toLowerCase().trim();

    const matchesSearch =
      (user.nombre1?.toLowerCase() || "").includes(searchLower) ||
      (user.nombre2?.toLowerCase() || "").includes(searchLower) ||
      (user.apellido1?.toLowerCase() || "").includes(searchLower) ||
      (user.apellido2?.toLowerCase() || "").includes(searchLower) ||
      (user.email.toLowerCase() || "").includes(searchLower) ||
      (user.documento?.toString() || "").includes(searchTerm); // Búsqueda exacta para documento

    const matchesRole = !selectedRole || user.id_rol == selectedRole
    const matchesStatus = !selectedStatus || user.activo == selectedStatus

    return matchesSearch && matchesRole && matchesStatus
  })

  const handleViewUser = (user) => {
    setSelectedUser(user)
    setModalMode("view")
    setShowModal(true)
  }

  const handleEditUser = (user) => {
    setSelectedUser(user)
    setModalMode("edit")
    setShowModal(true)
  }

  const handleCreateUser = () => {
    setSelectedUser(null)
    setModalMode("create")
    setShowModal(true)
  }

  const handleDeleteUser = async (userId) => {
    if (window.confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
      try {
        setUsers(users.filter((u) => u.documento !== userId))
        toast.success("Usuario eliminado exitosamente")
      } catch (error) {
        toast.error("Error al eliminar usuario")
      }
    }
  }

  const exportToExcel = () => {
    toast.success("Exportando usuarios a Excel...")
  }

  const getRoleColor = (id_rol) => {
    const colors = {
      admin: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
      docente: "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200",
      estudiante: "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
      acudiente: "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200",
    }
    return colors[id_rol] || "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
  }

  const getRoleIcon = (id_rol) => {
    switch (id_rol) {
      case 104:
        return <Shield className="w-4 h-4" />
      case 102:
        return <User className="w-4 h-4" />
      case 101:
        return <User className="w-4 h-4" />
      case 103:
        return <User className="w-4 h-4" />
      default:
        return <User className="w-4 h-4" />
    }
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
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Usuarios</h1>
          <p className="text-gray-600 dark:text-gray-400">Gestiona todos los usuarios del sistema</p>
        </div>
        <div className="flex gap-2 mt-4 sm:mt-0">
          <Button onClick={exportToExcel} variant="outline">
            <Download className="w-4 h-4 mr-2" />
            Exportar
          </Button>
          {user?.id_rol == "104" && (
            <Button onClick={handleCreateUser}>
              <Plus className="w-4 h-4 mr-2" />
              Nuevo Usuario
            </Button>
          )}
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-blue-500 p-3 rounded-lg">
              <User className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Usuarios</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">{users.length}</p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-green-500 p-3 rounded-lg"> 
              <User className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Estudiantes</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {users.filter((u) => u.id_rol == "101").length}
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
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Docentes</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {users.filter((u) => u.id_rol == "102").length}
              </p>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="flex items-center">
            <div className="bg-orange-500 p-3 rounded-lg">
              <Shield className="w-6 h-6 text-white" />
            </div>
            <div className="ml-4">
              <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Activos</p>
              <p className="text-2xl font-bold text-gray-900 dark:text-white">
                {users.filter((u) => u.activo === 1).length}
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
              placeholder="Buscar por nombre, email o documento..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10"
            />
          </div>
          <select
            value={selectedRole}
            onChange={(e) => setSelectedRole(e.target.value)}
            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="">Todos los roles</option>
            <option value="104">Administrador</option>
            <option value="102">Docente</option>
            <option value="101">Estudiante</option>
            <option value="103">Acudiente</option>
          </select>
          <select
            value={selectedStatus}
            onChange={(e) => setSelectedStatus(e.target.value)}
            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="">Todos los estados</option>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
          <Button variant="outline">
            <Filter className="w-4 h-4 mr-2" />
            Filtros
          </Button>
        </div>
      </div>

      {/* Users Table */}
      <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead className="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Usuario
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Rol
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Contacto
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Estado
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody className="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              {filteredUsers.map((user) => (
                <tr key={user.id} className="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td className="px-6 py-4 whitespace-nowrap">
                    <div className="flex items-center">
                      <img
                        src={`/IMG/${user.foto || "user.png"}`}
                        alt={`${user.nombre1} ${user.apellido1}`}
                        className="w-10 h-10 rounded-full object-cover mr-3"
                      />
                      <div>
                        <div className="text-sm font-medium text-gray-900 dark:text-white">
                          {user.nombre1} {user.nombre2} {user.apellido1} {user.apellido2}
                        </div>
                        <div className="text-sm text-gray-500 dark:text-gray-400">{user.documento}</div>
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <div className="flex items-center">
                      <span
                        className={`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getRoleColor(user.id_rol)}`}
                      >
                        {getRoleIcon(user.id_rol)}
                        <span className="ml-1 capitalize">{user?.id_rol === 104 ? "Administrador" : user?.id_rol === 102 ? "Docente" : "Estudiante"}</span>
                      </span>
                    </div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <div className="text-sm text-gray-900 dark:text-white">
                      <div className="flex items-center mb-1">
                        <Mail className="w-4 h-4 mr-1 text-gray-400" />
                        {user.email}
                      </div>
                      <div className="flex items-center">
                        <Phone className="w-4 h-4 mr-1 text-gray-400" />
                        {user.telefono}
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <span
                      className={`px-2 py-1 text-xs font-medium rounded-full ${user.activo === 1
                          ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                          : "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
                        }`}
                    >
                      {user.activo === 1 ? "Activo" : "Inactivo"}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div className="flex gap-2">
                      <Button size="sm" variant="outline" onClick={() => handleViewUser(user)}>
                        <Eye className="w-4 h-4" />
                      </Button>
                      {user?.id_rol === 104 && (
                        <>
                          <Button size="sm" variant="outline" onClick={() => handleEditUser(user)}>
                            <Edit className="w-4 h-4" />
                          </Button>
                          <Button
                            size="sm"
                            variant="outline"
                            onClick={() => handleDeleteUser(user.documento)}
                            className="text-red-600 hover:text-red-700"
                          >
                            <Trash2 className="w-4 h-4" />
                          </Button>
                        </>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {filteredUsers.length === 0 && (
        <div className="text-center py-12">
          <User className="w-12 h-12 text-gray-400 mx-auto mb-4" />
          <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">No se encontraron usuarios</h3>
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
                    ? "Nuevo Usuario"
                    : modalMode === "edit"
                      ? "Editar Usuario"
                      : "Información del Usuario"}
                </h2>
                <button
                  onClick={() => setShowModal(false)}
                  className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                >
                  ×
                </button>
              </div>

              {selectedUser && modalMode === "view" && (
                <div className="space-y-6">
                  <div className="flex items-center">
                    <img
                      src={`/IMG/${user.foto || "user.png"}`}
                      alt={`${selectedUser.nombre1} ${selectedUser.apellido1}`}
                      className="w-20 h-20 rounded-full object-cover mr-4"
                    />
                    <div>
                      <h3 className="text-xl font-semibold text-gray-900 dark:text-white">
                        {selectedUser.nombre1} {selectedUser.nombre2} {selectedUser.apellido1} {selectedUser.apellido2}
                      </h3>
                      <div className="flex items-center mt-1">
                        <span
                          className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getRoleColor(selectedUser.id_rol)}`}
                        >
                          {getRoleIcon(selectedUser.id_rol)}
                          <span className="ml-1 capitalize">{selectedUser.id_rol}</span>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Documento
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedUser.documento}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                      <p className="text-gray-900 dark:text-white">{selectedUser.email}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Teléfono
                      </label>
                      <p className="text-gray-900 dark:text-white">{selectedUser.telefono}</p>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                      <span
                        className={`px-2 py-1 text-xs font-medium rounded-full ${selectedUser.activo === 1
                            ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                            : "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
                          }`}
                      >
                        {selectedUser.activo === 1 ? "Activo" : "Inactivo"}
                      </span>
                    </div>
                  </div>

                  {/* Role-specific information */}
                  {selectedUser.id_rol === 101 && (
                    <div className="grid grid-cols-2 gap-4">
                      <div>
                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grado</label>
                        <p className="text-gray-900 dark:text-white">{selectedUser.grado}°</p>
                      </div>
                      {/* <div>
                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Curso</label>
                        <p className="text-gray-900 dark:text-white">{selectedUser.curso}</p>
                      </div> */}
                    </div>
                  )}

                  {selectedUser.id_rol == "102" && selectedUser.materias && (
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Materias
                      </label>
                      <div className="flex flex-wrap gap-2">
                        {selectedUser.materias.map((materia, index) => (
                          <span
                            key={index}
                            className="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full"
                          >
                            {materia}
                          </span>
                        ))}
                      </div>
                    </div>
                  )}

                  {selectedUser.id_rol == "103" && selectedUser.estudiante && (
                    <div>
                      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Estudiante a cargo
                      </label>
                      <p className="text-gray-900 dark:text-white">{user.nombre1} {user.nombre2} {user.apellido1} {user.apellido2}</p>
                    </div>
                  )}
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

export default UsersPage
