var express = require('express'),
  app = express(),
  port = process.env.PORT || 3000,
  mongoose = require('mongoose'),
  DBmodel = require('./api/models/model'),
  bodyParser = require('body-parser');

let db_name = 'receitas-db';
mongoose.Promise = global.Promise;
mongoose.connect('mongodb://localhost/'+db_name);

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

var routes = require('./api/routes/routes');
routes(app);

app.use(function(req, res) {
  res.status(404).send({url: req.originalUrl + ' not found'})
});

app.listen(port);

console.log('RESTful API server listening on port ' + port);
console.log('mongodb://localhost/'+db_name);
