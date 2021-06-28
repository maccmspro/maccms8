<?php
require("conn.php");
require(MAC_ROOT.'/inc/common/360_safe3.php');

$m = be('get','m');
$xmlpath = MAC_ROOT ."/inc/config/timmingset.xml";
$doc = new DOMDocument();
$doc -> formatOutput = true;
$xml = @file_get_contents($xmlpath);
$doc -> loadXML($xml);
$xmlnode = $doc -> documentElement;
$timmingnodes = $xmlnode->getElementsByTagName("timming");

foreach($timmingnodes as $timmingnode){
	$tname = $timmingnode->getElementsByTagName("name")->item(0)->nodeValue;
    $tdes = $timmingnode->getElementsByTagName("des")->item(0)->nodeValue;
    $tstatus = $timmingnode->getElementsByTagName("status")->item(0)->nodeValue;
    $tfile = $timmingnode->getElementsByTagName("file")->item(0)->nodeValue;
    $tparamets = $timmingnode->getElementsByTagName("paramets")->item(0)->nodeValue;
    $tweeks = $timmingnode->getElementsByTagName("weeks")->item(0)->nodeValue;
    $thours = $timmingnode->getElementsByTagName("hours")->item(0)->nodeValue;
    $truntime = $timmingnode->getElementsByTagName("runtime")->item(0)->nodeValue;
    
    if(!empty($truntime)) { $oldweek= date('w',$truntime); $oldhours= date('H',$truntime); }
    $curweek= date('w',time()) ;	$curhours= date("H",time());
    
	if(strlen($oldhours)==1 && intval($oldhours) <10){ $oldhours= '0'.$oldhours; }
	if(strlen($curhours)==1 && intval($curhours) <10){ $curhours= substr($curhours,1,1); }
	
	if( (!empty($m) && $tname==$m) || ($tstatus==1 && ( empty($truntime) || ($oldweek."-".$oldhours) != ($curweek."-".$curhours) && strpos($tweeks,$curweek)>-1 && strpos($thours,$curhours)>-1)) ) {
		$timmingnode->getElementsByTagName("runtime")->item(0)->nodeValue = time();
		$doc -> save($xmlpath);
		
		$p = array();
	    $m = $tparamets;
	    $par = explode('-',$m);
	    $parlen = count($par);
	    $ac = $par[0];
	    
	    $colnum = array('id','pg');
	    if($parlen>=2){
	    	$method = $par[1];
	    	 for($i=2;$i<$parlen;$i+=2){
	            $p[$par[$i]] = in_array($par[$i],$colnum) ? intval($par[$i+1]) : chkSql($par[$i+1]);
	        }
	    }
	    if($p['pg']<1){ $p['pg']=1; }
	    unset($colnum);
	    
	    getDbConnect();
		include(MAC_ROOT."/inc/timming/".$tfile);
	}
}
unset($doc,$p,$par);
?>