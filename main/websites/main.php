<!DOCTYPE html>  

<?php
    if((isset($_SESSION['loged']) && ($_SESSION['loged']) == true)){
        // Do nothing
    }

    else{
        header("Location: ../index.php");
    }
?>

<html>
    <div class="up">
        <div class="logout">
            <a href='api/account.php?m=2'>Wyloguj się</a>
        </div>

        <div class="up_text">
            <h1>XDD</h1>
        </div>

        <div class="logout">
            <a href='api/account.php?m=2'>Wyloguj się</a>
        </div>
    </div>

    <div class="container">
        <div class="box">Div 1</div>
        <div class="box">Div 2</div>
        <div class="box">Div 3</div>
    </div>
</html>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins');

    *{
        margin: 0px;
        font-family: 'Poppins', sans-serif; 
    }


    .up{
        background-color: black;
        color: white;
        text-align: center;
        border-radius: 0px 0px 10px 10px;
        height: 50px;
    }

    .up_text{
        width: 100%;
        position: absolute;
    }

    .logout{
        float: right;
        margin-right: 20px;
        margin-top: 10px;
    }

    .container{
        display:flex;
        justify-content:space-evenly;
        align-items: baseline;
        max-width: 1600px;
        margin: 10px auto 0px auto;
    }


</style>
<script src="https://apis.google.com/js/api.js"></script>
<script>
    GetVideosIDs();

    function GetVideosIDs(){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);

            var jsonObject = JSON.parse(this.responseText);

            let NumOfVideos = jsonObject.UserVideos.VideosCount;

            for(let i = 0; i < NumOfVideos; i++){
                console.log(i);
                CreateNewText("div1", jsonObject.UserVideos.VideosID[i]);
            }
        }
        };
        xmlhttp.open("GET", "https://incognitoo.pl/api/drive.php?m=1", true);
        xmlhttp.send();
    }

    function GetVideoInfo(){
        var videoId = document.getElementById("videoId").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);

            var jsonObject = JSON.parse(this.responseText);

            let name = jsonObject.originalFilename;

            if(name == undefined){
                CreateNewText("div1", "Nie znaleziono takiego filmu");
            }
            else
            CreateNewText("div1", name);
        }
        };
        xmlhttp.open("GET", "https://incognitoo.pl/api/drive.php?m=2&videoKey="+videoId, true);
        xmlhttp.send();        
    }

    function CreateNewText(object, text){
        var para = document.createElement("p");
        var node = document.createTextNode(text);
        para.appendChild(node);
        var element = document.getElementById(object);
        element.appendChild(para);
    }


</script>