<?php

$p = 2;
$pL = 4.5;
$H = 0;
$dHdp = [116.1, 112.9, 109.8, 107.3, 105.3, 103.9, 102.6];

$resultP = [$p];
$resultH = [$H];
$i = 1;
while ($p <= $pL) {
    $posN = $i;
    $posO = $posN - 1;
    $p += 0.5;
    array_push($resultP, $p);
    $plusA = 0;
    if ($i > 1) {
        $plusA = array();
        for($j = 1; $j<=$posO; $j++){
            array_push($plusA, $dHdp[$j]);
        }
        $plusA = array_sum($plusA);
    }
    $H = ($resultP[$posN] - $resultP[$posO]) * ((($dHdp[0] + $dHdp[$posN])/2) + $plusA);
    array_push($resultH, $H);
    echo $H . '<br />';
    $i++;
}