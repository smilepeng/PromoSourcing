<?php


// ------------------------------------------------------------------------

class Admin extends MX_Controller {

	// set defaults
	var $table = 'requests';						// table to update
	var $includes_path = '/includes/admin';		// path to includes for header and footer
	var $redirect = '/admin/requests/viewall';		// default redirect
	var $objectID = 'requestID';					// default unique ID	
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
			redirect('/admin/summary');
		}
		if (!in_array($this->uri->segment(2), $this->permission->permissions))
		{
			redirect('/admin/summary');
		}

		// get siteID, if available
		if (defined('SITEID'))
		{
			$this->siteID = SITEID;
		}

		//  load models and libs
		$this->load->model('requests_model', 'request');
		$this->load->library('tags');
	}
	
	function index()
	{
		redirect($this->redirect);
	}
	
	function viewall()
	{
		// check permissions for this page
		if (!in_array('requests', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
		
		// if search
		$where = '';
		if (count($_POST) && ($query = $this->input->post('searchbox')))
		{
			$name = @preg_split('/ /', $query);
			if (count($name) > 1)
			{								
				$where = '(productID LIKE "%'.$this->db->escape_like_str($query).'%"  )';
			}
			else
			{
				$where = '(productID LIKE "%'.$this->db->escape_like_str($query).'%" )';
			}
		}

		// output requests
		$output = $this->core->viewall($this->table, $where);

		

		$this->load->view($this->includes_path.'/header');
		$this->load->view('viewall',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	function add()
	{
		// check permissions for this page
		if (!in_array('requests_edit', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
				
	
		// get values
		$output['data'] = $this->core->get_values('requests');	

		// get product types
		$output['productTypes'] = $this->requests->get_product_types();

		if (count($_POST))
		{		
			// required
			$this->core->required = array(
				'postTitle' => array('label' => 'Title', 'rules' => 'required|trim'),
				'body' => 'Body'
			);
			
			// tidy tags
			$tags = '';
			if ($this->input->post('tags'))
			{
				foreach (explode(',', $this->input->post('tags')) as $tag)
				{
					$tags[] = ucwords(trim(strtolower(str_replace('-', ' ', $tag))));
				}
				$tags = implode(', ', $tags);
			}
		
			// set date
			$this->core->set['dateCreated'] = date("Y-m-d H:i:s");
			$this->core->set['userID'] = $this->session->userdata('userID');
			$this->core->set['uri'] = url_title(strtolower($this->input->post('postTitle')));
			$this->core->set['tags'] = $tags;
			
			// update
			if ($this->core->update('blog_posts'))
			{
				$postID = $this->db->insert_id();
	
				// update categories
				$this->blog->update_cats($postID, $this->input->post('catsArray'));

				// update tags
				$this->tags->update_tags('blog_posts', $postID, $tags);
							
				// where to redirect to
				redirect($this->redirect);
			}
		}

		// templates
		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/add', $output);
		$this->load->view($this->includes_path.'/footer');
	}

	function edit($requestID)
	{
		// check permissions for this page
		if (!in_array('requests_edit', $this->permission->permissions) && $userID != $this->session->userdata('userID'))
		{
			redirect('/admin/requests');
		}

		// check this is a valid user
		if (!$request = $this->requests->get_request($requestID))
		{
			show_error('Request is not exist.');
		}

		
		// set object ID
		$objectID = array($this->objectID => $userID);		

		// required
		$this->core->required = array(
			'username' => array('label' => 'Username', 'rules' => 'really_unique[users.username]|trim'),
			'email' => array('label' => 'Email', 'rules' => 'valid_email|unique[users.email]|trim'),
			'firstName' => array('label' => 'First name', 'rules' => 'trim|ucfirst'),
			'lastName' => array('label' => 'Last name', 'rules' => 'trim|ucfirst')
		);

		// get values
		$output['data'] = $this->core->get_values($this->table, $objectID);
		$output['groups'] = $this->permission->get_groups();			

		// deal with post
		if (count($_POST))
		{
			// set date
			$this->core->set['dateModified'] = date("Y-m-d H:i:s");
	
			// check groupID is not being overridden
			if (($this->input->post('groupID') && @!in_array('users_groups', $this->permission->permissions)) || ($this->input->post('groupID') < 0 && $this->session->userdata('groupID') >= 0))
			{
				redirect('/admin/summary');
				die();
			}
	
			// set siteID
			if ($this->input->post('siteID') && $this->session->userdata('groupID') < 0)
			{
				$this->core->set['siteID'] = $this->input->post('siteID');
			}
			else
			{
				$this->core->set['siteID'] = $this->siteID;
			}

			// update
			if ($this->core->update($this->table, $objectID))
			{
				$output['message'] = '<p>Your details have been updated.</p>';
			}
		}	
		
		// templates
		$this->load->view($this->includes_path.'/header');
		$this->load->view('edit',$output);
		$this->load->view($this->includes_path.'/footer');
	}


	function delete($objectID)
	{
		// check permissions for this page
		if (!in_array('requests_delete', $this->permission->permissions))
		{
			redirect('/admin/requests');
		}
				
		if ($this->core->delete($this->table, array($this->objectID => $objectID)))
		{
			// where to redirect to
			redirect($this->redirect);
		}
	}


	function ac_requests()
	{	
		$q = strtolower($_GET["q"]);
        if (!$q) return;

        // form dropdown
        $results = $this->requests->get_requests($q);

        // go foreach
        foreach((array)$results as $row)
        {
            $items[$row['productName']] = $row['clientID'];
        }

        foreach ($items as $key=>$value) {
			/* If you want to force the results to the query
			if (strpos(strtolower($key), $tags) !== false)
			{
				echo "$key|$id|$name\n";
			}*/
			$this->output->set_output("$key|$value\n");
        }
	}

}