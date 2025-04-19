"use client"

import { useState } from "react"
import { View, Text, StyleSheet, FlatList, TouchableOpacity, TextInput, ActivityIndicator } from "react-native"
import { useTheme } from "../context/ThemeContext"
import { useAuth } from "../context/AuthContext"
import { useNavigation } from "@react-navigation/native"
import { Ionicons } from "@expo/vector-icons"

// Datos de ejemplo para observadores
const observadoresData = [
  {
    id: "1",
    estudiante: "Claudio Rodríguez",
    fecha: "2025-04-06",
    descripcion: "LLEGÓ TARDE A CLASE",
    trimestre: "I",
    nivelFalta: "Regular",
  },
  {
    id: "2",
    estudiante: "María González",
    fecha: "2025-04-05",
    descripcion: "NO ENTREGÓ TAREA",
    trimestre: "I",
    nivelFalta: "Leve",
  },
  {
    id: "3",
    estudiante: "Juan Pérez",
    fecha: "2025-04-04",
    descripcion: "COMPORTAMIENTO INADECUADO",
    trimestre: "I",
    nivelFalta: "Grave",
  },
]

const ObservadoresScreen = () => {
  const { colors } = useTheme()
  const { user } = useAuth()
  const navigation = useNavigation()
  const [searchText, setSearchText] = useState("")
  const [loading, setLoading] = useState(false)
  const [observadores, setObservadores] = useState(
    user?.role === "estudiante" ? observadoresData.filter((o) => o.estudiante.includes("Claudio")) : observadoresData,
  )

  const handleSearch = () => {
    setLoading(true)
    // Simulación de búsqueda
    setTimeout(() => {
      if (searchText) {
        const filtered = observadoresData.filter(
          (item) =>
            item.estudiante.toLowerCase().includes(searchText.toLowerCase()) ||
            item.descripcion.toLowerCase().includes(searchText.toLowerCase()),
        )
        setObservadores(filtered)
      } else {
        setObservadores(
          user?.role === "estudiante"
            ? observadoresData.filter((o) => o.estudiante.includes("Claudio"))
            : observadoresData,
        )
      }
      setLoading(false)
    }, 500)
  }

  const renderItem = ({ item }) => {
    // Determinar el color según el nivel de falta
    const getNivelColor = (nivel) => {
      switch (nivel) {
        case "Leve":
          return colors.success || "#4CAF50"
        case "Regular":
          return colors.warning || "#FFC107"
        case "Grave":
          return colors.error || "#F44336"
        default:
          return colors.text
      }
    }

    return (
      <TouchableOpacity
        style={[
          styles.observadorItem,
          {
            backgroundColor: colors.card,
            borderLeftWidth: 4,
            borderLeftColor: getNivelColor(item.nivelFalta),
            borderColor: colors.border,
            borderWidth: 1,
            borderLeftWidth: 4,
          },
        ]}
        onPress={() => navigation.navigate("ObservadorDetail" as never, { observador: item } as never)}
      >
        <View style={styles.observadorHeader}>
          <Text style={[styles.estudiante, { color: colors.text }]}>{item.estudiante}</Text>
          <Text style={[styles.fecha, { color: colors.text + "CC" }]}>{item.fecha}</Text>
        </View>
        <Text style={[styles.descripcion, { color: colors.text }]}>{item.descripcion}</Text>
        <View style={styles.observadorFooter}>
          <Text style={[styles.trimestre, { color: colors.text + "CC" }]}>Trimestre: {item.trimestre}</Text>
          <Text
            style={[
              styles.nivelFalta,
              {
                color: getNivelColor(item.nivelFalta),
                fontWeight: "bold",
              },
            ]}
          >
            {item.nivelFalta}
          </Text>
        </View>
      </TouchableOpacity>
    )
  }

  const handleDescargarObservador = () => {
    navigation.navigate("DescargarObservador" as never)
  }

  return (
    <View style={[styles.container, { backgroundColor: colors.background }]}>
      <View style={[styles.searchContainer, { backgroundColor: colors.card }]}>
        <TextInput
          style={[styles.searchInput, { backgroundColor: colors.background, color: colors.text }]}
          placeholder="Buscar observación..."
          placeholderTextColor={colors.text + "80"}
          value={searchText}
          onChangeText={setSearchText}
          onSubmitEditing={handleSearch}
        />
        <TouchableOpacity style={styles.searchButton} onPress={handleSearch}>
          <Ionicons name="search" size={20} color="#fff" />
        </TouchableOpacity>
      </View>

      <View style={styles.actionButtonsContainer}>
        {user?.role === "docente" && (
          <TouchableOpacity
            style={[styles.addButton, { backgroundColor: colors.primary }]}
            onPress={() => navigation.navigate("CrearObservador" as never)}
          >
            <Ionicons name="add" size={24} color="#fff" />
            <Text style={styles.addButtonText}>Crear Observación</Text>
          </TouchableOpacity>
        )}

        {user?.role === "docente" && (
          <TouchableOpacity
            style={[styles.downloadButton, { backgroundColor: colors.accent }]}
            onPress={handleDescargarObservador}
          >
            <Ionicons name="download" size={24} color="#fff" />
            <Text style={styles.downloadButtonText}>Descargar Observador</Text>
          </TouchableOpacity>
        )}
      </View>

      {loading ? (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color={colors.primary} />
        </View>
      ) : observadores.length > 0 ? (
        <FlatList
          data={observadores}
          renderItem={renderItem}
          keyExtractor={(item) => item.id}
          contentContainerStyle={styles.listContainer}
        />
      ) : (
        <View style={styles.emptyContainer}>
          <Ionicons name="document-text-outline" size={60} color={colors.text + "50"} />
          <Text style={[styles.emptyText, { color: colors.text }]}>No se encontraron observaciones</Text>
        </View>
      )}
    </View>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  searchContainer: {
    flexDirection: "row",
    padding: 15,
    alignItems: "center",
  },
  searchInput: {
    flex: 1,
    height: 40,
    borderRadius: 20,
    paddingHorizontal: 15,
    marginRight: 10,
  },
  searchButton: {
    backgroundColor: "#2196F3",
    width: 40,
    height: 40,
    borderRadius: 20,
    justifyContent: "center",
    alignItems: "center",
  },
  actionButtonsContainer: {
    marginHorizontal: 15,
    marginBottom: 15,
  },
  addButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    padding: 12,
    borderRadius: 10,
    marginBottom: 10,
  },
  addButtonText: {
    color: "#fff",
    fontWeight: "bold",
    marginLeft: 8,
  },
  downloadButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    padding: 12,
    borderRadius: 10,
  },
  downloadButtonText: {
    color: "#fff",
    fontWeight: "bold",
    marginLeft: 8,
  },
  listContainer: {
    padding: 15,
  },
  observadorItem: {
    borderRadius: 10,
    padding: 15,
    marginBottom: 15,
  },
  observadorHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 10,
  },
  estudiante: {
    fontWeight: "bold",
    fontSize: 16,
  },
  fecha: {
    fontSize: 14,
  },
  descripcion: {
    fontSize: 15,
    marginBottom: 10,
  },
  observadorFooter: {
    flexDirection: "row",
    justifyContent: "space-between",
  },
  trimestre: {
    fontSize: 14,
  },
  nivelFalta: {
    fontSize: 14,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
  },
  emptyContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    padding: 20,
  },
  emptyText: {
    marginTop: 10,
    fontSize: 16,
    textAlign: "center",
  },
})

export default ObservadoresScreen

