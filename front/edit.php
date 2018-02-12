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

<?php

if( ( isset($_POST['r_ingredientes']) and !empty($_POST['r_ingredientes']) )
 or (       isset($_POST['r_passos']) and !empty($_POST['r_passos'])       ) ){
  $is = $_POST['r_ingredientes'];
  $ps = $_POST['r_passos'];
  $id = $_POST['r_id'];

  $nome = trim($_POST['r_nome']);

  $ingredientes = array();
  foreach(explode('@',str_replace("\n",'@',$is)) as $i){
    $i = trim($i);
    if(!$i){ continue; }
    $tmp = explode(',',$i);
    $ingredientes[] = array('qtde' => $tmp[0], 'unid' => $tmp[1], 'oque' => implode(',',array_slice($tmp,2)));
  }

  $passos = array();
  foreach(explode('@',str_replace("\n",'@',$ps)) as $p){
    $p = trim($p);
    if(!$p){ continue; }
    $passos[] = $p;
  }

  if( isset($_POST['new-item']) and !empty($_POST['new-item']) and !!$_POST['new-item'] ){
    $url = 'http://localhost:3000/r';
    $data = array('nome' => $nome, 'passos' => $passos, 'ingredientes' => $ingredientes);

    $options = array(
      'http' => array(
        'header'  => "Content-type: application/json",
        'method'  => 'POST',
        'content' => json_encode($data,JSON_UNESCAPED_UNICODE)
      ));
  }else{
    $url = 'http://localhost:3000/r/'.$id;
    $data = array('nome' => $nome, 'passos' => $passos, 'ingredientes' => $ingredientes);

    $options = array(
      'http' => array(
        'header'  => "Content-type: application/json",
        'method'  => 'PUT',
        'content' => json_encode($data,JSON_UNESCAPED_UNICODE)
      ));
  }

  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) {
    print('Erro ao buscar receitas.');
    exit(var_dump($result));
  }

  $r = json_decode($result);

  header('Location: /?id='.$r->_id);
}elseif( isset($_GET['delete']) and !empty($_GET['delete']) and !!$_GET['delete'] ){
  $id = $_GET['id'];
  $url = 'http://localhost:3000/r/'.$id;
  $options = array(
    'http' => array(
        'method'  => 'DELETE'
    ));
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) {
    print('Erro ao buscar receita (id: '.$id.').');
    exit(var_dump($result)); 
  }

  $r = json_decode($result);
  header('Location: /');
}elseif( isset($_GET['id']) and !empty($_GET['id'])){
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

  $r = json_decode($result);
}else{
  $r = (object)array( '_id' => 'nova receita', 'passos' => array(), 'ingredientes' => array(), 'nome' => "" );
  $new = true;
}

  print('<form id="search" action="/edit.php" method="post">');
  print('<input value="Salvar" type="submit" class="full"/>');
  print('<input name="new-item" value="'.$new.'" type="hidden"/>');

  print('<div class="tab">');
  print('<input id="'.$r->_id.'" type="checkbox" checked name="tabs">');
  print('<input value="'.$r->_id.'" type="hidden" name="r_id">');
  print('<label for="'.$r->_id.'">'.$r->_id.'</label>');
  print('<div class="tab-content">');

  print('<ul class="tree">');

  print('<li>Nome');
  print('<ul>');
  print('<li><input name="r_nome" value="'.$r->nome.'" type="text" placeholder="nome da receita"/></li>');
  print('</ul>');
  print('</li><!-- Nome -->');

  print('<li>Ingredientes');
  print('<ul>');
  print('<li><textarea name="r_ingredientes" placeholder="quantidade,medida,nome" cols="40" rows="5">');
  foreach($r->ingredientes as $i){
    print($i->qtde.','.$i->unid.','.$i->oque."\n");
  }
  print('</textarea></li>');
  print('</ul>');
  print('</li><!-- Ingredientes -->');

  print('<li>Passos');
  print('<ul>');
  print('<li><textarea name="r_passos" placeholder="um passo por linha" cols="40" rows="5">');
  foreach($r->passos as $p){
    print($p."\n");
  }
  print('</textarea></li>');
  print('</ul>');
  print('</li><!-- Passos -->');

  print('</ul><!-- tree -->');

  print('</div><!-- tab-content -->');
  print('</div><!-- tab -->');

?>

</form>