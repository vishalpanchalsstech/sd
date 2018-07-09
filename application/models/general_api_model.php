<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class general_api_model extends CI_Model{
		function __construct(){
		parent::__construct();
	}
	public function Get_country_api()
	 {
	  
	  $query = $this->db->query("select Id,CONCAT(Name,'-',SortName) as CountryName from countriesmaster");
	  $result = $query->result();
	  return $result; 
	 }
	
}
?>