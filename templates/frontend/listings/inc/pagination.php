<?php
$LISTING_Query		= CCP()->front->query->get_listing_query();
$posts_pagination	= the_posts_pagination(
	array(
		'total'        => $LISTING_Query->max_num_pages,
		'mid_size'  => 1,
		'prev_text' => '<span class="fas fa-arrow-left"></span>',
		'next_text' => '<span class="fas fa-arrow-right"></span>',
	)
);
