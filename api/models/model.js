'use strict';
var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var DBingrediente = new Schema({
  qtde: {
    type: Number
  },
  unid: {
    type: String
  },
  oque: {
    type: String,
    required: 'nome do ingrediente'
  }
});
var DBSchema = new Schema({
  nome: {
    type: String,
    required: 'nome da receita'
  },
  passos: {
    type: [{
      type: String
    }],
    required: 'passos da receita'
  },
  ingredientes: {
    type: [DBingrediente]
  }
});

module.exports = mongoose.model('DBmodel', DBSchema);
