$(document).ready(function(){

  jsonToSend = {
    "action" : "GETLABS"
  };
  
  $.ajax({
    url: "data/applicationLayer.php",
    type:"POST",
    data:jsonToSend,
    dataType:"json",
    contentType : "application/x-www-form-urlencoded",
    success:  function(jsonData){
      //console.log(jsonData);

      var newHtml = "";

      for (var i = 0; i < jsonData.length; i ++){
        newHtml += '<option value="';
        newHtml += jsonData[i].laboratory_id;
        newHtml += '">';
        newHtml += jsonData[i].laboratory_location;
        newHtml += '</option>';
      }
      $("#laboratory").append(newHtml);
    },
    error: function(errorMsg){
        console.log(errorMsg);
    }
  });

  jsonToSend = {
        "action": "NOTIFICATIONS"
    };

    $.ajax({
        url: "data/applicationLayer.php",
        type:"POST",
        data:jsonToSend,
        dataType:"json",
        contentType : "application/x-www-form-urlencoded",
        success:  function(jsonData){
            //console.log(jsonData);

            $("#notifsNum").text(jsonData.length);

            var newHtml = '';

            for (var k = 0; k < jsonData.length; k++){
                newHtml += "<a class='notList'> Your order #";
                newHtml += jsonData[k].request_id;
                newHtml += " was";

                if(jsonData[k].status == "ON TIME"){
                    newHtml += " accepted </a>";
                }
                else{
                    newHtml += " cancelled </a>";
                }
            }

            $("#notifs").append(newHtml);
        },
        error: function(errorMsg){
            console.log(errorMsg);
        }
    });

    $("#notisBell").on("click", function(){
        jsonToSend = {
            "action": "READ_NOTIFICATIONS"
        };

        $.ajax({
            url: "data/applicationLayer.php",
            type:"POST",
            data:jsonToSend,
            dataType:"json",
            contentType : "application/x-www-form-urlencoded",
            success:  function(jsonData){
                //console.log(jsonData);
                $(".notList").remove();
                $("#notifsNum").text("");

                //window.location.replace("yourOrders.html");
            },
            error: function(errorMsg){
                console.log(errorMsg);
            }
        });
                
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

  $("#reqButton").on("click",function(){
    jsonToSend = {
      "action" : "SEND_REQUEST",
      "materialName" : $("#componentName").val(),
      "materialQuantity" : $("#minQty").val(),
      "laboratory" : $("#laboratory").val(),
      "additionalInfo" : $("#additionalInfo").val()
    };

    $.ajax({
      url: "data/applicationLayer.php",
      type:"POST",
      data:jsonToSend,
      dataType:"json",
      contentType : "application/x-www-form-urlencoded",
      success:  function(jsonData){
        console.log(jsonData);
        	console.log(jsonData);
          window.location.replace("yourOrders.html");
      },
      error: function(errorMsg){
          console.log(errorMsg);
      }
    });
  });
    
});