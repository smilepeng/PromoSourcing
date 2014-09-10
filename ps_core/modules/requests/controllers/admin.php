<?php


// ------------------------------------------------------------------------

class Admin extends MX_Controller {

	// set defaults
	var $table = 'requests';						// table to update
	var $includes_path = '/includes/admin';		// path to includes for header and footer
	var $redirect = '/admin/requests/viewall';		// default redirect
	var $objectID = 'requestID';					// default unique ID	
	var $permissions = array();
	var $currentUserID;
	var $language;
	var $isCN=false;
	var $languageAffix='';
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
		
		$this->currentUserID = $this->session->userdata('userID');
		$this->language = $this->session->userdata('language');
		if( strtoupper($this->language ) == 'CN')
		{
			$this->isCN = true;
			$this->languageAffix='_cn';
		}
		//  load models and libs
		$this->load->model('requests_model', 'request');
		
		$this->load->library('tags');
	}
	
	function index()
	{
		redirect($this->redirect);
	}
	
	
	function parseImages($requests,  $attrs='class="pic thumb" ')
	{
		if( empty($requests))
			return $requests;
		//replace file names it to html images 
		for($i=0; $i<count($requests); $i++)
		{
			$imgs = explode(',', $requests[$i]['imgs']);
			if( count($imgs)>0 )
			{
				$img = $imgs[0];				
				$requests[$i]['imgs'] = $this->template->parse_image($img , $attrs);
			}else
			{
				$requests[$i]['imgs'] =   '<img src="'.$this->config->item('base_url'). $this->config->item('staticFolder').'/images/'. $this->config->item('noPictureFile') .'"  name="'. $requests[$i]['productName']  .'" '. $attrs .' />';
			}
			
		}
	
		return $requests;
	}


	function summary()
	{
		if (!$this->session->userdata('session_user'))
		{
			redirect('/admin/login/'.$this->core->encode($this->uri->uri_string()));
		}

		$limit =  10 ;


			$recentRequests =$this->request->get_requests('', '','O,A,Q','',$limit);
			$output['recentRequests'] = $this->parseImages($recentRequests);
			$recentAcceptedRequests =$this->request->get_requests('', '', 'A','',$limit);
			$output['recentAcceptedRequests'] = $this->parseImages($recentAcceptedRequests);
			$recentCompletedRequests =$this->request->get_requests('', '', 'Q','',$limit);
			$output['recentCompletedRequests'] = $this->parseImages($recentCompletedRequests);

		
			
		$this->load->view( $this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('summary'.$this->languageAffix, $output);
		$this->load->view($this->includes_path.'/footer');	
	
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
				$where = '(requestID LIKE "%'.$this->db->escape_like_str($query).'%"  )';
			}
			else
			{
				$where = '(requestID LIKE "%'.$this->db->escape_like_str($query).'%" )';
			}
		}

		// output requests
		$output = $this->core->viewall($this->table, $where);

		

		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('viewall',$output);
		$this->load->view($this->includes_path.'/footer');
	}
	
	
	function viewall_open()
	{
		// set limit
		$limit =  $this->site->config['paging'] ;
		$search = $this->input->post('searchbox');
		$status = 'O';
		
		
			$open_requests =$this->request->get_requests('', '', $status,$search,$limit);
			$output['requests'] = $this->parseImages($open_requests);
		//log_message('error', print_r($output, true));	
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('viewall_open'.$this->languageAffix,$output);
		$this->load->view($this->includes_path.'/footer');
	
	}
	
	//Accepted or In-processing
	function viewall_accepted()
	{
		// set limit
		$limit =  $this->site->config['paging'] ;
		
		// get products
		$requests = $this->request->get_requests('', '', 'A', $this->input->post('searchbox'),  $limit);
		$output['requests'] = $this->parseImages($requests);		
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('viewall_accepted',$output);
		$this->load->view($this->includes_path.'/footer');
	
	}
	
	
	function viewall_archived()
	{
		// set limit
		$limit =  $this->site->config['paging'] ;
		// get products
		$requests = $this->request->get_requests('', '', 'Q', $this->input->post('searchbox'),  $limit);
		$output['requests'] = $this->parseImages($requests);		
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('viewall_archived',$output);
		$this->load->view($this->includes_path.'/footer');
	
	}


		function archive()
	{
		// set limit
		$limit =  $this->site->config['paging'] ;
		// get products
		$requests = $this->request->get_requests('', '', 'Q', $this->input->post('searchbox'),  $limit);
		$output['requests'] = $this->parseImages($requests);		
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('archive',$output);
		$this->load->view($this->includes_path.'/footer');
	
	}


	
	function view_my_summary()
	{
		// set limit
	
		// get products


		// set limit
		$limit =  $this->site->config['paging'] ;
		
		$search = $this->input->post('searchbox');
		$status = '';		
		if( $_POST )
		{
		
		
		}else
		{
			if (in_array('requests_edit', $this->permission->permissions)){
				$open_requests =$this->request->get_requests($this->currentUserID, '', $status,$search,$limit);
				$output['requests'] = $this->parseImages($open_requests);
				
			}elseif (in_array('requests_accept', $this->permission->permissions)){
				$open_requests =$this->request->get_requests( '', $this->currentUserID,$status,$search,$limit);
				$output['requests'] = $this->parseImages($open_requests);
			}

			$this->load->view($this->includes_path.'/header'.$this->languageAffix );
			$this->load->view('my_summary'.$this->languageAffix,$output);
			$this->load->view($this->includes_path.'/footer');
		}
	
	}

	//Sourcing team accepted
	function view_my_accepted()
	{
		// set limit
		$limit =  $this->site->config['paging'] ;
		
		$requests = $this->request->get_requests('',$this->currentUserID, 'A', $this->input->post('searchbox'), $limit);
		$output['requests'] = $this->parseImages($requests);
	

		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('view_my_accepted'.$this->languageAffix,$output);
		$this->load->view($this->includes_path.'/footer');
	
	}
	
	function view_my_quoted()
	{
		// set limit
		$limit =  $this->site->config['paging'] ;
		
		// get products
			
		$requests = $this->request->get_requests('',$this->currentUserID, 'Q', $this->input->post('searchbox'), $limit);
		$output['requests'] = $this->parseImages($requests);	

		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('view_my_quoted'.$this->languageAffix,$output);
		$this->load->view($this->includes_path.'/footer');
	
	}
	//Sale Team pages
	function view_my_open()
	{
		// set limit
		$limit =  $this->site->config['paging'] ;
		$search = $this->input->post('searchbox');
		$status = '';
		
		if (in_array('requests_edit', $this->permission->permissions)){
			$open_requests =$this->request->get_requests($this->currentUserID, 'O', $status,$search,$limit);
			$output['requests'] = $this->parseImages($open_requests);
			
			$this->load->view($this->includes_path.'/header'.$this->languageAffix );
			$this->load->view('view_my_open',$output);
			$this->load->view($this->includes_path.'/footer');
		}
	
	}
	
	function view_my_inprocess()
	{
		// set limit
		$limit =  $this->site->config['paging'] ;
		
		// get products
		$requests = $this->request->get_requests($this->currentUserID,'', 'A', $this->input->post('searchbox'), $limit);
		$output['requests'] = $this->parseImages($requests);
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('view_my_inprocess',$output);
		$this->load->view($this->includes_path.'/footer');
	
	}
	function view_my_completed()
	{
		// set limit
		$limit =  $this->site->config['paging'] ;
		
		// get products
		$requests = $this->request->get_requests($this->currentUserID,'', 'Q', $this->input->post('searchbox'), $limit);
		$output['requests'] = $this->parseImages($requests);
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('view_my_completed',$output);
		$this->load->view($this->includes_path.'/footer');
	
	}
		
	function add()
	{
		// check permissions for this page
		if (!in_array('requests_edit', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
		
		// required	
		$this->core->required = array(
			'clientName' => array('label' => 'Client Name', 'rules' => 'required|trim')
		);		
		
		if ($this->input->post('cancel'))
		{			
			redirect('/admin/requests/view_my_open');
		}
		else
		{	
			$output['requested_products'] = null;
			
			// set date
			$this->core->set['dateCreated'] = date("Y-m-d H:i:s");
			// update
			if (count($_POST) && $this->core->update('clients') )
			{
				$creatorID = $this->session->userdata('userID');
				$clientID = $this->db->insert_id();
				$this->core->set['dateCreated'] = date("Y-m-d H:i:s");
				$this->core->set['creatorID'] = $creatorID;
				$this->core->set['clientID'] = $clientID;
				if ($this->core->update('requests'))
				{
					$requestID = $this->db->insert_id();												
				}			
				
			}
			
		}

		
		
		// get product types
		$output['productTypes'] = $this->request->get_product_types('','',100);
		$output['data'] =array();
		
		// templates
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('add_request', $output);
		$this->load->view($this->includes_path.'/footer');
	}
	
	function view_client_request( $requestID)
	{
		// check permissions for this page
		if (!in_array('requests_edit', $this->permission->permissions))
		{
			redirect('/admin/summary');
		}
	
		$output= array();
		//Request Detail
		$output['request'] = $this->request->get_client_request($requestID);
		//Requested Products
		$requestedProducts= $this->request->get_requested_products_of_request($requestID);
		for($i=0; $i<count($requestedProducts); $i++)
		{
			$requestedProducts[$i] = 	$this->get_request_product_data($requestedProducts[$i] );
		}
		
		$output['requestedProducts'] = $requestedProducts;
		// get product types
		$output['productTypes'] = $this->request->get_product_types('','',100);
		
		
		
		// templates
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('view_client_request', $output);
		$this->load->view($this->includes_path.'/footer');
	}

	function accept( $requestedProductID)
	{
		if (in_array('requests_accept', $this->permission->permissions))
		{
			//Update status of requested product
			$object= array('status'=>'A','accepterID'=> $this->session->userdata('userID'),'dateAccepted'=>date("Y-m-d H:i:s") );
			
			
			$this->db->where('productID', $requestedProductID);
		
			if(	$this->db->update('requested_products', $object) )
			{
				//Record operation
				//log_message('error',  $requestedProductID);
				$this->add_request_operation($requestedProductID, 'Accept Request',$this->session->userdata('userID'),'' );
				redirect('admin/requests/view_request/'.$requestedProductID);
			}
			
			
		}else{
			
		
		}		
		
	}
	
	function release( $requestedProductID)
	{
		if ( in_array('requests_release', $this->permission->permissions) )
		{
			//Update status of requested product
			$object= array('status'=>'O','accepterID'=> null ,'dateAccepted'=>null );
			$this->db->where('productID', $requestedProductID);

			if(	$this->db->update('requested_products', $object) )
			{
				$this->add_request_operation($requestedProductID, 'Release Request',$this->session->userdata('userID'),'' );
				redirect('/admin/requests/view_request/'.$requestedProductID);
			}
		}else{
			
			echo "No permission to make this operation.";
		}		
	}

	function add_request_operation($requestedProductID=0, $operationName='', $operaterID=0, $note='' )
	{
		
		$this->core->set['requestedProductID'] 		=$requestedProductID;
		$this->core->set['operationName'] 			=$operationName;
		$this->core->set['operaterID'] 				=$operaterID;
		$this->core->set['dateOperated'] 			= date("Y-m-d H:i:s");
		$this->core->set['note'] 			= $note;
		$this->core->update('request_operations');
	}
		
	function view_request($requestedProductID)
	{
		
		// check permissions for this page
		if (!$this->session->userdata('session_admin'))
		{
			redirect('/admin/summary');
		}
		
		//Request Info
		$output['requestedProduct'] = $this->request->get_requested_product($requestedProductID);
		
		$output['requestedProduct'] = $this->get_request_product_data( $output['requestedProduct'] );
		
		//Quotes info
		$productTypeID = $output['requestedProduct']['typeID'];
		$quotes =$this->request->get_quotes_for_product($requestedProductID);
		//log_message('error', 'quotes print'. print_r( $output['quotes'] ,true));
		
		$output['quotes'] = ($quotes) ? $this->get_quotes_data( $quotes,$productTypeID  ) : array();
		
		$output['noQuote'] = empty($quotes);
		//log_message('error', '('.print_r($quotes, true).')'.$output['noQuote']  );
		// templates
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		
		$this->load->view(( $this->isCN )?'view_request_CN':'view_request', $output);
		$this->load->view($this->includes_path.'/footer');
	}
	
	//Parse image string and add feature details
	function get_request_product_data( $requestedProduct )
	{
		 $requestedProduct['images'] = $this->template->parse_image_string( $requestedProduct ['imgs'] );	
		 $productTypeID = $requestedProduct['typeID'];
			
		$productFields = $this->request->get_features_for_product_type($productTypeID);
		$formString='';
		if( !empty($productFields)  )
		{
			foreach($productFields as $field)
			{
				$formString.='<p class="inline-label"> <label class="label"  for="'. $field['fieldSafe'].'">'. ( ( $this->isCN )?$field['fieldNameCN'] :$field['fieldName'] ).':</label><span class="fieldValue">'. (array_key_exists($field['fieldSafe'], $requestedProduct )?$requestedProduct[$field['fieldSafe']]:''). '</span>';
			
				
				$formString.='   </p>';
			}
		}
		$requestedProduct['featuresDetail'] = $formString; 
		 
		return $requestedProduct;
	
	}
	
	function get_quote_data( $quote )
	{
		$productFields = $this->request->get_features_for_product_type($quote['typeID']);
		$prices= $this->request->get_quoted_prices( $quote['quotedProductID'] );
		$quotedPrices='<p class="inline-label"> <label class="label"  for="orderQuantity">'. (($this->isCN)?'产品单价':'Unit Price').':</label> ';

		if($prices)
		{
			$quotedPrices.='<table class="unit_prices"><thead><td>'. (($this->isCN)?'产品数量':'Quantity').'</td><td>'. (($this->isCN)?'产品单价':'Unit Price').'</td></thead>';
			foreach($prices as $price )
			{	
				$quotedPrices .= '<tr><td>'. $price['quantity']."</td><td>" .(empty($price['price'])?$price['price']:'n/a') .'</td></tr>';
			}
			$quotedPrices .="</table>";
		}else
		{
			$quotedPrices .= (($this->isCN)?'无价格提供':'No price available');
		}
		$quotedPrices .='  </p>';
		
		$quote['quotedPrices']=$quotedPrices;
		$quote['images'] = $this->template->parse_image_string( $quote['imgs'] );
		$featureString='';
		foreach($productFields as $field)
		{
			$featureString.='<p class="inline-label"> <label class="label" for="'. $field['fieldSafe'].'">'.(($this->isCN)? $field['fieldNameCN']: $field['fieldName']).':</label><span class="fieldValue">'. (array_key_exists($field['fieldSafe'],$quote )?$quote[$field['fieldSafe']]:''). '</span>';
			$featureString.='   </p>';
		}
		
		$quote['quotedFeaturesDetail']=$featureString;
		return $quote;
	
	}
	function get_quotes_data( $quotes, $productTypeID ='1')
	{
		//log_message('error', print_r($quotes, true). $productTypeID);
		$productFields = $this->request->get_features_for_product_type($productTypeID);
		
		if ( count($quotes)>0 &&  $productTypeID)
		{
			
			for($i=0; $i< count($quotes ); $i++)
			{	
				$quote= $quotes[$i];
				$prices= $this->request->get_quoted_prices($quotes[$i]['quotedProductID']);
				$quotedPrices='<p class="inline-label"> <label class="label"  for="orderQuantity">'. (($this->isCN)?'产品单价':'Unit Price').':</label> ';

				if($prices)
				{
					$quotedPrices.='<table class="unit_prices"><thead><td>'. (($this->isCN)?'产品数量':'Quantity').'</td><td>'. (($this->isCN)?'产品单价':'Unit Price').'</td></thead>';
					foreach($prices as $price )
					{
						
						$quotedPrices .= '<tr><td>'. $price['quantity']."</td><td>" .(!empty($price['price'])?'$'.$price['price']:'n/a') .'</td></tr>';
					}
					$quotedPrices .="</table>";
				}else
				{
					$quotedPrices .= (($this->isCN)?'无价格提供':'No price available');
				}
				$quotedPrices .='  </p>';
				
				
				
				
				
				$quotes[$i]['quotedPrices']=$quotedPrices;
				$quotes[$i]['images'] = $this->template->parse_image_string( $quotes[$i]['imgs'] );
				$featureString='';
				foreach($productFields as $field)
				{
					$featureString.='<p class="inline-label"> <label class="label" for="'. $field['fieldSafe'].'">'.(($this->isCN)? $field['fieldNameCN']: $field['fieldName']).':</label><span class="fieldValue">'. (array_key_exists($field['fieldSafe'],$quote )?$quote[$field['fieldSafe']]:''). '</span>';
				
					
					$featureString.='   </p>';
				}
				
				$quotes[$i]['quotedFeaturesDetail']=$featureString;
				
			}
			
		}
		
		return $quotes;
	
	}
		
	function add_requested_product_ajax()
	{
		
		// check permissions for this page
		if (!in_array('requests_edit', $this->permission->permissions))
		{
			echo '{"status":"error"}';
			die();
		}
				
		// required
		$this->core->required = array(
			'productName' => 'Product name'
			
		);

		// set date
		$this->core->set['dateCreated'] = date("Y-m-d H:i:s");
		$this->core->set['creatorID'] = $this->session->userdata('userID');
		$this->core->set['status'] = 'O';
		
		// get values
		$output['data'] = $this->core->get_values('requested_products');	
		$data = $output['data'] ;
		
		/*
		
		$productID = $this->input->post('productID');
		// where
		$objectID = array('productID' => $productID);
		*/
		$output= array();
		
		if ( count($_POST) && $this->core->update('requested_products' ) )
		{
			// get insert id
			$requestedProductID = $this->db->insert_id();
			
			//Add operation record
			$this->add_request_operation($requestedProductID, 'Add Request',$this->session->userdata('userID'),'' );
			
			/*
			$output['data']  = $this->request->get_requested_product($requestedProductID);
			//log_message("error",'data '.print_r($output['data'],true) );
			$productTypeID = $output['data']['typeID'];
	
			$productFields = $this->request->get_features_for_product_type($productTypeID);
			$formString='';
			foreach($productFields as $field)
			{
				$formString.='<label for="'. $field['fieldSafe'].'">'. $field['fieldName'].':</label><span class="fieldValue">'. (array_key_exists($field['fieldSafe'], $data )?$data[$field['fieldSafe']]:''). '</span>';
			
				
				$formString.='   <br class="clear" />';
			}
			$output['featuresDetail'] = $formString; 
			*/
			$output['requestedProduct'] = $this->request->get_requested_product($requestedProductID);
			$output['requestedProduct'] = $this->get_request_product_data( $output['requestedProduct'] );
			$this->load->view('requested_product_detail_ajax',$output);
		}
				
	}
	
	function save_requested_product_ajax()
	{
		
		// check permissions for this page
		if (!in_array('requests_edit', $this->permission->permissions))
		{
			echo '{"status":"error"}';
			die();
		}
				
		// required
		$this->core->required = array(
			'productName' => 'Product name'
			
		);

					
		// set date
		$this->core->set['dateModified'] = date("Y-m-d H:i:s");
		
		
		$requestedProductID =  (int)$this->input->post('productID');
		// where
		$objectID = array('productID' => $requestedProductID);
		
		$output= array();
		//log_message('error', print_r($_POST, true) . $requestedProductID);
		if ( count($_POST) && $this->core->update('requested_products', $objectID ) )
		{
			//Add operation record
			$this->add_request_operation($requestedProductID, 'Modify Request',$this->session->userdata('userID'),'' );
			//Request Info
			$output['requestedProduct'] = $this->request->get_requested_product($requestedProductID);
			$output['requestedProduct'] = $this->get_request_product_data( $output['requestedProduct'] );
			$this->load->view('requested_product_detail_ajax',$output);
		}
				
	}
	
	function add_client_ajax()
	{
		
		// check permissions for this page
		$json=array();
		if (!in_array('requests_edit', $this->permission->permissions))
		{
			$json=array('status'=>'0');
		}
				
		// required
		$this->core->required = array(
			'clientName' => 'Client name'
			
		);

			//log_message("error",'$_POST '.print_r($_POST,true) );		
			// set date
			$this->core->set['dateCreated'] = date("Y-m-d H:i:s");
			$this->core->set['creatorID'] = $this->session->userdata('userID');
				
			// add
			//$clientID = $this->input->post('clientID');
			// where
			//$objectID = array('clientID' => $clientID);
			if (  $this->core->update('clients' ) && count($_POST))
			{
				
				$clientID=	$this->db->insert_id();
				
				// set date
				$this->core->set['dateCreated'] = date("Y-m-d H:i:s");
				$this->core->set['creatorID'] = $this->session->userdata('userID');
				$this->core->set['clientID'] =$clientID;
				$this->core->update('requests') ;
					// get insert id
				$requestID = $this->db->insert_id();
				$json=array('status'=>'1', 'clientID'=>$clientID, 'requestID'=>$requestID);
				
				//	log_message("error",'output '.print_r($output,true) );
			}else
			{
								
				$json=array('status'=>'0');
			}
				
			$output = json_encode($json);
			echo $output;
			die();
			
		
	
	}
	
	function add_requested_product()
	{
		
	
	
	}
		
	function edit($requestID)
	{
		// check permissions for this page
		if (!in_array('requests_edit', $this->permission->permissions) && $userID != $this->session->userdata('userID'))
		{
			redirect('/admin/requests');
		}

		// check this is a valid user
		if (!$request = $this->request->get_request($requestID))
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
		$this->load->view($this->includes_path.'/header'.$this->languageAffix );
		$this->load->view('edit',$output);
		$this->load->view($this->includes_path.'/footer');
	}

	function requested_product_form_ajax( $productTypeID='', $requestID='' )
	{
		//$uriArray = $this->uri->uri_to_assoc(5);
		//log_message("error",'uri '.print_r($uriArray,true) . ' form ID: '.$formID." productTypeID ".$productTypeID );
		// logout if not admin
		if ($this->session->userdata('session_admin'))
		{			
			$productFields = $this->request->get_features_for_product_type($productTypeID);
			$formString='';
			foreach($productFields as $field)
			{
				$formString.='<p class="inline-label"><label class="label" for="'. $field['fieldSafe'].'">'. $field['fieldName'].':</label>';
				switch ( $field['fieldType'])
				{
					case 'combo':
						$valueSet = explode(',',$field['valueSet'] );
						$options=null;
						foreach ($valueSet as $value):
							$options[$value] = $value;
						endforeach;
						$formString.=@form_dropdown($field['fieldSafe'], $options, set_value($field['fieldSafe'], $valueSet[0]), 'id="'.$field['fieldSafe'].'" class="select selectMultiple float-left small-margin-right"');	
					break;
					
					case 'int':
					case 'input':
					default:					
						$formString.=@form_input($field['fieldSafe'],set_value($field['fieldSafe'], ''), 'id="'.$field['fieldSafe'].'" class="input float-left small-margin-right"');
						
						if( !empty($field['sampleValue'] ))
							$formString.='<span class="info-spot float-left ">
										<span class="icon-info-round"></span>
										<span class="info-bubble">
											e.g. '.$field['sampleValue'].'
										</span></span> ';
					break;
				
				}
				
				$formString.='   </p>';
			}
			$output['data'] =array();
			$output['data']['typeID']= $productTypeID;
			$output['data']['requestID']= $requestID;
			$output['featuresForm'] = $formString ;		
			$output['productTypes'] = $this->request->get_product_types('','',100);
			
			//$output['formID'] = $formID;
			//log_message('error', 'Output: '.print_r($output, true) );
			$this->load->view('requested_product_form_ajax', $output);
		}
	
	
	}
	
	function edit_requested_product_form_ajax( $productID)
	{
		
		if ($this->session->userdata('session_admin'))
		{	
			if ( ! $productID )
			{
				echo "Product ID not found!";
				die();
			}
			
			$requestedProduct = $this->request->get_requested_product($productID);
			$output['data'] = $requestedProduct;
			$output['data']['images'] = $this->template->parse_image_string( $requestedProduct ['imgs'] );
			//log_message("error",$productID.' requested '.print_r($requestedProduct,true) );	
			if ( $requestedProduct  )
			{
			
				$productTypeID = $requestedProduct['typeID'];
				
				$productFields = $this->request->get_features_for_product_type($productTypeID);
				$formString='';
				$output['productTypes'] = $this->request->get_product_types('','',100);
				foreach($productFields as $field)
				{
					$formString.='<p class="inline-label"><label class="label" for="'. $field['fieldSafe'].'">'. $field['fieldName'].':</label>';
				switch ( $field['fieldType'])
				{
					case 'combo':
						$valueSet = explode(',',$field['valueSet'] );
						$options=null;
						$selected=0;
						foreach ($valueSet as $value):
							$options[$value] = $value;
							if( $requestedProduct[$field['fieldSafe']] == $value )
								$selected = $value;
						endforeach;
						$formString.=@form_dropdown($field['fieldSafe'], $options, set_value($field['fieldSafe'], $selected  ), 'id="'.$field['fieldSafe'].'" class="select selectMultiple float-left small-margin-right"');	
					break;
					
					case 'int':
					case 'input':
					default:					
						$formString.=@form_input($field['fieldSafe'],set_value($field['fieldSafe'], $requestedProduct[$field['fieldSafe']]), 'id="'.$field['fieldSafe'].'" class="input float-left small-margin-right"');
						
						if( !empty($field['sampleValue'] ))
							$formString.='<span class="info-spot float-left ">
										<span class="icon-info-round"></span>
										<span class="info-bubble">
											e.g. '.$field['sampleValue'].'
										</span></span> ';
					break;
				
				}
				
				$formString.='   </p>';
				}
			}
			else
			{
				echo "Product is not identified.";
				die();
			}
			//$output['productTypeID']=$productTypeID;
			$output['featuresForm'] = $formString ;					
			//$output['formID'] = $formID;
			$this->load->view('edit_requested_product_form_ajax', $output);
		}
	
	
	}
	
	function edit_quote_form_ajax( $quoteID)
	{
		
		if ($this->session->userdata('session_admin'))
		{	
			if ( ! $quoteID )
			{
				echo "Quote is not found!";
				die();
			}
			
			
			$quote = $this->request->get_quote($quoteID);
			$productID = 	$quote['requestedProductID'];
			
			if ( $quote  )
			{
				$formString='';
				$productTypeID = $quote['productTypeID']; 
				
			
				if( $quote['userID'] != $this->session->userdata('userID') )
				{
					echo '<a class="window_close" href="#"><img src="'.$this->config->item('base_url'). $this->config->item('staticFolder').'/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">Warning</span>
						<div style="clear:both;"></div>
						<div id="window_scroll">You have not permission to edit this quote for this product. </div>';
					die();
				}
				
				//Quantity required				
				/*
				//Price quoted
				$prices= $this->request->get_quoted_prices($quoteID);
				$mappedPrices =array();
				if( is_array($prices) )
				{
					foreach( $prices as $price)
					{
						$mappedPrices[ $price['quantity']] =    $price['price'];
					}
				}		

				//Quantity & Price
				$unitPriceFormString = '';
				$unitPriceFormString.='<p class="inline-label"> <label class="label"  for="orderQuantity">'. (($this->isCN)?'产品单价':'Unit Price').':</label>  ';
				
				if ( !empty($quote['orderQuantities'] ) )
				{
					$orderQuantities = explode(',', $quote['orderQuantities'] );
					$unitPriceFormString.='<table class="unit_prices" ><thead><td>'. (($this->isCN)?'产品数量':'Quantity').'</td><td>'. (($this->isCN)?'产品单价':'Unit Price').'</td></thead>';
					foreach (  $orderQuantities as $orderQuantity)
					{					
						//$formString.='<li><span class="quantity">'.$orderQuantity.":</span>" .@form_input('price_'.$orderQuantity,set_value('price_'.$orderQuantity,   array_key_exists($orderQuantity, $mappedPrices )?$mappedPrices[$orderQuantity]:''), 'id="price_'.$orderQuantity.'" class="input float-left"') .'</li>';	
						
						$unitPriceFormString.='<tr><td>'.$orderQuantity."</td><td>" .@form_input('price_'.$orderQuantity,set_value('price_'.$orderQuantity,  array_key_exists($orderQuantity, $mappedPrices )?$mappedPrices[$orderQuantity]:''), 'id="price_'.$orderQuantity.'" class="input float-left"') .'</td></tr>';	
					}
					$unitPriceFormString.="</table>";
				}else
				{
					$unitPriceFormString.=($this->isCN)?'需求数量未提供':'No order quantity is provided, you can quote it in comment.' ;
				}
				$unitPriceFormString.='   </p>';
				
				
				$productFields = $this->request->get_features_for_product_type($productTypeID);
			
				
				foreach($productFields as $field)
				{
					$formString.='<label for="'. $field['fieldSafe'].'">'. $field['fieldName'].':</label>';
					switch ( $field['fieldType'])
					{
						case 'combo':
							$valueSet = explode(',',$field['valueSet'] );
							$options=null;
							foreach ($valueSet as $value):
								$options[$value] = $value;
							endforeach;
							$formString.=@form_dropdown($field['fieldSafe'], $options, set_value($field['fieldSafe'], array_key_exists($field['fieldSafe'], $quote )?$quote[$field['fieldSafe']]:''), 'id="'.$field['fieldSafe'].'" class="input float-left"');	
						break;
						
						case 'int':
						case 'input':
						default:					
							$formString.=@form_input($field['fieldSafe'],set_value($field['fieldSafe'], array_key_exists($field['fieldSafe'], $quote )?$quote[$field['fieldSafe']]:''), 'id="'.$field['fieldSafe'].'" class="input float-left"');
							if( !empty($field['sampleValue'] ))
								$formString.=' <span class="tip">e.g. '.$field['sampleValue'].'</span>';
						break;
					
					}
					
					$formString.='   <br class="clear" />';
										
				}
				
				$quote['images']= $this->template->parse_image_string( $quote ['imgs'] );;
				$output['data'] = $quote;
				$output['unitPriceForm'] = $unitPriceFormString ;	
				$output['featuresForm'] = $formString ;	
				$this->load->view('edit_quote_form_ajax', $output);
				*/
				
				$output = $this->get_quote_data_for_form( $productID, $quoteID);
			
				if( $output)
				{								
					$this->load->view($this->isCN?'edit_quote_form_cn_ajax':'edit_quote_form_ajax', $output);				
				}
				else
				{
					echo '<a class="window_close" href="#"><img src="'.$this->config->item('base_url'). $this->config->item('staticFolder').'/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">Warning</span>
						<div style="clear:both;"></div>
						<div id="window_scroll">Nothing exists.</div>';
					die();
				}
			}
			else
			{
				echo "Product is not identified.";
				die();
			}
			
		}
	
	
	}
	
	function add_quote_form_ajax( $productID, $templateID='')
	{
		
		if ($this->session->userdata('session_admin') && in_array('requests_accept', $this->permission->permissions )  )
		{	
			if ( ! $productID )
			{
				echo '<a class="window_close" href="#"><img src="'.$this->config->item('base_url'). $this->config->item('staticFolder').'/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">Warning</span>
					<div style="clear:both;"></div>
					<div id="window_scroll">This product request is not exist.</div>';
				die();
			}
			$requestedProduct = $this->request->get_requested_product($productID);
			if( $requestedProduct['accepterID'] != $this->session->userdata('userID') )
			{
				echo '<a class="window_close" href="#"><img src="'.$this->config->item('base_url'). $this->config->item('staticFolder').'/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">Warning</span>
					<div style="clear:both;"></div>
					<div id="window_scroll">You have not permission to make a quote for this product. <br/> Please accept it before you make quote.</div>';
				die();
			}
			
			$output = $this->get_quote_data_for_form( $productID, $templateID);
			
			if( $output)
			{								
				$this->load->view($this->isCN?'add_quote_form_cn_ajax':'add_quote_form_ajax', $output);				
			}
			else
			{
				echo '<a class="window_close" href="#"><img src="'.$this->config->item('base_url'). $this->config->item('staticFolder').'/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">Warning</span>
					<div style="clear:both;"></div>
					<div id="window_scroll">Nothing exists.</div>';
				die();
			}
		}else
		{
		
			echo '<a class="window_close" href="#"><img src="'.$this->config->item('base_url'). $this->config->item('staticFolder').'/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">Warning</span>
					<div style="clear:both;"></div>
					<div id="window_scroll">You are not belong to this group, and do not have permission to make a quote for this product.</div>';
				die();
		}
	}
	
	
	
	function search_quote_form_ajax( $productID)
	{
			
		$requestedProduct = $this->request->get_requested_product($productID);

		$output['quoted_products']=array();

		$this->load->view($this->isCN?'search_quote_form_cn_ajax':'search_quote_form_ajax', $output);
		
	}
	
	function search_quoted_product_ajax()
	{
		//log_message("error",'$_POST '.print_r($_POST,true) );		
		if (count($_POST)  )
		{
			$limit =  $this->site->config['paging'] ;
			$output['quoted_products']=$this->request->search_quotes($limit);
	
			$this->load->view($this->isCN?'search_quote_result_cn_ajax':'search_quote_result_ajax',$output);
			
		}else{
		
			echo "Unknown Access!";
		
		}
	}
	
	function get_quoted_product_ajax($quotedProductID)
	{
		if(!empty($quotedProductID) )
		{
			//Retrieve quotes data
			$output['quote'] =$this->request->get_quote($quotedProductID);
			
			
			$output['quote'] =  $this->get_quote_data( $output['quote']  );
				
			$this->load->view($this->isCN?'quote_detail_cn_ajax':'quote_detail_ajax',$output);
		
		
		}
	
	}
	
	
	function get_quote_data_for_form(  $productID,$quoteID )
	{
		
		$output=array();
		$formString='';
		$unitPriceFormString = '';
		$productTypeID = '';
		//--------Get price form & parse images----
		$requestedProduct = $this->request->get_requested_product($productID);
		if( empty($requestedProduct) )
			return false;
		
		$productTypeID = $requestedProduct['typeID'];
		
		$quote = array();		
		if($quoteID )
			$quote  = $this->request->get_quote($quoteID);
		//log_message('error', print_r($quote,true). $quoteID);
		$mappedPrices = array();
		if ( $quote  )
		{
			//Price quoted
			$prices= $this->request->get_quoted_prices($quote['quotedProductID']);
			$mappedPrices =array();
			if( is_array($prices) )
			{
				foreach( $prices as $price)
				{
					$mappedPrices[ $price['quantity']] =    $price['price'];
				}
			}
			$quote['images']= $this->template->parse_image_string( $quote ['imgs'] );
		}
		//-----------Get Prices---------------
		$unitPriceFormString.='<p class="inline-label"> <label class="label"  for="orderQuantity">'. (($this->isCN)?'产品单价':'Unit Price').':</label>  ';
		if ( !empty($requestedProduct['orderQuantities'] ) )
		{
		
			$orderQuantities = explode(',', $requestedProduct['orderQuantities'] );
			$unitPriceFormString.='<table class="unit_prices" ><thead><td>'. (($this->isCN)?'产品数量':'Quantity').'</td><td>'. (($this->isCN)?'产品单价':'Unit Price').'</td></thead>';
			
			foreach (  $orderQuantities as $orderQuantity)
			{					
				$unitPriceFormString.='<tr><td>'.$orderQuantity."</td><td>" .@form_input('price_'.$orderQuantity,set_value('price_'.$orderQuantity,  array_key_exists($orderQuantity, $mappedPrices )?$mappedPrices[$orderQuantity]:''), 'id="price_'.$orderQuantity.'" class="input float-left"') .'</td></tr>';					
			}
			$unitPriceFormString.="</table>";
		}
		$unitPriceFormString.='</p>';
	
		//---------Get Feature form ----------
		$productFields = $this->request->get_features_for_product_type($productTypeID);
		foreach($productFields as $field)
		{
			$formString.='<p class="inline-label"> <label class="label" for="'. $field['fieldSafe'].'">'.(($this->isCN)? $field['fieldNameCN']: $field['fieldName']).':</label>';
			switch ( $field['fieldType'])
			{
				case 'combo':
					$valueSet = explode(',',$field['valueSet'] );
					$options=null;
					foreach ($valueSet as $value):
						$options[$value] = $value;
					endforeach;
					$formString.=@form_dropdown($field['fieldSafe'], $options, set_value($field['fieldSafe'], array_key_exists($field['fieldSafe'], $quote )?$quote[$field['fieldSafe']]:''), 'id="'.$field['fieldSafe'].'" class="input float-left"');	
				break;
				
				case 'int':
				case 'input':
				default:					
					$formString.=@form_input($field['fieldSafe'],set_value($field['fieldSafe'], array_key_exists($field['fieldSafe'], $quote )?$quote[$field['fieldSafe']]:''), 'id="'.$field['fieldSafe'].'" class="input float-left"');
					if( !empty($field['sampleValue'] ))
						$formString.=' <span class="tip">e.g. '.$field['sampleValue'].'</span>';
				break;			
			}			
			$formString.='</p>';
								
		}
		
		
		$output['data'] = $quote;
		$output['data']['requestedProductID'] = $productID;
		$output['unitPriceForm'] = $unitPriceFormString ;	
		$output['featuresForm'] = $formString ;	
		//log_message('error', print_r($output,true));
		return $output;
		
	
	}
	
	function add_quoted_product_ajax()
	{
		
		// check permissions for this page
		if (!in_array('requests_accept', $this->permission->permissions))
		{
			echo '{"status":"error"}';
			die();
		}
				
		// required
		$this->core->required = array(
			'productName' => 'Product name'
			
		);
		
		// set date
		$this->core->set['dateQuoted'] = date("Y-m-d H:i:s");
		$this->core->set['userID'] = $this->session->userdata('userID');
		$requestedProductID = $this->input->post('requestedProductID');
		
		$requested_product= $this->request->get_requested_product($requestedProductID);
		$productTypeID=  $requested_product['typeID'];
		$this->core->set['productTypeID'] = (int)$productTypeID;
		//log_message('error', print_r($_POST, true));
		// update		
		//$quotedProductID = $this->input->post('quotedProductID');
		// where
		//$objectID = array('quotedProductID' => $quotedProductID);

		$output= array();
		
		if ( count($_POST) && $this->core->update('quoted_products') )
		{
			// get insert id
			$quotedProductID = $this->db->insert_id();
			//Add Quoted Prices
			$quote = $this->request->get_quote($quotedProductID);
			$orderQuantities = explode(',', $quote['orderQuantities'] );
			foreach( $orderQuantities as $orderQuantity)
			{
				$this->core->set['quotedProductID'] = $quotedProductID;
				$this->core->set['quantity'] =  $orderQuantity;
				$this->core->set['price'] =  $this->input->post('price_'.$orderQuantity);
				$this->core->update('quote_prices');
			
			}
			//Add operation record
			$this->add_request_operation($requestedProductID, 'Add Quote '.$quotedProductID,$this->session->userdata('userID'),'' );
			
			//Retrieve quotes data
			$quotes =$this->request->get_quotes_for_product($requestedProductID);
			
			//log_message('error', 'productTypeID'.$productTypeID);
			$output['quotes'] =  $this->get_quotes_data( $quotes, $productTypeID  );
			
			
			//log_message('error', print_r($output['quotes'], true));	
			$this->load->view( ( $this->isCN )?'quotes_detail_cn_ajax':'quotes_detail_ajax',$output);
		}
			
	}
	
	function save_quoted_product_ajax()
	{
		
		// check permissions for this page
		if (!in_array('requests_accept', $this->permission->permissions))
		{
			echo '{"status":"error"}';
			die();
		}
				
		// required
		$this->core->required = array(
			'productName' => 'Product name'
			
		);
		
		// set date
		$this->core->set['dateModified'] = date("Y-m-d H:i:s");
		
		$requestedProductID = $this->input->post('requestedProductID');
		
		// update		
		$quotedProductID = $this->input->post('quotedProductID');
		// where
		$objectID = array('quotedProductID' => $quotedProductID);

		$output= array();
		
		if ( count($_POST) && $this->core->update('quoted_products', $objectID) )
		{
			
			//Update Prices
			//Get old price
			$quote= $this->request->get_quote($quotedProductID);
			$productTypeID = $quote['productTypeID'];
			
			$prices= $this->request->get_quoted_prices($quotedProductID);
			$mappedPrices=array();
			if( is_array($prices) )
			{
				foreach( $prices as $price)
				{
					$mappedPrices[ $price['quantity'] ] =    $price;
				}
			}	
			
			if(!empty($quotedProductID) )
			{
				$quote = $this->request->get_quote($quotedProductID);
				$orderQuantities = explode(',', $quote['orderQuantities'] );
				foreach( $orderQuantities as $orderQuantity)
				{
					
						$this->core->set['quotedProductID'] = $quotedProductID;
						$this->core->set['quantity'] =  $orderQuantity;
						$this->core->set['price'] =  $this->input->post('price_'.$orderQuantity);	
						$pID = null;
						if ( array_key_exists($orderQuantity, $mappedPrices ) )
						{
							$pID = array('priceID' => $mappedPrices[$orderQuantity]['priceID']);
							$this->core->update('quote_prices', $pID);
						}					
						else	
							$this->core->update('quote_prices');
						
									
				}
			}
			//Add operation record
			$this->add_request_operation($requestedProductID, 'Modify Quote '.$quotedProductID,$this->session->userdata('userID'),'' );
			//Retrieve quotes data
			$output['quotes'] =$this->request->get_quotes_for_product($requestedProductID);
			
			
			$output['quotes'] =  $this->get_quotes_data( $output['quotes'],$productTypeID  );
				
			$this->load->view('quotes_detail_ajax',$output);
		}
			
	}
	
	function submit_quotes($requestedProductID)
	{
		
		// check permissions for this page
		if (!in_array('requests_accept', $this->permission->permissions))
		{
			echo '{"status":"error"}';
			die();
		}
				
			//Update status of requested product
			$object= array('status'=>'Q','accepterID'=> $this->session->userdata('userID'),'dateQuoted'=>date("Y-m-d H:i:s") );
			
			
			$this->db->where('productID', $requestedProductID);
		
			if(	$this->db->update('requested_products', $object) )
			{
				//Record operation
				//log_message('error',  $requestedProductID);
				$this->add_request_operation($requestedProductID, 'Finish quote',$this->session->userdata('userID'),'' );
				redirect('admin/requests/view_my_quoted');
			}
			
	}

	
	function delete_quote_ajax($quotedProductID)
	{	
		// check permissions for this page
		if (!in_array('requests_accept', $this->permission->permissions))
		{
			echo '{"status":"error"}';
		}
		if ($this->core->soft_delete('quoted_products', array('quotedProductID' => $quotedProductID)))
		{
			//Add operation record
			$this->add_request_operation($requestedProductID, 'Delete Quote '.$quotedProductID,$this->session->userdata('userID'),'' );
			
		}
	
	}
	
	function delete_request_ajax($requestedProductID)
	{
		$output=null;
		// check permissions for this page
		if (!in_array('requests_delete', $this->permission->permissions))
		{
			$json=array('status'=>'0');
			$output = json_encode($json);
		}
		if ($this->core->soft_delete('requested_products', array('productID' => $requestedProductID)))
		{
			//Add operation record
			$this->add_request_operation($requestedProductID, 'Delete Request '.$quotedProductID,$this->session->userdata('userID'),'' );
			$json=array('status'=>'1');
			$output = json_encode($json);
		}
		echo $output;
		die();
	
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