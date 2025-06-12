"use client"

import { useState } from "react"
import { Mail, Edit, Save, X, Camera } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"
import { useAuth } from "../../context/AuthContext"
import toast from "react-hot-toast"

const ProfilePage = () => {
  const { user } = useAuth()
  const [isEditing, setIsEditing] = useState(false)
  const [formData, setFormData] = useState({
    nombre1: "Juan",
    nombre2: "Carlos",
    apellido1: "García",
    apellido2: "Martínez",
    documento: "1234567890",
    email: "juan.garcia@colegio.edu.co",
    telefono: "3001234567",
    direccion: "Calle 123 #45-67, Bogotá",
    fecha_nacimiento: "1985-05-15",
    avatar: "/IMG/user.png",
  })

  const handleInputChange = (e) => {
    const { name, value } = e.target
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }))
  }

  const handleSave = async () => {
    try {
      // API call to update profile
      toast.success("Perfil actualizado exitosamente")
      setIsEditing(false)
    } catch (error) {
      toast.error("Error al actualizar el perfil")
    }
  }

  const handleCancel = () => {
    // Reset form data to original values
    setIsEditing(false)
  }

  const handleAvatarChange = (e) => {
    const file = e.target.files[0]
    if (file) {
      const reader = new FileReader()
      reader.onload = (e) => {
        setFormData((prev) => ({
          ...prev,
          avatar: e.target.result,
        }))
      }
      reader.readAsDataURL(file)
    }
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Mi Perfil</h1>
          <p className="text-gray-600 dark:text-gray-400">Gestiona tu información personal</p>
        </div>
        <div className="flex gap-2 mt-4 sm:mt-0">
          {!isEditing ? (
            <Button onClick={() => setIsEditing(true)}>
              <Edit className="w-4 h-4 mr-2" />
              Editar Perfil
            </Button>
          ) : (
            <div className="flex gap-2">
              <Button variant="outline" onClick={handleCancel}>
                <X className="w-4 h-4 mr-2" />
                Cancelar
              </Button>
              <Button onClick={handleSave}>
                <Save className="w-4 h-4 mr-2" />
                Guardar
              </Button>
            </div>
          )}
        </div>
      </div>

      {/* Profile Card */}
      <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div className="p-6">
          {/* Avatar Section */}
          <div className="flex flex-col sm:flex-row sm:items-center mb-8">
            <div className="relative mb-4 sm:mb-0 sm:mr-6">
              <img
                src={`/IMG/${user?.foto || "user.png"}`}
                alt="Avatar"
                className="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600"
              />
              {isEditing && (
                <label className="absolute bottom-0 right-0 bg-primary-500 hover:bg-primary-600 text-white p-2 rounded-full cursor-pointer transition-colors">
                  <Camera className="w-4 h-4" />
                  <input type="file" accept="image/*" onChange={handleAvatarChange} className="hidden" />
                </label>
              )}
            </div>
            <div className="flex-1">
              <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                {user?.nombre1} {user?.nombre2} {user?.apellido1} {user?.apellido2}
              </h2>
              <p className="text-gray-600 dark:text-gray-400 mb-2">
                {user?.id_rol == "102"
                  ? "Docente"
                  : user?.id_rol == "104"
                    ? "Administrador"
                    : user?.id_rol == "101"
                      ? "Estudiante"
                      : "Usuario"}
              </p>
              <div className="flex items-center text-sm text-gray-500 dark:text-gray-400">
                <Mail className="w-4 h-4 mr-1" />
                {user?.email}
              </div>
            </div>
          </div>

          {/* Personal Information */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primer Nombre</label>
              {isEditing ? (
                <Input name="nombre1" value={user?.nombre1} onChange={handleInputChange} />
              ) : (
                <p className="text-gray-900 dark:text-white py-2">{user?.nombre1}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Segundo Nombre</label>
              {isEditing ? (
                <Input name="nombre2" value={user?.nombre2} onChange={handleInputChange} />
              ) : (
                <p className="text-gray-900 dark:text-white py-2">{user?.nombre2}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primer Apellido</label>
              {isEditing ? (
                <Input name="apellido1" value={user?.apellido1} onChange={handleInputChange} />
              ) : (
                <p className="text-gray-900 dark:text-white py-2">{user?.apellido1}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Segundo Apellido
              </label>
              {isEditing ? (
                <Input name="apellido2" value={user?.apellido2} onChange={handleInputChange} />
              ) : (
                <p className="text-gray-900 dark:text-white py-2">{user?.apellido2}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Documento de Identidad
              </label>
              {isEditing ? (
                <Input name="documento" value={user?.documento} onChange={handleInputChange} />
              ) : (
                <p className="text-gray-900 dark:text-white py-2">{user?.documento}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
              {isEditing ? (
                <Input name="email" type="email" value={user?.email} onChange={handleInputChange} />
              ) : (
                <p className="text-gray-900 dark:text-white py-2">{user?.email}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
              {isEditing ? (
                <Input name="telefono" value={user?.telefono} onChange={handleInputChange} />
              ) : (
                <p className="text-gray-900 dark:text-white py-2">{user?.telefono}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Fecha de Nacimiento
              </label>
              {isEditing ? (
                <Input
                  name="fecha_nacimiento"
                  type="date"
                  value={formData.fecha_nacimiento}
                  onChange={handleInputChange}
                />
              ) : (
                <p className="text-gray-900 dark:text-white py-2">{formData.fecha_nacimiento}</p>
              )}
            </div>

            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dirección</label>
              {isEditing ? (
                <Input name="direccion" value={formData.direccion} onChange={handleInputChange} />
              ) : (
                <p className="text-gray-900 dark:text-white py-2">{formData.direccion}</p>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Role-specific Information */}
      {user?.id_rol == "102" && (
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="p-6">
            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información Académica</h3>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Materias que Imparte
                </label>
                <div className="flex flex-wrap gap-2">
                  <span className="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full">
                    Matemáticas
                  </span>
                  <span className="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full">
                    Física
                  </span>
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Cursos a Cargo
                </label>
                <div className="flex flex-wrap gap-2">
                  <span className="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm rounded-full">
                    11° A
                  </span>
                  <span className="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm rounded-full">
                    10° B
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {user?.id_rol === "101" && (
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
          <div className="p-6">
            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información Académica</h3>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Grado</label>
                <p className="text-gray-900 dark:text-white py-2">11°</p>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Curso</label>
                <p className="text-gray-900 dark:text-white py-2">A</p>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Director de Curso
                </label>
                <p className="text-gray-900 dark:text-white py-2">Prof. García Martínez</p>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Security Section */}
      <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div className="p-6">
          <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Seguridad</h3>
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <div>
                <h4 className="text-sm font-medium text-gray-900 dark:text-white">Cambiar Contraseña</h4>
                <p className="text-sm text-gray-600 dark:text-gray-400">
                  Actualiza tu contraseña regularmente para mantener tu cuenta segura
                </p>
              </div>
              <Button variant="outline">Cambiar</Button>
            </div>
            <div className="flex items-center justify-between">
              <div>
                <h4 className="text-sm font-medium text-gray-900 dark:text-white">Autenticación de Dos Factores</h4>
                <p className="text-sm text-gray-600 dark:text-gray-400">
                  Añade una capa extra de seguridad a tu cuenta
                </p>
              </div>
              <Button variant="outline">Configurar</Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default ProfilePage
