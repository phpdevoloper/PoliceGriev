<?php
header('Content-type: application/xml; charset=UTF-8');
Class Pagination{
	
	public function getStartResult($pageNo, $pageSize){
		$start=0;
		if($pageNo=='null'){
			$start=1;
		}
		else {
			$start= ($pageNo*$pageSize) - $pageSize  + 1;
		}
		return $start;
	}
	
	public function getMaxResult($pageNo, $pageSize){
		$endPage=0;
		if($pageNo=='null'){
			$endPage=$pageSize;
		}
		else {
			$endPage= ($pageNo*$pageSize);
		}
		return $endPage;
	}
	
	public function paginationXML($count, $pageNo, $pageSize){
		$xml = '<noOfPage>'.($count%$pageSize >0? ($count/$pageSize)+1:($count/$pageSize)).'</noOfPage>';
		$xml .= '<pageNo>'.($pageNo=='null'?1:$pageNo).'</pageNo>';
		$xml .= '<pageSize>'.$pageSize.'</pageSize>';
		return $xml;
	}
	
	public function reponseStatus($count, $mode){
		if ($count==1){
			$status="S";
		}
		else{
			$status="F";
		}
		
		if($mode=='I'){
			$msg='Insert';
		}
		else if($mode=='U'){
			$msg='Update';
		}
		else if($mode=='D'){
			$msg.='Delete';
		}
			
		if($status=="S"){
			$msg.='d successfully';
		}
		else{
			$msg.=' failed';
		}
		
		$xml='<msg>'.$msg.'</msg>';
		$xml.='<status>'.$status.'</status>';
		return $xml;
	}
	
	public function res_Status($res){
		if($res=='t'){
			$status="true";
		}
		else if($res=='f'){
			$status="false";
		}
		else if($res=='w')
		{
			$status="wrong";
		}
	 	$xml.='<status>'.$status.'</status>';
		return $xml;
	}
	
	public function generateXMLTag($xmlTagName, $val){
		$xml.='<'.$xmlTagName.'><![CDATA['.$val.']]></'.$xmlTagName.'>';
		return $xml;
	}
	
	public function currentTimeStamp(){
		$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
		return $date->format('Y-m-d H:i:s');	
	}
}
$page = new Pagination();  
?>
