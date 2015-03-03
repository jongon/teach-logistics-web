<?php require_once("../../../../wp-load.php"); ?>
<?php 

$post_id = $_REQUEST['post_id'];

$course_page_type = $_REQUEST['course_page_type'];

if($course_page_type == 'archive' || $course_page_type == 'tax-archive') {
	
	$post_layout = dttheme_option('dt_course','archives-post-layout'); 
	$post_layout = !empty($post_layout) ? $post_layout : "one-half-column";
	
	$post_per_page = get_option('posts_per_page');
	
} else {
	
	$tpl_default_settings = get_post_meta( $post_id, '_tpl_default_settings', TRUE );
	$tpl_default_settings = is_array( $tpl_default_settings ) ? $tpl_default_settings  : array();
	
	$post_layout  = array_key_exists( "courses-post-layout", $tpl_default_settings ) ? $tpl_default_settings['courses-post-layout'] : "one-half-column";
	$post_per_page	=	isset($tpl_default_settings['courses-post-per-page']) ? $tpl_default_settings['courses-post-per-page'] : -1;

}


$grid_view = $list_view = $layout_class = $post_class = "";

switch($post_layout):

	case 'one-half-column';
		$post_class = "column dt-sc-one-half";
		$firstcnt = 2;
		$grid_view = 'active';
	break;

	case 'one-third-column':
		$post_class = "column dt-sc-one-third";
		$firstcnt = 3;
		$grid_view = 'active';
	break;

endswitch;

$curr_page = isset($_REQUEST['curr_page']) ? $_REQUEST['curr_page'] : 1;
$offset = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0;
$view_type = isset($_REQUEST['view_type']) ? $_REQUEST['view_type'] : 'grid';
$price_type = isset($_REQUEST['price_type']) ? $_REQUEST['price_type'] : 'all';
$courses_type = isset($_REQUEST['courses_type']) ? $_REQUEST['courses_type'] : 'all';

/* Change b/w list and grid view */
if( isset($view_type) && $view_type === "list" ) {
	$layout_class = "course-list-view";
	$firstcnt = 1;
	$list_view = 'active';
	$grid_view = '';
} elseif( isset($view_type) && $view_type === "grid" ) {
	$layout_class = '';
	$grid_view = 'active';
	$list_view = '';
} 

	
/* Configured all datas here to access in ajax function */
echo '<span id="dt-course-datas" data-postid="'.$post_id.'" data-view_type="'.$view_type.'" data-postperpage="'.$post_per_page.'" data-curr_page="'.$curr_page.'" data-offset="'.$offset.'" data-price_type="'.$price_type.'" data-courses_type="'.$courses_type.'" style="display:none;"></span>';


if($courses_type != 'popular') {
	
	$args = array( 'offset'=>$offset, 'paged' => $curr_page ,'posts_per_page' => $post_per_page,'post_type' => 'dt_courses','meta_query'=>array(), 'tax_query'=>array(),);

	if($price_type == 'paid') {
		
		$args['meta_query'][] = array(
						'key'     => 'starting-price',
						'value'   => 0,
						'type'    => 'numeric',
						'compare' => '>'
						);
						
	} else if($price_type == 'free') {
		
		$args['meta_query'][] = array(
						'key'     => 'starting-price',
						'compare' => 'NOT EXISTS'
						);
						
	} else if($courses_type == 'featured') {
		
		$args['meta_query'][] = array(
						'key'     => 'featured-course',
						'compare' => 'EXISTS'
						);
							
	}
	
	if($course_page_type == 'tax-archive') {

		$args['tax_query'][] = array( 'taxonomy' => 'course_category',
						'field' => 'id',
						'terms' => $post_id,
						'operator' => 'IN'
						);
						
	}
		
	$wp_query->query( $args );
	if( $wp_query->have_posts() ):  while( $wp_query->have_posts() ): $wp_query->the_post();
	
		$firstcls = $temp_class = '';
		$no = $wp_query->current_post+1;
		
		if(($no%$firstcnt) == 1){ $firstcls = ' first'; }
		$temp_class = 'class="'.$post_class.' '.$firstcls.'"';
		
		$course_settings = get_post_meta(get_the_ID(), '_course_settings');
		
		if( $grid_view == 'active' ) {
		echo '<div '.$temp_class.'>';
		}
		?>
		<article id="post-<?php echo get_the_ID(); ?>" class="<?php echo implode(" ", get_post_class("dt-sc-custom-course-type {$layout_class}", get_the_ID())); ?>">
		
			<div class="dt-sc-course-thumb">
				<a href="<?php echo the_permalink(); ?>" >
					<?php
					if(has_post_thumbnail()):
						$image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full');
					?>
						<img src="<?php echo $image_url[0]; ?>" alt="<?php echo get_the_title(); ?>" />
					<?php else: ?>
						<img src="http://placehold.it/1170x822&text=Image" alt="<?php echo get_the_title(); ?>" />
					<?php endif; ?>
				 </a>
                <div class="dt-sc-course-overlay">
                    <a title="<?php echo get_the_title(); ?>" href="<?php echo the_permalink(); ?>" class="dt-sc-button small white"> <?php echo __('View Course', 'dt_themes'); ?> </a>
                </div>
			</div>			
			
			<?php
			$lesson_args = array('post_type' => 'dt_lessons', 'posts_per_page' => -1, 'meta_key' => 'dt_lesson_course', 'meta_value' => get_the_ID() );
			$lessons_array = get_pages( $lesson_args );
			
			$count = $duration = 0;
			if(count($lessons_array) > 0) {
				foreach($lessons_array as $lesson) {
					$lesson_data = get_post_meta($lesson->ID, '_lesson_settings');
					if(isset($lesson_data[0]['lesson-duration'])) $duration = $duration + $lesson_data[0]['lesson-duration'];
					$count++;
				}
			}
			
			if($duration > 0) {
				$hours = floor($duration/60); 
				$mins = $duration % 60; 
				if(strlen($mins) == 1) $mins = '0'.$mins;
				if(strlen($hours) == 1) $hours = '0'.$hours;
				if($hours == 0) {
					$duration = '00 : '.$mins;
				} else {
					$duration = $hours . ' : ' . $mins; 				
				}
			}
			?>
			
			<div class="dt-sc-course-details">	
            
				<?php if($list_view == 'active') { ?>
                
                   <h5><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
                
                    <?php $starting_price = get_post_meta(get_the_ID(), 'starting-price', true);
                    if($starting_price != ''): ?>
                        <span class="dt-sc-course-price"> <span class="amount"> 
                            <?php 
                            if(dttheme_option('dt_course','currency-position') == 'after-price') 
                                echo $starting_price.dttheme_option('dt_course','currency'); 
                            else
                                echo dttheme_option('dt_course','currency').$starting_price; 
                            ?>
                        </span> </span>
                    <?php else: ?>
                        <span class="dt-sc-course-price"> <span class="amount"> <?php echo __('Free', 'dt_themes'); ?> </span> </span>
                    <?php endif; ?>
                                
                <?php } else { ?>
                
                    <?php $starting_price = get_post_meta(get_the_ID(), 'starting-price', true);
                    if($starting_price != ''): ?>
                        <span class="dt-sc-course-price"> <span class="amount"> 
                            <?php 
                            if(dttheme_option('dt_course','currency-position') == 'after-price') 
                                echo $starting_price.dttheme_option('dt_course','currency'); 
                            else
                                echo dttheme_option('dt_course','currency').$starting_price; 
                            ?>
                        </span> </span>
                    <?php else: ?>
                        <span class="dt-sc-course-price"> <span class="amount"> <?php echo __('Free', 'dt_themes'); ?> </span> </span>
                    <?php endif; ?>
                    
                    <h5><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
                
                <?php } ?>

                <div class="dt-sc-course-meta">
                    <p> <?php the_terms(get_the_ID(), 'course_category', ' ', ', ', ' '); ?> </p>
                    <p> <?php echo $count.'&nbsp;'.__('Lessons', 'dt_themes'); ?> </p>
                </div>
                
                <?php if($list_view == 'active') { ?>
                    <div class="dt-sc-course-desc">
                        <?php echo get_the_excerpt(); ?>
                    </div>
                <?php } ?>
                
                <div class="dt-sc-course-data">
                    <div class="dt-sc-course-duration">
                        <i class="fa fa-clock-o"> </i>
                        <span> <?php echo $duration; ?> </span>
                    </div>
					<?php
					if(function_exists('the_ratings') && !dttheme_option('general', 'disable-ratings-courses')) { 
						echo do_shortcode('[ratings id="'.get_the_ID().'"]');
					}
					?>
                </div>
                                                    
			</div>
		
		</article>
		<?php
		if( $grid_view == 'active' ) {
		echo '</div>';
		}
		?>    
	
	<?php 
	endwhile; else:
		echo '<div class="dt-sc-info-box">'.__('No Courses Found!', 'dt_themes').'</div>';
	endif; 
	
	$total_posts = $wp_query->found_posts;

} else {
	
	/* Manually queried to list the popular courses based on wp-postratings(plugin) */
	
	global $wpdb;
	$table1 = $wpdb->prefix . "ratings";
	$table2 = $wpdb->prefix . "posts";
	$table3 = $wpdb->prefix . "term_relationships";
	$table4 = $wpdb->prefix . "term_taxonomy";
	

	if($course_page_type == 'tax-archive') {
		
		$cp_qry1 = "SELECT b.* FROM $table1 a, $table2 b, $table3 tr, $table4 tt  WHERE a.rating_postid = b.ID and b.post_type='dt_courses' and b.post_status = 'publish' AND tt.term_id = {$post_id} AND tr.term_taxonomy_id = tt.term_taxonomy_id and b.ID =  tr.object_id group by a.rating_postid order by avg(a.rating_rating) desc";
		
		$cs_cnt = 0;
		$wp_course_cnt = $wpdb->get_results( $cp_qry1 );
		$cs_cnt = count($wp_course_cnt);
		
		if($post_per_page == -1 ) $post_per_page = $cs_cnt;
		
		$cp_qry2 = "SELECT b.* FROM $table1 a, $table2 b, $table3 tr, $table4 tt  WHERE a.rating_postid = b.ID and b.post_type='dt_courses' and b.post_status = 'publish' AND tt.term_id = {$post_id} AND tr.term_taxonomy_id = tt.term_taxonomy_id and b.ID =  tr.object_id group by a.rating_postid order by avg(a.rating_rating) desc LIMIT $offset, $post_per_page";
		
	} else {
		
		$cp_qry1 = "SELECT a.* FROM $table1 a, $table2 b WHERE a.rating_postid = b.ID and b.post_type='dt_courses' and b.post_status = 'publish' group by a.rating_postid order by avg(a.rating_rating) desc";
		
		$cs_cnt = 0;
		$wp_course_cnt = $wpdb->get_results( $cp_qry1 );
		$cs_cnt = count($wp_course_cnt);
		
		if($post_per_page == -1 ) $post_per_page = $cs_cnt;
		
		$cp_qry2 = "SELECT a.*, b.* FROM $table1 a, $table2 b WHERE a.rating_postid = b.ID and b.post_type='dt_courses' and b.post_status = 'publish' group by a.rating_postid order by avg(a.rating_rating) desc LIMIT $offset, $post_per_page";		
	
	}	
	
	
	$wp_course_qry = $wpdb->get_results( $cp_qry2 );
	
	$cs_num = 0;
	if(!empty($wp_course_qry)) {
		
		foreach($wp_course_qry as $course_item) :
			
			$course_item_id = $course_item -> ID;
			
			$firstcls = $temp_class = '';
			$no = $cs_num + 1;
			
			if(($no%$firstcnt) == 1){ $firstcls = ' first'; }
			$temp_class = 'class="'.$post_class.' '.$firstcls.'"';
			
			$course_settings = get_post_meta($course_item_id, '_course_settings');
			
			if( $grid_view == 'active' ) {
			echo '<div '.$temp_class.'>';
			}
			?>
			<article id="post-<?php echo $course_item_id; ?>" class="<?php echo implode(" ", get_post_class("dt-sc-custom-course-type {$layout_class}", $course_item_id)); ?>">
			
				<div class="dt-sc-course-thumb">
					<a href="<?php echo get_permalink($course_item_id); ?>" >
						<?php
						if(has_post_thumbnail($course_item_id)):
							$image_url = wp_get_attachment_image_src( get_post_thumbnail_id($course_item_id), 'full');
						?>
							<img src="<?php echo $image_url[0]; ?>" alt="<?php echo $course_item->post_title; ?>" />
						<?php else: ?>
							<img src="http://placehold.it/1170x822&text=Image" alt="<?php echo $course_item->post_title; ?>" />
						<?php endif; ?>
					 </a>
                    <div class="dt-sc-course-overlay">
                        <a title="<?php $course_item->post_title; ?>" href="<?php echo get_permalink($course_item_id); ?>" class="dt-sc-button small white"> <?php echo __('View Course', 'dt_themes'); ?> </a>
                    </div>
				</div>
				
				<?php
				$lesson_args = array('post_type' => 'dt_lessons', 'posts_per_page' => -1, 'meta_key' => 'dt_lesson_course', 'meta_value' => $course_item_id );
				$lessons_array = get_pages( $lesson_args );
				
				$count = $duration = 0;
				if(count($lessons_array) > 0) {
					foreach($lessons_array as $lesson) {
						$lesson_data = get_post_meta($lesson->ID, '_lesson_settings');
						if(isset($lesson_data[0]['lesson-duration'])) $duration = $duration + $lesson_data[0]['lesson-duration'];
						$count++;
					}
				}
				
				if($duration > 0) {
					$hours = floor($duration/60); 
					$mins = $duration % 60; 
					if(strlen($mins) == 1) $mins = '0'.$mins;
					if(strlen($hours) == 1) $hours = '0'.$hours;
					if($hours == 0) {
						$duration = '00 : '.$mins;
					} else {
						$duration = $hours . ' : ' . $mins; 				
					}
				}
				?>
				
				<div class="dt-sc-course-details">	
                
                	<?php if($list_view == 'active') { ?>
                    
                        <h5><a href="<?php echo get_permalink($course_item_id); ?>" title="<?php echo $course_item->post_title; ?>"><?php echo $course_item->post_title; ?></a></h5>

						<?php $starting_price = get_post_meta($course_item_id, 'starting-price', true);
                        if($starting_price != ''): ?>
                            <span class="dt-sc-course-price"> <span class="amount"> 
                                <?php 
                                if(dttheme_option('dt_course','currency-position') == 'after-price') 
                                    echo $starting_price.dttheme_option('dt_course','currency'); 
                                else
                                    echo dttheme_option('dt_course','currency').$starting_price; 
                                ?>
                            </span> </span>
                        <?php else: ?>
                            <span class="dt-sc-course-price"> <span class="amount"> <?php echo __('Free', 'dt_themes'); ?> </span> </span>
                        <?php endif; ?>
                                        
                    <?php } else { ?>
                    
						<?php $starting_price = get_post_meta($course_item_id, 'starting-price', true);
                        if($starting_price != ''): ?>
                            <span class="dt-sc-course-price"> <span class="amount"> 
                                <?php 
                                if(dttheme_option('dt_course','currency-position') == 'after-price') 
                                    echo $starting_price.dttheme_option('dt_course','currency'); 
                                else
                                    echo dttheme_option('dt_course','currency').$starting_price; 
                                ?>
                            </span> </span>
                        <?php else: ?>
                            <span class="dt-sc-course-price"> <span class="amount"> <?php echo __('Free', 'dt_themes'); ?> </span> </span>
                        <?php endif; ?>
                    
                        <h5><a href="<?php echo get_permalink($course_item_id); ?>" title="<?php echo $course_item->post_title; ?>"><?php echo $course_item->post_title; ?></a></h5>
                    
                    <?php } ?>
                    
                    
                    <div class="dt-sc-course-meta">
                        <p> <?php the_terms($course_item_id, 'course_category', ' ', ', ', ' '); ?> </p>
                        <p> <?php echo $count.'&nbsp;'.__('Lessons', 'dt_themes'); ?> </p>
                    </div>
                
					<?php if($list_view == 'active') { ?>
                        <div class="dt-sc-course-desc">
                            <?php echo $course_item->post_excerpt; ?>
                        </div>
                    <?php } ?>
                
                    <div class="dt-sc-course-data">
                        <div class="dt-sc-course-duration">
                            <i class="fa fa-clock-o"> </i>
                            <span> <?php echo $duration; ?> </span>
                        </div>
                        <?php
						if(function_exists('the_ratings') && !dttheme_option('general', 'disable-ratings-courses')) { 
							echo do_shortcode('[ratings id="'.$course_item_id.'"]');
						}
                        ?>
                    </div>
                
				</div>
			
			</article>
			<?php
			if( $grid_view == 'active' ) {
			echo '</div>';
			}
			
			$cs_num++;
			
		endforeach;
		
	} else {
		echo '<div class="dt-sc-info-box">'.__('No Courses Found!', 'dt_themes').'</div>';
	}


	$total_posts = $cs_cnt;

}

/* Pagination to work with ajax */
echo dtthemes_ajax_pagination($post_per_page, $curr_page, $total_posts, $post_id);
?>