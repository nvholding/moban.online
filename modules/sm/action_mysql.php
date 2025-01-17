<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */



if (! defined('NV_IS_FILE_MODULES')) {

    die('Stop!!!');
}

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_product;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_units;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_saleoff;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_saleoff_detail;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_customer";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_discounts";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_orders";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_orders_id";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_orders_id_out";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_transaction";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_warehouse";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_warehouse_logs";//bang ghi tong sn xuat nhap cua tung kho hang
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_importproduct";//bang ghi cac lan nhap hang cua kho tong gmwhite
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_importproduct_history";//bang ghi chi tiet cac lan nhap hang cua kho tong gmwhite
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_statistic";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_warehouse_order";//the kho, ghi thong tin xuat nhap hang

//cac bang cham soc kh mua hang
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_scenario_header";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_scenario_detail";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_message_queue";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_message_history";

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_depot";
$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_product (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 catid int(11) NOT NULL DEFAULT '0',
 productshopid int(11) NOT NULL DEFAULT '0' COMMENT 'ID sp tai module shops',
 unit smallint(4) NOT NULL,
 pnumber int(11) NOT NULL DEFAULT '0',
 pnumberout int(11) NOT NULL DEFAULT '0',
 addtime int(11) unsigned NOT NULL DEFAULT '0',
 edittime int(11) unsigned NOT NULL DEFAULT '0',
 priceshow tinyint(4) NOT NULL DEFAULT '1',
 status tinyint(4) NOT NULL DEFAULT '1',
 code varchar(250) NOT NULL DEFAULT '',
 title varchar(250) NOT NULL,
 alias varchar(250) NOT NULL,
 image varchar(250) NOT NULL,
 price_in float DEFAULT 0 COMMENT 'gia nhap',
 price_retail float DEFAULT 0 COMMENT 'gia ban le',
 price_wholesale float DEFAULT 0 COMMENT 'gia ban si',
 weight smallint(4) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 UNIQUE KEY code (code)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat(
  id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  parentid smallint(4) unsigned NOT NULL DEFAULT '0',
  title varchar(250) NOT NULL DEFAULT '',
  alias varchar(250) NOT NULL DEFAULT '',
  custom_title varchar(255) NOT NULL DEFAULT '',
  keywords text NOT NULL,
  description tinytext NOT NULL,
  description_html text NOT NULL,
  groups_view varchar(255) NOT NULL DEFAULT '6',
  image varchar(255) NOT NULL DEFAULT '',
  lev smallint(4) unsigned NOT NULL DEFAULT '0',
  numsub smallint(4) unsigned NOT NULL DEFAULT '0',
  subid varchar(255) NOT NULL,
  sort smallint(4) unsigned NOT NULL DEFAULT '0',
  inhome tinyint(1) unsigned NOT NULL DEFAULT '1',
  numlinks tinyint(3) unsigned NOT NULL DEFAULT '4',
  viewtype tinyint(1) unsigned NOT NULL DEFAULT '1',
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
  PRIMARY KEY (id),
  UNIQUE KEY alias (alias)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_units (
 id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(250) NOT NULL,
 note text NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_saleoff (
 id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
 salesfrom float DEFAULT 0 COMMENT 'doanh thu tu',
 salesto float DEFAULT 0 COMMENT 'doanh thu den',
 moneyrequire float DEFAULT 0 COMMENT 'số tiền thỏa mãn đk',
 status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_saleoff_detail (
 id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
 saleoffid smallint(4) unsigned NOT NULL,
 productid smallint(4) unsigned NOT NULL COMMENT 'SP dc tặng',
 numbergift smallint(4) unsigned NOT NULL COMMENT 'SL dc tặng',
 moneygift float DEFAULT 0 COMMENT 'Tiền được tặng',
 status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái',
 PRIMARY KEY (id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_customer (
 customer_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 refer_userid mediumint(8) unsigned NOT NULL COMMENT 'Nguoi gioi thieu',
 code varchar(250) NOT NULL DEFAULT '',
 fullname varchar(100) NOT NULL,
 address varchar(200) NOT NULL,
 phone varchar(50) NOT NULL,
 email varchar(100) NOT NULL,
 description text,
 add_time int(11) NOT NULL DEFAULT '0',
 edit_time int(11) NOT NULL DEFAULT '0',
 custype tinyint(1) unsigned NOT NULL DEFAULT '0',
 status tinyint(1) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (customer_id),
 UNIQUE KEY code (code),
 UNIQUE KEY name_phone (refer_userid,fullname,phone)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_discounts (
  id smallint(6) NOT NULL AUTO_INCREMENT,
  productid int(10) unsigned NOT NULL DEFAULT '0',
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  begin_quantity int(10) unsigned NOT NULL DEFAULT '0',
  end_quantity int(10) unsigned NOT NULL DEFAULT '0',
  percent float unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY productid (productid),
  KEY begin_quantity (begin_quantity),
  KEY end_quantity (end_quantity)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_orders (
 order_id int(11) unsigned NOT NULL auto_increment,
 customer_id mediumint(8) unsigned NOT NULL,
 order_code varchar(30) NOT NULL default '',
 order_name varchar(250) NOT NULL,
 order_email varchar(250) NOT NULL,
 order_phone varchar(20) NOT NULL,
 order_address varchar(250) NOT NULL,
 order_note text NOT NULL,
 user_id int(11) unsigned NOT NULL default '0' COMMENT 'ID tuyen tren',
 admin_id int(11) unsigned NOT NULL default '0' COMMENT 'ID người xác nhận đặt hàng',
 order_total double NOT NULL default '0',
 order_time int(11) unsigned NOT NULL default '0',
 edit_time int(11) unsigned NOT NULL default '0',
 postip varchar(100) NOT NULL,
 saleoff double NOT NULL DEFAULT '0' COMMENT 'số tiền giam gia cho ĐL',
 feeship double unsigned NOT NULL default '0' COMMENT 'Phí ship hàng',
 price_payment double unsigned NOT NULL default '0' COMMENT 'Tổng tiền đã thanh toán',
 shipcode tinyint NOT NULL DEFAULT '0' COMMENT 'Ship COD',
 showadmin tinyint NOT NULL DEFAULT '0' COMMENT 'Đơn hàng của 1=NV CTY',
 chossentype tinyint(4) NOT NULL COMMENT 'Kiểu nhập hàng 1: cho mình, 2 cho ĐL dưới cấp, 3 khách lẻ',
 ordertype tinyint(4) NOT NULL COMMENT '1: Nhập hàng, 0: trả hàng',
 orderid_refer int(11) unsigned NOT NULL default '0' COMMENT 'ID đơn hàng khi trả',
 amount_refunded double NOT NULL DEFAULT '0' COMMENT 'Số tiền còn sau khi trả hàng',
 status tinyint(4) NOT NULL,
 PRIMARY KEY (order_id),
 UNIQUE KEY order_code (order_code),
 KEY user_id (user_id),
 KEY order_time (order_time)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_orders_id (
 id int(11) unsigned NOT NULL AUTO_INCREMENT,
 order_id int(11) NOT NULL,
 proid mediumint(9) NOT NULL,
 num mediumint(9) NOT NULL,
 type_return tinyint(4) NOT NULL COMMENT '1 bị hỏng, 2 k bán được',
 numreturn MEDIUMINT NOT NULL default '0' COMMENT 'SP da tra',
 price int(11) NOT NULL,
 num_out int(10) unsigned NOT NULL,
 num_com int(10) unsigned NOT NULL COMMENT 'SL cty sẽ xuất',
 isgift tinyint(4) unsigned NOT NULL COMMENT '0 SP Mua, 1 la SP Tang',
 PRIMARY KEY (id),
 UNIQUE KEY orderid (order_id, id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_orders_id_out (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id int(11) NOT NULL,
  proid mediumint(9) NOT NULL,
  num_out int(10) UNSIGNED NOT NULL DEFAULT '0',
  timeout int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY orderid (order_id,id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_transaction (
 transaction_id int(11) NOT NULL AUTO_INCREMENT,
 transaction_time int(11) NOT NULL DEFAULT '0',
 transaction_status int(11) NOT NULL,
 order_id int(11) NOT NULL DEFAULT '0',
 userid int(11) NOT NULL DEFAULT '0',
 payment varchar(100) NOT NULL DEFAULT '0',
 payment_id varchar(22) NOT NULL DEFAULT '0',
 payment_time int(11) NOT NULL DEFAULT '0',
 payment_amount float NOT NULL DEFAULT '0',
 payment_data text NOT NULL,
 PRIMARY KEY (transaction_id),
 KEY order_id (order_id),
 KEY payment_id (payment_id)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_warehouse (
  customerid int(11) unsigned NOT NULL,
  title varchar(250) NOT NULL,
  note TEXT NOT NULL,
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  price_discount_in float NOT NULL DEFAULT '0' COMMENT 'So tien dc chiet khau',
  price_discount_out float NOT NULL DEFAULT '0' COMMENT 'So tien da chiet khau',
  UNIQUE KEY customerid (customerid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_warehouse_logs (
  customerid int(11) unsigned NOT NULL default '0',
  depotid smallint(6) unsigned NOT NULL default '0',
  productid int(11) unsigned NOT NULL default '0',
  quantity_in INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'SL SP trong kho',
  price_in float NOT NULL DEFAULT '0' COMMENT 'So tien thu duoc',
  quantity_out INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'SL SP da ban',
  price_out float NOT NULL DEFAULT '0' COMMENT 'So tien da nhap',
  quantity_com INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Tồn tại cty',
  KEY customerid (customerid)
) ENGINE=MyISAM";

//thong tin xuat nhap hang
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_warehouse_order (
  customerid int(11) unsigned NOT NULL default '0',
  depotid smallint(6) unsigned NOT NULL default '0',
  productid int(11) unsigned NOT NULL default '0',
  orderid int(11) unsigned NOT NULL default '0',
  quantity_befor INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'SL trc nhap-xuat',
  quantity_in INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'SL nhập',
  price_in float NOT NULL DEFAULT '0' COMMENT 'So tien thu duoc',
  quantity_after INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'SL sau nhap-xuat',
  quantity_out INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'SL ban',
  price_out float NOT NULL DEFAULT '0' COMMENT 'So tien da nhap',
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  KEY customerid (customerid)
) ENGINE=MyISAM";

//ghi lich su cac lan nhap hang

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_importproduct (
  iid int(11) NOT NULL AUTO_INCREMENT,
  customerid int(11) unsigned NOT NULL,
  title varchar(250) NOT NULL,
  note TEXT NOT NULL,
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (iid)
) ENGINE=MyISAM";


//ghi lich su cac lan nhap hang danh cho kho gmwhite

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_importproduct_history (
  iid int(11) unsigned NOT NULL default '0',
  productid int(11) unsigned NOT NULL default '0',
  quantity INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'SL SP nhap kho',
  totalprice float NOT NULL DEFAULT '0' COMMENT 'So tien da nhap',
  UNIQUE KEY productid (iid,productid)
) ENGINE=MyISAM";


//thống kê doanh thu theo tháng từng user

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_statistic (
 customer_id mediumint(8) unsigned NOT NULL,
 monthyear int(11) unsigned NOT NULL default '0' COMMENT 'Doanh thu tháng nào',
 total_price float NOT NULL DEFAULT '0' COMMENT 'So tien',
 UNIQUE KEY customer_id (customer_id,monthyear),
 KEY monthyear (monthyear)
) ENGINE=MyISAM";

//kich ban cham soc
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_scenario_header (
 id int(10) unsigned NOT NULL auto_increment,
 proid mediumint(8) unsigned NOT NULL,
 note TEXT NOT NULL,
 addtime int(10) unsigned NOT NULL default '0',
 status tinyint(1) NOT NULL,
 PRIMARY KEY (id),
 KEY proid (proid)
) ENGINE=MyISAM";

//kich ban cham soc chi tiet
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_scenario_detail (
 id int(10) unsigned NOT NULL auto_increment,
 scenarioid int(10) unsigned NOT NULL,
 title varchar(250) NULL,
 content TEXT NOT NULL,
 daysend smallint(4) unsigned NOT NULL default '0' COMMENT 'Gửi vào ngày thứ mấy kể từ khi đặt hàng',
 hoursend tinyint(1) unsigned NOT NULL default '0' COMMENT 'Giờ sẽ gửi tin',
 addtime int(10) unsigned NOT NULL default '0',
 sendtype tinyint(1) NOT NULL COMMENT '1: SMS, 2: Email, 3: Notification app',
 status tinyint(1) NOT NULL,
 PRIMARY KEY (id),
 KEY scenarioid (scenarioid)
) ENGINE=MyISAM";

//Message Queue
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_message_queue (
 id int(10) unsigned NOT NULL auto_increment,
 order_id int(10) unsigned NOT NULL,
 proid int(10) unsigned NOT NULL,
 sid int(10) unsigned NOT NULL COMMENT 'ID bang scenario_header',
 sid_detail int(10) unsigned NOT NULL COMMENT 'ID bang scenario_detail',
 title varchar(250) NOT NULL,
 receiver varchar(250) NOT NULL COMMENT 'Người nhận, SĐT nếu sendtype=1, Enail nếu sendtype=2...',
 content TEXT NOT NULL,
 timesend int(10) unsigned NOT NULL default '0',
 sendtype tinyint(1) NOT NULL COMMENT '1: SMS, 2: Email, 3: Notification app',
 active tinyint(1) NOT NULL COMMENT '1: kích hoạt, 0 không',
 PRIMARY KEY (id),
 KEY timesend (timesend),
 KEY active (active)
) ENGINE=MyISAM";

//Message history
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_message_history (
 id int(10) unsigned NOT NULL auto_increment,
 order_id int(10) unsigned NOT NULL,
 proid int(10) unsigned NOT NULL,
 sid int(10) unsigned NOT NULL COMMENT 'ID bang scenario_header',
 sid_detail int(10) unsigned NOT NULL COMMENT 'ID bang scenario_detail',
 title varchar(250) NOT NULL,
 receiver varchar(250) NOT NULL COMMENT 'Người nhận, SĐT nếu sendtype=1, Enail nếu sendtype=2...',
 content TEXT NOT NULL,
 timesend int(10) unsigned NOT NULL default '0',
 sendtype tinyint(1) NOT NULL COMMENT '1: SMS, 2: Email, 3: Notification app',
 timesent int(10) unsigned NOT NULL default '0',
 smsid varchar(50) NOT NULL default '',
 status tinyint(1) NOT NULL,
 PRIMARY KEY (id),
 KEY timesend (timesend)
) ENGINE=MyISAM";


//cac kho hang cty
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_depot(
 id smallint(6) unsigned NOT NULL auto_increment,
 userid mediumint(8) unsigned NOT NULL,
 title varchar(250) NOT NULL,
 address varchar(250) NOT NULL,
 mobile varchar(30) NOT NULL,
 addtime int(10) unsigned NOT NULL default '0',
 status tinyint(1) NOT NULL,
 PRIMARY KEY (id),
 KEY proid (userid)
) ENGINE=MyISAM";


$data = array();
$data['percent_book_one'] = 20;
$data['percent_allow_ok'] = 70;
$data['ketoan'] = '';
$data['kho'] = '';
$data['deposits'] = '20';
$data['percent_discount_1'] = '30';
$data['percent_discount_2'] = '40';
$data['percent_discount_3'] = '50';

$data['sms_on'] = 1;
$data['sms_type'] = 2;
$data['apikey'] = '81CF49D2388126412DB2E7CE63CA46';
$data['secretkey'] = 'EE310DB80631F52381480F085CAF8D';
$data['email_notify'] = 'kid.apt@gmail.com';
$data['brandname'] = 'CASH13';
foreach ($data as $config_name => $config_value) {
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', " . $db->quote($module_name) . ", " . $db->quote($config_name) . ", " . $db->quote($config_value) . ")";
}