<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rapid Miner Process Parser</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="extensions/jquery-3.2.0.min.js"></script>
    <script src="js/app.js"></script>
    <link rel="stylesheet" href="extensions/font-awesome-4.7.0/css/font-awesome.css">

    <!--LOCAL SPEICHERN!!!-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
<?php
//Block Internet Explorer

    $msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
    // IE
    if ($msie) {
        //Funktioniert das mit dem CSS?
        echo 'You are using the Internet Explorer currently. Please use Google Chrome for this page!';
        echo '<style type="text/css">
                #leftContent, #rightContent {
                    display: none;
                }
              </style>';
    }
?>
<?php require_once("php/interfaceLogic.php"); ?>
    <div id = "leftContent">
        <div id = "welcomeTextWrapper">
            <div id = "welcomeText">
                <h1>Hello, Please Upload your </br>
                <span class="highlight">Rapid Miner</span> File!</h1>
            </div>
            <div id="uploadForm">
                <form action="" enctype="multipart/form-data" method="post">
                    <div>
                        <input type="file" name="upload[]" id="file" class="inputfile" data-multiple-caption="{count} files selected" multiple/>
                        <label class="fileContainer" for="file" id="label"><i style = "margin-right: 5px;"class="fa fa-upload" aria-hidden="true"></i><span>Choose files</span></label>
                        <br/><br/>
                        <button id="submitLabel" type="submit" value="Submit" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id =  "rightContent">
        <div id = "rightContentTop" class="normal" onclick="expandDiv(this)">
            <div class = "rightContentWrapper" >
                <div class = "icon" ><i class="fa fa-filter" aria-hidden="true"></i></div>
                <div class = "rightContentHeading"><span class="highlight">Select </span> Data</div>
            </div>
            <div class = "box" onclick=" event.stopImmediatePropagation();">
                <h2>Filter Box</h2>
                </br>
               <form action = "yourPHPFile" id="filterForm">
                   <div id = "formData">
                       <label for="startDate ">Start Date </label><br/>
                       <input type="date" id = "startDate" class="datePicker" name="startDate"></br><br/>
                       <label for="endDate">End Date </label><br/>
                       <input type="date" class="datePicker" id="endDate">
                   </div>
                   <div id="formData">
                       <label for="range">Select the amount of data you want to export</label><br/><br/>
                       <input type="range" name="range" value="<?=$instances ?>" id="range" min="1" max="<?= $instances ?>" oninput="rangeOut.value = range.value" >
                       <output name="rangeOut" id="rangeOut"><?=$instances?></output>
                       <div>(# of Instances selected)</div>
                   </div>
                   <div class="formData">
                       <input type="checkbox" class="filled-in" id="filled-in-box" checked="checked" name="checkAllAttr"/>
                       <label for="filled-in-box">Export all attributes?</label><br/><br>
                   </div>
                  <div class="formData">
                       <label>Select export format:</label>
                       <div class="switch">
                           XES
                           <label>
                               <input type="checkbox" id="xes">
                               <span class="lever"></span>
                           </label>
                       </div>
                       <div class="switch">
                           CSV
                           <label>
                               <input type="checkbox" id="csv">
                               <span class="lever"></span>
                           </label>
                       </div>
                  </div>
                   <!--Functionality must be implemented-->
                   <button type="button" class="exportButton" id="exportFilterButton">Export w/ filters</button>
                   <!--SQL Statement "SELECT Name, P_ID, Date FROM Process_Instance WHERE P_ID = MAX(P_ID)-->
                   <button type="button" class="exportButton" id="exportLastButton">Export last</button>
               </form>
            </div>
        </div>
        <div id = "rightContentMiddle" class="normal" onclick="toDo(this)">
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
        <div id = "rightContentBottom" class="normal" onclick="expandDiv(this)">
            <div class = "rightContentWrapper">

                <div class = "icon"><i class="fa fa-lightbulb-o" aria-hidden="true"></i></div>
                <div class = "rightContentHeading"><span class="highlight">About</span> the Project</div>
            </div>
            <div class = "box" id="aboutText">
                <p>This software provides you with the possibility to upload <span class="highlight">XML files of Rapid Miner processes</span> and store these data inside a <span class="highlight">mySQL database</span>.
                    Furthermore, you can <span class="highlight">export</span> the whole database with filter settings, like "# of instances", in the box <span class="highlight">"Select data"</span> up top.<br/><br/>
                    The exported file is parsed for the usage in terms of <span class="highlight">Process Mining</span>. Because of that, you can choose whether to export the data
                    in the <span class="highlight">XES</span> or the <span class="highlight">CSV</span> format to ensure usage in different Process Mining Tools.<br/>
                <br/>The whole Software Development Process is documented on GitHub. You can view it by following this link:
                <a href="https://github.com/MonteChristo46/RapidProcessParser" target="_blank">GitHub RapidProcessParser</a><br/></p>
            </div>
        </div>
    </div>
</body>
<script>
    //Get Filter expressions
    $('#exportFilterButton').click(function(){
        $.ajax({ url: 'php/exportFilter.php',
            data: { export: true,
                range: range.value,
                startDate: $('#startDate').val(),
                endDate: $('#endDate').val(),
                allAttr: $('#filled-in-box').is(":checked"),
                xes: $('#xes').is(":checked"),
                csv: $('#csv').is(":checked")},
            type: 'post',
            success: function(output) {
                alert(output);
            }
        });
    });

    //Change button value when file is selected
    var inputs = document.querySelectorAll( '.inputfile' );
    Array.prototype.forEach.call( inputs, function( input )
    {
        console.log(input);
        var label	 = input.nextElementSibling,
            labelVal = label.innerHTML;
        console.log(label);
        input.addEventListener( 'change', function( e )
        {
            var fileName = '';
            if( this.files && this.files.length > 1 )
                fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
            else
                fileName = e.target.value.split( '\\' ).pop();

            if( fileName ) {
                console.log("here");
                label.querySelector('span').innerHTML = fileName;
            }else {
                label.querySelector('span').innerHTML = labelVal;
            }
        });
    });
</script>
</html>