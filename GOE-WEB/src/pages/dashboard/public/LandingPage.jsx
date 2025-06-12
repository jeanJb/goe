import { Link } from "react-router-dom"
import { GraduationCap, Users, Calendar, BookOpen, Download } from "lucide-react"
import Button from "../../components/ui/Button"

const LandingPage = () => {
  const features = [
    {
      icon: BookOpen,
      title: "Observadores de Estudiantes",
      description: "Gestiona y registra observaciones de comportamiento estudiantil de manera eficiente.",
    },
    {
      icon: Calendar,
      title: "Control de Asistencias",
      description: "Lleva un registro detallado de la asistencia de estudiantes y docentes.",
    },
    {
      icon: Users,
      title: "Gestión de Usuarios",
      description: "Administra estudiantes, docentes y personal administrativo desde un solo lugar.",
    },
    {
      icon: GraduationCap,
      title: "Cursos y Materias",
      description: "Organiza cursos, materias y asignaciones de manera intuitiva.",
    },
  ]

  const team = [
    { name: "Genesis Sanabria", role: "Project Manager", image: "/IMG/genesis.png" },
    { name: "Sebastian Rivero Martinez", role: "Desarrollador Full Stack", image: "/IMG/sebastian.jpg" },
    { name: "Sebastian Hernandez", role: "Desarrollador Full Stack", image: "/IMG/gemelo.jpg" },
    { name: "Roger Fuentes Ramirez", role: "Desarrollador Full Stack", image: "/IMG/roger.jpg" },
    { name: "Jean Pierre Bolaños", role: "Desarrollador Full Stack", image: "/IMG/jean.jpg" },
  ]

  return (
    <div className="min-h-screen bg-white dark:bg-gray-900">
      {/* Navigation */}
      <nav className="bg-white dark:bg-gray-900 shadow-sm fixed w-full z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <img src="/IMG/logos/goe03.png" alt="GOE" className="w-8 h-8" />
              <span className="ml-2 text-xl font-bold text-primary-500">GOE</span>
            </div>
            <div className="hidden md:flex items-center space-x-8">
              <a href="#home" className="text-gray-700 dark:text-gray-300 hover:text-primary-500">
                Inicio
              </a>
              <a href="#about" className="text-gray-700 dark:text-gray-300 hover:text-primary-500">
                Acerca de
              </a>
              <a href="#features" className="text-gray-700 dark:text-gray-300 hover:text-primary-500">
                Características
              </a>
              <a href="#team" className="text-gray-700 dark:text-gray-300 hover:text-primary-500">
                Equipo
              </a>
              <Link to="/login">
                <Button>Iniciar Sesión</Button>
              </Link>
            </div>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section
        id="home"
        className="pt-16 bg-gradient-to-br from-primary-50 to-blue-100 dark:from-gray-900 dark:to-gray-800"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
              <h1 className="text-4xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                Gestión de Observador Estudiantil
              </h1>
              <p className="text-xl text-gray-600 dark:text-gray-300 mb-8">
                Un espacio digital diseñado para estudiantes, docentes y directivos. Simplifica la administración
                escolar con herramientas modernas e intuitivas.
              </p>
              <div className="flex flex-col sm:flex-row gap-4">
                <Link to="/login">
                  <Button size="lg" className="w-full sm:w-auto">
                    Comenzar Ahora
                  </Button>
                </Link>
                <Button variant="outline" size="lg" className="w-full sm:w-auto">
                  <Download className="w-5 h-5 mr-2" />
                  Descargar App
                </Button>
              </div>
            </div>
            <div className="relative">
              <img
                src="/IMG/colegio-jose-felix-r.jpg"
                alt="Colegio José Félix Restrepo"
                className="rounded-lg shadow-2xl"
              />
            </div>
          </div>
        </div>
      </section>

      {/* About Section */}
      <section id="about" className="py-20 bg-white dark:bg-gray-900">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
              <img src="/IMG/R.jpg" alt="Colegio" className="rounded-lg shadow-lg" />
            </div>
            <div>
              <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                Colegio Técnico José Félix Restrepo
              </h2>
              <p className="text-lg text-gray-600 dark:text-gray-300 mb-6">
                El Colegio Técnico José Félix Restrepo IED presenta ante la comunidad educativa sus 12 líneas de
                inclusión y diversidad, respaldadas bajo el sello ICONTEC "No discriminación" adquirido en el 2023.
              </p>
              <Button>Conocer Más</Button>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section id="features" className="py-20 bg-gray-50 dark:bg-gray-800">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">Características Principales</h2>
            <p className="text-lg text-gray-600 dark:text-gray-300">
              Herramientas diseñadas para optimizar la gestión educativa
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {features.map((feature, index) => {
              const Icon = feature.icon
              return (
                <div key={index} className="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg text-center">
                  <div className="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <Icon className="w-6 h-6 text-primary-500" />
                  </div>
                  <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">{feature.title}</h3>
                  <p className="text-gray-600 dark:text-gray-300">{feature.description}</p>
                </div>
              )
            })}
          </div>
        </div>
      </section>

      {/* Team Section */}
      <section id="team" className="py-20 bg-white dark:bg-gray-900">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">Nuestro Equipo</h2>
            <p className="text-lg text-gray-600 dark:text-gray-300">Conoce a las personas detrás de GOE</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8">
            {team.map((member, index) => (
              <div key={index} className="text-center">
                <div className="relative mb-4">
                  <img
                    src={member.image || "/placeholder.svg"}
                    alt={member.name}
                    className="w-32 h-32 rounded-full mx-auto object-cover"
                  />
                </div>
                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-1">{member.name}</h3>
                <p className="text-gray-600 dark:text-gray-300">{member.role}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-primary-500">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-bold text-white mb-4">¿Listo para comenzar?</h2>
          <p className="text-xl text-primary-100 mb-8">
            Únete a GOE y transforma la gestión de tu institución educativa
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link to="/login">
              <Button variant="secondary" size="lg">
                Iniciar Sesión
              </Button>
            </Link>
            <Link to="/register">
              <Button
                variant="outline"
                size="lg"
                className="border-white text-white hover:bg-white hover:text-primary-500"
              >
                Registrarse
              </Button>
            </Link>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
              <div className="flex items-center mb-4">
                <img src="/IMG/logos/goe03.png" alt="GOE" className="w-8 h-8" />
                <span className="ml-2 text-xl font-bold">GOE</span>
              </div>
              <p className="text-gray-400">
                Gestión de Observador Estudiantil - Simplificando la administración educativa
              </p>
            </div>
            <div>
              <h3 className="text-lg font-semibold mb-4">Enlaces</h3>
              <ul className="space-y-2 text-gray-400">
                <li>
                  <a href="#home" className="hover:text-white">
                    Inicio
                  </a>
                </li>
                <li>
                  <a href="#about" className="hover:text-white">
                    Acerca de
                  </a>
                </li>
                <li>
                  <a href="#features" className="hover:text-white">
                    Características
                  </a>
                </li>
                <li>
                  <a href="#team" className="hover:text-white">
                    Equipo
                  </a>
                </li>
              </ul>
            </div>
            <div>
              <h3 className="text-lg font-semibold mb-4">Soporte</h3>
              <ul className="space-y-2 text-gray-400">
                <li>
                  <a href="#" className="hover:text-white">
                    Documentación
                  </a>
                </li>
                <li>
                  <a href="#" className="hover:text-white">
                    Contacto
                  </a>
                </li>
                <li>
                  <a href="#" className="hover:text-white">
                    FAQ
                  </a>
                </li>
              </ul>
            </div>
            <div>
              <h3 className="text-lg font-semibold mb-4">Contacto</h3>
              <p className="text-gray-400">
                Colegio Técnico José Félix Restrepo
                <br />
                Bogotá, Colombia
                <br />
                info@goe.edu.co
              </p>
            </div>
          </div>
          <div className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2024 GOE. Todos los derechos reservados.</p>
          </div>
        </div>
      </footer>
    </div>
  )
}

export default LandingPage
