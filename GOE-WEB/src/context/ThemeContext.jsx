"use client"

import { createContext, useContext, useState, useEffect } from "react"

const ThemeContext = createContext()

export const useTheme = () => {
  const context = useContext(ThemeContext)
  if (!context) {
    throw new Error("useTheme must be used within a ThemeProvider")
  }
  return context
}

export const ThemeProvider = ({ children }) => {
  const [isDark, setIsDark] = useState(() => {
    // Verificar si hay una preferencia guardada
    const saved = localStorage.getItem("goe-theme")
    if (saved) {
      return saved === "dark"
    }
    // Si no hay preferencia guardada, usar la preferencia del sistema
    return window.matchMedia("(prefers-color-scheme: dark)").matches
  })

  useEffect(() => {
    // Guardar preferencia
    localStorage.setItem("goe-theme", isDark ? "dark" : "light")

    // Aplicar clase al documento
    const root = document.documentElement
    if (isDark) {
      root.classList.add("dark")
    } else {
      root.classList.remove("dark")
    }
  }, [isDark])

  const toggleTheme = () => {
    setIsDark((prev) => !prev)
  }

  const setTheme = (theme) => {
    setIsDark(theme === "dark")
  }

  const value = {
    isDark,
    toggleTheme,
    setTheme,
    theme: isDark ? "dark" : "light",
  }

  return <ThemeContext.Provider value={value}>{children}</ThemeContext.Provider>
}
