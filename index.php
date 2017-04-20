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
    <script src="extensions/d3.min.js"></script>
    <script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>


    <!--LOCAL SPEICHERN!!!-->
    <link href="https://fonts.googleapi  s.com/css?family=Open+Sans" rel="stylesheet">
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
                        <input type="text" class="leftInput" name="useCaseLabel" id="useCaseLabel" placeholder="Please add Use Case name" required/><br/>
                        <input type="text" class="leftInput" name="label1" id="label1" placeholder="Name of Dataset"/>
                        <input type="text" class="leftInput" name="label2" id="label2" placeholder="Classification"/><br/>
                        <input type="text" class="leftInput" name="label3" id="label3" placeholder="Please add Label"/>
                        <input type="text" class="leftInput" name="label4" id="label4" placeholder="Please add Label"/><br/>
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
                <div class = "icon circle" ><i class="fa fa-filter" aria-hidden="true"></i></div>
                <div class = "rightContentHeading"><span class="highlight">Select </span> Data</div>
            </div>
            <div class = "box" id="filterBox" onclick=" event.stopImmediatePropagation();">
                <h2>Filter Box</h2>
                </br>
               <form action = "yourPHPFile" id="filterForm">
                   <div class = "formData">
                      <form>
                       <label for="startDate ">Start Date </label><br/>
                       <input type="date" id = "startDate" class="datePicker" name="startDate" placeholder="dd.mm.yyyy"></br><br/>
                       <label for="endDate">End Date </label><br/>
                       <input type="date" class="datePicker" id="endDate" placeholder="dd.mm.yyyy">
                      </form>
                   </div>
                   <div class="formData">
                       <form>
                       <label for="range">Select the amount of data you want to export</label><br/><br/>
                       <input type="range"  value="<?=$instances ?>" id="range" min="1" max="<?= $instances ?>" oninput="rangeOut.value = range.value" >
                       <output name="rangeOut" id="rangeOut"><?=$instances?></output>
                       <div>(# of Instances selected)</div>
                       </form>
                   </div>
                   <div class = "formData" id="labelSelectors">
                       <table>
                           <tr>
                               <td>
                                   <form>
                                   <label for="labelsInput">Labels
                                       <i class="fa fa-trash" id="deleteLabels"aria-hidden="true"></i></label>
                                   </br>
                                   <div class = "selectedValues" style="font-size: 10px">Selected Labels: <span style="font-size: 10px" id="selectedValuesLabels"></span></div>
                                   </br>
                                   <input style="width: 95%" type="text" list="labelsList" id="labelsInput" value="" size="50"  placeholder="  Type labels" onsubmit="">
                                   <datalist id="labelsList">
                                       <?php createLabelSelectionForDataList() ?>
                                   </datalist>
                                   </form>
                               </td>
                               <td>
                                   <form>
                                   <label for="labelsInput">Use Case Name
                                       <i class="fa fa-trash" id="deleteUseCases"aria-hidden="true"></i></label>
                                   </br>
                                   <div class = "selectedValues" style="font-size: 10px">Selected Names: <span style="font-size: 10px" id="selectedValuesUseCase"></span></div>
                                   </br>
                                   <input style="width: 95%" type="text" list="useCaseList" id="useCaseInput" value="" size="50"  placeholder="  Type Names" onsubmit="">
                                   <datalist id = "useCaseList">
                                       <?php createUseCaseSelectionForDataList() ?>
                                   </datalist>
                                   </form>
                               </td>
                           </tr>
                       </table>
                   </div>
                   <div class="formData">
                       <form>
                       <input type="checkbox" class="filled-in" id="filled-in-box" checked="checked" name="checkAllAttr"/>
                       <label for="filled-in-box">Export all attributes?</label><br/><br>
                       </form>
                   </div>
                  <div class="formData">
                      <form>
                       <label>Select export format:</label>
                       <div class="switch">
                           XES
                           <label>
                               <input class="switchDownloadType" type="checkbox" id="xes" checked>
                               <span class="lever"></span>
                           </label>
                       </div>
                       <div class="switch">
                           CSV
                           <label>
                               <input class="switchDownloadType" type="checkbox" id="csv">
                               <span class="lever"></span>
                           </label>
                       </div>
                      </form>
                  </div>
                   <button type="button" class="exportButton" id="exportFilterButton">Export w/ filters</button>
                   <button type="button" class="exportButton" id="exportLastButton">Export last</button>
               </form>
            </div>
        </div>
        <div id = "rightContentMiddle" class="normal" onclick="toDo(this)">
            <div class = "rightContentWrapper">
                    <div class = "icon circle"><i class="fa fa-database" aria-hidden="true"></i></div>
                    <div class = "rightContentHeading">Data <span class="highlight">Overview</span></div>
            </div>
            <div class = "box">
                <div class="chartDiv">
                    <svg id="dataOverview" width="320" height="250">"></svg>
                </div>



                <h3>Must frequently used use case</h3>
                <span class=" automaticNumberCounter"><?= $fProcess?></span>
                <h3>Must frequently used activity</h3>
                <span class=" automaticNumberCounter"><?= $fActivity?></span>
                <h3>Must frequently used attribute</h3>
                <span class=" automaticNumberCounter"><?= $fAttr?></span>
                <!--<h3>Process instances in the database</h3>
                <span class="automaticNumberCounter" action="yourPhpScript" value='<?= $instances ?>'><?= $instances ?></span>
                <h3>Activities in the database</h3>
                <span class="automaticNumberCounter" value='<?= $activities ?>'><?= $activities ?></span>
                <h3>Attributes in the database</h3>
                <span class="automaticNumberCounter" value='<?= $attributes ?>'><?= $attributes ?></span>
                -->
            </div>
        </div>
        <div id = "rightContentBottom" class="normal" onclick="expandDiv(this)">
            <div class = "rightContentWrapper">

                <div class = "icon circle"><i class="fa fa-lightbulb-o" aria-hidden="true"></i></div>
                <div class = "rightContentHeading"><span class="highlight">About</span> the Project</div>
            </div>
            <div class = "box" id="aboutText">
                <p>This software provides you with the possibility to upload <span class="highlight">XML files of Rapid Miner processes</span> and store these data inside a <span class="highlight">MySQL database</span>.
                    Furthermore, you can <span class="highlight">export</span> the whole database with filter settings, like "# of instances", in the box <span class="highlight">"Select data"</span> up top.<br/><br/>
                    The exported file is parsed for the usage in terms of <span class="highlight">Process Mining</span>. Because of that, you can choose whether to export the data
                    in the <span class="highlight">XES</span> or the <span class="highlight">CSV</span> format to ensure usage in different Process Mining Tools.<br/>
                <br/>The whole Software Development Process is documented on GitHub. You can view it by following this link:
                <a href="https://github.com/MonteChristo46/RapidProcessParser" target="_blank">GitHub RapidProcessParser</a><br/></p>
            </div>
        </div>
    </div>
<iframe id="test" src=""></iframe>
</body>
<script>
    $("#labelSelectors").submit(function(e) {
        e.preventDefault();
    });
    //Make Ruler unavailable if date is picked
    $(".datePicker").change(function(){
        var value = $(this).val();
        if(value != ""){//If date was set
            $('#range').attr('disabled', 'disabled');
            $('#range').css('cursor', 'not-allowed');
        }else{
            var currentValues = $('.datePicker');
            var remove = true;

            $.each(currentValues, function(){
                console.log($(this).val());
                if($(this).val() != ""){
                    remove = false;
                }
            });
            if(remove){
                $('#range').removeAttr('disabled');
            }
        }
    });

    /*Eigentlich muss hier eine Funktion stehen - aber ich habe kein Bock mehr. Mach ich noch*/

    //Variablen --> Müssen an outParserHandler Übergeben werden
    var selectedLabels = ["da39a3ee"]; // Ajax is not able to send empty arrays -- Hash provide solution
    var selectedNames = ["da39a3ee"];
    //LabelSelection
    $("#labelsInput").keypress(function(e) {
        //13 is equal to enter
        if (e.which == 13) {
            var valueOfInput = $("#labelsInput").val();
            var options = $("#labelsList").children();
            var values = [];
            for(var i = 0; i<options.length; i++){
                var value = options[i].getAttribute("value");
                values.push(value);
            }
            if(values.includes(valueOfInput) == false){
                alert("Your Label: "+valueOfInput+" doesn't exist or you already selected it.");
            }else{
                for(var i = 0; i<options.length; i++){
                    if($(options[i]).attr("value") == valueOfInput){
                        options[i].remove();
                    }
                }
                $("#selectedValuesLabels").append(valueOfInput+", ");
                selectedLabels.push(valueOfInput);
                $("#labelsInput").val("");

            }
        }
    });
    //Use Case Selection
    $("#useCaseInput").keypress(function(e) {
        //13 is equal to enter
        if (e.which == 13) {
            var valueOfInput = $("#useCaseInput").val();
            var options = $("#useCaseList").children();
            var values = [];
            for(var i = 0; i<options.length; i++){
                var value = options[i].getAttribute("value");
                values.push(value);
            }
            if(values.includes(valueOfInput) == false){
                alert("The Name: "+valueOfInput+" doesn't exist or you already selected it.");
            }else{
                for(var i = 0; i<options.length; i++){
                    if($(options[i]).attr("value") == valueOfInput){
                        options[i].remove();
                    }
                }
                $("#selectedValuesUseCase").append(valueOfInput+", ");
                selectedNames.push(valueOfInput);
                $("#useCaseInput").val("");

            }
        }
    });

    $('.fa-trash').click(function(){
        var id = $(this).attr('id');
        if(id == "deleteLabels"){
            var span = $('#selectedValuesLabels');
            var options = $("#labelsList");
        }else if(id == "deleteUseCases"){
            var span = $('#selectedValuesUseCase');
            var options = $('#useCaseList');
        }
        var selected = span.html();
        var valueArray = selected.split(", ");
        console.log(valueArray);
        for(var i=0; i<(valueArray.length - 1); i++){
            console.log(valueArray[i]);
            var opt = document.createElement('option');
            opt.value = valueArray[i];
            options.append(opt);

            if(id == "deleteUseCases"){
                for(var j=0; j<selectedNames.length; j++){
                    if(selectedNames[j] == valueArray[i]){
                        selectedNames.splice(j);
                    }
                }
            }else if(id == "deleteLabels"){
                for(var j=0; j<selectedLabels.length; j++){
                    if(selectedLabels[j] == valueArray[i]){
                        selectedLabels.splice(j);
                    }
                }
            }
            //console.log(selectedLabels);
            console.log(selectedNames);

        }
        span.html("");
    });

    //Get Filter expressions and start download of parsed data
    $('.exportButton').click(function(){
        //console.log("Button wurde geklickt");
        $.ajax({
            type: 'POST',
            dataType:'json',
            url: 'php/outParserHandler.php',
            data: {
                type: $(this).attr('id'),
                range: range.value,
                startDate: $('#startDate').val(),
                endDate: $('#endDate').val(),
                allAttr: $('#filled-in-box').is(":checked"),
                xes: $('#xes').is(":checked"),
                csv: $('#csv').is(":checked"),
                useCases: selectedNames,
                labels: selectedLabels
            },
            success: function(url) {
                var req = new XMLHttpRequest();
                var file = url.a;
                console.log(url);
                console.log(url.a);
                req.open("GET", "php/"+file, true);
                req.responseType = "blob";
                req.onload = function (event) {
                    var blob = req.response;
                    var fileName = file;
                    var link=document.createElement('a');
                    link.href=window.URL.createObjectURL(blob);
                    link.download=fileName;
                    link.click();
                };
                req.send();
            },
            error: function (url,request, error) {
                console.log(url.responseText);
                console.log(request);
                console.log(error);
                alert(" Can't do because: " + error);
            }
        });
    });

    //Change button value when file is selected
    var inputs = document.querySelectorAll( '.inputfile' );
    Array.prototype.forEach.call(inputs, function( input )
    {
        //console.log(input);
        var label	 = input.nextElementSibling;
        var labelVal = label.innerHTML;

        //console.log(label);
        input.addEventListener('change', function(e){

            var fileName = '';
            if( this.files && this.files.length > 1 ) {
                fileName = (this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
            }else {
                fileName = e.target.value.split('\\').pop();
            }
            if(fileName) {
                label.querySelector('span').innerHTML = fileName;
            }else{
                label.querySelector('span').innerHTML = labelVal;
            }
        });
    });
    function getChart() {
        $.ajax({
            url:'php/getJSON.php',
            complete: function (json) {
                var data = JSON.parse(json.responseText);
                console.log(data);
                var svg = d3.select("#dataOverview"),
                    margin = {top: 20, right: 20, bottom: 30, left: 40},
                    width = +svg.attr("width") - margin.left - margin.right,
                    height = +svg.attr("height") - margin.top - margin.bottom;

                var x = d3.scaleBand().rangeRound([0, width]).padding(0.1),
                    y = d3.scaleLinear().rangeRound([height, 0]);

                var g = svg.append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

                var colors = d3.scaleLinear()
                    .domain([0, data.length])
                    .range(["#244071", "#162846"]);
                x.domain(data.map(function(d) { return d.name; }));
                y.domain([0, d3.max(data, function(d) { return parseInt(d.number); })]);

            

                g.append("g")
                    .attr("class", "axis axis--x")
                    .attr("transform", "translate(0," + height + ")")
                    .call(d3.axisBottom(x));

                g.append("g")
                    .attr("class", "axis axis--y")
                    .call(d3.axisLeft(y).ticks(10))
                    .append("text")
                    .attr("transform", "rotate(-90)")

                g.selectAll(".bar")
                    .data(data)
                    .enter().append("rect")
                    .style("fill", function(d,i){
                        return colors(i);
                    })
                    .attr("class", "bar")
                    .attr("x", function(d) { return x(d.name); })
                    .attr("y", function(d) { return y(d.number); })
                    .attr("width", x.bandwidth())
                    .attr("height", function(d) { return height - y(d.number); });


            },
            error: function () {
                console.log("Something went wrong with the chart creation! :(");
            }
        });
        return false;
    }
    getChart();

</script>
</html>