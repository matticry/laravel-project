# 🐾 Sistema de Gestión de Mascotas

Un sistema completo desarrollado en Laravel para la gestión integral de mascotas y sus propietarios, con integración de APIs externas para obtención automática de imágenes por raza.

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Tecnologías Utilizadas](#️-tecnologías-utilizadas)
- [Screenshots](#-screenshots)
- [Instalación](#-instalación)
- [Configuración](#️-configuración)
- [Usuario de Prueba](#-usuario-de-prueba)
- [Funcionalidades](#-funcionalidades)
- [APIs Integradas](#-apis-integradas)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Testing](#-testing)
- [Consideraciones de Seguridad](#-consideraciones-de-seguridad)

## ✨ Características Principales

- **Gestión completa de usuarios** con sistema de autenticación robusto
- **CRUD completo de mascotas** con validaciones avanzadas
- **Integración con APIs externas** (TheDogAPI & TheCatAPI) para imágenes automáticas
- **Sistema de subida de imágenes** propias para mascotas
- **Consulta avanzada de usuarios** y visualización de sus mascotas en cards
- **Dashboard intuitivo** con navegación por tabs
- **Rate limiting** para protección contra ataques de fuerza bruta
- **Arquitectura limpia** con Repository Pattern y Service Layer

## 🛠️ Tecnologías Utilizadas

### Backend
- **Laravel 8+** - Framework PHP
- **MySQL/MariaDB** - Base de datos
- **Eloquent ORM** - Mapeo objeto-relacional
- **Repository Pattern** - Abstracción de datos
- **Service Layer** - Lógica de negocio
- **Form Requests** - Validaciones
- **Seeders & Factories** - Datos de prueba

### Frontend
- **Tailwind CSS** - Framework de estilos
- **Alpine.js** - Interactividad JavaScript
- **Blade Templates** - Motor de plantillas
- **Responsive Design** - Diseño adaptativo

### Testing & Calidad
- **PHPUnit** - Testing unitario
- **Service Providers** - Inyección de dependencias
- **SOLID Principles** - Principios de desarrollo

### APIs Externas
- **TheDogAPI** - Información y imágenes de razas de perros
- **TheCatAPI** - Información y imágenes de razas de gatos

## 📸 Screenshots

### Login del Sistema
![Login](https://res.cloudinary.com/dhkmjpq1h/image/upload/v1753569313/Captura_de_pantalla_2025-07-26_173442_ywmkwc.png)

### Registro de Usuario
![Registro](https://res.cloudinary.com/dhkmjpq1h/image/upload/v1753569340/Captura_de_pantalla_2025-07-26_173529_c7qgrk.png)

### Dashboard Principal - CRUD de Usuarios
![Dashboard](https://res.cloudinary.com/dhkmjpq1h/image/upload/v1753569381/Captura_de_pantalla_2025-07-26_173610_avlbbn.png)

### Gestión de Mascotas
![Gestión Mascotas](https://res.cloudinary.com/dhkmjpq1h/image/upload/v1753569413/Captura_de_pantalla_2025-07-26_173642_fo2lmh.png)

### Registro de Mascota con API Externa
![Registro Mascota](https://res.cloudinary.com/dhkmjpq1h/image/upload/v1753569448/Captura_de_pantalla_2025-07-26_173716_jpjlrt.png)

### Consulta de Usuario y sus Mascotas
![Consulta Usuario](https://res.cloudinary.com/dhkmjpq1h/image/upload/v1753569481/Captura_de_pantalla_2025-07-26_173749_gbcgy4.png)

## 🚀 Instalación

### Prerrequisitos
- PHP 8.0 o superior
- Composer
- MySQL/MariaDB
- Node.js (opcional, para assets)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone [URL_DEL_REPOSITORIO]
cd laravel-pets-management
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Configurar variables de entorno**
```bash
cp .env.example .env
```

4. **Generar clave de aplicación**
```bash
php artisan key:generate
```

5. **Configurar base de datos en `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pets_management
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

6. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

7. **Crear enlace simbólico para almacenamiento**
```bash
php artisan storage:link
```

8. **Iniciar el servidor de desarrollo**
```bash
php artisan serve
```

## ⚙️ Configuración

### APIs Externas
Las APIs de TheDogAPI y TheCatAPI están preconfiguradas con una clave válida. Si necesitas usar tu propia clave, actualiza el archivo de configuración correspondiente.

### Permisos de Archivos
Asegúrate de que las carpetas `storage` y `bootstrap/cache` tengan permisos de escritura:
```bash
chmod -R 775 storage bootstrap/cache
```

## 👤 Usuario de Prueba

Para acceder al sistema, utiliza las siguientes credenciales:

- **Usuario**: `admin`
- **Contraseña**: `admin`
- **URL de acceso**: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)

## 🎯 Funcionalidades

### Sistema de Autenticación
- ✅ Login y registro de usuarios
- ✅ Rate limiting (3 intentos con cooldown)
- ✅ Rutas protegidas
- ✅ Gestión de sesiones

### Gestión de Usuarios
- ✅ CRUD completo de usuarios
- ✅ Validaciones avanzadas
- ✅ Sistema de permisos
- ✅ Dashboard con navegación por tabs

### Gestión de Mascotas
- ✅ CRUD completo de mascotas
- ✅ Campos: nombre, especie, raza, edad, propietario
- ✅ Estados: activo/inactivo
- ✅ Filtros y búsquedas avanzadas
- ✅ Paginación de resultados

### Integración de Imágenes
- ✅ **Búsqueda automática** por especie y raza
- ✅ **Subida de imágenes propias** hasta 2MB
- ✅ **Previsualización** en tiempo real
- ✅ **Almacenamiento seguro** en `/storage/app/public/profile_images`
- ✅ **Fallback graceful** si las APIs fallan

### Consultas Avanzadas
- ✅ Búsqueda de usuarios por nombre, email, DNI
- ✅ Visualización de mascotas en cards elegantes
- ✅ Estadísticas por usuario
- ✅ Filtros por estado (activas/inactivas)

## 🔌 APIs Integradas

### TheDogAPI
- **Endpoint**: `https://api.thedogapi.com/v1/`
- **Funcionalidad**: Búsqueda de razas e imágenes de perros
- **Uso**: Automático al registrar mascotas de especie "Perro"

### TheCatAPI
- **Endpoint**: `https://api.thecatapi.com/v1/`
- **Funcionalidad**: Búsqueda de razas e imágenes de gatos
- **Uso**: Automático al registrar mascotas de especie "Gato"

### Flujo de Integración
1. Usuario selecciona especie (Perro/Gato) y escribe raza
2. Sistema busca automáticamente en la API correspondiente
3. Muestra imagen encontrada con opción de usar o subir propia
4. Almacena imagen según preferencia del usuario

## 📁 Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── PetController.php
│   │   └── AuthController.php
│   ├── Requests/
│   │   ├── StorePetRequest.php
│   │   └── UpdatePetRequest.php
│   └── Resources/
│       └── PetResource.php
├── Models/
│   ├── Pet.php
│   └── User.php
├── Services/
│   └── PetService.php
├── Repositories/
│   └── PetRepository.php
└── Contracts/
    └── PetRepositoryInterface.php

database/
├── migrations/
├── seeders/
└── factories/

resources/
├── views/
│   ├── pets/
│   │   ├── index.blade.php
│   │   ├── edit.blade.php
│   │   ├── search-users.blade.php
│   │   └── user-profile.blade.php
│   └── auth/
└── css/

storage/
└── app/
    └── public/
        └── profile_images/
```

## 🧪 Testing

### Ejecutar Tests
```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter PetTest

# Tests con cobertura
php artisan test --coverage
```

### Tipos de Tests Incluidos
- **Unit Tests**: Servicios y repositorios
- **Feature Tests**: Controllers y rutas
- **Integration Tests**: APIs externas
- **Validation Tests**: Form requests

## 🔒 Consideraciones de Seguridad

### Implementadas
- ✅ **Rate Limiting**: 3 intentos de login con cooldown
- ✅ **CSRF Protection**: Tokens en formularios
- ✅ **Validación de archivos**: Solo imágenes, tamaño limitado
- ✅ **Sanitización de entradas**: Form Requests
- ✅ **Rutas protegidas**: Middleware de autenticación
- ✅ **SQL Injection**: Uso de Eloquent ORM

### Recomendaciones para Producción
- Usar HTTPS en producción
- Configurar certificados SSL para APIs externas
- Implementar backup automático de base de datos
- Configurar logging centralizado
- Usar variables de entorno para APIs keys

## 📞 Soporte

Para problemas o consultas sobre el proyecto:

1. **Verificar logs**: `storage/logs/laravel.log`
2. **Limpiar cache**: `php artisan config:clear && php artisan route:clear`
3. **Regenerar autoload**: `composer dump-autoload`

## 🏗️ Decisiones Técnicas

### ¿Por qué Repository Pattern?
- Abstrae la lógica de acceso a datos
- Facilita testing con mocks
- Permite cambiar implementaciones sin afectar servicios

### ¿Por qué Service Layer?
- Separa lógica de negocio de controllers
- Facilita reutilización de código
- Mejora mantenibilidad del código

### ¿Por qué Alpine.js?
- Framework ligero para interactividad
- Sintaxis declarativa similar a Vue.js
- Integración perfecta con Tailwind CSS

## 🚀 Próximas Mejoras

- [ ] API REST completa para móviles
- [ ] Sistema de notificaciones
- [ ] Recordatorios de vacunas
- [ ] Historial médico de mascotas
- [ ] Integración con más APIs de mascotas
- [ ] Sistema de citas veterinarias

---

**Desarrollado con ❤️ usando Laravel y las mejores prácticas de desarrollo**
