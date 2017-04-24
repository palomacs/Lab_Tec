var origTotal = [];

$(document).ready(function(){
  jsonToSend = {
    "action" : "LOADCAT"
  };

  $.ajax({
    type:"POST",
    url: "data/applicationLayer.php",
    data:jsonToSend,
    dataType:"json",
    contentType:"application/x-www-form-urlencoded",
      success: function(jsonData){
        newHtml ="";
        for(var i=0;i<jsonData.length;i++){
          var newHtml = "<tr><td class='materialName'>"+jsonData[i].material;
          newHtml+="</td><td><input type='number' class='materialTotal w3-text-black' value='";
          newHtml+=jsonData[i].total+"'/></td><td class='updateQ'>";
          newHtml+=jsonData[i].available+"</td><td>";
          newHtml+=jsonData[i].laboratory_location+"</td><td>";
          newHtml+=jsonData[i].material_type+"</td><td>";

          if (jsonData[i].additional_info == null){
            jsonData[i].additional_info = "";
          }

          newHtml+=jsonData[i].additional_info+"</td></tr>";
          $("#catalogInfo").append(newHtml);

          origTotal[i] = jsonData[i].total;
          //console.log(origTotal[i]);
        }
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

  $("#update").on("click",function(){
    var materialName = document.getElementsByClassName("materialName");
    var materialAvailability = document.getElementsByClassName("updateQ");
    var materialTotal = document.getElementsByClassName("materialTotal");
    
    //console.log(materialAvailability);
    
    var mN = [];
    var mT = [];
    var mA = [];

    for(var i=0;i<materialName.length;i++){
      mN[i]=materialName[i].innerText;
      mT[i]=materialTotal[i].value;

      //console.log("orig = " + origTotal[i]);
      //console.log("now = " + mT[i]);
      mA[i]= parseInt(materialAvailability[i].innerText) + (parseInt(mT[i]) - parseInt(origTotal[i]));
    }

    //console.log(mN);
    //console.log(mT);
    //console.log(mA);

    jsonToSend = {
      "action" : "UPDATE_QTY",
      "compID" : mN,
      "compQty" : mT, 
      "compAv": mA
    };

    $.ajax({
      type:"POST",
      url: "data/applicationLayer.php",
      data:jsonToSend,
      dataType:"json",
      contentType:"application/x-www-form-urlencoded",
        success: function(jsonData){
          //alert("Catalog up to date.");
        
          for(var i=0;i<materialName.length;i++){
            materialAvailability[i].innerHTML = mA[i];
          }
        },
        error: function(errorMsg){
            console.log(errorMsg);
        }
      });

  });

  $("#addC").on("click",function(){
    window.location.replace("addAdmin.html");
  });

});