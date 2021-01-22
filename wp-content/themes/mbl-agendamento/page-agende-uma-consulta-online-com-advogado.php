
<html lang="pt-BR">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name=viewport content="width=device-width">
<meta charset="UTF-8">

<title>Agende uma Consulta Online com Advogados da Magalhães Bezerra Lima</title>

<style type="text/css" media="screen">

	body{
		padding: 0;
        margin: 0;
    }
    .bot{
		width: 100%;
		height: calc(100vh - 80px);
    }
    .topo{
    	background: white;
	    padding: 10px;
	    text-align: center;
    }
    .topo img{
    	width: 200px;
    }

    footer{
	    text-align: center;
	    font-size: 14px;
	    font-style: normal;
	    background-color: #f0f0f0;
	    padding: 30px 10px;
    }
    footer address{
    	 font-style: normal;
    }


	@media (min-width: 768px){
	    body{
	        padding: 0;
	        margin: 0;
	        background-image: url(https://mbl.adv.br/wp-content/uploads/2020/06/001-1.jpg);
		    background-repeat: no-repeat;
		    background-size: cover;
		    background-position: 50% 0%;
	    }
	    .bot{
			width: 460px;
			height: calc(100vh - 200px);
			background-position: top center;
			position: fixed;
			right: 20px;
			transform: translate(0px, -50%);
			top: 50%;
			border-radius: 8px;
			overflow: hidden;
			-webkit-box-shadow: 0px 0px 12px 0px rgba(0,0,0,0.51);
			-moz-box-shadow: 0px 0px 12px 0px rgba(0,0,0,0.51);
			box-shadow: 0px 0px 12px 0px rgba(0,0,0,0.51);
	    }

	    footer{
	    	padding: 20px 10px;
	    }

	    

	    .content{
	    	height: calc(100vh - 155px);
	    }


	    .call{
	        position: fixed;
	        bottom: 35px;
	        font-size: 15px;
	        background: #CDDC39;
	        right: 60px;
	        padding: 10px 30px 10px 15px;
	        font-family: sans-serif;
	        border-radius: 12px;
	        color: #0f793d;
	        max-width: 200px;
	        -webkit-box-shadow: 4px 4px 5px 0px rgba(0,0,0,0.31);
	        -moz-box-shadow: 4px 4px 5px 0px rgba(0,0,0,0.31);
	        box-shadow: 4px 4px 5px 0px rgba(0,0,0,0.31);
	    }
	    iframe{
	        height: 100vh;
	    }

	    body{
	        overflow: hidden;
	    }
	}
    
</style>

</head>

<body >
	<div class="topo">
    	<img src="https://mbl.adv.br/wp-content/uploads/2020/08/logo-mbl.png" class="logo">
    </div>

    <div class="bot" id="chat"></div>

    <section class="content">
    	
    </section>

    <footer>
    	<address>Av. Dom Luís, 300 - L2, conj. 226<br>
    	CEP 60160-230 - Aldeota - Fortaleza/CE - Brasil</address>
    </footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/blip-chat-widget" type="text/javascript"></script>

<script>
    (function () {
        window.onload = function () {
            
            var blipClient = new BlipChat()
            .withAppKey('bWJsYWR2b2dhZG9zOjI4NDNmOGVjLWQwODMtNGRhYi1iMTA1LTcwMmZhM2MwOThlYQ==')
            .withButton({"color":"#353c4d","icon":"https://s3-sa-east-1.amazonaws.com/msging.net/Services/Images/494a1070-c60f-4337-881e-58da2855e157", "bubbleMessage": "Teste"})
            .withTarget('chat')
            .withAccount({
                fullName:'teste'
            })
           
            .withEventHandler(BlipChat.CREATE_ACCOUNT_EVENT, function () {
              blipClient.sendMessage({
                  "type": "text/plain",
                  "content": "Oi, quero marcar uma consulta jurídica online.",
                  "nameTo": "teste 2"
              });
          });
           blipClient.build();
           $( "#chat" ).on( "click", function() {
                setTimeout(function(){ blipClient.widget._openChat(); }, 5000);
            });
            $( "#chat" ).trigger( "click" );

           $('.call').click(function(event) {
              blipClient.widget._openChat()
           });
        }
    })();
</script>
                

</body>
</html>
