import 'dart:convert';

import 'package:fuodz/services/app_currency_system.service.dart';
import 'package:fuodz/services/local_storage.service.dart';
import 'package:supercharged/supercharged.dart';

class AppStrings {
  static String get appName => env('app_name');
  static String get companyName => env('company_name');
  static String get googleMapApiKey => env('google_maps_key');
  static String get fcmApiKey => env('fcm_key');
  static String get currencySymbol => env('currency');
  static String get currencyCode => env('currency_code');
  static String get countryCode => env('country_code');
  static bool get enableOtp => env('enble_otp') == "1";
  static bool get enableOTPLogin => env('enableOTPLogin') == "1";
  static String get currentCurrencySymbol => AppCurrencySystemService().currentCurrencySymbol;
  static bool get enableEmailLogin => env('enableEmailLogin');
  static bool get enableProfileUpdate => env('enableProfileUpdate');
  static bool get enableGoogleDistance => env('enableGoogleDistance') == "1";
  static bool get enableSingleVendor => env('enableSingleVendor') == "1";
  static bool get enableMultipleVendorOrder => env('enableMultipleVendorOrder') ?? false;
  static bool get enableReferSystem => env('enableReferSystem') == "1";
  static String get referAmount => env('referAmount');
  static bool get enableChat => env('enableChat') == "1";
  static bool get enableOrderTracking => env('enableOrderTracking') == "1";
  static bool get enableFatchByLocation => env('enableFatchByLocation') ?? true;
  static bool get showVendorTypeImageOnly => env('showVendorTypeImageOnly') == "1";
  static bool get enableUploadPrescription => env('enableUploadPrescription') == "1";
  static bool get enableParcelVendorByLocation => env('enableParcelVendorByLocation') == "1";
  static bool get enableParcelMultipleStops => env('enableParcelMultipleStops') == "1";
  static int get maxParcelStops => env('maxParcelStops').toString().toInt() ?? 1;
  static String get what3wordsApiKey => env('what3wordsApiKey') ?? "";
  static bool get isWhat3wordsApiKey => what3wordsApiKey.isNotEmpty;
  static String get androidDownloadLink => env('androidDownloadLink') ?? "";
  static String get iOSDownloadLink => env('iosDownloadLink') ?? "";
  static bool get isSingleVendorMode => env('isSingleVendorMode') == "1";
  static bool get canScheduleTaxiOrder => env('taxi')['canScheduleTaxiOrder'] != null ? (env('taxi')['canScheduleTaxiOrder'] == "1") : false;
  static int get taxiMaxScheduleDays => (env('taxi')['taxiMaxScheduleDays'].toString().toInt()) ?? 2;
  static Map<String, dynamic> get enabledVendorType => env('enabledVendorType') ?? {};
  static double get bannerHeight => double.parse("${env('bannerHeight') ?? 150.00}");
  static String get otpGateway => env('otpGateway') ?? "none";
  static bool get isFirebaseOtp => otpGateway.toLowerCase() == "firebase";
  static bool get isCustomOtp => !["none", "firebase"].contains(otpGateway.toLowerCase());
  static String get emergencyContact => env('emergencyContact') ?? "911";
  static bool get googleLogin => env('auth')['googleLogin'] ?? false;
  static bool get appleLogin => env('auth')['appleLogin'] ?? false;
  static bool get facebbokLogin => env('auth')['facebbokLogin'] ?? false;
  static bool get qrcodeLogin => env('auth')['qrcodeLogin'] ?? false;
  static String? get firebaseDb => env('firebase')['db'] ?? "(default)";
  static String? get firebasePrefix => env('firebase')['prefix'] ?? "";
  static dynamic get uiConfig => env('ui') ?? null;
  static bool get useWebsocketAssignment => (env('useWebsocketAssignment') ?? false);

  static const String notificationChannel = "high_importance_channel";
  static String firstTimeOnApp = "first_time";
  static String authenticated = "authenticated";
  static String userAuthToken = "auth_token";
  static String userKey = "user";
  static String appLocale = "locale";
  static String notificationsKey = "notifications";
  static String appCurrency = "currency";
  static String appColors = "colors";
  static String appExchangeRates = "exchange_rates";
  static String appRemoteSettings = "appRemoteSettings";
  static String appStoreId = "";

  static Future<bool> saveAppSettingsToLocalStorage(String stringMap) async {
    return await LocalStorageService.prefs!.setString(AppStrings.appRemoteSettings, stringMap);
  }

  static dynamic appSettingsObject;
  static Future<void> getAppSettingsFromLocalStorage() async {
    appSettingsObject = LocalStorageService.prefs?.getString(AppStrings.appRemoteSettings);
    if (appSettingsObject != null) { appSettingsObject = jsonDecode(appSettingsObject); }
  }

  static dynamic env(String ref) {
    getAppSettingsFromLocalStorage();
    return appSettingsObject != null ? appSettingsObject[ref] : "";
  }

  static List<String> get orderCancellationReasons {
    return ["Tiempo de espera largo", "Vendedor muy lento", "personalizada"];
  }

  static List<String> get orderStatuses {
    return ['pending', 'preparing', 'ready', 'enroute', 'failed', 'cancelled', 'delivered'];
  }
}
