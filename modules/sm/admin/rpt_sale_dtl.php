<?php

/**

 * @Project NUKEVIET 4.x

 * @Author VINADES.,JSC (contact@vinades.vn)

 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved

 * @License GNU/GPL version 2 or any later version

 * @Createdate 04/18/2017 09:47

 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['report_sale_detail'];
$table_name = NV_PREFIXLANG . "_" . $module_data . "_orders";

$checkss = $nv_Request->get_string('checkss', 'get', '');
$where = '';
$search = array();
if ($checkss == md5(session_id())) {

    $search['order_code'] = $nv_Request->get_title('order_code', 'get', '');
    $search['date_from'] = $nv_Request->get_title('from', 'get', '');
    $search['date_to'] = $nv_Request->get_title('to', 'get', '');
    $search['order_email'] = $nv_Request->get_title('order_email', 'get', '');
    $search['order_phone'] = $nv_Request->get_title('order_phone', 'get', '');
    $search['order_name'] = $nv_Request->get_title('order_name', 'get', '');
    $search['order_payment'] = $nv_Request->get_title('order_payment', 'get', '');
    $search['agencyid'] = $nv_Request->get_int('agencyid', 'get', 0);
    $search['producttype'] = $nv_Request->get_int('producttype', 'get', 0);

    if (! empty($search['order_code'])) {
        $where .= ' AND order_code like "%' . $search['order_code'] . '%"';
    }
    if (! empty($search['date_from'])) {
        if (! empty($search['date_from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_from'], $m)) {
            $search['date_from'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        } else {
            $search['date_from' ] = NV_CURRENTTIME;
        }
        $where .= ' AND order_time >= ' . $search['date_from'] . '';
    }

    if (! empty($search['date_to'])) {
        if (! empty($search['date_to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_to'], $m)) {
            $search['date_to'] = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
        } else {
            $search['date_to' ] = NV_CURRENTTIME;
        }
        $where .= ' AND order_time <= ' . $search['date_to'] . '';
    }

    if (! empty($search['order_email'])) {
        $where .= ' AND order_email like "%' . $search['order_email'] . '%"';
    }
    if (! empty($search['order_phone'])) {
        $where .= ' AND order_phone like "%' . $search['order_phone'] . '%"';
    }
    if (! empty($search['order_name'])) {
        $where .= ' AND order_name like "%' . $search['order_name'] . '%"';
    }
    if ($search['order_payment'] != '-2') {
        $where .= ' AND status  = ' . $search['order_payment'];
    }

    if (! empty($search['producttype']) && $search['producttype'] != 0) {
        $producttype = $search['producttype'] - 1;
        $where .= ' AND producttype  = ' . $producttype;
    }

    if (! empty($search['agencyid']) && $search['agencyid'] != 0) {
        $where .= ' AND (customer_id  = ' . $search['agencyid'] . ' or user_id = ' . $search['agencyid'] . ')';
    }
}

$transaction_status = array(
    '2' => $lang_module['history_payment_check'],
    '4' => $lang_module['history_payment_yes'],
    '5' => $lang_module['history_order_ships'],
    '0' => $lang_module['history_payment_no']);

$search_months = array(
    array( 'key' => 1, 'value' => 31, 'title' => $lang_module['search_month_1']),
    array( 'key' => 2, 'value' => 29, 'title' => $lang_module['search_month_2']),
    array( 'key' => 3, 'value' => 31, 'title' => $lang_module['search_month_3']),
    array( 'key' => 4, 'value' => 30, 'title' => $lang_module['search_month_4']),
    array( 'key' => 5, 'value' => 31, 'title' => $lang_module['search_month_5']),
    array( 'key' => 6, 'value' => 30, 'title' => $lang_module['search_month_6']),
    array( 'key' => 7, 'value' => 31, 'title' => $lang_module['search_month_7']),
    array( 'key' => 8, 'value' => 31, 'title' => $lang_module['search_month_8']),
    array( 'key' => 9, 'value' => 30, 'title' => $lang_module['search_month_9']),
    array( 'key' => 10, 'value' => 31, 'title' => $lang_module['search_month_10']),
    array( 'key' => 11, 'value' => 30, 'title' => $lang_module['search_month_11']),
    array( 'key' => 12, 'value' => 31, 'title' => $lang_module['search_month_12'])
);

$xtpl = new XTemplate("rpt_sale_dtl.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$count = 0;
$order_info = array( 'rpt_sum_orders' => 0, 'rpt_sum_price' => 0, 'rpt_sum_discount' => 0, 'rpt_sum_payment' => 0, 'rpt_sum_total' => 0, 'rpt_sum_debt' => 0, 'sum_unit' => '');

$sql = "SELECT count(*) ";
$sql .= "FROM nv4_vi_sm_orders t1 ";
$sql .= "	INNER JOIN nv4_vi_sm_orders_id t2 ON t1.order_id = t2.order_id ";
$sql .= "	INNER JOIN nv4_vi_sm_product t3 ON t2.proid = t3.id ";
$sql .= "WHERE depotid !=0" . $where;
$num_items = $db->query($sql)->fetchColumn();

$sql = "SELECT t1.order_id, t1.ordertype, t1.order_code, t1.order_time, t1.order_name, t1.order_phone, t1.chossentype, t1.user_id, t1.producttype, t1.status, ";
$sql .= "	t2.id, t2.proid, t3.code, t3.title, t2.num, t2.price, t2.num*t2.price as total ";
$sql .= "FROM nv4_vi_sm_orders t1 ";
$sql .= "	INNER JOIN nv4_vi_sm_orders_id t2 ON t1.order_id = t2.order_id ";
$sql .= "	INNER JOIN nv4_vi_sm_product t3 ON t2.proid = t3.id ";
$sql .= "WHERE depotid !=0" . $where . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;

//$db->select('*')->where($sql_where . $where)->order('order_id DESC')->limit($per_page)->offset(($page - 1) * $per_page);
//die($db->sql());
$query = $db->query($sql);
while ($row = $query->fetch()) {
    if( $row['chossentype'] == 3 ){
        if( $row['ordertype'] == 0 ) {
            $row['order_type'] = "Đơn trả lại khách lẻ";
        } else {
            $row['order_type'] = "Đơn hàng bán khách lẻ";
        }
    } else {
        if( $row['ordertype'] == 0 ) {
            $row['order_type'] = "Đơn trả lại CTV";
        } else {
            $row['order_type'] = "Đơn hàng bán CTV";
        }
    }
    if ($row['producttype'] == 0)
        $row['order_type'] = $row['order_type'] . " - <b class='blue'>Sản phẩm thường</b>";
    else
        $row['order_type'] = $row['order_type'] . " - <b class='red'>Sản phẩm tích lũy</b>";

    if( $row['ordertype'] == 2 ){
        $transaction_status[0] = $lang_module['history_payment_no_plane'];
        $transaction_status[4] = $lang_module['history_payment_yes_plane'];
    }else{
        $transaction_status[-1] = $lang_module['history_payment_wait'];
        $transaction_status[0] = $lang_module['history_payment_no'];
        $transaction_status[4] = $lang_module['history_payment_yes'];
    }

    if ($row['status'] == 4) {
        $row['status_payment'] = $transaction_status[4];
    }  elseif ($row['status'] == 5) {
        $row['status_payment'] = $transaction_status[5];
    } elseif ($row['status'] == 3) {
        $row['status_payment'] = $transaction_status[3];
    } elseif ($row['status'] == 2) {
        $row['status_payment'] = $transaction_status[2];
    } elseif ($row['status'] == 1) {
        $row['status_payment'] = $transaction_status[1];
    } elseif ($row['status'] == 0) {
        $row['status_payment'] = $transaction_status[0];
    } elseif ($row['status'] == - 1) {
        $row['status_payment'] = $transaction_status[-1];
    } else {
        $row['status_payment'] = "ERROR";
    }
    /*
    if( $row['price_payment'] == $row['order_total'] ){
        $row['status_payment'] = $lang_module['history_payment_yes'];
    }*/

    $row['shipcode'] = ( $row['shipcode'] == 0 )? '' : '<b>Ship COD</b>';
    $row['link_user'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=edit&userid=" . $row['user_id'];
    $row['order_time'] = nv_date("H:i d/m/y", $row['order_time']);
    $row['total'] = number_format( $row['total'], 0, '.', ',');
    if( $row['ordertype']  == 0 ){
        $row['ordertype_title'] = '<b class="red"> ' . $lang_module['return_order'] . '</b>';
    }elseif( $row['ordertype'] == 2 ){
        $row['ordertype_title'] = '<b class="blue"> ' . $lang_module['ordertype_2'] . '</b>';
    }


    $xtpl->assign('DATA', $row);

    $xtpl->assign('id', $row['id'] . "_" . md5($row['id'] . $global_config['sitekey'] . session_id()));
    if( in_array( $row['ordertype'], array(1,2))){
        $xtpl->assign('link_view', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_view&order_id=" . $row['order_id'] . '&checkss=' . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    }
    else{
        $xtpl->assign('link_view', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=return-or-view&order_id=" . $row['order_id'] . '&checkss=' . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    }

    $xtpl->parse('main.data.row');
    ++$count;
}


$xtpl->assign('sql_show', base64_encode($db->sql()));
$xtpl->assign('URL_CHECK_PAYMENT', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checkpayment");
$xtpl->assign('URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_del");
$xtpl->assign('URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('PAGES', nv_generate_page($base_url, $num_items, $per_page, $page));
$xtpl->assign('num_items', $num_items);
$xtpl->parse('main.data');

foreach ($transaction_status as $key => $lang_status) {

    $xtpl->assign('TRAN_STATUS', array( 'key' => $key, 'title' => $lang_status, 'selected' => (isset($search['order_payment']) and $key == $search['order_payment']) ? 'selected="selected"' : '' ));
    $xtpl->parse('main.transaction_status');
}

foreach ($search_months as $month) {
    $xtpl->assign('SEARCH_MONTH', array( 'key' => $month['key'], 'value' => $month['value'], 'title' => $month['title'], 'selected' => ( !empty( $search['search_month'] ) && $month['key'] == $search['search_month']) ? 'selected="selected"' : '' ));
    $xtpl->parse('main.search_month');
}
foreach ( $array_select_producttype as $key => $producttype ){
    $sl = (! empty($search['producttype']) && $search['producttype'] == $key) ? ' selected=selected' : '';
    $xtpl->assign('PRODUCTTYPE', array('key' => $key, 'value' => $producttype, 'sl' => $sl));
    $xtpl->parse('main.producttype');
}

//he thong ctv, dl
$list_Agency = nvGetListCurrentAgency();
if( !empty($list_Agency )){
    //danh sach agency
    foreach ($list_Agency as $agency){
        $agency['sl'] = (! empty($search['agencyid']) && $search['agencyid'] == $agency['key']) ? ' selected=selected' : '';
        $xtpl->assign('AGENCY', $agency);
        $xtpl->parse('main.agency.loop');
    }
    $xtpl->parse('main.agency');
}

if (! empty($search['date_from'])) {
    $search['date_from'] = nv_date('d/m/Y', $search['date_from']);
}

if (! empty($search['date_to'])) {
    $search['date_to'] = nv_date('d/m/Y', $search['date_to']);
}

$xtpl->assign('ORDER_INFO', $order_info);
$xtpl->assign('CHECKSESS', md5(session_id()));
$xtpl->assign('SEARCH', $search);


$xtpl->parse('main');
$contents = $xtpl->text('main');


include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
