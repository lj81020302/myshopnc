<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<title><?php echo $output['html_title'];?></title>
<link href="<?php echo AGENT_TEMPLATES_URL;?>/css/login.css" rel="stylesheet" type="text/css">

<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo AGENT_TEMPLATES_URL?>/js/jquery.supersized.min.js"></script>

</head>
<body>
<?php 
require_once($tpl_file);
?>
<script type="text/javascript">
	$(function() {
		
		var images = "<?php echo AGENT_TEMPLATES_URL?>/images/admin_login_bg_image_0.png|<?php echo AGENT_TEMPLATES_URL?>/images/admin_login_bg_image_1.png|<?php echo AGENT_TEMPLATES_URL?>/images/admin_login_bg_image_2.png".split("|");
		
		var slides = [];
		
		for(var i = 0; i < images.length; i++){
			slides.push({
				image: images[i]
			});
		}
		
		$.supersized({
			
			// 功能
			slide_interval: 4000,
			transition: 1,
			transition_speed: 1000,
			performance: 1,
			
				// 大小和位置
			min_width: 0,
			min_height: 0,
			vertical_center: 1,
			horizontal_center: 1,
			fit_always: 0,
			fit_portrait: 1,
			fit_landscape: 0,
			
				// 组件
			slide_links: 'blank',
			slides: slides
		});
	});
</script>
</body>
</html>
