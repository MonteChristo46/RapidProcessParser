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
                        <!--<input class="fileUpload" id='upload' name="upload[]" type="file" multiple="multiple" />-->
                        <label class="fileContainer" for="file" id="label"><i style = "margin-right: 5px;"class="fa fa-upload" aria-hidden="true"></i>Choose files</label><br/><br/>
                        <input type="file" name="upload[]" id="file" class="inputfile" multiple="multiple"/>
                        <button id="submitLabel" type="submit" value="Submit" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include("php/interfaceLogic.php"); ?>
    <div id =  "rightContent">
        <div id = "rightContentTop" onclick="expandDiv(this)">
            <div class = "rightContentWrapper" >
                <div class = "icon" ><i class="fa fa-filter" aria-hidden="true"></i></div>
                <div class = "rightContentHeading"><span class="highlight">Select </span> Data</div>
            </div>
            <div class = "box" onclick=" event.stopImmediatePropagation();">
                <h2>Filter Box</h2>
                </br>
               <form action = "yourPHPFile" id="filterForm">
                   <label for="startDate ">Start Date </label><br/>
                   <input type="date" id = "startDate "> </br><br/>
                   <label for="endDate">End Date </label><br/>
                   <input type="date" id="endDate"></br></br>
                   <label for="range">Select the amount of data you want to export</label><br/><br/>
                   <input type="range" name="ageInputName" id="range" min="1" max="<?= $instances ?>" oninput="ageOutputId.value = range.value">
                   <output name="ageOutputName" id="ageOutputId">50</output> (Maximum of Instances in DB)</br></br>
                   <label for="checkbox">Export all Attributes?</label><br/><br/>
                   <input type="checkbox" id="checkbox"> yes</br></br>
                   <label for="checkbox">Select Data Format</label><br/><br/>
                   <input type="checkbox" id="checkbox"> XES   <input type="checkbox" id="checkbox"> CSV</br></br>
                   <button>Export</button><!--Functionality must be implemented-->
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
                <span class="automaticNumberCounter" action="yourPhpScript" value='<?= $instances ?>'><?= $instances ?></span>
                <h3>Activities in the database</h3>
                <span class="automaticNumberCounter" value='<?= $activities ?>'><?= $activities ?></span>
                <h3>Attributes in the database</h3>
                <span class="automaticNumberCounter" value='<?= $attributes ?>'><?= $attributes ?></span>
            </div>
        </div>
        <div id = "rightContentBottom" onclick="expandDiv(this)">
            <div class = "rightContentWrapper">
                <div class = "icon"><i class="fa fa-lightbulb-o" aria-hidden="true"></i></div>
                <div class = "rightContentHeading"><span class="highlight">About</span> the Project</div>
            </div>
            <div class = "box">
                The whole Software Development Process is documented on GitHub. You can view it by following this link:
                <a href="https://github.com/MonteChristo46/RapidProcessParser">GitHub RapidProcessParser</a><br/>
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