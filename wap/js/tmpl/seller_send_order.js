$(function() { 
    var e = getCookie("seller_key");
    var orderid = getQueryString("orderid");
    if (!e) {
        location.href = "login.html"
    }
    function s() {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_express&op=get_mylist", 
            data: {
                key: e
            },
            dataType: "json",
            success: function(ed) {
                checkLogin(ed.login);  
				 
                var t = template.render("sshipping_list", ed);
                $("#shipping_list").empty();
                $("#shipping_list").append(t);
			
				//默认发货地址 
				 $.ajax({
					type: "post",
					url: ApiUrl + "/index.php?act=seller_express&op=get_defaultexpress",
					data: {
						order_id:orderid,
						key: e
					},
					dataType: "json",
					success: function(f) {
					    
						checkLogin(e.login);  
						var g = template.render("saddress_list", f.datas);
						$("#address_list").empty();
						$("#address_list").append(g);

					}
				 })
  


                $(".btn-l").click(function() {
                     
                    $.sDialog({
                        skin: "block",
                        content: "确定发货？",
                        okBtn: true,
                        cancelBtn: true,
                        okFn: function() {
							 
                            saveexpress();
                        }
                    })
                })
            } 
        })
    }
    s();
	
    $("#shipping_list").on("click", ".send-order",saveexpress);  
    function saveexpress() {
		   var expressid = $(this).attr("express_id");
	       var expresssn=$("#sc"+expressid).val(); 
		   var daddress_id=$("#shippingid").val();
	       //alert(expressid+":"+expresssn);
		  if(expresssn==""){

			  
							$.sDialog({
								skin: "block",
								content: "请输入单号!",
								okBtn: true,
								cancelBtn: false
								 
							})
		  
		  }else{
			            $.ajax({
							type: "post",
							url: ApiUrl + "/index.php?act=seller_order&op=order_deliver_send",
							data: {
								order_id: orderid,
								shipping_express_id:expressid,
								shipping_code:expresssn,
								daddress_id:daddress_id,
								key: e
							},
							dataType: "json",
							success: function(e) {
								checkLogin(e.login);
								if (e) {
 
									$.sDialog({
										skin: "block",
										content: "发货成功!",
										okBtn: true,
										cancelBtn: true,
										okFn: function() {
											window.location.href="store_orders_list.html?data-state=state_send";
										}
									})
  
								}else{
								
									
									$.sDialog({
										skin: "block",
										content: "发货失败!",
										okBtn: true,
										cancelBtn: true 
									})
								
								}
							}
						  }) 
						 
		  }
    }
});