ALTER TABLE `#__store_joinin` ADD `is_person` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为个人 1是，0否';
ALTER TABLE `#__store` ADD `is_person` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为个人 1是，0否';
ALTER TABLE `#__brand` ADD `brand_view` int(10) NOT NULL COMMENT '品牌单页浏览量';
update `#__brand` set brand_bgpic=replace(brand_bgpic,'/data/upload/shop/editor/brand_default_max.jpg','brand_default_max.jpg');
update `#__brand` set brand_xbgpic=replace(brand_xbgpic,'/data/upload/shop/editor/brand_default_small.jpg','brand_default_small.jpg');
ALTER TABLE `#__brand` CHANGE `brand_bgpic` `brand_bgpic` VARCHAR(100) NULL DEFAULT 'brand_default_max.jpg' COMMENT '品牌大图', CHANGE `brand_xbgpic` `brand_xbgpic` VARCHAR(100) NULL DEFAULT 'brand_default_small.jpg' COMMENT '品牌小图';
