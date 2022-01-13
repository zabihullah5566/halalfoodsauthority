<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Config_model extends CI_model {


		public function forgot_passsword($cnic){
			$query = $this->db->select('`cid`, `Name`, `cname`, `cpass`, `districtId`, `address`, `email`, `c_mobile`, `date_time`, `cnic`, `otp`, `photo`')
            	->from('tbl_customer')
            	->where('cnic',$cnic)
            	->get();

            	return $query->row_array();
		}

		public function get_user_data($user_id){
			$query = $this->db->select('`cid`, `Name`, `cname`, `cpass`, `districtId`, `address`, `email`, `c_mobile`, `date_time`, `cnic`, `otp`, `photo`')
            	->from('tbl_customer')
            	->where('cid',$user_id)
            	->get();

            	return $query->row_array();
		}
		/* Get District APIS*/
		function get_distric(){
			$this->db->select('`Id`, ,`Name`, `Operational`, `Code`, `OperationDistrictId`, `EngineerId`, `Division`')
			->from(' tbl_district');
			$query = $this->db->get();
			return $query->result();
		}
		/* Get Bussiness Type APIS*/
		function get_bussiness_type(){
			$this->db->select('`Id`, `Name`, `Desc`, `SortId`, `BussinessType`')
			->from(' tbl_bussiness_type');
			$query = $this->db->get();
			return $query->result();
		}
	}

	?>