<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<?php echo getChat($layout);?>
<script language="javascript">
function fade(){$("img[rel='lazy']").each(function(){var $scroTop=$(this).offset();if($scroTop.top<=$(window).scrollTop()+$(window).height()){$(this).hide();$(this).attr("src",$(this).attr("data-url"));$(this).removeAttr("rel");$(this).removeAttr("name");$(this).fadeIn(500)}})}if($("img[rel='lazy']").length>0){$(window).scroll(function(){fade()})}fade();

</script>
<div id="cti">
  <div class="wrapper">
    <ul>
      <?php if ($output['contract_list']) {?>
      <?php foreach($output['contract_list'] as $k=>$v){?>
        <?php if($v['cti_descurl']){ ?>
            <li><span class="line"></span><a href="<?php echo $v['cti_descurl'];?>" target="_blank"><span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span></a></li>
        <?php }else{ ?>
            <li><span class="line"></span> <span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span> </li>
        <?php }?>
      <?php }?>
      <?php }?>
    </ul>
  </div>
</div>
<div id="footer">
  <p><a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlMember('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p>
  <?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?>  <?php echo $output['setting_config']['icp_number']; ?></div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div> <?php print_r(Tpl::showTrace());?> </div>
  </fieldset>
</div>
<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<!-- 对比 --> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/compare.js"></script> 
<script type="text/javascript">
$(function(){
	// Membership card
	$('[nctype="mcard"]').membershipCard({type:'shop'});
});
</script>