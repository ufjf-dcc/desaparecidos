<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buscar extends CI_Controller {

    public function index(){        
        if(empty($_POST['nome'])){
            $data['total'] = $this->total_cadastros();
            $this->load->view('tema/pages/busca', $data);
        }

    }
    
    public function ajax_search($offset = 0, $limit = 30){
        //Carrega a classe de consulta no virtuoso
        $this->load->library('virtuoso_query');
        //Carrega a classe para gerar consultas sparql
        $this->load->library('sparql');

        //Montando a consulta SPARQL

        //Defini os prefixos que serão usados
        $this->sparql->prefix("foaf", "http://xmlns.com/foaf/0.1/");
        $this->sparql->prefix("des", get_schema());
        
        $fields =  array(
              'foaf:name'=>'nome',
              'foaf:nick'=>'apelido',
              'foaf:birthday'=>'data_nascimento',
              'foaf:gender'=>'sexo',
              'foaf:img'=>'imagem',
              'foaf:age'=>'idade',
              'des:cityDes'=>'cidade',
              'des:stateDes'=>'estado',
              'dbpprop:height'=>'altura',
              'dbpprop:weight'=>'peso',
              'des:skin'=>'pele',
              'dbpprop:hairColor'=>'cor_cabelo',
              'dbpprop:eyeColor'=>'cor_olho',
              'des:moreCharacteristics'=>'mais_caracteristicas',
              'des:disappearanceDate'=>'data_desaparecimento',
              'des:disappearancePlace'=>'local_desaparecimento',
              'des:circumstanceLocation'=>'circunstancia_desaparecimento',
              'des:dateLocation'=>'data_localizacao',
              'des:additionalData'=>'dados_adicionais',
              'des:status'=>'situacao',
              'des:source'=>'fonte'
        );
        
        //Defini os campos quer serão exibidos
        $this->sparql->select("?id");
        $this->sparql->select("?nome");        
        $this->sparql->select("?situacao");
        $this->sparql->select("?sexo");
        //Tripla quer será retornada - Está condição deve ser satisfeita para retornar um resultado
        $this->sparql->new_ptrn("?recurso des:id ?id");
        //Condições opcionais
        foreach($fields as $key => $value){            
            $this->sparql->optional($this->sparql->new_ptrn("?recurso $key ?$value"));
        }
        
        if(isset($_POST['nome']) && ($_POST['nome'] != '')) $this->sparql->new_ptrn('FILTER regex(?nome, "'.trim($_POST['nome']).'", "i")');
        if(isset($_POST['situacao']) && ($_POST['situacao'] != '')) $this->sparql->new_ptrn('FILTER regex(?situacao, "'.trim($_POST['situacao']).'", "i")');
        if(isset($_POST['sexo']) && ($_POST['sexo'] != '')) $this->sparql->new_ptrn('FILTER regex(?sexo, "'.trim($_POST['sexo']).'", "i")');
        if(isset($_POST['idade']) && ($_POST['idade'] != '')) $this->sparql->new_ptrn('FILTER regex(?idade, "'.trim($_POST['idade']).'", "i")');
        
        $this->sparql->offset($offset);
        $this->sparql->limit($limit);
        //Ordena por nome
        $this->sparql->order("?nome");
        //processa a consulta
        $query = $this->sparql->query();

        //Carregando os dados para consulta no virtuoso
        //$this->virtuoso_query->load_sparql_http('http://desaparecidos.ice.ufjf.br:8890/sparql/');
        $this->virtuoso_query->load_graph(get_graph());
        $this->virtuoso_query->load_query_sparql($query);
        $this->virtuoso_query->load_format('application/json');
        //Executa a query SPARQL
        $this->virtuoso_query->execute();
        
        //Retorna o resultado no formato especificado
        //$obj_json = $this->virtuoso_query->get_result();

        //Retorna como um objeto mais simples
        $data['desaparecidos'] = $this->virtuoso_query->convert_json_to_simple_object();
        $data['offset'] = $offset + 30;
        $this->load->view('tema/pages/resultado-busca', $data);
    }
    
    public function ajax_search_name(){        
        $result = new stdClass();  
        $result->error = 0;
        if(!isset($_GET['texto'])){
            $result->error = 1;
            $result->message = 'Nenhuma mensagem enviada';
            echo json_encode($result);
            return;
        }
        
        $texto = trim($_GET['texto']);
        $nomes = explode(' ', $texto);        
        
        $query = '';
        $flag = false;
        foreach($nomes as $nome){
            if($nome == '' || $nome == ' ') continue;
            if($flag){
                $query .= ' || ';                
            }
            $query .= 'regex(?nome, "' . $nome . '", "i")';            
            $flag = true;
        }
        
        //Carrega a classe de consulta no virtuoso
        $this->load->library('virtuoso_query');
        //Carrega a classe para gerar consultas sparql
        $this->load->library('sparql');

        //Montando a consulta SPARQL

        //Defini os prefixos que serão usados
        $this->sparql->prefix("foaf", "http://xmlns.com/foaf/0.1/");
        $this->sparql->prefix("des", get_schema());
        
        $fields =  array(
              'foaf:name'=>'nome',
              'foaf:nick'=>'apelido',
              'foaf:birthday'=>'data_nascimento',
              'foaf:gender'=>'sexo',
              'foaf:img'=>'imagem',
              'foaf:age'=>'idade',
              'des:cityDes'=>'cidade',
              'des:stateDes'=>'estado',
              'dbpprop:height'=>'altura',
              'dbpprop:weight'=>'peso',
              'des:skin'=>'pele',
              'dbpprop:hairColor'=>'cor_cabelo',
              'dbpprop:eyeColor'=>'cor_olho',
              'des:moreCharacteristics'=>'mais_caracteristicas',
              'des:disappearanceDate'=>'data_desaparecimento',
              'des:disappearancePlace'=>'local_desaparecimento',
              'des:circumstanceLocation'=>'circunstancia_desaparecimento',
              'des:dateLocation'=>'data_localizacao',
              'des:additionalData'=>'dados_adicionais',
              'des:status'=>'situacao',
              'des:source'=>'fonte'
        );
        
        //Defini os campos quer serão exibidos
        $this->sparql->select("?id");
        $this->sparql->select("?nome");        
        $this->sparql->select("?situacao");
        $this->sparql->select("?sexo");
        //Tripla quer será retornada - Está condição deve ser satisfeita para retornar um resultado
        $this->sparql->new_ptrn("?recurso des:id ?id");
        //Condições opcionais
        foreach($fields as $key => $value){            
            $this->sparql->optional($this->sparql->new_ptrn("?recurso $key ?$value"));
        }
        
        if($texto != ''){
            $this->sparql->new_ptrn('FILTER ( ' . $query . ' ) ');
            //$this->sparql->new_ptrn('FILTER regex(?nome, "a", "i")');
        }
        
        
        
        //Ordena por nome
        $this->sparql->order("?nome");
        //processa a consulta
        $query = $this->sparql->query();

        //Carregando os dados para consulta no virtuoso
        //$this->virtuoso_query->load_sparql_http('http://desaparecidos.ice.ufjf.br:8890/sparql/');
        $this->virtuoso_query->load_graph(get_graph());
        $this->virtuoso_query->load_query_sparql($query);
        $this->virtuoso_query->load_format('application/json');
        //Executa a query SPARQL
        $this->virtuoso_query->execute();
        
        //Retorna o resultado no formato especificado
        //$obj_json = $this->virtuoso_query->get_result();

        //Retorna como um objeto mais simples
        $desaparecidos = $this->virtuoso_query->convert_json_to_simple_object();
        $result->size = sizeof($desaparecidos);
        
        $result->status = 'Nenhuma informacao encontrada';
        $result->link = '';
        $maior = 0;
        foreach($desaparecidos as $value){
            $total = 0;
            $segmento_nome = explode(' ', $value->nome);
            foreach($segmento_nome as $seg){
                if(in_array($seg, $nomes)) $total++;
            }
            
            if($total > $maior){
                $result->status = 'Situação: ' . $value->situacao;
                $result->link = 'http://desaparecidos.ice.ufjf.br/index.php/desaparecido/html/' . $value->id;
                $maior = $total;
            }
        }
        echo  json_encode($result);
    }
    
    public function total_cadastros(){
        $this->load->library('virtuoso_query');
        //Carrega a classe para gerar consultas sparql
        $this->load->library('sparql');

        //Montando a consulta SPARQL

        //Defini os prefixos que serão usados
        $this->sparql->prefix("foaf", "http://xmlns.com/foaf/0.1/");
        $this->sparql->prefix("des", get_schema());
        //Defini os campos quer serão exibidos
        $this->sparql->select("?id");
        $this->sparql->select("?nome");        
        $this->sparql->select("?situacao");
        $this->sparql->select("?sexo");
        //Tripla quer será retornada - Está condição deve ser satisfeita para retornar um resultado
        $this->sparql->new_ptrn("?recurso des:id ?id");
        //Condições opcionais
        $this->sparql->optional($this->sparql->new_ptrn("?recurso foaf:name ?nome"));
        $this->sparql->optional($this->sparql->new_ptrn("?recurso des:status ?situacao"));
        $this->sparql->optional($this->sparql->new_ptrn("?recurso foaf:gender ?sexo"));
        //Ordena por nome
        $query = $this->sparql->query();

        //Carregando os dados para consulta no virtuoso
        //$this->virtuoso_query->load_sparql_http('http://desaparecidos.ice.ufjf.br:8890/sparql/');
        $this->virtuoso_query->load_graph(get_graph());
        $this->virtuoso_query->load_query_sparql($query);
        $this->virtuoso_query->load_format('application/json');
        //Executa a query SPARQL
        $this->virtuoso_query->execute();

        //Retorna o resultado no formato especificado
        //$obj_json = $this->virtuoso_query->get_result();

        //Retorna como um objeto mais simples
        $desaparecidos = $this->virtuoso_query->convert_json_to_simple_object();
        return sizeof($desaparecidos);
    }
}

/* End of file buscar.php */
/* Location: ./application/controllers/buscar.php */


