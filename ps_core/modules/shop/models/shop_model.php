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

class Shop_model extends CI_Model {
	
	
	// defaults
	var $table = '';
	var $uri_assoc_segment = 4;
	var $errors;

	var $siteVars = array();
	
	function __construct()
	{
		parent::__construct();

		// get siteID, if available
		if (defined('SITEID'))
		{
			$this->siteID = SITEID;
		}

		// get config vars
		$this->siteVars = $this->site->config;
		
	}
	
	
	function get_categories_by_parentID($parentID = 0)
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0,'parentID' => $parentID ));
		$this->db->select('cats.*', FALSE);
		$this->db->order_by('catOrder');			
		
		// template type
		$query = $this->db->get('cats');

		if ($query->num_rows())
		{
			// get categories
			$result = $query->result_array();			
			foreach ($result as $cat)
			{
				// populate array
				$categories[] = $cat;	
			}			
			return $categories;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_categories($catID = '')
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0, 'parentID' => 0));
		if ( !empty($catID) )
			$this->db->where(array('catID' => $catID) );
			
		$this->db->select('cats.*, parentID as tempParentID, if(parentID>0, parentID+1, catID) AS parentOrder, (SELECT catName from '.$this->db->dbprefix.'cats WHERE '.$this->db->dbprefix.'cats.catID = tempParentID) AS parentName', FALSE);
		$this->db->order_by('catOrder');			
		
		// template type
		$query = $this->db->get('cats');

		if ($query->num_rows())
		{
			// get categories
			$result = $query->result_array();
			
			foreach ($result as $cat)
			{
				// populate array
				$categories[] = $cat;				
				
				// get children
				if ($children = $this->get_category_children($cat['catID']))
				{
					foreach ($children as $child)
					{
						// populate array
						$categories[] = $child;
					}
				}
			}
			
			return $categories;
		}
		else
		{
			return FALSE;
		}
	}

	function get_product_fields( $search = '',  $limit = '')
	{
	// set limit from uri if set
		$limit = (!$limit && $limit != 'all') ? $this->siteVars['shopItemsPerPage'] : $limit;
		// start cache
		$this->db->start_cache();

		// search
		if ($search)
		{
			$this->db->where('(fieldName LIKE "%'.$this->db->escape_like_str($search).'%" OR valueSet LIKE "%'.$this->db->escape_like_str($search).'%" OR sampleValue LIKE "%'.$this->db->escape_like_str($search).'%" )');
		}
		
		// set order
		$order = FALSE;
		$uriArray = $this->uri->uri_to_assoc($this->uri_assoc_segment);
		foreach($uriArray as $key => $value)
		{
			if ($value == 'filedName' || $value == 'fieldType' || $value == 'valueSet' || $value == 'sampleValue' )
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
			$this->db->order_by('product_fieldsOrder', 'asc');		
		}	
		// default wheres
		$this->db->where(array('product_fields.siteID' => $this->siteID, 'deleted' => 0));
		
		// stop cache
		$this->db->stop_cache();
			
		// get total rows
		$query = $this->db->get('product_fields');
		
		$totalRows = $query->num_rows();
		//log_message('error', 'Fields:'.print_r($query,true).$totalRows );
		// init paging
		$this->core->set_paging($totalRows, $limit);
		$query = $this->db->get('product_fields', $limit, $this->pagination->offset);
		//log_message('error', 'Fields:'.print_r($query,true).$totalRows );		
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
	
	function get_ordered_product_fields( $limit)
	{
	
		$limit = (!$limit && $limit != 'all') ? $this->siteVars['shopItemsPerPage'] : $limit;
		
		
		// start cache
		$this->db->start_cache();
		
		$this->db->order_by('product_fieldsOrder', 'asc');
		
		// default wheres
		$this->db->where(array('product_fields.siteID' => $this->siteID, 'deleted' => 0));
		
		// stop cache
		$this->db->stop_cache();
			
		// get total rows
		$query = $this->db->get('product_fields');
		$totalRows = $query->num_rows();

		// init paging
		$this->core->set_paging($totalRows, $limit);
		$query = $this->db->get('product_fields', $limit, $this->pagination->offset);
				
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

	
	function get_category_children_by_ref($parentCatSafe = '', $limit='')
	{
		//log_message('error', 'Parent cat safe:'.$parentCatSafe);
		// select
		$this->db->select('cats.*, if(parentID>0, parentID+1, catID) AS parentOrder ', FALSE);
		// set limit from uri if set
		$limit = (!$limit && $limit != 'all') ? $this->siteVars['shopItemsPerPage'] : $limit;
		
		
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0) );
	    $this->db->where( 'parentID = ( select catID from '.$this->db->dbprefix.'cats where catSafe="'.$parentCatSafe.'")');
		
		$this->db->order_by('catOrder', 'asc');
		
		$query = $this->db->get('cats ', $limit);
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_category($catID = '')
	{
		// select
		$this->db->select('cats.*, parentID as tempParentID, (SELECT catName from '.$this->db->dbprefix.'cats WHERE '.$this->db->dbprefix.'cats.catID = tempParentID) AS parentName, (SELECT catSafe from '.$this->db->dbprefix.'cats WHERE '.$this->db->dbprefix.'cats.catID = tempParentID) AS parentSafe', FALSE);
		
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0));

		// get category by ID
		$this->db->where('catID', $catID);
		
		$query = $this->db->get('cats', 1);
		
		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}		
	}

	
	function get_category_by_reference($catRef = '', $parentRef = '')
	{
		// get parent
		$parent = ($parentRef) ? $this->get_category_by_reference($parentRef) : '';
		
		// select parent
		$this->db->select('cats.*, parentID as tempParentID, (SELECT catName from '.$this->db->dbprefix.'cats WHERE '.$this->db->dbprefix.'cats.catID = tempParentID) AS parentName, (SELECT catSafe from '.$this->db->dbprefix.'cats WHERE '.$this->db->dbprefix.'cats.catID = tempParentID) AS parentSafe', FALSE);

		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0));

		// check for parent
		if ($parent)
		{
			$this->db->where('parentID', $parent['catID']);
		}
		else
		{
			$this->db->where('parentID', 0);
		}
		
		// get category by reference
		$this->db->where('catSafe', $catRef);
		
		$query = $this->db->get('cats', 1);
		
		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_category_parents($parentID=0)  //Edited by smy () ==> ($parentID=0)
	{		
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0));

		// where parent is set
		$this->db->where('parentID', $parentID); 
		
		$this->db->order_by('catOrder', 'asc');
		
		$query = $this->db->get('cats');
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_category_children($catID = '')
	{
		// select
		$this->db->select('cats.*, parentID as tempParentID, if(parentID>0, parentID+1, catID) AS parentOrder, (SELECT catName from '.$this->db->dbprefix.'cats WHERE '.$this->db->dbprefix.'cats.catID = tempParentID) AS parentName', FALSE);
		
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0));

		// get category by ID
		$this->db->where('parentID', $catID);
		
		$this->db->order_by('catOrder', 'asc');
		
		$query = $this->db->get('cats');
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}		
	}
	
	function get_cats_for_product_type($productTypeID)
	{
		// get cats for this product
		$this->db->join('cats', 'cat_type_map.catID = cats.catID', 'left');
		$this->db->order_by('catOrder');
		$query = $this->db->get_where('cat_type_map', array('productTypeID' => $productTypeID));
	
		if ($query->num_rows())
		{
			$catsArray = $query->result_array();
			$cats = array();			
			
			foreach($catsArray as $cat)
			{
				$cats[$cat['catID']] = $cat['catName'];
			}
	
			return $cats;
		}
		else
		{
			return FALSE;
		}	
	}
	
	function get_features_for_product_type($productTypeID)
	{
		// get cats for this product
		// get cats for this product
		$this->db->select('product_type_field_map.*, product_fields.* ');
		$this->db->join('product_fields', 'product_type_field_map.product_fieldsID = product_fields.product_fieldsID', 'left');
		$this->db->order_by('product_fieldsOrder', 'asc');
		$this->db->where( array('productTypeID' => $productTypeID) );
		
		$query = $this->db->get('product_type_field_map' );
		
		
	
		if ($query->num_rows())
		{
			$fieldsArray = $query->result_array();
			$fields = array();			
			
			foreach($fieldsArray as $field)
			{
				$fields[$field['product_fieldsID']] = $field['fieldName'];
			}
	
			return $fields;
		}
		else
		{
			return FALSE;
		}	
	}
	
	
	function get_cat_ids_for_product_type($productTypeID)
	{
		// get cats for this product
		$this->db->join('cats', 'shop_catmap.catID = cats.catID', 'left');
		$this->db->order_by('catOrder');
		$query = $this->db->get_where('cat_type_map', array('productTypeID' => $productTypeID));
	
		if ($query->num_rows())
		{
			$catsArray = $query->result_array();
			$catIDs = array();			
			
			foreach($catsArray as $cat)
			{
				$catIDs[] = $cat['catID'];
			}
	
			return $catIDs;
		}
		else
		{
			return FALSE;
		}	
	}
	
	function update_cat_product_types($productTypeID, $catsArray = '')
	{
		// delete cats
		$this->db->delete('cat_type_map', array('productTypeID' => $productTypeID, 'siteID' => $this->siteID));

		if ($catsArray)
		{
			foreach($catsArray as $catID => $cat)
			{
				if ($cat)
				{					
					$query = $this->db->get_where('cat_type_map', array('productTypeID' => $productTypeID, 'catID' => $catID, 'siteID' => $this->siteID));
					
					if (!$query->num_rows())
					{
						$this->db->insert('cat_type_map', array('productTypeID' => $productTypeID, 'catID' => $catID, 'siteID' => $this->siteID));
					}
				}
			}
		}

		return TRUE;
	}
	
	
	function update_type_fields($productTypeID, $fieldArray = '')
	{
		// delete cats
		$this->db->delete('product_type_field_map', array('productTypeID' => $productTypeID, 'siteID' => $this->siteID));

		if ($fieldArray)
		{
			foreach($fieldArray as $product_fieldsID => $field)
			{
				if ($field)
				{					
					$query = $this->db->get_where('product_type_field_map', array('productTypeID' => $productTypeID, 'product_fieldsID' => $product_fieldsID, 'siteID' => $this->siteID));
					
					if (!$query->num_rows())
					{
						$this->db->insert('product_type_field_map', array('productTypeID' => $productTypeID, 'product_fieldsID' => $product_fieldsID, 'siteID' => $this->siteID));
					}
				}
			}
		}

		return TRUE;
	}
	/*
	function get_bands()
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID));
		
		$this->db->order_by('multiplier', 'asc');
		
		$query = $this->db->get('shop_bands');
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_band($bandID)
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID));
		
		// get category by ID
		$this->db->where('bandID', $bandID);
		
		$query = $this->db->get('shop_bands', 1);
		
		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}		
	}
	
	function get_band_by_multiplier($multiplier = '')
	{
		// default where
		$this->db->where('siteID', $this->siteID);
		
		// where multiplier
		$this->db->where('multiplier', $multiplier);

		$query = $this->db->get('shop_bands', 1);
		
		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_modifiers($multiplier = '')
	{
		// default where
		$this->db->where('shop_modifiers.siteID', $this->siteID);

		// where band
		if ($multiplier)
		{
			$this->db->where('shop_bands.multiplier', $multiplier);
		}

		// join band
		$this->db->select('bandName, shop_bands.multiplier AS bandOrder, shop_modifiers.*', FALSE);
		$this->db->join('shop_bands', 'shop_bands.bandID = shop_modifiers.bandID');

		$this->db->order_by('bandOrder', 'asc');
		$this->db->order_by('shop_modifiers.multiplier', 'asc');
		
		$query = $this->db->get('shop_modifiers');

		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_modifier($modifierID)
	{
		// default where
		$this->db->where('shop_modifiers.siteID', $this->siteID);
		
		// get category by ID
		$this->db->where('modifierID', $modifierID);

		// join band
		$this->db->select('bandName, shop_modifiers.*', FALSE);
		$this->db->join('shop_bands', 'shop_bands.bandID = shop_modifiers.bandID');
		
		$query = $this->db->get('shop_modifiers', 1);
		
		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_modifier_by_multiplier($multiplier = '')
	{
		// default where
		$this->db->where('shop_modifiers.siteID', $this->siteID);
		
		// where multiplier
		$this->db->where('shop_modifiers.multiplier', $multiplier);

		// join band
		$this->db->select('bandName, shop_modifiers.*', FALSE);
		$this->db->join('shop_bands', 'shop_bands.bandID = shop_modifiers.bandID');
		
		$query = $this->db->get('shop_modifiers', 1);
		
		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_postages()
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID));
		
		$this->db->order_by('total', 'asc');
		
		$query = $this->db->get('shop_postages');
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_postage($postageID)
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID));
		
		// get category by ID
		$this->db->where('postageID', $postageID);
		
		$query = $this->db->get('shop_postages', 1);
		
		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}		
	}
	*/
	function get_product_types($catID = '', $search = '',  $limit = '')
	{
		// set limit from uri if set
		$limit = (!$limit && $limit != 'all') ? $this->siteVars['shopItemsPerPage'] : $limit;
		//log_message("error",'catID '.print_r($catID,true) );
		
		// get cat IDs
		if ($catID && !$productTypesArray = $this->get_catmap_product_type_ids($catID))
		{
			return FALSE;
		}
				
		// start cache
		$this->db->start_cache();

		// get products
		if ($catID)
		{
			// where category
			$this->db->where_in('productTypeID', $productTypesArray);		
		}
			
		// search
		if ($search)
		{
			$this->db->where('(typeName LIKE "%'.$this->db->escape_like_str($search).'%" OR tags LIKE "%'.$this->db->escape_like_str($search).'%" OR catNames LIKE "%'.$this->db->escape_like_str($search).'%" OR featureNames LIKE "%'.$this->db->escape_like_str($search).'%")');
		}
		
		
		// set order
		$order = FALSE;
		$uriArray = $this->uri->uri_to_assoc($this->uri_assoc_segment);
		foreach($uriArray as $key => $value)
		{
			if ($value == 'typeName' || $value == 'tags' || $value == 'featureNames' || $value == 'catNames')
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
			$this->db->order_by('typeName','desc');
		}
		
		
		$this->db->join('cat_type_map', 'cat_type_map.productTypeID = product_types.productTypeID', 'left');
		$this->db->join('cats', 'cat_type_map.catID = cats.catID', 'left');
		
		$this->db->join('product_type_field_map', 'product_type_field_map.productTypeID = product_types.productTypeID', 'left');
		$this->db->join('product_fields', 'product_type_field_map.product_fieldsID = product_fields.product_fieldsID', 'left');
		
		// default wheres
		$this->db->where(array('product_types.siteID' => $this->siteID, 'product_types.deleted' => 0));
		$this->db->group_by("productTypeID"); 
		
		$this->db->select('product_types.*,  GROUP_CONCAT(distinct catName SEPARATOR "," )  AS catNames, GROUP_CONCAT(distinct fieldName SEPARATOR "," )  AS featureNames  ', FALSE);
		// stop cache
		$this->db->stop_cache();
			
			
		// get total rows		
		$query = $this->db->get('product_types');
		$totalRows = $query->num_rows();
		//log_message("error",'product_types '.print_r($query->result_array(),true) );
		// init paging
		$this->core->set_paging($totalRows, $limit);
		$query = $this->db->get('product_types', $limit, $this->pagination->offset);
				
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
	
	//Get products in category of catSafe
	function get_product_types_by_catSafe($catSafe = '',$search='', $limit = '', $childrenProducts=false)
	{
		
		// set limit from uri if set
		$limit = (!$limit && $limit != 'all') ? $this->siteVars['shopItemsPerPage'] : $limit;
		
		// get products' ID from shop_catmap
		if ($catSafe && !$productsArray = $this->get_catmap_product_type_ids_by_catSafe($catSafe))
		{
			return FALSE;
		}
				
		// start cache
		$this->db->start_cache();

		// get products
		if ($catSafe)
		{
			// where category
			$this->db->where_in('productID', $productsArray);		
		}
		
		// only select products for this admin user
		if ($this->session->userdata('session_admin') && !@in_array('shop_all', $this->permission->permissions))
		{
			$this->db->where('userID', $this->session->userdata('userID'));
		}
		
		// get published products for admin
		if ($this->uri->segment(1) != 'admin')
		{
			$this->db->where('published', 1);
		}
		
		// search
		if ($search)
		{
			$this->db->where('(typeName LIKE "%'.$this->db->escape_like_str($search).'%" OR subtitle LIKE "%'.$this->db->escape_like_str($search).'%" OR description LIKE "%'.$this->db->escape_like_str($search).'%" OR catalogueID LIKE "%'.$this->db->escape_like_str($search).'%")');
		}
		
		// featured
		if ($featured)
		{
			$this->db->where('featured', 'Y');
		}

		// set order
		$order = FALSE;
		$uriArray = $this->uri->uri_to_assoc($this->uri_assoc_segment);
		if(empty($uriArray))
		{
			$this->db->order_by('price','asc');
		}
		foreach($uriArray as $key => $value)
		{
			if ($value == 'price' || $value == 'typeName' || $value == 'catalogueID' || $value == 'dateCreated' || $value == 'stock' || $value == 'published')
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
			if ($catSafe || $featured)
			{
				$this->db->order_by('productOrder','asc');
			}
			$this->db->order_by('dateCreated','desc');
		}
		
		// default wheres
		$this->db->where(array('product_types.siteID' => $this->siteID, 'product_types.deleted' => 0));
		
		// stop cache
		$this->db->stop_cache();
			
		// get total rows
		$query = $this->db->get('product_types');
		$totalRows = $query->num_rows();
		//log_message('error', 'featured product ids all:'. print_r($query->result_array(), true));
		// init paging
		if($limit!='all'){
			$this->core->set_paging($totalRows, $limit);
			$query = $this->db->get('product_types', $limit, $this->pagination->offset);
		}
		//log_message('error', 'featured product ids limit :'.$limit.print_r($query->result_array(), true));
		// flush cache
		$this->db->flush_cache();		
			
		if ($query->num_rows())
		{
		//		log_message('error', 'featured product ids:'. print_r($query->result_array(), true));
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	
	function get_product_types_by_tag($tag = '', $limit = '')
	{
		// set limit from uri if set
		$limit = (!$limit && $limit != 'all') ? $this->siteVars['shopItemsPerPage'] : $limit;

		// get rows based on this tag
		$tags = $this->tags->fetch_rows(array(
			'table' => 'product_types',
			'tags' => array(1, $tag),
			'limit' => $limit,
			'siteID' => $this->siteID
		));
		if (!$tags)
		{
			return FALSE;
		}

		// build tags array
		foreach ($tags as $tag)
		{
			$tagsArray[] = $tag['row_id'];
		}
		
		// look for products
		$this->db->where_in('productID', $tagsArray);

		// set order
		$order = FALSE;
		$uriArray = $this->uri->uri_to_assoc($this->uri_assoc_segment);
		foreach($uriArray as $key => $value)
		{
			if ($value == 'price' || $value == 'typeName' || $value == 'catalogueID' || $value == 'dateCreated')
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
			$this->db->order_by('productOrder','asc');
			$this->db->order_by('dateCreated','desc');
		}
		
		// default wheres
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0, 'published' => 1));
		$query = $this->db->get('product_types', $limit, $this->pagination->offset);

		$output = $query->result_array();

		$this->db->where_in('productID', $tagsArray);
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0, 'published' => 1));
		$query_total = $this->db->get('product_types'); 
		$config['total_rows'] = $query_total->num_rows();	
		$config['per_page'] = $limit;
		$config['full_tag_open'] = '<div class="pagination"><p>';
		$config['full_tag_close'] = '</p></div>';
		$config['num_links'] = 3;
		$this->pagination->initialize($config);
			
		if ($query->num_rows())
		{
			return $output;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_catmap_product_type_ids($catID)
	{
		// get rows based on this category
		$this->db->join('cats', 'cats.catID = cat_type_map.catID');
		$this->db->where('cats.catID', $catID);
		
		// get result
		$result = $this->db->get('cat_type_map');
		
		if ($result->num_rows())
		{
			$cats = $result->result_array();
			
			foreach ($cats as $cat)
			{
				$productTypesArray[] = $cat['productTypeID'];
			}
			
			return $productTypesArray;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_catmap_product_type_ids_by_catSafe($catSafe)
	{
		// get rows based on this category
		//select productID from cat_type_map join shop_cats on shop_cats.catID = cat_type_map.catID where shop_cats.catSafe=$catSafe group_by productID
		//select productID from cat_type_map where catID in (select catID from shop_cats where  (parentID=(select catID from shop_cats where catSafe=$catSafe) or catSafe=$catSafe ) )
		$this->db->join('cats', 'cats.catID = cat_type_map.catID');
		$this->db->where('cats.catSafe', $catSafe);
		$this->db->group_by('cat_type_map.productTypeID');
		// get result
		$result = $this->db->get('cat_type_map');
		
		if ($result->num_rows())
		{
			$cats = $result->result_array();
			
			foreach ($cats as $cat)
			{
				$productsArray[] = $cat['productTypeID'];
			}
			//log_message('error', 'product ids:'. print_r($productsArray, true));
			return $productsArray;
		}
		else
		{
			return FALSE;
		}
	}

	function get_all_product_types()
	{
		$this->db->where('siteID', $this->siteID);
		$this->db->where('deleted', 0);
		
		$this->db->order_by('typeName','asc');
		
		$query = $this->db->get('product_types');
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function get_latest_product_types($catSafe = '', $limit = 3)
	{
		$cat = $this->get_category_by_reference($catSafe);
		
		$this->db->where('product_types.siteID', $this->siteID);
		$this->db->where('product_types.deleted', 0);
		
		
		if ($catSafe)
		{
			$this->db->where('cat_type_map.catID', $cat['catID']);
			
			$this->db->select('product_types.*');

			$this->db->join('cat_type_map', 'cat_type_map.productTypeID = product_types.productTypeID');
			$this->db->group_by('product_types.productTypeID');
		}
		
		$this->db->order_by('product_types.dateCreated', 'desc');
		
		$query = $this->db->get('product_types', $limit);
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	

	function get_product_type($productTypeID)
	{
		if ($productTypeID)
		{
			$this->db->where(array('product_types.siteID' => $this->siteID, 'deleted' => 0));
			
			$this->db->where('product_types.productTypeID', $productTypeID);
						
			// join and group
			$this->db->select('product_types.*');
			$this->db->join('cat_type_map', 'cat_type_map.productTypeID = product_types.productTypeID', 'left');
			$this->db->group_by('product_types.productTypeID');	
			
			$query = $this->db->get('product_types', 1);
			
			$output = $query->row_array();

			return $output;
		}
		else
		{
			return FALSE;
		}
	}


	function get_files()
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0));
		
		$query = $this->db->get('files');
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_file($fileID)
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0));

		$this->db->where('fileID', $fileID);
		
		$query = $this->db->get('files', 1);
		
		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_upsells()
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0));
				
		$this->db->order_by('upsellOrder', 'desc');
		
		$query = $this->db->get('shop_upsells');
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function get_upsell($upsellID)
	{
		// default where
		$this->db->where(array('siteID' => $this->siteID, 'deleted' => 0));
		
		// get upsell by ID
		$this->db->where('upsellID', $upsellID);
		
		$this->db->order_by('upsellOrder', 'asc');
		
		$query = $this->db->get('shop_upsells', 1);
		
		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}		
	}

	function captcha()
	{
 		$this->load->plugin('captcha');		
		
		// load captcha
		$syls = array('ble', 'ond', 'san', 'tle', 'ile', 'bre', 'aps', 'que', 'yil', 'ste', 'tre', 'ale', 'sho', 'spi', 'dal', 'clo', 'fal', 'gul', 'she');
		$randSyls = array_rand($syls, 2);
		$randomWord = '';
		foreach ($randSyls as $x)
		{
			$randomWord .= $syls[$x];
		}
		
		$vals = array(
					'word'		 => $randomWord,
					'img_path'	 => './static/uploads/captcha/',
					'img_url'	 => '/static/uploads/captcha/',
					'img_width'	 => '100',
					'img_height' => 30,
					'expiration' => 7200
				);
		$cap = create_captcha($vals);

		$data = array(
					'captcha_id' => '',
					'captcha_time' => $cap['time'],
					'ip_address'  => $this->input->ip_address(),
					'word' => $cap['word']
				);

		$query = $this->db->insert_string('captcha', $data);
		$this->db->query($query);

		return $cap;
	}


	function get_items($ids)
	{
		$this->db->where_in('productTypeID', $ids);
		$query = $this->db->get('product_types');

		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function unpack_item($key, $quantity)
	{
		// get the master array (unserialize) then get the info from db
		$keys = unserialize($key);
		$product = $this->get_product($keys['productTypeID']);				
		$variation1 = @$this->get_variation($keys['variation1']);
		$variation2 = @$this->get_variation($keys['variation2']);
		$variation3 = @$this->get_variation($keys['variation3']);

		
		// create new cart array, based on serial //Edited by smy: remove  'catID' => $product['catID'], a item has more than one catID
		$item = @array('productTypeID' => $keys['productTypeID'],'catalogueID' => $product['catalogueID'], 'typeName' => $product['typeName'], 'price' => $product['price'], 'quantity' => $quantity, 'variation1' => $variation1['variation'], 'variation1Price' => $variation1['price'], 'variation2' => $variation2['variation'], 'variation2Price' => $variation2['price'], 'variation3' => $variation3['variation'], 'variation3Price' => $variation3['price'], 'fileID' => $product['fileID'], 'bandID' => $product['bandID'], 'freePostage' => $product['freePostage'], 'stock' => $product['stock']);
		
		
		// add variation1 price modifier
		if ($variation1['price'])
		{
			$item['price'] += $variation1['price'];
		}

		// add variation2 price modifier
		if ($variation2['price'])
		{
			$item['price'] += $variation2['price'];
		}

		// add variation2 price modifier
		if ($variation3['price'])
		{
			$item['price'] += $variation3['price'];
		}

		return $item;
	}

	

	function get_user()
	{
		if ($userID = $this->session->userdata('userID'))
		{			
			$query = $this->db->get_where('users', array('userID' => $userID), 1);
			
			$output = $query->row_array();

			return $output;
		}
		else
		{
			return FALSE;
		}
	}

	function get_user_by_email($email)
	{
		// default wheres
		$this->db->where('siteID', $this->siteID);		
		
		$this->db->where('email', $email);

		// grab
		$query = $this->db->get('users', 1);

		if ($query->num_rows())
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}		
	}

	
	function update_related_product_table($fieldName="", $fieldType="inputbox", $valueSet="", $defaultValue="")
	{
		$type= null;
		$constraint= null;
		$enumValues=explode(',', $valueSet);
		
		$enumString='' ;
		foreach($enumValues as $value)
		{
			$enumString.= ( (empty($enumString))?"":",") ." '".$value."' ";
		}
		switch ($fieldType)
		{
			case 'input':
				$type= 'VARCHAR';
				$constraint= '100';
			break;
	
			case 'textbox':
				$type= 'text';
				$constraint= '100';
			break;
			case 'combo':
				$type= 'ENUM';				
				$constraint= $enumString;
			break;
			case 'datetime':
				$type= 'timestamp';				
				
			break;
			case 'int':
				$type= 'int';				
				$constraint= '11';
			break;
			default:
				$type= 'VARCHAR';
				$constraint= '100';
		}
		
		//-------Hack----
		if( $type== 'ENUM' )
			{
				$type= 'VARCHAR';
				$constraint= '200';
			
			}
		$fields = array(
                    $fieldName => array(
						'type' => $type,
                        'constraint' => $constraint,
						'default' => $defaultValue,
						 'null' => TRUE,
                                    )
                );
		$this->load->dbforge();
		//Add/modify requested product table
		if ($this->db->field_exists($fieldName, 'requested_products'))
		{		
			$this->dbforge->modify_column('requested_products', $fields);
		}else
		{
			$this->dbforge->add_column('requested_products', $fields);
		}
		//Add/modify quoted product table
		if ($this->db->field_exists($fieldName, 'quoted_products'))
		{		
			$this->dbforge->modify_column('quoted_products', $fields);
		}else
		{
			$this->dbforge->add_column('quoted_products', $fields);
		}
		
	
		
	}
}