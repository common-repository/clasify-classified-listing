<?php
if (!defined('ABSPATH')) exit;
$provider   = CCP()->front->listing_provider;
$price      = $provider->get_meta_data('clasify_classified_plugin_pricing', get_the_ID());
echo "<h2>{$price}</h2>";
