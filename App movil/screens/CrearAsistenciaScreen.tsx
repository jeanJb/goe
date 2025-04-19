"use client"

import { useState } from "react"
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, Alert, Platform, FlatList } from "react-native"
import { useTheme } from "../context/ThemeContext"
import { useNavigation } from "@react-navigation/native"
import { Ionicons } from "@expo/vector-icons"
import { Picker } from "@react-native-picker/picker"
import DateTimePicker from "@react-native-community/datetimepicker"

// Tipos de asistencia
type AsistenciaStatus = "Asistió" | "Retardo" | "Excusa" | "Falla"

// Datos de ejemplo para la lista de estudiantes
const estudiantesData = [
  { id: "1", nombre: "Pedro Torrez", asistencia: "Asistió", justificacion: "" },
  { id: "2", nombre: "Alejandro Díaz", asistencia: "Asistió", justificacion: "" },
  { id: "3", nombre: "Sebastián Cardenas", asistencia: "Asistió", justificacion: "" },
  { id: "4", nombre: "Jaime Bolaños", asistencia: "Asistió", justificacion: "" },
  { id: "5", nombre: "María González", asistencia: "Asistió", justificacion: "" },
  { id: "6", nombre: "Ana Martínez", asistencia: "Asistió", justificacion: "" },
]

const CrearAsistenciaScreen = () => {
  const { colors } = useTheme()
  const navigation = useNavigation()

  const [formData, setFormData] = useState({
    fecha: new Date(),
    curso: "",
    materia: "",
    trimestre: "I",
  })

  const [estudiantes, setEstudiantes] = useState(estudiantesData)
  const [showDatePicker, setShowDatePicker] = useState(false)

  const handleDateChange = (event, selectedDate) => {
    const currentDate = selectedDate || formData.fecha
    setShowDatePicker(Platform.OS === "ios")
    setFormData({ ...formData, fecha: currentDate })
  }

  const cambiarAsistencia = (id: string, nuevoEstado: AsistenciaStatus) => {
    setEstudiantes(estudiantes.map((est) => (est.id === id ? { ...est, asistencia: nuevoEstado } : est)))
  }

  const handleSubmit = () => {
    // Validar campos requeridos
    if (!formData.curso || !formData.materia) {
      Alert.alert("Error", "Por favor completa los campos requeridos")
      return
    }

    // Aquí iría la lógica para guardar la asistencia
    Alert.alert("Éxito", "Asistencia registrada correctamente", [{ text: "OK", onPress: () => navigation.goBack() }])
  }

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

  const renderEstudianteItem = ({ item }) => (
    <View style={[styles.estudianteItem, { backgroundColor: colors.card }]}>
      <View style={styles.estudianteInfo}>
        <Text style={[styles.estudianteNombre, { color: colors.text }]}>{item.nombre}</Text>
        <Text style={[styles.asistenciaActual, { color: getAsistenciaColor(item.asistencia) }]}>{item.asistencia}</Text>
      </View>

      <View style={styles.asistenciaButtons}>
        <TouchableOpacity
          style={[
            styles.asistenciaButton,
            {
              backgroundColor: getAsistenciaColor("Asistió") + "20",
              borderColor: getAsistenciaColor("Asistió"),
              borderWidth: item.asistencia === "Asistió" ? 2 : 0,
            },
          ]}
          onPress={() => cambiarAsistencia(item.id, "Asistió")}
        >
          <Ionicons name="checkmark-circle" size={24} color={getAsistenciaColor("Asistió")} />
        </TouchableOpacity>

        <TouchableOpacity
          style={[
            styles.asistenciaButton,
            {
              backgroundColor: getAsistenciaColor("Retardo") + "20",
              borderColor: getAsistenciaColor("Retardo"),
              borderWidth: item.asistencia === "Retardo" ? 2 : 0,
            },
          ]}
          onPress={() => cambiarAsistencia(item.id, "Retardo")}
        >
          <Ionicons name="time" size={24} color={getAsistenciaColor("Retardo")} />
        </TouchableOpacity>

        <TouchableOpacity
          style={[
            styles.asistenciaButton,
            {
              backgroundColor: getAsistenciaColor("Excusa") + "20",
              borderColor: getAsistenciaColor("Excusa"),
              borderWidth: item.asistencia === "Excusa" ? 2 : 0,
            },
          ]}
          onPress={() => cambiarAsistencia(item.id, "Excusa")}
        >
          <Ionicons name="document-text" size={24} color={getAsistenciaColor("Excusa")} />
        </TouchableOpacity>

        <TouchableOpacity
          style={[
            styles.asistenciaButton,
            {
              backgroundColor: getAsistenciaColor("Falla") + "20",
              borderColor: getAsistenciaColor("Falla"),
              borderWidth: item.asistencia === "Falla" ? 2 : 0,
            },
          ]}
          onPress={() => cambiarAsistencia(item.id, "Falla")}
        >
          <Ionicons name="close-circle" size={24} color={getAsistenciaColor("Falla")} />
        </TouchableOpacity>
      </View>
    </View>
  )

  return (
    <ScrollView style={[styles.container, { backgroundColor: colors.background }]}>
      <View style={[styles.card, { backgroundColor: colors.card }]}>
        <Text style={[styles.title, { color: colors.text }]}>Datos de la Asistencia</Text>

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>Fecha:</Text>
          <TouchableOpacity
            style={[styles.dateButton, { backgroundColor: colors.background, borderColor: colors.border }]}
            onPress={() => setShowDatePicker(true)}
          >
            <Text style={{ color: colors.text }}>{formData.fecha.toLocaleDateString()}</Text>
            <Ionicons name="calendar-outline" size={20} color={colors.text} />
          </TouchableOpacity>
          {showDatePicker && (
            <DateTimePicker value={formData.fecha} mode="date" display="default" onChange={handleDateChange} />
          )}
        </View>

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>Curso:</Text>
          <View style={[styles.pickerContainer, { backgroundColor: colors.background, borderColor: colors.border }]}>
            <Picker
              selectedValue={formData.curso}
              onValueChange={(value) => setFormData({ ...formData, curso: value })}
              style={{ color: colors.text }}
              dropdownIconColor={colors.text}
            >
              <Picker.Item label="Seleccione un curso" value="" />
              <Picker.Item label="701" value="701" />
              <Picker.Item label="702" value="702" />
              <Picker.Item label="703" value="703" />
              <Picker.Item label="801" value="801" />
              <Picker.Item label="1001" value="1001" />
              <Picker.Item label="1002" value="1002" />
            </Picker>
          </View>
        </View>

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>Materia:</Text>
          <View style={[styles.pickerContainer, { backgroundColor: colors.background, borderColor: colors.border }]}>
            <Picker
              selectedValue={formData.materia}
              onValueChange={(value) => setFormData({ ...formData, materia: value })}
              style={{ color: colors.text }}
              dropdownIconColor={colors.text}
            >
              <Picker.Item label="Selecciona una materia" value="" />
              <Picker.Item label="FISICA" value="FISICA" />
              <Picker.Item label="INFORMATICA" value="INFORMATICA" />
              <Picker.Item label="INGLES" value="INGLES" />
              <Picker.Item label="MATEMATICAS" value="MATEMATICAS" />
              <Picker.Item label="FILOSOFIA" value="FILOSOFIA" />
            </Picker>
          </View>
        </View>

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>Trimestre:</Text>
          <View style={[styles.pickerContainer, { backgroundColor: colors.background, borderColor: colors.border }]}>
            <Picker
              selectedValue={formData.trimestre}
              onValueChange={(value) => setFormData({ ...formData, trimestre: value })}
              style={{ color: colors.text }}
              dropdownIconColor={colors.text}
            >
              <Picker.Item label="I" value="I" />
              <Picker.Item label="II" value="II" />
              <Picker.Item label="III" value="III" />
              <Picker.Item label="IV" value="IV" />
            </Picker>
          </View>
        </View>
      </View>

      <View style={styles.estudiantesSection}>
        <Text style={[styles.sectionTitle, { color: colors.text }]}>Lista de Estudiantes</Text>
        <View style={styles.leyendaContainer}>
          <View style={styles.leyendaItem}>
            <Ionicons name="checkmark-circle" size={16} color={getAsistenciaColor("Asistió")} />
            <Text style={[styles.leyendaText, { color: colors.text }]}>Asistió</Text>
          </View>
          <View style={styles.leyendaItem}>
            <Ionicons name="time" size={16} color={getAsistenciaColor("Retardo")} />
            <Text style={[styles.leyendaText, { color: colors.text }]}>Retardo</Text>
          </View>
          <View style={styles.leyendaItem}>
            <Ionicons name="document-text" size={16} color={getAsistenciaColor("Excusa")} />
            <Text style={[styles.leyendaText, { color: colors.text }]}>Excusa</Text>
          </View>
          <View style={styles.leyendaItem}>
            <Ionicons name="close-circle" size={16} color={getAsistenciaColor("Falla")} />
            <Text style={[styles.leyendaText, { color: colors.text }]}>Falla</Text>
          </View>
        </View>

        <FlatList
          data={estudiantes}
          renderItem={renderEstudianteItem}
          keyExtractor={(item) => item.id}
          scrollEnabled={false}
        />
      </View>

      <View style={styles.buttonContainer}>
        <TouchableOpacity style={[styles.submitButton, { backgroundColor: colors.primary }]} onPress={handleSubmit}>
          <Text style={styles.submitButtonText}>Guardar Asistencia</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={[styles.cancelButton, { backgroundColor: colors.error }]}
          onPress={() => navigation.goBack()}
        >
          <Text style={styles.cancelButtonText}>Cancelar</Text>
        </TouchableOpacity>
      </View>
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
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 20,
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
  dateButton: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    height: 45,
    borderWidth: 1,
    borderRadius: 8,
    paddingHorizontal: 10,
  },
  estudiantesSection: {
    marginBottom: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 15,
  },
  leyendaContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 15,
    flexWrap: "wrap",
  },
  leyendaItem: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 5,
    marginRight: 10,
  },
  leyendaText: {
    marginLeft: 5,
    fontSize: 12,
  },
  estudianteItem: {
    padding: 15,
    borderRadius: 8,
    marginBottom: 10,
  },
  estudianteInfo: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 10,
  },
  estudianteNombre: {
    fontSize: 16,
    fontWeight: "bold",
  },
  asistenciaActual: {
    fontWeight: "bold",
  },
  asistenciaButtons: {
    flexDirection: "row",
    justifyContent: "space-between",
  },
  asistenciaButton: {
    width: 50,
    height: 50,
    borderRadius: 25,
    justifyContent: "center",
    alignItems: "center",
  },
  buttonContainer: {
    marginBottom: 30,
  },
  submitButton: {
    padding: 15,
    borderRadius: 8,
    alignItems: "center",
    marginBottom: 10,
  },
  submitButtonText: {
    color: "#fff",
    fontWeight: "bold",
    fontSize: 16,
  },
  cancelButton: {
    padding: 15,
    borderRadius: 8,
    alignItems: "center",
  },
  cancelButtonText: {
    color: "#fff",
    fontWeight: "bold",
    fontSize: 16,
  },
})

export default CrearAsistenciaScreen

