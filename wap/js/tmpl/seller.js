$(function() { 
    if (getQueryString("seller_key") != "") {
        var a = getQueryString("seller_key");
        var e = getQueryString("sellername");
        addCookie("seller_key", a);
        addCookie("sellername", e)
    } else {

        var a = getCookie("seller_key")
    }
    if (a) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_index",
            data: {
                key: a
            },
            dataType: "json",
            success: function(a) {
				//JSON.stringify(a); 
				if(a.code==200){
					var allstoresum=a.datas.store_info.all_amonut[0].allsum; 
					var allorders=a.datas.store_info.allorder[0].allorders; 
					var e = '<div class="member-info">' + '<div class="user-avatar"> <img src="'+ a.datas.store_info.logo +'"/> </div>' + '<div class="user-name"> <span>' + a.datas.seller_info.seller_name + "[" + a.datas.store_info.store_name  + "]</span> </div>" + "</div>" + '<div class="member-collect"><span><a href="store_goods_list.html"><em>' + a.datas.store_info.goods_account + "</em>" + "<p>店铺商品</p>" + '</a> </span><span><a href="store_orders_list.html?data-state=state_new"><em>' + a.datas.store_info.order_account + "</em>" + "<p>店铺订单</p>" + '</a> </span><span><a href=""><em>' + allstoresum + "</em>" + "<p>店铺销量</p>" + "</a> </span></div>";
					$(".member-top").html(e);
					 var e = '<li><a href="store_goods_list.html"><i class="cc-07"></i><p>出售中</p></a></li><li><a href="store_goods_list.html?showtype=offlinegoods"><i class="cc-10"></i><p>仓库中</p></a></li><li><a href="store_goods_list.html?showtype=illegalgoods"><i class="cc-14"></i><p>违规商品</p></a></li><li><a href="store_goods_add.html"><i class="cc-04"></i><p>发布商品</p></a>';
					$("#goods_ul").html(e);
					var e = '<li><a href="store_orders_list.html?data-state=state_new">' + (a.datas.member_info.order_nopay_count > 0 ? "<em></em>": "") + '<i class="cc-01"></i><p>待付款</p></a></li>' + '<li><a href="store_orders_list.html?data-state=state_pay">' + (a.datas.member_info.order_noreceipt_count > 0 ? "<em></em>": "") + '<i class="cc-02"></i><p>待发货</p></a></li>' + '<li><a href="store_orders_list.html?data-state=state_send">' + (a.datas.member_info.order_notakes_count > 0 ? "<em></em>": "") + '<i class="cc-03"></i><p>已发货</p></a></li>' + '<li><a href="store_orders_list.html?data-state=state_success">' + (a.datas.member_info.order_noeval_count > 0 ? "<em></em>": "") + '<i class="cc-13"></i><p>已完成</p></a></li>' + '<li><a href="store_orders_list.html?data-state=state_cancel">' + (a.datas.member_info.
					return > 0 ? "<em></em>": "") + '<i class="cc-12"></i><p>已取消</p></a></li>';
					$("#order_ul").html(e);
					var e = '<li><div><p style="font-size:18px;color:red;font-weight:bold;">'+allstoresum+'</p><p>营业总额</p></div></li><li><div><p style="font-size:18px;color:red;font-weight:bold;">'+allstoresum+'</p><p>30天销量</p></div></li><li><div><p style="font-size:18px;color:red;font-weight:bold;">'+allorders+'</p><p>有效订单量</p></div></li><li><div><p style="font-size:18px;color:red;font-weight:bold;">'+a.datas.store_info.favorites_account+'</p><p>结算金额</p></div></li>';
					$("#asset_ul").html(e);
					return false
				} 
            }
        })
    } else {
         var i = '<div class="member-info">' + '<a href="login.html" class="default-avatar" style="display:block;"></a>' + '<a href="login.html" class="to-login">点击登录</a>' + "</div>" + '<div class="member-collect"><span><a href="login.html"><i class="favorite-goods"></i>' + "<p>店铺商品</p>" + '</a> </span><span><a href="login.html"><i class="favorite-store"></i>' + "<p>店铺订单</p>" + '</a> </span><span><a href="login.html"><i class="goods-browse"></i>' + "<p>我的众包</p>" + "</a> </span></div>";
         $(".member-top").html(i);
         
        return false
    }
    $.scrollTransparent();


    $("#quit").click(function() {
        var a = getCookie("sellername");
        var e = getCookie("seller_key");
        var i = "wap";
        $.ajax({
            type: "get",
            url: ApiUrl + "/index.php?act=seller_logout&op=index",
            data: {
                seller_name: a,
                key: e,
                client: i
            },
            success: function(a) {
                if (a) {
                    delCookie("sellername");
                    delCookie("seller_key");
                    location.href = WapSiteUrl+ '/tmpl/seller/login.html';
                }
            }
        })
    })




});