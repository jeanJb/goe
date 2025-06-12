"use client"

import { useState, useEffect } from "react"
import { Search, Plus, Trash2, Mail, Send, Inbox, Star, Archive, Reply, Forward, Paperclip } from "lucide-react"
import Button from "../../components/ui/Button"
import Input from "../../components/ui/Input"
import LoadingSpinner from "../../components/ui/LoadingSpinner"
import { useAuth } from "../../context/AuthContext"
import toast from "react-hot-toast"

const EmailPage = () => {
  const { user } = useAuth()
  const [emails, setEmails] = useState([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [selectedFolder, setSelectedFolder] = useState("inbox")
  const [selectedEmail, setSelectedEmail] = useState(null)
  const [showCompose, setShowCompose] = useState(false)
  const [composeData, setComposeData] = useState({
    to: "",
    subject: "",
    body: "",
    priority: "normal",
  })

  useEffect(() => {
    const fetchEmails = async () => {
      setLoading(true)
      try {
        await new Promise((resolve) => setTimeout(resolve, 1000))
        const mockEmails = [
          {
            id: 1,
            from: "admin@colegio.edu.co",
            fromName: "Administración",
            to: "juan.garcia@colegio.edu.co",
            subject: "Reunión de docentes - Viernes 26 de Enero",
            body: "Estimado profesor García,\n\nLe recordamos que tenemos reunión de docentes este viernes 26 de enero a las 3:00 PM en el aula magna. Los temas a tratar son:\n\n1. Evaluación del primer trimestre\n2. Planificación de actividades para febrero\n3. Nuevas políticas académicas\n\nPor favor confirme su asistencia.\n\nSaludos cordiales,\nAdministración",
            date: "2024-01-20 14:30",
            read: false,
            starred: true,
            folder: "inbox",
            priority: "high",
            attachments: [],
          },
          {
            id: 2,
            from: "maria.rodriguez@colegio.edu.co",
            fromName: "María Rodríguez",
            to: "juan.garcia@colegio.edu.co",
            subject: "Consulta sobre calificaciones de matemáticas",
            body: "Hola Juan,\n\nEspero que estés bien. Te escribo para consultarte sobre las calificaciones del examen de matemáticas del grado 11A. Algunos padres han preguntado sobre los criterios de evaluación.\n\n¿Podrías enviarme la rúbrica que utilizaste?\n\nGracias,\nMaría",
            date: "2024-01-19 10:15",
            read: true,
            starred: false,
            folder: "inbox",
            priority: "normal",
            attachments: [],
          },
          {
            id: 3,
            from: "juan.garcia@colegio.edu.co",
            fromName: "Juan García",
            to: "estudiantes11a@colegio.edu.co",
            subject: "Tarea de física para la próxima semana",
            body: "Estimados estudiantes,\n\nPara la próxima semana deben resolver los ejercicios del capítulo 5 del libro de física (páginas 120-125).\n\nRecuerden que la fecha límite de entrega es el miércoles 24 de enero.\n\nSaludos,\nProf. García",
            date: "2024-01-18 16:45",
            read: true,
            starred: false,
            folder: "sent",
            priority: "normal",
            attachments: [{ name: "ejercicios_fisica.pdf", size: "2.3 MB" }],
          },
        ]
        setEmails(mockEmails)
      } catch (error) {
        toast.error("Error al cargar correos")
      } finally {
        setLoading(false)
      }
    }

    fetchEmails()
  }, [])

  const filteredEmails = emails.filter((email) => {
    const matchesSearch =
      email.subject.toLowerCase().includes(searchTerm.toLowerCase()) ||
      email.fromName.toLowerCase().includes(searchTerm.toLowerCase()) ||
      email.body.toLowerCase().includes(searchTerm.toLowerCase())
    const matchesFolder = email.folder === selectedFolder

    return matchesSearch && matchesFolder
  })

  const handleEmailClick = (email) => {
    setSelectedEmail(email)
    if (!email.read) {
      setEmails(emails.map((e) => (e.id === email.id ? { ...e, read: true } : e)))
    }
  }

  const handleCompose = () => {
    setShowCompose(true)
    setComposeData({
      to: "",
      subject: "",
      body: "",
      priority: "normal",
    })
  }

  const handleSendEmail = async () => {
    try {
      // API call to send email
      toast.success("Correo enviado exitosamente")
      setShowCompose(false)
    } catch (error) {
      toast.error("Error al enviar correo")
    }
  }

  const handleStarEmail = (emailId) => {
    setEmails(emails.map((email) => (email.id === emailId ? { ...email, starred: !email.starred } : email)))
  }

  const handleDeleteEmail = (emailId) => {
    if (window.confirm("¿Estás seguro de que deseas eliminar este correo?")) {
      setEmails(emails.filter((e) => e.id !== emailId))
      setSelectedEmail(null)
      toast.success("Correo eliminado")
    }
  }

  const getPriorityColor = (priority) => {
    switch (priority) {
      case "high":
        return "text-red-500"
      case "low":
        return "text-green-500"
      default:
        return "text-gray-500"
    }
  }

  const getUnreadCount = (folder) => {
    return emails.filter((email) => email.folder === folder && !email.read).length
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <LoadingSpinner />
      </div>
    )
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Correo</h1>
          <p className="text-gray-600 dark:text-gray-400">Gestiona tu correo institucional</p>
        </div>
        <Button onClick={handleCompose}>
          <Plus className="w-4 h-4 mr-2" />
          Redactar
        </Button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {/* Sidebar */}
        <div className="lg:col-span-1">
          <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <nav className="space-y-2">
              <button
                onClick={() => setSelectedFolder("inbox")}
                className={`w-full flex items-center justify-between px-3 py-2 text-left rounded-md transition-colors ${
                  selectedFolder === "inbox"
                    ? "bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300"
                    : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                }`}
              >
                <div className="flex items-center">
                  <Inbox className="w-4 h-4 mr-3" />
                  Bandeja de entrada
                </div>
                {getUnreadCount("inbox") > 0 && (
                  <span className="bg-primary-500 text-white text-xs px-2 py-1 rounded-full">
                    {getUnreadCount("inbox")}
                  </span>
                )}
              </button>

              <button
                onClick={() => setSelectedFolder("sent")}
                className={`w-full flex items-center px-3 py-2 text-left rounded-md transition-colors ${
                  selectedFolder === "sent"
                    ? "bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300"
                    : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                }`}
              >
                <Send className="w-4 h-4 mr-3" />
                Enviados
              </button>

              <button
                onClick={() => setSelectedFolder("starred")}
                className={`w-full flex items-center px-3 py-2 text-left rounded-md transition-colors ${
                  selectedFolder === "starred"
                    ? "bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300"
                    : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                }`}
              >
                <Star className="w-4 h-4 mr-3" />
                Destacados
              </button>

              <button
                onClick={() => setSelectedFolder("archive")}
                className={`w-full flex items-center px-3 py-2 text-left rounded-md transition-colors ${
                  selectedFolder === "archive"
                    ? "bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300"
                    : "text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                }`}
              >
                <Archive className="w-4 h-4 mr-3" />
                Archivo
              </button>
            </nav>
          </div>
        </div>

        {/* Email List */}
        <div className="lg:col-span-1">
          <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div className="p-4 border-b border-gray-200 dark:border-gray-700">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                <Input
                  placeholder="Buscar correos..."
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  className="pl-10"
                />
              </div>
            </div>

            <div className="max-h-96 overflow-y-auto">
              {filteredEmails.map((email) => (
                <div
                  key={email.id}
                  onClick={() => handleEmailClick(email)}
                  className={`p-4 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors ${
                    selectedEmail?.id === email.id ? "bg-primary-50 dark:bg-primary-900/20" : ""
                  } ${!email.read ? "bg-blue-50 dark:bg-blue-900/20" : ""}`}
                >
                  <div className="flex items-start justify-between mb-2">
                    <div className="flex items-center">
                      <button
                        onClick={(e) => {
                          e.stopPropagation()
                          handleStarEmail(email.id)
                        }}
                        className={`mr-2 ${email.starred ? "text-yellow-500" : "text-gray-400"}`}
                      >
                        <Star className="w-4 h-4" />
                      </button>
                      <span className={`text-sm ${!email.read ? "font-semibold" : ""} text-gray-900 dark:text-white`}>
                        {email.fromName}
                      </span>
                    </div>
                    <span className="text-xs text-gray-500 dark:text-gray-400">{email.date.split(" ")[0]}</span>
                  </div>
                  <h3 className={`text-sm mb-1 ${!email.read ? "font-semibold" : ""} text-gray-900 dark:text-white`}>
                    {email.subject}
                  </h3>
                  <p className="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">{email.body}</p>
                  <div className="flex items-center mt-2">
                    {email.attachments.length > 0 && <Paperclip className="w-3 h-3 text-gray-400 mr-2" />}
                    <div className={`w-2 h-2 rounded-full ${getPriorityColor(email.priority)}`}></div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Email Content */}
        <div className="lg:col-span-2">
          {selectedEmail ? (
            <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
              <div className="p-6 border-b border-gray-200 dark:border-gray-700">
                <div className="flex items-start justify-between mb-4">
                  <div className="flex-1">
                    <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                      {selectedEmail.subject}
                    </h2>
                    <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                      <span className="font-medium">{selectedEmail.fromName}</span>
                      <span className="mx-2">•</span>
                      <span>{selectedEmail.date}</span>
                    </div>
                  </div>
                  <div className="flex gap-2">
                    <Button size="sm" variant="outline">
                      <Reply className="w-4 h-4" />
                    </Button>
                    <Button size="sm" variant="outline">
                      <Forward className="w-4 h-4" />
                    </Button>
                    <Button
                      size="sm"
                      variant="outline"
                      onClick={() => handleDeleteEmail(selectedEmail.id)}
                      className="text-red-600 hover:text-red-700"
                    >
                      <Trash2 className="w-4 h-4" />
                    </Button>
                  </div>
                </div>

                {selectedEmail.attachments.length > 0 && (
                  <div className="mb-4">
                    <h4 className="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Adjuntos ({selectedEmail.attachments.length})
                    </h4>
                    <div className="space-y-2">
                      {selectedEmail.attachments.map((attachment, index) => (
                        <div key={index} className="flex items-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                          <Paperclip className="w-4 h-4 text-gray-400 mr-2" />
                          <span className="text-sm text-gray-700 dark:text-gray-300 flex-1">{attachment.name}</span>
                          <span className="text-xs text-gray-500 dark:text-gray-400">{attachment.size}</span>
                        </div>
                      ))}
                    </div>
                  </div>
                )}
              </div>

              <div className="p-6">
                <div className="prose dark:prose-invert max-w-none">
                  <p className="whitespace-pre-wrap text-gray-700 dark:text-gray-300">{selectedEmail.body}</p>
                </div>
              </div>
            </div>
          ) : (
            <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
              <Mail className="w-12 h-12 text-gray-400 mx-auto mb-4" />
              <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">Selecciona un correo</h3>
              <p className="text-gray-600 dark:text-gray-400">Elige un correo de la lista para ver su contenido</p>
            </div>
          )}
        </div>
      </div>

      {/* Compose Modal */}
      {showCompose && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div className="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div className="p-6">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-xl font-bold text-gray-900 dark:text-white">Redactar Correo</h2>
                <button
                  onClick={() => setShowCompose(false)}
                  className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                >
                  ×
                </button>
              </div>

              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Para</label>
                  <Input
                    value={composeData.to}
                    onChange={(e) => setComposeData({ ...composeData, to: e.target.value })}
                    placeholder="destinatario@ejemplo.com"
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Asunto</label>
                  <Input
                    value={composeData.subject}
                    onChange={(e) => setComposeData({ ...composeData, subject: e.target.value })}
                    placeholder="Asunto del correo"
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridad</label>
                  <select
                    value={composeData.priority}
                    onChange={(e) => setComposeData({ ...composeData, priority: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
                  >
                    <option value="low">Baja</option>
                    <option value="normal">Normal</option>
                    <option value="high">Alta</option>
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensaje</label>
                  <textarea
                    value={composeData.body}
                    onChange={(e) => setComposeData({ ...composeData, body: e.target.value })}
                    rows={8}
                    className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Escribe tu mensaje aquí..."
                  />
                </div>
              </div>

              <div className="flex justify-end gap-2 mt-6">
                <Button variant="outline" onClick={() => setShowCompose(false)}>
                  Cancelar
                </Button>
                <Button onClick={handleSendEmail}>
                  <Send className="w-4 h-4 mr-2" />
                  Enviar
                </Button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

export default EmailPage
