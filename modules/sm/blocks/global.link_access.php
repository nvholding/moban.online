<?php



/**

 * @Project NUKEVIET 4.x

 * @Author VINADES.,JSC <contact@vinades.vn>

 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved

 * @License GNU/GPL version 2 or any later version

 * @Createdate 3/25/2010 18:6

 */



if (!defined('NV_MAINFILE')) {

    die('Stop!!!');

}

if (!nv_function_exists('nv_link_access')) {

    /**
     * nv_link_access()
     *
     * @param mixed $block_config
     * @return
     */

    function nv_link_access($block_config)
    {
        global $db_config, $db, $global_config, $user_info;

        $module = $block_config['module'];
        if (!defined('NV_IS_USER')) {
            return '';
        }
        $sql = 'SELECT t1.code, t1.possitonid, t1.agencyid, t1.salary_day, t1.benefit, t1.subcatid, t1.numsubcat, t1.datatext, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_affiliate_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t2.userid=' . $user_info['userid'] . ' ORDER BY t1.sort ASC';
        $user_data_affiliate = $db->query($sql)->fetch();
        //chua phai trong he thong thi chueyn huong
        if( empty( $user_data_affiliate ))
        {
           // Header('Location: ' . NV_BASE_SITEURL);
         //   die();
        }
        $user_data_affiliate['fullname'] = nv_show_name_user( $user_data_affiliate['first_name'], $user_data_affiliate['last_name'], $user_data_affiliate['username']);
        $user_data_affiliate['datatext'] = unserialize( $user_data_affiliate['datatext']);

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/sm/block.link_acess.tpl')) {

            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/sm/block.link_acess.tpl')) {

            $block_theme = $global_config['site_theme'];
        } else {

            $block_theme = 'default';
        }

        $xtpl = new XTemplate('block.link_acess.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/sm');

        $array_control = array();
        $link_module = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=sm&' . NV_OP_VARIABLE . '=';
        $link_affiliate = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=affiliate&' . NV_OP_VARIABLE . '=';
        $link_users = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=';

        $array_control_1 = $array_control_2 = $array_control_3 = array();
        $array_control_1[] = array('link' => $link_module . 'book-order', 'title' => 'Đặt hàng', 'icon' => 'fa-shopping-cart');
        $array_control_1[] = array('link' => $link_module . 'return-order', 'title' => 'Trả hàng', 'icon' => 'fa-cart-arrow-down');
        //$array_control_1[] = array('link' => $link_module . 'tranfer', 'title' => 'Luân chuyển hàng', 'icon' => 'fa-cart-arrow-down');

        if( $user_data_affiliate['numsubcat'] > 0 ){
            $array_control_2[] = array('link' => $link_affiliate . 'maps', 'title' => 'Sơ đồ hệ thống', 'icon' => 'fa-address-book');
        }
        $array_control_2[] = array('link' => $link_affiliate . 'register', 'title' => 'Tạo tài khoản NPP, ĐL', 'icon' => 'fa-user-plus');
        $array_control_2[] = array('link' => $link_users . 'editinfo/password', 'title' => 'Thay đổi thông tin đăng nhập', 'icon' => 'fa-edit');

        if( $user_data_affiliate['possitonid'] > 0 ){
            //$array_control[] = array('link' => $link_affiliate . 'main', 'title' => 'Giao dịch tự động');
            $array_control_3[] = array('link' => $link_module . 'doanhso', 'title' => 'Doanh số của bạn', 'icon' => 'fa-dot-circle-o');
            //$array_control[] = array('link' => $link_module . 'warehouse_logs', 'title' => 'Thống kê xuất/nhập hàng');
        }else{
            $array_control_3[] = array('link' => $link_module . 'order', 'title' => 'Quản lý đơn hàng', 'icon' => 'fa-shopping-basket');
            $array_control_3[] = array('link' => $link_module . 'warehouse_logs', 'title' => 'Thống kê xuất/nhập hàng', 'icon' => 'fa-bar-chart');
            $array_control_3[] = array('link' => $link_module . 'importplan', 'title' => 'Kế hoạch nhập hàng', 'icon' => 'fa-calendar');
        }

        if( !empty( $array_control_1 )){
            foreach ($array_control_1 as $control){
                $control['class'] = ' btn-success';
                $xtpl->assign('CONTROL', $control);
                $xtpl->parse('main.control1.loop');
            }
            $xtpl->parse('main.control1');
        }
        if( !empty( $array_control_2 )){
            foreach ($array_control_2 as $control){
                $control['class'] = ' btn-success';
                $xtpl->assign('CONTROL', $control);
                $xtpl->parse('main.control2.loop');
            }
            $xtpl->parse('main.control2');
        }
        if( !empty( $array_control_3 )){
            foreach ($array_control_3 as $control){
                $control['class'] = ' btn-success';
                $xtpl->assign('CONTROL', $control);
                $xtpl->parse('main.control3.loop');
            }
            $xtpl->parse('main.control3');
        }
        $xtpl->assign('LOGOUT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=logout');
        $xtpl->parse('main');
        return $xtpl->text('main');

    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_link_access($block_config);
}

