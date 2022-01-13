<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Config_model extends CI_model {

			/* Get Forgot Password APIS*/
		public function forgot_passsword($cnic,$password){
			$query = $this->db->select('`cid`, `Name`, `cname`, `cpass`, `districtId`, `address`, `email`, `c_mobile`, `date_time`, `cnic`, `otp`, `photo`')
            	->from('tbl_customer')
            	->where('cnic',$cnic)
            	->where('cpass',$password)
            	->get();

            	return $query->row_array();
		}
			/* Get User Data APIS*/
		public function get_user_data($cid){
			$query = $this->db->select('`cid`, `Name`, `cname`, `cpass`, `districtId`, `address`, `email`, `c_mobile`, `date_time`, `cnic`, `otp`, `photo`')
            	->from('tbl_customer')
            	->where('cid',$cid)
            	->get();

            	return $query->row_array();
		}
		/* Get District APIS*/
		function get_distric(){
			$this->db->select('`Id`, ,`Name`, `Operational`, `Code`, `OperationDistrictId`, `EngineerId`, `Division`')
			->from('tbl_district');
			$query = $this->db->get();
			return $query->result();
		}
		/* Get Business Type APIS*/
		function get_bussiness_type(){
			$this->db->select('`Id`, `Name`, `Desc`, `SortId`, `BussinessType`')
			->from('tbl_bussiness_type');
			$query = $this->db->get();
			return $query->result();
		}
		
		/* Get Owner_Business APIS*/
		function get_owner_record(){
			$this->db->select('`UserId`, `Name`, `FName`, `CNIC`, `Landline`, `Mobile`, `Fax`, `Email`, `Address`, `DistrictId`, `ProfileImage`, `Gender`, `DOC`, `CNICImage`')
			->from('r_owner')
			->order_by('UserId','desc');;
			$query = $this->db->get();
			return $query->result();
		}
		
		/* Get Owner_Business APIS*/
		function get_business_record(){
			$this->db->select('`Id`, `OwnerId`, `Lic_OwnerId`, `Name`, `Type`, `Address`, `Contact`, `Email`, `Website`, `DistrictId`, `DOC`, `StartDate`, `TotalArea`, `CoveredArea`, `LandStatus`, `FoodHandlers`, `BlockDiagramImage`, `Latitude`, `Longitude`, `UserId`, `Status`, `EntryDate`')
			->from('tbl_bussiness');
			//->order_by('UserId','desc');
			$query = $this->db->get();
			return $query->result();
		}
		public function Business_User_finding($name){
			$query = $this->db->select('`Id`, `OwnerId`, `Lic_OwnerId`, `Name`, `Type`, `Address`, `Contact`, `Email`, `Website`, `DistrictId`, `DOC`, `StartDate`, `TotalArea`, `CoveredArea`, `LandStatus`, `FoodHandlers`, `BlockDiagramImage`, `Latitude`, `Longitude`, `UserId`, `Status`, `EntryDate`')
            	->from('tbl_bussiness')
            	->where('Name',$name)
            	->get();

            	return $query->row_array();
		}
	}

	?>