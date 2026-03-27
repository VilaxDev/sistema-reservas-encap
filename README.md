# 🎭 Sistema de Reservas de Auditorio

Sistema web para la gestión y reserva de asientos en auditorios. Permite a los usuarios autenticados seleccionar y reservar asientos de forma visual, mientras que los administradores gestionan usuarios, auditorios y todas las reservas del sistema.

---

## 📋 Características

### Módulos principales

- **Dashboard** — Panel con estadísticas en tiempo real (usuarios, auditorios, reservas activas, reservas del día) y accesos rápidos.
- **Gestión de Usuarios (CRUD)** — Crear, editar, eliminar usuarios y asignar roles (admin/usuario). Solo accesible para administradores.
- **Gestión de Auditorios (CRUD)** — Crear, editar, eliminar auditorios con generación automática de asientos según filas y columnas configuradas.
- **Mapa Visual de Asientos** — Vista interactiva tipo cine que muestra asientos disponibles (verde), ocupados (rojo) y seleccionados (azul).
- **Reserva de Asientos** — Flujo de reserva intuitivo: seleccionar auditorio → elegir fecha → clic en asiento → confirmar.
- **Mis Reservas** — Cada usuario ve sus propias reservas; los administradores ven todas.

### Validaciones y permisos

- Un usuario **no puede reservar** un asiento que ya está ocupado para la misma fecha.
- Un usuario **no puede tener más de una reserva activa** por auditorio en la misma fecha.
- Solo los **administradores** pueden gestionar usuarios y auditorios.
- Los usuarios solo pueden **ver y cancelar** sus propias reservas.

---

## 🛠️ Tecnologías

| Tecnología | Versión |
|---|---|
| **PHP** | 8.2+ |
| **Laravel** | 12.x |
| **Laravel Breeze** | 2.x (Autenticación) |
| **Bootstrap** | 5.3.3 (CDN) |
| **Bootstrap Icons** | 1.11.3 (CDN) |
| **MySQL** | 5.7+ / 8.x |
| **Composer** | 2.x |

---

## 📁 Estructura del Proyecto

```
sistema_reserva/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuditorioController.php    # CRUD de auditorios
│   │   │   ├── ReservaController.php      # Gestión de reservas
│   │   │   ├── UserController.php         # CRUD de usuarios
│   │   │   └── ProfileController.php      # Perfil de usuario (Breeze)
│   │   └── Middleware/
│   │       └── AdminMiddleware.php        # Middleware de permisos admin
│   ├── Models/
│   │   ├── Asiento.php                    # Modelo de asiento
│   │   ├── Auditorio.php                  # Modelo de auditorio
│   │   ├── Reserva.php                    # Modelo de reserva
│   │   └── User.php                       # Modelo de usuario (con roles)
│   └── Providers/
│       └── AppServiceProvider.php         # Configuración de paginación Bootstrap
├── database/
│   ├── migrations/
│   │   ├── create_users_table             # Tabla de usuarios
│   │   ├── add_role_to_users_table        # Campo role (admin/usuario)
│   │   ├── create_auditorios_table        # Tabla de auditorios
│   │   ├── create_asientos_table          # Tabla de asientos
│   │   └── create_reservas_table          # Tabla de reservas
│   └── seeders/
│       └── DatabaseSeeder.php             # Datos de prueba
├── resources/views/
│   ├── layouts/
│   │   └── bootstrap.blade.php            # Layout principal con Bootstrap 5
│   ├── auditorios/                        # Vistas de auditorios (index, create, edit, show)
│   ├── usuarios/                          # Vistas de usuarios (index, create, edit)
│   ├── reservas/                          # Vistas de reservas (index, create, show)
│   ├── dashboard.blade.php                # Dashboard principal
│   └── auth/                              # Vistas de autenticación (Breeze)
└── routes/
    └── web.php                            # Definición de rutas
```

---

## ⚙️ Instalación

### Requisitos previos

- PHP 8.2 o superior
- Composer 2.x
- MySQL 5.7+ o 8.x
- Node.js y npm (para assets de Breeze)
- XAMPP, Laragon o servidor web compatible

### Pasos

1. **Clonar el repositorio**

```bash
git clone <url-del-repositorio> sistema_reserva
cd sistema_reserva
```

2. **Instalar dependencias de PHP**

```bash
composer install
```

3. **Configurar el archivo de entorno**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar la base de datos** en el archivo `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reserva
DB_USERNAME=root
DB_PASSWORD=
```

5. **Crear la base de datos**

```sql
CREATE DATABASE reserva;
```

6. **Ejecutar migraciones y seeders**

```bash
php artisan migrate --seed
```

7. **Instalar dependencias de Node.js** (para assets de autenticación Breeze)

```bash
npm install
npm run build
```

8. **Iniciar el servidor de desarrollo**

```bash
php artisan serve
```

La aplicación estará disponible en `http://127.0.0.1:8000`

---

## 🔑 Credenciales de Prueba

| Rol | Email | Contraseña |
|---|---|---|
| **Administrador** | `admin@admin.com` | `password` |
| **Usuario** | `usuario@demo.com` | `password` |

> Los datos de prueba incluyen 3 auditorios con asientos generados automáticamente.

---

## 🗃️ Base de Datos

### Diagrama de relaciones

```
users (1) ──────── (N) reservas
                         │
asientos (1) ───── (N) reservas
    │
auditorios (1) ── (N) asientos
```

### Tablas principales

| Tabla | Descripción |
|---|---|
| `users` | Usuarios del sistema con campo `role` (admin/usuario) |
| `auditorios` | Auditorios con nombre, descripción, filas y columnas |
| `asientos` | Asientos individuales (fila A-Z, número 1-N) vinculados a un auditorio |
| `reservas` | Reservas con usuario, asiento, fecha del evento y estado (reservado/cancelado) |

---

## 🔗 Rutas Principales

### Rutas públicas (autenticadas)

| Método | URI | Descripción |
|---|---|---|
| `GET` | `/dashboard` | Dashboard principal |
| `GET` | `/auditorios` | Lista de auditorios |
| `GET` | `/auditorios/{id}` | Mapa de asientos del auditorio |
| `GET` | `/reservas` | Mis reservas |
| `GET` | `/reservas/crear/{auditorio}` | Seleccionar asiento para reservar |
| `POST` | `/reservas` | Confirmar reserva |
| `GET` | `/reservas/{id}` | Detalle de una reserva |
| `PATCH` | `/reservas/{id}/cancelar` | Cancelar una reserva |

### Rutas de administración (solo admin)

| Método | URI | Descripción |
|---|---|---|
| `GET` | `/admin/usuarios` | Lista de usuarios |
| `GET` | `/admin/usuarios/create` | Crear usuario |
| `PUT` | `/admin/usuarios/{id}` | Actualizar usuario |
| `DELETE` | `/admin/usuarios/{id}` | Eliminar usuario |
| `GET` | `/admin/auditorios` | Gestión de auditorios |
| `POST` | `/admin/auditorios` | Crear auditorio |
| `PUT` | `/admin/auditorios/{id}` | Actualizar auditorio |
| `DELETE` | `/admin/auditorios/{id}` | Eliminar auditorio |

---

## 🎨 Interfaz de Usuario

La interfaz está construida con **Bootstrap 5** vía CDN, sin dependencias locales de CSS framework.

- **Navbar** adaptativa: muestra menú de administración solo para admins.
- **Mapa de asientos** visual e interactivo con selección por clic.
- **Alertas flash** para mensajes de éxito, error y validación.
- **Tablas responsivas** con paginación estilizada con Bootstrap 5.
- **Cards** para listar auditorios con información resumida.
- **Badges** de colores para estados de reserva y roles de usuario.

---

## 📝 Licencia

Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).