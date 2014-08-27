<?php
/**

 * @package		
 * @author		LIPENG LI
 */

// ------------------------------------------------------------------------

class Requests_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		
		// get siteID, if available
		if (defined('SITEID'))
		{
			$this->siteID = SITEID;
		}
	}

	function get_requests($q = '', $num = 10)
	{
		// start cache
		$this->db->start_cache();
		
		// default where
		$this->db->where(array(
			//'status' => 'O',			
			'siteID' => $this->siteID
		));
		
		// order
		$this->db->order_by('dateCreated', 'desc');
		
		// stop cache
		$this->db->stop_cache();

		// get total rows
		$query = $this->db->get('requests');
		$totalRows = $query->num_rows();
		
		// get comment count and post data
		$this->db->select('(SELECT firstName from '.$this->db->dbprefix.'users where '.$this->db->dbprefix.'users.userID = '.$this->db->dbprefix.'requests.createrID and 
deleted = 0 and active = 1) AS createrName, requests.* ', FALSE);
		
		// init paging
		$this->core->set_paging($totalRows, $num);
		$query = $this->db->get('requests', $num, $this->pagination->offset);
		
		// flush cache
		$this->db->flush_cache();

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function get_request($requestID)
	{
		// default wheres
		if ($this->session->userdata('groupID') >= 0)
		{
			$this->db->where('siteID', $this->siteID);
		}
		
		$this->db->where('requestID', $requestID);

		// grab
		$query = $this->db->get('requests', 1);

		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}		
	}

	function get_product_types($productTypeID = '', $limit=10)
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0));
		$this->db->order_by('typeOrder');
		
		// get based on category ID
		if ($catID)
		{
			$query = $this->db->get_where('blog_cats',   array('catID' => $catID), 1);
			
			if ($query->num_rows())
			{
				return $query->row_array();
			}
			else
			{
				return FALSE;
			}	
		}
		// or just get all of em
		else
		{
			// template type
			$query = $this->db->get('blog_cats', $limit);
			
			if ($query->num_rows())
			{
				return $query->result_array();
			}
			else
			{
				return FALSE;
			}
		}
	}	

	
}