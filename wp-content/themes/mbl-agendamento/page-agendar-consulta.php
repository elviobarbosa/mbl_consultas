<?php 
get_header();
//limpa o carrinho para evitar adicionar mais de um horário
WC()->cart->empty_cart();

//parametros vindos do Chat
$nome_completo = ( isset($_GET["nome"]) ) ? strip_tags($_GET["nome"]) : "";
$email = ( isset($_GET["email"]) ) ? strip_tags( $_GET["email"] ) : "";
$tel = (isset($_GET["tel"])) ? strip_tags($_GET["tel"]) : "";


//Separa nome e sobrenome
$nomes = explode(" ", $nome_completo);
$sobrenome = "";

for ($i=1; $i<count($nomes); $i++):
	$sobrenome .= $nomes[$i] . " ";
endfor;

$woocommerce->session->set( 'billing_phone', $tel);

user_reminders();
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
	<h2>Oi<?php echo " " . $nomes[0] ?>, falta pouco para você agendar sua consulta.</h2>
	<p>Escolha uma data e horário e agende sua consulta com um de nossos advogados.</p>
</div>
<div class="calendario">
<?php echo do_shortcode('[booked-calendar]'); ?>
</div>
<?php 
get_footer();
?>
<script type="text/javascript">
	//verifica quando o modal é acionado
	var hasmodal = window.setInterval(function(){
		if ($('.booked-modal').length > 0 ){
			setTimeout(function()
			{
				//insere os valores dos parametros nos campos
				$('input[name=guest_name]').val('<?php echo $nomes[0] ?>');
				$('input[name=guest_surname]').val('<?php echo $sobrenome ?>');
				$('input[name=guest_email]').val('<?php echo $email ?>');
				
				clearInterval(hasmodal);
			}, 600);
			
		}
	}, 300);

</script>