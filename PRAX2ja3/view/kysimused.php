<?php
global $kysimused;

//siia SQL päringuga andmebaasist kysimused


$kysimused=array(
    array("kysimus_id"=>"1", "Kysimus"=>"mitu Sõrme on inimesel",
        array("variant"=>"5", "value" => "false"),
        array("variant"=>"12", "value" => "false"),
        array("variant"=>"10", "value" => "true")
    ),
    array("kysimus_id"=>"2", "Kysimus"=>"mitu varvast on lätlasel",
        array("variant1"=>"5", "value" => "false"),
        array( "variant2"=>"12", "value" => "true"),
        array("variant2"=>"10", "value" => "false")
    )

);

