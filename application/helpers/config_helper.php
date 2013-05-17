<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('url_virtuoso'))
{
	function url_virtuoso($echoC = false)
	{
            $url = base_url();
            $size = strlen($url);
            $url = substr($url,0, $size-1);

            if($echoC)
                echo $url . ':8890';
            else
                return $url . ':8890';
	}
}


if ( ! function_exists('get_graph'))
{
	function get_graph($echoC = false)
	{
		if($echoC)
                    echo 'http://desaparecidos.ice.ufjf.br:8890/DES#';
                else
                    return 'http://desaparecidos.ice.ufjf.br:8890/DES#';
	}
}

if ( ! function_exists('get_schema'))
{
	function get_schema($echoC = false)
	{
		if($echoC)
                    echo 'http://desaparecidos.ice.ufjf.br/desaparecido/';
                else
                    return 'http://desaparecidos.ice.ufjf.br/desaparecido/';
	}
}




function sparqlQuery($query, $baseURL, $format="application/json")
  {
	$params=array(
		"default-graph" =>  "http://desaparecidos.ice.ufjf.br:8890/DES#",
		"query" =>  $query,
		"debug" =>  "on",
		"timeout" =>  "",
		"format" =>  $format,
		"save" =>  "display",
		"fname" =>  ""
	);

	$querypart="?";
	foreach($params as $name => $value)
  {
		$querypart=$querypart . $name . '=' . urlencode($value) . "&";
	}

	$sparqlURL=$baseURL . $querypart;
	echo $sparqlURL;
	return json_decode(file_get_contents($sparqlURL));
}






// ------------------------------------------------------------------------
/* End of file config_helper.php */
/* Location: ./application/helpers/config_helper.php */