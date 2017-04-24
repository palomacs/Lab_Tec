$(document).ready(function(){
    var jsonToSend = {
        "action": "WHO_AM_I"
    };

    $.ajax({
        url: "data/applicationLayer.php",
        type:"POST",
        data:jsonToSend,
        dataType:"json",
        contentType : "application/x-www-form-urlencoded",
        success:  function(jsonData){
            var I = jsonData.me.user_type;

            //console.log(I);

            if (I == "Professor"){
                //console.log(I);
                $('#attendantReqButton').css("visibility", "visible");
            }
            else{
                $('#attendantReqButton').css("visibility", "hidden");
            }
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

    $("#attendantReqButton").on("click", function(){
        var jsonToSend = {
            "action": "BECOME_ATTENDANT"
        };

        $.ajax({
            url: "data/applicationLayer.php",
            type:"POST",
            data:jsonToSend,
            dataType:"json",
            contentType : "application/x-www-form-urlencoded",
            success:  function(jsonData){
                $('#attendantReqButton').css("visibility", "hidden");
                $('#atteReqLabel').css("visibility", "visible");
            },
            error: function(errorMsg){
                console.log(errorMsg);
                $('#attendantReqButton').css("visibility", "hidden");
                $('#atteReqLabel').css("visibility", "visible");
            }
        });
    });

    $("#searchInput").keyup(function(){
        var material =$(this).val();

        jsonToSend = {
            "action": "SEARCH",
            "material": material
        };

        $("#searchList").children("li").remove();

        $.ajax({
            type: "POST",
            url: "data/applicationLayer.php",
            data:jsonToSend,
            contentType : "application/x-www-form-urlencoded",
            beforeSend: function(){
                $("#searchInput").css("background","#FFF url(Images/LoaderIcon.gif) no-repeat 570px");
            },
            success: function(jsonResponse){
                //console.log(jsonResponse);
                var newHtml = "";

                $("#suggesstion-box").show();

                for (var i = 0; i < jsonResponse.length; i ++){
                    newHtml = '<li id="'; 
                    newHtml+= jsonResponse[i].material_id + '_' + jsonResponse[i].available;
                    newHtml+= '" onclick="addCart(this.id)" class="searchResults">';
                    newHtml+= jsonResponse[i].material + '</li>';

                    $("#searchList").prepend(newHtml);
                }
                $("#searchInput").css("background","#FFF");
            },
            error: function(errorMsg){
                console.log(errorMsg);
            },
            complete: function(){
                $("#searchInput").css("background-image","none");
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

    $("#submitCartButton").on("click", function(){
        var myCartN = document.getElementsByClassName("myCart_name");
        var myCartQ = document.getElementsByClassName("myCart_quantity");
        var myCartA = document.getElementsByClassName("myCart_availables");

        var professorName = $("#professorName").val();

        //console.log(professorName);
        
        var material_names = [];
        var material_quantities = [];
        var reqDate = $("#requestDate").val();
        var retDate = $("#returnDate").val();

        for (var k = 0; k < myCartN.length; k++){
        	//console.log(myCartQ[k].value);
        	//console.log(myCartA[k].innerText);

        	if (myCartQ[k].value > myCartA[k].innerText){
        		myCartQ[k].value = myCartA[k].innerText;
        	}

            material_names[k] = myCartN[k].innerText;
            material_quantities[k] = myCartQ[k].value;
        }

        jsonToSend = {
            "action": "BORROW",
            "materials": material_names,
            "quantities": material_quantities,
            "professor": professorName, 
            "requestDate": reqDate, 
            "returnDate": retDate
        };

        //console.log(jsonToSend);

        $.ajax({
            type:"POST",
            url: "data/applicationLayer.php",
            data:jsonToSend,
            dataType:"json",
            contentType : "application/x-www-form-urlencoded",
            success: function(jsonData) {
                //console.log(jsonData);
                window.location.replace('yourOrders.html');
            },
            error: function(errorMsg){
                console.log(errorMsg);
            }
        });

        //console.log(material_names);
        //console.log(material_quantities);
    });

});

function addCart(id){
    $("#cart").css("visibility", "visible");

    var date = new Date();
    var day = ("0" + date.getDate()).slice(-2);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();

    var today = year + "-" + month + "-" + day; 
    var lastDayYear = year + "-12-31"; 

    $("#requestDate").attr("value", today);
    $("#requestDate").attr("min", today);
    $("#returnDate").attr("value", today);
    $("#returnDate").attr("min", today);
    $("#returnDate").attr("max", lastDayYear);

    var mId = "#" + id;
    var mN = $(mId).text();
    var av = id.substring(id.indexOf('_')+1, id.length);
    var myRows = $("#catalogInfo").find("tr");
    
    //console.log(av);
    //console.log(mN);
    //console.log(myRows.length);

    var exists = 0; 

    for (var i = 1; i < myRows.length; i++) {
        var MyIndexValue = $(myRows[i]).find('td:eq(0)').html();
        //console.log(MyIndexValue);
        if (MyIndexValue == mN)
            exists++;
    }


    if (exists === 0){
        var newHtml = '<tr>'; 
        newHtml+= '<td width="150px" class="myCart_name">';
        newHtml+= mN + '</td>';
        newHtml+= '<td width="25px">';
        newHtml+= '<form><input type="number" class="myCart_quantity" value="1" min="0" max="';
        newHtml+= av + '"';
        newHtml+= '></form></td>';
        newHtml+= '<td width="25px" class="myCart_availables">';
        newHtml+= av + '</td>';
        newHtml+= '</tr>';

        $("#catalogInfo").append(newHtml);
    }
}