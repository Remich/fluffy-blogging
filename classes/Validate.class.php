<?php

	/**
	*	Copyright 2010-2013 René Michlke
	*
	*	This file is part of RM Internet Suite.
	*/
	
	class Validate {
	
		public function isInRequest($param = NULL, $arr = NULL) {
		
			if($param === NULL) die('Software Error: Missing Parameter in isInRequest() call');
			if($arr === NULL) $arr = $this->request;
			
			/*echo 'param: ';print_r($param);echo'<br>';
			echo 'arr: ';print_r($arr); echo'<br>';*/
			
			if(is_string($param)) {
			
				if(!isset($arr[$param])) die('Software Error: Missing Paramater "'.$param.'".');
				
			} elseif(is_array($param)) {
			
				foreach($param as $key => $item) {
				
					if(is_array($item)) { 
					
						if(isset($arr[$key]))
							$this->isInRequest($item, $arr[$key]); 
						else
							die('Software Error: Missing Paramater "'.$key.'".');
							
					} elseif(!isset($arr[$item])) die('Software Error: Missing Paramater "'.$item.'".');
					
				} 
				
			} else die('Software Error: Wrong Parameter in isInRequest() call. Expected string or array. '.gettype($param).' given.');
		}
		
	} // <!-- end class ’Foo’ -->
	
?>
