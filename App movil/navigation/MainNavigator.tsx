"use client"
import { createStackNavigator } from "@react-navigation/stack"
import { useAuth } from "../context/AuthContext"
import AuthNavigator from "./AuthNavigator"
import AppNavigator from "./AppNavigator"
import LoadingScreen from "../screens/LoadingScreen"

const Stack = createStackNavigator()

const MainNavigator = () => {
  const { user, isLoading } = useAuth()

  if (isLoading) {
    return <LoadingScreen />
  }

  return (
    <Stack.Navigator screenOptions={{ headerShown: false }}>
      {user ? (
        <Stack.Screen name="App" component={AppNavigator} />
      ) : (
        <Stack.Screen name="Auth" component={AuthNavigator} />
      )}
    </Stack.Navigator>
  )
}

export default MainNavigator

