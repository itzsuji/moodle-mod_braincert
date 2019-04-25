var init = function (options) {
    jQuery(document).ready(function () {
        jQuery("#btnCheckout").click(function (event) {
            if (options.plan_commission == 0) {
                jQuery('#paypal_form_one_time').submit();
                return false;
            }

            jQuery("#btncontainer").css('display', 'none');
            jQuery("#txtprocessing").css('display', '');

            var orgamount = jQuery("#class_final_amount").val();
            var class_id = options.class_id;
            var price_id = jQuery("#class_price_id").val();
            var cancelUrl = options.url;
            var returnUrl = options.url + '&sesskey' + options.sesskey + '&task=returnpayment&class_id=' +
                    class_id + '&amount=' + orgamount + '&payment_mode=paypal';

            var card_holder_name = jQuery(".full_name").val();
            var card_number = jQuery(".card-number").val();
            var card_cvc = jQuery(".card-cvc").val();
            var card_expiry_month = jQuery(".card-expiry-month").val();
            var card_expiry_year = jQuery(".card-expiry-year").val();
            var student_email = options.user_email;

            jQuery.ajax({
                url: options.url + '&task=class_checkout',
                type: "POST",
                data: {class_id: class_id, price_id: price_id,
                    cancelUrl: cancelUrl, returnUrl: returnUrl,
                    card_holder_name: card_holder_name, card_number: card_number,
                    card_cvc: card_cvc, card_expiry_month: card_expiry_month,
                    card_expiry_year: card_expiry_year, student_email: student_email},
                success: function (result) {
                    var obj = jQuery.parseJSON(result);

                    if (obj.status == "error") {
                        jQuery(".card_error").show().html(obj.error);
                    }
                    if (obj.status == "ok") {
                        jQuery(".card_error").hide();
                        if (obj.payKey) {
                            jQuery("#paykey").val(obj.payKey);
                            jQuery("#submitBtn").trigger('click');
                            jQuery('#modal-content-buying').hide();
                        } else {
                            if (obj.charge_id) {
                                var url = options.url + '&sesskey=' + options.sesskey + "&task=returnpayment" +
                                        "&class_id=" + class_id + "&amount=" + orgamount + "&payment_mode=stripe";
                                window.top.location.href = url;
                            }
                        }
                    }
                    jQuery("#btncontainer").css('display', 'block');
                    jQuery("#txtprocessing").css('display', 'none');
                }
            });
        });

        jQuery('input[name=pricescheme]').click(function (event) {
            var selval = jQuery(this).val();
            jQuery('#subvalue').text(options.currencysymbol + selval);
            var _amnt = returnMoney(selval);
            var _option_id = jQuery(this).attr('option_id');

            jQuery("#class_final_amount").val(_amnt);
            jQuery("#one_time_amount").val(_amnt);
            var class_id = options.class_id;
            var returnUrl_one_time = options.url + ' & sesskey = ' + options.sesskey +
                    '&task=returnpayment&class_id=' + class_id + '&amount=' + _amnt + '&payment_mode=paypal';
            jQuery("#return_url").val(returnUrl_one_time);

            var ipnurl = options.base_url_api + 'index.php?' +
                    'option=com_classroomengine&view=classdetails&task=returnpaypalapi&Id' +
                    '=' + class_id + '&student_email=' + options.user_email + '&item_number=' + _option_id;
            jQuery(".one_time_notify_url").val(ipnurl);
            jQuery("#class_price_id").val(_option_id);
        });
        jQuery("#recording-video").hide();
        jQuery("#page-mod-braincert-view").find(".viewrecording").click(function () {
            jQuery("#recording-video").show();

            var videourl = jQuery(this).data("rpath");
            var sources = [{"type": "video/mp4", "src": videourl}];
            var player = videojs('recording-video', {
                controls: true,
                sources: sources,
                techOrder: ['youtube', 'html5']
            });
            player.pause();
            player.src(sources);
            player.load();
            player.play();
        });

        jQuery(".close").click(function (event) {
            jQuery(".modal").hide();
        });

    });


};
function returnMoney(number)
{
    var nStr = '' + Math.round(parseFloat(number) * 100) / 100;
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '.00';
    var rgx = /(\d+)(\d{3})/;

    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }

    return x1 + x2;
}

function buyingbtn(classid)
{
    jQuery("#modal-content-buying").show();
    jQuery("#pricescheme0").trigger("click");
}
