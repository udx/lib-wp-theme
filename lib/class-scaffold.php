<?php
/**
 * Theme Scaffolding.
 *
 * @author team@UD
 * @version 0.2.5
 * @namespace UsabilityDynamics
 * @module Theme
 * @author potanin@UD
 */
namespace UsabilityDynamics\Theme {

  if( !class_exists( '\UsabilityDynamics\Theme\Scaffold' ) ) {

    /**
     * Scaffold Class
     *
     * @class Scaffold
     * @author potanin@UD
     */
    class Scaffold {

      /**
       * Theme ID.
       *
       * @param $id
       * @var string
       */
      public $id;

      /**
       * Theme Version.
       *
       * @param $version
       * @var string
       */
      public $version;

      /**
       * Theme Text Domain.
       *
       * @param $domain
       * @var string
       */
      public $domain;

      /**
       * Theme Settings.
       *
       * @param $settings
       * @var string
       */
      public $settings;

      /**
       * Structure.
       *
       * @param $structure
       * @var string
       */
      public $structure;

      public function __construct() {
      }

      /**
       * Initializes Theme.
       *
       * @param array $options
       */
      public function initialize( $options = array() ) {

        if( !$this->id ) {
          _doing_it_wrong( 'UsabilityDynamics\Theme\Scaffold::initialize', 'Theme ID not specified.' );
        }

        // Initialize Settings.
        $this->settings = Settings::define(array(
          'id' => $this->id,
          'version' => $this->version,
          'domain' => $this->domain,
          'data' => array(
            '_option_keys' => array(
              'version' => $this->id . ':_version',
              'settings' => $this->id . ':_settings',
            )
          )
        ));

        $options = (object) Utility::extend( $options, array(
          'domain' => $this->domain
        ));

        // Set Instance Settings.
        $this->set( '_initialize', $options );

        add_filter( 'pre_update_option_rewrite_rules', array( $this, '_update_option_rewrite_rules' ), 1 );
        add_action( 'query_vars', array( $this, '_query_vars' ) );
        add_action( 'template_redirect', array( $this, '_redirect' ) );

        // @example http://discodonniepresents.com/manage/?debug=debug_rewrite_rules
        if( is_admin() && @$_GET[ 'debug' ] === 'debug_rewrite_rules' ) {
          die( json_encode(get_option( 'rewrite_rules' )) );
        }

        $this->_upgrade();

      }

      /**
       * Create Theme Settings Instance.
       *
       * @param array $args
       * @param array $data
       *
       * @return mixed
       */
      public function settings( $args = array(), $data = array() ) {

        return $this->settings;
      }

      /**
       * Handle Script Rewrites.
       *
       * @param array $options
       */
      public function scripts( $options = array() ) {

        foreach( (array) $options as $name => $_settings ) {

          $settings = array(
            'name' => $name,
            'url' => '',
            'version' => $this->version,
            'footer' => true,
            'deps' => array()
          );

          if( is_array( $_settings ) ) {
            $settings = (object) Utility::extend( $settings, $_settings );
          }

          if( is_string( $_settings ) ) {

            $settings = (object) Utility::extend( $settings, array(
              'name' => $name,
              'url' => $_settings,
              'version' => $this->version,
              'footer' => true,
              'deps' => array()
            ));

          }

            wp_register_script( $settings->name, $settings->url, $settings->deps, $settings->version, $settings->footer );

        }

      }

      /**
       * Handle Style Rewrites.
       *
       * @param array $options
       */
      public function styles( $options = array() ) {
        foreach( (array) $options as $name => $_settings ) {

          $settings = array(
            'name' => $name,
            'url' => '',
            'version' => $this->version,
            'footer' => true,
            'deps' => array()
          );

          if( is_array( $_settings ) ) {
            $settings = (object) Utility::extend( $settings, $_settings );
          }

          if( is_string( $_settings ) ) {
            $settings = (object) Utility::extend( $settings, array(
              'name' => $name,
              'url' => $_settings
            ) );
          }

          wp_register_style( $settings->name, $settings->url, $settings->deps, $settings->version, $settings->footer );

        }

      }

      /**
       * Handle Font Rewrties.
       *
       * @param array $options
       */
      public function fonts( $options = array() ) {


      }

      /**
       * Management Page / Interface.
       *
       * @param array $options
       */
      public function manage( $options = array() ) {

        //$settings->set( 'pages.manage', add_dashboard_page( __( 'Manage', HDDP ), __( 'Manage', HDDP ), $hddp[ 'manage_options' ], 'hddp_manage', array( 'UsabilityDynamics\Disco', 'hddp_manage' ) ) );

      }

      /**
       * Configures API/RPC Methods.
       *
       * @param array $options
       */
      public function api( $options = array() ) {

      }

      /**
       * Declare UDX Models / Scripts.
       *
       * * Adds cdn.udx.io script tag to <head>
       *
       * @param array $args
       */
      public function requires( $args = array() ) {

        $args = Utility::defaults( $args, array(
          'bootstrap' => true
        ));

        $this->requires = new Requires( $args );

      }

      /**
       * Configure Carrington Builder.
       *
       * @example
       *
       *      $this->carrington->add_module_style( 'polaroid', home_url( '/images/style-polaroid.jpg' ), 'cfct_module_callout' );
       *
       * @param array $args
       */
      public function carrington( $args = array() ) {

        $args = Utility::defaults( $args, array(
          'bootstrap' => true
        ));

        $this->carrington = new Carrington( $args );

        //$this->carrington->template->register_type('module', $classname, $args);
        //$this->carrington->template->deregister_type('module', $classname);
        //$this->carrington->template->register_type('row', $classname);
        //$this->carrington->template->deregister_type('row', $classname);
        //cfct_module_options::get_instance()->register($classname);
        //cfct_module_options::get_instance()->deregister($classname);

      }

      /**
       * Add Header Tag.
       *
       *
       */
      public function head( $options = array() ) {

        // Save "head" options.
        $this->set( '_head', (object) $options );

        add_action( 'wp_head', array( $this, 'wp_head' ) );

      }

      /**
       * Print Head Tags.
       *
       * @since 0.2.5
       * @author potanin@UD
       * @method wp_head
       */
      public function wp_head() {

        $output = array();

        foreach( (array) $this->get( '_head' ) as $data ) {

          $attributes = array();

          foreach( $data as $key => $value ) {
            if( $key != 'tag' ) {
              $attributes[] = $key . '="' . $value . '"';
            }
          }

          $output[] = '<' . $data[ 'tag' ] . ' ' . implode( ' ', $attributes ) . '></' . $data[ 'tag' ] . '>';

        }

        echo implode( "\n", $output );

      }

      /**
       * Declare Data Structure.
       *
       * @param array $options
       *
       * @return array|bool
       */
      public function structure( $options = array() ) {
        
        $options = wp_parse_args( $options, array(
          'types' => array(), // Custom post types
          'meta' => array(), // Meta fields. The list of arrays. Every meta array is set of /RW_Meta_Box field attributes
          'taxonomies' => array(), // Taxonomies
        ) );
        
        $this->structure = \UsabilityDynamics\Structure::define( $options );
        
        return $this->structure;
      }
      
      /**
       * Returns post data including meta data specified in structure
       *
       * @author peshkov@UD
       */
      public function get_post( $post_id, $filter = false ) {
    
        $post = get_post( $post_id, ARRAY_A, $filter );
      
        if( $post && !is_wp_error( $post ) && key_exists( $post[ 'post_type' ], (array)$this->structure ) ) {
        
          // Get meta data
          foreach( (array)$this->structure[ $post[ 'post_type' ] ][ 'meta' ] as $key ) {
            $post[ $key ] = get_post_meta( $post_id, $key, true );
          }
        
        }
      
        return $post;
      }

      /**
       * Configure Activation, Deactivation, Installation and Upgrade Handling.
       *
       * @param array $options
       */
      public function upgrade( $options = array() ) {

      }

      /**
       * Enables Customizer Interface for Settings.
       *
       * @param array $options
       */
      public function customizer( $options = array() ) {

        // @temp
        add_action( 'customize_register', function( $wp_customize ) {
          $wp_customize->remove_section( 'title_tagline' );
          $wp_customize->remove_section( 'static_front_page' );
          $wp_customize->remove_section( 'nav' );
        });

        foreach( (array) $options as $key => $config ) {
          // add_theme_support( $key );
        }

      }

      /**
       * Register Menus
       *
       * @param array $options
       */
      public function menus( $options = array() ) {

        foreach( (array) $options as $name => $config ) {

          if( $config && is_array( $config ) ) {
            register_nav_menu( $name, $config[ 'name' ] );
          }

          if( !$config || is_null( $config ) ) {
            unregister_nav_menu( $name );
          }

        }

      }

      /**
       * Enables Theme Support for Features.
       *
       * @param array $options
       */
      public function supports( $options = array() ) {

        foreach( (array) $options as $feature => $config ) {

          if( $config && is_array( $config ) ) {
            add_theme_support( $feature, $config );
          }

          if( !$config || is_null( $config ) ) {
            remove_theme_support( $feature );
          }

        }

      }

      /**
       * Configures Image Sizes.
       *
       * @param array $options
       * @return array
       */
      public function media( $options = array() ) {
        global $_wp_additional_image_sizes;

        foreach( (array) $options as $name => $settings ) {

          if( $name === 'post-thumbnail' ) {
            add_theme_support( 'post-thumbnails' );
          }

          $_wp_additional_image_sizes[ $name ] = array_filter(array(
            'description' => isset( $settings[ 'description' ] ) ? $settings[ 'description' ]  : '',
            'post_types' => isset( $settings[ 'post_types' ] ) ? $settings[ 'post_types' ] : array( 'page' ),
            'width' => isset( $settings[ 'width' ] ) ? absint( $settings[ 'width' ] ) : null,
            'height' => isset( $settings[ 'height' ] ) ? absint( $settings[ 'height' ] ) : null,
            'crop' => isset( $settings[ 'crop' ] ) ? (bool) $settings[ 'crop' ] : false
          ));

        }

        return $options;

      }

      /**
       * Set Theme Option.
       *
       * @param $key
       * @param $value
       */
      public function set( $key = null, $value = null ) {
        return $this->settings->set( $key, $value );
      }

      /**
       * Get Theme Option.
       *
       * @param $key
       * @param $default
       */
      public function get( $key = null, $default = null ) {
        return $this->settings->get( $key, $default );
      }

      /**
       * Modify Rewrite Ruels on Save.
       *
       * @param $value
       *
       * @return array
       */
      public function _update_option_rewrite_rules( $rules ) {

        // Define New Rules.
        $new_rules = array(
          '^assets/styles/([^/]+)/?'  => 'index.php?is_asset=1&asset_type=style&asset_slug=$matches[1]',
          '^assets/images/([^/]+)/?'  => 'index.php?is_asset=1&asset_type=image&asset_slug=$matches[1]',
          '^assets/scripts/([^/]+)/?' => 'index.php?is_asset=1&asset_type=script&asset_slug=$matches[1]',
          '^assets/models/([^/]+)/?'  => 'index.php?is_asset=1&asset_type=model&asset_slug=$matches[1]'
        );

        // Return concatenated rules.
        return $new_rules + $rules;

      }

      /**
       * Modify Query Rules.
       *
       * @param $query_vars
       *
       * @return array
       */
      public function _query_vars( $query_vars ) {

        $query_vars[] = 'asset_type';
        $query_vars[] = 'asset_slug';
        $query_vars[] = 'is_asset';

        return $query_vars;

      }

      /**
       * Handle Asset Redirection.
       *
       * @param $query_vars
       */
      public function _redirect( $query_vars ) {
        global $wp_query;

        $asset_slug = get_query_var( 'asset_slug' );
        $asset_type = get_query_var( 'asset_type' );

        if( !get_query_var( 'is_asset' ) ) {
          return;
        }

        if( is_file( $_path = trailingslashit( get_stylesheet_directory() ) . trailingslashit( get_query_var( 'asset_type' ) . 's' ) . get_query_var( 'asset_slug' ) ) ) {
          $_data = file_get_contents( $_path );
        };

        if( get_query_var( 'asset_type' ) === 'script' ) {
          $this->_serve_public( 'script', get_query_var( 'asset_slug' ), $_data );
        }

        if( get_query_var( 'asset_type' ) === 'image' ) {
          $this->_serve_public( 'image', get_query_var( 'asset_slug' ), $_data );
        }

        if( get_query_var( 'asset_type' ) === 'style' ) {
          $this->_serve_public( 'style', get_query_var( 'asset_slug' ), $_data );
        }

        if( get_query_var( 'asset_type' ) === 'model' ) {
          $this->_serve_public( 'model', get_query_var( 'asset_slug' ), $_data );
        }

      }

      /**
       * Serve Public Assets.
       *
       *
       * @example
       *
       *    add_filter( 'udx:theme:public:script', 'custom script content' );
       *    add_filter( 'udx:theme:public:style', 'custom script content' );
       *    add_filter( 'udx:theme:public:model', 'custom script content' );
       *
       * @param string $type
       * @param string $data
       */
      private function _serve_public( $type = '', $name, $data = '' ) {

        // Configure Data.
        $data = apply_filters( 'udx:theme:public:' . $type . ':data', $data, $name );

        // Configure Headers.
        $headers = apply_filters( 'udx:theme:public:' . $type . 'headers', array(
            'Cache-Control'   => 'public',
            'Pragma'   => 'cache',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Vary'            => 'Accept-Encoding'
          ));

        if( $type === 'script' ) {
          $headers[ 'Content-Type' ] = isset( $headers[ 'Content-Type' ] ) && $headers[ 'Content-Type' ] ? $headers[ 'Content-Type' ] : 'application/javascript; charset=' . get_bloginfo( 'charset' );
        }

        if( $type === 'style' ) {
          $headers[ 'Content-Type' ] = isset( $headers[ 'Content-Type' ] ) && $headers[ 'Content-Type' ] ? $headers[ 'Content-Type' ] : 'text/css; charset=' . get_bloginfo( 'charset' );
        }

        if( $type === 'image' ) {
          $headers[ 'Content-Type' ] = isset( $headers[ 'Content-Type' ] ) && $headers[ 'Content-Type' ] ? $headers[ 'Content-Type' ] : 'image/png; charset=' . get_bloginfo( 'charset' );
        }

        if( $type === 'model' ) {
          $headers[ 'Content-Type' ] = isset( $headers[ 'Content-Type' ] ) && $headers[ 'Content-Type' ] ? $headers[ 'Content-Type' ] : 'application/json; charset=' . get_bloginfo( 'charset' );
        }

        // Set Headers.
        foreach( (array) $headers as $name => $field_value ) {
          @header( "{$name}: {$field_value}" );
        }

        // WordPress will try to make it 404.
        http_response_code( 200 );

        // Output Data.
        die( $data );

      }

      /**
       * Handles Theme Activation.
       *
       */
      private function _activate() {

      }

      /**
       * Handles Theme Deactivation.
       *
       */
      private function _deactivate() {

      }

      /**
       * Handles Theme Installation.
       *
       */
      private function _install() {

        // Flush Rules.
        flush_rewrite_rules();

        // Update installed verison.
        update_option( $this->get( '_option_keys.version' ), $this->version );

        // wp_die( 'installed' );

      }

      /**
       * Handles Theme Upgrades.
       *
       */
      private function _upgrade() {

        // Get Installed Version.
        $_installed = get_option( $this->get( '_option_keys.version' ) );

        // Not Instlled.
        if( !$_installed ) {
          $this->_install();
        }

        // Upgrade Needed.
        if( version_compare( $this->version, $_installed, '>' ) ) {

          // Flush Rules.
          flush_rewrite_rules();

          // Update installed verison.
          update_option( $this->get( '_option_keys.version' ), $this->version );

          // wp_die( 'upgrded' );

        }

      }

    }

  }

}