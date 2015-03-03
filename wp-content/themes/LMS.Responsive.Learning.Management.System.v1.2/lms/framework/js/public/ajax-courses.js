jQuery(document).ready(function($){
	  
	$( 'body' ).delegate( '#courses-type', 'change', function(){	
			
		$('a.course-price').removeClass('active');
		$('a.course-price').first().addClass('active');
			
		var postid = $(this).attr('data-postid'),
			view_type = $('#dt-course-datas').attr('data-view_type'),
			price_type = '',
			courses_type = $(this).val(),
			offset = 0,
			curr_page = 1;

		loadCourses(postid, view_type, price_type, courses_type, offset, curr_page);

		return false;
		
	});
  
	$( 'body' ).delegate( '.course-price', 'click', function(){	
			
		$('#courses-type').val('all');	
		$('a.course-price').removeClass('active');
		$(this).addClass('active');
		
		var postid = $(this).attr('data-postid'),
			view_type = $('#dt-course-datas').attr('data-view_type'),
			price_type = $(this).attr('data-price_type'),
			courses_type = '',
			offset = 0,
			curr_page = 1;

		loadCourses(postid, view_type, price_type, courses_type, offset, curr_page);
			
		return false;
		
	});
	
	
	$( 'body' ).delegate( '.course-layout', 'click', function(){	
			
		$('a.course-layout').removeClass('active');
		$(this).addClass('active');
		
		var postid = $(this).attr('data-postid'),
			view_type = $(this).attr('data-view_type'),
			price_type = $('#dt-course-datas').attr('data-price_type'),
			courses_type = $('#dt-course-datas').attr('data-courses_type'),
			offset = $('#dt-course-datas').attr('data-offset'),
			curr_page = $('#dt-course-datas').attr('data-curr_page');

		loadCourses(postid, view_type, price_type, courses_type, offset, curr_page);
			
		return false;
		
	});

	$( 'body' ).delegate( '#ajax_tpl_course_content .pagination a', 'click', function(){	
			
		var postid = $('#dt-course-datas').attr('data-postid'),
			view_type = $('#dt-course-datas').attr('data-view_type'),
			price_type = $('#dt-course-datas').attr('data-price_type'),
			courses_type = $('#dt-course-datas').attr('data-courses_type'),
			postperpage = $('#dt-course-datas').attr('data-postperpage'),
			curr_page = $(this).text();
			
		if($(this).hasClass('dt-prev'))
			curr_page = parseInt($(this).attr('cpage'))-1;
		else if($(this).hasClass('dt-next'))
			curr_page = parseInt($(this).attr('cpage'))+1;
			
		if(curr_page == 1) var offset = 0;
		else if(curr_page > 1) var offset = ((curr_page-1)*postperpage);

		loadCourses(postid, view_type, price_type, courses_type, offset, curr_page);
			
		return false;
			
	});
	
	
	function loadCourses(postid, view_type, price_type, courses_type, offset, curr_page) {
	
		if (jQuery('body').hasClass('post-type-archive-dt_courses')) {
			var course_page_type = 'archive';
		} else if (jQuery('body').hasClass('tax-course_category')) {
			var course_page_type = 'tax-archive';
		} else if (jQuery('body').hasClass('page-template page-template-tpl-courses-php')) {
			var course_page_type = 'template';
		}
		
		$.ajax({
			type: "POST",
			url: mytheme_urls.framework_base_url + 'courses_utils.php',
			data:
			{
				post_id: postid,
				view_type: view_type,
				price_type: price_type,
				courses_type: courses_type,
				offset: offset,
				curr_page: curr_page,
				course_page_type: course_page_type
			},
			beforeSend: function(){
				$('#dt-sc-ajax-load-image').show();
			},
			error: function (xhr, status, error) {
				$('#ajax_tpl_course_content').html('Something went wrong!');
			},
			success: function (response) {
				$('#ajax_tpl_course_content').html(response);
			},
			complete: function(){
				$('#dt-sc-ajax-load-image').hide();
			} 
		});
	
	}
  
});