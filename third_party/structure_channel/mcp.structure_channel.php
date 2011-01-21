<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Channel module subclass for use with Structure module
 *
 * This file must be in your /system/third_party/structure_channel directory of your ExpressionEngine installation
 *
 * @package             Structure Channel
 * @author              Mark Croxton (mcroxton@hallmark-design.co.uk)
 * @copyright			Copyright (c) 2010 Hallmark Design
 * @link                http://hallmark-design.co.uk
 */

// --------------------------------------------------------------------

class Structure_channel_mcp {

	var $stats_cache	= array(); // Used by mod.stats.php

	/**
	 * Constructor
	 */
	function Structure_channel_mcp()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}
}
// END CLASS

/* End of file mcp.structure_channel.php */
/* Location: ./system/expressionengine/modules/structure_channel/mcp.structure_channel.php */