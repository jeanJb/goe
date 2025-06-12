"use client"

import { useState, useEffect } from "react"
import { Link, useLocation } from "react-router-dom"
import { useAuth } from "../../context/AuthContext"
import { useTheme } from "../../context/ThemeContext"
import {
  Home,
  Users,
  Calendar,
  BookOpen,
  GraduationCap,
  Mail,
  User,
  LogOut,
  Menu,
  X,
  Sun,
  Moon,
  ClipboardList,
  School,
} from "lucide-react"

const Sidebar = ({ isOpen, onClose }) => {
  const { user, logout } = useAuth()
  const { isDark, toggleTheme } = useTheme()
  const location = useLocation()
  const [collapsed, setCollapsed] = useState(false)

  // Close sidebar on route change (mobile)
  useEffect(() => {
    onClose()
  }, [location.pathname, onClose])

  // Definir roles numéricos
  const ROLES = {
    ESTUDIANTE: 101,
    PROFESOR: 102,
    ADMINISTRADOR: 104,
  }

  const menuItems = [
    {
      icon: Home,
      label: "Inicio",
      path: "/dashboard",
      roles: [ROLES.ADMINISTRADOR, ROLES.PROFESOR, ROLES.ESTUDIANTE],
    },
    {
      icon: BookOpen,
      label: "Observadores",
      path: "/dashboard/observers",
      roles: [ROLES.ADMINISTRADOR, ROLES.PROFESOR, ROLES.ESTUDIANTE],
    },
    {
      icon: Calendar,
      label: "Asistencias",
      path: "/dashboard/attendance",
      roles: [ROLES.ADMINISTRADOR, ROLES.PROFESOR, ROLES.ESTUDIANTE],
    },
    {
      icon: GraduationCap,
      label: "Estudiantes",
      path: "/dashboard/students",
      roles: [ROLES.ADMINISTRADOR, ROLES.PROFESOR],
    },
    {
      icon: School,
      label: "Cursos",
      path: "/dashboard/courses",
      roles: [ROLES.ADMINISTRADOR],
    },
    {
      icon: ClipboardList,
      label: "Materias",
      path: "/dashboard/subjects",
      roles: [ROLES.ADMINISTRADOR],
    },
    {
      icon: Users,
      label: "Usuarios",
      path: "/dashboard/users",
      roles: [ROLES.ADMINISTRADOR],
    },
    {
      icon: Mail,
      label: "Correos",
      path: "/dashboard/email",
      roles: [ROLES.ADMINISTRADOR, ROLES.PROFESOR],
    },
  ]

  // Filtrar elementos del menú según el rol del usuario
  const userRole = Number.parseInt(user?.id_rol || user?.id_rol || 101)
  const filteredMenuItems = menuItems.filter((item) => item.roles.includes(userRole))

  const isActive = (path) => location.pathname === path

  // Función para obtener el nombre del rol
  const getRoleName = (role) => {
    switch (Number.parseInt(role)) {
      case ROLES.ADMINISTRADOR:
        return "Administrador"
      case ROLES.PROFESOR:
        return "Profesor"
      case ROLES.ESTUDIANTE:
        return "Estudiante"
      default:
        return "Usuario"
    }
  }

  return (
    <>
      {/* Mobile overlay */}
      {isOpen && <div className="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" onClick={onClose} />}

      {/* Sidebar */}
      <div
        className={`
        fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700
        transition-all duration-10000 ease-in-out
        lg:translate-x-0 lg:static lg:inset-0
        ${isOpen ? "translate-x-0" : "-translate-x-full"}
        ${collapsed ? "lg:w-20" : "lg:w-64"}
      `}
      >
        {/* Header */}
        <div className={`flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 ${collapsed ? "flex-col" : ""} `}>
          <div className={`flex items-center ${collapsed ? "justify-center" : ""}`}>
            <img src="/IMG/logos/goe03.png" alt="GOE" className="w-10 h-10" />
            {!collapsed && (
              <div className="ml-3">
                <span className="text-xl font-bold text-gray-800 dark:text-white">GOE</span>
                <p className="text-xs text-gray-500 dark:text-gray-400">{getRoleName(userRole)}</p>
              </div>
            )}
          </div>

          {/* Mobile close button */}
          <button
            onClick={onClose}
            className="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300"
          >
            <X className="w-5 h-5" />
          </button>

          {/* Desktop collapse button */}
          <button
            onClick={() => setCollapsed(!collapsed)}
            className="hidden lg:block p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300"
          >
            <Menu className="w-5 h-5" />
          </button>
        </div>

        {/* User info */}
        {!collapsed && user && (
          <div className="p-4 border-b border-gray-200 dark:border-gray-700">
            <div className="flex items-center">
              <div className="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                {user.nombre?.charAt(0)?.toUpperCase() || user.email?.charAt(0)?.toUpperCase() || "U"}
              </div>
              <div className="ml-3">
                <p className="text-sm font-medium text-gray-900 dark:text-white">{user.nombre1 || user.email}</p>
                <p className="text-xs text-gray-500 dark:text-gray-400">{user.email}</p>
              </div>
            </div>
          </div>
        )}

        {/* Navigation */}
        <nav className="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
          {filteredMenuItems.map((item) => {
            const Icon = item.icon
            return (
              <Link
                key={item.path}
                to={item.path}
                className={`
                  flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  ${
                    isActive(item.path)
                      ? "bg-blue-500 text-white"
                      : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                  }
                  ${collapsed ? "justify-center" : ""}
                `}
                title={collapsed ? item.label : ""}
              >
                <Icon className="w-5 h-5" />
                {!collapsed && <span className="ml-3">{item.label}</span>}
              </Link>
            )
          })}
        </nav>

        {/* Bottom section */}
        <div className="p-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
          {/* Profile */}
          <Link
            to="/dashboard/profile"
            className={`
              flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors
              ${
                isActive("/dashboard/profile")
                  ? "bg-blue-500 text-white"
                  : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
              }
              ${collapsed ? "justify-center" : ""}
            `}
            title={collapsed ? "Perfil" : ""}
          >
            <User className="w-5 h-5" />
            {!collapsed && <span className="ml-3">Perfil</span>}
          </Link>

          {/* Theme toggle */}
          <button
            onClick={toggleTheme}
            className={`
              w-full flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors
              text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700
              ${collapsed ? "justify-center" : ""}
            `}
            title={collapsed ? (isDark ? "Modo claro" : "Modo oscuro") : ""}
          >
            {isDark ? <Sun className="w-5 h-5" /> : <Moon className="w-5 h-5" />}
            {!collapsed && <span className="ml-3">{isDark ? "Modo claro" : "Modo oscuro"}</span>}
          </button>

          {/* Logout */}
          <button
            onClick={logout}
            className={`
              w-full flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors
              text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20
              ${collapsed ? "justify-center" : ""}
            `}
            title={collapsed ? "Cerrar sesión" : ""}
          >
            <LogOut className="w-5 h-5" />
            {!collapsed && <span className="ml-3">Cerrar sesión</span>}
          </button>
        </div>
      </div>
    </>
  )
}

export default Sidebar
