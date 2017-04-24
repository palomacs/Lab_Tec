$(document).ready(function(){
  jsonToSend = {
    "action" : "GET_CT"
  };

  $.ajax({
    type:"POST",
    url: "data/applicationLayer.php",
    data:jsonToSend,
    dataType:"json",
    contentType:"application/x-www-form-urlencoded",
      success: function(jsonData){
          var newHtml = "";

          for (var i = 0; i < jsonData.length; i ++){
              newHtml += '<option value="';
              newHtml += jsonData[i].componentType_id; //comprobar nombre
              newHtml += '">';
              newHtml += jsonData[i].componentType; //comprobar nombre
              newHtml += '</option>';
          }

          $("#componentType").append(newHtml);
      },
      error: function(errorMsg){
          console.log(errorMsg);
      }
  });

  $("#addButton").on("click",function(){
    var $cName = $("#componentName");
    var $cType = $("#componentType");
    var $cAvailability = $("#qtyAvailable");

    if($cName.val() != "" && $cAvailability.val() != ""){
      var jsonToSend = {
        "action" : "ADDCOMP",
        "cName" : $cName.val(),
        "cType" : $cType.val(),
        "cAvailability" : $cAvailability.val()
      };

      $.ajax({
        type: "POST",
        url: "data/applicationLayer.php",
        data: jsonToSend,
        dataType : "json",
        contentType: "application/x-www-form-urlencoded",
        success : function(jsonResponse){
          alert("Component added successfully");
          window.location.replace("homeAdmin.html");
        },
        error: function(errorMsg){
          console.log(errorMsg.statusText);
        }
      });
    }
  });
}