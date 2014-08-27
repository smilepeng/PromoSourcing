<?php
/**
 * MMS
 *
 * A user friendly, modular content management system for PHP 5.0
 * Built on CodeIgniter - http://codeigniter.com
 *
 * @package		MMS
 * @author		Smyle
 * @copyright	Copyright (c) 2014, SMYLE
 * @license		
 * @link		
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

class Feedback_Model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();

		// get siteID, if available
		if (defined('SITEID'))
		{
			$this->siteID = SITEID;
		}	
	}

	function get_all_posts()
	{

		$this->db->where('deleted', 0);
		$this->db->where('siteID', $this->siteID);
		
		$query = $this->db->get('feedback');

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
	
	function get_posts($num = 10)
	{
		// start cache
		$this->db->start_cache();
		
		// default where
		$this->db->where(array(
			
			'deleted' => 0,
			'siteID' => $this->siteID
		));
		
		// order
		$this->db->order_by('dateCreated', 'desc');
		
		// stop cache
		$this->db->stop_cache();

		// get total rows
		$query = $this->db->get('feedback');
		$totalRows = $query->num_rows();
		
		// get comment count and post data
		$this->db->select('feedback.* ', FALSE);
		
		// init paging
		$this->core->set_paging($totalRows, $num);
		$query = $this->db->get('feedback', $num, $this->pagination->offset);
		
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

	
	function get_post_by_subject($subject = '', $limit = 10)
	{
		$this->db->start_cache();
		$this->db->where('subject', $subject);
		$this->db->where('deleted', 0);	
		$this->db->where('siteID', $this->siteID);

		// get comment count and post data
		$this->db->select('feedback.*', FALSE);
		$this->db->stop_cache();
				
		$query = $this->db->get('feedback');

		// init paging
		$this->core->set_paging($totalRows, $limit);
		$query = $this->db->get('feedback', $limit, $this->pagination->offset);
		$this->db->flush_cache();

		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
	

	function get_post_by_id($feedbackID)
	{
		$this->db->where('feedbackID', $feedbackID);
		
		$query = $this->db->get('feedback', 1);
		
		if ($query->num_rows())
		{
			$post = $query->row_array();
			
			return $post;
		}
		else
		{
			return FALSE;
		}
	}

	

	function get_archive($limit = 20)
	{
		$this->db->select(' DATE_FORMAT(dateCreated, "%M %Y") as dateStr,feedback.* ', FALSE);
		$this->db->where('archived', 1);
		$this->db->where('deleted', 0);
		$this->db->where('siteID', $this->siteID);	

		$this->db->order_by('dateCreated', 'desc');
		$this->db->group_by('dateStr');
		
		$query = $this->db->get('feedback', $limit);

		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	

			
}