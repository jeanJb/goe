"use client"

import { useState } from "react"
import { View, Text, StyleSheet, ScrollView, TextInput, TouchableOpacity, Alert, Platform } from "react-native"
import { useTheme } from "../context/ThemeContext"
import { useNavigation } from "@react-navigation/native"
import { Ionicons } from "@expo/vector-icons"
import { Picker } from "@react-native-picker/picker"
import DateTimePicker from "@react-native-community/datetimepicker"

const CrearObservadorScreen = () => {
  const { colors } = useTheme()
  const navigation = useNavigation()

  const [formData, setFormData] = useState({
    documento: "",
    curso: "",
    estudiante: "",
    fecha: new Date(),
    observacion: "",
    compromiso: "",
    docente: "Juan Paez",
    trimestre: "I",
    nivelFalta: "Leve",
  })

  const [showDatePicker, setShowDatePicker] = useState(false)

  const handleDateChange = (event, selectedDate) => {
    const currentDate = selectedDate || formData.fecha
    setShowDatePicker(Platform.OS === "ios")
    setFormData({ ...formData, fecha: currentDate })
  }

  const handleSubmit = () => {
    // Validar campos requeridos
    if (!formData.estudiante || !formData.observacion) {
      Alert.alert("Error", "Por favor completa los campos requeridos")
      return
    }

    // Aquí iría la lógica para guardar la observación
    Alert.alert("Éxito", "Observación creada correctamente", [{ text: "OK", onPress: () => navigation.goBack() }])
  }

  return (
    <ScrollView style={[styles.container, { backgroundColor: colors.background }]}>
      <View style={[styles.card, { backgroundColor: colors.card }]}>
        <Text style={[styles.title, { color: colors.text }]}>Datos del Estudiante</Text>

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>Curso:</Text>
          <View style={[styles.pickerContainer, { backgroundColor: colors.background, borderColor: colors.border }]}>
            <Picker
              selectedValue={formData.curso}
              onValueChange={(value) => setFormData({ ...formData, curso: value })}
              style={{ color: colors.text }}
              dropdownIconColor={colors.text}
            >
              <Picker.Item label="Seleccione" value="" />
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
          <Text style={[styles.label, { color: colors.text }]}>Estudiante:</Text>
          <View style={[styles.pickerContainer, { backgroundColor: colors.background, borderColor: colors.border }]}>
            <Picker
              selectedValue={formData.estudiante}
              onValueChange={(value) => setFormData({ ...formData, estudiante: value })}
              style={{ color: colors.text }}
              dropdownIconColor={colors.text}
            >
              <Picker.Item label="Seleccione" value="" />
              <Picker.Item label="Pedro Torrez" value="Pedro Torrez" />
              <Picker.Item label="Alejandro Díaz" value="Alejandro Díaz" />
              <Picker.Item label="Sebastián Cardenas" value="Sebastián Cardenas" />
              <Picker.Item label="Jaime Bolaños" value="Jaime Bolaños" />
            </Picker>
          </View>
        </View>

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>Documento:</Text>
          <TextInput
            style={[
              styles.input,
              { backgroundColor: colors.background, color: colors.text, borderColor: colors.border },
            ]}
            value={formData.documento}
            onChangeText={(text) => setFormData({ ...formData, documento: text })}
            placeholder="Número de documento"
            placeholderTextColor={colors.text + "80"}
            keyboardType="numeric"
          />
        </View>

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
      </View>

      <View style={[styles.card, { backgroundColor: colors.card, marginTop: 15 }]}>
        <Text style={[styles.title, { color: colors.text }]}>Detalles de la Observación</Text>

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>¿Por qué se hace la Observación?</Text>
          <TextInput
            style={[
              styles.textArea,
              { backgroundColor: colors.background, color: colors.text, borderColor: colors.border },
            ]}
            value={formData.observacion}
            onChangeText={(text) => setFormData({ ...formData, observacion: text })}
            placeholder="Describa la situación..."
            placeholderTextColor={colors.text + "80"}
            multiline
            numberOfLines={4}
            textAlignVertical="top"
          />
        </View>

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>Compromiso del Estudiante:</Text>
          <TextInput
            style={[
              styles.textArea,
              { backgroundColor: colors.background, color: colors.text, borderColor: colors.border },
            ]}
            value={formData.compromiso}
            onChangeText={(text) => setFormData({ ...formData, compromiso: text })}
            placeholder="Describa el compromiso..."
            placeholderTextColor={colors.text + "80"}
            multiline
            numberOfLines={4}
            textAlignVertical="top"
          />
        </View>

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>Docente:</Text>
          <TextInput
            style={[
              styles.input,
              { backgroundColor: colors.background, color: colors.text, borderColor: colors.border },
            ]}
            value={formData.docente}
            onChangeText={(text) => setFormData({ ...formData, docente: text })}
            placeholder="Nombre del docente"
            placeholderTextColor={colors.text + "80"}
            editable={false}
          />
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

        <View style={styles.formGroup}>
          <Text style={[styles.label, { color: colors.text }]}>Nivel de la Falta:</Text>
          <View style={[styles.pickerContainer, { backgroundColor: colors.background, borderColor: colors.border }]}>
            <Picker
              selectedValue={formData.nivelFalta}
              onValueChange={(value) => setFormData({ ...formData, nivelFalta: value })}
              style={{ color: colors.text }}
              dropdownIconColor={colors.text}
            >
              <Picker.Item label="Leve" value="Leve" />
              <Picker.Item label="Regular" value="Regular" />
              <Picker.Item label="Grave" value="Grave" />
            </Picker>
          </View>
        </View>
      </View>

      <View style={styles.buttonContainer}>
        <TouchableOpacity style={[styles.submitButton, { backgroundColor: colors.primary }]} onPress={handleSubmit}>
          <Text style={styles.submitButtonText}>Guardar Observación</Text>
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
  input: {
    height: 45,
    borderWidth: 1,
    borderRadius: 8,
    paddingHorizontal: 10,
  },
  pickerContainer: {
    borderWidth: 1,
    borderRadius: 8,
    height: 45,
    justifyContent: "center",
  },
  textArea: {
    borderWidth: 1,
    borderRadius: 8,
    paddingHorizontal: 10,
    paddingTop: 10,
    minHeight: 100,
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
  buttonContainer: {
    marginTop: 20,
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

export default CrearObservadorScreen

