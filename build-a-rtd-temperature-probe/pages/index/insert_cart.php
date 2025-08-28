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


if(isset($_REQUEST['calid'])){
	$ob= new database();
    $purucid=decrypt_str($_REQUEST['calid']);
	
	$getInfo = $ob->get_recs("customer","*","cid=$purucid");
	if($getInfo):

		foreach($getInfo as $info):
			$prtnum=$info->part_number;
			$shrprt = $info->part_num_temp;
			break;

		endforeach;

	endif;

	$info=$getInfo='';

	$getInfo = $ob->get_recs("customer","*, (select option_name from `option_tbl` op where op.id = customer.element ) as element, (select option_name from `option_tbl` op where op.id = customer.rtd_styles ) as rtd_styles, (select option_name from `option_tbl` op where op.id = customer.hardwareextension_cable_insulation ) as hardwareextension_cable_insulation, (select option_name from `option_tbl` op where op.id = customer.sensor_od ) as sensor_od, (select option_name from `option_tbl` op where op.id = customer.wire_leads ) as wire_leads,(select option_name from `option_tbl` op where op.id = customer.connection ) as connection","part_number='$prtnum' limit 1");

	if($getInfo):
		foreach($getInfo as $info):
			$sensor_type = $info->element;
			$connecting_heads = $info->rtd_styles;
			$hardware = $info->hardwareextension_cable_insulation;
			$sensor_od = $info->sensor_od;
			$elements = $info->wire_leads;
			$junction_type = $info->connection;
			$a_dim = $info->a_dim;
			$b_dim = $info->b_dim;
			$cost = $info->cost;
			$cost1 = sprintf('%.2f', $cost);			
			$prt = $info->part_num_temp;
			$lgprt = $info->part_number;
			$ccid = $info->cid;			
			break;
		endforeach;
	endif;

	if($shrprt==$prt){
		mysqli_query($obLink->dblink(), "update `customer` set `part_num_short`='$shrprt' where cid=$purucid");
	}

	$pcontant= "<ul>"
	. "<li>ELEMENT: $sensor_type</li>"
	. "<li>RTD STYLES: $connecting_heads</li>"
	. "<li>WIRE LEADS: $elements</li>"
	. "<li>SENSOR O.D: $sensor_od</li>"
	. "<li>A DIMENSION: $a_dim</li>"
	. "<li>EXTENSION CABLE INSULATION: $hardware</li>"
	. "<li>B DIMENSION: $b_dim</li>"
	. "<li>CONNECTION: $junction_type</li>";	
	
	mysqli_close($obLink->dblink());

	// connect to woocommerce DB
	$links = mysqli_connect("localhost", "blazepro_conf_usr", "w60POS5j!@");
	mysqli_select_db($links, 'blaze_11252024') or die('Unable to select database, Please Check Your Connection..');

	// $oldrecord = mysql_query("select ID from  ply_posts where post_name = '".$lgprt."' limit 1");
	$ttl = sanitize_title_cstm($lgprt);
	$oldrecord = mysqli_query($links, "select ID,post_title from  ply_posts where post_name = '".$ttl."' AND post_type = 'product' limit 1");
	

	if(mysqli_num_rows($oldrecord) && $ttl != "") {
		$oldrg=  mysqli_fetch_array($oldrecord);
		$product_id=$oldrg['ID']; 
        // $product_ttl = $oldrg['post_title'];
		$product_ttl = str_replace("Custom-RTD-Probe-","",$oldrg['post_title']);
		
		mysqli_close($links);
		$obLink->dblink();
		if($_REQUEST['returntype'] == "both")
		{
			echo $product_id."{|}".$product_ttl;
		}
		else
		{
			echo $product_id;
		}
	}
	else
	{
	/// added by me 28-jan //
	mysqli_close($links);
	include_once("../wp-config.php");
	$postarr = array(
	  'post_content'   => $pcontant,
	  'post_name'      => $lgprt,
	  'post_title'     => 'Custom-RTD-Probe-'.$prt,
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
	if( empty( get_post_meta( $product_id, '_sku', true ) ) ) {
		update_post_meta( $product_id, '_sku', $prt );
	}
	global $wpdb;
	$wpdb->close();
	// connect to woocommerce DB
	$links = mysqli_connect("localhost", "blazepro_conf_usr", "w60POS5j!@");
	mysqli_select_db($links, 'blaze_11252024') or die('Unable to select database, Please Check Your Connection..');

		/*mysql_query("INSERT INTO ply_posts SET 
						post_author             = '4',
						post_content                = '".$pcontant."',
						post_title               = 'Custom-RTD-Probe-".$prt."',
						post_excerpt               = '".$pcontant."',
						comment_status               = 'closed',
						ping_status         = 'closed',
						menu_order    = '1',
						post_name    = '".$lgprt."',
						to_ping  = ' ',
						pinged           = ' ',
						guid = 'http://blazeprobes.com/build-a-rtd-temperature-probe/img/RTD-MAIN.jpg',
						post_content_filtered            = ' ',
						post_type       = 'product'") or die(mysql_error());

						$product_id=mysql_insert_id();*/

		mysqli_query($links, "INSERT INTO ply_posts SET 
						post_author             = '4',
						post_content                = '',
						post_title               = 'Custom-RTD-Probe-".$prt."',
						post_status  =  'inherit',
						post_excerpt               = '',
						comment_status               = 'open',
						ping_status         = 'closed',
						post_name    = 'Custom-".$prt."',
						to_ping  = ' ',
						pinged           = ' ',
						post_parent = '".$product_id."',
						guid = 'http://blazeprobes.com/build-a-rtd-temperature-probe/img/RTD-MAIN.jpg',
						post_content_filtered            = ' ',
						post_mime_type = 'image/jpeg',
						post_type       = 'attachment'") or die(mysqli_error($links));						        

		$img_id=mysqli_insert_id($links);	

		$que=" INSERT INTO ply_postmeta (post_id, meta_key, meta_value) VALUES "
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
		if($_REQUEST['returntype'] == "both")
		{
			// echo $product_id."{|}Custom-RTD-Probe-".$prt;
			echo $product_id."{|}".$prt;
		}
		else
		{
			echo $product_id;
		}
	}
}