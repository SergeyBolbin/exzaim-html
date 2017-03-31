
$(document).ready(function() {

    setInterval(function() {
        var seconds = new Date().getSeconds();
        var sdegree = seconds * 6;
        var srotate = "rotate(" + sdegree + "deg)";
        $("#sec").css({"-moz-transform": srotate, "-webkit-transform": srotate}); 
        if(isIE()) {
            $("#sec").css({msTransform : srotrate});
        }
    }, 1000);


    setInterval(function() {
        var hours = new Date().getHours();
        var mins = new Date().getMinutes();
        var hdegree = hours * 30 + (mins / 2);
        var hrotate = "rotate(" + hdegree + "deg)";

        $("#hour").css({"-moz-transform": hrotate, "-webkit-transform": hrotate}); 
        if(isIE()) {
            $("#hour").css({msTransform : srotrate});
        }

    }, 1000);


    setInterval(function() {
        var mins = new Date().getMinutes();
        var mdegree = mins * 6;
        var mrotate = "rotate(" + mdegree + "deg)";
        
        $("#min").css({"-moz-transform": mrotate, "-webkit-transform": mrotate});
        if(isIE()) {
            $("#min").css({msTransform : srotrate});
        }

    }, 1000);

}); 


function isIE(){
    var ua = window.navigator.userAgent;
    return ua.indexOf("MSIE") >= 0;
}