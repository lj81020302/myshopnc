<?php defined('ShopNC_CLUB') or exit('Access Invalid!');?>

<div class="page-container">
	<div style="position: absolute;left: 40%;top: 30%;margin-top: -140px;">
		<div style="font-size: 26px;margin: 50px 0;">
			
			<p>代理商中心</p>
		</div>
		<div >
			<form id="form_login" method="post">
			<?php Security::getToken();?>
			<input type="hidden" name="form_submit" value="ok"/>
    		<input type="hidden" name="SiteUrl" id="SiteUrl" value="<?php echo SHOP_SITE_URL;?>" />
				<div class="input-text-box">
					<div class="form-group">
						<label class="tit">帐号</label>
						<!-- 用户名 -->
						<input type="text" id="user_name" class="username input-text" name="user_name" autocomplete="off" placeholder="请输入用户名" />
					</div>
					<div class="form-group">
						<label class="tit">密码</label>
						<!-- 密码 -->
						<input type="password" id="password" class="username input-text" name="password" autocomplete="off" placeholder="请输入密码 " />
						<div class="js-info-error tip-error animated fadeInLefts" style="animation-duration: 0.2s; display:none;width: 100px;left:315px;">
<!-- 							<div class="tip-arrow"></div> -->
							<div class="tip-inners"></div>
						</div>
					</div>
										<div class="form-group checkbox-signup">
					</div>
				</div>
				<input type="submit" class="submit" value="立即登录" />
			</form>
		</div>
	</div>
	<div class="bottom">
		<h6>酒天仙女商城(www.jiutianxiannv.com) 版权所有</h6>
		<div class="copyright">
			<p><a href="www.niushop.com.cn" target="_blank" class="copyright-logo">酒天仙女网提供技术支持</a></p>
		</div>
	</div>
</div>

