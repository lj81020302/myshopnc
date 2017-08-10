var goods_id = getQueryString("goods_id");
var map_list = [];
var map_index_id = '';
var store_id;
$(function (){
    var key = getCookie('key');

    var unixTimeToDateString = function(ts, ex) {
        ts = parseFloat(ts) || 0;
        if (ts < 1) {
            return '';
        }
        var d = new Date();
        d.setTime(ts * 1e3);
        var s = '' + d.getFullYear() + '-' + (1 + d.getMonth()) + '-' + d.getDate();
        if (ex) {
            s += ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
        }
        return s;
    };

    var buyLimitation = function(a, b) {
        a = parseInt(a) || 0;
        b = parseInt(b) || 0;
        var r = 0;
        if (a > 0) {
            r = a;
        }
        if (b > 0 && r > 0 && b < r) {
            r = b;
        }
        return r;
    };

    template.helper('isEmpty', function(o) {
        for (var i in o) {
            return false;
        }
        return true;
    });

     // 图片轮播
    function picSwipe(){
      var elem = $("#mySwipe")[0];
      window.mySwipe = Swipe(elem, {
        continuous: false,
        // disableScroll: true,
        stopPropagation: true,
        callback: function(index, element) {
          $('.goods-detail-turn').find('li').eq(index).addClass('cur').siblings().removeClass('cur');
        }
      });
    }
    get_detail(goods_id);
  //点击商品规格，获取新的商品
  function arrowClick(self,myData){
    $(self).addClass("current").siblings().removeClass("current");
    //拼接属性
    var curEle = $(".spec").find("a.current");
    var curSpec = [];
    $.each(curEle,function (i,v){
        // convert to int type then sort
        curSpec.push(parseInt($(v).attr("specs_value_id")) || 0);
    });
    var spec_string = curSpec.sort(function(a, b) { return a - b; }).join("|");
    //获取商品ID
    goods_id = myData.spec_list[spec_string];
    get_detail(goods_id);
  }

  function contains(arr, str) {//检测goods_id是否存入
	    var i = arr.length;
	    while (i--) {
           if (arr[i] === str) {
	           return true;
           }
	    }
	    return false;
	}
  $.sValid.init({
        rules:{
            buynum:"digits"
        },
        messages:{
            buynum:"请输入正确的数字"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $.sDialog({
                    skin:"red",
                    content:errorHtml,
                    okBtn:false,
                    cancelBtn:false
                });
            }
        }
    });
  //检测商品数目是否为正整数
  function buyNumer(){
    $.sValid();
  }
  
  function get_detail(goods_id) {
      //渲染页面
      $.ajax({
         url:ApiUrl+"/index.php?act=fx_goods&op=goods_detail",
         type:"get",
         data:{goods_id:goods_id,key:key},
         dataType:"json",
         success:function(result){
            var data = result.datas;
            if(!data.error){
              //渲染模板
              var html = template.render('product_detail', data);
              $("#product_detail_html").html(html);

              var html = template.render('product_detail_sepc', data);
              $("#product_detail_spec_html").html(html);
              
              // 购物车中商品数量
              if (getCookie('fx_cart_count')) {
                  if (getCookie('fx_cart_count') > 0) {
                      $('#cart_count,#cart_count1').html('<sup>'+getCookie('fx_cart_count')+'</sup>');
                  }
              }
              
            //图片轮播
              picSwipe();

              //购买数量，减
              $(".minus").click(function (){
                 var buynum = $(".buy-num").val();
                 if(buynum >1){
                    $(".buy-num").val(parseInt(buynum-1));
                 }
              });
              //购买数量加
              $(".add").click(function (){
                 var buynum = parseInt($(".buy-num").val());
                 if(buynum < data.goods_storage){
                    $(".buy-num").val(parseInt(buynum+1));
                 }
              });
              //加入购物车
              $("#add-cart").click(function (){
                var key = getCookie('key');//登录标记
                var quantity = parseInt($(".buy-num").val());
                 if(!key){
                     var goods_info = decodeURIComponent(getCookie('fx_goods_cart'));
                     if (goods_info == null) {
                         goods_info = '';
                     }
                     if(goods_id<1){
                         show_tip();
                         return false;
                     }
                     var cart_count = 0;
                     if(!goods_info){
                         goods_info = goods_id+','+quantity;
                         cart_count = 1;
                     }else{
                         var goodsarr = goods_info.split('|');
                         for (var i=0; i<goodsarr.length; i++) {
                             var arr = goodsarr[i].split(',');
                             if(contains(arr,goods_id)){
                                 show_tip();
                                 return false;
                             }
                         }
                         goods_info+='|'+goods_id+','+quantity;
                         cart_count = goodsarr.length;
                     }
                     // 加入cookie
                     addCookie('fx_goods_cart',goods_info);
                     // 更新cookie中商品数量
                     addCookie('fx_cart_count',cart_count);
                     show_tip();
                     getCartCount();
                     $('#cart_count,#cart_count1').html('<sup>'+cart_count+'</sup>');
                     return false;
                 }else{
                	 
                    $.ajax({
                       url:ApiUrl+"/index.php?act=fx_member_cart&op=cart_add",
                       data:{key:key,goods_id:goods_id,quantity:quantity},
                       type:"post",
                       success:function (result){
                          var rData = $.parseJSON(result);
                          if(checkLogin(rData.login)){
                            if(!rData.datas.error){
                                show_tip();
                                // 更新购物车中商品数量
                                delCookie('fx_cart_count');
                                getCartCount();
                                $('#cart_count,#cart_count1').html('<sup>'+getCookie('fx_cart_count')+'</sup>');
                            }else{
                              $.sDialog({
                                skin:"red",
                                content:rData.datas.error,
                                okBtn:false,
                                cancelBtn:false
                              });
                            }
                          }
                       }
                    })
                 }
              });

              

            }else {

              $.sDialog({
                  content: data.error + '！<br>请返回上一页继续操作…',
                  okBtn:false,
                  cancelBtnText:'返回',
                  cancelFn: function() { history.back(); }
              });
            }

            //验证购买数量是不是数字
            $("#buynum").blur(buyNumer);

            // 从下到上动态显示隐藏内容
            $.animationUp({
                valve : '.animation-up,#goods_spec_selected',          // 动作触发
                wrapper : '#product_detail_spec_html',    // 动作块
                scroll : '#product_roll',     // 滚动块，为空不触发滚动
                start : function(){       // 开始动作触发事件
                    $('.goods-detail-foot').addClass('hide').removeClass('block');
                },
                close : function(){        // 关闭动作触发事件
                    $('.goods-detail-foot').removeClass('hide').addClass('block');
                }
            });
            
            $.animationUp({
                valve : '#getVoucher',          // 动作触发
                wrapper : '#voucher_html',    // 动作块
                scroll : '#voucher_roll',     // 滚动块，为空不触发滚动
            });

            $('#voucher_html').on('click', '.btn', function(){
                getFreeVoucher($(this).attr('data-tid'));
            });
            
            // 联系客服
            $('.kefu').click(function(){
				if (data.store_info.node_chat) {
					 window.location.href = WapSiteUrl+'/tmpl/member/chat_info.html?goods_id=' + goods_id + '&t_id=' + result.datas.store_info.member_id;
				}else{
                	window.location.href = "http://wpa.qq.com/msgrd?v=3&uin=" + result.datas.store_info.store_qq + "&site=qq&menu=yes";
            	}	 
				
				
                
				
            })
         }
      });
  }
  
  $.scrollTransparent();
  $('#product_detail_html').on('click', '#get_area_selected', function(){
      $.areaSelected({
          success : function(data){
              $('#get_area_selected_name').html(data.area_info);
              var area_id = data.area_id_2 == 0 ? data.area_id_1:data.area_id_2;
              $.getJSON(ApiUrl + '/index.php?act=goods&op=calc', {goods_id:goods_id,area_id:area_id},function(result){
                  $('#get_area_selected_whether').html(result.datas.if_store_cn);
                  $('#get_area_selected_content').html(result.datas.content);
                  if (!result.datas.if_store) {
                      $('.buy-handle').addClass('no-buy');
                  } else {
                      $('.buy-handle').removeClass('no-buy');
                  }
              });
          }
      });
  });
  
  $('body').on('click', '#goodsBody,#goodsBody1', function(){
      window.location.href = WapSiteUrl+'/tmpl/product_info.html?goods_id=' + goods_id;
  });
  $('body').on('click', '#goodsEvaluation,#goodsEvaluation1', function(){
      window.location.href = WapSiteUrl+'/tmpl/product_eval_list.html?goods_id=' + goods_id;
  });

  $('#list-address-scroll').on('click','dl > a',map);
  $('#map_all').on('click',map);
});


function show_tip() {
    var flyer = $('.goods-pic > img').clone().css({'z-index':'999','height':'3rem','width':'3rem'});
    flyer.fly({
        start: {
            left: $('.goods-pic > img').offset().left,
            top: $('.goods-pic > img').offset().top-$(window).scrollTop()
        },
        end: {
            left: $("#cart_count1").offset().left+40,
            top: $("#cart_count1").offset().top-$(window).scrollTop(),
            width: 0,
            height: 0
        },
        onEnd: function(){
            flyer.remove();
        }
    });
}

function virtual() {
	$('#get_area_selected').parents('.goods-detail-item').remove();
    $.getJSON(ApiUrl + '/index.php?act=goods&op=store_o2o_addr', {store_id:store_id},function(result){
    	if (!result.datas.error) {
    		if (result.datas.addr_list.length > 0) {
    	    	$('#list-address-ul').html(template.render('list-address-script',result.datas));
    	    	map_list = result.datas.addr_list;
    	    	var _html = '';
    	    	_html += '<dl index_id="0">';
    	    	_html += '<dt>'+ map_list[0].name_info +'</dt>';
    	    	_html += '<dd>'+ map_list[0].address_info +'</dd>';
    	    	_html += '</dl>';
    	    	_html += '<p><a href="tel:'+ map_list[0].phone_info +'"></a></p>';
    	    	$('#goods-detail-o2o').html(_html);

    	    	$('#goods-detail-o2o').on('click','dl',map);

    	    	if (map_list.length > 1) {
    	    		$('#store_addr_list').html('查看全部'+map_list.length+'家分店地址');
    	    	} else {
    	    		$('#store_addr_list').html('查看商家地址');
    	    	}
    	    	$('#map_all > em').html(map_list.length);    			
    		} else {
    			$('.goods-detail-o2o').hide();
    		}
    	}
    });
    $.animationLeft({
        valve : '#store_addr_list',
        wrapper : '#list-address-wrapper',
        scroll : '#list-address-scroll'
    });
}

function map() {
	  $('#map-wrappers').removeClass('hide').removeClass('right').addClass('left');
	  $('#map-wrappers').on('click', '.header-l > a', function(){
		  $('#map-wrappers').addClass('right').removeClass('left');
	  });
	  $('#baidu_map').css('width', document.body.clientWidth);
	  $('#baidu_map').css('height', document.body.clientHeight);
	  map_index_id = $(this).attr('index_id');
	  if (typeof map_index_id != 'string'){
		  map_index_id = '';
	  }
	  if (typeof(map_js_flag) == 'undefined') {
	      $.ajax({
	          url: WapSiteUrl+'/js/map.js',
	          dataType: "script",
	          async: false
	      });
	  }
	if (typeof BMap == 'object') {
	    baidu_init();
	} else {
	    load_script();
	}
}