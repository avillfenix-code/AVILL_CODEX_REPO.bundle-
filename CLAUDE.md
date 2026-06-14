# AVILL — Contexto completo del proyecto

## Qué es este proyecto

**AVILL** es un fork personalizado de **Glover 1.8.40** (super-app de delivery/taxi/encomiendas en Laravel + Flutter) adaptado para **Quibdó, Chocó, Colombia**. La diferencia principal con Glover estándar es el sistema de **tarifa fija** (precio predeterminado por zona, sin cobro por km/minuto).

## Estructura del repositorio

```
AVILL_CODEX_REPO.bundle-/
├── backend/                  ← PARCHE AVILL sobre Glover base
│   ├── app/Services/AvillFareService.php        ← Servicio principal de tarifas
│   ├── app/Traits/TaxiTrait.php                 ← Override del TaxiTrait de Glover
│   ├── app/Models/Avill*.php                    ← 4 modelos AVILL
│   ├── database/migrations/2024_01_01_000*      ← 4 migraciones AVILL
│   ├── resources/lang/es/avill.php              ← Traducciones ES
│   └── resources/views/layouts/partials/nav/menu.blade.php  ← Menu admin
├── apps/                     ← PARCHE AVILL para Flutter (customer, driver, vendor)
├── glover_1840_update/       ← BASE Glover 1.8.40 Update Owners (referencia)
│   ├── routes/web.php        ← Incluye rutas AVILL al final
│   └── ...
├── customer/                 ← App Flutter cliente (base Glover)
├── driver/                   ← App Flutter conductor (base Glover)
└── vendor/                   ← App Flutter vendedor (base Glover)
```

## Cómo funciona el pricing AVILL

1. `TaxiTrait::getTaxiOrderTotalPrice()` llama primero a `AvillFareService::quoteForTrip()`
2. `AvillFareService` usa **ray-casting** para detectar si el origen está en una zona de Quibdó (polígono GeoJSON almacenado en `avill_service_areas.map_polygon`)
3. Si está en zona → busca tarifa en `avill_manual_fares` para el par origen-destino
4. Si `pricing_mode = 'tarifa_fija'` → devuelve precio fijo
5. Si `pricing_mode = 'cotizacion_manual'` → devuelve `pending: true` (operador confirma)
6. Aplica recargos de `avill_surcharges` (nocturno, domingo, festivo, lluvia)
7. Si no es zona AVILL → cae al pricing estándar de Glover (km + minuto)

## Tablas de base de datos AVILL

| Tabla | Descripción |
|-------|-------------|
| `avill_service_areas` | Zonas de Quibdó con polígono GeoJSON |
| `avill_manual_fares` | Tarifas por par origen-destino |
| `avill_surcharges` | Recargos (nocturno, domingo, festivo, lluvia) |
| `avill_holidays` | Festivos colombianos |

## Rutas admin AVILL (Laravel)

```
/avill/fares      → AvillManualFareLivewire   (Tarifas Quibdó)
/avill/areas      → AvillServiceAreaLivewire  (Zonas AVILL)
/avill/surcharges → AvillSurchargeLivewire    (Recargos)
/avill/holidays   → AvillHolidayLivewire      (Festivos)
```

## APIs modificadas

- `GET /api/delivery/fee/summary` → `RegularOrderController::deliveryFeeSummary()` — llama AvillFareService primero
- `POST /api/package/order/summary` → `PackageOrderController::summary()` — ídem
- Response incluye: `avill_fixed_fare`, `avill_fare_pending`, `avill_quote`

## Flutter — cambios AVILL

- `apps/customer/lib/constants/app_strings.dart` → razones de cancelación en español
- `apps/driver/assets/lang/es.json` → 399 claves de traducción ES
- Etiquetas en español: "Domiciliario", "Chatear con domiciliario", etc.

## Configuración del entorno de desarrollo

```bash
# Git branch de trabajo
git checkout claude/dreamy-brahmagupta-c6njv0

# Git proxy (entorno cloud Claude Code)
git remote set-url origin http://local_proxy@127.0.0.1:36733/git/avillfenix-code/AVILL_CODEX_REPO.bundle-
```

## Para aplicar el parche en producción

1. Copiar `backend/` sobre el directorio raíz de Glover instalado
2. Copiar `apps/` sobre el directorio de las apps Flutter
3. Ejecutar `php artisan migrate` (crea las 4 tablas AVILL)
4. Cargar polígonos GeoJSON de Quibdó en admin → AVILL → Zonas
5. Configurar tarifas en admin → AVILL → Tarifas Quibdó
6. Configurar recargos en admin → AVILL → Recargos
7. Cargar festivos colombianos en admin → AVILL → Festivos

## Compatibilidad

- Glover base: **1.8.40 Update Owners** (verificado con diff — sin conflictos)
- AVILL solo agrega código, no modifica lógica existente de Glover
- TaxiTrait AVILL extiende el original con `if ($avillQuote) return $avillQuote`

## Pendiente (ver TODO.md)

- Livewire tables (AvillManualFareLivewire, etc.) — esqueleto básico creado, faltan columnas/filtros completos
- `google-services.json` en las 3 apps Flutter → apuntar a Firebase de AVILL
- Verificar regex de teléfono en AuthController (actualmente +225 Costa de Marfil → cambiar a +57 Colombia)
- `flutter analyze` y `flutter build` en ambiente con Flutter SDK
- Cargar datos reales en BD (zonas, tarifas, recargos, festivos)
