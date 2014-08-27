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

class Feedback extends MX_Controller {

	var $partials = array();
	var $sitePermissions = array();
	var $num = 10;

	function __construct()
	{
		parent::__construct();

		// get siteID, if available
		if (defined('SITEID'))
		{
			$this->siteID = SITEID;
		}		

		$this->load->model('feedback_model', 'feedback');
		$this->load->module('pages');

		

		// load partials - archive
		if ($archive = $this->feedback->get_archive())
		{
			foreach($archive as $date)
			{
				$this->partials['feedback:archive'][] = array(
					'archive:link' => site_url('/feedback/'.$date['feedbackID'].'/'),
					'archive:title' => $date['subject'],
					'archive:author' => $date['name'],
					'archive:body' => $date['message']
				);
			}
		}

	}

	function index()
	{	
		redirect('/feedback/viewall');
	}



	function add_feedback()
	{
		
		// get values
		$output['data'] = $this->core->get_values('feedback');	

		
		if (count($_POST))
		{		
		
			//log_message('error', 'feedback:'.print_r($_POST, true));
			// required
			$this->core->required = array(
				'name' => array('label' => 'Name', 'rules' => 'required|trim'),
				'subject' => array('label' => 'Subject', 'rules' => 'required|trim'),
				'message' => 'Message'
			);
			
					
			// set date
			$this->core->set['dateCreated'] = date("Y-m-d H:i:s");		

			
			// update
			if(!$this->core->update('feedback'))
				$output['data']['message'] = 'Message is invalid.';
		
		}else{
			$output['data']['message'] = 'No data is submitted';
		}
		// load content into a popup
		if ($this->core->is_ajax())
		{
			// display with cms layer	
			$this->pages->view('feedback_added_popup', $output, TRUE);
		}
		else
		{
			// display with cms layer	
			$this->pages->view('feedback_added', $output, TRUE);
		}

	}

	function _captcha_check()
	{
		// if captcha is posted, check its not a bot (requires js)
		if ($this->input->post('captcha') == 'notabot')
		{
			return TRUE;
		}
		elseif ($this->input->post('captcha') != 'notabot')
		{
			$this->form_validation->set_message('captcha_check', 'You didn\'t pass the spam check, please contact us to post a comment.');
			return FALSE;
		}
	}

    function _populate_posts($posts = '')
    {
    	if ($posts && is_array($posts))
    	{
			$x = 0;
			foreach($posts as $post)
			{
				// get author details
				$author = $this->feedback->lookup_user($post['userID']);				
				
				// populate template array
				$data[$x] = array(
					'post:link' => site_url('feedback/'.dateFmt($post['dateCreated'], 'Y/m').'/'.$post['uri']),
					'post:title' => $post['postTitle'],
					'post:date' => dateFmt($post['dateCreated'], ($this->site->config['dateOrder'] == 'MD') ? 'M jS Y' : 'jS M Y'),
					'post:day' => dateFmt($post['dateCreated'], 'd'),
					'post:month' => dateFmt($post['dateCreated'], 'M'),
					'post:year' => dateFmt($post['dateCreated'], 'y'),										
					'post:body' => $this->template->parse_body($post['body'], TRUE, site_url('feedback/'.dateFmt($post['dateCreated'], 'Y/m').'/'.$post['uri'])),
					'post:excerpt' => $this->template->parse_body($post['excerpt'], TRUE, site_url('feedback/'.dateFmt($post['dateCreated'], 'Y/m').'/'.$post['uri'])),
					'post:author' => (($author['displayName']) ? $author['displayName'] : $author['firstName'].' '.$author['lastName']),
					'post:author-id' => $author['userID'],
					'post:author-email' => $author['email'],
					'post:author-gravatar' => 'http://www.gravatar.com/avatar.php?gravatar_id='.md5(trim($author['email'])).'&default='.urlencode(site_url('/static/uploads/avatars/noavatar.gif')),
					'post:author-bio' => $author['bio'],
					'post:comments-count' => $post['numComments']
				);
	
				// get cats
				if ($cats = $this->feedback->get_cats_for_post($post['postID']))
				{
					$i = 0;
					foreach ($cats as $cat)
					{
						$data[$x]['post:categories'][$i]['category:link'] = site_url('feedback/'.url_title(strtolower(trim($cat))));
						$data[$x]['post:categories'][$i]['category'] = $cat;
						
						$i++;
					}
				}
				
				// get tags
				if ($post['tags'])
				{
					$tags = explode(',', $post['tags']);

					$i = 0;
					foreach ($tags as $tag)
					{
						$data[$x]['post:tags'][$i]['tag:link'] = site_url('feedback/tag/'.$this->tags->make_safe_tag($tag));
						$data[$x]['post:tags'][$i]['tag'] = $tag;
						
						$i++;
					}
				}
	
				$x++;
			}

			return $data;
		}
		else
		{
			return FALSE;
		}
    }
    
}