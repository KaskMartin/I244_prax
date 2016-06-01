/**
 * laenatud : http://stackoverflow.com/questions/4847726/how-to-animate-following-the-mouse-in-jquery
 */
window.onload = function() {var mouseX = 0, mouseY = 0;
$(document).mousemove(function(e){
    mouseX = e.pageX;
    mouseY = e.pageY;
});

// cache the selector
var follower = $("#j2litaja");
var xp = 0, yp = 0;
var loop = setInterval(function(){
    // change 12 to alter damping, higher is slower
    xp += (mouseX - xp) / 12;
    yp += (mouseY - yp) / 12;
    follower.css({left:xp, top:yp});

}, 30);}