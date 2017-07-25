<?php 
include_once "../engines/Utility/PHPExcelWrapper.php";
class BadgeModel{
	function __construct(){
		
	}
	function generate_code(){
		$conn = open_db(0);
		$sql = "";
		
		mysql_close($conn);
	}
	function json2Excel(){
		$xls = new PHPExcelWrapper();
		$data[0]['Judul'] = "Foo";
		$data[0]['Nilai'] = 120;
		
		$data[1]['Judul'] = "Bar";
		$data[1]['Nilai'] = 14;
		
		$data[2]['Judul'] = "FooBar";
		$data[2]['Nilai'] = 139;
		$xls->setHeader(array('nama','nilai'));
		$xls->getExcel($data,"Foo Bar");
		die();
	}
}
?>