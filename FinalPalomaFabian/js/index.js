$(document).ready(function(){
  //Validate session
  var jsonToSend = {
    "action" : "LOGIN_SESSION"
};

$.ajax({
    type:"POST",
    url: "data/applicationLayer.php",
    data:jsonToSend,
    dataType:"json",
    contentType:"application/x-www-form-urlencoded",
    success : function(jsonResponse){
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

                        if (I == "Attendant"){
                            window.location.replace("homeAtt.html");
                        }
                        /*else if (I == "Administrator"){
                            window.location.replace("homeAdmin.html");
                        }*/
                        else{
                            window.location.replace("home.html");
                        }
                    },
                    error: function(errorMsg){
                        console.log(errorMsg);
                    }
                });
    },
    error : function(errorMsg){
      console.log(errorMsg);
  }
});

var cookie_name = 'remember_user';
var res = retrieve_cookie(cookie_name);

if(res) {
      //alert('Cookie with name "' + cookie_name + '" value is ' + '"' + res + '"');
      $("#userNameLogin").val(res);
      $('#remember').prop('checked', true); 
  } 
  else {
    //alert('Cookie with name "' + cookie_name + '" does not exist...');
    $("#userNameLogin").val("");
    $('#remember').prop('checked', false);
}

$("#usernameNew").keyup(function(){
    var un =$(this).val();
    var $degree = $("#degreeProgram");

    if (un.indexOf("L0") >= 0){
      $degree.hide();
      var userType = 2; 
  }
  else{
      $degree.show();
  }
});

  //Load degrees
  jsonToSend = {
    "action" : "GET_DEGREES"
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
          newHtml += jsonData[i].degree_program_id;
          newHtml += '">';
          newHtml += jsonData[i].degree_program;
          newHtml += '</option>';
      }

      $("#degreeProgram").append(newHtml);
  },
  error: function(errorMsg){
      console.log(errorMsg);
  }
});

$("#createButton").on("click", function(){
    var $fName = $("#fName");
    var $lName = $("#lName");
    var $email = $("#emailNew");
    var $userName = $("#usernameNew");
    var $pswd = $("#passwordNew");
    var $degree = $("#degreeProgram");
    var userType = 0;
    var validEmail = 0; 

    if ($userName.val().indexOf("A0") >= 0){
      userType = 1;
  }
  else if ($userName.val().indexOf("L0") >= 0){
      userType = 2; 
  }
  else{
      var un = $("#usernameNew")[0];
      un.setCustomValidity("ID must start with A0 or L0");
  }

  if ($email.val().indexOf("@itesm.mx") >= 0){
      validEmail = 1; 
  }
  else{
      var e = $("#emailNew")[0];
      e.setCustomValidity("E-mail must be @itesm.mx");
  }

  if ($fName.val() != "" &&  $lName.val() != "" &&
    validEmail > 0 && $userName.val() != "" && 
    $pswd.val() != "" && userType > 0){
      var jsonToSend = {
          "fName" : $fName.val(),
          "lName" : $lName.val(),
          "email" : $email.val(),
          "username" : $userName.val(),
          "password" : $pswd.val(),
          "degree" : $degree.val(),
          "userType" : userType, 
          "action" : "REGISTRATION"
      };

      $.ajax({
        type: "POST",
        url: "data/applicationLayer.php",
        data: jsonToSend,
        dataType : "json",
        contentType: "application/x-www-form-urlencoded",
        success : function(jsonResponse){
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

                        if (I == "Attendant"){
                            window.location.replace("homeAtt.html");
                        }
                        else if (I == "Administrator"){
                            window.location.replace("homeAdmin.html");
                        }
                        else{
                            window.location.replace("home.html");
                        }
                    },
                    error: function(errorMsg){
                        console.log(errorMsg);
                    }
                });
        },
        error: function(errorMsg){
          console.log(errorMsg.statusText);
      }
  });
  }
});

$("#loginButton").on("click", function(){
    var $userName = $("#userNameLogin");
    var $pswd = $("#passwordLogin");
    var $remember = $("input[name='remember']:checked");

    var jsonToSend = {
      "username": $userName.val(),
      "password": $pswd.val(),
      "remember": $remember.val(),
      "action" : "LOGIN"
  };

  if ($userName.val() !== "" && $pswd.val() !== ""){
    $.ajax({
        type:"POST",
        url: "data/applicationLayer.php",
        data:jsonToSend,
        dataType:"json",
        contentType:"application/x-www-form-urlencoded",
        success : function(jsonResponse){
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

                        if (I == "Attendant"){
                            window.location.replace("homeAtt.html");
                        }
                        else if (I == "Administrator"){
                            window.location.replace("homeAdmin.html");
                        }
                        else{
                            window.location.replace("home.html");
                        }
                    },
                    error: function(errorMsg){
                        console.log(errorMsg);
                    }
                });
        },
        error : function(errorMsg){
            $("#logMissing").text("Username or password incorrect");
        }
    });
}
});

$('.form').find('input, textarea').on('keyup blur focus', function (e) {

    var $this = $(this),
    label = $this.prev('label');

    if (e.type === 'keyup') {
       if ($this.val() === '') {
        label.removeClass('active highlight');
    } else {
        label.addClass('active highlight');
    }
} 
else if (e.type === 'blur') {
 if( $this.val() === '' ) {
    label.removeClass('active highlight'); 
} else {
    label.removeClass('highlight');   
}   
} 
else if (e.type === 'focus') {

    if( $this.val() === '' ) {
        label.removeClass('highlight'); 
    } 
    else if( $this.val() !== '' ) {
        label.addClass('highlight');
    }
}
});

$('.tab a').on('click', function (e) {
    e.preventDefault();
    
    $(this).parent().addClass('active');
    $(this).parent().siblings().removeClass('active');
    
    target = $(this).attr('href');

    $('.tab-content > div').not(target).hide();
    
    $(target).fadeIn(600);
});
});

function retrieve_cookie(name) {
  var cookie_value = "",
  current_cookie = "",
  name_expr = name + "=",
  all_cookies = document.cookie.split(';'),
  n = all_cookies.length;

  for(var i = 0; i < n; i++) {
    current_cookie = all_cookies[i].trim();
    if(current_cookie.indexOf(name_expr) === 0) {
      cookie_value = current_cookie.substring(name_expr.length, current_cookie.length);
      break;
  }
}
return cookie_value;
}