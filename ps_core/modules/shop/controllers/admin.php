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
	var $redirect = '/admin/shop/categories';				// default redirect
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

		// load libs
		$this->load->model('shop_model', 'shop');
		$this->load->library('tags');
	}
	
	function index()
	{
		redirect($this->redirect);
	}
	
	function product_types($catID = '')
	{		
		// set order segment
		if (is_numeric($catID) )
		{
			$this->shop->uri_assoc_segment = 5;
			
			// output selected category
			$output['catID'] = $catID;
		}
		else
		{
			$output['catID'] = '';
		}
		
		// set limit
		$limit =  $this->site->config['paging'] ;
		
		// get products
		$output['product_types'] = $this->shop->get_product_types($catID, $this->input->post('searchbox'), $limit);
		
		// get categories
		$output['categories'] = $this->shop->get_categories();
	

		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/product_types',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	function add_product_type()
	{
		// check permissions for this page
		if (!in_array('shop_edit', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
				
		// required
		$this->core->required = array(
			'typeName' => 'Product type name'  
		);

		if ($this->input->post('cancel'))
		{			
			redirect( '/admin/shop/product_types');
		}
		else
		{			
			// set date
			$this->core->set['dateCreated'] = date("Y-m-d H:i:s");
			$this->core->set['userID'] = $this->session->userdata('userID');
			
			// get values
			$output['data'] = $this->core->get_values('product_types');	
			
			
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
			
			
			$this->core->set['typeSafe'] = url_title(strtolower(trim($this->input->post('typeName'))));
				
			// set tags
			$this->core->set['tags'] = $tags;
				
			// update
			if ($this->core->update('product_types') && count($_POST))
			{
				// get insert id
				$productTypeID = $this->db->insert_id();

					
				// update categories
				$this->shop->update_cat_product_types($productTypeID, $this->input->post('catsArray'));
				// update product fields
				$this->shop->update_type_fields($productTypeID, $this->input->post('fieldsArray'));
				// update tags
				$this->tags->update_tags('product_types', $productTypeID, $tags);
					
				// where to redirect to
				redirect('/admin/shop/product_types');
			}
		
			// get categories
			$output['categories'] = $this->shop->get_categories();
			// get product fields
			$output['product_fields'] = $this->shop->get_product_fields();
		}

		// templates
		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/add_product_type',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	function edit_product_type($productTypeID)
	{
		// check permissions for this page
		if (!in_array('shop_edit', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
				
		// required
		$this->core->required = array(
			'typeName' => 'Product Type name'		
		);

		// where
		$objectID = array('productTypeID' => $productTypeID);	

		// get values
		$output['data'] = $this->core->get_values('product_types', $objectID);	

		if ($this->input->post('cancel'))
		{			
			redirect($this->redirect);
		}
		else
		{	
			
			// get image errors if there are any
			if ($this->shop->errors)
			{
				$output['errors'] = $this->shop->errors;
			}
			else
			{
				// set stock
				if ($this->input->post('status') == 'O' || ($this->site->config['shopStockControl'] && !$this->input->post('stock')))
				{
					$this->core->set['stock'] = 0;
					$this->core->set['status'] = 'O';
				}
					
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
				
				// set tags
				$this->core->set['tags'] = $tags;
				
				// update
				if ($this->core->update('product_types', $objectID) && count($_POST))
				{
					/*
					// clear variations
					$this->shop->clear_variations($productTypeID);
					
					// add variation 1
					for ($x=1; $x<6; $x++)
					{
						if ($this->input->post('variation1-'.$x))
						{
							$varID = $this->shop->add_variation($productTypeID, 1, $this->input->post('variation1-'.$x), $this->input->post('variation1_price-'.$x));
						}
					}
		
					// add variation 2
					for ($x=1; $x<6; $x++)
					{
						if ($this->input->post('variation2-'.$x))
						{
							$varID = $this->shop->add_variation($productTypeID,  2, $this->input->post('variation2-'.$x), $this->input->post('variation2_price-'.$x));
						}
					}

					// add variation 3
					for ($x=1; $x<6; $x++)
					{
						if ($this->input->post('variation3-'.$x))
						{
							$varID = $this->shop->add_variation($productTypeID, 3, $this->input->post('variation3-'.$x), $this->input->post('variation3_price-'.$x));
						}
					}
					*/
					// update categories
					$this->shop->update_cats($productTypeID, $this->input->post('catsArray'));

					// update tags
					$this->tags->update_tags('product_types', $productTypeID, $tags);

					// set success message
					$this->session->set_flashdata('success', 'Your changes were saved.');

					// view page
					if ($this->input->post('view'))
					{
						redirect('/shop/'.$productTypeID.'/'.strtolower(url_title($this->input->post('typeName'))));
					}
					else
					{																	
						// where to redirect to
						redirect('/admin/shop/edit_product/'.$productTypeID);
					}
				}
			}		

			// set message
			if ($message = $this->session->flashdata('success'))
			{
				$output['message'] = '<p>'.$message.'</p>';
			}

			
			
			// get categories
			$output['categories'] = $this->shop->get_categories();
			
			// get categories for this product
			$output['data']['categories'] = $this->shop->get_cats_for_product_type($productTypeID);
			
	
			// templates
			$this->load->view($this->includes_path.'/header');
			$this->load->view('admin/edit_product_type',$output);
			$this->load->view($this->includes_path.'/footer');			
		}
	}

	function delete_product_type($productTypeID)
	{
		// check permissions for this page
		if (!in_array('shop_delete', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
				
		if ($this->core->soft_delete('product_types', array('productTypeID' => $productTypeID)));
		{
			// remove category mappings
			$this->shop->update_cats($productTypeID);

			// where to redirect to
			redirect($this->redirect);
		}
	}

	function product_fields()
	{		

		// set limit
		$limit =  $this->site->config['paging'] ;
		
		// get products
		$output['product_fields'] = $this->shop->get_product_fields( $this->input->post('searchbox'), $limit);
		
		
		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/product_fields',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	function add_product_field()
	{
		// check permissions for this page
		if (!in_array('shop_edit', $this->permission->permissions))
		{
			redirect('/admin/product_fields');
		}
				
		// required
		$this->core->required = array(
			'fieldName' => 'Product field name'
		);

		if ($this->input->post('cancel'))
		{			
			redirect( '/admin/shop/product_fields');
		}
		else
		{			
			// set date
			$this->core->set['dateCreated'] = date("Y-m-d H:i:s");
			$this->core->set['userID'] = $this->session->userdata('userID');
			$fieldName=$this->input->post('fieldName');	
			$fieldSafe=url_title(strtolower(trim($fieldName)));
			$this->core->set['fieldSafe'] = $fieldSafe;
			// get values
			$output['data'] = $this->core->get_values('product_fields');	
			
			
			// update
			if ($this->core->update('product_fields') && count($_POST))
			{
				// get insert id
				$productFieldID = $this->db->insert_id();
				
				
				$fieldType=$this->input->post('fieldType');	
				$valueSet=$this->input->post('valueSet');	
				$defaultValue=$this->input->post('defaultValue');	
				
				// update requested product table
				$this->shop->update_related_product_table($fieldSafe, $fieldType, $valueSet, $defaultValue);
				
				
				// where to redirect to
				redirect('/admin/shop/product_fields');
			}
		
		
			// get product fields
			$output['product_fields'] = $this->shop->get_product_fields();
		}

		// templates
		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/add_product_field',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	function edit_product_field($productFieldID)
	{
		// check permissions for this page
		if (!in_array('shop_edit', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
				
		// required
		$this->core->required = array(
			'fieldName' => 'Product Field name'		
		);

		// where
		$objectID = array('fieldID' => $productFieldID);	

		// get values
		$output['data'] = $this->core->get_values('product_fields', $objectID);	

		if ($this->input->post('cancel'))
		{			
			redirect('/admin/shop/product_fields');
		}
		else
		{	
			
			// get image errors if there are any
			if ($this->shop->errors)
			{
				$output['errors'] = $this->shop->errors;
			}
			else
			{
			
					// set date
				$this->core->set['dateModified'] = date("Y-m-d H:i:s");
			
				// update
				if ($this->core->update('product_fields', $objectID) && count($_POST))
				{
					$fieldName=$this->input->post('fieldName');	
					$fieldSafe=url_title(strtolower(trim($fieldName)));					
					$fieldType=$this->input->post('fieldType');	
					$valueSet=$this->input->post('valueSet');	
					$defaultValue=$this->input->post('defaultValue');
					// update requested_product and quoted_product
					$this->shop->update_related_product_table($fieldSafe,$fieldType,$valueSet,$defaultValue);
				
					// set success message
					$this->session->set_flashdata('success', 'Your changes were saved.');

					// view page
					if ($this->input->post('view'))
					{
						redirect('/shop/'.$productFieldID.'/'.strtolower(url_title($this->input->post('typeName'))));
					}
					else
					{																	
						// where to redirect to
						redirect('/admin/shop/edit_product_field/'.$productFieldID);
					}
				}
			}		

			// set message
			if ($message = $this->session->flashdata('success'))
			{
				$output['message'] = '<p>'.$message.'</p>';
			}

			
	
			// templates
			$this->load->view($this->includes_path.'/header');
			$this->load->view('admin/edit_product_field',$output);
			$this->load->view($this->includes_path.'/footer');			
		}
	}

	function delete_product_field($productFieldID)
	{
		// check permissions for this page
		if (!in_array('shop_delete', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
				
		if ($this->core->soft_delete('product_types', array('productTypeID' => $productTypeID)));
		{
			// remove category mappings
			$this->shop->update_cats($productTypeID);

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

	function categories()
	{
		// check permissions for this page
		if (!in_array('shop_cats', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
			
		// get parents
		if ($parents = $this->shop->get_category_parents())
		{
			// get children
			foreach($parents as $parent)
			{
				$children[$parent['catID']] = $this->shop->get_category_children($parent['catID']);
			}
		}

		// send data to view
		$output['parents'] = @$parents;
		$output['children'] = @$children;

		$this->load->view($this->includes_path.'/header');
		$this->load->view('admin/categories',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	function add_cat()
	{
		//log_message('error', 'files=========: '. print_r($_FILES, true));
		//log_message('error', 'POST=========: '. print_r($_POST, true));
		// check permissions for this page
		if (!in_array('shop_cats', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}

		// required fields
		$this->core->required = array(
			'catName' => 'Title',
		);

		// populate form
		$output['data'] = $this->core->get_values();
		
		// deal with post
		if (count($_POST))
		{
			$this->core->set['dateModified'] = date("Y-m-d H:i:s");
			$this->core->set['catSafe'] = url_title(strtolower(trim($this->input->post('catName'))));
			
			// update
			if ($this->core->update('cats'))
			{
				// get insert id
				$catID = $this->db->insert_id();
				redirect('/admin/shop/categories');
			}
		

		}
	
		// get parents
		$output['parents'] = $this->shop->get_category_parents();		

		// templates
		if (!$this->core->is_ajax()) $this->load->view($this->includes_path.'/header');
		$this->load->view('admin/category_form', $output);
		if (!$this->core->is_ajax()) $this->load->view($this->includes_path.'/footer');
	}

	function edit_cat($catID)
	{
		// check permissions for this page
		if (!in_array('shop_cats', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}

		// required fields
		$this->core->required = array(
			'catName' => 'Title',
		);

		// where
		$objectID = array('catID' => $catID);

		// get values from version
		$row = $this->shop->get_category($catID);

		// populate form
		$output['data'] = $this->core->get_values($row);
		
		// deal with post
		if (count($_POST))
		{
			$this->core->set['dateModified'] = date("Y-m-d H:i:s");
			$this->core->set['catSafe'] = url_title(strtolower(trim($this->input->post('catName'))));
			
			// update
			if ($this->core->update('cats', $objectID))
			{
				redirect('/admin/shop/categories');
			}
				
		}

		
		// get parents
		$output['parents'] = $this->shop->get_category_parents();		

		// templates
		if (!$this->core->is_ajax()) $this->load->view($this->includes_path.'/header');
		$this->load->view('admin/category_form', $output);
		if (!$this->core->is_ajax()) $this->load->view($this->includes_path.'/footer');
	}

	function delete_cat($catID)
	{
		// check permissions for this page
		if (!in_array('cats', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
				
		// where
		$objectID = array('catID' => $catID);	
		
		if ($this->core->soft_delete('cats', $objectID))
		{
			// delete sub categories
			$objectID = array('parentID' => $catID);
			
			$this->core->soft_delete('cats', $objectID);
			
			// where to redirect to
			redirect('/admin/shop/categories');
		}		
	}

	function order($field = '')
	{
		//log_message('error', 'Order: '.print_r($_POST, true).' Key: '.print_r(key($_POST), true)  );
		$this->core->order(key($_POST), $field);
	}
	
	function ac_product_types()
	{	
		$q = strtolower($_GET["q"]);
		if (!$q) return;
		
		// form dropdown
		$results = $this->shop->get_product_types(NULL, $q);
		
		// go foreach
		foreach((array)$results as $row)
		{
			$items[$row['productTypeID']] = $row['typeName'];
		}
		
		// output
		$output = '';
		foreach ($items as $key=>$value)
		{
			$output .= "$key|$value\n";
		}
		
		$this->output->set_output($output);
	}
	
}