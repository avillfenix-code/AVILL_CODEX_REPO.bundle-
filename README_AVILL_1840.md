# AVILL PATCH — Glover 1.8.40
**Fecha:** 2026-06-14  
**Compatible con:** Glover 1.8.40 (Update Owners)  
**No modifica** el modelo base de Glover para seguir recibiendo actualizaciones futuras.

---

## QUÉ HACE ESTE PARCHE

1. **Tarifa fija Quibdó/Chocó** — Motor AVILL integrado en TaxiTrait y PackageOrderController. Si el origen está en zona Quibdó, cobra tarifa fija del panel. Si no, usa el motor genérico de Glover (km + minuto).
2. **Panel editable de tarifas Quibdó** — 4 tablas del admin: Tarifas, Zonas, Recargos, Festivos.
3. **Apps 100% en español** — UI, botones, razones de cancelación, confirmaciones.
4. **Fix overflow de texto** — Notas de pedido se ajustan dentro del recuadro en los 3 apps.

---

## PASO 1 — APLICAR ACTUALIZACIÓN GLOVER 1.8.40

```bash
composer install
composer dump-autoload
npm install && npm run prod
php artisan migrate
```

## PASO 2 — COPIAR ARCHIVOS DEL PARCHE AVILL

Copia cada carpeta sobre tu proyecto respetando la estructura.

## PASO 3 — MIGRACIONES AVILL

```bash
php artisan migrate
```

## PASO 4 — REGISTRAR RUTAS EN web.php

```php
Route::get('avill/fares',    [AvillManualFareLivewire::class, 'index'])->name('avill.fares');
Route::get('avill/areas',    [AvillServiceAreaLivewire::class,'index'])->name('avill.areas');
Route::get('avill/surcharges',[AvillSurchargeLivewire::class,'index'])->name('avill.surcharges');
Route::get('avill/holidays', [AvillHolidayLivewire::class,  'index'])->name('avill.holidays');
```

## PASO 5 — CARGAR DATOS INICIALES DE QUIBDÓ

Desde el panel admin:
- **AVILL → Zonas** → Crear barrios/comunas con polígonos GeoJSON
- **AVILL → Tarifas** → Crear tarifas fijas por ruta
- **AVILL → Festivos** → Cargar festivos colombianos 2026

## PASO 6 — FLUTTER

```bash
flutter clean && flutter pub get && flutter build apk --release
```
