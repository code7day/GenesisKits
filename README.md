# Genesis Kit v1.0.0

**Genesis Kit** es un starter kit de producción diseñado para aplicaciones de alto rendimiento y escalables. Combina la experiencia de desarrollo del TALL Stack (Tailwind, Alpine, Laravel, Livewire) con la potencia de Filament 4 para la gestión backend, todo construido sobre **Laravel 12**.

No es solo una plantilla; es una base pre-optimizada que resuelve problemas arquitectónicos comunes desde el primer día.

## 1. Stack Tecnológico

Este kit integra las siguientes tecnologías y configuraciones:

*   **Core**: Laravel 12
*   **Frontend**: Livewire 3 + Volt (Functional API) + Alpine.js
*   **Diseño**: Tailwind CSS v3
*   **Dashboard Usuario**: Laravel Jetstream
*   **Panel Admin**: Filament 4
*   **Base de Datos**: MySQL (Preconfigurado)
*   **Entorno Dev**: Vite con soporte SSL (https://)
*   **Testing**: Pest

## 2. Arquitectura de Rutas y Propósito

Hemos diseñado una arquitectura de doble entorno para separar estrictamente la experiencia del "Usuario Final" de la "Gestión Empresarial".

### Zona 1: Pública y Usuario (`/` y `/me`)
Diseñada para tus clientes SaaS o usuarios públicos.

*   **`/` (Landing Pública)**: Páginas de marketing y contenido. Alto rendimiento, SEO-ready con Blade/Livewire.
*   **`/me` (Zona Privada de Usuario)**: El área privada para usuarios registrados.
    *   **Tecnología**: Jetstream.
    *   **Propósito**: Gestión de perfil, suscripciones, acceso a datos personales.
    *   **Razón**: Los usuarios esperan una experiencia de "App" limpia y con marca, distinta de un panel de administración de base de datos.

### Zona 2: Backoffice (`/dashboard`)
Diseñada para ti, tu personal o administradores de inquilinos (tenants).

*   **`/dashboard` (Backoffice)**: El centro de comando.
    *   **Tecnología**: Filament 4.
    *   **Propósito**: Gestión de usuarios, moderación de contenido, analíticas, configuración del sistema.
    *   **Razón**: Filament permite una generación rápida de CRUDs y tablas/formularios potentes, asegurando alta productividad para herramientas internas.

## 3. Optimizaciones Core (Nativas)

La mayoría de proyectos Laravel sufren problemas de N+1 y brechas de seguridad al acercarse a producción. Genesis Kit los previene activamente.

### Prevención de N+1 (Strict Mode)
Forzamos el **Strict Mode** en entornos no productivos (`local`, `testing`). Esto te obliga a escribir consultas eficientes durante el desarrollo.

*   **Mecanismo**: `Model::shouldBeStrict(! app()->isProduction())` en `AppServiceProvider`.
*   **Comportamiento**: Si intentas acceder a una relación que no fue cargada ansiosamente (eager loaded), la app lanzará una excepción inmediatamente.

### Control de Acceso y Seguridad
El acceso al Backoffice (`/dashboard`) está **estrictamente denegado por defecto** en producción.

*   **Mecanismo**: Contrato `FilamentUser` implementado en el modelo `User`.
*   **Configuración**:
    *   **Local**: Abierto a todos para facilitar el desarrollo.
    *   **Producción**: **Debes definir la lógica** en `User::canAccessPanel()`. Por defecto es `false` (seguro).

---

## 4. Configuración de Entorno Local (Desarrollo)

Sigue estos pasos para levantar el proyecto en un nuevo entorno de desarrollo.

### 1. Clonar y Configurar

```bash
# 1. Clona el repositorio
git clone [URL-DE-TU-REPO-GIT] genesis-kit
cd genesis-kit

# 2. Instala dependencias de Composer
composer install

# 3. Copia el archivo de entorno
cp .env.example .env

# 4. Genera la llave de la aplicación
php artisan key:generate
```

### 2. Configurar .env

Abre tu archivo `.env` y configura tus variables de entorno locales.

**Importante**: Para que el SSL local funcione correctamente, `APP_URL` debe estar configurado con `https`.

```dotenv
# .env
APP_URL=https://genesis.host # (O el dominio local que uses)

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=genesis_kit
DB_USERNAME=root
DB_PASSWORD=tu_contraseña_de_bd
```

### 3. Base de Datos y Dependencias Frontend

```bash
# 5. Ejecuta las migraciones (esto creará todas las tablas)
php artisan migrate

# 6. Instala las dependencias de Node.js
npm install
```

### 4. Crear tu Usuario Administrador

```bash
# 7. Crea tu primer usuario para Filament y Jetstream
php artisan make:filament-user
```
(Sigue las instrucciones interactivas para crear tu cuenta).

### 5. Ejecutar el Servidor de Desarrollo (Importante)

```bash
# 8. Inicia el servidor de desarrollo (PHP, Vite, etc.)
composer run dev
```

**¡ATENCIÓN: Error de SSL esperado!**

La primera vez que cargues `https://genesis.host`, es probable que la página se vea rota y la consola muestre un error `net::ERR_CERT_AUTHORITY_INVALID`.

**Solución (Solo la primera vez):**

1.  Abre una nueva pestaña en tu navegador.
2.  Navega directamente a la URL del servidor de Vite: `https://[::1]:5173` (o `https://localhost:5173`).
3.  Verás una advertencia de "Conexión no privada".
4.  Haz clic en "Avanzado" y luego en "Continuar a [::1] (no seguro)".
5.  Refresca la pestaña de tu aplicación (`https://genesis.host`). ¡Todo cargará correctamente!

### 6. Acceso a la Aplicación

Ya estás listo para trabajar:

*   **App/Login**: `https://genesis.host/login`
*   **Zona Privada de Usuario (Perfil)**: `https://genesis.host/me`
*   **Backoffice de Gestión (Filament)**: `https://genesis.host/dashboard`
*   **Debug Telescope**: `https://genesis.host/telescope`

## Despliegue a Producción

Sigue esta lista de verificación para desplegar Genesis Kit a un servidor de producción.

### 1. Configuración de Servidor

*   Asegúrate de que tu servidor cumple con los requisitos (PHP 8.3+, MySQL, etc.).
*   Asegúrate de que OPcache esté instalado y habilitado en tu `php.ini` para un rendimiento óptimo.
*   Configura tu servidor web (Nginx/Apache) para que apunte al directorio `/public`.
*   Asegura los permisos correctos para los directorios `storage` y `bootstrap/cache`.

### 2. Variables de Entorno (.env)

Configura tu archivo `.env` de producción con los valores correctos. Los más importantes son:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio-en-produccion.com

DB_DATABASE=tu_db_de_produccion
DB_USERNAME=tu_usuario_de_prod
DB_PASSWORD=tu_pass_de_prod
```

### 3. Comandos de Despliegue

Ejecuta estos comandos en tu servidor (o en tu script de CI/CD) en cada despliegue:

```bash
# 1. Instala dependencias de Composer optimizadas para producción
composer install --no-dev --optimize-autoloader

# 2. Instala y compila assets de frontend
npm install
npm run build

# 3. Ejecuta migraciones (el flag --force es necesario en producción)
php artisan migrate --force

# 4. Optimiza la aplicación (¡MUY IMPORTANTE!)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Optimiza Filament (¡CRÍTICO PARA EL RENDIMIENTO!)
php artisan filament:optimize
```


## Licencia

Este proyecto está bajo la Licencia MIT.
