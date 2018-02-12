<?php

if( isset($_GET['id']) and !empty($_GET['id'])){
  $id = $_GET['id'];
  $url = 'http://localhost:3000/r/'.$id;
  $options = array(
    'http' => array(
        'method'  => 'GET'
    ));
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) {
    print('Erro ao buscar receita (id: '.$id.').');
    exit(var_dump($result)); 
  }
  $all = array(json_decode($result));
  header('Content-disposition: attachment; filename='.$all[0]->nome.'.json');
}else{ # sem query/busca
  $url = 'http://localhost:3000/r';
  $options = array(
    'http' => array(
        'method'  => 'GET'
    ));
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) {
    print('Erro ao buscar receitas.');
    exit(var_dump($result)); 
  }

  $all = json_decode($result);
  header('Content-disposition: attachment; filename=tudo.json');
}

header('Content-type: application/json');
print_r(json_encode($all));
?>
