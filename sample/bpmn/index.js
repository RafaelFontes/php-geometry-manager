var exec  = require("child_process").exec;

var express = require('express')
var app = express()

app.use('/', express.static(__dirname));

app.get('/diagram.bpmn', function (req, res) {
  exec( "php " + __dirname + "/bpmn-renderer.php", (error, stdout, stderr) => {
    res.send(stdout);
  });
})

app.listen(8080)
