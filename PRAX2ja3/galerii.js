/**
 * Created by martin on 21.03.16.
 */
window.onload = function() {
    if (document.getElementsByClassName('hoidja') != null) {
        console.log("Käivitan galerii!");
        var pictureArray = document.getElementById('galeriipildid').getElementsByTagName('a');
        console.log(pictureArray);
        for (var i = 0; i < pictureArray.length; i++) {
            pictureArray[i].onclick = function() {
                showDetails(this);
                return false;};
        }

    }
};

function suurus(el) {
    el.removeAttribute("height"); // eemaldab suuruse
    el.removeAttribute("width");
    if (el.width > window.innerWidth || el.height > window.innerHeight) {  // ainult liiga suure pildi korral
        if (window.innerWidth >= window.innerHeight) { // lai aken
            el.height = window.innerHeight * 0.9; // 90% kõrgune
            if (el.width > window.innerWidth) { // kas element läheb ikka üle piiri?
                el.removeAttribute("height");
                el.width = window.innerWidth * 0.9;
            }
        } else { // kitsas aken
            el.width = window.innerWidth * 0.9;   // 90% laiune
            if (el.height > window.innerHeight) { // kas element läheb ikka üle piiri?
                el.removeAttribute("width");
                el.height = window.innerHeight * 0.9;
            }
        }
    }
}

function showDetails(el) {
    console.log(el);
    $.get(el.href, "html", function(data){
        document.getElementById('sisu').innerHTML=data;
    });
    document.getElementById("hoidja").setAttribute("style", "display:initial");
}

function hideDetails () {
    document.getElementById("hoidja").setAttribute("style", "display:none");
}
window.onresize=function() {
    var suurpilt=document.getElementById("suurpilt");
    suurus(suurpilt);
};