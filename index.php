<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rapid Miner Process Parser</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="extensions/jquery-3.2.0.min.js"></script>
    <link rel="stylesheet" href="extensions/font-awesome-4.7.0/css/font-awesome.css">

    <!--LOCAL SPEICHERN!!!-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
    <div id = "leftContent">
        <div id = "welcomeTextWrapper">
            <div id = "welcomeText">
                <h1>Hello, Please Upload your </br>
                <span class="highlight">Rapid Miner</span> File!</h1>
            </div>
            <div id="uploadForm">
                <form action="" enctype="multipart/form-data" method="post">

                    <div>
                        <input id='upload' name="upload[]" type="file" multiple="multiple" />
                    </div>

                    <p><input type="submit" name="submit" value="Submit"></p>

                </form>
            </div>

            <!--<button id="buttonUpload">upload you file here</button>-->
        </div>
    </div>
    <?php
        function alert($message){
            echo '<script type="text/javascript" language="Javascript">';
            echo 'alert("'.$message.'");';
            echo '</script>';
        }

        require_once ("php/InParser.php");

        if(isset($_POST['submit'])){
            $count = 0;
            $uploadedFiles = count($_FILES['upload']['name']);

            if($uploadedFiles > 0){
                for($i=0; $i<count($_FILES['upload']['name']); $i++) {
                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    if($tmpFilePath != ""){

                        $shortname = $_FILES['upload']['name'][$i];
                        $filePath = "upload/" .$_FILES['upload']['name'][$i];

                        if(move_uploaded_file($tmpFilePath, $filePath)) {

                            $files[] = $shortname;
                            $count += 1;
                            $parser = new inParser($filePath);
                            $parser->parseInDatabase();//Anpassen wenn Abstrakte Klasse fertig!
                        }
                    }
                }
            }
            if($count == $uploadedFiles){
                alert("Success: Uploaded and stored ".$count." File(s) in Database!");
            }
        }
    ?>
    <div id =  "rightContent">
        <div id = "rightContentTop" onclick="expandDiv(this)" >
            <div class = "rightContentWrapper" >
                <div class = "icon" ><i class="fa fa-filter" aria-hidden="true"></i></div>
                <div class = "rightContentHeading"><span class="highlight">Select </span> Data</div>
            </div>
            <div class = "box" onclick=" event.stopImmediatePropagation();">
                <h2>Filter Box</h2>
                </br>
               <form action = "yourPHPFile">
                   <label for="startDate ">Start Date </label>
                   <input type="date" id = "startDate "> </br>
                   <label for="endDate">End Date </label>
                   <input type="date" id="endDate"></br></br>
                   <label for="range">Select the amount of data you want to export</label>
                   <input type="range" id="range"></br></br>
                   <label for="checkbox">Export all Attributes?</label>
                   <input type="checkbox" id="checkbox"></br></br>
                   <button>Select all</button>
               </form>
            </div>
        </div>
        <div id = "rightContentMiddle" onclick="toDo(this)">
            <div class = "rightContentWrapper">
                <div class = "icon"><i class="fa fa-database" aria-hidden="true"></i></div>
                <div class = "rightContentHeading">Data <span class="highlight">Overview</span></div>
            </div>
            <div class = "box">
                <h3>Process instances in the database</h3>
                <span class="automaticNumberCounter" action="yourPhpScript" value='19000'>19000</span>
                <h3>Activities in the database</h3>
                <span class="automaticNumberCounter" value='90000'>90000</span>
            </div>
        </div>
        <div id = "rightContentBottom" onclick="expandDiv(this)">
            <div class = "rightContentWrapper">
                <div class = "icon"><i class="fa fa-lightbulb-o" aria-hidden="true"></i></div>
                <div class = "rightContentHeading"><span class="highlight">About</span> the Project</div>
            </div>
            <div class = "box">
Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
            </div>
        </div>
    </div>
</body>
<script>
    function toDo(obj){
        expandDiv(obj);
        countNumbers(obj);
    }

    function expandDiv(object){
    $(object).find(".box").slideToggle('slow', function(){
        var bodyHeight = $('body').height();
        var rightContent = $(object);
            var heading = rightContent.find(".rightContentHeading");
            var rightContentTopHeight = Math.round((rightContent.height()/bodyHeight)*100);
            var newHeight = 0.6; // in percentage
            if(rightContentTopHeight/100 < newHeight){
                //heading.css("display", "none");
                rightContent.height(newHeight*bodyHeight);
                rightContent.siblings().height(Math.round(((1-newHeight)/2)*100)+"%");
                rightContent.siblings().attr("onclick","");
            }else if(rightContentTopHeight/100 == newHeight){
                //heading.css("display", "block");
                rightContent.height(33.3+"%"); // Height of rightContentTop
                rightContent.siblings().height(33.3+"%");
                rightContent.siblings().attr("onclick","expandDiv(this)");
            }
        });

    }

    function countNumbers(object) {
    var opened = false;
    var boxHeight = $(object).height();
        var bodyHeight = $('body').height();
        var actualHeight = Math.round((boxHeight/bodyHeight)*100);
        console.log(actualHeight);
        var startingHeight = 33;
        if(actualHeight <= startingHeight){
            var opened = true;
        }
        if(opened) {
            $('.automaticNumberCounter').each(function () {
                var $this = $(this);
                //Value anstelle von text(), da der Counter den Text verändert
                jQuery({Counter: 0}).animate({Counter: $this.attr('value')}, {
                    duration: 2000,
                    easing: 'swing',
                    step: function (now) {
                        $this.text(Math.ceil(now));
                    }
                });
            });
        }
    }
</script>
</html>