"use client"

import type React from "react"
import { useState } from "react"
import { Link, useNavigate } from "react-router-dom"
import { useAuthStore } from "../../stores/authStore"
import { Button } from "../ui/button"
import { Input } from "../ui/input"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "../ui/card"
import { Alert, AlertDescription } from "../ui/alert"

const Register: React.FC = () => {
  const [formData, setFormData] = useState({
    documento: "",
    email: "",
    clave: "",
    confirmarClave: "",
    nombre1: "",
    nombre2: "",
    apellido1: "",
    apellido2: "",
    telefono: "",
    direccion: "",
  })
  const [error, setError] = useState("")
  const [success, setSuccess] = useState("")

  const { register, isLoading } = useAuthStore()
  const navigate = useNavigate()

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value })
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setError("")
    setSuccess("")

    if (formData.clave !== formData.confirmarClave) {
      setError("Las contraseñas no coinciden")
      return
    }

    try {
      await register(formData)
      setSuccess("Usuario registrado exitosamente. Verifica tu correo para activar tu cuenta.")
      setTimeout(() => navigate("/login"), 3000)
    } catch (err: any) {
      setError(err.message)
    }
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 px-4">
      <div className="w-full max-w-2xl">
        <Card className="shadow-lg">
          <CardHeader className="space-y-1">
            <CardTitle className="text-2xl text-center">Registro de Usuario</CardTitle>
            <CardDescription className="text-center">Completa los datos para crear tu cuenta</CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              {error && (
                <Alert className="border-red-200 bg-red-50 text-red-800">
                  <AlertDescription>{error}</AlertDescription>
                </Alert>
              )}

              {success && (
                <Alert className="border-green-200 bg-green-50 text-green-800">
                  <AlertDescription>{success}</AlertDescription>
                </Alert>
              )}

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  name="documento"
                  type="number"
                  placeholder="Documento"
                  value={formData.documento}
                  onChange={handleChange}
                  required
                />
                <Input
                  name="email"
                  type="email"
                  placeholder="Correo Electrónico"
                  value={formData.email}
                  onChange={handleChange}
                  required
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  name="nombre1"
                  type="text"
                  placeholder="Primer Nombre"
                  value={formData.nombre1}
                  onChange={handleChange}
                  required
                />
                <Input
                  name="nombre2"
                  type="text"
                  placeholder="Segundo Nombre"
                  value={formData.nombre2}
                  onChange={handleChange}
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  name="apellido1"
                  type="text"
                  placeholder="Primer Apellido"
                  value={formData.apellido1}
                  onChange={handleChange}
                  required
                />
                <Input
                  name="apellido2"
                  type="text"
                  placeholder="Segundo Apellido"
                  value={formData.apellido2}
                  onChange={handleChange}
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  name="telefono"
                  type="tel"
                  placeholder="Teléfono"
                  value={formData.telefono}
                  onChange={handleChange}
                />
                <Input
                  name="direccion"
                  type="text"
                  placeholder="Dirección"
                  value={formData.direccion}
                  onChange={handleChange}
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  name="clave"
                  type="password"
                  placeholder="Contraseña"
                  value={formData.clave}
                  onChange={handleChange}
                  required
                />
                <Input
                  name="confirmarClave"
                  type="password"
                  placeholder="Confirmar Contraseña"
                  value={formData.confirmarClave}
                  onChange={handleChange}
                  required
                />
              </div>

              <Button type="submit" className="w-full bg-primary-500 hover:bg-primary-600" disabled={isLoading}>
                {isLoading ? "Registrando..." : "Registrarse"}
              </Button>

              <div className="text-center">
                <span className="text-sm text-gray-600 dark:text-gray-400">¿Ya tienes cuenta? </span>
                <Link to="/login" className="text-sm text-primary-600 hover:text-primary-500 font-medium">
                  Inicia sesión
                </Link>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}

export default Register
