"use client"
import { useState, useEffect } from "react"
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, ActivityIndicator, Alert, Modal } from "react-native"
import { useTheme } from "../context/ThemeContext"
import { useAuth } from "../context/AuthContext"
import { useNavigation, useRoute } from "@react-navigation/native"
import { Ionicons } from "@expo/vector-icons"
import { observadoresService } from "../services/api"

const ObservadorDetailScreen = () => {
  const { colors } = useTheme()
  const { user, token } = useAuth()
  const navigation = useNavigation()
  const route = useRoute()
  const { observadorId } = route.params as { observadorId: string }

  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const [observador, setObservador] = useState<any>(null)
  const [showDeleteModal, setShowDeleteModal] = useState(false)
  const [deleting, setDeleting] = useState(false)

  // Cargar detalle del observador
  useEffect(() => {
    const loadObservadorDetail = async () => {
      if (!token || !observadorId) return

      try {
        setLoading(true)
        setError(null)

        const response = await observadoresService.getById(observadorId, token)

        if (response.success && response.data) {
          setObservador(response.data)
        } else {
          setError(response.error || "No se pudo cargar el detalle del observador")
        }
      } catch (error: any) {
        console.error("Error cargando detalle del observador:", error)
        setError("Error de conexión. Intenta de nuevo más tarde.")
      } finally {
        setLoading(false)
      }
    }

    loadObservadorDetail()
  }, [observadorId, token])

  // Función para eliminar el observador
  const handleDelete = async () => {
    if (!token || !observadorId) return

    try {
      setDeleting(true)
      const response = await observadoresService.delete(observadorId, token)

      if (response.success) {
        Alert.alert("Éxito", "Observador eliminado correctamente", [{ text: "OK", onPress: () => navigation.goBack() }])
      } else {
        Alert.alert("Error", response.error || "No se pudo eliminar el observador")
        setShowDeleteModal(false)
      }
    } catch (error: any) {
      console.error("Error eliminando observador:", error)
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

  if (error || !observador) {
    return (
      <View style={[styles.errorContainer, { backgroundColor: colors.background }]}>
        <Ionicons name="alert-circle-outline" size={60} color={colors.error} />
        <Text style={[styles.errorText, { color: colors.text }]}>{error || "No se pudo cargar el observador"}</Text>
        <TouchableOpacity
          style={[styles.retryButton, { backgroundColor: colors.primary }]}
          onPress={() => navigation.goBack()}
        >
          <Text style={styles.retryButtonText}>Volver</Text>
        </TouchableOpacity>
      </View>
    )
  }

  return (
    <ScrollView style={[styles.container, { backgroundColor: colors.background }]}>
      <View style={[styles.card, { backgroundColor: colors.card }]}>
        <View style={styles.header}>
          <Text style={[styles.title, { color: colors.text }]}>Detalle de Observación</Text>
          {user?.role === "docente" && (
            <View style={styles.actionButtons}>
              <TouchableOpacity
                style={[styles.editButton, { backgroundColor: colors.primary }]}
                onPress={() => {
                  // Aquí iría la lógica para editar
                  alert("Función de edición no implementada")
                }}
              >
                <Ionicons name="pencil" size={16} color="#fff" />
                <Text style={styles.editButtonText}>Editar</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.deleteButton, { backgroundColor: colors.error }]}
                onPress={() => setShowDeleteModal(true)}
              >
                <Ionicons name="trash" size={16} color="#fff" />
                <Text style={styles.deleteButtonText}>Eliminar</Text>
              </TouchableOpacity>
            </View>
          )}
        </View>

        <View style={styles.infoSection}>
          <View style={styles.infoRow}>
            <Text style={[styles.label, { color: colors.text }]}>Estudiante:</Text>
            <Text style={[styles.value, { color: colors.text }]}>{observador.estudiante}</Text>
          </View>

          <View style={styles.infoRow}>
            <Text style={[styles.label, { color: colors.text }]}>Fecha:</Text>
            <Text style={[styles.value, { color: colors.text }]}>{observador.fecha}</Text>
          </View>

          <View style={styles.infoRow}>
            <Text style={[styles.label, { color: colors.text }]}>Trimestre:</Text>
            <Text style={[styles.value, { color: colors.text }]}>{observador.trimestre}</Text>
          </View>

          <View style={styles.infoRow}>
            <Text style={[styles.label, { color: colors.text }]}>Nivel de Falta:</Text>
            <Text style={[styles.value, { color: colors.text }]}>{observador.nivelFalta}</Text>
          </View>

          {observador.docente && (
            <View style={styles.infoRow}>
              <Text style={[styles.label, { color: colors.text }]}>Docente:</Text>
              <Text style={[styles.value, { color: colors.text }]}>{observador.docente}</Text>
            </View>
          )}
        </View>

        <View style={styles.descriptionSection}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>Descripción de la Observación</Text>
          <View style={[styles.descriptionBox, { backgroundColor: colors.background }]}>
            <Text style={[styles.descriptionText, { color: colors.text }]}>{observador.descripcion}</Text>
          </View>
        </View>

        <View style={styles.descriptionSection}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>Compromiso del Estudiante</Text>
          <View style={[styles.descriptionBox, { backgroundColor: colors.background }]}>
            <Text style={[styles.descriptionText, { color: colors.text }]}>
              {observador.compromiso ||
                "El estudiante se compromete a mejorar su comportamiento y cumplir con las normas establecidas por la institución."}
            </Text>
          </View>
        </View>

        <View style={styles.signatureSection}>
          <View style={styles.signatureRow}>
            <Text style={[styles.signatureLabel, { color: colors.text }]}>Firma del Docente</Text>
            <Text style={[styles.signatureLabel, { color: colors.text }]}>Firma del Estudiante</Text>
          </View>
          <View style={styles.signatureRow}>
            <View style={[styles.signatureLine, { backgroundColor: colors.border }]} />
            <View style={[styles.signatureLine, { backgroundColor: colors.border }]} />
          </View>
        </View>
      </View>

      <TouchableOpacity
        style={[styles.backButton, { backgroundColor: colors.primary }]}
        onPress={() => navigation.goBack()}
      >
        <Ionicons name="arrow-back" size={20} color="#fff" />
        <Text style={styles.backButtonText}>Volver</Text>
      </TouchableOpacity>

      {/* Modal de confirmación para eliminar */}
      <Modal visible={showDeleteModal} transparent={true} animationType="fade">
        <View style={styles.modalContainer}>
          <View style={[styles.modalContent, { backgroundColor: colors.card }]}>
            <Text style={[styles.modalTitle, { color: colors.text }]}>Eliminar Observador</Text>
            <Text style={[styles.modalText, { color: colors.text }]}>
              ¿Estás seguro que deseas eliminar esta observación? Esta acción no se puede deshacer.
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
    </ScrollView>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 15,
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
  card: {
    borderRadius: 10,
    padding: 20,
    marginBottom: 20,
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
    marginBottom: 20,
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
  descriptionSection: {
    marginBottom: 20,
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: "bold",
    marginBottom: 10,
  },
  descriptionBox: {
    padding: 15,
    borderRadius: 8,
  },
  descriptionText: {
    lineHeight: 22,
  },
  signatureSection: {
    marginTop: 20,
  },
  signatureRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 10,
  },
  signatureLabel: {
    fontWeight: "bold",
    textAlign: "center",
    width: "45%",
  },
  signatureLine: {
    height: 1,
    width: "45%",
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
  modalContainer: {
    flex: 1,
    backgroundColor: "rgba(0,0,0,0.5)",
    justifyContent: "center",
    alignItems: "center",
  },
  modalContent: {
    width: "80%",
    borderRadius: 10,
    padding: 20,
  },
  modalTitle: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 15,
  },
  modalText: {
    marginBottom: 20,
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
})

export default ObservadorDetailScreen

