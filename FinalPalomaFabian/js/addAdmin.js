$(document).ready(function(){

  var jsonToSend = {
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
              newHtml += '<option class="w3-text-black" value="';
              newHtml += jsonData[i].material_type_id; //comprobar nombre
              newHtml += '">';
              newHtml += jsonData[i].material_type; //comprobar nombre
              newHtml += '</option>';
          }

          $("#componentType").append(newHtml);
      },
      error: function(errorMsg){
          console.log(errorMsg);
      }
  });

  $("#logout").on("click", function(){
        jsonToSend = {
            "action": "LOGOUT"
        };

        $.ajax({
            type:"POST",
            url: "data/applicationLayer.php",
            data:jsonToSend,
            dataType:"json",
            contentType:"application/x-www-form-urlencoded",
            success : function(jsonResponse){
                window.location = "index.html";
            },
            error : function(errorMsg){
                console.log(errorMsg);

            }
        });
    });

  $("#addButton").on("click",function(){
    
    var $cName = $("#componentName");
    var $cType = $("#componentType");
    var $cAvailability = $("#qtyAvailable");
    var $additionalInfo = $("#additionalInfo");

    if($cName.val() !== "" && $cAvailability.val() !== ""){
      jsonToSend = {
        "action" : "ADDCOMP",
        "cName" : $cName.val(),
        "cType" : $cType.val(),
        "cAvailability" : $cAvailability.val(),
        "adInfo" : $additionalInfo.val()
      };

      //console.log(jsonToSend);

      $.ajax({
        type:"POST",
        url: "data/applicationLayer.php",
        data:jsonToSend,
        dataType:"json",
        contentType:"application/x-www-form-urlencoded",
        success : function(jsonResponse){
          //console.log("jsonResponse");
          //alert("Component added successfully");
        },
        error: function(errorMsg){
          console.log(errorMsg);
        }
      });
    }
  });
});