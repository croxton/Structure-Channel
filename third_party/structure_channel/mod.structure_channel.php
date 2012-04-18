<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Channel module subclass for use with Structure module
 *
 * This file must be in your /system/third_party/structure_channel directory of your ExpressionEngine installation
 *
 * @package		ExpressionEngine
 * @subpackage	Structure Channel
 * @author		Mark Croxton (mcroxton@hallmark-design.co.uk)
 * @link		http://hallmark-design.co.uk
 */

if ( ! class_exists('Channel'))
{
	include APPPATH.'modules/channel/mod.channel'.EXT;
}

class Structure_channel extends Channel {
	
	public $offset;
	public $pagination_param;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// pagination parameter, defaults to 'p', e.g. ?p=15
		$this->pagination_param = $this->EE->TMPL->fetch_param('pagination_param', 'p');
		
		// pass the offset to the pagnation object
		$this->pagination->offset = filter_var($this->EE->input->get($this->pagination_param, TRUE), FILTER_SANITIZE_NUMBER_INT);
		
		// make sure the loader object knows where to find our pagination library
		$this->EE->load->add_package_path(PATH_THIRD.'structure_channel');
		
		// load new pagination class and overload the EE one
		$this->EE->load->library('structure_channel_pagination');
		$this->EE->pagination = new Structure_channel_pagination($this->pagination_param);	
	}
}

/* End of file mod.structure_channel.php */
/* Location: ./system/expressionengine/modules/structure_channel/mod.structure_channel.php */