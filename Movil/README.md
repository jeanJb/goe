# GOE - Gestión de Observadores Estudiantil

GOE (Gestión de Observadores Estudiantil) es una aplicación web y móvil desarrollada con **PHP, MySQL y React Native** que permite la gestión de asistencias de estudiantes de manera eficiente. Cuenta con funcionalidades avanzadas de CRUD, autenticación por correo y sistema de notificaciones en tiempo real.

## Tecnologías Utilizadas
- **Frontend Web:** HTML, CSS, JavaScript
- **Backend Web:** PHP
- **Base de Datos:** MySQL
- **Autenticación:** Verificación por correo con enlace de activación
- **Notificaciones:** Modal emergente con PHP y MySQL
- **Aplicación Móvil:** React Native con conexión a la API REST

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
   git clone https://github.com/SebasTechWolf/goes.git
   cd goe
2. **Configurar la base de datos:**
-   Importar el archivo database.sql en MySQL
-   Editar config.php con las credenciales de la base de datos
