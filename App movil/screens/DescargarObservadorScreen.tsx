"use client"

import { useState } from "react"
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, Image, ActivityIndicator } from "react-native"
import { useTheme } from "../context/ThemeContext"
import { useNavigation } from "@react-navigation/native"
import { Ionicons } from "@expo/vector-icons"
import { Picker } from "@react-native-picker/picker"

// Datos de ejemplo para los cursos
const cursosData = ["701", "702", "703", "801", "1001", "1002"]

// Datos de ejemplo para los estudiantes
const estudiantesData = {
  "701": [
    { id: "1", nombre: "Pedro Torrez" },
    { id: "2", nombre: "Alejandro Díaz" },
  ],
  "702": [
    { id: "3", nombre: "Sebastián Cardenas" },
    { id: "4", nombre: "Jaime Bolaños" },
  ],
  "703": [
    { id: "5", nombre: "María González" },
    { id: "6", nombre: "Ana Martínez" },
    { id: "7", nombre: "Claudio Rodríguez" },
  ],
}

// Datos de ejemplo para las observaciones
const observacionesData = [
  {
    id: "1",
    fecha: "2025-04-06",
    descripcion: "LLEGÓ TARDE A CLASE",
    trimestre: "I",
    nivelFalta: "Regular",
    docente: "Juan Paez",
  },
  {
    id: "2",
    fecha: "2025-04-05",
    descripcion: "NO ENTREGÓ TAREA",
    trimestre: "I",
    nivelFalta: "Leve",
    docente: "Juan Paez",
  },
  {
    id: "3",
    fecha: "2025-04-04",
    descripcion: "COMPORTAMIENTO INADECUADO",
    trimestre: "I",
    nivelFalta: "Grave",
    docente: "Juan Paez",
  },
]

// Datos de ejemplo para el estudiante seleccionado
const estudiantePerfilData = {
  nombre: "Claudio Rodríguez",
  documento: "1029143097",
  curso: "703",
  email: "claudio.rodriguez@gmail.com",
  telefono: "3057645321",
  direccion: "Calle 6",
  rh: "A+",
  eps: "Compensar",
  fechaNacimiento: "2006-05-18",
  enfermedades: "Ninguna",
}

const DescargarObservadorScreen = () => {
  const { colors } = useTheme()
  const navigation = useNavigation()

  const [cursoSeleccionado, setCursoSeleccionado] = useState("")
  const [estudianteSeleccionado, setEstudianteSeleccionado] = useState("")
  const [loading, setLoading] = useState(false)
  const [mostrarPreview, setMostrarPreview] = useState(false)

  const handleBuscar = () => {
    if (!cursoSeleccionado || !estudianteSeleccionado) {
      alert("Por favor selecciona un curso y un estudiante")
      return
    }

    setLoading(true)
    // Simulación de carga
    setTimeout(() => {
      setLoading(false)
      setMostrarPreview(true)
    }, 1000)
  }

  const handleDescargar = () => {
    setLoading(true)
    // Simulación de descarga
    setTimeout(() => {
      setLoading(false)
      alert("Observador descargado correctamente")
      navigation.goBack()
    }, 1500)
  }

  // Función para obtener el color según el nivel de falta
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
    <ScrollView style={[styles.container, { backgroundColor: colors.background }]}>
      <View style={[styles.card, { backgroundColor: colors.card }]}>
        <Text style={[styles.title, { color: colors.text }]}>Descargar Observador Estudiantil</Text>

        {!mostrarPreview ? (
          <>
            <View style={styles.formGroup}>
              <Text style={[styles.label, { color: colors.text }]}>Curso:</Text>
              <View
                style={[styles.pickerContainer, { backgroundColor: colors.background, borderColor: colors.border }]}
              >
                <Picker
                  selectedValue={cursoSeleccionado}
                  onValueChange={(value) => {
                    setCursoSeleccionado(value)
                    setEstudianteSeleccionado("")
                  }}
                  style={{ color: colors.text }}
                  dropdownIconColor={colors.text}
                >
                  <Picker.Item label="Seleccione un curso" value="" />
                  {cursosData.map((curso) => (
                    <Picker.Item key={curso} label={curso} value={curso} />
                  ))}
                </Picker>
              </View>
            </View>

            <View style={styles.formGroup}>
              <Text style={[styles.label, { color: colors.text }]}>Estudiante:</Text>
              <View
                style={[styles.pickerContainer, { backgroundColor: colors.background, borderColor: colors.border }]}
              >
                <Picker
                  selectedValue={estudianteSeleccionado}
                  onValueChange={(value) => setEstudianteSeleccionado(value)}
                  style={{ color: colors.text }}
                  dropdownIconColor={colors.text}
                  enabled={!!cursoSeleccionado}
                >
                  <Picker.Item
                    label={cursoSeleccionado ? "Seleccione un estudiante" : "Primero seleccione un curso"}
                    value=""
                  />
                  {cursoSeleccionado &&
                    estudiantesData[cursoSeleccionado]?.map((estudiante) => (
                      <Picker.Item key={estudiante.id} label={estudiante.nombre} value={estudiante.id} />
                    ))}
                </Picker>
              </View>
            </View>

            <TouchableOpacity
              style={[styles.buscarButton, { backgroundColor: colors.primary }]}
              onPress={handleBuscar}
              disabled={!cursoSeleccionado || !estudianteSeleccionado || loading}
            >
              {loading ? (
                <ActivityIndicator color="#fff" />
              ) : (
                <>
                  <Ionicons name="search" size={20} color="#fff" />
                  <Text style={styles.buscarButtonText}>Buscar</Text>
                </>
              )}
            </TouchableOpacity>
          </>
        ) : (
          // Vista previa del observador
          <View style={styles.previewContainer}>
            <View style={styles.previewHeader}>
              <Text style={[styles.previewTitle, { color: colors.text }]}>OBSERVADOR ESTUDIANTIL</Text>
              <Text style={[styles.previewSubtitle, { color: colors.text }]}>INSTITUCIÓN EDUCATIVA</Text>
            </View>

            {/* Datos del estudiante */}
            <View style={[styles.perfilContainer, { backgroundColor: colors.card, borderColor: colors.border }]}>
              <View style={styles.perfilHeader}>
                <Image source={require("../assets/default-avatar.png")} style={styles.perfilAvatar} />
                <View style={styles.perfilInfo}>
                  <Text style={[styles.perfilNombre, { color: colors.text }]}>{estudiantePerfilData.nombre}</Text>
                  <Text style={[styles.perfilDetalle, { color: colors.text }]}>
                    Documento: {estudiantePerfilData.documento}
                  </Text>
                  <Text style={[styles.perfilDetalle, { color: colors.text }]}>
                    Curso: {estudiantePerfilData.curso}
                  </Text>
                </View>
              </View>

              <View style={styles.perfilDetalles}>
                <View style={styles.perfilRow}>
                  <Text style={[styles.perfilLabel, { color: colors.text }]}>Email:</Text>
                  <Text style={[styles.perfilValue, { color: colors.text }]}>{estudiantePerfilData.email}</Text>
                </View>
                <View style={styles.perfilRow}>
                  <Text style={[styles.perfilLabel, { color: colors.text }]}>Teléfono:</Text>
                  <Text style={[styles.perfilValue, { color: colors.text }]}>{estudiantePerfilData.telefono}</Text>
                </View>
                <View style={styles.perfilRow}>
                  <Text style={[styles.perfilLabel, { color: colors.text }]}>Dirección:</Text>
                  <Text style={[styles.perfilValue, { color: colors.text }]}>{estudiantePerfilData.direccion}</Text>
                </View>
                <View style={styles.perfilRow}>
                  <Text style={[styles.perfilLabel, { color: colors.text }]}>RH:</Text>
                  <Text style={[styles.perfilValue, { color: colors.text }]}>{estudiantePerfilData.rh}</Text>
                </View>
                <View style={styles.perfilRow}>
                  <Text style={[styles.perfilLabel, { color: colors.text }]}>EPS:</Text>
                  <Text style={[styles.perfilValue, { color: colors.text }]}>{estudiantePerfilData.eps}</Text>
                </View>
                <View style={styles.perfilRow}>
                  <Text style={[styles.perfilLabel, { color: colors.text }]}>Fecha de Nacimiento:</Text>
                  <Text style={[styles.perfilValue, { color: colors.text }]}>
                    {estudiantePerfilData.fechaNacimiento}
                  </Text>
                </View>
              </View>
            </View>

            {/* Observaciones */}
            <Text style={[styles.observacionesTitle, { color: colors.text }]}>OBSERVACIONES</Text>

            {observacionesData.map((observacion) => (
              <View
                key={observacion.id}
                style={[
                  styles.observacionItem,
                  {
                    backgroundColor: colors.card,
                    borderColor: colors.border,
                    borderLeftColor: getNivelColor(observacion.nivelFalta),
                  },
                ]}
              >
                <View style={styles.observacionHeader}>
                  <Text style={[styles.observacionFecha, { color: colors.text }]}>{observacion.fecha}</Text>
                  <Text style={[styles.observacionNivel, { color: getNivelColor(observacion.nivelFalta) }]}>
                    {observacion.nivelFalta}
                  </Text>
                </View>

                <Text style={[styles.observacionDescripcion, { color: colors.text }]}>{observacion.descripcion}</Text>

                <View style={styles.observacionFooter}>
                  <Text style={[styles.observacionTrimestre, { color: colors.text }]}>
                    Trimestre: {observacion.trimestre}
                  </Text>
                  <Text style={[styles.observacionDocente, { color: colors.text }]}>
                    Docente: {observacion.docente}
                  </Text>
                </View>
              </View>
            ))}

            <View style={styles.buttonContainer}>
              <TouchableOpacity
                style={[styles.descargarButton, { backgroundColor: colors.primary }]}
                onPress={handleDescargar}
                disabled={loading}
              >
                {loading ? (
                  <ActivityIndicator color="#fff" />
                ) : (
                  <>
                    <Ionicons name="download" size={20} color="#fff" />
                    <Text style={styles.descargarButtonText}>Descargar PDF</Text>
                  </>
                )}
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.cancelarButton, { backgroundColor: colors.secondary }]}
                onPress={() => setMostrarPreview(false)}
                disabled={loading}
              >
                <Text style={[styles.cancelarButtonText, { color: colors.text }]}>Volver</Text>
              </TouchableOpacity>
            </View>
          </View>
        )}
      </View>

      <TouchableOpacity
        style={[styles.backButton, { backgroundColor: colors.primary }]}
        onPress={() => navigation.goBack()}
      >
        <Ionicons name="arrow-back" size={20} color="#fff" />
        <Text style={styles.backButtonText}>Volver a Observadores</Text>
      </TouchableOpacity>
    </ScrollView>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 15,
  },
  card: {
    borderRadius: 10,
    padding: 20,
    marginBottom: 15,
  },
  title: {
    fontSize: 20,
    fontWeight: "bold",
    marginBottom: 20,
    textAlign: "center",
  },
  formGroup: {
    marginBottom: 15,
  },
  label: {
    fontWeight: "bold",
    marginBottom: 8,
  },
  pickerContainer: {
    borderWidth: 1,
    borderRadius: 8,
    height: 45,
    justifyContent: "center",
  },
  buscarButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    padding: 15,
    borderRadius: 8,
    marginTop: 10,
  },
  buscarButtonText: {
    color: "#fff",
    fontWeight: "bold",
    marginLeft: 10,
  },
  backButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    padding: 12,
    borderRadius: 10,
    marginBottom: 30,
  },
  backButtonText: {
    color: "#fff",
    fontWeight: "bold",
    marginLeft: 8,
  },
  previewContainer: {
    marginTop: 10,
  },
  previewHeader: {
    alignItems: "center",
    marginBottom: 20,
  },
  previewTitle: {
    fontSize: 18,
    fontWeight: "bold",
  },
  previewSubtitle: {
    fontSize: 14,
    marginTop: 5,
  },
  perfilContainer: {
    borderWidth: 1,
    borderRadius: 10,
    padding: 15,
    marginBottom: 20,
  },
  perfilHeader: {
    flexDirection: "row",
    marginBottom: 15,
  },
  perfilAvatar: {
    width: 80,
    height: 80,
    borderRadius: 40,
    marginRight: 15,
  },
  perfilInfo: {
    flex: 1,
    justifyContent: "center",
  },
  perfilNombre: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 5,
  },
  perfilDetalle: {
    fontSize: 14,
    marginBottom: 3,
  },
  perfilDetalles: {
    borderTopWidth: 1,
    borderTopColor: "#eee",
    paddingTop: 15,
  },
  perfilRow: {
    flexDirection: "row",
    marginBottom: 8,
  },
  perfilLabel: {
    fontWeight: "bold",
    width: 150,
  },
  perfilValue: {
    flex: 1,
  },
  observacionesTitle: {
    fontSize: 16,
    fontWeight: "bold",
    marginBottom: 15,
  },
  observacionItem: {
    borderWidth: 1,
    borderLeftWidth: 4,
    borderRadius: 8,
    padding: 15,
    marginBottom: 15,
  },
  observacionHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 10,
  },
  observacionFecha: {
    fontWeight: "bold",
  },
  observacionNivel: {
    fontWeight: "bold",
  },
  observacionDescripcion: {
    marginBottom: 10,
  },
  observacionFooter: {
    flexDirection: "row",
    justifyContent: "space-between",
  },
  observacionTrimestre: {
    fontSize: 12,
  },
  observacionDocente: {
    fontSize: 12,
  },
  buttonContainer: {
    marginTop: 20,
  },
  descargarButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    padding: 15,
    borderRadius: 8,
    marginBottom: 10,
  },
  descargarButtonText: {
    color: "#fff",
    fontWeight: "bold",
    marginLeft: 10,
  },
  cancelarButton: {
    padding: 15,
    borderRadius: 8,
    alignItems: "center",
  },
  cancelarButtonText: {
    fontWeight: "bold",
  },
})

export default DescargarObservadorScreen

