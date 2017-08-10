var key = getCookie('key');
// buy_stop2使用变量
var ifcart = getQueryString('ifcart');
if(ifcart==1){
    var cart_id = getQueryString('cart_id');
}else{
    var cart_id = getQueryString("goods_id")+'|'+getQueryString("buynum");
}
var pay_name = 'online';
var invoice_id = 0;
var address_id,vat_hash,offpay_hash,offpay_hash_batch,voucher,pd_pay,password,fcode='',rcb_pay,rpt,payment_code;
var message = {};
// change_address 使用变量
var freight_hash,city_id,area_id
// 其他变量
var area_info;
var goods_id;
$(function() {
    $.animationLeft({
        valve : '#list-address-valve',
        wrapper : '#list-address-wrapper',
        scroll : '#list-address-scroll'
    });
    
    // 支付方式
    $.animationLeft({
        valve : '#select-payment-valve',
        wrapper : '#select-payment-wrapper',
        scroll : ''
    });
    
    
    template.helper('isEmpty', function(o) {
        var b = true;
        $.each(o, function(k, v) {
            b = false;
            return false;
        });
        return b;
    });
    
    template.helper('pf', function(o) {
        return parseFloat(o) || 0;
    });

    template.helper('p2f', function(o) {
        return (parseFloat(o) || 0).toFixed(2);
    });

    var _init = function () {
    	
        var totals = 0;
        // 购买第一步 提交
        $.ajax({//提交订单信息
            type:'post',
            url:ApiUrl+'/index.php?act=fx_member_buy&op=buy_step1',
            dataType:'json',
            data:{key:key,ifcart:ifcart},
            success:function(result){
                checkLogin(result.login);
                if (result.datas.error) {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }
                // 商品数据
                	
                result.datas.WapSiteUrl = WapSiteUrl;
                var html = template.render('goods_list', result.datas);
                $("#deposit").html(html);
                	
                password = '';
                if(result.datas.goods_list.length>0){
	                	var total_price = result.datas.totals;
	                	if (total_price <= 0) {
	                		total_price = 0;
	                	}
	                	// 计算总价
	                	$('#totalPrice,#onlineTotal').html(total_price.toFixed(2));
	                	$('#ToBuyStep2').parent().addClass('ok');
                	
                }else{
            		total_price = 0;
                	// 计算总价
                	$('#totalPrice,#onlineTotal').html(total_price.toFixed(2));
                	$('#ToBuyStep2').parent().removeClass('ok');
                	$('#ToBuyStep2').removeClick();
                	$('#ToBuyStep2').click(function(){});
                }
            }
        });
        
    }
    
    rcb_pay = 0;
    pd_pay = 0;
    // 初始化
    _init();
    
    // 支付方式选择
    // 在线支付
    $('#payment-online').click(function(){
        pay_name = 'online';
        $('#select-payment-wrapper').find('.header-l > a').click();
        $('#select-payment-valve').find('.current-con').html('在线支付');
        $(this).addClass('sel').siblings().removeClass('sel');
    })
    // 货到付款
    $('#payment-offline').click(function(){
        pay_name = 'offline';
        $('#select-payment-wrapper').find('.header-l > a').click();
        $('#select-payment-valve').find('.current-con').html('货到付款');
        $(this).addClass('sel').siblings().removeClass('sel');
    })
    
    // 支付
    $('#ToBuyStep2').click(function(){
        var msg = '';
        for (var k in message) {
            msg += k + '|' + message[k] + ',';
        }
        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=fx_member_buy&op=buy_step2',
            data:{
                key:key,
                ifcart:ifcart,
                cart_id:cart_id,
                address_id:address_id,
                vat_hash:vat_hash,
                offpay_hash:offpay_hash,
                offpay_hash_batch:offpay_hash_batch,
                pay_name:pay_name,
                invoice_id:invoice_id,
                voucher:voucher,
                pd_pay:pd_pay,
                password:password,
                fcode:fcode,
                rcb_pay:rcb_pay,
                rpt:rpt,
                pay_message:msg
                },
            dataType:'json',
            success: function(result){
                checkLogin(result.login);
                if (result.datas.error) {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }
                if (result.datas.payment_code == 'offline') {
                    window.location.href = WapSiteUrl + '/tmpl/fx_member/order_list.html';
                } else {
                    delCookie('cart_count');
                    toPay(result.datas.pay_sn,'fx_member_buy','pay');
                }
            }
        });
    });
});