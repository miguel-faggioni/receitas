'use strict';

var mongoose = require('mongoose'),
  DB = mongoose.model('DBmodel');

// retorna todas as receitas
exports.list_all = function(req, res) {
  DB.find({}, function(err, task) {
    if (err)
      res.send(err);
    res.json(task);
  });
};

function sanitize(body){
  for(let i = 0; i< body.ingredientes.length;i++){
    body.ingredientes[i].qtde = eval(body.ingredientes[i].qtde // avalia a fração/soma
                                     .replace(/ +/,'+')   // troca 1 ou mais espaços por '+'
                                     .replace(/^\+/,'')   // remove '+' no início
                                     .replace(/\+$/,'')); // remove '+' no fim
  }
}

// cria a receita recebida em req.body
exports.create = function(req, res) {
  sanitize(req.body);
  var new_task = new DB(req.body);
  new_task.save(function(err, task) {
    if (err){
      console.log(err);
      res.send(err);
    }
    res.json(task);
  });
};

// retorna a receita com id = :id
exports.get_by_id = function(req, res) {
  DB.findById(req.params.id, function(err, task) {
    if (err){
      console.log(err);
      res.send(err);
    }
    res.json(task);
  });
};

// atualiza a receita com id = :id
// se a receita nao existe, cria
// retorna a receita atualizada
exports.update_by_id = function(req, res) {
  sanitize(req.body);  
  DB.findOneAndUpdate({_id: req.params.id}, req.body, {new: true}, function(err, task) {
    if (err){
      console.log(err);
      res.json({ message: 'Item com id: '+req.params.id+' não foi encontrado'});
    }
    res.json(task);
  });
};

// remove a receita com id = :id
exports.delete_by_id = function(req, res) {
  DB.remove({
    _id: req.params.id
  }, function(err, task) {
    if (err){
      console.log(err);
      res.send(err);
    }
    res.json({ message: 'Item removido com sucesso (id: '+req.params.id+')'});
  });
};

// recebe { 'string': string,
//          'campo' : string,
//          'regex' : boolean }
// retorna todos os itens que satisfizerem a busca
exports.search = function(req, res) {
  let str = req.body.string;
  let campo = req.body.campo;
  let regex = req.body.regex || false;
  let query = {[campo]:str};
  if(regex){
    query = {[campo]:{$regex:str}};
  }
  var cursor = DB.find(query).cursor(); 
  var r = []
  cursor.on('data', function(doc){
    r.push(doc);
  });
  cursor.on('close', function(){
    res.send(r);
  });
}
