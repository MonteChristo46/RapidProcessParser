<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rapid Miner Process Parser</title>
    <link rel="stylesheet" href="extensions/materialize/css/materialize.min.css">
    <link rel="stylesheet" href="extensions/noUiSlider-master/distribute/nouislider.min.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="extensions/noUiSlider-master/distribute/nouislider.min.js"></script>
    <script src="extensions/jquery-3.2.0.min.js"></script>
    <script src="js/app.js"></script>
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
                   <input type="date" id = "startDate" class="datepicker"></br><br/>
                   <label for="endDate">End Date </label><br/>
                   <input type="date" id="endDate"></br></br>
                   <label for="range">Select the amount of data you want to export</label><br/><br/>
                   <input type="range" name="ageInputName" id="range" min="1" max="<?= $instances ?>" oninput="ageOutputId.value = range.value">
                   <output name="ageOutputName" id="ageOutputId">50</output> (# of Instances selected)</br></br>
                   <input type="checkbox" class="filled-in" id="filled-in-box" checked="checked" />
                   <label for="filled-in-box">Export all attributes?</label><br/><br>

                   <!-- Switch -->
                   <label>Select export format:</label>
                   <div class="switch">
                       XES
                       <label>
                           No
                           <input type="checkbox">
                           <span class="lever"></span>
                           Yes
                       </label>
                   </div>
                   <div class="switch">
                       CSV
                       <label>
                           No
                           <input type="checkbox">
                           <span class="lever"></span>
                           Yes
                       </label>
                   </div>
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
                <div class="chartDiv">
                    <ul class="chart">

                        <li>
                            <span style="height:<?=$instancesHeight?>%" title="Instances"></span>
                        </li>
                        <li>
                            <span style="height:<?=$activitiesHeight?>%" title="Activities"></span>
                        </li>
                        <li>
                            <span style="height:<?=$attributesHeight?>%" title="Attributes"></span>
                        </li>
                    </ul>
                </div>

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
<script type="text/javascript">

</script>
</html>