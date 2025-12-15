Feriados App – Backend
Backend desarrollado en Laravel para la gestión de feriados, con carga inicial automática desde una API externa.
🛠 Tecnologías
·	Laravel 12
·	PHP 8.2+
·	MySQL / MariaDB
📦 Requisitos
·	PHP 8.2 o superior
·	Composer
·	MySQL o MariaDB
🚀 Instalación
Clonar el repositorio:
git clone https://github.com/algobupe-dev/feriados-app.git
cd feriados-app/backend

Instalar dependencias:
composer install

Configurar variables de entorno:
cp .env.example .env
php artisan key:generate

Configurar la base de datos en el archivo .env y crear la base de datos.
🧱 Migraciones y carga inicial de datos
Ejecutar migraciones y seeders:
php artisan migrate --seed

Este proceso carga automáticamente los feriados desde la API externa:
https://api.boostr.cl/holidays.json
▶️ Levantar el servidor
php artisan serve

El backend estará disponible en:
http://127.0.0.1:8000
🔗 Endpoints disponibles
·	
·	Listar feriados (paginado):
·	GET /api/holidays

·	
·	Crear feriado:
·	POST /api/holidays

·	
·	Actualizar feriado:
·	PUT /api/holidays/{id}

·	
·	Eliminar feriado:
·	DELETE /api/holidays/{id}

⚙️ Importación manual de feriados
Además de la carga inicial automática, existe un comando Artisan para reimportar feriados:
php artisan holidays:import

🧠 Arquitectura
·	Service: HolidayImportService
·	Seeder: HolidaySeeder
·	Command: holidays:import
·	Importación idempotente mediante updateOrCreate

⚠️ El frontend será implementado en Nuxt.js y se documentará en este mismo README.
