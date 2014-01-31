<?php

class cfct_block_c12_7 extends cfct_block {
  public function __construct( $classes = array() ) {
    $this->add_classes( array( 'col-md-7' ) );
    parent::__construct( $classes );
  }
}

class cfct_block_c12_2 extends cfct_block {
  public function __construct( $classes = array() ) {
    $this->add_classes( array( 'col-md-2' ) );
    parent::__construct( $classes );
  }
}

class cfct_block_c12_3 extends cfct_block {
  public function __construct( $classes = array() ) {
    $this->add_classes( array( 'col-md-3' ) );
    parent::__construct( $classes );
  }
}

/**
 * 2 Column Row, Column 2 is wide
 *
 * @package Carrington Build
 */
class row_two_sidebars extends cfct_build_row {

  public function __construct() {
    $config = array(
      'name'        => __( 'Dual Sidebars', 'carrington-build' ),
      'description' => __( '2 Columns. The second column is wider than the first.', 'carrington-build' ),
      'icon'        => get_stylesheet_directory_uri() . '/lib/row-two-sidebars/icon.png'
    );

    $this->push_block( new cfct_block_c12_7 );
    $this->push_block( new cfct_block_c12_2 );
    $this->push_block( new cfct_block_c12_3 );

    parent::__construct( $config );
  }
}

cfct_build_register_row( 'row_two_sidebars' );


