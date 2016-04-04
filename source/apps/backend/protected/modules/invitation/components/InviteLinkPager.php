<?php
class InviteLinkPager extends CLinkPager {
	protected $cssPager = "";
	protected $linkPager = "";
	protected $mpages;
	
	public function getLinks($link = "") {
		
		$buttons = $this->createPageButtons();
		if ($buttons){
			$html = "<ul class='$this->cssPager'>";
			foreach ($buttons as $items){
				$html .= $items;
			}
			$html .= "</ul>";
			return $html;
		}
		return false;
	}
	
	public function initPager($itemCount, $pageSize, $css = ""){
		$this->cssPager = $css == "" ? "paging" : $css;
		$this->mpages = new CPagination($itemCount);
		$this->mpages->setPageSize($pageSize);
		$this->setPages($this->mpages);
	}
	
	protected function createPageUrl($page){
		$params = $_GET;
		if($page>0) // page 0 is the default
			$params[$this->mpages->pageVar]=$page+1;
		else
			unset($params[$this->mpages->pageVar]);
		return "/" . $this->getController()->getRoute() . "?" . http_build_query($params);
	}
}

?>