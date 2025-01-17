<?php
/**

 * @Project NUKEVIET 4.x

 * @Author VINADES.,JSC (contact@vinades.vn)

 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved

 * @License GNU/GPL version 2 or any later version

 * @Createdate Tue, 18 Nov 2014 10:21:15 GMT

 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
    die( 'Stop!!!' );

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$per_page = 20;
$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$q = $nv_Request->get_title( 'q', 'post,get', '' );
$sstatus = $nv_Request->get_int( 'sstatus', 'post,get', -1 );
$product = $nv_Request->get_title('product', 'post,get', '');
$where = '';
$sql_where = '';

if( $sstatus != -3 ) {
    if ($sstatus == -2)
        $where .= ' and t1.bonus_point > 0';
    elseif ($sstatus == -1)
        $where .= ' and t5.status in (0,1)';
    else
        $where .= ' and t5.status = ' . $sstatus;
    $sql_where = $where;
    $base_url .= '&amp;sstatus=' . $sstatus;
}
if( !empty( $q ) ) {
    $where .= " and (t1.barcode like '%" . $q  . "%' or t1.bonus_gift = '" . $q . "')";
    $sql_where .= " and (t1.barcode like '%" . $q  . "%' or t1.bonus_gift = '" . $q . "' or t4.fullname LIKE '%" . $q . "%')";
    $base_url .= '&amp;q=' . $q;
}

if( !empty( $product ) ) {
    $where .= " and t1.bonus_gift = '" . $product . "'";
    $sql_where .= " and t1.bonus_gift = '" . $product . "'";
    $base_url .= '&amp;product=' . $product;
}

$sql = "SELECT count(*) 
    FROM nv4_vi_sm_barcode t1
        LEFT OUTER JOIN nv4_vi_sm_customer_gifts t5 ON t1.barcode = t5.barcode
    WHERE t1.status=1 ". $where;
//echo $sql; die();
$num_items = $db->query($sql)->fetchColumn();

$sql = "SELECT t1.*, t2.username, concat(t2.first_name, ' ', t2.last_name) as customer, t2.phone as customer_phone, t2.address as customer_address, 
            t4.customer_id, t4.fullname, t4.address, t4.phone, t4.email,t5.gift_desc,
        concat(t3.first_name, ' ', t3.last_name) as agency, t3.phone as agency_phone,IFNULL(t5.agencyid,0) as agencyid
    FROM nv4_vi_sm_barcode t1
        LEFT OUTER JOIN nv4_users t2 ON t1.customerid = t2.userid
        LEFT OUTER JOIN nv4_vi_sm_customer_gifts t5 ON t1.barcode = t5.barcode
        LEFT OUTER JOIN nv4_users t3 ON t5.agencyid = t3.userid
        LEFT OUTER JOIN nv4_vi_sm_customer t4 ON t4.refer_userid = t1.customerid
    WHERE t1.status=1 ". $sql_where . " LIMIT " . $per_page . ' OFFSET ' . (($page - 1) * $per_page);
//echo $sql; die();
//echo $db->sql();die();
$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/shared' );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'Q', $q );
$xtpl->assign( 'TOTAL_GIFT', $num_items );

$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );

$result = $db->query($sql);
$number = 0;
while( $view = $result->fetch() )
{
    $view['customer_type'] = empty($view['customer_id']) ? 'NPP/ĐL Minh Khang' : 'Khách lẻ';
    $view['gift_status'] = $view['bonus_point'] > 0 ? "Sử dụng mã cào ngày " . date( 'd/m/Y H:i', $view['used_date'] ) : (empty($view['agencyid']) ? 'Chưa nhận quà' : "Đã nhận quà ngày " . date( 'd/m/Y H:i', $view['updated_date'] ) . " bởi " . $view['agency'] . ' [' . $view['agency_phone'] . ']');
    $view['number'] = ++$number;
    $view['link_gift'] = empty($view['customer_id']) ? '#' : NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=customer_gifts&amp;id=' . $view['customer_id'];
    $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=exchange_gifts&amp;id=' . $view['customer_id'];
    $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_customer_id=' . $view['customer_id'] . '&amp;delete_checkss=' . md5( $view['customer_id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
    $view['edit_time'] = date( 'd/m/Y H:i', $view['edit_time'] );
    $view['status'] = $lang_module['active_' . $view['status']];
    $xtpl->assign( 'VIEW', $view );
    $xtpl->parse( 'main.loop' );
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_giftcode';
$array_gift = $db->query( $sql );
while ($pro = $array_gift->fetch()) {
    $pro['sl'] = ( $product == $pro['code'])? ' selected=selected' : '';
    $xtpl->assign('PRODUCT', $pro);
    $xtpl->parse('main.product');
}

$array_status = array( '-2' => "Thẻ cào tích điểm",'-1' => "Thẻ cào quà tặng", '0' => "Thẻ cào quà tặng Chưa nhận quà", '1' => "Thẻ cào quà tặng Đã nhận quà" );
foreach( $array_status as $key => $_status ) {
    $sl = ( $key == $sstatus ) ? ' selected="selected"' : '';
    $xtpl->assign( 'SEARCH_STATUS', array(
        'selected' => $sl,
        'key' => $key,
        'value' => $_status ) );
    $xtpl->parse( 'main.search_status' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
$page_title = "Thống kê thẻ cào";

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';