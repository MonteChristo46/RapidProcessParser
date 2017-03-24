function toDo(obj){
    expandDiv(obj);
    countNumbers(obj);
}

function expandDiv(object){
    $(object).find('.box').slideToggle('slow', function(){
        console.log("hi");
        var bodyHeight = $('body').height();
        var rightContentBox = $(object);
        var rightHeight = (rightContentBox.height()/bodyHeight)*100; //Get % of page
        var newHeight = 0.8;
        var heightBorder = 0.7;
        console.log(rightHeight);
        if(rightHeight/100 < 0.8){
            rightContentBox.height(newHeight*bodyHeight);
            rightContentBox.siblings().height(((1-newHeight)/2)*100+"%");
            rightContentBox.siblings().attr("onclick","expandDiv(this)");
            rightContentBox.siblings().find('.box').css("display", "none");
        }else if(rightHeight/100 < 0.5){
            rightContentBox.height(100*(1/3)+"%"); // Height of rightContentTop
            rightContentBox.siblings().height(100*(1/3)+"%");
            rightContentBox.siblings().attr("onclick","expandDiv(this)");
        }
    });

}

function countNumbers(object) {
    var opened = false;
    var boxHeight = $(object).height();
    var bodyHeight = $('body').height();
    var actualHeight = Math.round((boxHeight/bodyHeight)*100);
    //console.log(actualHeight);
    var startingHeight = 33;
    if(actualHeight <= startingHeight){
        var opened = true;
    }
    if(opened) {
        $('.automaticNumberCounter').each(function () {
            var $this = $(this);
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

/*BACKUP
 function expandDiv(object){
 $(object).find(".box").slideToggle('slow', function(){
 var bodyHeight = $('body').height();
 var rightContent = $(object);
 var heading = rightContent.find(".rightContentHeading");
 var rightContentTopHeight = Math.round((rightContent.height()/bodyHeight)*100);
 var newHeight = 0.65; // in percentage //Bei 33.3 sollte das schon mehr als 0.6 sein...
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

 }*/