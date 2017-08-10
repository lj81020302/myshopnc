$(function (){
	var cart_count = 0;
	cart_count = getCookie('cart_count');
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
        var key = getCookie('key');
    }
    var html = '<div class="nctouch-footer-wrap posr">'
        +'<div class="nav-text">';
        
    html += "</div>" + "</div>";
      if (cart_count > 0) {
      	var fnav = '<div id="footnav" class="footnav clearfix"><ul>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/fx_member/fx_cart_list.html"><i class="cart"></i><sup></sup><p>购物车</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/fx_member/member.html"><i class="member"></i><p>我的</p></a></li></ul>'
		+'</div>';
      	} else {
      	var fnav = '<div id="footnav" class="footnav clearfix"><ul>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/fx_member/fx_cart_list.html"><span id="cart_count"><i class="cart"></i></span><p>购物车</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/fx_member/member.html"><i class="member"></i><p>我的</p></a></li></ul>'
		+'</div>';	
      	}
	$("#footer").html(html+fnav);
    var key = getCookie('key');
	$('#logoutbtn').click(function(){
		var username = getCookie('username');
		var key = getCookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?act=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					delCookie('username');
					delCookie('key');
					delCookie('cart_count');
					location.href = WapSiteUrl+'/tmpl/fx_member/member.html';
				}
			}
		});
	});
	if(typeof(navigate_id) == 'undefined'){navigate_id="0";}
	//当前页面
	if(navigate_id == "1"){
		$(".footnav .home").parent().addClass("current");
		$(".footnav .home").attr('class','home2');
	}else if(navigate_id == "2"){
		$(".footnav.categroy").parent().addClass("current");
		$(".footnav.categroy").attr('class','categroy2');
	}else if(navigate_id == "3"){
		$(".footnav .search").parent().addClass("current");
		$(".footnav .search").attr('class','search2');
	}else if(navigate_id == "4"){
		$(".footnav .cart").parent().addClass("current");
		$(".footnav .cart").attr('class','cart2');
	}else if(navigate_id == "5"){
		$(".footnav .member").parent().addClass("current");
		$(".footnav .member").attr('class','member2');
	}
});