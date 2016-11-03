<?php
/**
* 实例化分页 $page = new GetPage(1,10);
* 获取分页html代码 $html = $page->showpage();
*/
class GetPage{
	private $page;
	private $page_num;
	
	public function __construct($page=1,$page_num=10){
		$this->page = $page;
		$this->page_num = $page_num;
	}

	public function setUrl() {  
		 $_url = $_SERVER["REQUEST_URI"];  
		 $_par = parse_url($_url);  
		 if (isset($_par['query'])) {  
				parse_str($_par['query'],$_query);  
				unset($_query['page']);  
				$_url = $_par['path'].'?'.http_build_query($_query);  
		 }  
		 return $_url;  
	  } 
	  
	 //获取分页
	function showpage($page=$this->page,$page_num=$this->page_num){
		$url = setUrl();
		$url = trim($url,'.html');
		if(preg_match('/\?/',$url)){
			$url .='&';
		}else{
			$url .='?';
		}
		if($_POST['search']!=null){
			$url .= 'search='.$_POST['search'].'&';
		}
		
		$html = '';
		$html .= '<ul class="pagination pull-right">
				 <li class="footable-page-arrow"><a target="_self" href="'.$url.'page=1" class="disable">&laquo;</a></li>
				 <li class="footable-page-arrow"><a target="_self" href="'.$url.'page=';
		if(($page-1)>0){
			$html .= $page-1;
		}else{
			$html .= 1;
		}
		$html .= '" class="disable">&lsaquo;</a></li>';
		for($i=2;$i>0;$i--){
			if(($page-$i)>0)
			$html .= '<li class="footable-page-arrow"><a target="_self" href="'.$url.'page='.($page-$i).'">'.($page-$i).'</a></li>';
		}
		for($i=0;$i<3;$i++){
			if(($page+$i)<=$page_num){
				$html .= '<li class="footable-page-arrow"><a target="_self"';
				if($page == ($page+$i)){
				$html .= '';	
				}
				$html .= ' href="'.$url.'page='.($page+$i).'">'.($page+$i).'</a></li>';
			}
		}

		
		$html .= '<li class="footable-page-arrow"><a target="_self" href="'.$url.'page=';
		if(($page+1)<=$page_num){
			$html .= $page+1;
		}else{
			$html .= $page_num;
		}
		
		$html .= '">&rsaquo;</a></li>
				<li class="footable-page-arrow"><a target="_self" href="'.$url.'page='.($page_num).'">&raquo;</a></li>
				</ul>';
		return $html;
	} 
}
