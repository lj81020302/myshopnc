<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<!DOCTYPE html>
<html class="no-js">
    
    <head>
        <title>酒天仙女网</title>
        <!-- Bootstrap -->
        <link href="<?php echo AGENT_RESOURCE_URL?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo AGENT_RESOURCE_URL?>/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo AGENT_RESOURCE_URL?>/vendors/easypiechart/jquery.easy-pie-chart.css" rel="stylesheet" media="screen">
        <link href="<?php echo AGENT_RESOURCE_URL?>/assets/styles.css" rel="stylesheet" media="screen">
        <link href="<?php echo AGENT_TEMPLATES_URL?>/css/base.css" rel="stylesheet" media="screen">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript" src="<?php echo AGENT_RESOURCE_URL?>/vendors/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="<?php echo AGENT_RESOURCE_URL?>/vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<script type="text/javascript" src="<?php echo AGENT_RESOURCE_URL?>/bootstrap/js/bootstrap.min.js"></script>
        
    </head>
    
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#" style="color: #6e98d8;"><b>代理商中心</b></a>
                    <div class="nav-collapse collapse">
                        <ul class="nav pull-right">
                            <li class="dropdown">
                                <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">您好， <i class="icon-user"></i> <?php echo $output['agent_info']['agent_name'];?> <i class="caret"></i>

                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a tabindex="-1" href="#updpwdAlert" data-toggle="modal">修改密码</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a tabindex="-1" href="index.php?act=index&op=logout">退出</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav">
                        	<?php foreach ($output['top_menu'] as $key => $val) {?>
                            <li <?php if($val['is_select']==1){?> class="active"<?php }?>>
                                <a href="<?php echo $val['url'];?>"><?php echo $val['name'];?></a>
                            </li>
                              <?php }?> 
                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
            
            	<!-- 左菜单 -->
                <div class="span3" id="sidebar">
            	<?php if(count($output['left_menu']) > 0){?>
                    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
                    	<?php foreach ($output['left_menu'] as $key => $val) {?>
                            <li <?php if($val['is_select']==1){?> class="active"<?php }?>>
                                <a href="<?php echo $val['url'];?>"><?php echo $val['name'];?></a>
                            </li>
                      	<?php }?> 
                    </ul>
            	<?php }?>
                </div>
                <!--/span-->
                <!-- 主页面 -->
                <div class="span9" id="content">
                    
                    <?php require_once($tpl_file);?>
                </div>
            </div>
            <hr>
            <footer>
                <p>&copy; 酒天仙女网 提供技术支持</p>
            </footer>
        </div>
        <div class="row-fluid">
        <div id="updpwdAlert" class="modal hide">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button">&times;</button>
				<h3>修改密码</h3>
			</div>
			<div class="modal-body">
				<form action="#" id="form_sample_upd_pwd" class="form-horizontal" novalidate="novalidate">
				<fieldset>
					<div class="control-group">
						<label class="control-label">旧 密 码<span class="required">*</span></label>
						<div class="controls">
							<input type="password" name="oldpwd" data-required="1" class="span6 m-wrap" id="oldpwd">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">新 密 码<span class="required">*</span></label>
						<div class="controls">
							<input type="password" name="pwd1" data-required="1" class="span6 m-wrap" id="pwd1">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">再次输入<span class="required">*</span></label>
						<div class="controls">
							<input name="pwd2" type="password" class="span6 m-wrap" id="pwd2">
						</div>
					</div>
					
				</fieldset>
			</form>
			</div>
			<div class="modal-footer">
				<a class="btn btn-primary" href="#" onclick="updatePassword()">确认</a>
				<a data-dismiss="modal" class="btn" href="#">取消</a>
			</div>
		</div>
		</div>
        <script type="text/javascript">
        function updatePassword(){
        	var password1 =  $.trim($("#pwd1").val());
        	var password2 =  $.trim($("#pwd2").val());
        	var oldpassword =  $.trim($("#oldpwd").val());
        	if(oldpassword == ''){
            	alert("请输入旧密码");
            	return false;
        	}
        	if(password1 == ''){
            	alert("请输入新密码");
            	return false;
        	}
        	if(password2 == ''){
            	alert("请再次输入密码");
            	return false;
        	}
        	if(password1 != password2){
            	alert("两次密码不一致");
            	return false;
        	}
            	
        	$.ajax({
                type:"POST",
                url:'<?php echo urlAgent('index', 'update_password');?>',  
                async:false,  
                data:{'password2': password2, 'oldpassword' : oldpassword},
                success: function(data){
        	        if(data == 1){
        				alert("修改成功");
        				document.location.reload();
        		    }else{
        			    alert("修改失败");
        		    }
                }  
            });  
        }
        </script>
    </body>

</html>