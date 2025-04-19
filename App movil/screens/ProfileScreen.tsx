"use client"

import { useState } from "react"
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  Image,
  TouchableOpacity,
  TextInput,
  Alert,
  Modal,
  FlatList,
} from "react-native"
import { useTheme } from "../context/ThemeContext"
import { useAuth } from "../context/AuthContext"
import { Ionicons } from "@expo/vector-icons"

const ProfileScreen = () => {
  const { colors } = useTheme()
  const { user, updateProfile, logout } = useAuth()

  // Estado para edición de datos básicos
  const [isEditingBasic, setIsEditingBasic] = useState(false)
  const [basicData, setBasicData] = useState({
    telefono: user?.telefono || "",
    direccion: user?.direccion || "",
    email: user?.email || "",
  })

  // Estado para edición de datos adicionales
  const [isEditingAdditional, setIsEditingAdditional] = useState(false)
  const [additionalData, setAdditionalData] = useState({
    rh: user?.rh || "",
    eps: user?.eps || "",
    fechaNacimiento: user?.fechaNacimiento || "",
    enfermedades: user?.enfermedades || "",
  })

  const [showLogoutModal, setShowLogoutModal] = useState(false)

  // Manejadores para datos básicos
  const handleEditBasic = () => {
    setIsEditingBasic(true)
  }

  const handleSaveBasic = async () => {
    try {
      await updateProfile(basicData)
      setIsEditingBasic(false)
      Alert.alert("Éxito", "Datos básicos actualizados correctamente")
    } catch (error) {
      Alert.alert("Error", "No se pudo actualizar los datos básicos")
    }
  }

  const handleCancelBasic = () => {
    setBasicData({
      telefono: user?.telefono || "",
      direccion: user?.direccion || "",
      email: user?.email || "",
    })
    setIsEditingBasic(false)
  }

  // Manejadores para datos adicionales
  const handleEditAdditional = () => {
    setIsEditingAdditional(true)
  }

  const handleSaveAdditional = async () => {
    try {
      await updateProfile(additionalData)
      setIsEditingAdditional(false)
      Alert.alert("Éxito", "Datos adicionales actualizados correctamente")
    } catch (error) {
      Alert.alert("Error", "No se pudo actualizar los datos adicionales")
    }
  }

  const handleCancelAdditional = () => {
    setAdditionalData({
      rh: user?.rh || "",
      eps: user?.eps || "",
      fechaNacimiento: user?.fechaNacimiento || "",
      enfermedades: user?.enfermedades || "",
    })
    setIsEditingAdditional(false)
  }

  const handleLogout = async () => {
    await logout()
    setShowLogoutModal(false)
  }

  return (
    <ScrollView style={[styles.container, { backgroundColor: colors.background }]}>
      <View style={[styles.profileHeader, { backgroundColor: colors.card }]}>
        <View style={styles.avatarContainer}>
          <Image
            source={user?.avatar ? { uri: user.avatar } : require("../assets/default-avatar.png")}
            style={styles.avatar}
          />
        </View>
        <View style={styles.nameContainer}>
          <Text style={[styles.userName, { color: colors.text }]}>
            {user?.nombre} {user?.apellido}
          </Text>
          <Text style={[styles.userRole, { color: colors.text }]}>
            {user?.role === "docente" ? "DOCENTE" : "ESTUDIANTE"}
          </Text>
          <Text style={[styles.userCourse, { color: colors.text }]}>CURSO: {user?.curso}</Text>
        </View>
      </View>

      {/* Sección de datos básicos */}
      <View style={styles.sectionContainer}>
        <View style={styles.sectionHeaderContainer}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>Datos Básicos</Text>
          {!isEditingBasic && (
            <TouchableOpacity
              style={[styles.editButton, { backgroundColor: colors.primary }]}
              onPress={handleEditBasic}
            >
              <Ionicons name="pencil-outline" size={16} color="#fff" />
              <Text style={styles.editButtonText}>EDITAR</Text>
            </TouchableOpacity>
          )}
        </View>

        <View style={[styles.infoCard, { backgroundColor: colors.card }]}>
          <View style={styles.infoRow}>
            <Text style={[styles.infoLabel, { color: colors.text }]}>Documento:</Text>
            <Text style={[styles.infoValue, { color: colors.text }]}>{user?.documento}</Text>
          </View>

          <View style={styles.infoRow}>
            <Text style={[styles.infoLabel, { color: colors.text }]}>Email:</Text>
            {isEditingBasic ? (
              <TextInput
                style={[styles.input, { color: colors.text, borderColor: colors.border }]}
                value={basicData.email}
                onChangeText={(text) => setBasicData({ ...basicData, email: text })}
              />
            ) : (
              <Text style={[styles.infoValue, { color: colors.text }]}>{user?.email}</Text>
            )}
          </View>

          <View style={styles.infoRow}>
            <Text style={[styles.infoLabel, { color: colors.text }]}>Teléfono:</Text>
            {isEditingBasic ? (
              <TextInput
                style={[styles.input, { color: colors.text, borderColor: colors.border }]}
                value={basicData.telefono}
                onChangeText={(text) => setBasicData({ ...basicData, telefono: text })}
                keyboardType="phone-pad"
              />
            ) : (
              <Text style={[styles.infoValue, { color: colors.text }]}>{user?.telefono}</Text>
            )}
          </View>

          <View style={styles.infoRow}>
            <Text style={[styles.infoLabel, { color: colors.text }]}>Dirección:</Text>
            {isEditingBasic ? (
              <TextInput
                style={[styles.input, { color: colors.text, borderColor: colors.border }]}
                value={basicData.direccion}
                onChangeText={(text) => setBasicData({ ...basicData, direccion: text })}
              />
            ) : (
              <Text style={[styles.infoValue, { color: colors.text }]}>{user?.direccion}</Text>
            )}
          </View>
        </View>

        {isEditingBasic && (
          <View style={styles.editActions}>
            <TouchableOpacity
              style={[styles.actionButton, { backgroundColor: colors.primary }]}
              onPress={handleSaveBasic}
            >
              <Text style={styles.actionButtonText}>Guardar</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[styles.actionButton, { backgroundColor: colors.error }]}
              onPress={handleCancelBasic}
            >
              <Text style={styles.actionButtonText}>Cancelar</Text>
            </TouchableOpacity>
          </View>
        )}
      </View>

      {/* Sección de datos adicionales - solo para estudiantes */}
      {user?.role === "estudiante" && (
        <View style={styles.sectionContainer}>
          <View style={styles.sectionHeaderContainer}>
            <Text style={[styles.sectionTitle, { color: colors.text }]}>Datos Adicionales</Text>
            {!isEditingAdditional && (
              <TouchableOpacity
                style={[styles.editButton, { backgroundColor: colors.primary }]}
                onPress={handleEditAdditional}
              >
                <Ionicons name="pencil-outline" size={16} color="#fff" />
                <Text style={styles.editButtonText}>EDITAR</Text>
              </TouchableOpacity>
            )}
          </View>

          <View style={[styles.infoCard, { backgroundColor: colors.card }]}>
            <View style={styles.infoRow}>
              <Text style={[styles.infoLabel, { color: colors.text }]}>RH:</Text>
              {isEditingAdditional ? (
                <TextInput
                  style={[styles.input, { color: colors.text, borderColor: colors.border }]}
                  value={additionalData.rh}
                  onChangeText={(text) => setAdditionalData({ ...additionalData, rh: text })}
                />
              ) : (
                <Text style={[styles.infoValue, { color: colors.text }]}>{user?.rh}</Text>
              )}
            </View>

            <View style={styles.infoRow}>
              <Text style={[styles.infoLabel, { color: colors.text }]}>EPS:</Text>
              {isEditingAdditional ? (
                <TextInput
                  style={[styles.input, { color: colors.text, borderColor: colors.border }]}
                  value={additionalData.eps}
                  onChangeText={(text) => setAdditionalData({ ...additionalData, eps: text })}
                />
              ) : (
                <Text style={[styles.infoValue, { color: colors.text }]}>{user?.eps}</Text>
              )}
            </View>

            <View style={styles.infoRow}>
              <Text style={[styles.infoLabel, { color: colors.text }]}>Fecha de Nacimiento:</Text>
              {isEditingAdditional ? (
                <TextInput
                  style={[styles.input, { color: colors.text, borderColor: colors.border }]}
                  value={additionalData.fechaNacimiento}
                  onChangeText={(text) => setAdditionalData({ ...additionalData, fechaNacimiento: text })}
                />
              ) : (
                <Text style={[styles.infoValue, { color: colors.text }]}>{user?.fechaNacimiento}</Text>
              )}
            </View>

            <View style={styles.infoRow}>
              <Text style={[styles.infoLabel, { color: colors.text }]}>Enfermedades:</Text>
              {isEditingAdditional ? (
                <TextInput
                  style={[styles.input, { color: colors.text, borderColor: colors.border }]}
                  value={additionalData.enfermedades}
                  onChangeText={(text) => setAdditionalData({ ...additionalData, enfermedades: text })}
                  placeholder="Enfermedades o condiciones médicas"
                  placeholderTextColor={colors.text + "80"}
                />
              ) : (
                <Text style={[styles.infoValue, { color: colors.text }]}>{user?.enfermedades || "Ninguna"}</Text>
              )}
            </View>
          </View>

          {isEditingAdditional && (
            <View style={styles.editActions}>
              <TouchableOpacity
                style={[styles.actionButton, { backgroundColor: colors.primary }]}
                onPress={handleSaveAdditional}
              >
                <Text style={styles.actionButtonText}>Guardar</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={[styles.actionButton, { backgroundColor: colors.error }]}
                onPress={handleCancelAdditional}
              >
                <Text style={styles.actionButtonText}>Cancelar</Text>
              </TouchableOpacity>
            </View>
          )}
        </View>
      )}

      {user?.role === "docente" && (
        <View style={styles.sectionContainer}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>Mis Materias</Text>
          <View style={[styles.infoCard, { backgroundColor: colors.card }]}>
            {user?.materias?.map((materia, index) => (
              <Text key={index} style={[styles.materiaItem, { color: colors.text, borderBottomColor: colors.border }]}>
                {materia}
              </Text>
            ))}
          </View>
        </View>
      )}

      {user?.role === "docente" && (
        <View style={styles.sectionContainer}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>Estudiantes a Cargo</Text>
          <View style={[styles.infoCard, { backgroundColor: colors.card }]}>
            {/* Lista de estudiantes a cargo */}
            <FlatList
              data={[
                { id: "1", nombre: "Pedro Torrez", curso: "701", documento: "1028745123" },
                { id: "2", nombre: "Alejandro Díaz", curso: "701", documento: "1029876543" },
                { id: "3", nombre: "Sebastián Cardenas", curso: "703", documento: "1029143097" },
                { id: "4", nombre: "Jaime Bolaños", curso: "703", documento: "1030567890" },
                { id: "5", nombre: "María González", curso: "1001", documento: "1031234567" },
                { id: "6", nombre: "Ana Martínez", curso: "1001", documento: "1032345678" },
                { id: "7", nombre: "Claudio Rodríguez", curso: "703", documento: "1033456789" },
              ]}
              keyExtractor={(item) => item.id}
              scrollEnabled={false}
              renderItem={({ item }) => (
                <View style={[styles.estudianteItem, { borderBottomColor: colors.border }]}>
                  <View style={styles.estudianteInfo}>
                    <Text style={[styles.estudianteNombre, { color: colors.text }]}>{item.nombre}</Text>
                    <View style={styles.estudianteDetalles}>
                      <Text style={[styles.estudianteDetalle, { color: colors.text + "CC" }]}>Curso: {item.curso}</Text>
                      <Text style={[styles.estudianteDetalle, { color: colors.text + "CC" }]}>
                        Doc: {item.documento}
                      </Text>
                    </View>
                  </View>
                  <TouchableOpacity
                    style={[styles.verPerfilButton, { backgroundColor: colors.primary + "20" }]}
                    onPress={() => Alert.alert("Ver Perfil", `Ver perfil de ${item.nombre}`)}
                  >
                    <Ionicons name="person" size={16} color={colors.primary} />
                    <Text style={[styles.verPerfilText, { color: colors.primary }]}>Ver</Text>
                  </TouchableOpacity>
                </View>
              )}
            />
          </View>
        </View>
      )}

      {user?.role === "estudiante" && (
        <View style={styles.sectionContainer}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>MATERIAS</Text>
          <View style={[styles.infoCard, { backgroundColor: colors.card }]}>
            <View style={styles.tableHeader}>
              <Text style={[styles.tableHeaderText, { color: colors.text }]}>Materia</Text>
              <Text style={[styles.tableHeaderText, { color: colors.text }]}>Semana/Día</Text>
              <Text style={[styles.tableHeaderText, { color: colors.text }]}>Inicio</Text>
              <Text style={[styles.tableHeaderText, { color: colors.text }]}>Final</Text>
            </View>
            {user?.materias?.map((materia, index) => (
              <View key={index} style={[styles.tableRow, { borderBottomColor: colors.border }]}>
                <Text style={[styles.tableCell, { color: colors.text }]}>{materia}</Text>
                <Text style={[styles.tableCell, { color: colors.text }]}>Semana 1, día 1</Text>
                <Text style={[styles.tableCell, { color: colors.text }]}>12:30:00</Text>
                <Text style={[styles.tableCell, { color: colors.text }]}>14:20:00</Text>
              </View>
            ))}
          </View>
        </View>
      )}

      <TouchableOpacity
        style={[styles.logoutButton, { backgroundColor: colors.error }]}
        onPress={() => setShowLogoutModal(true)}
      >
        <Ionicons name="log-out-outline" size={20} color="#fff" />
        <Text style={styles.logoutButtonText}>Cerrar Sesión</Text>
      </TouchableOpacity>

      <Modal visible={showLogoutModal} transparent={true} animationType="fade">
        <View style={styles.modalContainer}>
          <View style={[styles.modalContent, { backgroundColor: colors.card }]}>
            <Text style={[styles.modalTitle, { color: colors.text }]}>Cerrar Sesión</Text>
            <Text style={[styles.modalText, { color: colors.text }]}>¿Estás seguro que deseas cerrar sesión?</Text>
            <View style={styles.modalActions}>
              <TouchableOpacity style={[styles.modalButton, { backgroundColor: colors.error }]} onPress={handleLogout}>
                <Text style={styles.modalButtonText}>Sí, cerrar sesión</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={[styles.modalButton, { backgroundColor: colors.secondary }]}
                onPress={() => setShowLogoutModal(false)}
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
  },
  profileHeader: {
    flexDirection: "row",
    padding: 20,
    marginBottom: 20,
  },
  avatarContainer: {
    marginRight: 20,
  },
  avatar: {
    width: 100,
    height: 100,
    borderRadius: 50,
    backgroundColor: "#ddd",
  },
  nameContainer: {
    flex: 1,
    justifyContent: "center",
  },
  userName: {
    fontSize: 22,
    fontWeight: "bold",
    marginBottom: 5,
  },
  userRole: {
    fontSize: 16,
    marginBottom: 5,
  },
  userCourse: {
    fontSize: 16,
  },
  sectionContainer: {
    marginBottom: 20,
    paddingHorizontal: 20,
  },
  sectionHeaderContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 10,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: "bold",
  },
  editButton: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 6,
    paddingHorizontal: 12,
    borderRadius: 5,
  },
  editButtonText: {
    color: "#fff",
    fontWeight: "bold",
    fontSize: 12,
    marginLeft: 5,
  },
  infoCard: {
    borderRadius: 10,
    padding: 15,
  },
  infoRow: {
    flexDirection: "row",
    marginBottom: 15,
    alignItems: "center",
  },
  infoLabel: {
    fontWeight: "bold",
    width: 150,
  },
  infoValue: {
    flex: 1,
  },
  input: {
    flex: 1,
    borderWidth: 1,
    borderRadius: 5,
    padding: 8,
  },
  editActions: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginTop: 15,
  },
  actionButton: {
    flex: 1,
    padding: 12,
    borderRadius: 5,
    alignItems: "center",
    marginHorizontal: 5,
  },
  actionButtonText: {
    color: "#fff",
    fontWeight: "bold",
  },
  materiaItem: {
    paddingVertical: 10,
    borderBottomWidth: 1,
  },
  tableHeader: {
    flexDirection: "row",
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: "#2196F3",
  },
  tableHeaderText: {
    fontWeight: "bold",
    flex: 1,
    textAlign: "center",
  },
  tableRow: {
    flexDirection: "row",
    paddingVertical: 10,
    borderBottomWidth: 1,
  },
  tableCell: {
    flex: 1,
    textAlign: "center",
  },
  logoutButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    marginHorizontal: 20,
    marginBottom: 30,
    padding: 15,
    borderRadius: 10,
  },
  logoutButtonText: {
    color: "#fff",
    fontWeight: "bold",
    marginLeft: 10,
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
  modalActions: {
    flexDirection: "column",
  },
  modalButton: {
    padding: 12,
    borderRadius: 5,
    alignItems: "center",
    marginBottom: 10,
  },
  modalButtonText: {
    color: "#fff",
    fontWeight: "bold",
  },
  estudianteItem: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingVertical: 12,
    borderBottomWidth: 1,
  },
  estudianteInfo: {
    flex: 1,
  },
  estudianteNombre: {
    fontWeight: "bold",
    fontSize: 16,
    marginBottom: 4,
  },
  estudianteDetalles: {
    flexDirection: "row",
  },
  estudianteDetalle: {
    fontSize: 14,
    marginRight: 15,
  },
  verPerfilButton: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 6,
    paddingHorizontal: 12,
    borderRadius: 5,
  },
  verPerfilText: {
    fontWeight: "bold",
    fontSize: 12,
    marginLeft: 5,
  },
})

export default ProfileScreen

