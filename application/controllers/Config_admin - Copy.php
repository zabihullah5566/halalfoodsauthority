<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Config_admin extends CI_Controller {

	public function __construct(){
	parent::__construct();

	$this->load->model('config_model');
	}
	/*
	Complain Insert Fields
	complain Category
	category name
	Higeen
	Adult
	Corruption
	Service Matter.
	
	
	*/
	public function login(){

		$json = array();
		$array = array();
		if($this->input->post('cnic','password'))
	{
	
		 $cnic      = $_POST['cnic'];
		 $password  = $_POST['password'];
		 $result  = $this->config_model->forgot_passsword($cnic,$password);
 
	 if($result > 0)
	 {
		 $array['userID'] = $result['cid'];
		 $array['name']   = $result['Name'];
		 $array['cnic']   = $result['cnic'];		 
		 $json['success'] = 1;
		 $json['Message'] = "User Login";
		 $json['user_data']= $array;
		 		
	 }
	 else
	 {
		 
		 $json['success'] = 0;
		 $json['Message'] = "invalid User";	 
	 }
	}
 
    header('content-type/application/json');
	echo json_encode($json);

	}
	/*Find Business Name API*/
	   public function find_business_name(){
		   $json = array();
		$array = array();
		if($this->input->post('name'))
		{
			$name      = $_POST['name'];
			$result  = $this->config_model->Business_User_finding($name);
			if($result > 0)
	 {
		 $array['name']   = $result['Name'];	 
		 $json['success'] = 1;
		 $json['Message'] = "Available User";
		 $json['user_data']= $array;
		 		
	 }
	 else
	 {
		 
		 $json['success'] = 0;
		 $json['Message'] = "Not User";	 
	 }
		}
		 header('content-type/application/json');
	echo json_encode($json);
	   }

	public function forgot_password(){
	$json = array();
	if($this->input->post('cnic'))
	{
	$cnic    = $this->input->post('cnic');
	$result  = $this->config_model->forgot_passsword($cnic);

	if($result > 0){

	$contact          = $result['c_mobile'];
	$password         = $result['cpass'];
	$json['c_mobile'] = $contact;
	$json['cpass']    = $password;
	$json['success'] = 'Add user';
	//$json['success'] = 0;

	}

	else{

	$json['message'] = 'Invalid user';
	}	
	}

	header('content-type/application/json');
	echo json_encode($json);
	}
    /* Get Distric_record APIS*/
	function distric_record(){
		
		//$data = $this->api_model->fetch_all();
       //echo json_encode($data->result_array());
		
		$data= $this->config_model->get_distric();
		echo json_encode($data);
		
		
	}
	/* Get Business Type APIS*/
	
	function bussiness_type_record(){
		
		$data= $this->config_model->get_bussiness_type();
		echo json_encode($data);
		
		
	}
	/* Add Owner_registration  APIS*/
	function add_owner_reg(){
		
		$json = array();
		if($this->input->post()){
			
			$find_business_name = $this->config_model->Business_User_finding($this->input->post('bussiness_name'));
			
			if($find_business_name > 0)
	        {
		        $json['success'] = 0;
		        $json['message'] = "This business name already exist";
				echo json_encode($json);die;
	        }
			
			//================================================
		 
			 $profile_image = $_FILES['profile_image']['name'];
			 $config['upload_path'] = './assets/img';
             $config['allowed_types'] = 'gif|jpg|png';
			// $config['max_size']     = '100';
			  $this->load->library('upload',$config);
			 
			 $this->upload->do_upload('profile_image');
			 
			   $gbr = $this->upload->data();
                  //Compress Image
                $this->_create_thumbs($gbr['file_name']);
				
			 $profile_image = str_replace(' ','_',$profile_image); 
			 
			 
			 $cnic_image = $_FILES['cnic_image']['name'];
			 $config['upload_path'] = '<?=base_url()?>assets/img';
             $config['allowed_types'] = 'gif|jpg|png';
			// $config['max_size']     = '100';
			  $this->load->library('upload',$config);
			 
			 $this->upload->do_upload('cnic_image');
			 $cnic_image = str_replace(' ','_',$cnic_image);
			
			 $data = array(
			   'Name'  => $this->input->post('name'),
			   'FName'  => $this->input->post('fname'),
			   'CNIC'  => $this->input->post('cnic_no'),
			   'DistrictId'  => '42',
			   'Address' 		=> $this->input->post('bussiness_address'),
			   'Mobile' 		=> $this->input->post('contact_no'),
			   'Gender' 		=> $this->input->post('Gender'),
			   'CNICImage' 		=> $cnic_image,
			   'ProfileImage' 	=> $profile_image,
			   
			);
			$cnic =$this->input->post('cnic_no');
			
			$query = $this->db->select('UserId,CNIC')->from('r_owner')->where('CNIC',$cnic)->get();	
			$num = $query->num_rows();
			
			if($num == 0)
			{
				$this->db->insert('r_owner',$data);
				$user_id = $this->db->insert_id();
			}
			else
			{ 	
			    $user_data = $query->result_array(); 
				$user_id = $user_data[0]['UserId'];
			}
		
			
			 $data_business = array(
			   'Name'           => $this->input->post('bussiness_name'),
			   'Type'           => $this->input->post('bussiness_category'),
			   'Address' 		=> $this->input->post('bussiness_address'),
			   'Contact' 		=> $this->input->post('contact_no'),
			   'Latitude' 		=> $this->input->post('latitude'),
			   'Longitude' 		=> $this->input->post('longitude'),
			   'Lic_OwnerId' 	=> $user_id,
			   'UserId' 		=> $user_id,
			   'EntryDate' 		=> date('Y-m-d H:i:s'),

			   
			);
			//echo '<pre>';
			//print_r($data);die();
			$this->db->insert('tbl_bussiness',$data_business);
			$user_id2 = $this->db->insert_id();
			/* Sent data r_application*/
			$data_application = array(
			    'BussId' 	=> $user_id2,
				'Source'    => $this->input->post('owner'),
		
			);
			//echo '<pre>';
			//print_r($data);die();
			$this->db->insert('r_application',$data_application);
			
			//$json[] = 'record added';
			$json['success'] = 0;
			$json['message'] = 'Successfully record added';
			echo json_encode($json);die;
			
		}
		
		 
		
	}
	
	function _create_thumbs($file_name){
        // Image resizing config
        $config = array(
          
                'image_library' => 'GD2',
                'source_image'  => '<?=base_url()?>assets/img/'.$file_name,
                'maintain_ratio'=> FALSE,
                'width'         => 100,
                'height'        => 67,
               // 'new_image'     => './assets/images/small/'.$file_name
            );
 
        $this->load->library('image_lib', $config);

        $this->image_lib->resize();
           
            
        
    }
	/* Get Get_Owner_All_record APIS*/
	function get_owner_record(){
		
		//$data = $this->api_model->fetch_all();
       //echo json_encode($data->result_array());
		
		$data= $this->config_model->get_owner_record();
		//echo '<pre>';
		echo json_encode($data);
		//echo json_encode(print_r($data));die;
		
		
	}
	/* Get Get_ Business_All_record APIS*/
	public function business_records(){
		
		$data= $this->config_model->get_business_record();
		echo json_encode($data);
	}
	 /* Post Product_record APIS*/
	 function add_product(){
		 $json = array();
    		if($this->input->post()){
    			$data = array(
    			'BussId' =>$this->input->post('bus_id'),
    			'Name' =>$this->input->post('name'),
    			'Description' =>$this->input->post('description'),
    			'Registration' =>$this->input->post('reg'),
    			'ManUnit' =>$this->input->post('munit'),
    			
    			);
				
    			$this->db->insert('r_business_products',$data);
				$json['success'] = 0;
				$json['message'] = 'record added';
				echo json_encode($json);die;
				
    		}
    		
    		
    	} 
	 
	public function sign_up(){

	$json = array();

	if($this->input->post())
	{
	$name        = $this->input->post('name');
	$district    = $this->input->post('district_id');
	$cnic        = $this->input->post('cnic');
	$c_mobile    = $this->input->post('c_mobile');
	$password    = $this->input->post('password');


	$result = $this->config_model->forgot_passsword($cnic);
	if($result > 0){

	$json['success'] = '1';
	$json['message'] = 'This user already exist';

	}
	else{

	$data = array(
	'Name'       => $name,
	'districtId' => $district,
	'cnic'       => $cnic,
	'c_mobile'   => $c_mobile,
	'cpass'      => $password,
	);


	$this->db->insert('tbl_customer',$data);
	$cid         = $this->db->insert_id();
	$user_data       = $this->config_model->get_user_data($cid);
	$json['success'] = 0;
	$json['message'] = $user_data;
	}
	}

	header('content-type/application/json');
	echo json_encode($json);
	
	
	}

	}



	?>