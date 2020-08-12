<?php
class MxBestandliveCRUD
{

	private $_get = NULL;

	private $_post = NULL;

	function __construct( $_get, $_post )
	{

		if( count( $_get ) !== 0 ) {

			$this->_get = $_get;
		}

		if( count( $_post ) !== 0 ) {

			$this->_post = $_post;
		}

	}

	// get results
	public function mx_get_results_b() {

		if( $this->_get !== NULL || $this->_post !== NULL ) return false;

		global $wpdb;

		$res = $wpdb->get_results( "SELECT * FROM bestandlive ORDER BY product_id DESC" );

		if( count( $res ) == 0 ) {
			return false;
		} else {
			return $res;
		}			

	}

	// edit item
	public function mx_edit_item_b() {

		if( ! isset( $this->_get['edit'] ) ) return false;

		if( $this->_post !== NULL ) return false;

		global $wpdb;

		$product_id = $this->_get['edit'];

		$row = $wpdb->get_row( "SELECT * FROM bestandlive WHERE product_id = $product_id" );

		return $row;

	}

	// save data
	public function mx_save_data_b() {

		if( $this->_post == NULL ) return false;

		if( ! isset( $this->_get['edit'] ) ) return false;

		return $this->_post;

	}

	// add item
	public function mx_add_item_b() {

		if( ! isset( $this->_get['add_item'] ) ) return false;

		return 'add';

	}

	// save a new item
	public function mx_save_item_b() {

		if( ! isset( $this->_get['save_item'] ) ) return false;

		return 'save_new_item';

	}

	// remove item
	public function mx_remove_item_b() {

		if( ! isset( $this->_get['delete'] ) ) return false;

		if( $this->_post == NULL ) return false;

		return 'delete';

	}
	
}
