<html>
 <head>
  <meta  charset=utf-8 /> 
  <title> Raspagem do site www.desaparecidos.mg.gov </title>
 </head>
 <body>
	<?php
        
            include("../simple_html_dom/simple_html_dom.php");
            include("atualizacaoNicolasNew.php");
        
            function Tira2pts($texto){
                $aux = explode(":", $texto);
                return $aux[1];
            }

                                                            
            function certifica($valor_de_procura, $vet){
                $tam = count($vet);
                $size = strlen($valor_de_procura);
                for($i=0;$i<$tam;$i++){
                    if(similar_text($vet[$i],$valor_de_procura ) == $size ){
                        //echo "indice: ".$i."<br>";
                        $dado = explode(":", $vet[$i]);
                        //echo $dado[1];
                        return ucwords(strtolower($dado[1]));
                    }
                }
                return null;
            }
            
            // url do album de fotos, onde se encontram os desaparecidos
            $urlBase = "http://www.desaparecidos.mg.gov.br/album.asp?pg=";

            // url auxiliar utilizada para pegar a pagina do desaparecido
            $urlAux = "http://www.desaparecidos.mg.gov.br/";

            //o site possui 174 paginas de desaparecidos de 1 a 174
            for($i=1; $i<2; $i++){ //$i=1; $i<175; $i++
                // concateno para pegar o indice da página
                //$html = file_get_html("http://www.desaparecidos.mg.gov.br/album.asp?pg=2");
                //$html = new simple_html_dom();
                //$html->load_file("http://www.desaparecidos.mg.gov.br/album.asp?pg=1");
                //$next = $html->find('a[class="txtbasealbum"]');
                //echo $next[0]->outertext;
                //$html2 = file_get_html($urlAux.$next[0]->href);
                $html = new simple_html_dom();
                $html->load_file("http://www.desaparecidos.mg.gov.br/album.asp?pg=2");
                echo $html->plaintext;
                /*
                foreach( $html->find('a[class="txtalbum1"]') as $a){
                    //echo "ola";
                    echo $a->plaintext."<br>";
                }*/
                
            }

     ?>
 </body>
</html>
