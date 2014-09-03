<?php
error_reporting(E_ALL);
 
 require './simple_html_dom.php';
 
echo "<table>";
echo "<thead>";
echo "<tr>";
echo '<th>nome</th>';
echo '<th>apelido</th>';
echo '<th>dataNascimento</th>';
echo '<th>sexo</th>';
echo '<th>imagem</th>';
echo '<th>idade</th>';
echo '<th>cidade</th>';
echo '<th>estado</th>';
echo '<th>altura</th>';
echo '<th>peso</th>';
echo '<th>pele</th>';
echo '<th>corCabelo</th>';
echo '<th>corOlhos</th>';
echo '<th>caracteristicasDiversas</th>';
echo '<th>dataDesaparecimento</th>';
echo '<th>localDesaparecimento</th>';
echo '<th>circunstanciaLocalizacao</th>';
echo '<th>localLocalizacao</th>';
echo '<th>dataLocalizacao</th>';
echo '<th>dadosComplementares</th>';
echo '<th>situacao</th>';
echo '<th>fonte</th>';
echo "</tr>";
echo "</thead>";
echo "<tbody>";

$urlBase = 'http://www.desaparecidos.gov.br';
$domMain = file_get_html($urlBase);
foreach($domMain->find("#mainmeu .menu .item-127 a") as $li){
		$d = $li->href;
}
$urlDesaparecido = $urlBase.$d;

echo"URL do link desaparecidos".'<br>';
echo "$urlDesaparecido".'<br>';
echo "<a href='$urlDesaparecido'>Clique aqui</a><br/>";
//pagina do site onde se encontram as pessoas cadastradas
$domDesaparecidos = file_get_html($urlDesaparecido);
foreach($domDesaparecidos->find(".paginacao a")as $page) {
    $pages[] = $page->href;
}

$vetor = array();
$i = 0;
$count = 1;
//string que vai armazenar todos os RDFs
$rdf = '<?xml version="1.0"?>
<rdf:RDF
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:foaf="http://xmlns.com/foaf/0.1/" 
	xmlns:dbpprop="http://dbpedia.org/property/"
	xmlns:being="http://purl.org/ontomedia/ext/common/being#"
	xmlns:owl="http://www.w3.org/2002/07/owl#"
	xmlns:des="http://www.desaparecidos.com.br/rdf/">
	';

foreach($pages as $link){
	$domPage = file_get_html($link);
	foreach($domPage->find(".boxDesaparecidor a") as $pessoa){
		$urlPessoa = $urlBase.$pessoa->href;
		$domPessoa = file_get_html($urlPessoa);		
		$nome = $domPessoa->find(".titulo");//Ate aqui foi testado e esta tudo OK
		$situacao = $domPessoa->find(".desaparecido");//OK
		$imagem = $domPessoa->find(".slides_container img");
		$celulas = array();
		foreach($domPessoa->find("table") as $table){
			foreach($table->find("tr") as $tr){
				foreach($tr->find("td") as $td){ $celulas[] = $td->plaintext; }
				  }
				}
		$sexo = trim(str_replace('Sexo:', '', $celulas[0]));
		$pele = trim(str_replace('Cor da pele: ', '', $celulas[1]));
		$peso = trim(str_replace('Peso aproximado: ', '', $celulas[3]));
		$idade = trim(str_replace('Idade hoje: ', '', $celulas[4]));
		$altura = trim(str_replace('Altura aproximada: ', '', $celulas[5]));
		$corOlhos = trim(str_replace('Olhos: ', '', $celulas[7]));
		$cabelo = trim(str_replace('Cabelos: ', '', $celulas[9]));
		$cidade;
		
		$dados = array();
		foreach($domPessoa->find(".inf p") as $p){ $dados[] = $p->plaintext;}
		$dados_invalidos = array('Desapareceu em ','(h&aacute; ',', no munic&iacute;pio ');
		$dados_processados1 = str_replace($dados_invalidos, '', $dados[0]);//string com dados importantes
		
		$dados_separados = explode(" ", $dados_processados1);
		$dataDesaparecimento = $dados_separados[0];
		$dados_processados2 = explode("dias)de ", $dados_processados1);
		$cidade = $dados_processados2[1];
		$dados_processados3 = explode("/", $cidade);
		$dados_processados4 = explode(".", $dados_processados3[1]);
		$estado = $dados_processados4[0];
		$outrosDados;
		$info = $dados[1]." ".$dados[2].". ".$dados[3];
		
		//////////// Dados a serem guardados no vetor e em um arquivo txt no formato RDF//////////////////////////////////////////////
		
		$nomev = $nome[0]->plaintext;
        $sexov = $sexo;
        $imagemv = $imagem[0]->src;
        $idadev = $idade;
        $cidadev = $cidade;
        $estadov = $estado;
        $alturav = $altura;
        $pesov = $peso;
        $pelev = $pele;
        $corCabelov = $cabelo;
        $corOlhosv = $corOlhos;
        $dt_desaparecimentov = $dataDesaparecimento;
        $dadosComplementaresv = $info;
        $situacaov = $situacao[0]->plaintext;
        $fontev = $urlPessoa;

		$pessoa = array($nomev, $sexov, $imagemv, $idadev, $cidadev, $estadov, $alturav, $pesov, $pelev, $corCabelov, $corOlhosv, $dt_desaparecimentov, $dadosComplementaresv, $situacaov, $fontev);
		///////////////////////////Guardando em RDF////////////////////////////////////////////
		
		$rdf = $rdf.'
	<rdf:description rdf:about="http://www.desaparecidos.gov.br">
		<foaf:name>' . $nomev. '</foaf:name>
		<foaf:gender>' .$sexov. '</foaf:gender>
		<foaf:img>' .$imagemv. '</foaf:img>
		<foaf:age>' .$idadev. '</foaf:age>
		<des:cityDes>' .$cidadev. '</des:cityDes>
		<des:cityDes rdf:resource="http://rdf.freebase.com/ns/en.juiz_de_fora" />
		<des:cityDes rdf:resource="http://dbpedia.org/resource/Juiz_de_Fora" />
		<des:cityDes rdf:resource="" />
		<des:cityDes rdf:resource="" />
		<des:stateDes>' .$estadov. '</des:stateDes>
		<dbpprop:height>' .$alturav. '</dbpprop:height>
		<dbpprop:weight>' .$pesov. '</dbpprop:weight>
		<des:skin>' .$pelev. '</des:skin>
		<dbpprop:hairColor>' .$corCabelov. '</dbpprop:hairColor>
		<dbpprop:eyeColor>' .$corOlhosv. '</dbpprop:eyeColor>
		<des:disappearanceDate>' .$dt_desaparecimentov. '</des:disappearanceDate>
		<des:additionalData>' .$dadosComplementaresv. '</des:additionalData>
		<des:status>' .$situacaov. '</des:status>
		<des:source>' .$fontev. '</des:source>
	</rdf:description>
	
';
		$vetor[$i] = $pessoa;
		$i = $i + 1;
		$count = $count + 1;
	  }
	}
	$rdf = $rdf.'</rdf:RDF>';
	
	echo " total de pessoas eh: $count";
		$v = $vetor[0];
		foreach($v as $p){
			echo $p.'<br/>';
			}
////Forçando o Download//////////////////////////////////
header('Content-type: text/plain');
header( 'Content-Length: ' . strlen( $rdf ) ); 
header('Content-Disposition: attachment; filename="RDF.txt"');

echo $rdf;
?>
