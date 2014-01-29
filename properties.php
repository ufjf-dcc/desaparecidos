<?php	
	class Constant{
  
		public $DB_HOST = "http://172.10.40.9:10035";//db.host= http://172.18.10.52:8890/conductor"
		public $DB_USER = "desaparecidos";//db.user=
		public $DB_PASS = "get2011";//db.pass=
		public $DB_DESA = "desaparecidos";//db.desa=

	}	

	function getProperty($string){

		$dados = fopen("application.properties","r");
		
		while (!feof($dados)) {

			$linha = fgets($dados, 4096);

			$pos = stripos($linha, $string);
			

			if($pos !== false) {
				
				$db= trim(str_replace($string,"",$linha));
				
			}
		
		}
			
		fclose($dados);
		

		return $db;	
	}

	
?>
