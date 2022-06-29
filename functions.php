<?php 

function book_library_enqueue_style() {
    wp_enqueue_style( 'book-library-main-style',  get_stylesheet_directory_uri(). '/css/styles.css', false );
}
 
function book_library_enqueue_script() {
    wp_enqueue_script('jquery');
    
    wp_enqueue_script( 'book-library-bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', true );

    wp_enqueue_script( 'book-library-main-js', get_stylesheet_directory_uri(). '/js/scripts.js', array('jquery') , '' , true );
}
 
add_action( 'wp_enqueue_scripts', 'book_library_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'book_library_enqueue_script' );

// Register menu
add_action( 'after_setup_theme', 'register_primary_menu' );
 
function register_primary_menu() {
    register_nav_menu( 'primary-nav', __( 'Primary Menu', 'books-rental' ) );
}

// Custom class to li 

function sn_menu_item_class( $classes, $item ) {       
    $classes[] = "nav-item"; 
    return $classes;
} 

add_filter( 'nav_menu_css_class' , 'sn_menu_item_class' , 10, 2 );

// csutom link class
function sn_modify_link_menuclass($ulclass) {
    return preg_replace('/<a /', '<a class="nav-link px-lg-3 py-3 py-lg-4"', $ulclass);
}

add_filter('wp_nav_menu', 'sn_modify_link_menuclass');

// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', get_stylesheet_directory() . '/includes/acf/' );
define( 'MY_ACF_URL', get_stylesheet_directory_uri() . '/includes/acf/' );

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}


// ACF JSON

add_filter('acf/settings/save_json', 'my_acf_json_save_point');
 
function my_acf_json_save_point( $path ) {
    
    // update path
    $path = get_stylesheet_directory() . '/acf-json';
    
    
    // return
    return $path;
    
}


// CPT Books
// Our custom post type function
function create_posttype_books() {
  
    register_post_type( 'books',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Books' ),
                'singular_name' => __( 'Book' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'book-list'),
            'show_in_rest' => false,
            'supports' => array( 'title', 'editor', 'thumbnail' ) ,
            'menu_icon' => 'dashicons-book'
  
        )
    );
}

add_action( 'init', 'create_posttype_books' );

// Books taxonomy 
add_action( 'init', 'create_books_hierarchical_taxonomy', 0 );
 
function create_books_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Category', 'taxonomy singular name' ),
    'menu_name' => __( 'Categories' ),
  );    
 
// Now register the taxonomy
  register_taxonomy('book_category',array('books'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => false,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'book-category' ),
  ));
 
}

// Get all books
function getAllBooks(){
    $args = array(  
        'post_type' => 'books',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
        'orderby' => 'title', 
        'order' => 'ASC', 
    );

    $books = new WP_Query( $args ); 
    return $books;
}


// AJAX search filter
add_action( 'wp_ajax_filter_books', 'filter_books' );
add_action( 'wp_ajax_nopriv_filter_books', 'filter_books' );

function filter_books(){

    $autor_ksiazki = $_POST['autor_ksiazki'];
    $rok_wydania = $_POST['rok_wydania'];
    $wydawnictwo = $_POST['wydawnictwo'];



    $args = array(  
        'post_type' => 'books',
        'post_status' => 'publish',
        'posts_per_page' => -1 ,
    );

    if(isset($autor_ksiazki) && !empty($autor_ksiazki)){
        if(array_key_exists('meta_query' , $args)){
            $args['meta_query'][] = array(
                array(
                    'key'     => 'autor_ksiazki',
                    'value'   => $autor_ksiazki,
                    'compare' => 'IN'
                )
            );
        }else{
            $args['meta_query']= array(
                'relation' => 'AND',
                array(
                    'key'     => 'autor_ksiazki',
                    'value'   => $autor_ksiazki,
                    'compare' => 'IN'
                )
            );
        }
        
    }

    if(isset($rok_wydania) && !empty($rok_wydania)){
        if(array_key_exists('meta_query' , $args)){
            $args['meta_query'][] = array(
                array(
                    'key'     => 'rok_wydania',
                    'value'   => $rok_wydania,
                    'compare' => 'IN'
                )
            );
        }else{
            $args['meta_query']= array(
                'relation' => 'AND',
                array(
                    'key'     => 'rok_wydania',
                    'value'   => $rok_wydania,
                    'compare' => 'IN'
                )
            );
        }
    }

    // Wydawnictwo

     if(isset($wydawnictwo) && !empty($wydawnictwo)){
        if(array_key_exists('meta_query' , $args)){
            $args['meta_query'][] = array(
                array(
                    'key'     => 'wydawnictwo',
                    'value'   => array($wydawnictwo),
                    'compare' => 'IN'
                )
            );
        }else{
            $args['meta_query']= array(
                'relation' => 'AND',
                array(
                    'key'     => 'wydawnictwo',
                    'value'   => array($wydawnictwo),
                    'compare' => 'IN'
                )
            );
        }
    }

    $loop = new WP_Query( $args ); 

    if ( $loop->have_posts() ) :
        while ( $loop->have_posts() ) : $loop->the_post(); 
           get_template_part( 'template-parts/content-book' );
        endwhile;
    else:
        echo "No results";
    endif;

    wp_reset_postdata();

    die();
}
