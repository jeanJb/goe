"use client"

import { useState } from "react"
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Image,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  Alert,
  ActivityIndicator,
} from "react-native"
import { useTheme } from "../../context/ThemeContext"
import { useAuth } from "../../context/AuthContext"
import { Ionicons } from "@expo/vector-icons"
import {login} from "../../services/api" 

const LoginScreen = () => {
  const { colors, isDark, toggleTheme } = useTheme()
  const { login } = useAuth()
  const [email, setEmail] = useState("")
  const [password, setPassword] = useState("")
  const [isLoading, setIsLoading] = useState(false)

  const handleLogin = async () => {
    if (!email || !password) {
      Alert.alert("Error", "Por favor ingresa email y contraseña")
      return
    }

    setIsLoading(true)
    try {
      await login(email, password)
    } catch (error) {
      Alert.alert("Error de inicio de sesión", "Credenciales inválidas")
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <KeyboardAvoidingView behavior={Platform.OS === "ios" ? "padding" : "height"} style={{ flex: 1 }}>
      <ScrollView contentContainerStyle={[styles.container, { backgroundColor: colors.background }]}>
        <View style={styles.themeToggle}>
          <TouchableOpacity onPress={toggleTheme}>
            <Ionicons name={isDark ? "sunny-outline" : "moon-outline"} size={24} color={colors.text} />
          </TouchableOpacity>
        </View>

        <View style={styles.logoContainer}>
          <Image source={require("../../assets/logo.png")} style={styles.logo} resizeMode="contain" />
        </View>

        <Text style={[styles.title, { color: colors.text }]}>Bienvenido a GOE</Text>

        <Text style={[styles.subtitle, { color: colors.text }]}>Gestión de Observadores y Asistencias</Text>

        <View style={styles.formContainer}>
          <View
            style={[
              styles.inputContainer,
              { backgroundColor: colors.inputBackground || colors.card, borderColor: colors.border },
            ]}
          >
            <Ionicons name="mail-outline" size={20} color={colors.text} style={styles.inputIcon} />
            <TextInput
              style={[styles.input, { color: colors.text }]}
              placeholder="Correo electrónico"
              placeholderTextColor={isDark ? "#888" : "#999"}
              value={email}
              onChangeText={setEmail}
              autoCapitalize="none"
              keyboardType="email-address"
            />
          </View>

          <View
            style={[
              styles.inputContainer,
              { backgroundColor: colors.inputBackground || colors.card, borderColor: colors.border },
            ]}
          >
            <Ionicons name="lock-closed-outline" size={20} color={colors.text} style={styles.inputIcon} />
            <TextInput
              style={[styles.input, { color: colors.text }]}
              placeholder="Contraseña"
              placeholderTextColor={isDark ? "#888" : "#999"}
              value={password}
              onChangeText={setPassword}
              secureTextEntry
            />
          </View>

          <TouchableOpacity
            style={[styles.loginButton, { backgroundColor: colors.primary }]}
            onPress={handleLogin}
            disabled={isLoading}
          >
            {isLoading ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <Text style={styles.loginButtonText}>Iniciar Sesión</Text>
            )}
          </TouchableOpacity>

          {/* <Text style={[styles.helpText, { color: colors.text }]}>Para probar la aplicación, usa:</Text>
          <Text style={[styles.credentialText, { color: colors.text }]}>Docente: docente@example.com</Text>
          <Text style={[styles.credentialText, { color: colors.text }]}>Estudiante: estudiante@example.com</Text>
          <Text style={[styles.credentialText, { color: colors.text }]}>(Cualquier contraseña funcionará)</Text> */}
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  )
}

const styles = StyleSheet.create({
  container: {
    flexGrow: 1,
    justifyContent: "center",
    padding: 20,
  },
  themeToggle: {
    position: "absolute",
    top: 40,
    right: 20,
  },
  logoContainer: {
    alignItems: "center",
    marginBottom: 30,
  },
  logo: {
    width: 120,
    height: 120,
  },
  title: {
    fontSize: 28,
    fontWeight: "bold",
    textAlign: "center",
    marginBottom: 10,
  },
  subtitle: {
    fontSize: 16,
    textAlign: "center",
    marginBottom: 30,
  },
  formContainer: {
    width: "100%",
  },
  inputContainer: {
    flexDirection: "row",
    alignItems: "center",
    borderWidth: 1,
    borderRadius: 8,
    marginBottom: 16,
    paddingHorizontal: 10,
  },
  inputIcon: {
    marginRight: 10,
  },
  input: {
    flex: 1,
    height: 50,
  },
  loginButton: {
    height: 50,
    borderRadius: 8,
    justifyContent: "center",
    alignItems: "center",
    marginTop: 10,
    marginBottom: 20,
  },
  loginButtonText: {
    color: "#fff",
    fontSize: 16,
    fontWeight: "bold",
  },
  helpText: {
    textAlign: "center",
    marginTop: 20,
    fontSize: 14,
  },
  credentialText: {
    textAlign: "center",
    fontSize: 14,
    marginTop: 5,
  },
})

export default LoginScreen

