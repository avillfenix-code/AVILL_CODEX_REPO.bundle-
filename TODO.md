# TODO — AVILL Producción

## Backend Laravel

- [ ] **Livewire tables completas** — `AvillManualFareLivewire`, `AvillServiceAreaLivewire`, `AvillSurchargeLivewire`, `AvillHolidayLivewire`: agregar columnas, filtros, paginación, modales de creación/edición
- [ ] **Registrar ServiceProvider** — verificar que `AvillFareService` esté registrado en `AppServiceProvider` o como singleton
- [ ] **Permisos Spatie** — agregar permisos `view-avill-*`, `manage-avill-*` en el seeder de roles
- [ ] **Verificar regex teléfono** — `AuthController` tiene `+225` (Costa de Marfil) → cambiar a `+57` (Colombia)
- [ ] **php artisan migrate** — ejecutar en servidor de producción para crear las 4 tablas AVILL
- [ ] **Seeders de datos iniciales** — festivos colombianos 2025-2026, zonas Quibdó, tarifas base

## Flutter Apps

- [ ] **google-services.json** — las 3 apps apuntan al Firebase de Glover original → crear proyecto Firebase AVILL y reemplazar
- [ ] **flutter analyze** — ejecutar en ambiente con Flutter SDK y corregir warnings
- [ ] **flutter build apk/ios** — compilar en ambiente real
- [ ] **Constante API_BASE_URL** — verificar que apunte al servidor AVILL, no al demo de Glover
- [ ] **Notificaciones push** — configurar FCM con las nuevas credenciales Firebase

## Datos de configuración (admin panel)

- [ ] **Zonas AVILL** → `admin/avill/areas` — cargar polígonos GeoJSON de Quibdó (barrios/comunas)
- [ ] **Tarifas** → `admin/avill/fares` — configurar tarifas por par de zonas
- [ ] **Recargos** → `admin/avill/surcharges` — nocturno (22:00-06:00), domingo, festivo, lluvia
- [ ] **Festivos** → `admin/avill/holidays` — festivos colombianos 2025 y 2026

## Infraestructura

- [ ] **Servidor** — Laravel en PHP 8.1+, MySQL 8, Redis para colas
- [ ] **Variables .env** — `APP_URL`, `DB_*`, `PUSHER_*`, `FIREBASE_*`, `GOOGLE_MAPS_KEY`
- [ ] **Colas** — `php artisan queue:work` corriendo con Supervisor
- [ ] **Google Maps API** — verificar que la key de Maps funcione en Colombia

## Verificación final

- [ ] Crear viaje de prueba en Quibdó → confirmar que aplica tarifa fija
- [ ] Crear viaje fuera de Quibdó → confirmar que usa pricing estándar Glover
- [ ] Probar recargo nocturno (después de las 22:00)
- [ ] Probar cotización manual (`pricing_mode = 'cotizacion_manual'`) → operador recibe alerta
- [ ] Probar delivery/encomienda con AvillFareService
