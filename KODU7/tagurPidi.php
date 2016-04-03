<?php

$s = 'abcdefg123456789nitraM';

echo "Esialgne string: $s\n </br>";

$temp = '';
for ($i = 0, $j = mb_strlen($s); $i < $j; $i++) {
    $temp .= $s{$j - $i - 1};
};

echo "Esimese variandi tulem: $temp \n </br>";

echo "Teise variandi tulem: ";
for($i=strlen($s)-1;$i >=0;$i--){
    echo "$s[$i]";
};

echo "\n </br>";

for($i=strlen($s)-1, $j=0; $j<$i; $i--, $j++) {
    list($s[$j], $s[$i]) = array($s[$i], $s[$j]);
}

echo "Kolmanda variandi tulem: $s";

?>


