'use strict';
module.exports = function(app) {
  var controller = require('../controllers/controller');

  app.route('/r')
    .get(controller.list_all)
    .post(controller.create);

  app.route('/r/:id')
    .get(controller.get_by_id)
    .put(controller.update_by_id)
    .delete(controller.delete_by_id);

  app.route('/s')
    .post(controller.search);
};
