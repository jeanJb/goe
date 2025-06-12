"use client"

import { Menu, Bell } from "lucide-react"
import { useAuth } from "../../context/AuthContext"

const Header = ({ onMenuClick }) => {
  const { user } = useAuth()

  return (
    <header className="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
      <div className="flex items-center justify-between px-6 py-4">
        {/* Mobile menu button */}
        <button
          onClick={onMenuClick}
          className="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700"
        >
          <Menu className="w-6 h-6" />
        </button>

        {/* Page title */}
        <div className="flex-1 lg:flex-none">
          <h1 className="text-2xl font-semibold text-gray-900 dark:text-white">
            ¡Hola, {user?.nombre1} {user?.apellido1}!
          </h1>
        </div>

        {/* Right section */}
        <div className="flex items-center space-x-4">
          {/* Notifications */}
          <button className="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 relative">
            <Bell className="w-6 h-6" />
            <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>

          {/* User avatar */}
          <div className="flex items-center space-x-3">
            <div className="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
              <span className="text-white text-sm font-medium">
                {user?.nombre1?.charAt(0)}
                {user?.apellido1?.charAt(0)}
              </span>
            </div>
            <div className="hidden md:block">
              <p className="text-sm font-medium text-gray-900 dark:text-white">
                {user?.nombre1} {user?.apellido1}
              </p>
              <p className="text-xs text-gray-500 dark:text-gray-400">
                {user?.id_rol == "104" ? "Administrador" : user?.id_rol == "102" ? "Docente" : "Estudiante"}
              </p>
            </div>
          </div>
        </div>
      </div>
    </header>
  )
}

export default Header
