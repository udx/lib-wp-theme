/**
 * Script Ran within iFrame when using Customizer.
 * Logic here is used to interact with the site preview in real-time.
 *
 * @author peshkov@ud
 */
 
( function( $, args ) {

  console.log( args );

  /**
   * Create Element for Hot Swapping Styles
   */
  function createStyleContainer() {
    // console.log( 'createStyleContainer' );
    if( $( '#lib_wp_theme_customizer' ).length ) {
      return null;
    }
    var _element = $( '<style type="text/css" id="lib_wp_theme_customizer"></style>' );
    // Create New Element and add to <head>
    $( 'head' ).append( _element );
    // console.log( '_element', _element );
  }

  /**
   * Update Dynamic Styles
   *
   * @param style
   */
  function updateStyles( style ) {
    // Oue dynamically generated style element
    $( 'head #lib_wp_theme_customizer' ).text( style );
  }
  
  
  /*
  // Update Styles Live.
  wp.customize( args.name, function( style ) {
    var intent;
    createStyleContainer();
    
    // Listen for Changes.
    style.bind( function ( style ) {
      //console.log( 'stylesChanged', style );
      // Clear Intent
      window.clearTimeout( intent );
      // Pause for Intent Check
      intent = window.setTimeout( function() {
        updateStyles( style );
      }, 200 );
    });

  });
  //*/

} )( jQuery, _lib_wp_theme_customizer );

