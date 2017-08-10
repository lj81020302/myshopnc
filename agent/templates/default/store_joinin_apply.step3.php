<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="explain">
<?php if($output['is_pass'] == 1){?>
<p>恭喜您，审核成功</p>
<p>您的编号为：<span style="color:red;"><?php echo $output['agent_no'];?></span>，请使用该编号登录代理商管理中心开始赚钱吧！。</p>
<?php }else if($output['is_pass'] == -1){ ?>
<p>很遗憾，审核失败</p>
<?php }else if($output['is_pass'] == 0){ ?>
<p>正在审核中，请您耐心等待！。</p>
<?php }?>
</div>


<div class="bottom">
<?php if($output['is_pass'] == 1){?>
  <a id="" href="<?php echo urlAgent("agent")?>" class="btn">开始赚钱</a>
  <?php }else if($output['is_pass'] == -1){ ?>
  <a id="" href="<?php echo urlAgent("agent_register", 'show_join')?>" class="btn">重新申请</a>
  <?php }?>
</div>