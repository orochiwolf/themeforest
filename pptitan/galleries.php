<?php
/**
 * Template Name: Galleries Grid
 * The main template file for display gallery of galleries.
 *
 * @package WordPress
*/

/**
*	Get Current page object
**/
$page = get_page($post->ID);

/**
*	Get current page id
**/

if(!isset($current_page_id) && isset($page->ID))
{
    $current_page_id = $page->ID;
}

get_header(); 
?>

<br class="clear"/>
</div>

<?php

//Get Page background style
$bg_style = get_post_meta($current_page_id, 'page_bg_style', true);

if($bg_style == 'Static Image')
{
    if(has_post_thumbnail($current_page_id, 'full'))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        $pp_page_bg = $image_thumb[0];
    }
    else
    {
    	$pp_page_bg = get_template_directory_uri().'/example/bg.jpg';
    }

    wp_enqueue_script("script-static-bg", get_template_directory_uri()."/templates/script-static-bg.php?bg_url=".$pp_page_bg, false, THEMEVERSION, true);
} // end if static image
else
{
    $page_bg_gallery_id = get_post_meta($current_page_id, 'page_bg_gallery_id', true);
    wp_enqueue_script("script-supersized-gallery", get_template_directory_uri()."/templates/script-supersized-gallery.php?gallery_id=".$page_bg_gallery_id, false, THEMEVERSION, true);
?>

<div id="thumb-tray" class="load-item">
    <div id="thumb-back"></div>
    <div id="thumb-forward"></div>
    <a id="prevslide" class="load-item"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_back.png" alt=""/></a>
    <a id="nextslide" class="load-item"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_forward.png" alt=""/></a>
</div>

<input type="hidden" id="pp_image_path" name="pp_image_path" value="<?php echo get_template_directory_uri(); ?>/images/"/>

<?php
}
?>

<!-- Begin content -->
<?php
if($bg_style == 'Static Image')
{
?>
<div class="page_control_static">
    <a id="page_minimize" href="#">
    	<img src="<?php echo get_template_directory_uri(); ?>/images/icon_zoom.png" alt=""/>
    </a>
    <a id="page_maximize" href="#">
    	<img src="<?php echo get_template_directory_uri(); ?>/images/icon_plus.png" alt=""/>
    </a>
</div>
<?php
}
else
{
?>
<div class="page_control">
    <a id="page_minimize" href="#">
    	<img src="<?php echo get_template_directory_uri(); ?>/images/icon_minus.png" alt=""/>
    </a>
    <a id="page_maximize" href="#">
    	<img src="<?php echo get_template_directory_uri(); ?>/images/icon_plus.png" alt=""/>
    </a>
</div>
<?php
}
?>

<?php
$page_audio = get_post_meta($current_page_id, 'page_audio', true);

if(!empty($page_audio))
{
?>
<div class="page_audio">
	<?php echo do_shortcode('[audio width="30" height="30" src="'.$page_audio.'"]'); ?>
</div>
<?php
}
?>

<div id="page_content_wrapper" class="fade-in two">
    
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    		
    		<div id="galleries_grid_wrapper" class="sidebar_content full_width nomargintop transparentbg">

					
<?php

$query_string ="post_type=gallery&paged=$paged";
query_posts($query_string);

if (have_posts()) : while (have_posts()) : the_post();

	$image_thumb = '';
								
	if(has_post_thumbnail(get_the_ID(), 'large'))
	{
	    $image_id = get_post_thumbnail_id(get_the_ID());
	    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
	}
	
	$custom_gallery_url = get_post_meta(get_the_ID(), 'gallery', true);
	if(empty($custom_gallery_url))
	{
		$custom_gallery_url = get_permalink();
	}
?>

<!-- Begin each blog post -->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post_wrapper grid_layout">
	
		<?php
	    	if(!empty($image_thumb))
	    	{
	    		$small_image_url = wp_get_attachment_image_src($image_id, 'blog_g', true);
	    ?>
	    
	    <div class="post_img">
	    	<a href="<?php echo $custom_gallery_url; ?>">
	    		<img src="<?php echo $small_image_url[0]; ?>" alt="" class=""/>
	    	</a>
	    </div>
	    <br class="clear"/>
	    
	    <?php
	    	}
	    ?>
	    
	    <div class="post_header">
	    	<h6 class="cufon"><a href="<?php echo $custom_gallery_url; ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
	    </div>
	    
	    <?php
	    	$gallery_description = get_the_content();
	    	
	    	if(!empty($gallery_description))
	    	{
	    		echo $gallery_description;
	    	}
	    ?>
	    
	</div>

</div>
<!-- End each blog post -->

<?php endwhile; endif; ?>
    		
    	</div>
    	
    </div>
    <!-- End main content -->
    
    <?php
			if (function_exists("wpapi_pagination")) 
			{
			    wpapi_pagination($wp_query->max_num_pages);
			}
			else
			{
			?>
			    <div class="pagination"><p><?php posts_nav_link(' '); ?></p></div>
			<?php
			}
		?>

</div>  

<?php get_footer(); ?>