
        function initialize_paypal_sdk(submission_id, payment_id, user_id, btn_color='gold') {
                   
                    paypal.Buttons({
                        style: {
                            layout: 'vertical',
                            color:  btn_color
                        },
                        createOrder: function(data, actions) {
                            return actions.order.create(orders);
                        },
                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(orderData) {
                                var transaction = orderData.purchase_units[0].payments.captures[0];
                                process_paypal_sdk_payment(submission_id, payment_id, transaction, user_id);
                            });
                        },
                        onError: function (err) {
                            console.log(err);
                            alert(rm_ajax.paypal_error);
                        }

                    }).render('#rm_paypal_btn_container');
                
        }
        function process_paypal_sdk_payment(submission_id, payment_id, transaction,user_id){

            let data = {action: 'rm_process_paypal_sdk_payment', 'rm_sec_nonce': rm_ajax.nonce, transaction: transaction, submission_id: submission_id, payment_id: payment_id, user_id:user_id};
            jQuery.ajax({
                url: rm_ajax.url,
                type: 'POST',
                data: data,
                async: true,
                success: function(success_response) {
                    jQuery('#rm_paypal_order_success').show();
                    jQuery('#rm_paypal_order_area').html(success_response.data.msg);
                        if (success_response.data.redirect) {
                            location.href = success_response.data.redirect;
                        }
                        if (success_response.data.hasOwnProperty('reload_params')) {
                            var url = [location.protocol, '//', location.host, location.pathname].join('');
                            if(url.indexOf('admin-ajax.php')>=0){
                                            return;
                            }
                            url += success_response.data.reload_params;
                            location.href = url;
                        }
                },
                error: function(error_response) {
                    console.log(error_response);
                }
            });
        }
jQuery(document).ready(function(e){
    
});
