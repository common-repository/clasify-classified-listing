<?php
do_action( 'cl_before_template_' . $template_name );
esc_html_e( 'Admin Extensions Page', 'clasify-classified-listing' );
do_action( 'cl_after_template_' . $template_name );
