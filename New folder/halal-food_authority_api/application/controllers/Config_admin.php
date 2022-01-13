	<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Config_admin extends CI_Controller {

	public function __construct(){
	parent::__construct();

	$this->load->model('config_model');
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

	$json['success'] = 1;

	}

	else{

   $json['success'] = 0;
	$json['message'] = 'Invilid user';
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
		   'CNICImage' 			=> $cnic_image,
		   'ProfileImage' 			=> $profile_image,
		   
		);
	   // echo '<pre>';
		//print_r($data);die();
		$this->db->insert('r_owner',$data);
		
		$user_id = $this->db->insert_id();
		//another table  
		
		
		
		 $data = array(
		   'Name'  => $this->input->post('bussiness_name'),
		   'Type'  => $this->input->post('bussiness_category'),
		   'Address' 		=> $this->input->post('bussiness_address'),
		   'Contact' 		=> $this->input->post('contact_no'),
		   'Latitude' 		=> $this->input->post('latitude'),
		   'Longitude' 		=> $this->input->post('longitude'),
		   'UserId' 		=> $user_id,
		   'EntryDate' 		=> date('Y-m-d',strtotime($this->input->post('entryDate'))),

		   
		);
		//echo '<pre>';
		//print_r($data);die();
		$this->db->insert('tbl_bussiness',$data);
		$user_id2 = $this->db->insert_id();
		/* Sent data r_application*/
		$data = array(
			'BussId' 		=> $user_id2,
			'Source'  => $this->input->post('owner'),
	
		);
		//echo '<pre>';
		//print_r($data);die();
		$this->db->insert('r_application',$data);
		
		$json[] = 'record added';
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

	$json['success'] = 1;
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
	$user_id         = $this->db->insert_id();
	$user_data       = $this->config_model->get_user_data($user_id);
	$json['success'] = 0;
	$json['message'] = $user_data;
	}
	}

	header('content-type/application/json');
	echo json_encode($json);
	}

	}



	?>