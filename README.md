# LecturaXP

Aplicación web para el seguimiento personal de lecturas, desarrollada como proyecto de fin de ciclo con **Laravel 12**.

## Descripción

LecturaXP permite a los usuarios registrar y gestionar su actividad lectora: añadir libros con toda su información, llevar un diario de sesiones de lectura con páginas leídas y comentarios, y visualizar el progreso en el dashboard personal. Incluye un sistema de roles (administrador / usuario) que controla el acceso a las distintas funcionalidades.

## Tecnologías

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.2 + Laravel 12 |
| Autenticación | Laravel Breeze |
| Frontend | Blade + TailwindCSS + Vite |
| Base de datos | MySQL (via XAMPP) |
| Tests | PHPUnit 11 |

## Funcionalidades principales

- Registro e inicio de sesión con autenticación completa (Laravel Breeze)
- CRUD de libros (título, autor, ISBN, género, portada, descripción, año)
- Registro de sesiones de lectura por libro (fecha, páginas leídas, comentarios)
- Dashboard con resumen de actividad del usuario
- Control de acceso por roles mediante Policies y Middleware
- Validación de formularios con Form Requests

## Instalación

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio>
cd lecturaxp

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar la base de datos en .env y ejecutar migraciones
php artisan migrate --seed

# 6. Compilar assets
npm run build

# 7. Iniciar servidor
php artisan serve
```

> Requiere XAMPP (MySQL activo) o cualquier servidor MySQL accesible.

## Tests

```bash
php artisan test
```

## Licencia

Proyecto académico — Uso educativo.
