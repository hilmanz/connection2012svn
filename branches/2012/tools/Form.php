<?php
class Form{
	var $fields;
	function __construct(){
		$this->fields = array();
	}
	function add($id,$label,$type){
		array_push($this->fields,array("id"=>$id,"label"=>$label,"type"=>$type));
	}
	function render(){
		$str="<table width='100%'>\n";
		$fields = $this->fields;
		for($i=0;$i<sizeof($this->fields);$i++){
			$type = $fields[$i]['type'];
			$id = $fields[$i]['id'];
			$label = $fields[$i]['label'];
			if($type=="text"){
				$str.="<tr><td>".$label."</td><td><input type='text' name='".$id."' id='".$id."' value=''></td></tr>\n";
			}else if($type=="textarea"){
				$str.="<tr><td colspan='2'>".$label."</td></tr><tr><td colspan='2'><textarea  name='".$id."' id='".$id."'></textarea></td></tr>\n";
			}else if($type=="select"){
				$str.="<tr><td>".$label."</td><td>\n<select name='".$id."' id='".$id."'>\n<option>sample</option>\n</select>\n</td></tr>\n";
			}else if($type=="select"){
				$str.="<tr><td>".$label."</td><td>\n<select name='".$id."' id='".$id."'>\n<option>sample</option>\n</select>\n</td></tr>\n";
			}else{
				$str.="<tr><td>".$label."</td><td>--</td></tr>\n";
			}
		}
		$str.="</table>\n";
		return $str;
	}
	function __toString(){
		return $this->render();
	}
}
?>