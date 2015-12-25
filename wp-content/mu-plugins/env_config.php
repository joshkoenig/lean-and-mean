<?php
/*
  Plugin Name: Environment Configuration
  Plugin URI: https://pantheon.io/docs/articles/wordpress/configuring-wp-config-php/
  Description: Automatically setting environment configuration.
  Version: 0.1
  Author: This Site's Developers
*/
if ( isset( $_ENV['PANTHEON_ENVIRONMENT'] ) ) :
add_filter( 'pre_option_wpghs_oauth_token', '_env_config_wpghs_oauth_token' );
function _env_config_wpghs_oauth_token( $wpghs_oauth_token ) {
  if ( $_ENV['PANTHEON_ENVIRONMENT'] == 'live' ) {
    return $real_value;
  }
  return null;
}
endif; # Ensuring that this only executes on Pantheon.