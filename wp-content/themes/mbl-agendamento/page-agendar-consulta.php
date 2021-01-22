<?php 
get_header();
WC()->cart->empty_cart();

$nome = $_GET["nome"];
$email = $_GET["email"];
$tel = $_GET["tel"];

$woocommerce->session->set( 'booked_first_name', $nome );

?>
<style type="text/css" media="screen">
.calendario{
	padding: 30px;
    max-width: 900px;
    margin: 0 auto;
}
.intro{
	padding: 30px 30px 0 30px;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
}
.intro p{
	font-size: 18px;
}
</style>
<div class="intro">
	<h2>Oi<?php echo " " . $nome ?>, falta pouco para você agendar sua consulta.</h2>
	<p>Escolha uma data e horário e agende sua consulta com um de nossos advogados.</p>
</div>
<div class="calendario">
<?php echo do_shortcode('[booked-calendar]'); ?>
</div>
<?php 
get_footer();
?>