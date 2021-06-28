<?php
if(!defined('MAC_ROOT')){
	exit('Access Denied');
}
if($method=='index')
{
	$tpl->C["siteaid"] = 20;
	$tpl->P['cp'] = 'app';
	$tpl->P['cn'] = 'artindex'.$tpl->P['pg'];
	echoPageCache($tpl->P['cp'],$tpl->P['cn']);
	$tpl->H = loadFile(MAC_ROOT_TEMPLATE."/art_index.html");
	getDbConnect();
	$tpl->mark();
	$tpl->pageshow();
}

elseif($method=='map')
{
	$tpl->C["siteaid"] = 21;
	$tpl->P['cp'] = 'app';
	$tpl->P['cn'] = 'artmap';
	echoPageCache($tpl->P['cp'],$tpl->P['cn']);
	$tpl->H = loadFile(MAC_ROOT_TEMPLATE."/art_map.html");
	getDbConnect();
	$tpl->mark();

}

elseif($method=='list')
{
	$tpl->C["siteaid"] = 22;
	$tpl->P['cp'] = 'artlist';
	$tpl->P['cn'] = $tpl->P['id'].'-'.$tpl->P['pg'].'-'.$tpl->P['order'].'-'.$tpl->P['by'];
	echoPageCache($tpl->P['cp'],$tpl->P['cn']);
	$tpl->P['arttypeid'] = $tpl->P['id'];
	$tpl->T = $MAC_CACHE['arttype'][$tpl->P['arttypeid']];
	if (!is_array($tpl->T)){ showMsg("获取数据失败，请勿非法传递参数","../"); }
	getDbConnect();
	$tpl->P['dp']=true;
	$tpl->loadlist('art');
	$tpl->P['dp']=false;
	$tpl->mark();
	$tpl->pageshow();
}

elseif($method=='type')
{
	$tpl->C["siteaid"] = 22;
	$tpl->P['cp'] = 'arttype';
	$tpl->P['cn'] = $tpl->P['id'].'-'.$tpl->P['pg'];
	echoPageCache($tpl->P['cp'],$tpl->P['cn']);
	$tpl->P['arttypeid'] = $tpl->P['id'];
	$tpl->T = $MAC_CACHE['arttype'][$tpl->P['arttypeid']];
	if (!is_array($tpl->T)){ showMsg("获取数据失败，请勿非法传递参数","../"); }
	getDbConnect();
	$tpl->P['dp']=true;
	$tpl->loadtype('art');
	$tpl->P['dp']=false;
	$tpl->mark();
	$tpl->pageshow();
}


elseif($method=='topicindex')
{
	$tpl->C["siteaid"] = 23;
	$tpl->P['cp'] = 'arttopicindex';
	$tpl->P['cn'] = $tpl->P['pg'];
	echoPageCache($tpl->P['cp'],$tpl->P['cn']);
	getDbConnect();
	$tpl->H = loadFile(MAC_ROOT_TEMPLATE."/art_topicindex.html");
	$tpl->mark();
	$tpl->pageshow();
}

elseif($method=='topic')
{
	$tpl->C["siteaid"] = 24;
	$tpl->P['cp'] = 'arttopic';
	$tpl->P['cn'] = $tpl->P['id'].'-'.$tpl->P['pg'].'-'.$tpl->P['order'].'-'.$tpl->P['by'];
	echoPageCache($tpl->P['cp'],$tpl->P['cn']);
	$tpl->P['arttopicid'] = $tpl->P['id'];
	$tpl->T = $MAC_CACHE['arttopic'][$tpl->P['arttopicid']];
	if (!is_array($tpl->T)){ showMsg("获取数据失败，请勿非法传递参数","../"); }
	getDbConnect();
	$tpl->loadtopic ('art');
	$tpl->pageshow();
}

elseif($method=='search')
{
	$tpl->C["siteaid"] = 25;
	$wd = trim(be("all", "wd")); $wd = chkSql($wd);
	if(!empty($wd)){ $tpl->P["wd"] = $wd; }
	
	if ( $tpl->P['pg']==1 && getTimeSpan("last_arsearchtime") < $MAC['app']['searchtime']){ 
		showMsg("请不要频繁操作，时间间隔为".$MAC['app']['searchtime']."秒",MAC_PATH);
		exit;
	}

    if(intval($MAC['app']['searchlen'])<1){
        $MAC['app']['searchlen'] = 10;
    }

	if(mb_strlen($wd) > $MAC['app']['searchlen']){
	    $wd = substring($wd,$MAC['app']['searchlen']);
        $tpl->P["wd"] = $wd;
    }
	
	//if (empty($tpl->P["wd"]) && empty($tpl->P["ids"]) && empty($tpl->P["pinyin"]) && empty($tpl->P["letter"]) && empty($tpl->P["tag"]) && empty($tpl->P["type"]) ){ alert ("搜索参数不正确"); }
	
	$tpl->P['cp'] = 'artsearch';
	$tpl->P['cn'] = urlencode($tpl->P['wd']).'-'.$tpl->P['pg'].'-'.$tpl->P['order'].'-'.$tpl->P['by'].'-'.$tpl->P['ids']. '-'.$tpl->P['pinyin']. '-'.$tpl->P['type'] .'-'.urlencode($tpl->P['tag']) ;
	echoPageCache($tpl->P['cp'],$tpl->P['cn']);
	$tpl->P["where"]='';
	$tpl->P["des"]='';
	
	getDbConnect();
	
	
	if (!empty($tpl->P["letter"])){
    	$tpl->P["key"]=$tpl->P["letter"];
    	$tpl->P["des"] = $tpl->P["des"] . "&nbsp;首字母为" . $tpl->P["letter"];
    }
    
    if($tpl->P["wd"]=='{wd}'){ $tpl->P["wd"]=''; }
    if (!empty($tpl->P["wd"])) {
    	$tpl->P["key"]=$tpl->P["wd"] ;
    	$tpl->P["des"] = $tpl->P["des"] . "&nbsp;名称或主演为" . $tpl->P["wd"];
    }
    if (!empty($tpl->P["pinyin"])){
    	$tpl->P["key"]=$tpl->P["pinyin"] ;
    	$tpl->P["des"] = $tpl->P["des"] . "&nbsp;拼音为" . $tpl->P["pinyin"];
    }
    if (!empty($tpl->P["tag"])){
		$tpl->P["key"]=$tpl->P["tag"] ;
		$tpl->P["des"] = $tpl->P["des"] . "&nbsp;Tag为" . $tpl->P["tag"];
	}
    $tpl->P['typepid'] = 0;
	if(!empty($tpl->P["typeid"])){
		$typearr = $MAC_CACHE['arttype'][$tpl->P['typeid']];
		if (is_array($typearr)){
			$tpl->P['typepid'] = $typearr['t_pid'];
			if (empty($tpl->P["key"])){ $tpl->P["key"]= $typearr["t_name"];  }
			$tpl->P["des"] = $tpl->P["des"] . "&nbsp;分类为" . $typearr["t_name"];
		}
	}
	if(!empty($tpl->P["ids"])){
		$arr = explode(',',$tpl->P["ids"]);
		for($i=0;$i<count($arr);$i++){
			$arr[$i] = intval($arr[$i]);
		}
		$tpl->P["ids"] = join(',',$arr);
	}
	
	$tpl->H = loadFile(MAC_ROOT_TEMPLATE."/art_search.html");
	$tpl->mark();
	$tpl->pageshow();
	
	$colarr = array('{page:des}','{page:key}','{page:now}','{page:order}','{page:by}','{page:wd}','{page:wdencode}','{page:pinyin}','{page:letter}','{page:typeid}','{page:typepid}');
	$valarr = array($tpl->P["des"],$tpl->P["key"],$tpl->P["pg"],$tpl->P["order"],$tpl->P["by"],$tpl->P["wd"],urlencode($tpl->P["wd"]),$tpl->P["pinyin"],$tpl->P["letter"],$tpl->P['typeid'],$tpl->P['typepid']   );
	
	$tpl->H = str_replace($colarr, $valarr ,$tpl->H);
    unset($colarr,$valarr);
    $linktype = $tpl->getLink('art','search','',array('typeid'=>$tpl->P['typepid']));
    $linkletter = $tpl->getLink('art','search','',array('letter'=>''));
    
    $linkorderasc = $tpl->getLink('art','search','',array('order'=>'asc'));
    $linkorderdesc = $tpl->getLink('art','search','',array('order'=>'desc'));
    $linkbytime = $tpl->getLink('art','search','',array('by'=>'time'));
    $linkbyhits = $tpl->getLink('art','search','',array('by'=>'hits'));
    $linkbyscore = $tpl->getLink('art','search','',array('by'=>'score'));
    
    $tpl->H = str_replace(array('{page:linkletter}','{page:linktype}','{page:linkorderasc}','{page:linkorderdesc}','{page:linkbytime}','{page:linkbyhits}','{page:linkbyscore}',), array($linkletter,$linktype,$linkorderasc,$linkorderdesc,$linkbytime,$linkbyhits,$linkbyscore) ,$tpl->H);
	$_SESSION["last_artsearchtime"]  = time();
}

elseif($method=='detail')
{
	$tpl->C["siteaid"] = 26;
	$tpl->P['cp'] = 'art';
	$tpl->P['cn'] = $tpl->P['id'].'-'.$tpl->P['pg'];
	echoPageCache($tpl->P['cp'],$tpl->P['cn']);
	getDbConnect();
	$sql = "SELECT * FROM {pre}art WHERE a_hide=0 AND a_id=" . $tpl->P['id'];
	$row = $db->getRow($sql);
	if (!$row){ showMsg ("获取数据失败，请勿非法传递参数", "../"); }
	$tpl->T = $MAC_CACHE['arttype'][$row['a_type']];
	$tpl->D = $row;
	unset($row);
	$tpl->loadart();
	$tpl->replaceArt();
}

else
{
	showErr('System','未找到指定系统模块');
}
?>