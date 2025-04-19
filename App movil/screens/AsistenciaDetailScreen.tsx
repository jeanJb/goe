"use client"

import { useState, useEffect } from "react"
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  FlatList,
  Alert,
  Modal,
  ActivityIndicator,
} from "react-native"
import { useTheme } from "../context/ThemeContext"
import { useAuth } from "../context/AuthContext"
import { useNavigation, useRoute } from "@react-navigation/native"
import { Ionicons } from "@expo/vector-icons"
import { asistenciasService } from "../services/api"

// Tipos de asistencia
type AsistenciaStatus = "Asistió" | "Retardo" | "Excusa" | "Falla"

const AsistenciaDetailScreen = () => {
  const { colors } = useTheme()
  const { user, token } = useAuth()
  const navigation = useNavigation()
  const route = useRoute()
  const { asistenciaId } = route.params as { asistenciaId: string }

  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const [asistencia, setAsistencia] = useState<any>(null)
  const [editMode, setEditMode] = useState(false)
  const [selectedEstudiante, setSelectedEstudiante] = useState<any>(null)
  const [showEditModal, setShowEditModal] = useState(false)
  const [showDeleteModal, setShowDeleteModal] = useState(false)
  const [saving, setSaving] = useState(false)
  const [deleting, setDeleting] = useState(false)
  const [estudiantes, setEstudiantes] = useState<any[]>([])

  // Cargar detalle de la asistencia
  useEffect(() => {
    const loadAsistenciaDetail = async () => {
      if (!token || !asistenciaId) return

      try {
        setLoading(true)
        setError(null)

        const response = await asistenciasService.getById(asistenciaId, token)

        if (response.success && response.data) {
          setAsistencia(response.data)
          if (response.data.estudiantes) {
            setEstudiantes(response.data.estudiantes)
          }
        } else {
          setError(response.error || "No se pudo cargar el detalle de la asistencia")
        }
      } catch (error: any) {
        console.error("Error cargando detalle de la asistencia:", error)
        setError("Error de conexión. Intenta de nuevo más tarde.")
      } finally {
        setLoading(false)
      }
    }

    loadAsistenciaDetail()
  }, [asistenciaId, token])

  // Función para obtener el color según el estado de asistencia
  const getAsistenciaColor = (estado: AsistenciaStatus) => {
    switch (estado) {
      case "Asistió":
        return colors.success || "#4CAF50"
      case "Retardo":
        return colors.warning || "#FFC107"
      case "Excusa":
        return colors.info || "#2196F3"
      case "Falla":
        return colors.error || "#F44336"
      default:
        return colors.text
    }
  }

  const handleEditMode = () => {
    if (editMode) {
      // Guardar cambios
      handleSaveChanges()
    } else {
      setEditMode(true)
    }
  }

  const handleEditEstudiante = (estudiante: any) => {
    setSelectedEstudiante(estudiante)
    setShowEditModal(true)
  }

  const cambiarAsistencia = (nuevoEstado: AsistenciaStatus) => {
    if (selectedEstudiante) {
      setEstudiantes(
        estudiantes.map((est) => (est.id === selectedEstudiante.id ? { ...est, asistencia: nuevoEstado } : est)),
      )
      setShowEditModal(false)
    }
  }

  const handleSaveChanges = async () => {
    if (!token || !asistenciaId) return

    try {
      setSaving(true)

      const estudiantesData = estudiantes.map((est) => ({
        id: est.id,
        asistencia: est.asistencia,
        justificacion: est.justificacion || "",
      }))

      const response = await asistenciasService.update(asistenciaId, { estudiantes: estudiantesData }, token)

      if (response.success) {
        Alert.alert("Éxito", "Asistencia actualizada correctamente")
        setEditMode(false)
      } else {
        Alert.alert("Error", response.error || "No se pudo actualizar la asistencia")
      }
    } catch (error: any) {
      console.error("Error actualizando asistencia:", error)
      Alert.alert("Error", "Error de conexión. Intenta de nuevo más tarde.")
    } finally {
      setSaving(false)
    }
  }

  // Función para eliminar la asistencia
  const handleDelete = async () => {
    if (!token || !asistenciaId) return

    try {
      setDeleting(true)
      const response = await asistenciasService.delete(asistenciaId, token)

      if (response.success) {
        Alert.alert("Éxito", "Asistencia eliminada correctamente", [{ text: "OK", onPress: () => navigation.goBack() }])
      } else {
        Alert.alert("Error", response.error || "No se pudo eliminar la asistencia")
        setShowDeleteModal(false)
      }
    } catch (error: any) {
      console.error("Error eliminando asistencia:", error)
      Alert.alert("Error", "Error de conexión. Intenta de nuevo más tarde.")
      setShowDeleteModal(false)
    } finally {
      setDeleting(false)
    }
  }

  if (loading) {
    return (
      <View style={[styles.loadingContainer, { backgroundColor: colors.background }]}>
        <ActivityIndicator size="large" color={colors.primary} />
      </View>
    )
  }

  if (error || !asistencia) {
    return (
      <View style={[styles.errorContainer, { backgroundColor: colors.background }]}>
        <Ionicons name="alert-circle-outline" size={60} color={colors.error} />
        <Text style={[styles.errorText, { color: colors.text }]}>{error || "No se pudo cargar la asistencia"}</Text>
        <TouchableOpacity
          style={[styles.retryButton, { backgroundColor: colors.primary }]}
          onPress={() => navigation.goBack()}
        >
          <Text style={styles.retryButtonText}>Volver</Text>
        </TouchableOpacity>
      </View>
    )
  }

  const renderEstudianteItem = ({ item }: { item: any }) => (
    <View style={[styles.estudianteItem, { backgroundColor: colors.card }]}>
      <View style={styles.estudianteInfo}>
        <Text style={[styles.estudianteNombre, { color: colors.text }]}>{item.nombre}</Text>
        <View style={styles.asistenciaInfo}>
          <Text style={[styles.asistenciaStatus, { color: getAsistenciaColor(item.asistencia) }]}>
            {item.asistencia}
          </Text>
          {item.justificacion ? (
            <Text style={[styles.justificacion, { color: colors.text }]}>Justificación: {item.justificacion}</Text>
          ) : null}
        </View>
      </View>

      {editMode && user?.role === "docente" && (
        <TouchableOpacity
          style={[styles.editButton, { backgroundColor: colors.primary }]}
          onPress={() => handleEditEstudiante(item)}
        >
          <Ionicons name="pencil-outline" size={16} color="#fff" />
          <Text style={styles.editButtonText}>Editar</Text>
        </TouchableOpacity>
      )}
    </View>
  )

  return (
    <View style={[styles.container, { backgroundColor: colors.background }]}>
      <ScrollView style={styles.headerContainer}>
        <View style={[styles.card, { backgroundColor: colors.card }]}>
          <View style={styles.header}>
            <Text style={[styles.title, { color: colors.text }]}>Detalle de Asistencia</Text>
            {user?.role === "docente" && (
              <View style={styles.actionButtons}>
                <TouchableOpacity
                  style={[styles.editButton, { backgroundColor: editMode ? colors.success : colors.primary }]}
                  onPress={handleEditMode}
                  disabled={saving}
                >
                  {saving ? (
                    <ActivityIndicator size="small" color="#fff" />
                  ) : (
                    <>
                      <Ionicons name={editMode ? "save-outline" : "pencil-outline"} size={16} color="#fff" />
                      <Text style={styles.editButtonText}>{editMode ? "Guardar" : "Editar"}</Text>
                    </>
                  )}
                </TouchableOpacity>

                <TouchableOpacity
                  style={[styles.deleteButton, { backgroundColor: colors.error }]}
                  onPress={() => setShowDeleteModal(true)}
                  disabled={saving || editMode}
                >
                  <Ionicons name="trash-outline" size={16} color="#fff" />
                  <Text style={styles.deleteButtonText}>Eliminar</Text>
                </TouchableOpacity>
              </View>
            )}
          </View>

          <View style={styles.infoSection}>
            <View style={styles.infoRow}>
              <Text style={[styles.label, { color: colors.text }]}>Materia:</Text>
              <Text style={[styles.value, { color: colors.text }]}>{asistencia.materia}</Text>
            </View>

            <View style={styles.infoRow}>
              <Text style={[styles.label, { color: colors.text }]}>Curso:</Text>
              <Text style={[styles.value, { color: colors.text }]}>{asistencia.curso}</Text>
            </View>

            <View style={styles.infoRow}>
              <Text style={[styles.label, { color: colors.text }]}>Fecha y Hora:</Text>
              <Text style={[styles.value, { color: colors.text }]}>{asistencia.fecha}</Text>
            </View>

            <View style={styles.infoRow}>
              <Text style={[styles.label, { color: colors.text }]}>Trimestre:</Text>
              <Text style={[styles.value, { color: colors.text }]}>{asistencia.trimestre}</Text>
            </View>

            {user?.role === "estudiante" && asistencia.docente && (
              <View style={styles.infoRow}>
                <Text style={[styles.label, { color: colors.text }]}>Docente:</Text>
                <Text style={[styles.value, { color: colors.text }]}>{asistencia.docente}</Text>
              </View>
            )}
          </View>
        </View>

        {user?.role === "docente" ? (
          <View style={styles.estudiantesSection}>
            <Text style={[styles.sectionTitle, { color: colors.text }]}>Lista de Estudiantes</Text>
            {editMode && (
              <View style={styles.leyendaContainer}>
                <Text style={[styles.leyendaText, { color: colors.text, fontWeight: "bold" }]}>
                  Toca en "Editar" para cambiar el estado de asistencia
                </Text>
              </View>
            )}
            <FlatList
              data={estudiantes}
              renderItem={renderEstudianteItem}
              keyExtractor={(item) => item.id.toString()}
              scrollEnabled={false}
            />
          </View>
        ) : (
          <View style={[styles.card, { backgroundColor: colors.card, marginTop: 15 }]}>
            <Text style={[styles.sectionTitle, { color: colors.text }]}>Tu Asistencia</Text>
            <View style={styles.asistenciaPersonal}>
              <Text
                style={[
                  styles.asistenciaPersonalStatus,
                  { color: getAsistenciaColor(asistencia.estado || "Presente") },
                ]}
              >
                {asistencia.estado || "Presente"}
              </Text>
              {asistencia.docente && (
                <Text style={[styles.asistenciaPersonalInfo, { color: colors.text }]}>
                  Registrado por: {asistencia.docente}
                </Text>
              )}
              {asistencia.justificacion && (
                <Text style={[styles.asistenciaPersonalInfo, { color: colors.text }]}>
                  Justificación: {asistencia.justificacion}
                </Text>
              )}
            </View>
          </View>
        )}

        <TouchableOpacity
          style={[styles.backButton, { backgroundColor: colors.primary }]}
          onPress={() => navigation.goBack()}
        >
          <Ionicons name="arrow-back" size={20} color="#fff" />
          <Text style={styles.backButtonText}>Volver</Text>
        </TouchableOpacity>
      </ScrollView>

      {/* Modal para editar asistencia */}
      <Modal visible={showEditModal} transparent={true} animationType="fade">
        <View style={styles.modalContainer}>
          <View style={[styles.modalContent, { backgroundColor: colors.card }]}>
            <Text style={[styles.modalTitle, { color: colors.text }]}>
              Editar Asistencia: {selectedEstudiante?.nombre}
            </Text>

            <View style={styles.asistenciaButtons}>
              <TouchableOpacity
                style={[styles.asistenciaButton, { backgroundColor: getAsistenciaColor("Asistió") }]}
                onPress={() => cambiarAsistencia("Asistió")}
              >
                <Ionicons name="checkmark-circle" size={24} color="#fff" />
                <Text style={styles.asistenciaButtonText}>Asistió</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.asistenciaButton, { backgroundColor: getAsistenciaColor("Retardo") }]}
                onPress={() => cambiarAsistencia("Retardo")}
              >
                <Ionicons name="time" size={24} color="#fff" />
                <Text style={styles.asistenciaButtonText}>Retardo</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.asistenciaButton, { backgroundColor: getAsistenciaColor("Excusa") }]}
                onPress={() => cambiarAsistencia("Excusa")}
              >
                <Ionicons name="document-text" size={24} color="#fff" />
                <Text style={styles.asistenciaButtonText}>Excusa</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.asistenciaButton, { backgroundColor: getAsistenciaColor("Falla") }]}
                onPress={() => cambiarAsistencia("Falla")}
              >
                <Ionicons name="close-circle" size={24} color="#fff" />
                <Text style={styles.asistenciaButtonText}>Falla</Text>
              </TouchableOpacity>
            </View>

            <TouchableOpacity
              style={[styles.cancelModalButton, { backgroundColor: colors.secondary }]}
              onPress={() => setShowEditModal(false)}
            >
              <Text style={[styles.cancelModalButtonText, { color: colors.text }]}>Cancelar</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>

      {/* Modal de confirmación para eliminar */}
      <Modal visible={showDeleteModal} transparent={true} animationType="fade">
        <View style={styles.modalContainer}>
          <View style={[styles.modalContent, { backgroundColor: colors.card }]}>
            <Text style={[styles.modalTitle, { color: colors.text }]}>Eliminar Asistencia</Text>
            <Text style={[styles.modalText, { color: colors.text }]}>
              ¿Estás seguro que deseas eliminar este registro de asistencia? Esta acción no se puede deshacer.
            </Text>

            <View style={styles.modalButtons}>
              <TouchableOpacity
                style={[styles.modalButton, { backgroundColor: colors.error }]}
                onPress={handleDelete}
                disabled={deleting}
              >
                {deleting ? (
                  <ActivityIndicator color="#fff" size="small" />
                ) : (
                  <Text style={styles.modalButtonText}>Eliminar</Text>
                )}
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.modalButton, { backgroundColor: colors.secondary }]}
                onPress={() => setShowDeleteModal(false)}
                disabled={deleting}
              >
                <Text style={[styles.modalButtonText, { color: colors.text }]}>Cancelar</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
  },
  errorContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    padding: 20,
  },
  errorText: {
    marginTop: 10,
    fontSize: 16,
    textAlign: "center",
    marginBottom: 20,
  },
  retryButton: {
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 8,
  },
  retryButtonText: {
    color: "#fff",
    fontWeight: "bold",
  },
  headerContainer: {
    padding: 15,
  },
  card: {
    borderRadius: 10,
    padding: 20,
  },
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 20,
  },
  title: {
    fontSize: 20,
    fontWeight: "bold",
  },
  actionButtons: {
    flexDirection: "row",
  },
  editButton: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 6,
    paddingHorizontal: 12,
    borderRadius: 5,
    marginRight: 8,
  },
  editButtonText: {
    color: "#fff",
    marginLeft: 5,
    fontWeight: "bold",
  },
  deleteButton: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 6,
    paddingHorizontal: 12,
    borderRadius: 5,
  },
  deleteButtonText: {
    color: "#fff",
    marginLeft: 5,
    fontWeight: "bold",
  },
  infoSection: {
    marginBottom: 10,
  },
  infoRow: {
    flexDirection: "row",
    marginBottom: 10,
  },
  label: {
    fontWeight: "bold",
    width: 120,
  },
  value: {
    flex: 1,
  },
  estudiantesSection: {
    marginTop: 15,
    marginBottom: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 15,
    paddingHorizontal: 5,
  },
  leyendaContainer: {
    marginBottom: 10,
  },
  leyendaText: {
    fontSize: 14,
  },
  estudianteItem: {
    padding: 15,
    borderRadius: 8,
    marginBottom: 10,
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
  },
  estudianteInfo: {
    flex: 1,
  },
  estudianteNombre: {
    fontWeight: "bold",
    fontSize: 16,
    marginBottom: 5,
  },
  asistenciaInfo: {
    flexDirection: "row",
    alignItems: "center",
    flexWrap: "wrap",
  },
  asistenciaStatus: {
    fontWeight: "bold",
    marginRight: 10,
  },
  justificacion: {
    fontStyle: "italic",
  },
  asistenciaPersonal: {
    marginTop: 10,
  },
  asistenciaPersonalStatus: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 5,
  },
  asistenciaPersonalInfo: {
    fontStyle: "italic",
    marginBottom: 5,
  },
  backButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    padding: 12,
    borderRadius: 10,
    marginTop: 10,
    marginBottom: 30,
  },
  backButtonText: {
    color: "#fff",
    fontWeight: "bold",
    marginLeft: 8,
  },
  modalContainer: {
    flex: 1,
    backgroundColor: "rgba(0,0,0,0.5)",
    justifyContent: "center",
    alignItems: "center",
  },
  modalContent: {
    width: "90%",
    borderRadius: 10,
    padding: 20,
  },
  modalTitle: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 20,
    textAlign: "center",
  },
  modalText: {
    marginBottom: 20,
    textAlign: "center",
  },
  modalButtons: {
    flexDirection: "column",
  },
  modalButton: {
    padding: 12,
    borderRadius: 8,
    alignItems: "center",
    marginBottom: 10,
  },
  modalButtonText: {
    color: "#fff",
    fontWeight: "bold",
  },
  asistenciaButtons: {
    flexDirection: "column",
    alignItems: "stretch",
  },
  asistenciaButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    padding: 12,
    borderRadius: 8,
    marginBottom: 10,
  },
  asistenciaButtonText: {
    color: "#fff",
    fontWeight: "bold",
    marginLeft: 10,
  },
  cancelModalButton: {
    padding: 12,
    borderRadius: 8,
    alignItems: "center",
    marginTop: 10,
  },
  cancelModalButtonText: {
    fontWeight: "bold",
  },
})

export default AsistenciaDetailScreen

