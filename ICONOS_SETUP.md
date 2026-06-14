# Configurar Logos AVILL en las 3 Apps

## Archivos de imagen que necesitas

Para cada app (customer, driver, vendor), coloca estos archivos en `assets/images/`:

| Archivo | Uso | Tamaño mínimo |
|---|---|---|
| `app_icon.png` | Ícono de la app en launcher | 1024×1024 px, fondo blanco |
| `app_icon_foreground.png` | Capa frontal ícono adaptativo Android | 1024×1024 px, sin fondo |
| `splash_logo.png` | Logo en pantalla de carga | 400×400 px, sin fondo |

## Qué imagen va en cada app

| App | Logo a usar |
|---|---|
| `customer/assets/images/` | Logo **AVILL** (sin subtítulo) |
| `driver/assets/images/` | Logo **AVILL CONDUCTOR** |
| `vendor/assets/images/` | Logo **AVILL VENDEDOR** |

## Pasos para generar los íconos

```bash
# 1. Coloca los PNG en cada app (ver tabla arriba)

# 2. Customer app
cd customer
flutter pub get
dart run flutter_launcher_icons
dart run flutter_native_splash:create
cd ..

# 3. Driver app
cd driver
flutter pub get
dart run flutter_launcher_icons
dart run flutter_native_splash:create
cd ..

# 4. Vendor app
cd vendor
flutter pub get
dart run flutter_launcher_icons
dart run flutter_native_splash:create
cd ..
```

Esto genera automáticamente todos los tamaños de ícono para Android e iOS.

## Colores de la marca AVILL

| Color | Hex | Uso |
|---|---|---|
| Verde AVILL | `#2E8B2E` | Texto "Avill" y carrito |
| Amarillo AVILL | `#F5C400` | Barras "ill" y acento |
| Blanco | `#FFFFFF` | Fondo splash y ícono |

## Nombre de la app en el launcher

Cambiar en `android/app/src/main/AndroidManifest.xml`:
```xml
android:label="AVILL"         <!-- Customer -->
android:label="AVILL Conductor"  <!-- Driver -->
android:label="AVILL Vendedor"   <!-- Vendor -->
```

En iOS, cambiar en `ios/Runner/Info.plist`:
```xml
<key>CFBundleDisplayName</key>
<string>AVILL</string>   <!-- o AVILL Conductor / AVILL Vendedor -->
```
