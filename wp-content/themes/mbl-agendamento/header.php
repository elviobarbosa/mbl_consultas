<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


<?php wp_head(); //user_reminders();?>
</head>

<body <?php body_class(); ?>>

	

<?php wp_body_open();?>

<div>
	
	<header class="main-header" role="banner" >
		<h1>Magalhães Bezerra Lima Advogados</h1>
		<nav>
			<ul>
				<!--li class="agenda"><i></i><a href="#">Agende uma Consulta</a></li-->
				<!--li class="conta"><i></i><a href="#">Minhas Consultas</a></li-->
			</ul>
		</nav>

	</header><!-- #masthead -->

	

	<div id="content" class="site-content" tabindex="-1">
		<div class="">

		<?php
		do_action( 'storefront_content_top' );
		
