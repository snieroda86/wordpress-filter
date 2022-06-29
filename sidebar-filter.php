<aside>
	<div class="filter-wrapper-sn">
		
		<form id="filter-sn" method="post">
			<input type="hidden" name="action"  value="filter_books">

			<!-- Autor -->
			<div>
			<h4>Autor</h4>		
				<ul class="list-group list-group-flush">
					<?php 	
					
					 global $wpdb;
			        $results = $wpdb->get_results( 
	                    "SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key='autor_ksiazki'"  , ARRAY_A
	                 );

			        $res = array_unique($results , SORT_REGULAR);
			        
			        foreach ($res as $key => $value) { ?>
			        	<li class="list-group-item">
					      <!-- Default checked -->
					      <div class="custom-control custom-checkbox">
					        <input type="checkbox" value="<?php echo $value['meta_value']; ?>" name="autor_ksiazki[]" class="custom-control-input" id="autor_ksiazki-<?php echo $key; ?>" >
					        <label class="custom-control-label" for="autor_ksiazki-<?php echo $key; ?>"><?php echo $value['meta_value']; ?></label>
					      </div>
					    </li>
			        <?php } ?>
				   
				  </ul>
			</div>

			<!-- Rok wydania -->

			<div>	
				<h4>Rok wydania</h4>	
				<ul class="list-group list-group-flush">
					<?php 	
					
					 // global $wpdb;
			        $results2 = $wpdb->get_results( 
	                    "SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key='rok_wydania'"  , ARRAY_A
	                 );

			        $res2 = array_unique($results2 , SORT_REGULAR);
			       
			        foreach ($res2 as $key => $value) { ?>
			        	<li class="list-group-item">
					      <!-- Default checked -->
					      <div class="custom-control custom-checkbox">
					        <input type="checkbox" value="<?php echo $value['meta_value']; ?>" name="rok_wydania[]" class="custom-control-input" id="rok_wydania-<?php echo $key; ?>" >
					        <label class="custom-control-label" for="rok_wydania-<?php echo $key; ?>"><?php echo $value['meta_value']; ?></label>
					      </div>
					    </li>
			        <?php } ?>
				   
				  </ul>
			</div>

			<!-- Wydawnictwo -->

			<div class="pb-3">	
				<h4>Wydawnictwo</h4>	
				
					<?php 	
					
					 // global $wpdb;
			        $results3 = $wpdb->get_results( 
	                    "SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key='wydawnictwo'"  , ARRAY_A
	                 );

			        $res3 = array_unique($results3 , SORT_REGULAR); ?>

			        <select class="form-select" aria-label="Default select example" name="wydawnictwo">
			        	 <option  selected value> -- wybierz -- </option>
			        	<?php 
				        foreach ($res3 as $key => $value) { ?>
				        <option  value="<?php echo $value['meta_value'] ?>"><?php echo $value['meta_value'] ?></option>	
				        <?php } ?>
					  
					  
					</select>
			</div>


			<button class="btn btn-secondary" type="submit">Szukaj</button>
		</form>
	</div>
</aside>

<script type="text/javascript" >
	jQuery(document).ready(function($) {

		$('#filter-sn').on("submit" , function(e){
			e.preventDefault();

			var formData = $(this).serialize();
			var data = formData;

			var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
			$.ajax( {
	            url: ajaxurl, // This variable somewhere
	            method: 'POST',
	            data: data , 
	        } ).done( function( result ){

	            $('#book-list').html(result);
	        } );		
			
			
		});

		
	});
	</script>