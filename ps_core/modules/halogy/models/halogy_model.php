<?php
/**
 * Halogy
 *
 * A user friendly, modular content management system for PHP 5.0
 * Built on CodeIgniter - http://codeigniter.com
 *
 * @package		Halogy
 * @author		Haloweb Ltd
 * @copyright	Copyright (c) 2012, Haloweb Ltd
 * @license		http://halogy.com/license
 * @link		http://halogy.com/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

class Halogy_model extends CI_Model {
	var $uri_assoc_segment = 4;
	
	function __construct()
	{
		parent::__construct();

		// get siteID, if available
		if (defined('SITEID'))
		{
			$this->siteID = SITEID;
		}
	}	

	function get_num_page_views()
	{
		// grab
		$this->db->select('sum(views) as count');
		$this->db->where('siteID', $this->siteID);
		$this->db->where('deleted', 0);
		$this->db->where('active', 1);
		$query = $this->db->get('pages');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['count'];
		}
		else
		{
			return FALSE;
		}
	}

	function get_num_pages()
	{
		// grab
		$this->db->select('count(*) as count');
		$this->db->where('siteID', $this->siteID);
		$this->db->where('deleted', 0);
		$query = $this->db->get('pages');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['count'];
		}
		else
		{
			return FALSE;
		}
	}

	function get_popular_pages()
	{
		// grab
		$this->db->where('siteID', $this->siteID);
		$this->db->where('active', 1);
		$this->db->where('deleted', 0);
		$this->db->order_by('views', 'desc');

		$query = $this->db->get('pages', 5);

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	function get_new_tickets()
	{
		// grab
		$this->db->select('count(*) as count');
		$this->db->where('deleted', 0);
		$this->db->where('viewed', 0);
		$this->db->where('siteID', $this->siteID);
		$this->db->where('dateCreated >=', date("Y-m-d 00:00:00", strtotime('-2 days')));
		
		$query = $this->db->get('tickets');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['count'];
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_blog_posts_count()
	{
		// grab
		$this->db->select('count(*) as count');
		$this->db->where('deleted', 0);
		$this->db->where('siteID', $this->siteID);
		$query = $this->db->get('blog_posts');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['count'];
		}
		else
		{
			return FALSE;
		}
	}

	function get_blog_new_comments()
	{
		// grab
		$this->db->select('count(*) as count');
		$this->db->where('deleted', 0);
		$this->db->where('active', 0);		
		$this->db->where('siteID', $this->siteID);
		$this->db->where('dateCreated >=', date("Y-m-d 00:00:00", strtotime('-4 days')));
				
		$query = $this->db->get('blog_comments');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['count'];
		}
		else
		{
			return FALSE;
		}
	}

	function get_blog_latest_post()
	{
		// grab
		$this->db->select('postTitle');
		$this->db->where('deleted', 0);
		$this->db->where('siteID', $this->siteID);
		$this->db->order_by('dateCreated', 'desc');

		$query = $this->db->get('blog_posts', 1);

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row;
		}
		else
		{
			return FALSE;
		}
	}

	function get_popular_blog_posts()
	{
		// grab
		$this->db->where('siteID', $this->siteID);
		$this->db->where('deleted', 0);
		$this->db->order_by('views', 'desc');

		$query = $this->db->get('blog_posts', 5);

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	
	function get_requests($createrID = '', $accepterID='', $status='', $search = '',  $limit = '')
	{
		// start cache
		$this->db->start_cache();
		// set limit from uri if set
		$limit = (!$limit && $limit != 'all') ? $this->siteVars['shopItemsPerPage'] : $limit;
		
		if ($createrID )
		{
			$this->db->where('createrID', $createrID);		
		}
		if ($accepterID )
		{
			$this->db->where('accepterID', $accepterID);		
		}	
		if ($status )
		{
			$this->db->where('status', $status);		
		}	
		
		
		
		
		// search
		if ($search)
		{
			$fields = $this->db->field_data('requested_products');
			$searchString="";
			foreach ($fields as $field)
			{				
				//log_message('error',  $field->type . $field->name);
				if( $field->type !='int')
				{
					$searchString.= (empty($searchString)? '':' OR ') . $field->name.' LIKE "%'.$this->db->escape_like_str($search).'%" ';
				}			 
			}
			if(! empty($searchString)) 
				$this->db->where("(".$searchString.")");
		}
		
		
		// set order
		$order = FALSE;
		$uriArray = $this->uri->uri_to_assoc($this->uri_assoc_segment);
		foreach($uriArray as $key => $value)
		{
			if ($value == 'requestID' || $value == 'customerName' || $value == 'productName' || $value == 'createrName'|| $value == 'dateCreated' )
			{
				if ($key == 'orderasc')
				{
					$order = TRUE;
					$this->db->order_by($value,'asc');
				}
				elseif ($key == 'orderdesc')
				{
					$order = TRUE;
					$this->db->order_by($value,'desc');
				}
			}
		}
		if (!$order)
		{
			$this->db->order_by('requested_products.dateCreated','desc');
		}
		
		
		$this->db->join('requests', 'requests.requestID = requested_products.requestID', 'left');
		$this->db->join('clients', 'requests.clientID = clients.clientID', 'left');
		$this->db->join('users as creators', 'creators.userID = requested_products.creatorID', 'left');
		$this->db->join('users as accepters', 'accepters.userID = requested_products.accepterID', 'left');
		
		
		
		
		$this->db->select('requested_products.*, clients.clientName  ', FALSE);
		// stop cache
		$this->db->stop_cache();
			
			
		// get total rows		
		$query = $this->db->get('requested_products');
		$totalRows = $query->num_rows();
		//log_message("error",'product_types '.print_r($query->result_array(),true) );
		// init paging
		$this->core->set_paging($totalRows, $limit);
		$query = $this->db->get('requested_products', $limit, $this->pagination->offset);
				
		// flush cache
		$this->db->flush_cache();		
			
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	
	function get_popular_shop_products()
	{
		// grab
		$this->db->where('siteID', $this->siteID);
		$this->db->where('deleted', 0);
		$this->db->order_by('views', 'desc');

		$query = $this->db->get('shop_products', 5);

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	function get_num_sites()
	{
		// grab
		$this->db->where('resellerID', $this->site->config['resellerID']);
		$query = $this->db->get('sites');

		return $query->num_rows();
	}

	function get_activity($when = '')
	{
		// default wheres
		$this->db->where('siteID', $this->siteID);

		// when?
		if ($when == 'today')
		{
			$this->db->where('date >=', date("Y-m-d 00:00:00", strtotime('today')));
		}
		elseif ($when == 'yesterday')
		{
			$this->db->where('date <=', date("Y-m-d 00:00:00", strtotime('today')));
			$this->db->where('date >=', date("Y-m-d 00:00:00", strtotime('1 day ago')));
		}
		else
		{
			$this->db->where('date <=', date("Y-m-d 00:00:00", strtotime('today')));
			$this->db->where('date >=', date("Y-m-d 00:00:00", strtotime('1 day ago')));
		}
		$this->db->where('date <', date("Y-m-d H:i:s", strtotime('5 minutes ago')));		

		$this->db->select('COUNT(*) as guests, date, SUM(views) AS views, referer, userdata');
		$this->db->group_by('userdata');

		$this->db->order_by('date', 'desc');
			
		$query = $this->db->get('tracking');

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	function get_recent_activity()
	{
		$this->db->where('siteID', $this->siteID);
		$this->db->where('date >', date("Y-m-d H:i:s", strtotime('5 minutes ago')));
		$this->db->order_by('date', 'desc');	
		$query = $this->db->get('tracking');

		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	function get_num_users()
	{
		// default wheres
		$this->db->where('siteID', $this->siteID);
		$this->db->where('active', 1);	

		$this->db->select('COUNT(*) as numUsers');
			
		$query = $this->db->get('users');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['numUsers'];
		}
		else
		{
			return FALSE;
		}
	}

	function get_num_users_today()
	{
		// default wheres
		$this->db->where('siteID', $this->siteID);
		$this->db->where('active', 1);
		
		// when?
		$this->db->where('dateCreated >=', date("Y-m-d 00:00:00", strtotime('today')));

		$this->db->select('COUNT(*) as numUsers');
			
		$query = $this->db->get('users');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['numUsers'];
		}
		else
		{
			return FALSE;
		}
	}

	function get_num_users_yesterday()
	{
		// default wheres
		$this->db->where('siteID', $this->siteID);
		$this->db->where('active', 1);
		
		// when?
		$this->db->where('dateCreated >=', date("Y-m-d 00:00:00", strtotime('yesterday')));
		$this->db->where('dateCreated <=', date("Y-m-d 00:00:00", strtotime('today')));		

		$this->db->select('COUNT(*) as numUsers');
			
		$query = $this->db->get('users');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['numUsers'];
		}
		else
		{
			return FALSE;
		}
	}

	function get_num_users_week()
	{
		// default wheres
		$this->db->where('siteID', $this->siteID);
		$this->db->where('active', 1);

		// when?
		$this->db->where('dateCreated >=', date("Y-m-d 00:00:00", strtotime('-1 week sun')));

		$this->db->select('COUNT(*) as numUsers');
			
		$query = $this->db->get('users');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['numUsers'];
		}
		else
		{
			return FALSE;
		}
	}

	function get_num_users_last_week()
	{
		// default wheres
		$this->db->where('siteID', $this->siteID);
		$this->db->where('active', 1);

		// when?
		$this->db->where('dateCreated >=', date("Y-m-d 00:00:00", strtotime('-2 week sun')));
		$this->db->where('dateCreated <=', date("Y-m-d 00:00:00", strtotime('-1 week sun')));

		$this->db->select('COUNT(*) as numUsers');
			
		$query = $this->db->get('users');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			return $row['numUsers'];
		}
		else
		{
			return FALSE;
		}
	}

}