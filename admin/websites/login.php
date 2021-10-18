<!DOCTYPE html>    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<html>  

  <head>    
    <title>Video-Styl - Logowanie</title>     
  </head> 

  <body>    
    <h2>Becz00nia</h2><br>    
    <div class="login">   
      <h1>Logowanie <br >Panel Główny</h1>
      <label><b>Nazwa Użytkownika</b></label><br>  
      <input id="username" type="text" name="username" placeholder="Tu wpisz nazwę użytkownika"><br><br> 
      <label><b>Hasło</b></label><br>  
      <div class="button">
        <input type="Password" name="password" id="password" placeholder="Tu wpisz swoje hasło"><br><br>  <br> 
      </div>
      <button type="button" name="log" id="log" onclick="loggin()">Zaloguj Się</button><br><br>
      <input type="checkbox" id="check"><span>Zapamiętaj Mnie</span><br><br>  
    </div>   
    <div class="error">
      <h3 id="h3">Błędna nazwa użytkownika lub hasło<br>Spróbuj ponownie</h3>
    </div> 
  </body> 

</html>  

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins');
    @import url('https://fonts.googleapis.com/css2?family=Pattaya');

    body{  
      margin: 0;  
      padding: 0;  
      background-color: black;  
      font-family: 'Poppins', sans-serif; 
    }  

    input{
        outline: none;
    }

    input:hover, input:focus{
        background-color: rgb(211, 211, 211);
    }

    .login{  
      width: 382px;  
      overflow: hidden;  
      margin: auto;  
      padding: 80px;  
      padding-top: 0px;
      padding-bottom: 0px;
      background: #1d1d1dba;  
      border-radius: 15px ;  
      text-align: center;
    } 

    h2{  
      font-family: 'Pattaya', sans-serif;
      text-align: center;  
      color: white;  
      padding: 10px; 
      font-size: 60px;
      display: none;
    }  

    h1{
      color:white;
      padding-bottom: 10px;
    }

    label{  
      color: white;  
      font-size: 17px;  
      padding-right: 0px;
    }  

    #username{  
      width: 300px;  
      height: 30px;  
      border: none;  
      border-radius: 3px;  
      padding-left: 8px;  
    }  

    #password{  
      width: 300px;  
      height: 30px;  
      border: none;  
      border-radius: 3px;  
      padding-left: 8px;     
    }  

    #log{  
      width: 300px;  
      height: 30px;  
      padding: auto;
      border: none;  
      border-radius: 17px;  
      padding-left: 7px;  
      font-size: 15px;
      color: black;  
      background-color: white;
    } 

    span{  
      color: white;  
      font-size: 17px;  
    } 

    a{  
      float: right;  
      background-color: grey;  
    } 

    .error{ 
      width: 300px;  
      overflow: hidden;  
      margin: auto;  
      padding: 80px;  
      padding-top: 0px;
      padding-bottom: 0px;
      background: white;  
      border-radius: 15px ;  
      text-align: center;
      color:black;
      display: none;
    }

    h3{
      font-size: 15px;
      color: black;
    }


</style>

<script>
    let proba = 0;

    $("h2").fadeIn(3000);

    function loggin(){
      $(".error").slideUp("slow"); 

      var username = document.getElementById("username").value;
      var password = document.getElementById("password").value;

      console.log(username+" "+password)

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
          console.log(this.responseText);

          document.getElementById("username").value = "";
          document.getElementById("password").value = "";

          var jsonObject = JSON.parse(this.responseText)

          if(jsonObject.loged == 1){
            console.log("No zalogowales sie");
            location = self.location;
            return;
          }

          else{
            proba++;

            if(proba == 3){
              var element = document.getElementById("h3");
              setTimeout(() => { element.innerHTML = "Błędna nazwa użytkownika lub hasło<br>Jeśli problem nadal występuje, skontakuj się z nami."; console.log("Zmiana")}, 500);
            }

            console.log("No nie zalogowales sie");
            log_error();

            $(".error").slideDown("slow");

            return;
          }
        }
      };

      xhttp.open("POST", "/api/account.php?m=1", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("username="+username+"&password="+password);
    }

    function log_error(){
        
    }

</script>