# GOE - Gestión de Observadores Estudiantil

GOE (Gestión de Observadores Estudiantil) es una aplicación web y móvil desarrollada con **PHP, MySQL y React Native** que permite la gestión de asistencias de estudiantes de manera eficiente. Cuenta con funcionalidades avanzadas de CRUD, autenticación por correo y sistema de notificaciones en tiempo real.

Combina tecnologías modernas como React Native (con Expo y TypeScript) para la app móvil, y PHP + MySQL para el backend web, asegurando un entorno funcional y seguro.

## Tecnologías Utilizadas
- **Frontend Web:** HTML, CSS, JavaScript
- **Backend Web:** PHP
- **Base de Datos:** MySQL
- **Autenticación:** Verificación por correo con enlace de activación
- **Notificaciones:** Modal emergente con PHP y MySQL
- **Aplicación Móvil:** React Native con conexión a la API REST
- **API REST:** PHP + JSON

  ## Web
  - **Frontend:** HTML, CSS, JavaScript
  - **Backend:** PHP
  - **Base de Datos:** MySQL
  - **API REST:** PHP + JSON
  - **Autenticación:** Verificación por correo electrónico (enlace de activación)
  - **Notificaciones:** Modal emergente con PHP y MySQL

   ## Móvil
   - **Framework:** React Native con Expo
   - **Lenguaje:** TypeScript
   - **Almacenamiento local:** AsyncStorage
   - **Consumo de API:** Axios
   - **Gestión de sesión:** Context API con verificación de token
   - **Navegación:** React Navigation

## Características Principales
✅ **Registro y autenticación de usuarios** con verificación por correo electrónico  
✅ **Gestión de asistencias** con filtrado dinámico por docente  
✅ **CRUD** en la web y la app móvil con sincronización en tiempo real  
✅ **Sistema de notificaciones** en modal emergente  
✅ **API REST** en PHP para la integración con la app móvil  
✅ **Implementación segura** con protección contra SQL Injection y uso recomendado de HTTPS  
✅ **Escalabilidad**, con posibilidad de expandir el sistema con futuras mejoras

## Instalación y Configuración

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/jeanJB/goe.git
   cd goe
2. **Configurar la base de datos:**
-   Importar el archivo database.sql en MySQL
-   Editar config.php con las credenciales de la base de datos

##📱 Construcción de la App Móvil con React Native
## Requisitos
- NodeJS
- Expo CLI (npm install -g expo-cli)
- Editor de código como VS Code
- Emulador Android o la app Expo Go en tu celular

1. **Navegar el proyecto Movil:**
   ```bash
   cd "nombre de la carpeta donde esta alojado el proyecto movil"

2. **Instalar dependencias:**
   ```bash
   npm start
   
3. **Ejecutar el proyecto:**
   ```bash
   npm start
   
**o si  utilizas el emulador de android studio:**
   ```bash
      npm run android
   
