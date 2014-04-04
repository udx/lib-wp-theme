<?php
/**
 * Theme Settings
 *
 * @author Usability Dynamics, Inc. <info@usabilitydynamics.com>
 * @package Theme
 * @since 2.0.0
 */
namespace UsabilityDynamics\Theme {

  if( !class_exists( '\UsabilityDynamics\Theme\UI' ) ) {

    class UI extends \UsabilityDynamics\UI\Settings {
    
      /**
       * Create Settings Instance
       *
       * @param UsabilityDynamics\UI\Settings object $settings
       * @param array $args
       * @since 2.0.0
       */
      static function define( $settings, $args = false ) {
  
        $args = wp_parse_args( $args, array(
          'schema' => null,
          'path' => '/static/schemas/schema.ui.json',
          'l10n' => array(),
        ) );
      
        $l10n = apply_filters( 'ud:theme:ui:localization', $args[ 'l10n' ] );
        $schema = empty( $args[ 'schema' ] ) ? self::get_schema( $args[ 'path' ], $l10n ) : $args[ 'schema' ];
        
        // Instantiate Settings object
        $ui = new UI( $settings, array(
          'schema' => $schema,
        ) );

        // Return Instance.
        return $ui;

      }

      /**
       * Get default Settings from schema
       *
       */
      public function get_schema( $path, $l10n = array() ) {
        if( file_exists( $file = get_stylesheet_directory() . $path ) ) {
          return \UsabilityDynamics\Utility::l10n_localize( json_decode( file_get_contents( $file ), true ), (array)$l10n );
        }
        return NULL;
      }

    }

  }

}



