<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="breadcrumb"><span class="icon-home"></span><span><a href="<?php echo SHOP_SITE_URL;?>">首页</a></span> <span class="arrow">></span> <span>代理商申请</span> </div>
<div class="main">
  <div class="sidebar">
    <div class="title">
      <h3>代理商申请</h3>
    </div>
    <div class="content">
      <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
      <?php foreach($output['list'] as $key => $val){ ?>
      <dl show_id="<?php echo $val['type_id'];?>">
        <dt onclick="show_list('<?php echo $val['type_id'];?>');" style="cursor: pointer;"> <i class="hide"></i><?php echo $val['type_name'];?></dt>
        <dd style="display:none;">
          <ul>
            <?php if(!empty($val['help_list']) && is_array($val['help_list'])){ ?>
            <?php foreach($val['help_list'] as $k => $v){ ?>
            <li> <i></i>
              <?php if(empty($v['help_url'])){ ?>
              <a href="<?php echo urlShop('show_help', 'index', array('t_id' => $v['type_id'],'help_id' => $v['help_id']));?>" target="_blank"><?php echo $v['help_title'];?></a>
              <?php }else { ?>
              <a href="<?php echo $v['help_url'];?>" target="_blank"><?php echo $v['help_title'];?></a>
              <?php } ?>
            </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </dd>
      </dl>
      <?php } ?>
      <?php } ?>
      <dl>
        <dt class="<?php echo $output['sub_step'] == 'step0' ? 'current' : '';?>"> <i class="hide"></i>签订申请协议</dt>
      </dl>
      <dl show_id="0">
        <dt class="<?php echo $output['sub_step'] == 'step1' ? 'current' : '';?>"> <i class="hide"></i>填写申请</dt>
      </dl>
      <dl>
        <dt class="<?php echo $output['step'] == '2' ? 'current' : '';?>"> <i class="hide"></i>提交审核</dt>
      </dl>
      <dl>
        <dt class="<?php echo $output['sub_step'] == 'step3' ? 'current' : '';?>"> <i class="hide"></i>通过审核</dt>
      </dl>
    </div>
    <div class="title">
      <h3>平台联系方式</h3>
    </div>
    <div class="content">
      <ul>
        <li>电话：0351-5151519</li>
        <li>邮箱：431722596@qq.com</li>
      </ul>
    </div>
  </div>
  <div class="right-layout">
    <div class="joinin-step">
      <ul>
        <li class="step1 <?php echo $output['step'] >= 0 ? 'current' : '';?>"><span>签订申请协议</span></li>
        <li class="<?php echo $output['step'] >= 1 ? 'current' : '';?>"><span>填写申请</span></li>
        <li class="<?php echo $output['step'] >= 2 ? 'current' : '';?>"><span>提交审核</span></li>
        <li class="<?php echo $output['step'] >= 3 ? 'current' : '';?>"><span>通过审核</span></li>
        <li class="<?php echo $output['step'] >= 3 ? 'current step6' : '';?>"><span>开始赚钱</span></li>
      </ul>
    </div>
    <div class="joinin-concrete">
      <?php require('store_joinin_apply.'.$output['sub_step'].'.php'); ?>
    </div>
  </div>
</div>
