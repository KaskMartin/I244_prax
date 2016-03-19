/**
 * Created by martin on 17.03.16.
 */

window.onload = function() {
    var i=0;
    var p2rliteNimistu = document.querySelectorAll('.bead, .parembead');
    var len = p2rliteNimistu.length;

    for ( ;i<len; ++i) p2rliteNimistu[i].onclick = function() {floatFlipper(this)};

    function floatFlipper(flippable) {
        if (flippable.getAttribute("style")==null ||
            flippable.getAttribute("style")=="") {
            if (flippable.className == "parembead") {
                flippable.style.cssFloat = "left";
            } else {
                flippable.style.cssFloat = "right";
            }
        }
        else flippable.style.cssFloat = (flippable.style.cssFloat == "left" ? "right" : "left");
    };

};

