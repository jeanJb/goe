"use client"

import { useState } from "react"
import { Link, useNavigate } from "react-router-dom"
import { useForm } from "react-hook-form"
import { useAuth } from "../../context/AuthContext"
import { Eye, EyeOff, ArrowLeft } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"

const RegisterPage = () => {
  const [showPassword, setShowPassword] = useState(false)
  const { register: registerUser } = useAuth()
  const navigate = useNavigate()

  const {
    register,
    handleSubmit,
    watch,
    formState: { errors, isSubmitting },
  } = useForm()

  const password = watch("password")

  const onSubmit = async (data) => {
    const result = await registerUser(data)
    if (result.success) {
      navigate("/login")
    }
  }

  return (
    <div className="min-h-screen flex">
      {/* Left side - Form */}
      <div className="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24 bg-white dark:bg-gray-900">
        <div className="mx-auto w-full max-w-sm lg:w-96">
          {/* Back button */}
          <Link
            to="/"
            className="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mb-8"
          >
            <ArrowLeft className="w-4 h-4 mr-2" />
            Volver al inicio
          </Link>

          {/* Logo and title */}
          <div className="text-center mb-8">
            <img src="/IMG/logos/goe03.png" alt="GOE" className="w-16 h-16 mx-auto mb-4" />
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white">Crear Cuenta</h2>
            <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">Únete a la comunidad GOE</p>
          </div>

          {/* Form */}
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <Input
              label="Documento"
              type="number"
              placeholder="Número de documento"
              error={errors.documento?.message}
              {...register("documento", {
                required: "El documento es requerido",
                minLength: {
                  value: 6,
                  message: "El documento debe tener al menos 6 dígitos",
                },
              })}
            />

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Input
                label="Primer Nombre"
                type="text"
                placeholder="Primer nombre"
                error={errors.nombre1?.message}
                {...register("nombre1", {
                  required: "El primer nombre es requerido",
                })}
              />

              <Input
                label="Segundo Nombre"
                type="text"
                placeholder="Segundo nombre (opcional)"
                {...register("nombre2")}
              />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Input
                label="Primer Apellido"
                type="text"
                placeholder="Primer apellido"
                error={errors.apellido1?.message}
                {...register("apellido1", {
                  required: "El primer apellido es requerido",
                })}
              />

              <Input
                label="Segundo Apellido"
                type="text"
                placeholder="Segundo apellido"
                error={errors.apellido2?.message}
                {...register("apellido2", {
                  required: "El segundo apellido es requerido",
                })}
              />
            </div>

            <Input
              label="Correo electrónico"
              type="email"
              placeholder="tu@email.com"
              error={errors.email?.message}
              {...register("email", {
                required: "El correo es requerido",
                pattern: {
                  value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                  message: "Correo electrónico inválido",
                },
              })}
            />

            <Input
              label="Teléfono"
              type="tel"
              placeholder="Número de teléfono"
              error={errors.telefono?.message}
              {...register("telefono", {
                required: "El teléfono es requerido",
              })}
            />

            <Input
              label="Dirección"
              type="text"
              placeholder="Dirección de residencia"
              error={errors.direccion?.message}
              {...register("direccion", {
                required: "La dirección es requerida",
              })}
            />

            <div className="relative">
              <Input
                label="Contraseña"
                type={showPassword ? "text" : "password"}
                placeholder="Tu contraseña"
                error={errors.password?.message}
                {...register("password", {
                  required: "La contraseña es requerida",
                  minLength: {
                    value: 8,
                    message: "La contraseña debe tener al menos 8 caracteres",
                  },
                })}
              />
              <button
                type="button"
                className="absolute right-3 top-8 text-gray-400 hover:text-gray-600"
                onClick={() => setShowPassword(!showPassword)}
              >
                {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
              </button>
            </div>

            <Input
              label="Confirmar Contraseña"
              type="password"
              placeholder="Confirma tu contraseña"
              error={errors.confirmPassword?.message}
              {...register("confirmPassword", {
                required: "Confirma tu contraseña",
                validate: (value) => value === password || "Las contraseñas no coinciden",
              })}
            />

            <div className="flex items-center">
              <input
                id="terms"
                name="terms"
                type="checkbox"
                className="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                {...register("terms", {
                  required: "Debes aceptar los términos y condiciones",
                })}
              />
              <label htmlFor="terms" className="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                Acepto los{" "}
                <Link to="/terms" className="text-primary-600 hover:text-primary-500">
                  términos y condiciones
                </Link>
              </label>
            </div>
            {errors.terms && <p className="text-sm text-red-600 dark:text-red-400">{errors.terms.message}</p>}

            <Button type="submit" className="w-full" loading={isSubmitting} disabled={isSubmitting}>
              {isSubmitting ? "Creando cuenta..." : "Crear Cuenta"}
            </Button>
          </form>

          {/* Login link */}
          <p className="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            ¿Ya tienes una cuenta?{" "}
            <Link to="/login" className="font-medium text-primary-600 hover:text-primary-500">
              Inicia sesión aquí
            </Link>
          </p>
        </div>
      </div>

      {/* Right side - Image */}
      <div className="hidden lg:block relative w-0 flex-1">
        <img
          className="absolute inset-0 h-full w-full object-cover"
          src="/IMG/font.jpg"
          alt="Colegio José Félix Restrepo"
        />
        <div className="absolute inset-0 bg-primary-600 bg-opacity-75"></div>
        <div className="absolute inset-0 flex items-center justify-center">
          <div className="text-center text-white">
            <h3 className="text-3xl font-bold mb-4">Únete a GOE</h3>
            <p className="text-xl">Gestión de Observador Estudiantil</p>
          </div>
        </div>
      </div>
    </div>
  )
}

export default RegisterPage
