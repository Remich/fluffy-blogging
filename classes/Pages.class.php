<?php

	/**
	*	Copyright 2010-2013 René Michalke
	*
	*	This file is part of RM Internet Suite.
	*/
	
	
	/**
	* class Pages
	*
	* Divides Content by displaying content with flipping function
	*/
	class Pages {
	
		var $_entries;
		var $_itemsPerPage;
		
		public function __construct($entries, $itemsPerPage) {

			if (trim($entries) === "" ||
				!is_numeric($entries)) {
				die('ERROR: Entries is empty or not numeric! (Pages::__construct()');
			}
			$this->_entries = $entries;

			if (trim($itemsPerPage) === "" ||
				!is_numeric($itemsPerPage)) {
				die('ERROR: itemsPerPage is empty or not numeric! (Pages::__construct()');
			}
			$this->_itemsPerPage = $itemsPerPage;
		}
		
		public function getPages() {
			$pages = ceil($this->_entries / $this->_itemsPerPage);
			if($pages == 0)
				return 1;
			else
				return $pages;
		}
		
		public function getStart($page) {
			return ($this->getPages() - $page) * $this->_itemsPerPage;
		}
		
		public function getHtml($pageActive, $pages, $float = 0) {
			if($pageActive == 'all') {
				$pageActive = 0;
			}
			$pageActive_plus_1 = $pageActive + 1;
			$pageActive_minus_1 = $pageActive - 1;
			if($float == 1) {
				$class = ' right"';
			}
			$return = false;
			if(is_numeric($pageActive) AND $pages != 1) {
				$return = '<span class="pages'.@$class.'"><span>Pages: </span>';
				if($pageActive != $pages AND $pageActive != 0) {
					$return .= '<a href="ajax.php?action=load&id=page&jump='.$pageActive_plus_1.'">&#171;</a> ';
				}
				
				if($pageActive != $pages) {
					$return .= '<a href="ajax.php?action=load&id=page&jump='.$pages.'">'.$pages.'</a> ';
				} else {
					$return .= '<strong>'.$pages.'</strong> ';
				}
				
				for($a = $pages - 1; $a >= 2; $a--) {
					if($pageActive != $a)
						{
							if($a > $pageActive + 4 OR $a < $pageActive - 4) {
								if($a > $pageActive + 5 OR $a < $pageActive - 5) {
    						} else {
    							$return .= '&#133 ';
    						}
    					} else {
    						$return .= '<a href="ajax.php?action=load&id=page&jump='.$a.'">'.$a.'</a> ';
    					}
    				} else{
    					$return .= '<strong>'.$a.'</strong> ';
    				}
    			}
    			if($pageActive != 1) {
    				$return .= '<a href="ajax.php?action=load&id=page&jump=1">1</a> ';
    			} else {
    				$return .= '<strong>1</strong> ';
    			}
    			
    			if($pageActive != 1 AND $pageActive != 0) {
    				$return .= '<a href="ajax.php?action=load&id=page&jump='.$pageActive_minus_1.'">&#187;</a> ';
    			}
    			if($pageActive == 0) {
    				$return .= '<strong>All</strong> </span>';
    			} else {
    				$return .= '<a href="ajax.php?action=load&id=page&jump=all">All</a> </span>';
    			}
    		}
    		return $return;
    	}
	} // <!-- end class ’Pages’ -->
	
?>
