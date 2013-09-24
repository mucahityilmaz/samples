<?php
function buyukharf($ifade){
	$buyuk=array("Q","W","E","R","T","Y","U","I","O","P","Ğ","Ü","A","S","D","F","G","H","J","K","L","Ş","İ","Z","X","C","V","B","N","M","Ö","Ç"); 
	$kucuk=array("q","w","e","r","t","y","u","ı","o","p","ğ","ü","a","s","d","f","g","h","j","k","l","ş","i","z","x","c","v","b","n","m","ö","ç"); 
	$ifade=str_replace($kucuk,$buyuk,$ifade);
	return $ifade; 
}
function getAboutTabData($id=0){
	if($id==0){
		$people = array();
		$cumle = "SELECT * FROM `about` ORDER BY `id`";
		$sorgu = mysql_query($cumle) or die("error: ".mysql_error());
		while($sonuc = mysql_fetch_array($sorgu)){
			array_push($people,array("id"=>$sonuc['id'],"filename"=>$sonuc['resim_url'],"personname"=>$sonuc['name'],"story"=>$sonuc['text']));
		}
		return $people;
	}else{
		$person = array();
		$cumle = "SELECT * FROM `about` WHERE `id`='".$id."'";
		$sorgu = mysql_query($cumle) or die("error: ".mysql_error());
		if(mysql_num_rows($sorgu)==1){
			$sonuc = mysql_fetch_array($sorgu);
			$person = array("id"=>$sonuc['id'],"filename"=>$sonuc['resim_url'],"personname"=>$sonuc['name'],"story"=>$sonuc['text']);
		}
		return $person;
	}
}
function getWorksTabData($id=0){
	if($id==0){
		$works = array();
		$cumle = "SELECT * FROM `works` WHERE `bigteam`='0' ORDER BY `id`";
		$sorgu = mysql_query($cumle) or die("error: ".mysql_error());
		while($sonuc = mysql_fetch_array($sorgu)){
			array_push($works,array("id"=>$sonuc['id'],"filename"=>$sonuc['resim_url'],"workname"=>$sonuc['name'],"story"=>$sonuc['text'],"video"=>$sonuc['video_url']));
		}
		return $works;
	}else{
		$work = array();
		$cumle = "SELECT * FROM `works` WHERE `bigteam`='0' AND `id`='".$id."'";
		$sorgu = mysql_query($cumle) or die("error: ".mysql_error());
		if(mysql_num_rows($sorgu)==1){
			$sonuc = mysql_fetch_array($sorgu);
			$work = array("id"=>$sonuc['id'],"filename"=>$sonuc['resim_url'],"workname"=>$sonuc['name'],"story"=>$sonuc['text'],"video"=>$sonuc['video_url']);
		}
		return $work;
	}
}
function getWorksBtTabData($id=0){
	if($id==0){
		$works = array();
		$cumle = "SELECT * FROM `works` WHERE `bigteam`='1' ORDER BY `id`";
		$sorgu = mysql_query($cumle) or die("error: ".mysql_error());
		while($sonuc = mysql_fetch_array($sorgu)){
			array_push($works,array("id"=>$sonuc['id'],"filename"=>$sonuc['resim_url'],"workname"=>$sonuc['name'],"story"=>$sonuc['text'],"video"=>$sonuc['video_url']));
		}
		return $works;
	}else{
		$work = array();
		$cumle = "SELECT * FROM `works` WHERE `bigteam`='1' AND `id`='".$id."'";
		$sorgu = mysql_query($cumle) or die("error: ".mysql_error());
		if(mysql_num_rows($sorgu)==1){
			$sonuc = mysql_fetch_array($sorgu);
			$work = array("id"=>$sonuc['id'],"filename"=>$sonuc['resim_url'],"workname"=>$sonuc['name'],"story"=>$sonuc['text'],"video"=>$sonuc['video_url']);
		}
		return $work;
	}
}
function getPrizeTabData(){
	$prizes = array();
	$cumle = "SELECT * FROM `prize` ORDER BY `id`";
	$sorgu = mysql_query($cumle) or die("error: ".mysql_error());
	while($sonuc = mysql_fetch_array($sorgu)){
		array_push($prizes,array("id"=>$sonuc['id'],"filename"=>$sonuc['resim_url'],"prizename"=>$sonuc['name']));
	}
	return $prizes;
}
function getBrandsTabData(){
	$brands = array();
	$cumle = "SELECT * FROM `brands` ORDER BY `id`";
	$sorgu = mysql_query($cumle) or die("error: ".mysql_error());
	while($sonuc = mysql_fetch_array($sorgu)){
		array_push($brands,array("id"=>$sonuc['id'],"filename"=>$sonuc['resim_url'],"brandname"=>$sonuc['name']));
	}
	return $brands;
}
function getSettings(){
	$sonuc = array();
	$sorgu = mysql_query("SELECT * FROM `settings`") or die("Error: ".mysql_error());
	while($row = mysql_fetch_assoc($sorgu)){
		$sonuc[$row['alias']]=$row['value'];
	}
	return $sonuc;
}
function tekilDataGetir($page,$id){
	$cumle = "SELECT * FROM `".$page."` WHERE `id`='".$id."'";
	$sorgu = mysql_query($cumle);
	if(mysql_num_rows($sorgu)==1){
		return mysql_fetch_array($sorgu);
	} else {
		return false;
	}
}
?>
