<?php
function sanitize_title_cstm($string)
{
    $url = $string;
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url); // substitutes anything but letters, numbers and '_' with separator
    $url = trim($url, "-");
    $url = iconv("utf-8", "us-ascii//TRANSLIT", $url); // TRANSLIT does the whole job
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url); // keep only letters, numbers, '_' and separator
    return $url;
}


if (isset($_POST['calid'])) {
    $purucid = $_POST['calid'];

    $getInfo = $ob->get_recs("customer", "*, (select option_name from `option_tbl` op where op.id = customer.wire_type ) as wire_type, (select option_name from `option_tbl` op where op.id = customer.wire_insulation ) as wire_insulation, (select option_name from `option_tbl` op where op.id = customer.wire_guage ) as wire_guage, (select option_name from `option_tbl` op where op.id = customer.junction_type ) as junction_type, (select option_name from `option_tbl` op where op.id = customer.terminals ) as terminals", "cid=$purucid");
    if ($getInfo):

        foreach ($getInfo as $info):
            $prtnum = $info->part_number;
            $shrprt = $info->part_num_temp;
            break;

        endforeach;

    endif;
    // $info = $getInfo = '';
    // $getInfo = $ob->get_recs("customer", "*, (select option_name from `option_tbl` op where op.id = customer.wire_type ) as wire_type, (select option_name from `option_tbl` op where op.id = customer.wire_insulation ) as wire_insulation, (select option_name from `option_tbl` op where op.id = customer.wire_guage ) as wire_guage, (select option_name from `option_tbl` op where op.id = customer.junction_type ) as junction_type, (select option_name from `option_tbl` op where op.id = customer.terminals ) as terminals", "part_number='$prtnum' limit 1");

    if ($getInfo):

        foreach ($getInfo as $info):


            $wire_type = $info->wire_type;

            $wire_insulation = $info->wire_insulation;

            $wire_guage = $info->wire_guage;

            $junction_type = $info->junction_type;

            $terminals = $info->terminals;



            $a_dimension = $info->a_dimension;



            $cost = $info->cost;
            $cost = ($cost - ($cost * 0.10));
            $cost1 = sprintf('%.2f', $cost);
            $lgprt = $info->part_number;

            $prt = $info->part_num_temp;

            $ccid = $info->cid;

            break;

        endforeach;

    endif;

    if ($shrprt == $prt) {
        mysqli_query($obLink->dblink(), "update `customer` set `part_num_short`='$shrprt' where cid=$purucid");
    }

    $pcontant = "<ul>"
            . "<li>WIRE TYPE: $wire_type</li>"
            . "<li>WIRE INSULATION: $wire_insulation</li>"
            . "<li>WIRE GAUGE: $wire_guage</li>"
            . "<li>A DIMENSION: $a_dimension</li>"
            . "<li>JUNCTION TYPE: $junction_type</li>"
            . "<li>TERMINALS: $terminals</li>";

    mysqli_close($link);

    $links = mysqli_connect('localhost', 'blazest_blazest', '&1Zegodnoo02xYGm');
    mysqli_select_db($links, 'blazestag_blazestag') or die('Unable to select database, Please Check Your Connection..');
    // $oldrecord = mysql_query("select ID from  ply_posts where post_name = '" . $lgprt . "' limit 1");
	$ttl = sanitize_title_cstm($lgprt);
	$oldrecord = mysqli_query($links, "select ID from  ply_posts where post_name = '".$ttl."' limit 1");

    if (mysqli_num_rows($oldrecord)) {
        $oldrg = mysqli_fetch_array($oldrecord);
        $product_id = $oldrg['ID'];
        mysqli_close($links);
        $obLink->dblink();
        echo $product_id;
    } else {
/// added by me 28-jan //
mysqli_close($links);
include_once("../wp-config.php");
$postarr = array(
  'post_content'   => $pcontant,
  'post_name'      => $lgprt,
  'post_title'     => 'Custom-Flex-Probe-'.$prt,
  'post_status'    => 'publish',
  'post_type'      => 'product',
  'post_author'    => '4',
  'ping_status'    => 'closed',
  'post_parent'    => '',
  'menu_order'     => '0',
  'to_ping'        => '',
  'pinged'         => '',
  'post_password'  => '',
  'post_excerpt'   => $pcontant,
  'post_date'      => date('Y-m-d H:i:s'),
  'post_date_gmt'  => date('Y-m-d H:i:s'),
  'comment_status' => 'closed',
  'post_category'  => '',
  'tags_input'     => '',
  'tax_input'      => ''
);
$product_id=wp_insert_post( $postarr, false );
global $wpdb;
$wpdb->close();
$links = mysqli_connect('localhost', 'blazest_blazest', '&1Zegodnoo02xYGm');
mysqli_select_db($links, 'blazestag_blazestag') or die('Unable to select database, Please Check Your Connection..');
/// added by me 28-jan //
        /*mysql_query("INSERT INTO ply_posts SET 
						post_author             = '4',
						post_content                = '" . $pcontant . "',
						post_title               = 'Custom-Flex-Probe-" . $prt . "',
						post_excerpt               = '" . $pcontant . "',
						comment_status               = 'closed',
						ping_status         = 'closed',
						menu_order    = '1',
						post_name    = '" . $lgprt . "',
						to_ping  = ' ',
						pinged           = ' ',
						guid = 'https://blazeprobes.com/build-a-temperature-flex-wire-probe/img/flex-home.jpg',
						post_content_filtered            = ' ',
						post_type       = 'product'") or die(mysql_error());

        $product_id = mysql_insert_id();*/

        mysqli_query($links, "INSERT INTO ply_posts SET 
						post_author             = '4',
						post_content                = '',
						post_title               = 'Custom-Flex-Probe-" . $prt . "',
						post_status  =  'inherit',
						post_excerpt               = '',
						comment_status               = 'open',
						ping_status         = 'closed',
						post_name    = 'Custom-Flex-Probe-" . $prt . "',
						to_ping  = ' ',
						pinged           = ' ',
						post_parent = '" . $product_id . "',
						guid = 'https://blazeprobes.com/build-a-temperature-flex-wire-probe/img/flex-home.jpg',
						post_content_filtered            = ' ',
						post_mime_type = 'image/jpeg',
						post_type       = 'attachment'") or die(mysqli_error($links));

        $img_id = mysqli_insert_id($links);

        $que = " INSERT INTO ply_postmeta (post_id, meta_key, meta_value) VALUES "
                . " ($product_id,'_stock_status','instock'), "
                . "($product_id,'total_sales','0'),"
                . "($product_id,'_downloadable','no'),"
                . "($product_id,'_virtual','no'),"
                . "($product_id,'_purchase_note',''),"
                . "($product_id,'_featured','no'),"
                . "($product_id,'_weight','8'),"
                . "($product_id,'_length',''),"
                . "($product_id,'_width',''),"
                . "($product_id,'_height',''),"
                . "($product_id,'_sku',''),"
                . "($product_id,'_product_attributes','a:0:{}'),"
                . "($product_id,'_regular_price','$cost1'),"
                . "($product_id,'_sale_price',''),"
                . "($product_id,'_sale_price_dates_from',''),"
                . "($product_id,'_sale_price_dates_to',''),"
                . "($product_id,'_price','$cost1'),"
                . "($product_id,'_sold_individually',''),"
                . "($product_id,'_manage_stock','no'),"
                . "($product_id,'_backorders','no'),"
                . "($product_id,'_stock',''),"
                . "($product_id,'_upsell_ids','a:0:{}'),"
                . "($product_id,'_crosssell_ids','a:0:{}'),"
                . "($product_id,'_product_image_gallery','$img_id'),"
                . "($product_id,'_wc_rating_count','a:0:{}'),"
                . "($product_id,'_wc_review_count','0'),"
                . "($product_id,'_wc_average_rating',''),"
                . "($product_id,'_thumbnail_id','$img_id');";

        mysqli_query($links, $que) or die(mysqli_error($links));


        mysqli_close($links);
        $obLink->dblink();
        echo $product_id;
    }
}