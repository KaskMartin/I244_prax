/**
 * Created by martin on 17.03.16.
 */

window.onload = function() {
    var i=0;
    var p2rliteNimistu = document.querySelectorAll('.bead, .parembead');
    var len = p2rliteNimistu.length;

    for ( ;i<len; ++i) p2rliteNimistu[i].onclick = function() {floatFlipper(this)};

    function floatFlipper(flippable) {
        if (flippable.style.cssFloat = undefined) {
            flippable.className == "bead" ? flippable.style.cssFloat = "right" : flippable.style.cssFloat = "left";
        }
        flippable.style.cssFloat = (flippable.style.cssFloat == "right") ? "left" : "right";
    };

};

