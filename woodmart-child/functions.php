<?php
/**
 * Enqueue script and styles for child theme
 */
function woodmart_child_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), woodmart_get_theme_info( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 10010 );


add_action( 'woocommerce_before_add_to_cart_button', 'misha_before_add_to_cart_btn' );
 
function misha_before_add_to_cart_btn(){
    
    
    global $product;
    
    if($product->get_type() == 'external'){ ?>
    <style>
        .cp-external-tooltip {
    border: 4px solid #f1f1f190;
    padding: 20px;
}

.cp-external-tooltip strong {
    display: block;
    text-decoration: underline;
}
    </style>
            
        <div class='cp-external-tooltip' style='margin-bottom: 30px'>
            <strong>Important Note</strong>Once on the <a href='https://www.g2a.com/n/redeem-keys'>G2A.com</a> you can purcase the game for cheaper then the price given on this site. Simply scroll down and see the 'Offers' tab. Being a marketplace there are many different sellers trying to give us gamers the best price! =P
        </div>
            
        
    <?php };
    
}


function rds_availability_function($atts = []) {
    
   extract(shortcode_atts([
        'xbox' => '',
        'ps'  => '',
        'pc'   => ''
    ],  $atts )); 
    
    
    ?>
    
    <style>
        .wpb_single_image  {
            margin-bottom: 10px;
        }
        .rks-available-on {
            display: flex;
            justify-content: center;
            padding: 0 30px;
        }
        
        
        .rks-available-on__title {
            text-align: center;
            font-size: 22px;
            margin-bottom: 5px ;
        }
        .rks-available-on__item img{
            height: 30px;
            width: auto;
            margin: 0 10px;
            filter: invert(1);
        }
    </style>
    
    <p class='rks-available-on__title'>Get It Here</p>
    
    <div class="rks-available-on">
        
        
        <?php if(isset($atts['ps'])): ?>
            <div class="rks-available-on__item">
                <a href="<?php echo $atts['ps'] ?>">
                    <img src="/wp-content/uploads/2021/04/PS-ICON-2.png" alt="">
                </a>
            </div>
        <?php endif; ?>
            
        <?php if(isset($atts['xbox'])): ?>
            <div class="rks-available-on__item">
                <a href="<?php echo $atts['xbox'] ?>">
                    <img src="/wp-content/uploads/2021/04/XBOX-ICON.png" alt="">
                </a>
            </div>
        <?php endif; ?>
        
        <?php if(isset($atts['pc'])): ?>
            <div class="rks-available-on__item">
                <a href="<?php echo $atts['pc'] ?>">
                    <img src="/wp-content/uploads/2021/04/PC-ICON.png" alt="">
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php 
    
}
add_shortcode('rds_availability', 'rds_availability_function');


function woo_how_to_redeem_content() {


   global $product;
   
  
    $title = $product->get_title();
    
    $cleaned_title = explode(' - ', $title);
    
    
    $string = "<h3>How To Redeem {$cleaned_title[0]} ";
    
    $terms = get_the_terms( $product->get_id(), 'product_cat' );
    

    foreach($terms as $term){
        switch($term->name){
            case 'Steam':
                echo "{$string} key on Steam</h3>";
                echo "<ul>";
                    echo "<li>Launch Steam and log into your account.</li>";
                    echo "<li>Go to Games then click on 'Activate a Product on Steam'...</li>";
                    echo "<li>Enter the code you you received when purchasing {$cleaned_title[0]}.</li>";
                    echo "<li>Go to your Steam game library, highlight the game, and click install.</li>";
                echo "</ul>";
            
                break;
            case 'Origin':
                echo "{$string} on Origin</h3>";
                echo "";
                break;
            
        }
    }   
    
}

add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {
	
    $tabs['how_to_redeem'] = [
        'title'     => __( 'How To Redeem Your Key', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'woo_how_to_redeem_content'
    ];
    
    if( have_rows('minimum_req') ||  have_rows('max_req') ):
        $tabs['system_requirements'] = [
            'title'     => __( 'System Requirements', 'woocommerce' ),
            'priority'  => 55,
            'callback'  => 'woo_system_requiremnents_content'
        ];
    endif;
	return $tabs;

}

    
function woo_system_requiremnents_content(){
    
    
    
   global $product;
   
   
    $title = $product->get_title();
    
    $cleaned_title = explode(' - ', $title);
    
    echo "<h3>Unsure on your PC specs?</h3>";
    echo "<p>Fear not, use this free tool to see if you can run the game. Head over the <a target='_blank' href='https://www.systemrequirementslab.com/cyri'>Can I Run It</a> and find the game in the drop down, download the hardware test and follow insructions</p>";
    if( have_rows('minimum_req') ):
    
        echo "<h3>Minimum System Requirements For {$cleaned_title[0]}</h3>";
        echo "<ul class='rks-req-list'>";
            // Loop through rows.
            while( have_rows('minimum_req') ) : the_row();
        
                // Load sub field value.
                $min_os = get_sub_field('minimum_os');
                $min_proc = get_sub_field('minimum_processor');
                $min_mem = get_sub_field('minimum_memory');
                $min_grap = get_sub_field('minimum_graphics');
                $min_stor = get_sub_field('minimum_storage');
            
                    if($min_os){
                        echo "<li><strong>OS</strong> -- {$min_os}</li>";
                    }
                    
                    if($min_proc){
                        echo "<li><strong>Processor</strong> -- {$min_proc}</li>";
                    }
                    
                    if($min_mem){
                        echo "<li><strong>Memory</strong> -- {$min_mem}</li>";
                    }
                    
                    if($min_grap){
                        echo "<li><strong>Graphics</strong> -- {$min_grap}</li>";
                    }
                    
                    if($min_stor){
                        echo "<li><strong>Storage</strong> -- {$min_stor}</li>";
                    }
                
            endwhile;
        echo "</ul>";
    // No value.
    endif;
    
    

    if( have_rows('maximum_req') ):
    
        echo "<h3>Maximum System Requirements For {$cleaned_title[0]}</h3>";
        echo "<ul class='rks-req-list'>";
            // Loop through rows.
            while( have_rows('maximum_req') ) : the_row();
        
                // Load sub field value.
                $max_os = get_sub_field('maximum_os');
                $max_proc = get_sub_field('maximum_processor');
                $max_mem = get_sub_field('maximum_memory');
                $max_grap = get_sub_field('maximum_graphics');
                $max_stor = get_sub_field('maximum_storage');
            
                    if($min_os){
                        echo "<li><strong>OS</strong> -- {$max_os}</li>";
                    }
                    
                    if($min_proc){
                        echo "<li><strong>Processor</strong> -- {$max_proc}</li>";
                    }
                    
                    if($min_mem){
                        echo "<li><strong>Memory</strong> -- {$max_mem}</li>";
                    }
                    
                    if($min_grap){
                        echo "<li><strong>Graphics</strong> -- {$max_grap}</li>";
                    }
                    
                    if($min_stor){
                        echo "<li><strong>Storage</strong> -- {$max_stor}</li>";
                    }
                
            endwhile;
        echo "</ul>";
    // No value.
    endif;
    

    

    
}







