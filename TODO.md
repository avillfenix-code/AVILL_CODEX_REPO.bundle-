# TODO — AVILL Producción

## Backend Laravel

- [x] **Livewire controllers completos** — `AvillManualFareLivewire`, `AvillServiceAreaLivewire`, `AvillSurchargeLivewire`, `AvillHolidayLivewire`: CRUD con modales crear/editar ✅
- [x] **Livewire Tables** — columnas, búsqueda y ordenamiento para las 4 secciones ✅
- [x] **Registrar ServiceProvider** — `AvillServiceProvider` registra `AvillFareService` como singleton ✅ _(agregar a config/app.php en producción)_
- [x] **Permisos Spatie** — `AvillPermissionsSeeder` crea 8 permisos y los asigna a admin/city-admin ✅
- [x] **Rutas admin** — `/avill/fares`, `/avill/areas`, `/avill/surcharges`, `/avill/holidays` en web.php ✅
- [x] **Seeders de datos iniciales** — 27 zonas Quibdó, 4 recargos, 38 festivos 2025-2026 ✅
- [ ] **php artisan migrate** — ejecutar en servidor de producción _(requiere servidor real)_
- [ ] **Verificar regex teléfono** — en Admin → Configuración cambiar código de país a `+57 CO`

## Flutter Apps

- [ ] **google-services.json** — las 3 apps apuntan al Firebase de Glover original → crear proyecto Firebase AVILL y reemplazar en `*/android/app/google-services.json`
- [ ] **Constante API_BASE_URL** — verificar en cada app que apunte al servidor AVILL
- [ ] **flutter analyze** — ejecutar en ambiente con Flutter SDK y corregir warnings
- [ ] **flutter build apk** — compilar release en ambiente con SDK
- [ ] **Notificaciones push** — configurar FCM con las nuevas credenciales Firebase AVILL

## Datos de configuración (admin panel — post-deploy)

- [ ] **Zonas AVILL** → `admin/avill/areas` — cargar polígonos GeoJSON de Quibdó _(los 27 barrios ya están cargados, faltan los polígonos)_
- [ ] **Tarifas** → `admin/avill/fares` — configurar tarifas COP por par de zonas
- [ ] **Recargos** → `admin/avill/surcharges` — ajustar montos reales (los COP 2.000-4.000 son estimados)
- [ ] **Configuración país** → Admin → Settings → Country Code: `CO`, Phone: `+57`, Currency: `COP`

## Infraestructura

- [ ] **Servidor** — Laravel en PHP 8.1+, MySQL 8, Redis para colas
- [ ] **Variables .env** — `APP_URL`, `DB_*`, `PUSHER_*`, `FIREBASE_*`, `GOOGLE_MAPS_KEY`
- [ ] **Colas** — `php artisan queue:work` corriendo con Supervisor
- [ ] **Google Maps API** — verificar que la key de Maps funcione en Colombia (restricción por país/IP)

## Verificación final

- [ ] Crear viaje de prueba en Quibdó → confirmar que aplica tarifa fija (`avill_fixed_fare: true` en la API)
- [ ] Crear viaje fuera de Quibdó → confirmar que usa pricing estándar Glover (km + minuto)
- [ ] Probar recargo nocturno (después de las 22:00)
- [ ] Probar cotización manual (`pricing_mode = 'cotizacion_manual'`) → operador recibe alerta
- [ ] Probar delivery/encomienda con AvillFareService
