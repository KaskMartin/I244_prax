/**
 * Created by martin on 16.03.16.
 */

window.onload = function() {
    var m2rklaud = document.getElementById("m2rklaud");
    m2rklaud.onclick = function() {
        m2rklaud.style.left=(50+Math.random()*(window.screen.availWidth-300))+"px";
        m2rklaud.style.top=(50+Math.random()*(window.screen.availHeight-300))+"px";
    };
// lehe laadimine lõpetatud. Siia funktsiooni sisse kirjutan elementide mõjutamise käsud
};
