<?php
/**

 * @package		
 * @author		LIPENG LI
 */

// ------------------------------------------------------------------------

class Requests_model extends CI_Model {
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

	function get_product_types($catID = '', $search = '',  $limit = '')
	{
		
		
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
	
	
	function get_features_for_product_type($productTypeID)
	{
		// get cats for this product
		$this->db->select('product_type_field_map.*, product_fields.* ');
		$this->db->join('product_fields', 'product_type_field_map.product_fieldsID = product_fields.product_fieldsID', 'left');
		$this->db->order_by('product_fieldsOrder', 'asc');
		$this->db->where( array('productTypeID' => $productTypeID) );
		
		$query = $this->db->get('product_type_field_map' );
	
		if ($query->num_rows())
		{
			$fieldsArray = $query->result_array();
			/*
			$fields = array();			
			
			foreach($fieldsArray as $field)
			{
				$fields[$field['product_fieldsID']] = $field['fieldName'];
			}
			*/
			return $fieldsArray;
		}
		else
		{
			return FALSE;
		}	
	}
	
	function get_request_ids_of_creater($createrID)
	{

		$this->db->where('createrID', $createrID);
		
		// get result
		$result = $this->db->get('requests');
		
		if ($result->num_rows())
		{
			$requests = $result->result_array();
			$requestIDsArray=null;
			foreach ($requests as $request)
			{
				$requestIDsArray[] = $request['requestID'];
			}
			
			return $requestIDsArray;
		}
		else
		{
			return FALSE;
		}
	
	
	}
	
	//Given user ID
	//From user to find all requested products
	//User -> Requests -> Request_product_map ->requested product
	//Given requested product status
	//Requested_product -> request_product_map ->requests ->users & clients 
	//This is function to get all requests, or requests created by certain user
	//To get all accepted requests or accepted by certain user need use function get_accepted_requests
	//$userID: creater ID
	
	
	function get_requests($createrID = '', $accepterID='', $status='', $search = '',  $limit = '')
	{
		
		// start cache
		$this->db->start_cache();
		// set limit from uri if set
		$limit = (!$limit && $limit != 'all') ? $this->siteVars['shopItemsPerPage'] : $limit;
		
		if ($createrID )
		{
			$this->db->where('requested_products.creatorID', $createrID);		
		}
		if ($accepterID )
		{
			$this->db->where('requested_products.accepterID', $accepterID);		
		}	
		if ($status )
		{
			
			if( strripos($status, ',' )  )
			{
				$statuses = explode(',', $status);			
				$this->db->where_in('status', $statuses);	
			}else{
				$this->db->where('status', $status);		
			}
		}	
		
		
		
		
		// search
		if ($search)
		{
			$fields = $this->db->field_data('requested_products');
			$searchString="";
			foreach ($fields as $field)
			{				
				log_message('error',  $field->type . $field->name);
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
		
		$this->db->select('requested_products.*, clients.clientName, reps . firstName as repName, accepters . firstName as accepterName   ', FALSE);
			
		$this->db->join('requests', 'requests.requestID =requested_products.requestID', 'left');
		$this->db->join('clients', 'requests.clientID = clients.clientID', 'left');
		$this->db->join('users reps', 'reps . userID = '.$this->db->dbprefix.'requested_products.creatorID', 'left');
		$this->db->join('users accepters', 'accepters . userID = '.$this->db->dbprefix.'requested_products.accepterID', 'left');
		
		
		
		
	// stop cache
		$this->db->stop_cache();
			
			
		// get total rows		
		$query = $this->db->get('requested_products');
		$totalRows = $query->num_rows();
		
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
	
	
	function get_requested_product($requestedProductID = '')
	{
		
		
		if ($requestedProductID )
		{
			$this->db->where('requested_products.productID', $requestedProductID);		
		}else
		{
			return FALSE;			
		}
	
			
		$this->db->select('requested_products.*, clients.clientName, reps . firstName as repName, accepters . firstName as accepterName   ', FALSE);
			
		$this->db->join('requests', 'requests.requestID =requested_products.requestID', 'left');
		$this->db->join('clients', 'requests.clientID = clients.clientID', 'left');
		$this->db->join('users reps', 'reps . userID = '.$this->db->dbprefix.'requested_products.creatorID', 'left');
		$this->db->join('users accepters', 'accepters . userID = '.$this->db->dbprefix.'requested_products.accepterID', 'left');
		
			
		// get total rows		
		$query = $this->db->get('requested_products');
		//log_message("error",'query: '.print_r($query,true) );
		if ($query->num_rows()==1)
		{
			$products= $query->result_array() ;
			return $products[0];
		}
		else
		{
			return FALSE;
		}
	}
	
	//Get request detail, a request contains several requested products
	function get_client_request($requestID)
	{
		if ($requestID )
		{
			$this->db->where('requests.requestID', $requestID);		
		}else
		{
			return FALSE;			
		}
	
			
		$this->db->select('requests.*, clients.clientName, users.firstName as repName ', FALSE);
			
		$this->db->join('clients', 'requests.clientID = clients.clientID', 'left');
		$this->db->join('users', 'users.userID = requests.creatorID', 'left');
	
		
			
		// get total rows		
		$query = $this->db->get('requests');
		//log_message("error",'query: '.print_r($query,true) );
		if ($query->num_rows()==1)
		{
			$requests= $query->result_array() ;
			return $requests[0];
		}
		else
		{
			return FALSE;
		}
	
	}
	
	function get_requested_products_of_request($requestID )
	{
		if ($requestID )
		{
			$this->db->where('requested_products.requestID', $requestID);		
		}else
		{
			return FALSE;			
		}
		
		$this->db->order_by('requested_products.dateCreated','asc');
			
		$this->db->select('requested_products.*, clients.clientName, reps . firstName as repName, accepters . firstName as accepterName   ', FALSE);
			
		$this->db->join('requests', 'requests.requestID =requested_products.requestID', 'left');
		$this->db->join('clients', 'requests.clientID = clients.clientID', 'left');
		$this->db->join('users reps', 'reps . userID = '.$this->db->dbprefix.'requested_products.creatorID', 'left');
		$this->db->join('users accepters', 'accepters . userID = '.$this->db->dbprefix.'requested_products.accepterID', 'left');
					
		// get total rows		
		$query = $this->db->get('requested_products');
		
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
		
	
	}
	
	function get_quote($quotedProductID = '')
	{
		if ($quotedProductID )
		{
			$this->db->where('quoted_products.quotedProductID', $quotedProductID);		
		}else
		{
			return FALSE;			
		}
		$this->db->select('quoted_products.* , orderQuantities,  typeID ', FALSE);
			
		$this->db->join('requested_products', 'quoted_products.requestedProductID =requested_products.productID', 'left');
		
			
		// get total rows		
		$query = $this->db->get('quoted_products');
		//log_message("error",'query: '.print_r($query,true) );
		if ($query->num_rows()==1)
		{
			$products= $query->result_array() ;
			return $products[0];
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_quotes_for_product($requestedProductID = '')
	{
		if ($requestedProductID )
		{
			$this->db->where('quoted_products.requestedProductID', $requestedProductID);		
		}else
		{
			return FALSE;			
		}
	
			
		$this->db->select('quoted_products.*, users.firstName as quoterName  ', FALSE);
		$this->db->join('users', 'users.userID = quoted_products.userID', 'left');
		$this->db->order_by('dateQuoted','desc');
		
		
		// get total rows		
		$query = $this->db->get('quoted_products');
		//log_message("error",'query: '.print_r($query,true) );
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	
	}
	
	function search_quotes($limit = '')
	{
			$productName = $this->input->post('productName');
			$material = $this->input->post('material');
			$tags = $this->input->post('tags');
			$colour = $this->input->post('colour');
			$factoryDetail = $this->input->post('factoryDetail');
			$dateQuotedStart = $this->input->post('dateQuotedStart');
			$dateQuotedEnd = $this->input->post('dateQuotedEnd');
		
		// start cache
		$this->db->start_cache();

			
			if ( $productName )
			{		
				$this->db->like('productName', $productName); 
				
			}
			if ( $material )
			{	
				$this->db->like('material', $material); 
				
			}
			if ( $colour )
			{	
				$this->db->like('colour', $colour); 
				
			}
			if ( $factoryDetail )
			{	
				$this->db->like('factoryDetail', $factoryDetail); 
				
			}
			if ( $dateQuotedStart )
			{		
				$start = date('Y-m-d',strtotime($dateQuotedStart) );
				$this->db->where('DATE(`dateQuoted`) >= ',$start  );
				
			}
			if ( $dateQuotedEnd )
			{	
				$end = date( 'Y-m-d',strtotime($dateQuotedEnd) );
				$this->db->where('DATE(`dateQuoted`) <= ',$end  );
			}
			
		$this->db->select('quoted_products.*, users.firstName as accepterName  ', FALSE);
		$this->db->join('users', 'users.userID = quoted_products.userID', 'left');
		$this->db->order_by('dateQuoted','desc');
		// stop cache
		$this->db->stop_cache();
			
			
		// get total rows		
		$query = $this->db->get('quoted_products');
		$totalRows = $query->num_rows();
		
		$this->core->set_paging($totalRows, $limit);
		// get total rows		
		$query = $this->db->get('quoted_products', $limit, $this->pagination->offset);
		//log_message("error",'query: '.print_r($query,true) );
		if ($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	
	}
	
	function get_quoted_prices($quotedProductID="")
	{
		if ($quotedProductID )
		{
			$this->db->where('quote_prices.quotedProductID', $quotedProductID);		
		}else
		{
			return FALSE;			
		}
	
			
		$this->db->select('quote_prices.*  ', FALSE);	
		$this->db->order_by('quotePriceOrder','desc');
		
		
		// get total rows		
		$query = $this->db->get('quote_prices');

		//log_message("error",'query: '.print_r($query,true) );
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