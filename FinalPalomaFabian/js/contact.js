$(document).ready(function(){
    var jsonToSend = {
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

});

