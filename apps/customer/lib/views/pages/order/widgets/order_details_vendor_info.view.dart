import 'package:flutter/material.dart';
import 'package:flutter_icons/flutter_icons.dart';
import 'package:fuodz/constants/app_colors.dart';
import 'package:fuodz/constants/app_ui_settings.dart';
import 'package:fuodz/constants/sizes.dart';
import 'package:fuodz/extensions/dynamic.dart';
import 'package:fuodz/utils/ui_spacer.dart';
import 'package:fuodz/view_models/order_details.vm.dart';
import 'package:fuodz/widgets/buttons/custom_button.dart';
import 'package:localize_and_translate/localize_and_translate.dart';
import 'package:velocity_x/velocity_x.dart';

class OrderDetailsVendorInfoView extends StatelessWidget {
  const OrderDetailsVendorInfoView(this.vm, {Key? key}) : super(key: key);
  final OrderDetailsViewModel vm;

  @override
  Widget build(BuildContext context) {
    return VStack([
      HStack([
        VStack([
          (!vm.order.isSerice ? "Vendedor" : "Prestador de servicio").tr().text.medium.make(),
          vm.order.vendor!.name.text.medium.xl.make().py8().pOnly(bottom: Vx.dp4),
        ]).expand(),
        Visibility(
          visible: vm.order.canChatVendor && AppUISettings.canCallVendor,
          child: CustomButton(
            icon: FlutterIcons.phone_call_fea, iconColor: Colors.white,
            color: AppColor.primaryColor, shapeRadius: Sizes.radiusSmall,
            onPressed: vm.callVendor,
          ).h(50).fittedBox(),
        ),
        if (vm.order.canChatVendor)
          Visibility(
            visible: AppUISettings.canVendorChat,
            child: CustomButton(
              icon: FlutterIcons.chat_ent, iconColor: Colors.white,
              color: AppColor.primaryColor, shapeRadius: Sizes.radiusSmall,
              onPressed: vm.chatVendor,
            ).h(50).fittedBox(),
          ),
      ], spacing: 8),
      vm.order.canRateVendor
          ? CustomButton(
              icon: FlutterIcons.rate_review_mdi, iconColor: Colors.white,
              title: "Calificar a %s".tr().fill([(!vm.order.isSerice ? "Vendedor" : "Prestador de servicio").tr()]),
              color: AppColor.primaryColor,
              onPressed: vm.rateVendor,
            ).h(Vx.dp48).pOnly(top: Vx.dp12, bottom: Vx.dp20)
          : UiSpacer.emptySpace(),
    ]).px(20);
  }
}
