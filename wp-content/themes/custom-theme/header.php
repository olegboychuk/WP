<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
        <title>
			<?php is_front_page() ? bloginfo('description') : wp_title(''); ?>
		</title>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_head(); ?>
	</head>
    <body <?php body_class( isset($class) ? $class : '' ); ?>>
    <?php $logo = get_field('header_logo','options');?>
