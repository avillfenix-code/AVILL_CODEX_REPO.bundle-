# Guía de Despliegue — AVILL en Producción

## 1. Preparar el servidor

El servidor debe tener Glover 1.8.40 instalado y funcionando. Si no lo tiene:

```bash
# Instalar dependencias base de Glover primero
composer install
npm install && npm run prod
cp .env.example .env
php artisan key:generate
```

## 2. Aplicar el parche AVILL (backend)

Copiar los archivos del directorio `backend/` sobre el directorio raíz de Glover:

```bash
cp -rT backend/ /var/www/html/glover/
```

Esto sobreescribe:
- `app/Services/AvillFareService.php`
- `app/Traits/TaxiTrait.php`
- `app/Models/Avill*.php` (4 modelos)
- `app/Http/Livewire/Avill*.php` (4 controllers)
- `app/Http/Livewire/Tables/Avill*.php` (4 tables)
- `app/Http/Controllers/API/RegularOrderController.php`
- `app/Http/Controllers/API/PackageOrderController.php`
- `database/migrations/2024_01_01_000*.php` (4 migraciones)
- `database/seeders/Avill*.php` (4 seeders)
- `resources/lang/es/avill.php`
- `resources/views/livewire/avill_*.blade.php` (4 vistas)
- `resources/views/layouts/partials/nav/menu.blade.php`

## 3. Registrar rutas AVILL

Agregar al final de `routes/web.php` (dentro del middleware de auth/admin), antes del cierre `});`):

```php
// AVILL — tarifas fijas Quibdó
Route::get('avill/fares',      \App\Http\Livewire\AvillManualFareLivewire::class)->name('avill.fares');
Route::get('avill/areas',      \App\Http\Livewire\AvillServiceAreaLivewire::class)->name('avill.areas');
Route::get('avill/surcharges', \App\Http\Livewire\AvillSurchargeLivewire::class)->name('avill.surcharges');
Route::get('avill/holidays',   \App\Http\Livewire\AvillHolidayLivewire::class)->name('avill.holidays');
```

> El archivo `glover_1840_update/routes/web.php` en este repositorio ya tiene las rutas incluidas como referencia.

## 4. Ejecutar migraciones y seeders

```bash
php artisan migrate
php artisan db:seed --class=AvillDatabaseSeeder
```

Esto crea las 4 tablas AVILL y carga:
- 27 zonas de Quibdó (sin polígonos GeoJSON aún)
- 4 recargos base (nocturno, dominical, festivo, nocturno festivo)
- 38 festivos colombianos 2025-2026

## 5. Configurar el admin panel

```
Admin → Configuración → País → Colombia (CO)
Admin → Configuración → Moneda → Peso Colombiano (COP)
Admin → Configuración → Código de país → +57
```

## 6. Aplicar el parche Flutter

```bash
cp -rT apps/customer/ /path/to/flutter/customer/
cp -rT apps/driver/   /path/to/flutter/driver/
cp -rT apps/vendor/   /path/to/flutter/vendor/
```

## 7. Configurar Firebase

1. Crear proyecto en [Firebase Console](https://console.firebase.google.com)
2. Agregar app Android/iOS para cada una de las 3 apps (customer, driver, vendor)
3. Descargar `google-services.json` (Android) y `GoogleService-Info.plist` (iOS)
4. Reemplazar los archivos en:
   - `customer/android/app/google-services.json`
   - `driver/android/app/google-services.json`
   - `vendor/android/app/google-services.json`

## 8. Cargar polígonos GeoJSON de Quibdó

Para que el sistema de tarifa fija detecte automáticamente en qué zona está el origen:

1. Obtener el GeoJSON de cada barrio (de OpenStreetMap, Google Maps o IGAC)
2. Ir a Admin → AVILL → Zonas
3. Editar cada zona y pegar el polígono GeoJSON en el campo correspondiente

Sin polígonos, el sistema cae al pricing estándar de Glover (km + minuto).

## 9. Compilar y publicar las apps Flutter

```bash
cd customer && flutter build apk --release
cd driver   && flutter build apk --release
cd vendor   && flutter build apk --release
```

## 10. Verificación final

```bash
# Probar que las migraciones están ok
php artisan migrate:status | grep avill

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

Luego crear un viaje de prueba en Quibdó y verificar que la API responda con `avill_fixed_fare: true`.
