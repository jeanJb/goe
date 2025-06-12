"use client"

import { useState } from "react"
import { Link, useNavigate } from "react-router-dom"
import { useForm } from "react-hook-form"
import { useAuth } from "../../context/AuthContext"
import { Eye, EyeOff, ArrowLeft } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"

const LoginPage = () => {
  const [showClave, setShowClave] = useState(false)
  const { login, user } = useAuth()
  const navigate = useNavigate()

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useForm()

  const onSubmit = async (data) => {
    console.log("Datos del formulario:", data);

    try {
      const credentials = {
        email: data.email,
        clave: data.clave
      };
      //console.log("URL de la API:", import.meta.env.VITE_API_URL); // Verifica la URL

      const result = await login(credentials);
      console.log("Resultado del login:", result);

      if (result.success) {
        console.log("Usuario:", result.user); // ✅ Aquí sí está disponible
        navigate("/dashboard", { replace: true });
      }
    } catch (error) {
      console.error("Error completo:", error);
      console.error("Respuesta del servidor:", error.response);
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
            <img src="./IMG/logos/goe03.png" alt="GOE" className="w-16 h-16 mx-auto mb-4" />
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white">Iniciar Sesión</h2>
            <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">Accede a tu cuenta de GOE</p>
          </div>

          {/* Form */}
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
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

            <div className="relative">
              <Input
                label="Contraseña"
                type={showClave ? "text" : "password"}
                placeholder="Tu contraseña"
                error={errors.clave?.message}
                {...register("clave", {
                  required: "La contraseña es requerida",
                  minLength: {
                    value: 6,
                    message: "La contraseña debe tener al menos 6 caracteres",
                  },
                })}
              />
              <button
                type="button"
                className="absolute right-3 top-8 text-gray-400 hover:text-gray-600"
                onClick={() => setShowClave(!showClave)}
              >
                {showClave ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
              </button>
            </div>

            <div className="flex items-center justify-between">
              <div className="flex items-center">
                <input
                  id="remember-me"
                  name="remember-me"
                  type="checkbox"
                  className="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                />
                <label htmlFor="remember-me" className="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                  Recordarme
                </label>
              </div>

              <div className="text-sm">
                <Link to="/forgot-password" className="font-medium text-primary-600 hover:text-primary-500">
                  ¿Olvidaste tu contraseña?
                </Link>
              </div>
            </div>

            <Button type="submit" className="w-full" loading={isSubmitting} disabled={isSubmitting}>
              {isSubmitting ? "Iniciando sesión..." : "Iniciar Sesión"}
            </Button>
          </form>

          {/* Register link */}
          <p className="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            ¿No tienes una cuenta?{" "}
            <Link to="/register" className="font-medium text-primary-600 hover:text-primary-500">
              Regístrate aquí
            </Link>
          </p>
        </div>
      </div>

      {/* Right side - Image */}
      <div className="hidden lg:block relative w-0 flex-1">
        <img
          className="absolute inset-0 h-full w-full object-cover"
          src="/IMG/jfr4.jpg"
          alt="Colegio José Félix Restrepo"
        />
        <div className="absolute inset-0 bg-primary-600 bg-opacity-75"></div>
        <div className="absolute inset-0 flex items-center justify-center">
          <div className="text-center text-white">
            <h3 className="text-3xl font-bold mb-4">Bienvenido a GOE</h3>
            <p className="text-xl">Gestión de Observador Estudiantil</p>
          </div>
        </div>
      </div>
    </div>
  )
}

export default LoginPage
