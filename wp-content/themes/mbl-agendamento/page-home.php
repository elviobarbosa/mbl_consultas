<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package storefront
 */

get_header(); ?>

	<main id="home">
		<section class="dobra-um">
		   	<div class="banners">
				<ul class="slider">
				
				<?php
					$argsBanners = array(
						'post_type'         => 'post_banners',
						'post_status'       => 'publish',
						'posts_per_page'    => 5,
						'orderby'   => array(
					      'date' =>'DESC',
					      'menu_order'=>'ASC',
					      /*Other params*/
					     )
					);
					$queryBanners = new WP_Query($argsBanners);

					while ($queryBanners->have_posts()) : $queryBanners->the_post();
						$imgD = get_field('imagem_desktop');
						$imgM = get_field('imagem_mobile');
						
						$imagemD ='<img class="desktop" data-lazy="' . $imgD['url'] . '" title="'. $imgD['alt'] .'">';
						$imagemM ='<img class="mobile" data-lazy="' . $imgM['url'] . '" title="'. $imgM['alt'] .'">';

						$link = get_field('url', $post->ID);
						$href = ( $link ) ? ['<a href="' . $link . '">', '</a>']: ['',''];
						$title = get_the_title();
						$descricao = get_the_content();

						echo "<li>" . $href[0] . $imagemD . $imagemM;
						echo $href[1] . "</li>";
					endwhile;
				?>
				</ul>
		   	</div><!--banners-->
   		</section>

   		<section class="dobra-dois">
   			<div class="ctn child-container">
				<div class="roll">
					<?php
					$categoria_1 = get_field('categoria', 'options');
					$produtos_1 = get_field('produtos', 'options');
					
					echo "<h2>" . $categoria_1->name . "</h2>";

					$loop = new WP_Query( array( 'post_type' => 'product', 'post__in' => $produtos_1 ) );
					
					echo '<div class="grid">';
					while ( $loop->have_posts() ) : $loop->the_post(); 
						$product = wc_get_product( $loop->post->ID);
				         echo '<div>';
				         if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_single');
				         
				         echo '<div class="text">';
				         the_title("<h3>","</h3>"); 
				         echo "<h4>R$ ". $product->get_price() . "</h4></div>";
				         the_excerpt();
				         echo '<a class="btn" href="'. get_permalink( $loop->post->ID ) .'" title="' . get_the_title() . '">Comprar</a>';
				         echo '</div>';
				    endwhile;
					
					?>
					</div><!--grid-->
				</div>
		   	</div>
   		</section>


   		<section class="dobra-tres">
   			<div class="ctn">
				<h5>Nascemos com a ideia de reverenciar os mais divinos aromas.</h5>
				<div class="img">
					
					<img src="<?php echo URLTEMA ?>/assets/images/borda-santa-grasse-home.png">
				</div>
		   	</div><!--banners-->
   		</section>

   		<section class="dobra-quatro">
   			<div class="ctn child-container">
				<div class="roll">
					<?php
					$categoria_2 = get_field('categoria_2', 'options');
					$produtos_2 = get_field('produtos_2', 'options');
					
					echo "<h2>" . $categoria_2->name . "</h2>";

					$loop = new WP_Query( array( 'post_type' => 'product', 'post__in' => $produtos_2 ) );
					
					echo '<div class="itens">';
					while ( $loop->have_posts() ) : $loop->the_post(); 
						$product = wc_get_product( $loop->post->ID);
				         echo '<div>';
				         if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_single');
				         
				         the_title("<h3>","</h3>"); 
				         
				         echo "<h4>R$ ". $product->get_price() . "</h4>";
				         echo '<a class="btn" href="'. get_permalink( $loop->post->ID ) .'" title="' . get_the_title() . '">Comprar</a>';

				         echo '</div>';
				    endwhile;
					
					?>
					</div><!--itens-->
				</div>
		   	</div><!--banners-->
   		</section>

   		<section class="dobra-cinco">
   			<div class="ctn child-container">
				<div class="roll">
					<?php
					$categoria_3 = get_field('categoria_3', 'options');
					$produtos_3 = get_field('produtos_3', 'options');
					
					echo "<h2>" . $categoria_3->name . "</h2>";

					$loop = new WP_Query( array( 'post_type' => 'product', 'post__in' => $produtos_3 ) );
					
					echo '<div class="itens">';
					while ( $loop->have_posts() ) : $loop->the_post(); 
						$product = wc_get_product( $loop->post->ID);
				         echo '<div>';
				         if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_single');
				         
				         the_title("<h3>","</h3>"); 
				         
				         echo "<h4>R$ ". $product->get_price() . "</h4>";
				         echo '<a class="btn" href="'. get_permalink( $loop->post->ID ) .'" title="' . get_the_title() . '">Comprar</a>';

				         echo '</div>';
				    endwhile;
					
					?>
					</div><!--itens-->
				</div>
		   	</div>
   		</section>


   		<section class="dobra-seis">
   			<div class="ctn child-container">
				<div class="roll">
					<?php
					$img_conceito = get_field('imagem_conceito', 'options');
					$produtos_4 = get_field('produtos_4', 'options');
					echo '<div class="img"><img src="' . $img_conceito['url'] . '" title="'. $img_conceito['title'] .'"></div>';

					$loop = new WP_Query( array( 'post_type' => 'product', 'post__in' => $produtos_4 ) );
					
					while ( $loop->have_posts() ) : $loop->the_post(); 
						$product = wc_get_product( $loop->post->ID);
				         echo '<div>';
				         if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_single');
				         
				         echo '<a class="btn" href="'. get_permalink( $loop->post->ID ) .'" title="' . get_the_title() . '">'. get_the_title() .'</a>';

				         echo '</div>';
				    endwhile;
					
					?>
					
				</div>
		   	</div>
   		</section>

   		<section class="dobra-sete">
   			<div class="ctn child-container">
				<div class="roll">
					<?php
					$categoria_5 = get_field('categoria_5', 'options');
					$produtos_5 = get_field('produtos_5', 'options');
					
					echo "<h2>" . $categoria_5->name . "</h2>";
					echo "<p>" . $categoria_5->description . "</p>";

					$loop = new WP_Query( array( 'post_type' => 'product', 'post__in' => $produtos_5 ) );
					
					echo '<div class="grid">';
					while ( $loop->have_posts() ) : $loop->the_post(); 
						$product = wc_get_product( $loop->post->ID);
				         echo '<div>';
				         if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_single');
				         
				         echo '<div class="text">';
				         the_title("<h3>","</h3>"); 
				         echo "<h4>R$ ". $product->get_price() . "</h4></div>";
				         echo '<a class="btn" href="'. get_permalink( $loop->post->ID ) .'" title="' . get_the_title() . '">Comprar</a>';
				         echo '</div>';
				    endwhile;
					
					?>
					</div><!--grid-->
				</div><!--roll-->
		   	</div><!--ctn-->
   		</section>
	</main>

<?php
//do_action( 'storefront_sidebar' );
get_footer();
