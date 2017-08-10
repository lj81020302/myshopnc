<?php defined('ShopNC_CLUB') or exit('Access Invalid!'); return array (
  'system' => 
  array (
    'name' => '首页',
    'top_code' => '10100',
    'url' => 'index.php?act=index&op=index&top_code=10100',
    'child' => array()
  ),
  'goods' => 
  array (
    'name' => '商品',
    'top_code' => '10200',
    'url' => 'index.php?act=goods&op=index&top_code=10200&menu_code=10210',
    'child' => 
    array (
      0 => 
      array (
        'name' => '商品管理',
        'top_code' => '10200',
        'menu_code' => '10210',
        'url' => 'index.php?act=goods&op=index&top_code=10200&menu_code=10210',
      ),
        1 =>
        array (
            'name' => '订单列表',
            'top_code' => '10200',
            'menu_code' => '10220',
            'url' => 'index.php?act=agent&op=order&top_code=10200&menu_code=10220',
        ),
      2 => 
      array (
        'name' => '进货市场',
        'top_code' => '10200',
        'menu_code' => '10230',
        'url' => 'index.php?act=goods&op=market&top_code=10200&menu_code=10230',
      ),
      3=>
      array (
        'name' => '进货单',
        'top_code' => '10200',
        'menu_code' => '10240',
        'url' => 'index.php?act=goods&op=purchase_order&top_code=10200&menu_code=10240',
      ),
    ),
  ),
  'agent' => 
  array (
    'name' => '统计',
    'top_code' => '10300',
    'url' => 'index.php?act=goods&op=statistics&top_code=10300&menu_code=10310',
    'child' => 
    array (
      0 => 
      array (
        'name' => '商品统计',
        'top_code' => '10300',
        'menu_code' => '10310',   
        'url' => 'index.php?act=goods&op=statistics&top_code=10300&menu_code=10310',
      ),
      
      1 => 
      array (
        'name' => '佣金列表',
        'top_code' => '10300',
        'menu_code' => '10320',
        'url' => 'index.php?act=agent&op=commission&top_code=10300&menu_code=10320',
      ),
      2 => 
      array (
        'name' => '团队管理',
        'top_code' => '10300',
        'menu_code' => '10330',
        'url' => 'index.php?act=agent&op=team_manage&top_code=10300&menu_code=10330',
      ),
    ),
  ),
  'balance' => 
  array (
    'name' => '结算',
    'top_code' => '10400',
    'url' => 'index.php?act=balance&op=balance&top_code=10400&menu_code=10410',
    'child' => 
    array (
      0 => 
      array (
        'name' => '账号余额',
        'top_code' => '10400',
        'menu_code' => '10410',
        'url' => 'index.php?act=balance&op=balance&top_code=10400&menu_code=10410',
      ),
      1 => 
      array (
        'name' => '结算列表',
        'top_code' => '10400',
        'menu_code' => '10420',
        'url' => 'index.php?act=balance&op=balance_manage&top_code=10400&menu_code=10420',
      ),
      2 => 
      array (
        'name' => '提现记录',
        'top_code' => '10400',
        'menu_code' => '10430',   
        'url' => 'index.php?act=balance&op=index&top_code=10400&menu_code=10430',
      ),
      3 => 
      array (
        'name' => '提现账号',
        'top_code' => '10400',
        'menu_code' => '10440',   
        'url' => 'index.php?act=balance&op=bankaccount&top_code=10400&menu_code=10440',
      ),
    ),
  ),
);
