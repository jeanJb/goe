"use client"

import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom"
import { useAuth } from "./context/AuthContext"
import { useTheme } from "./context/ThemeContext"

// Public pages
import LandingPage from "./pages/public/LandingPage"
import LoginPage from "./pages/public/LoginPage"
import RegisterPage from "./pages/public/RegisterPage"

// Protected pages
import DashboardLayout from "./components/layout/DashboardLayout"
import HomePage from "./pages/dashboard/HomePage"
import StudentsPage from "./pages/dashboard/StudentsPage"
import AttendancePage from "./pages/dashboard/AttendancePage"
import ObserversPage from "./pages/dashboard/ObserversPage"
import CoursesPage from "./pages/dashboard/CoursesPage"
import SubjectsPage from "./pages/dashboard/SubjectsPage"
import UsersPage from "./pages/dashboard/UsersPage"
import ProfilePage from "./pages/dashboard/ProfilePage"
import EmailPage from "./pages/dashboard/EmailPage"

// Loading component
import LoadingSpinner from "./components/ui/LoadingSpinner"

function App() {
  const { user, loading } = useAuth()
  const { isDark } = useTheme()

  if (loading) {
    return <LoadingSpinner />
  }

  return (
    <div className={isDark ? "dark" : ""}>
      <div className="min-h-screen bg-body-light dark:bg-body-dark transition-colors duration-300">
        <Router>
          <Routes>
            {/* Public routes */}
            <Route path="/" element={!user ? <LandingPage /> : <Navigate to="/dashboard" />} />
            <Route path="/login" element={!user ? <LoginPage /> : <Navigate to="/dashboard" />} />
            <Route path="/register" element={!user ? <RegisterPage /> : <Navigate to="/dashboard" />} />

            {/* Protected routes */}
            <Route path="/dashboard" element={user ? <DashboardLayout /> : <Navigate to="/login" />}>
              <Route index element={<HomePage />} />
              <Route path="students" element={<StudentsPage />} />
              <Route path="attendance" element={<AttendancePage />} />
              <Route path="observers" element={<ObserversPage />} />
              <Route path="courses" element={<CoursesPage />} />
              <Route path="subjects" element={<SubjectsPage />} />
              <Route path="users" element={<UsersPage />} />
              <Route path="profile" element={<ProfilePage />} />
              <Route path="email" element={<EmailPage />} />
            </Route>

            {/* Catch all route */}
            <Route path="*" element={<Navigate to={user ? "/dashboard" : "/"} />} />
          </Routes>
        </Router>
      </div>
    </div>
  )
}

export default App
