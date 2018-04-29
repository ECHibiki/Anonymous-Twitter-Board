<?php
	class OAuthRandom{	
		public static function randomAlphaNumet($len){
			$rand_string = "";
			$opt_str = "1234567890qwertyuiopasdfghjklzxcvbnmZXCVBNMASDFGHJKLQWERTYUIOP";
			$options = str_split($opt_str);
			$max = mb_strlen($opt_str) - 1;
			for($char = 0 ; $char < $len ; $char++){
				 $rand_string .= $options[rand(0, $max)];
			}
			
			return str_replace("/", "5", str_replace("=", "2", $rand_string));
		}
	}
	
	class StandardFunctions{
		public static function getIniFile($path){
			$path_file = fopen($path,"r");
			$return_array = array();
			while(!feof($path_file)){
				$line = fgets($path_file);
				$key = trim(substr($line,0,strpos($line, "=")));
						//eat last character
				$value = trim(substr($line, strpos($line, "=")+1));

				$return_array[$key] = $value;
			}
			fclose($path_file);
			return $return_array;
		}
		
		public static function testBlock($val){
			echo "<pre>";
			var_dump($val);
			echo "</pre>";
		}
	
	
	public static function recursiveEchoJson($json, $indents){
		echo "<pre>";
		foreach($json as $key => $attribute){
			if(is_array ($attribute)){
				StandardFunctions::makeIndents($indents);
				echo "$key {\n";
				
				StandardFunctions::recursiveEchoJson($attribute, ++$indents);
				
				StandardFunctions::makeIndents(--$indents);
				echo "}\n";
			}
			else{
				StandardFunctions::makeIndents($indents);
				echo "$key = $attribute \n";
			}
		}
		echo "</pre>";
		if($indents == 1) echo "<hr/>";
	}
	
		
	public static function makeIndents($indent_count){
		for ($i = 0; $i < $indent_count ; $i++){	echo "\t";	}
	}
	
	
		//https://stackoverflow.com/questions/5695145/how-to-read-and-write-to-an-ini-file-with-php
	public static function write_php_ini($array, $file)
	{
		$res = array();
		foreach($array as $key => $val)
		{
			if(is_array($val))
			{
				$res[] = "[$key]";
				foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
			}
			else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
		}
		StandardFunctions::safefilerewrite($file, implode("\r\n", $res));
	}

	public static function safefilerewrite($fileName, $dataToSave)
	{    if ($fp = fopen($fileName, 'w'))
		{
			$startTime = microtime(TRUE);
			do
			{            $canWrite = flock($fp, LOCK_EX);
			   // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			   if(!$canWrite) usleep(round(rand(0, 100)*1000));
			} while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

			//file was locked so now we can store information
			if ($canWrite)
			{            fwrite($fp, $dataToSave);
				flock($fp, LOCK_UN);
			}
			fclose($fp);
		}

	}
	
}
?>