<?php

/**
 * 12-345678
 *
 * @package Carrington Build
 */
if (!class_exists('cfct_row_a_bcd')) {

  if ( !class_exists( '__block_c8_345678' ) ) {
    class __block_c8_345678 extends cfct_block {
      public function __construct( $classes = array() ) {
        $this->add_classes( array( 'col-md-9', 'column' ) );
        parent::__construct( $classes );
      }
    }
  }

	class cfct_row_a_bcd extends cfct_build_row {
		protected $_deprecated_id = 'row-a-bcd'; // deprecated property, not needed for new module development

		public function __construct() {
			$config = array(
				'name' => __('Left Sidebar 25%', 'carrington-build'),
				'description' => __('2 Columns. The second column is wider than the first.', 'carrington-build'),
				'icon' => plugins_url( '/icon.png', __DIR__ )
			);

			/* Filters in rows used to be keyed by the single classname
			that was registered for the class. Maintain backwards
			compatibility for filters by setting modifier for this row to
			the old classname property. */
			$this->set_filter_mod('cfct-row-a-bcd');

			$this->add_classes(array('row-c6-12-345678'));

			$this->push_block(new __block_c8_12);
			$this->push_block(new __block_c8_345678);

			parent::__construct($config);
		}
	}

}

?>