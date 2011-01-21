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

class Structure_channel_upd {

	var $version		= '1.0.0';
	
	/**
	 * Constructor
	 */
	function Structure_channel_upd()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Module Installer
	 *
	 * @access	public
	 * @return	bool
	 */
	function install()
	{
		$data = array(
					'module_name' => 'Structure_channel',
					'module_version' => $this->version,
					'has_cp_backend' => 'n'
					);

		$this->EE->db->insert('modules', $data);
		
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Module Uninstaller
	 *
	 * @access	public
	 * @return	bool
	 */
	function uninstall()
	{
		$this->EE->db->select('module_id');
		$this->EE->db->from('modules');
		$this->EE->db->where('module_name', 'Structure_channel');
		$query = $this->EE->db->get();

		$this->EE->db->delete('module_member_groups', array('module_id' => $query->row('module_id')));
		$this->EE->db->delete('modules', array('module_name' => 'Structure_channel'));

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Module Updater
	 *
	 * @access	public
	 * @return	bool
	 */
	function update($current = '')
	{
		return FALSE;
	}

}
// END CLASS

/* End of file upd.structure_channel.php */
/* Location: ./system/expressionengine/modules/structure_channel/upd.structure_channel.php */