
<div class="ncap-about">
  <div class="left-pic"></div>
  <div class="version">
    <h2>运维舫 B2B2C 电商平台系统</h2>
    <h4><?php echo $output['v_date'];?>版</h4>
    <hr>
    <h5>安装日期：<?php echo $output['s_date'];?></h5>
  </div>
  <div class="content">
    <div class="scroll switchbox" >
      <ul class="tema">
        <li>
          <h4>源码提供 - 运维舫</h4>
          <p>唯一官方网：<a href="http://www.shopnc.club" target="_blank">http://www.shopnc.club</a></p>
          <p>唯一淘宝店：<a href="http://www.shopnc.club" target="_blank">https://shopsearch.taobao.com/search?app=shopsearch&q=%E8%BF%90%E7%BB%B4%E8%88%AB</a></p>
          <p>唯一论坛：www.shopnc.club</p>
          </p>二次开发请联系论坛右边的客服中心相关开发人员</p>
        </li>
      </ul>
    </div>
    <!-- 代码结束 -->
    <div class="scrollbar switchbox" style="display: none;">
      <div class="law-notice">
        <p>程序来源：运维舫网店交流中心 <a href="http://www.shopnc.club" target="_blank">http://www.shopnc.club</a>。</p>
          感谢您选择运维舫B2B2C电商平台系统。希望我们的努力能为您提供一个高效快速和强大的企业级电子商务整体解决方案。</p>
        <p>运维舫B2B2C系统是根据网城天创系统进化而来！运维舫对本版本只用户学习和研究，您使用过程中产生的利益与运维舫(shopnc.club)无关，我们不负任何法律责任！</p>
        <p>为了你更好的使用本系统！请进入论坛：www.shopnc.club交流学习</p>
        <p>尊爱网络环境！禁止非法传播。</p>
      </div>
    </div>
    <div class="switchbox" style="display:none;" >
      <ul>
        <li>
          <h4><?php echo $lang['dashboard_aboutus_idea'];?></h4>
          <p><?php echo $lang['dashboard_aboutus_idea_content'];?></p>
        </li>
        <li>
          <h4>关注我们</h4>
          <p><?php echo $lang['dashboard_aboutus_website'];?> <a href="http://www.shopnc.club" target="_blank">http://www.shopnc.club</a></p>
          <p><?php echo $lang['dashboard_aboutus_website_tip'];?></p>
        </li>
        <li>
          <h4><?php echo $lang['dashboard_aboutus_notice'];?></h4>
          <p><?php echo $lang['dashboard_aboutus_notice4'];?>&nbsp;:&nbsp;&nbsp;jQuery,kindeditor<?php echo $lang['dashboard_aboutus_notice5'];?>.&nbsp;<?php echo $lang['dashboard_aboutus_notice6'];?> </p>
        </li>
      </ul>
    </div>
  </div>
  <div class="btns"><a href="javascript:void(0);" onClick="about_change(0)" class="ncap-btn ncap-btn-green">开发团队</a><a href="javascript:void(0);" onClick="about_change(1)" class="ncap-btn">法律声明</a><a href="javascript:void(0);" onClick="about_change(2)" class="ncap-btn">致用户</a></div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.scroll.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
$(function(){
	$("div.scroll").myScroll({
		speed:30,
		rowHeight:60
	});
	$("div.scrollbar").perfectScrollbar();
});

function about_change(i) {
    $(".switchbox").hide().eq(i).show();
    $(".btns > a").removeClass("ncap-btn-green").eq(i).addClass("ncap-btn-green");
    if (i == 0) {
        $("div.scroll").myScroll({
            speed:30,
            rowHeight:60
        });
    }
}
</script>