<?php
/**
 * Theme Settings
 *
 * @author Usability Dynamics, Inc. <info@usabilitydynamics.com>
 * @package Theme
 * @since 2.0.0
 */
namespace UsabilityDynamics\Theme {

  if( !class_exists( 'UsabilityDynamics\Theme\Settings' ) ) {

    class Settings extends \UsabilityDynamics\Settings {
    
      /**
       * Create Settings Instance
       *
       * @since 2.0.0
       */
      static function define( $args = false ) {

        // Instantiate Settings object
        $_instance = new Settings( Utility::parse_args( $args, array(
          "store" => "options",
          "key"   => 'theme::' . ( wp_get_theme()->get( 'Name' ) ),
        )));

        // Prepare default data which is used for storing in DB.
        if( !$_instance->get() ) {
          $_instance->set( $_instance->_get_system_settings() );
        }

        // Return Instance.
        return $_instance;

      }
      
      /**
       * Get default Settings from schema
       *
       */
      private function _get_system_settings( $path = '/static/schemas/default.settings.json' ) {

        if( file_exists( $file = get_stylesheet_directory() . $path ) ) {
          return $this->_localize( json_decode( file_get_contents( $file ), true ) );
        }

        return array();

      }
      
      /**
       * Localization Functionality.
       *
       * Replaces array's l10n data.
       * Helpful for localization of data which is stored in JSON files ( see /schemas )
       *
       * @param type $data
       *
       * @return type
       * @author peshkov@UD
       */
      private function _localize( $data ) {

        if ( !is_array( $data ) && !is_object( $data ) ) {
          return $data;
        }

        //** The Localization's list. */
        $l10n = apply_filters( 'ud::theme::festival', array(
          //@TODO: replace all strings in schema files with l10n.{key} and set all locale data here. peshkov@UD
        ));

        //** Replace l10n entries */
        foreach( $data as $k => $v ) {
          if ( is_array( $v ) ) {
            $data[ $k ] = self::_localize( $v );
          } elseif ( is_string( $v ) ) {
            if ( strpos( $v, 'l10n' ) !== false ) {
              preg_match_all( '/l10n\.([^\s]*)/', $v, $matches );
              if ( !empty( $matches[ 1 ] ) ) {
                foreach ( $matches[ 1 ] as $i => $m ) {
                  if ( key_exists( $m, $l10n ) ) {
                    $data[ $k ] = str_replace( $matches[ 0 ][ $i ], $l10n[ $m ], $data[ $k ] );
                  }
                }
              }
            }
          }
        }

        return $data;
      }

    }

  }

}



