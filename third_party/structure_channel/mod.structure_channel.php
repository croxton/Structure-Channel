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

class Structure_channel extends Channel
{
	public $offset;
	public $pagination_param;
	
	/**
	 * Constructor
	 */
	function Structure_channel()
	{
		parent::__construct();
		
		// pagination parameter, defaults to 'p'
		$this->pagination_param = $this->EE->TMPL->fetch_param('pagination_param', 'p');
		
		// determine the offset
		if ($this->offset = filter_var($this->EE->input->get($this->pagination_param, TRUE), FILTER_SANITIZE_NUMBER_INT));
		{	
			// override 'offset' tag paramater
			$this->EE->TMPL->tagparams['offset'] = $this->offset;
		}
	}
	
	/**
	 * Create pagination 
	 * 
	 * @access	public
	 * @see 	parent::create_pagination
	 * @param	int 	$count
	 * @param	string 	$query
	 * @return	bool
	 */
	function create_pagination($count = 0, $query = '')
	{
		if (is_object($query))
		{
			$row = $query->row_array();
		}
		else
		{
			$row = '';
		}

		// -------------------------------------------
		// 'channel_module_create_pagination' hook.
		//  - Rewrite the pagination function in the Channel module
		//  - Could be used to expand the kind of pagination available
		//  - Paginate via field length, for example
		//
			if ($this->EE->extensions->active_hook('channel_module_create_pagination') === TRUE)
			{
				$edata = $this->EE->extensions->universal_call('channel_module_create_pagination', $this);
				if ($this->EE->extensions->end_script === TRUE) return;
			}
		//
		// -------------------------------------------
		
		if ($this->paginate == TRUE)
		{
			
			/* --------------------------------------
			/*  For subdomain's or domains using $template_group and $template
			/*  in path.php, the pagination for the main index page requires
			/*  that the template group and template are specified.
			/* --------------------------------------*/

			if (($this->EE->uri->uri_string == '' OR $this->EE->uri->uri_string == '/') && $this->EE->config->item('template_group') != '' && $this->EE->config->item('template') != '')
			{
				$this->basepath = $this->EE->functions->create_url($this->EE->config->slash_item('template_group').'/'.$this->EE->config->item('template'));
			}
			
			if ($this->basepath == '')
			{
				$this->basepath = $this->EE->functions->create_url($this->EE->uri->uri_string);

				if (preg_match("#^P(\d+)|/P(\d+)#", $this->query_string, $match))
				{
					$this->p_page = (isset($match[2])) ? $match[2] : $match[1];
					$this->basepath = $this->EE->functions->remove_double_slashes(str_replace($match[0], '', $this->basepath));
				}
			}

			//  Standard pagination - base values

			if ($this->field_pagination == FALSE)
			{	
				if ($this->display_by == '')
				{
					if ($count == 0)
					{
						$this->sql = '';
						return;
					}

					$this->total_rows = $count;
				}

				if ($this->dynamic_sql == FALSE)
				{
					$cat_limit = FALSE;
					if ((in_array($this->reserved_cat_segment, explode("/", $this->EE->uri->uri_string))
						AND $this->EE->TMPL->fetch_param('dynamic') != 'no'
						AND $this->EE->TMPL->fetch_param('channel'))
						OR (preg_match("#(^|\/)C(\d+)#", $this->EE->uri->uri_string, $match) AND $this->EE->TMPL->fetch_param('dynamic') != 'no'))
					{
						$cat_limit = TRUE;
					}

					if ($cat_limit AND is_numeric($this->EE->TMPL->fetch_param('cat_limit')))
					{
						$this->p_limit = $this->EE->TMPL->fetch_param('cat_limit');
					}
					else
					{
						$this->p_limit  = ( ! is_numeric($this->EE->TMPL->fetch_param('limit')))  ? $this->limit : $this->EE->TMPL->fetch_param('limit');
					}
				}

				$this->p_page = ($this->p_page == '' OR ($this->p_limit > 1 AND $this->p_page == 1)) ? 0 : $this->p_page;

				if ($this->p_page > $this->total_rows)
				{
					$this->p_page = 0;
				}
								
				$this->current_page = floor(($this->p_page / $this->p_limit) + 1);
				$this->total_pages = intval(floor($this->total_rows / $this->p_limit));				
			}
			else
			{
				//  Field pagination - base values

				if ($count == 0)
				{
					$this->sql = '';
					return;
				}

				$m_fields = array();

				foreach ($this->multi_fields as $val)
				{
					foreach($this->cfields as $site_id => $cfields)
					{
						if (isset($cfields[$val]))
						{
							if (isset($row['field_id_'.$cfields[$val]]) AND $row['field_id_'.$cfields[$val]] != '')
							{
								$m_fields[] = $val;
							}
						}
					}
				}

				$this->p_limit = 1;

				$this->total_rows = count($m_fields);

				$this->total_pages = $this->total_rows;

				if ($this->total_pages == 0)
					$this->total_pages = 1;

				$this->p_page = ($this->p_page == '') ? 0 : $this->p_page;

				if ($this->p_page > $this->total_rows)
				{
					$this->p_page = 0;
				}

				$this->current_page = floor(($this->p_page / $this->p_limit) + 1);

				if (isset($m_fields[$this->p_page]))
				{
					$this->EE->TMPL->tagdata = preg_replace("/".LD."multi_field\=[\"'].+?[\"']".RD."/s", LD.$m_fields[$this->p_page].RD, $this->EE->TMPL->tagdata);
					$this->EE->TMPL->var_single[$m_fields[$this->p_page]] = $m_fields[$this->p_page];
				}
			}
			
			// add the current offset to total rows
			$this->total_rows += $this->offset;	
			$this->total_pages = floor($this->total_rows / $this->p_limit);
			$this->current_page = ceil($this->offset / $this->p_limit) + 1;

			//  Create the pagination
			if ($this->total_rows > 0 && $this->p_limit > 0)
			{
				if ($this->total_rows % $this->p_limit)
				{
					$this->total_pages++;
				}				
			}

			if ($this->total_rows > $this->p_limit)
			{
				$this->EE->load->library('pagination');

				if (strpos($this->basepath, SELF) === FALSE && $this->EE->config->item('site_index') != '')
				{
					$this->basepath .= SELF;
				}

				if ($this->EE->TMPL->fetch_param('paginate_base'))
				{
					// Load the string helper
					$this->EE->load->helper('string');

					$this->basepath = $this->EE->functions->create_url(trim_slashes($this->EE->TMPL->fetch_param('paginate_base')));
				}
				
				$config['base_url']		= $this->basepath;
				$config['prefix']		= '';
				$config['total_rows'] 	= $this->total_rows;
				$config['per_page']		= $this->p_limit;
				$config['cur_page']		= $this->offset;
				$config['first_link'] 	= $this->EE->lang->line('pag_first_link');
				$config['last_link'] 	= $this->EE->lang->line('pag_last_link');
				
				// enable query strings so that pagination links work with Structure URIs
				$config['page_query_string'] = true;
				$config['query_string_segment'] = $this->pagination_param;
				
				// Allows $config['cur_page'] to override
				$config['uri_segment'] = 0;

				$this->EE->pagination->initialize($config);
				$this->pagination_links = $this->EE->pagination->create_links();				
				
				// previous / next page links
				if ((($this->total_pages * $this->p_limit) - $this->p_limit) > $this->offset)
				{
					$this->page_next = reduce_double_slashes($this->basepath.'&amp;'.$this->pagination_param.'='.($this->offset + $this->p_limit));
				}

				if (($this->offset - $this->p_limit ) >= 0)
				{
					$this->page_previous = reduce_double_slashes($this->basepath.'&amp;'.$this->pagination_param.'='.($this->offset - $this->p_limit));
				}
			}
			else
			{
				$this->p_page = '';
			}
		}
	}

}

/* End of file mod.structure_channel.php */
/* Location: ./system/expressionengine/modules/structure_channel/mod.structure_channel.php */