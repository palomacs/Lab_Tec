$(document).ready(function(){

    jsonToSend = {
        'action' : 'CHECK_TAKEN'
    };

    $.ajax({
        type:'POST',
        url: 'data/applicationLayer.php',
        data:jsonToSend,
        dataType:'json',
        contentType:'application/x-www-form-urlencoded',
        success : function(jsonResponse){
            console.log(jsonResponse);
        },
        error : function(errorMsg){
            console.log(errorMsg);

        }
    });

    jsonToSend = {
        'action' : 'GET_TAKEN'
    };

    $.ajax({
        type:'POST',
        url: 'data/applicationLayer.php',
        data:jsonToSend,
        dataType:'json',
        contentType:'application/x-www-form-urlencoded',
        success : function(jsonResponse){
            var newHtml = '';

            //console.log(jsonResponse);

            for (var i = 0; i < jsonResponse.length; i++){
                //console.log(i);
                //console.log(jsonResponse[i]);

                newHtml += "<div class='w3-row w3-border' id='i";
                newHtml += jsonResponse[i].request_id+ "'>";
                newHtml += "<div class='w3-third w3-container'><p>Request id: ";
                newHtml += jsonResponse[i].request_id+"</p><p>ID: ";
                newHtml += jsonResponse[i].username+"</p><p>Professor: ";
                newHtml += jsonResponse[i].professor+"</p><p>Request date: ";
                newHtml += jsonResponse[i].start_date+"</p><p>End date: "+jsonResponse[i].end_date;
                newHtml += "</p></div>";
                newHtml += "<div class='w3-third w3-container'>";
                newHtml += "<table class='w3-table w3-striped w3-white' id='components'>";
                newHtml += "<tr class='w3-black'><td width='300px'>Component</td>";
                newHtml += "<td width='50px'>Quantity</td>";
                newHtml += "<td width='150px'>Status</td></tr>";
                
                var reqId = jsonResponse[i].request_id;
                var k = i; 
                
                //console.log("i = " + i);
                //console.log("k = " + k);

                do{
                    newHtml += "<tr><td class='componentList'>";
                    newHtml += jsonResponse[k].material+ "</td><td class='componentList'>";
                    newHtml += jsonResponse[k].quantity+"</td><td class='componentList'>";
                    newHtml += jsonResponse[k].status+"</td></tr>"; 
                    
                    k++;
                    //console.log("k = " + k);
                    
                }while(k < jsonResponse.length && reqId == jsonResponse[k].request_id);

                i = k-1;

                //console.log("i = " +i);

                newHtml += "</table></div>";
                newHtml += "<div class='w3-third w3-container'><input class='w3-center w3-green w3-button' type='button' id='a";
                newHtml += reqId;
                newHtml += "' value='Returned' onclick='manageTaken(this.id)' style='margin-top: 6%;margin-bottom: 3px;'></div></div>";
            }

            //console.log(newHtml);
            $('#requests').append(newHtml);
        },
        error : function(errorMsg){
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
	
});

function manageTaken(id){
    id = id.substr(1);
   
    jsonToSend = {
        "action": "MANAGE_REQUESTS_TAKEN",
        "id":  id
    };
    
    $.ajax({
        type:"POST",
        url: "data/applicationLayer.php",
        data:jsonToSend,
        dataType:"json",
        contentType:"application/x-www-form-urlencoded",
        success : function(jsonResponse){
            //console.log(jsonResponse);
            id = "#i" + id; 
            $(id).remove();
        },
        error : function(errorMsg){
            console.log(errorMsg);
        }
    });

    //Delete div. 
}