"use client"
import { createBottomTabNavigator } from "@react-navigation/bottom-tabs"
import { createStackNavigator } from "@react-navigation/stack"
import { Ionicons } from "@expo/vector-icons"
import { useTheme } from "../context/ThemeContext"
import { useAuth } from "../context/AuthContext"

// Screens
import HomeScreen from "../screens/HomeScreen"
import ObservadoresScreen from "../screens/ObservadoresScreen"
import AsistenciasScreen from "../screens/AsistenciasScreen"
import ProfileScreen from "../screens/ProfileScreen"
import ObservadorDetailScreen from "../screens/ObservadorDetailScreen"
import AsistenciaDetailScreen from "../screens/AsistenciaDetailScreen"
import CrearObservadorScreen from "../screens/CrearObservadorScreen"
import CrearAsistenciaScreen from "../screens/CrearAsistenciaScreen"
import DescargarObservadorScreen from "../screens/DescargarObservadorScreen"

const Tab = createBottomTabNavigator()
const Stack = createStackNavigator()

// Stack navigators para cada sección
const HomeStack = () => {
  const { colors } = useTheme()

  return (
    <Stack.Navigator
      screenOptions={{
        headerStyle: { backgroundColor: colors.headerBackground },
        headerTintColor: colors.text,
        cardStyle: { backgroundColor: colors.background },
      }}
    >
      <Stack.Screen name="HomeScreen" component={HomeScreen} options={{ title: "Inicio" }} />
    </Stack.Navigator>
  )
}

const ObservadoresStack = () => {
  const { colors } = useTheme()
  const { user } = useAuth()

  return (
    <Stack.Navigator
      screenOptions={{
        headerStyle: { backgroundColor: colors.headerBackground },
        headerTintColor: colors.text,
        cardStyle: { backgroundColor: colors.background },
      }}
    >
      <Stack.Screen name="ObservadoresScreen" component={ObservadoresScreen} options={{ title: "Observadores" }} />
      <Stack.Screen
        name="ObservadorDetail"
        component={ObservadorDetailScreen}
        options={{ title: "Detalle de Observador" }}
      />
      {user?.role === "docente" && (
        <Stack.Screen
          name="CrearObservador"
          component={CrearObservadorScreen}
          options={{ title: "Crear Observador" }}
        />
      )}
      {user?.role === "docente" && (
        <Stack.Screen
          name="DescargarObservador"
          component={DescargarObservadorScreen}
          options={{ title: "Descargar Observador" }}
        />
      )}
    </Stack.Navigator>
  )
}

const AsistenciasStack = () => {
  const { colors } = useTheme()
  const { user } = useAuth()

  return (
    <Stack.Navigator
      screenOptions={{
        headerStyle: { backgroundColor: colors.headerBackground },
        headerTintColor: colors.text,
        cardStyle: { backgroundColor: colors.background },
      }}
    >
      <Stack.Screen name="AsistenciasScreen" component={AsistenciasScreen} options={{ title: "Asistencias" }} />
      <Stack.Screen
        name="AsistenciaDetail"
        component={AsistenciaDetailScreen}
        options={{ title: "Detalle de Asistencia" }}
      />
      {user?.role === "docente" && (
        <Stack.Screen
          name="CrearAsistencia"
          component={CrearAsistenciaScreen}
          options={{ title: "Registrar Asistencia" }}
        />
      )}
    </Stack.Navigator>
  )
}

const ProfileStack = () => {
  const { colors } = useTheme()

  return (
    <Stack.Navigator
      screenOptions={{
        headerStyle: { backgroundColor: colors.headerBackground },
        headerTintColor: colors.text,
        cardStyle: { backgroundColor: colors.background },
      }}
    >
      <Stack.Screen name="ProfileScreen" component={ProfileScreen} options={{ title: "Mi Perfil" }} />
    </Stack.Navigator>
  )
}

// Tab Navigator principal
const AppNavigator = () => {
  const { colors, isDark } = useTheme()
  const { user } = useAuth()

  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        tabBarIcon: ({ focused, color, size }) => {
          let iconName

          if (route.name === "Home") {
            iconName = focused ? "home" : "home-outline"
          } else if (route.name === "Observadores") {
            iconName = focused ? "document-text" : "document-text-outline"
          } else if (route.name === "Asistencias") {
            iconName = focused ? "calendar" : "calendar-outline"
          } else if (route.name === "Profile") {
            iconName = focused ? "person" : "person-outline"
          }

          return <Ionicons name={iconName as any} size={size} color={color} />
        },
        tabBarActiveTintColor: colors.primary,
        tabBarInactiveTintColor: "gray",
        tabBarStyle: {
          backgroundColor: colors.tabBar,
          borderTopColor: colors.border,
        },
        headerShown: false,
      })}
    >
      <Tab.Screen name="Home" component={HomeStack} options={{ title: "Inicio" }} />
      <Tab.Screen name="Observadores" component={ObservadoresStack} options={{ title: "Observadores" }} />
      <Tab.Screen name="Asistencias" component={AsistenciasStack} options={{ title: "Asistencias" }} />
      <Tab.Screen name="Profile" component={ProfileStack} options={{ title: "Perfil" }} />
    </Tab.Navigator>
  )
}

export default AppNavigator

