$(function(){
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
        var key = getCookie('key');
    }

	if(key){
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_index",
            data:{key:key},
            dataType:'json',
            //jsonp:'callback',
            success:function(result){
                checkLogin(result.login);

                var html = '<div class="member-info">'
                    + '<div class="user-avatar"> <img src="' + result.datas.member_info.avatar + '"/> </div>'
                    + '<div class="user-name"> <span>'+result.datas.member_info.user_name+'<sup>' + result.datas.member_info.level_name + '</sup></span> </div>'
                    + '</div>';
                //渲染页面
                
                $(".member-top").html(html);
                                
                return false;
            }
        });
	} else {
	    var html = '<div class="member-info">'
	        + '<a href="login.html" class="default-avatar" style="display:block;"></a>'
	        + '<a href="login.html" class="to-login">点击登录</a>'
	        + '</div>';
	    //渲染页面
	    $(".member-top").html(html);
	    
        return false;
	}

	  //滚动header固定到顶部
	  $.scrollTransparent();
});