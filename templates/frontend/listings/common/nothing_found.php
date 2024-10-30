<?php
defined( 'ABSPATH' ) || exit;

echo '<div class="col-md-12"><div class="lazy-section nothing-found-container"><i class="fas fa-search"></i><div>';
echo '<h2>' . esc_html__( 'Nothing Found', 'clasify-classified-listing' ) . '</h2>';
echo '<p>' . esc_html__( 'Uh oh, we can\'t seem to find the listing you\'re looking for. Try going back to previous page or contact us for more information', 'clasify-classified-listing' ) . '</p>';
echo '</div></div></div>';
