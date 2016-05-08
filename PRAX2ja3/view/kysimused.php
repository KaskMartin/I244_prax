<?php
global $kysimused;

//siia SQL päringuga andmebaasist kysimused


$kysimused=array(
    array("kysimus_id"=>"1", "kysimus"=>"mitu Sõrme on inimesel",
        "vastused" => array (
            array("variant"=>"5", "value" => "false"),
            array("variant"=>"12", "value" => "false"),
            array("variant"=>"10", "value" => "true")
        ),
    ),
    array("kysimus_id"=>"2", "kysimus"=>"mitu varvast on lätlasel",
        "vastused" => array (
            array("variant"=>"5", "value" => "false"),
            array("variant"=>"12", "value" => "true"),
            array("variant"=>"10", "value" => "false")
        ),
    ),

    array("kysimus_id"=>"3", "kysimus"=>"Mitu lisapunkti mea peaksime saama",
        "vastused" => array (
            array("variant"=>"1", "value" => "false"),
            array("variant"=>"2", "value" => "false"),
            array("variant"=>"3", "value" => "false"),
            array("variant"=>"4", "value" => "true"),
            array("variant"=>"KÕIK", "value" => "true")
        ),
    ),

    array("kysimus_id"=>"4", "kysimus"=>"JetFuel can melt steal beams",
        "vastused" => array (
            array("variant"=>"YES", "value" => "false"),
            array("variant"=>"NO", "value" => "true"),
        ),
    )

);

