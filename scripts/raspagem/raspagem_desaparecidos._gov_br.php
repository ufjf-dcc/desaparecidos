<?php
error_reporting(E_ALL);
 include("atualizacaoNicolasNew.php");
 include("../simple_html_dom/simple_html_dom.php");
 
 
 
$total_cadastros = 0;
$urlBase = 'http://www.desaparecidos.gov.br';
$domMain = file_get_html($urlBase);
foreach($domMain->find("#mainmeu .menu .item-127 a") as $li){
		$d = $li->href;
}
$urlDesaparecido = $urlBase.$d;


//pagina do site onde se encontram as pessoas cadastradas
$domDesaparecidos = file_get_html($urlDesaparecido);
foreach($domDesaparecidos->find(".paginacao a")as $page) {
    $pages[] = $page->href;
}

$vetor = array();
$i = 0;
$count = 0;



foreach($pages as $link){
	$domPage = file_get_html($link);
	foreach($domPage->find(".boxDesaparecidor a") as $pessoa){
		$urlPessoa = $urlBase.$pessoa->href;
		$domPessoa = file_get_html($urlPessoa);		
		$nome = $domPessoa->find(".titulo");
		$situacao = $domPessoa->find(".desaparecido");
                if ( $situacao == null ){
                    $situacao = $domPessoa->find(".encontrado");
                }
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
        $cidadev = $dados_processados3[0];
        $estadov = $estado;
        $alturav = $altura;
        $pesov = $peso;
        $pelev = $pele;
        $corCabelov = $cabelo;
        $corOlhosv = $corOlhos;
        $dt_desaparecimentov = $dataDesaparecimento;
        $dadosComplementaresv = $info;
        $situacaov = trim($situacao[0]->plaintext);
        if($situacaov == "Desaparecido(a)" ){
            $situacaov = "Desaparecida";
        } 
        else{
            if($situacaov == "Encontrado(a)" ){
                $situacaov = "Encontrada";
            }
        }
        $fontev = $urlPessoa;

        $p = new Pessoa();
        $p->nome = ucwords(strtolower($nomev));
        $p->sexo = $sexov;
        $p->imagem = $imagemv;
        $p->idade = $idadev;
        $p->cidade = ucwords(strtolower($cidadev));
        $p->estado = $estadov;
        $p->altura = $alturav;
        $p->peso = $pesov;
        $p->pele = $pelev;
        $p->cor_cabelo = $corCabelov;
        $p->cor_olho = $corOlhosv;
        $p->data_desaparecimento = $dt_desaparecimentov;
        $p->dados_adicionais = $dadosComplementaresv;
        $p->situacao = $situacaov;
        $p->fonte = $fontev;
        
        $count = $count + 1;
        echo "count :".$count."<br>";
        echo $p->nome."<br>";
        //echoes($p);
        atualizacao_Principal($p);
        echo "------------------------<br>";
        //break;
        }
	}
    echo " total de pessoas eh: ". $count;
		
?>
