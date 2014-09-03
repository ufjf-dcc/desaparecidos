<?php	
	class Constant{
  
<<<<<<< HEAD
		public $DB_HOST = "db.host=";
		public $DB_USER = "db.user=";
		public $DB_PASS = "db.pass=";
		public $DB_DESA = "db.desa=";
=======
		public $DB_HOST = "http://172.10.40.9:10035";//db.host= http://172.18.10.52:8890/conductor"
		public $DB_USER = "desaparecidos";//db.user=
		public $DB_PASS = "get2011";//db.pass=
		public $DB_DESA = "desaparecidos";//db.desa=
>>>>>>> 0a5cb583163a08a9d30a7a8a27c68616187d27c7

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
