<?php
if(!defined('MAC_ROOT')){
	exit('Access Denied');
}
if($MAC['other']['comment']==0){ echo 'comment closed'; return; }
if($method=='show')
{
	$tpl->C["siteaid"] = $tpl->P['aid'];
	if($tpl->C["siteaid"]>=16 && $tpl->C["siteaid"]<=18){
		$tpl->C["siteaid"]=16;
		$tpl->D['d_id'] = $tpl->P['vid'];
	}
	elseif($tpl->C["siteaid"]==26){
		$tpl->D['a_id'] = $tpl->P['vid'];
	}
	else{
		echo '参数aid的值错误';
	}
	if($tpl->P['pg']<1){ $tpl->P['pg']=1; }
	$tpl->P['cp'] = 'app';
	$tpl->P['cn'] = 'comment'.$tpl->P['pg'].'-id-'.$tpl->P['vid'];
	//echoPageCache($tpl->P['cp'],$tpl->P['cn']);
	$tpl->H = loadFile(MAC_ROOT_TEMPLATE."/home_comment.html");
	getDbConnect();
	$tpl->mark();
	$tpl->H = str_replace("{maccms:commentverify}", $MAC['other']['commentverify'] ,$tpl->H);
	$ps='MAC.Comment.Show(\'{url}\')';
	$tpl->pageshow($ps);
}

elseif($method=='save')
{
	$c_vid = intval(be("all", "vid"));
	$c_type = intval(be("all", "aid"));
    $c_name = be("all", "c_name");  $c_name = chkSql($c_name);
    $c_content = be("all", "c_content");  $c_content = chkSql($c_content);
    $c_code = be("all","c_code");  $c_code = chkSql($c_code);
    
    if($c_type>=16 && $c_type<=18){
    	$c_type=16;
    }
    
    if (empty($c_name) || empty($c_content)){ echo '请输入昵称和内容'; exit; }
    if ($MAC['other']['commentverify']==1 && $_SESSION["code_comment"] != $c_code){ echo '验证码错误'; exit; }
    if (getTimeSpan("last_commenttime") < $MAC['other']['commenttime']){ echo '请不要频繁操作';exit; }
    $pattern = '/[^\x00-\x80]/'; 
	if(!preg_match($pattern,$c_content)){
		echo '内容必须包含中文,请重新输入!';exit;
	}
    
    $c_name = badFilter($c_name);
    $c_content = badFilter($c_content);
    $c_ip = ip2long(getIP());
    $c_time = time();
    
    if ($MAC['other']['commentaudit']==1){ $c_hide=1; } else { $c_hide=0; }
	if (strlen($c_name) >64){ $c_name = substring($c_name,64);}
	if (strlen($c_content) >255){ $c_content = substring($c_content,255);}
	
	getDbConnect();
	$db->Add ("{pre}comment", array("c_vid","c_hide","c_type","c_name", "c_ip", "c_time", "c_content"), array($c_vid, $c_hide, $c_type,$c_name, $c_ip, $c_time, $c_content));
	
	$_SESSION["last_commenttime"]  = time();
	$_SESSION["code_comment"] = "";
	echo "ok";
}

else
{
	showErr('System','未找到指定系统模块');
}
?>