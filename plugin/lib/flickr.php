<?php
    #
# criar o URL da API para a chamada
#

$params = array(
	'api_key'	=> 'b70499f1f12d44705f77e44f03115ba8',
	'method'	=> 'flickr.photos.search',
	'photo_id'	=> '251875545',
	'format'	=> 'php_serial',
	'tags'		=> 'festa',
	'per_page'	=> '3'
);

$encoded_params = array();

foreach ($params as $k => $v){

	$encoded_params[] = urlencode($k).'='.urlencode($v);
}

#
# chamar a API e decodificar a resposta
#

$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);

$rsp = file_get_contents($url);

$rsp_obj = unserialize($rsp);
$photoFlickr = unserialize($rsp);
var_dump($photoFlickr['photos']['photo']);

?>
