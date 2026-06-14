# Configuración Flutter — AVILL

## 1. Archivos que cambia el parche AVILL (`apps/`)

Al aplicar `cp -rT apps/ /ruta/apps/` se sobreescriben estos archivos:

| Archivo | Qué cambia |
|---|---|
| `customer/lib/constants/api.dart` | URL base → `https://TU_DOMINIO_AVILL.com/api` |
| `driver/lib/constants/api.dart`   | URL base → `https://TU_DOMINIO_AVILL.com/api` |
| `vendor/lib/constants/api.dart`   | URL base → `https://TU_DOMINIO_AVILL.com/api` |
| `customer/lib/constants/app_strings.dart` | Razones de cancelación en español |
| `driver/assets/lang/es.json`      | 399 claves de traducción en español |
| `driver/lib/view_models/…`        | Labels "Domiciliario", "Chatear con domiciliario" |
| `customer/lib/views/pages/…`      | Labels en español para detalles de pedido |
| `vendor/lib/views/pages/…`        | Labels en español para gestión de pedidos |

## 2. Cambiar la URL del servidor AVILL

**IMPORTANTE:** Antes de compilar, editar las 3 apps:

```
customer/lib/constants/api.dart  → línea 2
driver/lib/constants/api.dart    → línea 3
vendor/lib/constants/api.dart    → línea 3
```

Cambiar `TU_DOMINIO_AVILL.com` por el dominio real del servidor:

```dart
// ANTES (demo Glover)
static const defaultBaseUrl = "https://glover.edentech.online/api";

// DESPUÉS (servidor AVILL)
static const defaultBaseUrl = "https://avill.tudominio.com/api";
```

También se puede pasar en build sin tocar el código:
```bash
flutter build apk --dart-define=api=https://avill.tudominio.com/api
```

## 3. Configurar Firebase

### Crear proyecto Firebase
1. Ir a [console.firebase.google.com](https://console.firebase.google.com)
2. Crear proyecto: **AVILL**
3. Agregar app Android por cada una (3 total):

| App | Package name sugerido |
|---|---|
| Customer | `com.avill.customer` |
| Driver   | `com.avill.driver`   |
| Vendor   | `com.avill.vendor`   |

### Reemplazar archivos Firebase
Descargar `google-services.json` de Firebase y reemplazar en:
```
customer/android/app/google-services.json
driver/android/app/google-services.json
vendor/android/app/google-services.json
```

Para iOS, reemplazar `GoogleService-Info.plist`:
```
customer/ios/Runner/GoogleService-Info.plist
driver/ios/Runner/GoogleService-Info.plist
vendor/ios/Runner/GoogleService-Info.plist
```

## 4. Cambiar package name / applicationId

En `android/app/build.gradle` de cada app, cambiar:
```gradle
// ANTES
applicationId "com.edentech.fuodz"

// DESPUÉS (customer)
applicationId "com.avill.customer"

// DESPUÉS (driver)
applicationId "com.avill.driver"

// DESPUÉS (vendor)
applicationId "com.avill.vendor"
```

## 5. Compilar las apps

```bash
# Instalar dependencias
cd customer && flutter pub get
cd driver   && flutter pub get
cd vendor   && flutter pub get

# Analizar código
flutter analyze

# Compilar APK de producción
flutter build apk --release

# Compilar para iOS (requiere Mac + Xcode)
flutter build ipa --release
```

## 6. Configurar idioma por defecto

En Admin Panel → Settings → App Language → **Español (es)**

Los 399 strings del driver ya están en `driver/assets/lang/es.json`.
El customer y vendor obtienen los textos del servidor (tabla `translations`).

## 7. Verificar en el emulador/dispositivo

1. Abrir la app customer
2. Registrarse como usuario nuevo
3. Pedir un taxi → verificar que muestra precios en COP
4. El mapa debe mostrar Quibdó, Colombia (requiere Google Maps API key válida para Colombia)

## Nota sobre la Google Maps API Key

En cada app, el archivo `android/app/src/main/AndroidManifest.xml` contiene:
```xml
<meta-data android:name="com.google.android.geo.API_KEY"
           android:value="@string/google_maps_key"/>
```

La key está en `android/app/src/main/res/values/google_maps_api.xml`.
Reemplazar con una Google Maps API key habilitada para:
- Maps SDK for Android
- Places API
- Directions API
- Geocoding API
