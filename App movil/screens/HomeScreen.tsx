"use client"
import { View, Text, StyleSheet, ScrollView, Image, TouchableOpacity } from "react-native"
import { useTheme } from "../context/ThemeContext"
import { useAuth } from "../context/AuthContext"
import { useNavigation } from "@react-navigation/native"
import { Ionicons } from "@expo/vector-icons"

const HomeScreen = () => {
  const { colors } = useTheme()
  const { user } = useAuth()
  const navigation = useNavigation()

  return (
    <ScrollView style={[styles.container, { backgroundColor: colors.background }]}>
      <View style={styles.header}>
        <Text style={[styles.welcomeText, { color: colors.text }]}>
          ¡Hola, Bienvenid@, {user?.nombre} {user?.apellido}!
        </Text>
      </View>

      <View style={styles.heroSection}>
        <View style={styles.heroContent}>
          <Text style={[styles.heroTitle, { color: colors.text }]}>
            UN ESPACIO DIGITAL DISEÑADO PARA ESTUDIANTES, DOCENTES Y DIRECTIVOS.
          </Text>
        </View>
        <Image source={require("../assets/hero-image.png")} style={styles.heroImage} resizeMode="contain" />
      </View>

      <Text style={[styles.sectionTitle, { color: colors.text }]}>Accesos Rápidos</Text>

      <View style={styles.cardsContainer}>
        <TouchableOpacity
          style={[
            styles.card,
            {
              backgroundColor: colors.card,
              borderColor: colors.border,
              borderWidth: 1,
            },
          ]}
          onPress={() => navigation.navigate("Observadores" as never)}
        >
          <View style={[styles.iconContainer, { backgroundColor: colors.accent + "20" }]}>
            <Ionicons name="document-text" size={32} color={colors.accent} />
          </View>
          <Text style={[styles.cardTitle, { color: colors.text }]}>Observadores</Text>
          <Text style={[styles.cardDescription, { color: colors.text }]}>
            {user?.role === "docente" ? "Gestiona observaciones de estudiantes" : "Revisa tus observaciones"}
          </Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={[
            styles.card,
            {
              backgroundColor: colors.card,
              borderColor: colors.border,
              borderWidth: 1,
            },
          ]}
          onPress={() => navigation.navigate("Asistencias" as never)}
        >
          <View style={[styles.iconContainer, { backgroundColor: colors.accent + "20" }]}>
            <Ionicons name="calendar" size={32} color={colors.accent} />
          </View>
          <Text style={[styles.cardTitle, { color: colors.text }]}>Asistencias</Text>
          <Text style={[styles.cardDescription, { color: colors.text }]}>
            {user?.role === "docente" ? "Registra asistencia de estudiantes" : "Consulta tu registro de asistencia"}
          </Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={[
            styles.card,
            {
              backgroundColor: colors.card,
              borderColor: colors.border,
              borderWidth: 1,
            },
          ]}
          onPress={() => navigation.navigate("Profile" as never)}
        >
          <View style={[styles.iconContainer, { backgroundColor: colors.accent + "20" }]}>
            <Ionicons name="person" size={32} color={colors.accent} />
          </View>
          <Text style={[styles.cardTitle, { color: colors.text }]}>Perfil</Text>
          <Text style={[styles.cardDescription, { color: colors.text }]}>
            {user?.role === "docente" ? "Gestiona tu información y estudiantes" : "Actualiza tu información personal"}
          </Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  header: {
    padding: 20,
    paddingTop: 30,
  },
  welcomeText: {
    fontSize: 22,
    fontWeight: "bold",
  },
  heroSection: {
    flexDirection: "row",
    padding: 20,
    alignItems: "center",
  },
  heroContent: {
    flex: 1,
  },
  heroTitle: {
    fontSize: 24,
    fontWeight: "bold",
    lineHeight: 32,
  },
  heroImage: {
    width: 150,
    height: 150,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: "bold",
    marginHorizontal: 20,
    marginTop: 20,
    marginBottom: 10,
  },
  cardsContainer: {
    padding: 10,
  },
  card: {
    borderRadius: 10,
    padding: 20,
    marginBottom: 15,
    elevation: 2,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: "bold",
    marginTop: 10,
    marginBottom: 5,
  },
  cardDescription: {
    fontSize: 14,
    opacity: 0.8,
  },
  iconContainer: {
    width: 60,
    height: 60,
    borderRadius: 30,
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 10,
  },
})

export default HomeScreen

