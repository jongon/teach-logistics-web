<?php
$post_layout = dttheme_option('specialty','search-post-layout'); 
$post_layout = !empty($post_layout) ? $post_layout : "one-column";
$post_class = "";

switch($post_layout):
	case 'one-column':
		$post_class = " column dt-sc-one-column ";
		$firstcnt = 1;
	break;

	case 'one-half-column';
		$post_class = " column dt-sc-one-half";
		$firstcnt = 2;
	break;

	case 'one-third-column':
		$post_class = " column dt-sc-one-third ";
		$firstcnt = 3;
	break;
endswitch;

$post_per_page = get_option('posts_per_page');

$args = array( 'paged' => get_query_var( 'paged' ) ,'posts_per_page' => $post_per_page,'post_type' => 'dt_courses','s'=>$_GET['s']);

$wp_query->query( $args );
if( $wp_query->have_posts() ):  while( $wp_query->have_posts() ): $wp_query->the_post();

	$firstcls = $temp_class = '';
	$no = $wp_query->current_post+1;
	
	if(($no%$firstcnt) == 1){ $firstcls = ' first'; }
	$temp_class = 'class="'.$post_class.' '.$firstcls.'"';
	
	$course_settings = get_post_meta(get_the_ID(), '_course_settings');
	
	?>
    
	<div <?php echo $temp_class; ?>>

	<article id="post-<?php echo get_the_ID(); ?>" class="<?php echo implode(" ", get_post_class("dt-sc-custom-course-type", get_the_ID())); ?>">
	
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
			
			<div class="dt-sc-course-meta">
				<p> <?php the_terms(get_the_ID(), 'course_category', ' ', ', ', ' '); ?> </p>
				<p> <?php echo $count.'&nbsp;'.__('Lessons', 'dt_themes'); ?> </p>
			</div>
			
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
    
    </div>

<?php 
endwhile; else:
	echo __('No courses to load!', 'dt_themes');	
endif; 
?>

<!-- **Pagination** -->
<div class="pagination">
    <div class="prev-post"><?php previous_posts_link('<span class="fa fa-angle-double-left"></span> Prev');?></div>
    <?php echo dttheme_pagination();?>
    <div class="next-post"><?php next_posts_link('Next <span class="fa fa-angle-double-right"></span>');?></div>
</div><!-- **Pagination - End** -->