<?php
	class Form{
		private $filename;
		
		function __construct($filename){
			$filename="form/".$filename.".php";
			if(file_exists($filename)){
				$this->filename=$filename;
			}
			else{
				throw new Exception("Ce fichier n'existe pas");
			}
		}
		public function print($data=array(),$he=true){
			$idata=0;
			$ldata=count($data);
			$dataname=array_keys($data);
			$datavalue=array_values($data);
			while($idata<$ldata){
				if(($he)&&(gettype($datavalue[$idata])=="string")){
					$datavalue[$idata]=htmlentities($datavalue[$idata]);
				}
				eval('$'.$dataname[$idata].'=$datavalue[$idata];');
				$idata++;
			}
			unset($idata);
			unset($ldata);
			unset($datavalue);
			unset($dataname);
			unset($he);
			include($this->filename);
		}
		public function isCommitted(){
			$balises=$this->searchBalise();
			$ret=true;
			$i=0;
			$length=count($balises);
			$csrf=getCsrfObject();
			$method="get";
			while(($i<$length)&&($ret)){
				$b=$balises[$i];
				if($b->getBalise()=="form"){
					if(strtolower($b->get("method"))=="post"){
						$ret=$csrf->usePostToken();
						$method="post";
					}
					else{
						$ret=$csrf->useGetToken();
						$method="get";
					}
				}
				else if((($b->getBalise()=="input")&&($b->get("type")=="file"))||(($b->has("name"))||($this->has($method,$b->get("name"))))){
					if($b->getBalise()=="input"){
						if($b->has("required")){
							if($b->get("type")!="text"){
								if($b->get("type")=="number"){
									$value=$this->get($method,$b->get("name"));
									if(($value=="")||($value!=intval($value))){
										$ret=false;
									}
								}
								else if($b->get("type")=="file"){
									if(Files::exist($b->get("name"))){
										if(Files::hasError($b->get("name"))){
											$ret=false;
										}
									}
									else{
										$ret=false;
									}
								}
								else{
									if($this->get($method,$b->get("name"))==""){
										$ret=false;
									}
								}
							}
							else{
								if($this->get($method,$b->get("name")=="")){
									$ret=false;
								}
							}
						}
						else{
							if($b->get("type")=="file"){
								if(Files::exist($b->get("name"))){
									if(Files::hasError($b->get("name"))){
										$ret=false;
									}
								}
							}
							else if($b->get("type")=="number"){
								$value=$this->get($method,$b->get("name"));
								if($value!=intval($value)){
									$ret=false;
								}
							}
						}
					}
					else{
						if(($b->has("required"))&&($this->get($method,$b->get("name"))=="")){
							$ret=false;
						}
					}
				}
				else{
					$ret=false;
				}
				$i++;
			}
			return($ret);
		}
		private function searchBalise(){
			$file=file($this->filename);
			$search="<";
			$seb=">";
			$balise=array("form","input","select","textarea");
			$blength=count($balise);
			$i=0;
			$length=count($file);
			$res=array();
			while($i<$length){
				$j=0;
				$line=$file[$i];
				$llength=strlen($line);
				while($j<$llength){
					$char=substr($line,$j,1);
					if($char==$search){
						$str="";
						$good=false;
						$ec=false;
						$j++;
						while(($j<$llength)&&(!$good)){
							$char=substr($line,$j,1);
							if((!$ec)&&($char==$seb)){
								$good=true;
							}
							else{
								if($char=="\""){
									$ec=!$ec;
								}
								$str=$str.$char;
								$j++;
							}
						}
						$good2=false;
						$k=0;
						$a=new HTMLInput($str);
						if(!(($a->getBalise()=="input")&&(($a->get("type")=="submit")||($a->get("type")=="reset")))){
							while(($k<$blength)&&(!$good2)){
								if($a->getBalise()==$balise[$k]){
									$res=array_merge($res,array($a));
									$good2=true;
								}
								else{
									$k++;
								}
							}
						}
					}
					$j++;
				}
				$i++;
			}
			return($res);
		}
		private function get($method,$name){
			if($method=="post"){
				return(Post::get($name));
			}
			else{
				return(Getp::get($name));
			}
		}
		private function has($method,$name){
			if($method=="post"){
				return(Post::exist($name));
			}
			else{
				return(Getp::exist($name));
			}
		}
	}
	class HTMLInput{
		private $attributes;
		private $balise;
		
		function __construct($str){
			$balise="";
			$i=0;
			$length=strlen($str);
			$good=false;
			while(($i<$length)&&(!$good)){
				$char=substr($str,$i,1);
				if($char!=" "){
					$balise.=$char;
				}
				else{
					$good=true;
				}
				$i++;
			}
			$this->balise=$balise;
			$this->attributes=array();
			$isName=true;
			$ec=false;
			$name="";
			$value="";
			while($i<$length){
				$char=substr($str,$i,1);
				if($isName){
					if($char=="="){
						$isName=false;
						$i++;
					}
					else if($char==" "){
						$this->attributes=array_merge($this->attributes,array($name=>""));
						$name="";
					}
					else{
						$name.=$char;
					}
				}
				else{
					if($char=="\""){
						$isName=true;
						$this->attributes=array_merge($this->attributes,array($name=>$value));
						$name="";
						$value="";
						$i++;
					}
					else{
						$value.=$char;
					}
				}
				$i++;
			}
			if($name!=""){
				$this->attributes=array_merge($this->attributes,array($name=>""));
			}
		}
		public function get($attribute){
			return($this->attributes[$attribute]);
		}
		public function has($attribute){
			return(isset($this->attributes[$attribute]));
		}
		public function getBalise(){
			return($this->balise);
		}
	}
