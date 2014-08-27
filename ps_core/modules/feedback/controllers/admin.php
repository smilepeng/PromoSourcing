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

class Admin extends MX_Controller {

	// set defaults
	var $includes_path = '/includes/admin';				// path to includes for header and footer
	var $redirect = '/admin/feedback/viewall';				// default redirect
	var $permissions = array();

	function __construct()
	{
		parent::__construct();

		// check user is logged in, if not send them away from this controller
		if (!$this->session->userdata('session_admin'))
		{
			redirect('/admin/login/'.$this->core->encode($this->uri->uri_string()));
		}
		
		// get permissions and redirect if they don't have access to this module
		if (!$this->permission->permissions)
		{
			redirect('/admin/dashboard/permissions');
		}
		if (!in_array($this->uri->segment(2), $this->permission->permissions))
		{
			redirect('/admin/dashboard/permissions');
		}

		// get siteID, if available
		if (defined('SITEID'))
		{
			$this->siteID = SITEID;
		}

		//  load models and libs
		$this->load->model('feedback_model', 'feedback');

	}
	
	function index()
	{
		redirect($this->redirect);
	}
	
	function viewall()
	{
		// default where
		$where = array();


		// grab data and display
		$output = $this->core->viewall('feedback', $where, array('dateCreated', 'desc'));

		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/viewall',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	
	function process_feedback($feedbackID)
	{
		// check permissions for this page
		if (!in_array('feedback_process', $this->permission->permissions))
		{
			redirect('/admin/dashboard/permissions');
		}
		
		// set object ID
		$objectID = array('feedbackID' => $feedbackID);

		// get values
		$output['data'] = $this->core->get_values('feedback', $objectID);	
		
		if (count($_POST))
		{
			
			// update
			if ($this->core->update('feedback', $objectID))
			{
							
				// set success message
				$this->session->set_flashdata('success', TRUE);					
					// where to redirect to
					redirect('/admin/feedback/process_feedback/'.$feedbackID);
			
			}
		}

		// set message
		if ($this->session->flashdata('success'))
		{
			$output['message'] = '<p>Your changes were saved.</p>';
		}

		// templates
		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/process_feedback', $output);
		$this->load->view($this->includes_path.'/footer');
	}

	function delete_post($objectID)
	{
		// check permissions for this page
		if (!in_array('feedback_process', $this->permission->permissions))
		{
			redirect('/admin/dashboard/permissions');
		}		
		
		if ($this->core->soft_delete('feedback', array('feedbackID' => $objectID)))
		{
			
			// where to redirect to
			redirect($this->redirect);
		}
	}

	function preview()
	{
		// get parsed body
		$html = $this->template->parse_body($this->input->post('body'));

		// filter for scripts
		$html = preg_replace('/<script(.*)<\/script>/is', '<em>This block contained scripts, please refresh page.</em>', $html);

		// output
		$this->output->set_output($html);
	}

	function comments()
	{
		// grab data and display
		$output['comments'] = $this->feedback->get_latest_comments();

		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/comments',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	function approve_comment($commentID)
	{
		if ($this->feedback->approve_comment($commentID))
		{
			redirect('/admin/feedback/comments');
		}
	}

	function delete_comment($objectID)
	{
		// check permissions for this page
		if (!in_array('feedback_edit', $this->permission->permissions))
		{
			redirect('/admin/dashboard/permissions');
		}
				
		if ($this->core->soft_delete('feedback_comments', array('commentID' => $objectID)))
		{
			// where to redirect to
			redirect('/admin/feedback/comments/');
		}
	}

	function categories()
	{
		// check permissions for this page
		if (!in_array('feedback_cats', $this->permission->permissions))
		{
			redirect('/admin/dashboard/permissions');
		}

		// get values
		$output = $this->core->get_values('feedback_cats');		

		// get categories
		$output['categories'] = $this->feedback->get_categories();

		if (count($_POST))
		{				
			// required fields
			$this->core->required = array('catName' => 'Category name');
	
			// set date
			$this->core->set['dateCreated'] = date("Y-m-d H:i:s");
			$this->core->set['catSafe'] = url_title(strtolower(trim($this->input->post('catName'))));
	
			// update
			if ($this->core->update('feedback_cats'))
			{
				// where to redirect to
				redirect('/admin/feedback/categories');
			}
		}

		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/categories',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	function edit_cat()
	{
		// check permissions for this page
		if (!in_array('feedback_cats', $this->permission->permissions))
		{
			redirect('/admin/dashboard/permissions');
		}

		// go through post and edit each list item
		$listArray = $this->core->get_post();
		if (count($listArray))
		{
			foreach($listArray as $ID => $value)
			{
				if ($ID != '' && sizeof($value) > 0 && $value['catName'])
				{	
					// set object ID
					$objectID = array('catID' => $ID);
					$this->core->set['catName'] = $value['catName'];
					$this->core->set['catSafe'] = url_title(strtolower(trim($value['catName'])));
					$this->core->update('feedback_cats', $objectID);
				}
			}
		}

		// where to redirect to
		redirect('/admin/feedback/categories');		
	}	

	function delete_cat($catID)
	{
		// check permissions for this page
		if (!in_array('feedback_cats', $this->permission->permissions))
		{
			redirect('/admin/dashboard/permissions');
		}
				
		// where
		$objectID = array('catID' => $catID);	
		
		if ($this->core->soft_delete('feedback_cats', $objectID))
		{
			// where to redirect to
			redirect('/admin/feedback/categories');
		}		
	}

	function order($field = '')
	{
		$this->core->order(key($_POST), $field);
	}	

}