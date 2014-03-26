/**
 * Script Ran within iFrame when using Customizer.
 * Logic here is used to interact with the site preview in real-time.
 *
 * @author peshkov@ud
 */
 
( function( $, args ) {

  /**
   * Create Element for Hot Swapping Styles
   */
  function createStyleContainer( key ) {
    // console.log( 'createStyleContainer' );
    if( $( '#lib_wp_theme_customizer_' + key ).length ) {
      return null;
    }
    var _element = $( '<style type="text/css" id="lib_wp_theme_customizer_' + key + '"></style>' );
    // Create New Element and add to <head>
    $( 'head' ).append( _element );
    //console.log( '_element', _element );
  }

  /**
   * Update Dynamic Styles
   *
   * @param style
   */
  function updateStyles( c, style ) {
    // Oue dynamically generated style element
    var v = '';
    if( style && style.length > 0 ) {
      var v = c.selector + '{ ' + c.style + ':' + c.prefix + style + c.postfix + ' !important; }';
    }
    $( 'head #lib_wp_theme_customizer_' + c.mod_name ).text( v );
    
  }
  
  // Update Styles Live.
  $.each( args.settings, function( i, s ) {
    
    wp.customize( s.key, function( style ) {
      var intent;
      createStyleContainer( s.key );
      
      // Listen for Changes.
      style.bind( function ( style ) {
        //console.log( 'stylesChanged', s.key, style );
        // Clear Intent
        window.clearTimeout( intent );
        // Pause for Intent Check
        intent = window.setTimeout( function() {
          updateStyles( s.css, style );
        }, 200 );
      });

    });
    
  } );

} )( jQuery, _lib_wp_theme_customizer );

