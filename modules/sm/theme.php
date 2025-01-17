<?php



/**

 * @Project NUKEVIET 4.x

 * @Author VINADES.,JSC <contact@vinades.vn>

 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved

 * @License GNU/GPL version 2 or any later version

 * @Createdate Jul 11, 2010 8:43:46 PM

 */



if (!defined('NV_IS_MOD_SM')) {

    die('Stop!!!');

}



/**

 * nv_page_main()

 *

 * @param mixed $row

 * @param mixed $ab_links

 * @return

 */

function nv_page_main($row, $ab_links, $content_comment)

{

    global $module_name, $lang_module, $lang_global, $module_info, $meta_property, $client_info, $page_config;



    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    $xtpl->assign('LANG', $lang_module);

    $xtpl->assign('GLANG', $lang_global);

    $xtpl->assign('CONTENT', $row);



    if (!empty($row['description'])) {

        $xtpl->parse('main.description');

    }



    if ($row['socialbutton']) {

        if (!empty($page_config['facebookapi'])) {

            $meta_property['fb:app_id'] = $page_config['facebookapi'];

            $meta_property['og:locale'] = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';



            $xtpl->assign('SELFURL', $client_info['selfurl']);

            $xtpl->parse('main.socialbutton.facebook');

        }



        $xtpl->parse('main.socialbutton');

    }



    if (!empty($row['image'])) {

        if ($row['imageposition'] > 0) {

            if ($row['imageposition'] == 1) {

                if (!empty($row['imagealt'])) {

                    $xtpl->parse('main.imageleft.alt');

                }

                $xtpl->parse('main.imageleft');

            } else {

                if (!empty($row['imagealt'])) {

                    $xtpl->parse('main.imagecenter.alt');

                }

                $xtpl->parse('main.imagecenter');

            }

        }

    }



    if (defined('NV_IS_MODADMIN')) {

        $xtpl->assign('ADMIN_CHECKSS', md5($row['id'] . NV_CHECK_SESSION));

        $xtpl->assign('ADMIN_EDIT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id']);

        $xtpl->parse('main.adminlink');



        // Hiển thị cảnh báo cho người quản trị nếu bài ngưng hoạt động

        if (!$row['status']) {

            $xtpl->parse('main.warning');

        }

    } elseif (!$row['status']) {

        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);

    }



    if (!empty($ab_links)) {

        foreach ($ab_links as $row) {

            $xtpl->assign('OTHER', $row);

            $xtpl->parse('main.other.loop');

        }

        $xtpl->parse('main.other');

    }



    if (!empty($content_comment)) {

        $xtpl->assign('CONTENT_COMMENT', $content_comment);

        $xtpl->parse('main.comment');

    }



    $xtpl->parse('main');

    return $xtpl->text('main');

}



/**

 * nv_page_main_list()

 *

 * @param mixed $array_data

 * @return

 */

function nv_page_main_list($array_data, $generate_page)

{

    global $lang_global, $module_upload, $module_info, $module_name;



    $template = (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'] . '/main_list.tpl')) ? $module_info['template'] : 'default';



    $xtpl = new XTemplate('main_list.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_info['module_theme']);

    $xtpl->assign('GLANG', $lang_global);



    if (!empty($array_data)) {

        foreach ($array_data as $row) {

            if (!empty($row['image'])) {

                if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload . '/' . $row['image'])) {

                    $row['image'] = NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $module_upload . '/' . $row['image'];

                } elseif (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'])) {

                    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];

                } else {

                    $row['image'] = '';

                }

                $row['imagealt'] = !empty($row['imagealt']) ? $row['imagealt'] : $row['title'];

            }



            $xtpl->assign('DATA', $row);



            if (!empty($row['image'])) {

                $xtpl->parse('main.loop.image');

            }

            if (defined('NV_IS_MODADMIN')) {

                $xtpl->assign('ADMIN_CHECKSS', md5($row['id'] . NV_CHECK_SESSION));

                $xtpl->assign('ADMIN_EDIT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id']);

                $xtpl->parse('main.loop.adminlink');

            }

            $xtpl->parse('main.loop');

        }

        if ($generate_page != '') {

            $xtpl->assign('GENERATE_PAGE', $generate_page);

        }

    }



    $xtpl->parse('main');

    return $xtpl->text('main');

}





/**

 * cart_product()

 *

 * @param mixed $data_content

 * @param mixed $coupons_code

 * @param mixed $array_error_number

 * @return

 */

function cart_product($order_info, $user_data_affiliate, $array_agency, $list_Agency, $array_order_book_plane, $error )
{
    global $module_info, $lang_module, $module_file, $array_agency, $array_select_ordertype;

    $user_data_affiliate['agency_info'] = isset( $array_agency[$user_data_affiliate['agencyid']] )? $array_agency[$user_data_affiliate['agencyid']] : '';
    if( !empty( $user_data_affiliate['agency_info'] ) && $user_data_affiliate['agency_info']['percent_sale'] > 0){
        $lang_module['percent_sale_agency'] = sprintf( $lang_module['percent_sale_agency'], $user_data_affiliate['agency_info']['percent_sale'] . '%' );
        if( $user_data_affiliate['agency_info']['number_sale'] > 0 and $user_data_affiliate['agency_info']['number_gift'] >0 ){
            $lang_module['percent_sale_agency'] = $lang_module['percent_sale_agency'] . sprintf( $lang_module['gift_product_agency'], $user_data_affiliate['agency_info']['number_sale'], $user_data_affiliate['agency_info']['number_gift'] );
        }
    }else{
        $lang_module['percent_sale_agency'] = '';
    }

    $xtpl = new XTemplate('book-order.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_DEFINE_DEPOSITS', NV_DEFINE_DEPOSITS);

    if( $order_info['chossentype'] == 1 ){
        $xtpl->assign('chossentype_1', ' checked');
    }elseif( $order_info['chossentype'] == 2 ){
        $xtpl->assign('chossentype_2', ' checked');
    }elseif( $order_info['chossentype'] == 3 ){
        $xtpl->assign('chossentype_3', ' checked');
    }
    $xtpl->assign('order_shipcod', $order_info['order_shipcod'] == 1 ? ' checked=checked' : '');

    foreach ( $array_select_ordertype as $key => $ordertype ){
        $sl = ( $order_info['ordertype'] == $key )? ' selected=selected' : '';
        $xtpl->assign('ORDERTYPE', array('key' => $key, 'value' => $ordertype, 'sl' => $sl));
        $xtpl->parse('main.ordertype');
    }
    if( !empty( $error )){
        $xtpl->assign('ERROR', implode('<br />', $error));
        $xtpl->parse('main.error');
    }
    if( !empty($list_Agency )){
        //danh sach agency
        foreach ($list_Agency as $agency){
            $agency['sl'] = ( $order_info['chossentype'] != 3 && $agency['key'] == $order_info['customer_id'] )? ' selected=selected' : '';
            $xtpl->assign('AGENCY', $agency);
            $xtpl->parse('main.agency.loop');
        }
        $xtpl->parse('main.agency');
    }
    else{
        $xtpl->assign('link_here', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=affiliate&' . NV_OP_VARIABLE . '=register');
        $xtpl->parse('main.noagency');
    }

    if( !empty( $array_order_book_plane )){
        foreach ($array_order_book_plane as $book_plane ){
            $book_plane['sl'] = ( $data_order['orderid'] == $book_plane['order_id'] )? ' selected=selected' : '';
            $xtpl->assign('BOOK_PLANE', $book_plane);
            $xtpl->parse('main.list_order.loop');
        }
        $xtpl->parse('main.list_order');
    }else{
        $xtpl->parse('main.no_order');
    }

    $xtpl->assign('DATA', $order_info);
    $xtpl->assign('USERINFO', $user_data_affiliate );

    if( $order_info['customer_id'] > 0 && $order_info['chossentype'] == 3 ){
        $xtpl->parse('main.data_users');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}


/**

 * uers_order()

 *

 * @param mixed $data_content

 * @param mixed $data_order

 * @param mixed $total_coupons

 * @param mixed $error

 * @return

 */

function uers_order($data_content, $data_order, $order_info, $error, $act)
{
    global $module_info, $lang_module, $module_file, $module_name;

    $xtpl = new XTemplate('order.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);

    $price_total = 0;
    $stt = 1;
    if (!empty($data_content)) {
        foreach ($data_content as $data_row) {
            if( $act == 'retail'){
                $data_row['price_total'] = $data_row['price_retail'] * $data_row['order_number'];
                $data_row['sale_price_format'] = number_format($data_row['price_retail'], 0, '.', ',');
                $data_row['price_total_format'] = number_format($data_row['price_total'], 0, '.', ',');
            }elseif($act == 'wholesale'){
                $data_row['price_total'] = $data_row['price_wholesale'] * $data_row['order_number'];
                $data_row['sale_price_format'] = number_format($data_row['price_wholesale'], 0, '.', ',');
                $data_row['price_total_format'] = number_format($data_row['price_total'], 0, '.', ',');
            }
            $data_row['stt'] = $stt++;
            $xtpl->assign('DATA', $data_row);
            $xtpl->parse('main.rows');
            $price_total = $price_total + $data_row['price_total'];
        }
    }
    $xtpl->assign('price_total', $price_total);
    $xtpl->assign('price_total_fomart', number_format($price_total, 0, '.', ','));
    $xtpl->assign('DATA', $data_order);

    $xtpl->assign('ERROR', $error);

    if (!empty($order_info)) {

        $xtpl->assign('EDIT_ORDER', sprintf($lang_module['cart_edit_warning'], $order_info['order_url'], $order_info['order_code'], $order_info['order_edit']));

        $xtpl->parse('main.edit_order');

    }



    $xtpl->assign('LINK_CART', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart');



    $xtpl->parse('main');

    return $xtpl->text('main');

}





/**

 * payment()

 *

 * @param mixed $data_content

 * @param mixed $data_pro

 * @param mixed $url_checkout

 * @param mixed $intro_pay

 * @return

 */

function payment($data_content, $data_pro )
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $client_info, $array_discounts;

    if( $data_content['chossentype'] !=  3)
    {
        $lang_module['product_price'] = $lang_module['product_price_wholesale'];
    }

    $xtpl = new XTemplate('payment.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('dateup', date('d-m-Y', $data_content['order_time']));
    $xtpl->assign('moment', date("H:i' ", $data_content['order_time']));
    $xtpl->assign('DATA_ORDER', $data_content);
    $xtpl->assign('order_id', $data_content['order_id']);
    $xtpl->assign('cancel_url', $client_info['selfurl'] . '&cancel=1');
    $xtpl->assign('checkss', md5($client_info['session_id'] . $global_config['sitekey'] . $data_content['order_id']));

    $stt = 1;

    foreach ($data_pro as $data_row) {
        $data_row['stt'] = $stt++;
        $data_row['total_price'] = number_format( $data_row['total_price'], 0, '.', ',');
        $data_row['price_order'] = number_format( $data_row['price_order'], 0, '.', ',');
        $xtpl->assign('DATA', $data_row);
        $xtpl->parse('main.rows');
    }

    $total_price = round($data_content['order_total']);

    $xtpl->assign('price_total_no_discount', number_format( $total_price+$data_content['saleoff'],0 , '.', ',' ));
    if( !empty( $data_content['saleoff'] ) > 0){
        $xtpl->assign('SALEOFF', number_format( $data_content['saleoff'], 0, '.', ','));
        $xtpl->parse('main.saleoff');
    }

    $discount_price = 0;
    //chiet khau theo gia sp
    if (!empty( $array_discounts ) && $data_content['order_type'] == 0) {
        foreach ($array_discounts as $_d) {
            if ($_d['begin_price'] <= $data_content['order_total'] and $_d['end_price'] >= $data_content['order_total']) {
                $discount_percent = $_d['percent'];
                $discount_price = ($data_content['order_total'] * ($discount_percent / 100));
                break;
            }
        }
        $total_price = $data_content['order_total'] - $discount_price;
    }

    if( $discount_price > 0){
        $xtpl->assign('price_total_discount_fomart', number_format($discount_price,0 , '.', ',' ));
        $xtpl->assign('discount', $discount_percent);
        $xtpl->parse('main.discount');
    }

    $xtpl->assign('price_total_fomart', number_format($total_price, 0, '.', ','));
    $xtpl->assign('url_finsh', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
    $xtpl->assign('url_print', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print&order_id=' . $data_content['order_id'] . '&checkss=' . md5($data_content['order_id'] . $global_config['sitekey'] . session_id()));
    if( $discount_price > 0 ){
        $xtpl->parse('main.total_sale');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}





/**

 * print_pay()

 *

 * @param mixed $data_content

 * @param mixed $data_pro

 * @return

 */

function print_pay($data_content, $data_pro, $admin, $cty )
{
    global $module_info, $lang_module, $module_file, $global_config;
    if( $data_content['chossentype'] !=  3)
    {
        $lang_module['product_price'] = $lang_module['product_price_wholesale'];
    }
    $xtpl = new XTemplate('print.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('dateup', date('d-m-Y', $data_content['order_time']));
    $xtpl->assign('moment', date("H:i' ", $data_content['order_time']));
    $xtpl->assign('DATA_ORDER', $data_content);
    $xtpl->assign('logo_site', NV_BASE_SITEURL . $global_config['site_logo']);
    $xtpl->assign('order_id', $data_content['order_id']);
    $xtpl->assign('ngay_xuat_kho', sprintf( $lang_module['ngay_xuat_kho'], date('d', NV_CURRENTTIME), date('m', NV_CURRENTTIME) , date('Y', NV_CURRENTTIME)));
    if( $data_content['ordertype'] == 1 ){
        $xtpl->assign('INFO_TITLE', $lang_module['phieuxuatkho']);
    }
    elseif( $data_content['ordertype'] == 2 ){
        $xtpl->assign('INFO_TITLE', $lang_module['phieudatruocsanpham']);
    }else{
    $xtpl->assign('INFO_TITLE', $lang_module['phieutralaisanpham']);
}
    $i = 0;
    $stt = 1;
    if($admin == 1 ){
        $xtpl->parse('main.admin');
    }else{
        $xtpl->parse('main.noadmin');
    }
    foreach ($data_pro as $data_row) {
        $data_row['stt'] = $stt++;
        $data_row['total_price'] = number_format( $data_row['total_price'], 0, '.', ',');
        $data_row['price_order'] = number_format( $data_row['price_order'], 0, '.', ',');
        $xtpl->assign('DATA', $data_row);
        if($admin == 1 ){
            $xtpl->parse('main.rows.admin');
        }
        $xtpl->parse('main.rows');
    }
    $total_price = $data_content['order_total'];
    $xtpl->assign('price_total_no_discount', number_format($data_content['order_total'] + $data_content['saleoff'],0 , '.', ',' ));
    if( !empty( $data_content['saleoff'] ) > 0){
        $xtpl->assign('total_sale', number_format( $data_content['saleoff'], 0, '.', ','));
        $xtpl->parse('main.saleoff');
    }

    $discount_price = 0;
    //chiet khau theo gia sp
    if (!empty( $array_discounts ) && $data_content['order_type'] == 0) {
        foreach ($array_discounts as $_d) {
            if ($_d['begin_price'] <= $total_price and $_d['end_price'] >= $total_price) {
                $discount_percent = $_d['percent'];
                $discount_price = ($total_price * ($discount_percent / 100));
                break;
            }
        }
        $total_price = $total_price - $discount_price;
    }
    if( $discount_price > 0){
        $xtpl->assign('price_total_discount_fomart', number_format($discount_price,0 , '.', ',' ));
        $xtpl->assign('discount', $discount_percent);
        $xtpl->parse('main.discount');
    }
    if( $data_content['price_payment'] > 0) {
        $xtpl->assign('price_payment_fomart', number_format($data_content['price_payment'],0 , '.', ',' ));
        $xtpl->parse('main.price_payment');
        $total_price = $total_price - $data_content['price_payment'];
    }
    $xtpl->assign('price_total_fomart_text', ucfirst(convert_number_to_string( $total_price )));
    $xtpl->assign('price_total_fomart', number_format($total_price, 0, '.', ','));
    if( $cty == 1 ){
        $xtpl->parse('main.cty');
    }else{
        $xtpl->parse('main.nocty');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');

}


/**
 * cart_product()
 *
 * @param mixed $data_content
 * @param mixed $coupons_code
 * @param mixed $array_error_number
 * @return
 */

function cart_product_load($data_content, $agency_of_you, $chossentype, $ordertype, $customerid, $data_order, $show_quantity_warehouse )
{
    global $module_info, $lang_module, $module_file, $user_data_affiliate, $array_depot, $module_data, $db, $array_agency;

    if( $chossentype != 3){
        $lang_module['product_price'] = $lang_module['product_price_wholesale'];
    }

    $xtpl = new XTemplate('book-order.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('customerid', $customerid);
    $xtpl->assign('chossentype', $chossentype);
    $xtpl->assign('ordertype', $ordertype);
    $xtpl->assign('orderid', $data_order['order_id']);
    $xtpl->assign('customer_id', $data_order['customer_id']);
    $xtpl->assign('agency_info', $agency_of_you['agency_info']);

    $xtpl_parse = 'product';
    if( $user_data_affiliate['shareholder'] == 1 ){
        $xtpl_parse = 'shareholder';
    }

    if (!empty($data_content)) {

        if( $user_data_affiliate['possitonid'] > 0 ){
            foreach ( $array_depot as $depot ){
                $xtpl->assign('DEPOT', $depot);
                $xtpl->parse($xtpl_parse . '.list_depot.loop');
            }
            $xtpl->parse($xtpl_parse . '.list_depot');
        }

        $stt = 1;
        $total = 0;
        foreach ($data_content as $data_row) {
            $data_row['stt'] = $stt++;
            if( $chossentype == 3){
                //gia tuy bien
                if( $data_row['price_sale'] != -1){
                    $data_row['price_total'] = $data_row['price_sale'] * $data_row['cartnumber'];
                    $total = $total + $data_row['price_total'];
                    $data_row['price_retail'] = number_format($data_row['price_sale'], 0, '.', ',');
                    $data_row['price_total'] = number_format($data_row['price_total'], 0, '.', ',');
                }
                else{
                    $data_row['price_total'] = $data_row['price_retail'] * $data_row['cartnumber'];
                    $total = $total + $data_row['price_total'];
                    $data_row['price_retail'] = number_format($data_row['price_retail'], 0, '.', ',');
                    $data_row['price_total'] = number_format($data_row['price_total'], 0, '.', ',');
                }
            }else{
                if( $data_row['price_sale'] != -1){
                    $data_row['price_total'] = $data_row['price_sale'] * $data_row['cartnumber'];
                    $data_row['price_retail'] = $data_row['price_sale'];
                    $total = $total + $data_row['price_total'];
                    $data_row['price_retail'] = number_format($data_row['price_retail'], 0, '.', ',');
                    $data_row['price_total'] = number_format($data_row['price_total'], 0, '.', ',');
                }
                else{
                    if( $data_order['chossentype'] == 3 ){
                        //don khach le
                        $data_row['price_total'] = $data_row['price_retail'] * $data_row['cartnumber'];
                    }else{
                        $data_row['price_total'] = nv_get_price_for_agency( $data_row['price_retail'], $data_row['id'], $data_row['cartnumber'], true );
                        $data_row['price_retail'] = nv_get_price_for_agency( $data_row['price_retail'], $data_row['id'], $data_row['cartnumber'] );
                    }

                    $total = $total + $data_row['price_total'];
                    $data_row['price_retail'] = number_format($data_row['price_retail'], 0, '.', ',');
                    $data_row['price_total'] = number_format($data_row['price_total'], 0, '.', ',');
                }
            }
            //print_r($data_row);die;
            $xtpl->assign('DATA', $data_row);
            if( $show_quantity_warehouse ){
                $xtpl->parse($xtpl_parse . '.rows.number_warehouse');
            }
            if( $user_data_affiliate['shareholder'] == 1 ){
                $xtpl->parse($xtpl_parse . '.rows.quantity_com');
            }
            if( $data_row['priceshow'] == 1 ){
                $xtpl->parse($xtpl_parse . '.rows.priceshow');
            }else{
                $xtpl->parse($xtpl_parse . '.rows.nopriceshow');
            }
            $xtpl->parse($xtpl_parse . '.rows');
        }
        if( $show_quantity_warehouse ){
            $xtpl->parse($xtpl_parse . '.number_warehouse');
        }
        if( $user_data_affiliate['shareholder'] == 1 ){
            $xtpl->parse($xtpl_parse . '.quantity_com');
        }

        $price_total_discount = 0;
        //neu la dat hang moi co chiet khau.
        if( $ordertype == 1 ){
            //chiết khấu the0 % của mức đại lý đang hưởng
            //Neu theo % thi thoi theo muc tien nhap
            if( $agency_of_you['percent_discount'] > 0 ){
                $price_total_discount = floor($total / 100) * $agency_of_you['percent_discount'];
            }else if( $agency_of_you['price_for_discount'] > 0 and $agency_of_you['price_discount'] >0  ){
            // dat truoc thi khong ( chiết khấu theo số tiền nhập biến price_for_discount)
                $price_total_discount = floor($total / $agency_of_you['price_for_discount']) * $agency_of_you['price_discount'];
            }
        }

        $xtpl->assign('price_total', $total );
        $xtpl->assign('price_total_fomart', number_format($total,0 , '.', ',' ));
        $xtpl->assign('price_total_discount_fomart', number_format($price_total_discount,0 , '.', ',' ));

        $total = $total - $price_total_discount;

        if( $ordertype == 1 ){
            if( !empty( $data_order ) && $data_order['price_payment'] > 0 ){
                $xtpl->assign('price_payment', number_format($data_order['price_payment'],0 , '.', ',' ));
                $xtpl->parse($xtpl_parse . '.ordertype_' . $ordertype . '.price_payment');
            }
            $total = $total - $data_order['price_payment'];
            $xtpl->assign('price_total_end_fomart', number_format($total,0 , '.', ',' ));
            $xtpl->parse($xtpl_parse . '.ordertype_' . $ordertype );
        }elseif( $ordertype == 2 ){
            //neu la don hang dat truoc can tien tam ung la NV_DEFINE_DEPOSITS
            if( !isset( $data_order['price_payment'] ) || $data_order['price_payment'] == 0){
             $price_total_discount = ($total /100) * NV_DEFINE_DEPOSITS;   
            }else{
                //gia khi da submit
                $price_total_discount = $data_order['price_payment'];
            }
            $xtpl->assign('price_total_discount_fomart', number_format($price_total_discount,0 , '.', ',' ));
            $xtpl->parse($xtpl_parse . '.ordertype_' . $ordertype );
        }

    }else{
        return '';
    }
    $xtpl->parse($xtpl_parse );
    return $xtpl->text($xtpl_parse );
}


function return_orders($order_info, $error )
{
    global $module_info, $lang_module, $module_file, $op;


    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('OP', $op);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

    $xtpl->assign('order_shipcod', $order_info['order_shipcod'] == 1 ? ' checked=checked' : '');

    if( !empty( $error )){
        $xtpl->assign('ERROR', implode($error, '<br/>'));
        $xtpl->parse('main.error');
    }

    $xtpl->assign('DATA', $order_info);

    if( $order_info['customer_id'] > 0 && $order_info['chossentype'] == 3 ){
        $xtpl->parse('main.data_users');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');

}



/**

 * order_return_product_load()

 *

 * @param mixed $data_content

 * @param mixed $coupons_code

 * @param mixed $array_error_number

 * @return

 */

function order_return_product_load($array_orders_id, $data_order, $data_order_current, $agency_of_you, $array_gift_new )
{
    global $module_info, $lang_module, $module_file, $array_select_type_return;


    $xtpl = new XTemplate('return-order.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('DATA_CUSTOMER', $data_order );

    if (!empty($array_orders_id)) {
        $stt = 1;
        $rebook_total = $total = 0;
        foreach ($array_orders_id as $data_row) {

            $data_row['stt'] = $stt++;
            $data_row['price_format'] = number_format($data_row['price'],0 , '.', ',' );
            $data_row['price_total_format'] = number_format($data_row['price_total'],0 , '.', ',' );
            $data_row['rebook_price_format'] = number_format($data_row['rebook_price'],0 , '.', ',' );
            $data_row['rebook_total_price_format'] = number_format($data_row['rebook_total_price'],0 , '.', ',' );
            if( $data_row['isgift'] == 1 ){

                $data_row['rebook_number'] = $array_gift_new[$data_row['proid']];
                $data_row['num_return'] = $data_row['num'] - $array_gift_new[$data_row['proid']];
            }
            $xtpl->assign('DATA', $data_row);
            if( !empty( $data_row['message'] )){
                $xtpl->parse('product.rows.message');
            }
            if( $data_row['isgift'] == 1){
                $xtpl->assign('DISABLE', ' disabled=disabled');
            }else{
                $xtpl->assign('DISABLE', '');
            }
            foreach ( $array_select_type_return as $key => $val ){
                $sl = ( $key == $data_row['type_return'] )? ' selected=selected' : '';
                $xtpl->assign('NOTE_RETURN', array('value' => $key, 'title' => $val, 'sl' => $sl ));
                $xtpl->parse('product.rows.type_return');
            }
            $total+= $data_row['price_total'];
            $rebook_total+= $data_row['rebook_total_price'];
           $xtpl->parse('product.rows');
        }

        $price_total_discount = 0;
        if( $agency_of_you['price_for_discount'] > 0 and $agency_of_you['price_discount'] >0 ){
            $price_total_discount = floor($rebook_total / $agency_of_you['price_for_discount']) * $agency_of_you['price_discount'];
        }

        $xtpl->assign('price_total', $total );
        $xtpl->assign('total_price_return', number_format($total,0 , '.', ',' ));

        $xtpl->assign('rebook_total', $rebook_total );
        $xtpl->assign('rebook_total_format', number_format($rebook_total,0 , '.', ',' ));

        $xtpl->assign('price_total_discount_fomart', number_format($price_total_discount,0 , '.', ',' ));

        $rebook_total_after_discount = $rebook_total - $price_total_discount;

        if( !empty( $data_order_current )){
            $price_total_end = $data_order_current['amount_refunded'] - ($rebook_total_after_discount + $total);
            $xtpl->assign('total_order_old_format', number_format($data_order_current['amount_refunded'],0 , '.', ',' ));
        }else{
            $price_total_end = $data_order['amount_refunded'] - ($rebook_total_after_discount + $total);
            $xtpl->assign('total_order_old_format', number_format($data_order['amount_refunded'],0 , '.', ',' ));
        }

        $xtpl->assign('rebook_total_after_discount_format', number_format($rebook_total_after_discount,0 , '.', ',' ));

        $xtpl->assign('price_total_end_fomart', number_format($price_total_end,0 , '.', ',' ));


    }else{
        return '';
    }

    $xtpl->parse('product');
    return $xtpl->text('product');
}


function detail_order_return_view($array_orders_id, $data_order, $data, $agency_of_you, $array_transaction )
{
    global $module_info, $lang_module, $module_file, $array_select_type_return, $op, $module_name, $global_config;


    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $data['order_time'] = date('H:i, d/m/Y', $data['order_time'] );

    $xtpl->assign('LINK_PRINT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=print&order_id=' . $data['order_id'] . '&checkss=' . md5($data['order_id'] . $global_config['sitekey'] . session_id()));
    $xtpl->assign('URL_ACTIVE_PAY', NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&active_pay=1&order_id=' . $data['order_id'] );
    $xtpl->assign('URL_BACK', NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=or_view&order_id=' . $data['order_id']);

    if( $data['status'] != 0 ){
        $payment_amount = $data['order_total'] - $data['price_payment'];
    }elseif( $data['status'] == 0 ){
        $payment_amount = $data['order_total'];
    }

    if ($data['status'] == 4) {
        $html_payment = $lang_module['history_payment_yes'];
    } elseif ($data['status'] == 5) {
        $html_payment = $lang_module['history_order_ships'];
    }  elseif ($data['status'] == 3) {
        $html_payment = $lang_module['history_payment_cancel'];
    } elseif ($data['status'] == 2) {
        $html_payment = $lang_module['history_payment_check'];
    } elseif ($data['status'] == 1) {
        $html_payment = $lang_module['history_payment_send'];
    } elseif ($data['status'] == 0) {
        $html_payment = $lang_module['history_payment_no'];
    } elseif ($data['status'] == - 1) {
        $html_payment = $lang_module['history_payment_wait'];
    } else {
        $html_payment = 'ERROR';
    }
    $xtpl->assign('payment', $html_payment);
    $xtpl->assign('payment_amount', number_format($payment_amount, 0, '.', ','));

    $xtpl->assign('DATA_CUSTOMER', $data );

    if (!empty($array_orders_id)) {
        $stt = 1;
        $rebook_total = $total = 0;
        //print_r($array_orders_id);die;
        foreach ($array_orders_id as $data_row) {
            if( isset( $data_row['num_return'] ) ){
                $data_row['stt'] = $stt++;
                $data_row['price_format'] = number_format($data_row['price'],0 , '.', ',' );
                $data_row['price_total_format'] = number_format($data_row['price_total'],0 , '.', ',' );
                $data_row['rebook_price_format'] = number_format($data_row['rebook_price'],0 , '.', ',' );
                $data_row['rebook_total_price_format'] = number_format($data_row['rebook_total_price'],0 , '.', ',' );
                $data_row['type_return'] = $array_select_type_return[$data_row['type_return']];
                $xtpl->assign('DATA', $data_row);
                if( !empty( $data_row['message'] )){
                    $xtpl->parse('main.rows.message');
                }

                $total+= $data_row['price_total'];
                $rebook_total+= $data_row['rebook_total_price'];
                $xtpl->parse('main.rows');
            }
        }

        $price_total_discount = 0;
        if( $agency_of_you['price_for_discount'] > 0 and $agency_of_you['price_discount'] >0 ){
            $price_total_discount = floor($rebook_total / $agency_of_you['price_for_discount']) * $agency_of_you['price_discount'];
        }

        $xtpl->assign('total_order_old_format', number_format($data_order['order_total'],0 , '.', ',' ));

        $xtpl->assign('price_total', $total );
        $xtpl->assign('total_price_return', number_format($total,0 , '.', ',' ));

        $xtpl->assign('rebook_total', $rebook_total );
        $xtpl->assign('rebook_total_format', number_format($rebook_total,0 , '.', ',' ));

        $xtpl->assign('price_total_discount_fomart', number_format($price_total_discount,0 , '.', ',' ));

        $rebook_total_after_discount = $rebook_total - $price_total_discount;

        $xtpl->assign('rebook_total_after_discount_format', number_format($rebook_total_after_discount,0 , '.', ',' ));
        $price_total_end = $data_order['order_total'] - ($rebook_total_after_discount + $total);
        $xtpl->assign('price_total_end_fomart', number_format($price_total_end,0 , '.', ',' ));

        $congno = $data['order_total'] - $data['price_payment'];
        $xtpl->assign('congno', number_format( $congno, 0, '.', ','));
        
        if ( ($data['status'] < 4 || $data['price_payment'] < $data['order_total'])) {
            $xtpl->parse('main.onpay');
        }
        if( !empty( $array_transaction )){
            foreach ( $array_transaction as $transaction ) {
                $xtpl->assign('DATA_TRANS', $transaction);
                $xtpl->parse('main.transaction.loop');
            }
            $xtpl->parse('main.transaction');
        }
    }else{
        return '';
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}