<?php

	/**
	*	Copyright 2010-2014 René Michalke.
	*/
    require_once("models/Article/Article.class.php");

	class ListOfArticles extends ModelList {
		
		protected $_name = "ListOfArticles";
		protected $_month = NULL;
		protected $_year = NULL;
		
		public function __construct($request = NULL) {
			$this->_request = $request;

			// Tag-ID
			if (isset($this->_request['tag_id'])) {
				if (trim($this->_request['tag_id']) === "" ||
					!is_numeric($this->_request['tag_id'])) {
					die("ERROR: Tag-ID is empty or not numeric! (ListOfArticles::__construct()");
				}

				$this->tag_id = $this->_request['tag_id'];
			}

			// Month
			if (isset($this->_request['month'])) {
				if (trim($this->_request['month'])	=== "" ||
					!is_numeric($this->_request['month'])) {
					die("ERROR: Month is empty or not numeric! (ListOfArticles::__construct()");
				}

				$this->_month = $this->_request['month'];
			}

			// Year
			if (isset($this->_request['year'])) {
				if (trim($this->_request['year'])	=== "" ||
					!is_numeric($this->_request['year'])) {
					die("ERROR: Year is empty or not numeric! (ListOfArticles::__construct()");
				}

				$this->_year = $this->_request['year'];
			}

			$this->load();
		}
		
		public function load() {

			// Select Articles by TAG
			if($this->_tag_id != NULL) {

				require_once("models/Tag/Tag.class.php");
				$tag = new Tag(array("id" => $this->_tag_id));

				list($wheres, $this->_params) = $this->getWheres("rel_articles_tags");

				if($wheres == "") {
					$data = array();
				} else {
					// TODO better use smart SQL-Queries (leftjoin, rightjoint) on relationtable
					$this->_query = "SELECT id FROM article WHERE ".$wheres." ORDER BY a_date DESC";
				}

			// Select Articles by MONTH and YEAR
			} elseif($this->_month != NULL) { 			

				$this->_params = array(
					"month" => $this->_month,
					"year"  => $this->_year
				);

				switch(Config::getOption("db_type")) {
					case "mysql": 
						$wheres = 'DATE_FORMAT(a_date, "%m") = :month AND DATE_FORMAT(a_date, "%Y") = :year';
					break;
				
					case "sqlite": 
						$wheres = 'STRFTIME("%m", a_date) = :month AND STRFTIME("%Y", a_date) = :year';
					break;
				}

				$this->_query = "SELECT id FROM article WHERE ".$wheres." ORDER BY a_date DESC";

			// Select Articles only by YEAR
			} elseif($this->_year != NULL) {

				switch(Config::getOption("db_type")) {
					case "mysql": 
						$wheres = 'DATE_FORMAT(a_date, "%Y") = \''. $this->_year .'\'';
					break;
				
					case "sqlite": 
						$wheres = 'STRFTIME("%Y", a_date) = \''. $this->_year .'\'';
					break;
				}

				$this->_query = "SELECT id FROM article WHERE ".$wheres." ORDER BY a_date DESC";

			// Select all Articles
			} else {

				$this->_query = 'SELECT * FROM article ORDER BY a_date DESC';

			}

			// Get Data From DB
			$data = $this->getDataWithPages(Config::getOption('articles_per_page'));
			
						
			// Create Instances of Articles
			if(!sizeof($data)) {
				$this->_data['content'] = array();
			} else {
				foreach($data as $key => $item) {
	                $tmp = new Article(array('id'=>$item['id']));
	                $this->_data['content'][$key] =  $tmp->display();
	            }
	    	}
		}		
		
	}
?>
