(new Promise( (resolve,reject) => {
  jQuery.ajax({
      url: 'diagram.bpmn',
      success: function (result) {
          (result.isOk == false) ? reject(result) : resolve(result);
      }
  });

})).then( (xml) =>
{
  var viewer = new BpmnJS();
  console.log(xml);
  viewer.importXML(xml, function(err)
  {
    if (err) {
      console.log('error rendering', err);
    } else {
      console.log('rendered');
      viewer.attachTo('#canvas');
    }
  });

}).catch( err => {

  console.error(err);

});
