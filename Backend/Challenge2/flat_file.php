<?php
	header('Content-Type:application/json');
	/*
		@author: Bryan Herrera
		@description: program takes queries through the url and returns the heros that match.
	*/
	$data = "../../data.csv";
	//superheros array
	$superheros = readFromFile($data);
	$counter = 0;
	//status code
	$code;
	//the final array of superheros to be placed in JSON return object.
	$returnarray = [];
	//the final json object to return
	$responseobject;
	$success = false;
	if(!($superheros == NULL)){
		$code = http_response_code();
		if($code == 200)
			$success = true;

		$superheros = createHerosArray($superheros);
		if(isset($_GET) && $_GET){
            while($counter < count($superheros)){
				array_push($returnarray, $superheros[$counter]);
				foreach($_GET as $key => $value){
				    $value = str_replace('/', '', $value);
					$currenthero = end($returnarray);
					if($currenthero){
						if(!(array_key_exists($key, $currenthero))){
							array_pop($returnarray);
						}
						//changed else-if to do regular expression matching
                        else if(!(preg_match('/' .$value. '/', $currenthero[$key]))){
                            array_pop($returnarray);
                        }
					}
				}
				$counter ++; 
			}

            //set up the return object
            $responseobject = array(
                'status' => $code,
                "success" => $success,
                "version"=>"JSON-Flat-File-0.1",
                "hero:"=> $returnarray,
            );
            //return the final json object
            echo json_encode($responseobject,JSON_PRETTY_PRINT);
		}
		else{echo "enter a query";}
	}
	else{
		echo "superheros aren't here!";
	}




	/*returns a superhero object whose id matches the id passed into the function
		@param val: the id to check for
		@param superheros: the list of superheros to check.
		@return: Object representation of superhero that has id == val.
	*/
	function getHeroWithId($val, $superheros){
		$rtn = NULL;
		foreach($superheros as $hero){
			if(isset($hero['id'])){
				if($hero['id'] == $val){
					$rtn = $hero;
				}
			}
		}
		return json_encode($rtn, JSON_PRETTY_PRINT);
	}
	/*Reads the data from the file
	@param $data: path to where the data is located.
	@return: an array with all superheros
	*/
	function readFromFile($data){
		$handle = fopen($data, "r") or die("Can't read this file!");
		//array to return
		$rtn = [];
		//while not at end of file
		if(isset($handle)){
			while(!feof($handle)){
				array_push($rtn, fgetcsv($handle, filesize($data)));
			}
			fclose($handle);
			
		}
		else{
			echo "val can't be set!";
			$rtn = NULL;
		}

		return $rtn;
	}

	//prepares the json array for running queries
	function createHerosArray(&$heros){
		foreach($heros as &$val){
			 $val = array("id" => "{$val[0]}",
			 			 "first-name" => trim("{$val[1]}"),
			 			 "last-name" => trim("{$val[2]}"),
			 			 "persona" => trim("{$val[3]}"),
			 			 "sex" => trim("{$val[4]}"),
			 		);

		}
		return $heros;
	}

?>