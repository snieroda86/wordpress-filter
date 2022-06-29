<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package web14devsn
 */

get_header();
?>

	<main id="primary" class="site-main">

		<div class="container blog-container">
			<section class="mb-5 mt-3">
				<div class="product-cat-banner d-flex" style="background: url();background-size: cover;background-repeat: no-repeat; background-position: center;">
						<div class="cat-banner-inner">	
						<h1><?php  ?></h1>						
							
						</div>
				</div>
			</section>
			<!-- Posts list -->
			<section>
				<div class="row">
					<div class="col-md-8  mb-md-0 mb-4 pb-5">
						<div class="row" id="book-list">
							<?php
							if ( have_posts() ) :

								/* Start the Loop */
								while ( have_posts() ) :
									the_post();

									get_template_part( 'template-parts/content-book' );

								endwhile; ?>
								<div class="sn-pagination">
									<?php the_posts_pagination(); ?>	
								</div>
								
							<?php else :

								// get_template_part( 'template-parts/content', 'none' );

							endif;
							?>
						</div>
						
					</div>
					<!-- Sidebar -->
					<div class="col-md-4 blog-sidebar-col">
						<?php get_sidebar('filter'); ?>
					</div>
				</div>
				
			</section>

		
		</div>

	</main><!-- #main -->

<?php
get_footer();
