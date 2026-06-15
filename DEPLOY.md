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

Esto agrega/sobreescribe solo archivos seguros:
- `app/Services/AvillFareService.php` ← nuevo
- `app/Providers/AvillServiceProvider.php` ← nuevo
- `app/Traits/TaxiTrait.php` ← reemplaza el de Glover (compatible)
- `app/Models/Avill*.php` (4 modelos) ← nuevos
- `app/Http/Livewire/Avill*.php` (4 controllers) ← nuevos
- `app/Http/Livewire/Tables/Avill*.php` (4 tables) ← nuevas
- `database/migrations/` (5 migraciones AVILL) ← nuevas
- `database/seeders/Avill*.php` (5 seeders) ← nuevos
- `resources/lang/es/avill.php` ← nuevo
- `resources/views/livewire/avill_*.blade.php` (4 vistas) ← nuevas
- `resources/views/layouts/partials/nav/menu.blade.php` ← reemplaza (con sección AVILL añadida)

> **Los controllers API de Glover NO están en `backend/` para no sobreescribirlos.**
> El merge se hace en el Paso 5 abajo, con los snippets de `MERGE_SNIPPETS/`.

## 3. Registrar rutas AVILL

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

## 5. Merge manual de controllers API

Los snippets exactos están en `MERGE_SNIPPETS/`.

### RegularOrderController  (`app/Http/Controllers/API/RegularOrderController.php`)

**a)** Agregar el import (junto a los otros `use` del archivo):
```php
use App\Services\AvillFareService;
```

**b)** Agregar la propiedad en el class:
```php
protected AvillFareService $avillFare;
```

**c)** En el `__construct()` existente, agregar el parámetro:
```php
public function __construct(AvillFareService $avillFare, /* otros params existentes */) {
    $this->avillFare = $avillFare;
    // ... resto del constructor sin cambios ...
}
```

**d)** Al **inicio** del método `deliveryFeeSummary()`, antes del código Glover:
```php
$stops = $request->input('stops');
try {
    $avillQuote = $this->avillFare->quoteForDelivery($stops);
} catch (\Throwable $e) {
    logger()->error('AvillFareService error: ' . $e->getMessage());
    $avillQuote = null;
}
if ($avillQuote) {
    if ($avillQuote['pending']) {
        return response()->json([
            'delivery_fee' => 0, 'avill_fixed_fare' => false,
            'avill_fare_pending' => true, 'avill_quote' => $avillQuote,
            'message' => $avillQuote['message'],
        ]);
    }
    return response()->json([
        'delivery_fee' => $avillQuote['total'], 'avill_fixed_fare' => true,
        'avill_fare_pending' => false, 'avill_quote' => $avillQuote,
    ]);
}
// → continúa con el código Glover original
```

### PackageOrderController  (`app/Http/Controllers/API/PackageOrderController.php`)

Mismos pasos a/b/c. En el método `summary()`, mismo bloque con diferente respuesta:
```php
// (el mismo bloque try/catch + if $avillQuote, pero con)
return response()->json([
    'total' => $avillQuote['total'],
    'delivery_fee' => $avillQuote['base_amount'],
    'management_fee' => $avillQuote['management_surcharge_amount'],
    'surcharge_total' => $avillQuote['surcharge_total'],
    'avill_fixed_fare' => true, 'avill_fare_pending' => false,
    'avill_quote' => $avillQuote,
]);
```

## 4. Registrar el ServiceProvider AVILL

En `config/app.php`, dentro del array `providers`, agregar antes del cierre del array:

```php
App\Providers\AvillServiceProvider::class,
```

## 6. Ejecutar migraciones y seeders

```bash
php artisan migrate
php artisan db:seed --class=AvillDatabaseSeeder
```

Esto crea las 4 tablas AVILL y carga:
- Permisos Spatie para rol `admin` y `city-admin`
- 27 zonas de Quibdó (sin polígonos GeoJSON aún)
- 4 recargos base (nocturno, dominical, festivo, nocturno festivo)
- 38 festivos colombianos 2025-2026

## 7. Configurar el admin panel

```
Admin → Configuración → País → Colombia (CO)
Admin → Configuración → Moneda → Peso Colombiano (COP)
Admin → Configuración → Código de país → +57
```

## 8. Aplicar el parche Flutter

```bash
cp -rT apps/customer/ /path/to/flutter/customer/
cp -rT apps/driver/   /path/to/flutter/driver/
cp -rT apps/vendor/   /path/to/flutter/vendor/
```

## 9. Configurar Firebase

1. Crear proyecto en [Firebase Console](https://console.firebase.google.com)
2. Agregar app Android/iOS para cada una de las 3 apps (customer, driver, vendor)
3. Descargar `google-services.json` (Android) y `GoogleService-Info.plist` (iOS)
4. Reemplazar los archivos en:
   - `customer/android/app/google-services.json`
   - `driver/android/app/google-services.json`
   - `vendor/android/app/google-services.json`

## 10. Cargar polígonos GeoJSON de Quibdó

Para que el sistema de tarifa fija detecte automáticamente en qué zona está el origen:

1. Obtener el GeoJSON de cada barrio (de OpenStreetMap, Google Maps o IGAC)
2. Ir a Admin → AVILL → Zonas
3. Editar cada zona y pegar el polígono GeoJSON en el campo correspondiente

Sin polígonos, el sistema cae al pricing estándar de Glover (km + minuto).

## 11. Compilar y publicar las apps Flutter

```bash
cd customer && flutter build apk --release
cd driver   && flutter build apk --release
cd vendor   && flutter build apk --release
```

## 12. Verificación final

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
