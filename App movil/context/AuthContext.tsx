"use client"

import type React from "react"
import { createContext, useState, useContext, useEffect } from "react"
import AsyncStorage from "@react-native-async-storage/async-storage"

export type UserRole = "estudiante" | "docente"

export interface User {
    id: string
    nombre: string
    apellido?: string
    documento: string
    email: string
    telefono: string
    direccion: string
    curso: string
    role: UserRole
    rh?: string
    eps?: string
    fechaNacimiento?: string
    enfermedades?: string
    materias?: string[]
    avatar?: string
}

interface AuthContextProps {
    user: User | null
    isLoading: boolean
    login: (email: string, password: string) => Promise<void>
    logout: () => Promise<void>
    updateProfile: (userData: Partial<User>) => Promise<void>
}

const AuthContext = createContext<AuthContextProps | undefined>(undefined)

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [user, setUser] = useState<User | null>(null)
    const [isLoading, setIsLoading] = useState(true)

    useEffect(() => {
        // Cargar usuario desde AsyncStorage al iniciar
        const loadUser = async () => {
            try {
                const userData = await AsyncStorage.getItem("user")
                if (userData) {
                    setUser(JSON.parse(userData))
                }
            } catch (error) {
                console.log("Error loading user data", error)
            } finally {
                setIsLoading(false)
            }
        }

        loadUser()
    }, [])

    const login = async (email: string, password: string) => {
        setIsLoading(true)
        try {
            // Aquí iría la lógica real de autenticación con tu API
            // Por ahora, simulamos un login exitoso con datos de prueba

            // Ejemplo de usuario docente
            if (email === "docente@example.com") {
                const userData: User = {
                    id: "1",
                    nombre: "Juan",
                    apellido: "Paez",
                    documento: "2147483647",
                    email: "juan@gmail.com",
                    telefono: "12345672",
                    direccion: "transversal 16 A este #19-43 S",
                    curso: "703",
                    role: "docente",
                    materias: ["INFORMATICA", "INGLES", "FISICA"],
                    fechaNacimiento: "1985-03-15",
                    rh: "O+",
                    eps: "Sanitas",
                }
                setUser(userData)
                await AsyncStorage.setItem("user", JSON.stringify(userData))
            }
            // Ejemplo de usuario estudiante
            else if (email === "estudiante@example.com") {
                const userData: User = {
                    id: "2",
                    nombre: "Sebastian",
                    apellido: "Cardenas",
                    documento: "1029143097",
                    email: "sebastiancardenas18@gmail.com",
                    telefono: "3057645321",
                    direccion: "calle 6",
                    curso: "703",
                    role: "estudiante",
                    rh: "A+",
                    eps: "Compensar",
                    fechaNacimiento: "2006-05-18",
                    materias: ["EDU.FISICA", "FILOSOFIA"],
                    enfermedades: "Ninguna",
                }
                setUser(userData)
                await AsyncStorage.setItem("user", JSON.stringify(userData))
            } else {
                throw new Error("Credenciales inválidas")
            }
        } catch (error) {
            console.log("Error during login", error)
            throw error
        } finally {
            setIsLoading(false)
        }
    }

    const logout = async () => {
        try {
            await AsyncStorage.removeItem("user")
            setUser(null)
        } catch (error) {
            console.log("Error during logout", error)
        }
    }

    const updateProfile = async (userData: Partial<User>) => {
        try {
            if (user) {
                const updatedUser = { ...user, ...userData }
                setUser(updatedUser)
                await AsyncStorage.setItem("user", JSON.stringify(updatedUser))
                console.log("Perfil actualizado:", updatedUser)
                return updatedUser
            }
        } catch (error) {
            console.log("Error updating profile", error)
            throw error
        }
    }

    return (
        <AuthContext.Provider value={{ user, isLoading, login, logout, updateProfile }}>{children}</AuthContext.Provider>
    )
}

export const useAuth = () => {
    const context = useContext(AuthContext)
    if (context === undefined) {
        throw new Error("useAuth must be used within an AuthProvider")
    }
    return context
}

