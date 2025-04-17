"use client"

import type React from "react"
import { createContext, useState, useContext, useEffect } from "react"
import { useColorScheme } from "react-native"
import AsyncStorage from "@react-native-async-storage/async-storage"

type ThemeType = "light" | "dark"

interface ThemeContextProps {
    theme: ThemeType
    isDark: boolean
    toggleTheme: () => void
    colors: {
        background: string
        card: string
        text: string
        border: string
        primary: string
        secondary: string
        accent: string
        error: string
        tabBar: string
        headerBackground: string
        success?: string
        warning?: string
        info?: string
        inputBackground?: string
    }
}

const ThemeContext = createContext<ThemeContextProps | undefined>(undefined)

export const ThemeProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const deviceTheme = useColorScheme() as ThemeType
    const [theme, setTheme] = useState<ThemeType>(deviceTheme || "light")

    useEffect(() => {
        const loadTheme = async () => {
            try {
                const savedTheme = await AsyncStorage.getItem("theme")
                if (savedTheme) {
                    setTheme(savedTheme as ThemeType)
                }
            } catch (error) {
                console.log("Error loading theme", error)
            }
        }

        loadTheme()
    }, [])

    const toggleTheme = async () => {
        const newTheme = theme === "light" ? "dark" : "light"
        setTheme(newTheme)
        try {
            await AsyncStorage.setItem("theme", newTheme)
        } catch (error) {
            console.log("Error saving theme", error)
        }
    }

    const isDark = theme === "dark"

    // Definir colores basados en el tema
    const colors = {
        background: isDark ? "#121212" : "#F5F5F5",
        card: isDark ? "#1E1E1E" : "#FFFFFF",
        text: isDark ? "#FFFFFF" : "#000000",
        border: isDark ? "#3D3D3D" : "#E0E0E0",
        primary: "#2196F3", // Azul como en las capturas
        secondary: isDark ? "#3A3A3A" : "#EEEEEE",
        accent: isDark ? "#64B5F6" : "#2196F3", // Azul más claro en modo oscuro
        error: isDark ? "#FF6B6B" : "#FF5252", // Rojo más suave en modo oscuro
        tabBar: isDark ? "#1A1A1A" : "#FFFFFF",
        headerBackground: isDark ? "#1A1A1A" : "#FFFFFF",
        success: isDark ? "#66BB6A" : "#4CAF50", // Color de éxito
        warning: isDark ? "#FFCA28" : "#FFC107", // Color de advertencia
        info: isDark ? "#29B6F6" : "#03A9F4", // Color de información
        inputBackground: isDark ? "#2C2C2C" : "#F9F9F9", // Fondo para inputs
    }

    return <ThemeContext.Provider value={{ theme, isDark, toggleTheme, colors }}>{children}</ThemeContext.Provider>
}

export const useTheme = () => {
    const context = useContext(ThemeContext)
    if (context === undefined) {
        throw new Error("useTheme must be used within a ThemeProvider")
    }
    return context
}

