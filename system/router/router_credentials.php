<?php
	$settings = parse_ini_file(PATH_ROUTER . 'router.ini');
	
	$product_name = $settings['product_name'];
	$product_url = $settings['product_url'];
	$product_owner = $settings['product_owner'];
	$product_version = $settings['product_version'];

	$developer_name = $settings['developer_name'];
	$developer_url = $settings['developer_url'];
	$developer_email = $settings['developer_email'];
	$developer_skype = $settings['developer_skype'];

	ob_start();
?>

<div class="content">
	<b><?php echo $product_name; ?> v.<?php echo $product_version; ?></b>
	<br/>
	copyright &copy; <a href="<?php echo $product_url; ?>"><?php echo $product_owner; ?></a>
	<br/>
	developer <a href="<?php echo $developer_url; ?>"><?php echo $developer_name; ?></a>
	<br/>
	email: <a href="mailto: <?php echo $developer_email; ?>"><?php echo $developer_email; ?></a>
	<br/>
	skype: <a href="skype:<?php echo $developer_skype; ?>?chat"><?php echo $developer_skype; ?></a>
</div>

<?php
	$content = ob_get_clean();
	return $content;
?>