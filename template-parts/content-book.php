
 <div class="col-lg-4 mb-5">
 	<article id="book-<?php the_ID(); ?>">
		<div class="book-item-loop text-center">
			<?php the_post_thumbnail('full', ['class' => 'img-fluid' ]); ?>
			<h5 class="pt-3"><?php the_title(); ?></h5>
			<h6 class=""><?php the_field('autor_ksiazki'); ?></h6>
			<a href="<?php the_permalink(); ?>" class="btn btn-secondary"><?php _e('Read more' , 'books-rental'); ?></a>
		</div>
	</article>
</div>