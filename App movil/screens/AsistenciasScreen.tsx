"use client"

import { useState } from "react"
import { View, Text, StyleSheet, FlatList, TouchableOpacity, TextInput, ActivityIndicator } from "react-native"
import { useTheme } from "../context/ThemeContext"
import { useAuth } from "../context/AuthContext"
import { useNavigation } from "@react-navigation/native"
import { Ionicons } from "@expo/vector-icons"

// Datos de ejemplo para asistencias
const asistenciasData = [
  {
    id: "1",
    materia: "FISICA",
    curso: "703",
    fecha: "2025-02-24 21:39:00",
    trimestre: "I",
  },
  {
    id: "2",
    materia: "INFORMATICA",
    curso: "703",
    fecha: "2025-03-09 21:45:00",
    trimestre: "II",
  },
  {
    id: "3",
    materia: "INGLES",
    curso: "1002",
    fecha: "2025-03-18 21:44:00",
    trimestre: "II",
  },
]

const AsistenciasScreen = () => {
  const { colors } = useTheme()
  const { user } = useAuth()
  const navigation = useNavigation()
  const [searchText, setSearchText] = useState("")
  const [loading, setLoading] = useState(false)
  const [asistencias, setAsistencias] = useState(asistenciasData)

  const handleSearch = () => {
    setLoading(true)
    // Simulación de búsqueda
    setTimeout(() => {
      if (searchText) {
        const filtered = asistenciasData.filter(
          (item) =>
            item.materia.toLowerCase().includes(searchText.toLowerCase()) ||
            item.curso.toLowerCase().includes(searchText.toLowerCase()),
        )
        setAsistencias(filtered)
      } else {
        setAsistencias(asistenciasData)
      }
      setLoading(false)
    }, 500)
  }

  const renderItem = ({ item }) => (
    <TouchableOpacity
      style={[
        styles.asistenciaItem,
        {
          backgroundColor: colors.card,
          borderColor: colors.border,
          borderWidth: 1,
          borderLeftWidth: 4,
          borderLeftColor: colors.accent,
        },
      ]}
      onPress={() => navigation.navigate("AsistenciaDetail" as never, { asistencia: item } as never)}
    >
      <View style={styles.asistenciaHeader}>
        <View style={[styles.materiaContainer, { backgroundColor: colors.accent + "20" }]}>
          <Text style={[styles.materia, { color: colors.text }]}>{item.materia}</Text>
        </View>
        <Text style={[styles.curso, { color: colors.text }]}>Curso: {item.curso}</Text>
      </View>
      <View style={styles.asistenciaFooter}>
        <Text style={[styles.fecha, { color: colors.text + "CC" }]}>
          <Ionicons name="time-outline" size={14} color={colors.text + "CC"} /> {item.fecha}
        </Text>
        <Text style={[styles.trimestre, { color: colors.text + "CC" }]}>
          <Ionicons name="calendar-outline" size={14} color={colors.text + "CC"} /> Trimestre: {item.trimestre}
        </Text>
      </View>
    </TouchableOpacity>
  )

  return (
    <View style={[styles.container, { backgroundColor: colors.background }]}>
      <View style={[styles.searchContainer, { backgroundColor: colors.card }]}>
        <TextInput
          style={[styles.searchInput, { backgroundColor: colors.background, color: colors.text }]}
          placeholder="Buscar asistencia..."
          placeholderTextColor={colors.text + "80"}
          value={searchText}
          onChangeText={setSearchText}
          onSubmitEditing={handleSearch}
        />
        <TouchableOpacity style={styles.searchButton} onPress={handleSearch}>
          <Ionicons name="search" size={20} color="#fff" />
        </TouchableOpacity>
      </View>

      {user?.role === "docente" && (
        <TouchableOpacity
          style={[styles.addButton, { backgroundColor: colors.primary }]}
          onPress={() => navigation.navigate("CrearAsistencia" as never)}
        >
          <Ionicons name="add" size={24} color="#fff" />
          <Text style={styles.addButtonText}>Registrar Asistencia</Text>
        </TouchableOpacity>
      )}

      {loading ? (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color={colors.primary} />
        </View>
      ) : asistencias.length > 0 ? (
        <FlatList
          data={asistencias}
          renderItem={renderItem}
          keyExtractor={(item) => item.id}
          contentContainerStyle={styles.listContainer}
        />
      ) : (
        <View style={styles.emptyContainer}>
          <Ionicons name="calendar-outline" size={60} color={colors.text + "50"} />
          <Text style={[styles.emptyText, { color: colors.text }]}>No se encontraron registros de asistencia</Text>
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
  addButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    padding: 12,
    marginHorizontal: 15,
    marginBottom: 15,
    borderRadius: 10,
  },
  addButtonText: {
    color: "#fff",
    fontWeight: "bold",
    marginLeft: 8,
  },
  listContainer: {
    padding: 15,
  },
  asistenciaItem: {
    borderRadius: 10,
    padding: 15,
    marginBottom: 15,
  },
  asistenciaHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 10,
  },
  materia: {
    fontWeight: "bold",
    fontSize: 16,
  },
  curso: {
    fontSize: 14,
  },
  asistenciaFooter: {
    flexDirection: "row",
    justifyContent: "space-between",
  },
  fecha: {
    fontSize: 14,
  },
  trimestre: {
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
  materiaContainer: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 4,
  },
})

export default AsistenciasScreen

