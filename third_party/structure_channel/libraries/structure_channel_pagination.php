<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine Structure Channel Pagination Class
 *
 * @package		ExpressionEngine
 * @subpackage	Structure Channel
 * @category	Pagination
 * @author		Mark Croxton (mcroxton@hallmark-design.co.uk)
 * @link		http://hallmark-design.co.uk
 */

class Structure_channel_pagination extends CI_Pagination {
	
	public function __construct($pagination_param = 'p')
	{
		$this->EE =& get_instance();
		parent::__construct();
		
		$this->query_string_segment = $pagination_param;
		$this->page_query_string = TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Instantiate a new pagination object
	 * 
	 * @return object 
	 */
	public function create($classname)
 	{
  		return new Pagination_object($classname);
 	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Create's an array of pagination links including the first, previous,
	 * next, and last page links
	 * 
	 * @return array Associative array ready to go straight into EE's 
	 * template parser
	 */
	public function create_link_array()
	{
		$this->prefix = '';		
		return parent::create_link_array();
	}	
}
// END Structure_channel_pagination class

/* End of file Structure_channel_pagination.php */
/* Location: ./system/expressionengine/third_party/structure_channel/libraries/Structure_channel_pagination.php */