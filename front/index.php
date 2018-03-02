<head>
  <meta charset="UTF-8">
  <title>Receitas</title>
  <style>
<?php include 'main.css'; ?>
  </style>
</head>

<body>

<form id="search" action="/" method="get">
    <input id="search-btn" value="Buscar" type="submit"/>
    <input id="search-box" name="q" size="100" type="text" placeholder="receita, ingrediente, passo, ..."/>
</form>

<form id="new" action="/edit.php" method="get">
    <input id="search-btn" value="Nova receita" type="submit" class="full"/>
</form>

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

}elseif( isset($_GET['q']) and !empty($_GET['q'])){
  $q = $_GET['q'];
  $url = 'http://localhost:3000/s';

  $all = array();
  $map = array();
  
  foreach(array("nome","passos","ingredientes.oque") as $campo) {
    #$data = array('string' => $q,'campo' => $campo, 'regex' => "false");           # busca exata
    $data = array('string' => ".*".$q.".*",'campo' => $campo, 'regex' => "true");  # busca com regex: %termo%
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/json",
        'method'  => 'POST',
        'content' => json_encode($data,JSON_UNESCAPED_UNICODE)
      ));

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
      print('Erro ao buscar receitas.');
      exit(var_dump($result));
    }

    $res = json_decode($result);

    $all = array_merge($all,$res);

    foreach($res as $r){
      $map = array_merge($map,array($r->_id => $r));
    }
  }
  $all = array_values($map);

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
}

// imprime cada receita em $all
foreach($all as $r){
    print('<div class="tab">');
    print('<input id="'.$r->_id.'" type="checkbox" name="tabs">');


    print('<label for="'.$r->_id.'">'.$r->nome);
    print('<form id="edit-'.$r->_id.'" action="/edit.php" method="get">');
    print('<input name="id" type="hidden" value="'.$r->_id.'">');
    print('<span><input value="(editar)" type="submit"></span>');
    print('</form>');
    
    print('<form id="edit-'.$r->_id.'" action="/dl.php" method="get">');
    print('<input name="id" type="hidden" value="'.$r->_id.'">');
    print('<span><input value="(baixar)" type="submit"></span>');
    print('</form>');
    
    print('<form id="edit-'.$r->_id.'" action="/edit.php" method="get">');
    print('<input name="id" type="hidden" value="'.$r->_id.'">');
    print('<input name="delete" type="hidden" value="true">');
    print('<span><input class="red" value="(apagar)" type="submit"></span>');
    print('</form>');
    print('</label>');
    
    print('<div class="tab-content">');

    print('<ul class="tree">');

    print('<li>Ingredientes');
    print('<ul>');
    foreach($r->ingredientes as $i){
        print('<li>');
        if($i->qtde) print(round($i->qtde,1).' ');
        if($i->unid) print($i->unid.' - ');
        print($i->oque);
        print('</li>');
    }
    print('</ul>');
    print('</li><!-- Ingredientes -->');

    print('<li>Passos');
    print('<ul>');
    foreach($r->passos as $p){
        print_r('<li>'.$p.'</li>');
    }
    print('</ul>');
    print('</li><!-- Passos -->');

    print('</ul><!-- tree -->');

    print('</div><!-- tab-content -->');
    print('</div><!-- tab -->');
}


?>
