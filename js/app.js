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