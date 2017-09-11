<?php
/**
 * Vzorce
 */

/**
 * 1. Zadanie
 * 2. Zadanie
 */

function Mi()
{
    return [
        'metan' => 16.043,
        'etan' => 30.07,
        'propan' => 44.097,
        'ibutan' => 58.124,
        'nbutan' => 58.124,
        'ipentan' => 72.151,
        'npentan' => 72.151,
        'hexan' => 88.178,
        'CO2' => 44.011,
        'N' => 28.016
    ];
}

function yiMi($yi, $mi)
{
    $result = array();
    if (count($mi) == count($yi)) {
        foreach ($yi as $key => $value) {
            array_push($result, $value * $mi[$key]);
        }
    } else {
        throw new Exception("Objemove zlozenie a Molekulova hmotnost maju ine parametre");
    }

    return array_sum($result);
}

// 0.0
function prop($rop0)
{
    return $rop0 / 28.98;
}

function rop0($pMp)
{
    return $pMp / 22.414;
}

//function pMp()

// 1.5
function proup($prop, $prod, $yin)
{
    return ($prop - $prod * $yin) / (1 - $yin);
}

// 1.3
function pred($p, $proup)
{
    return ($p * 1000000) / ((100000 * (46.9 - 2.06 * $proup * $proup)));
}

// 1.4
function Tred($T, $proup)
{
    return $T / (97 + 172 * $proup);
}

// 1.7
function zu($pred, $Tred)
{
    if ((0 <= $pred and $pred <= 3.8) and ($Tred <= 2 and $Tred >= 1.17)) {
        $zu = 1 - $pred * ((0.18 / ($Tred - 0.73)) - 0.135) + ((0.16 * pow($pred, 3.45)) / pow($Tred, 6.1));
    } else if ((0 <= $pred and $pred <= 1.45) and ($Tred <= 1.17 and $Tred >= 1.05)) {
        $zu = 1 - (0.23 * $pred) - (1.88 - 1.6 * $Tred) * $pred * $pred;
    } else if ((1.45 <= $pred and $pred <= 4) and ($Tred <= 1.17 and $Tred >= 1.05)) {
        $zu = 0.13 * $pred + (6.05 * $Tred - 6.26) * ($Tred / ($pred * $pred));
    } else {
        throw new Exception('Chyba pri vypocte zu // 1.7');
    }

    return $zu;
}

// 1.8
function zd($T, $p, $yiN)
{
    if ($yiN > 0) {
        // 1.8
        $zd = 1 + 0.564 * 0.0000000001 * pow(($T - 273), 3.71) * pow($p, (14.7 / pow(($T - 273), 0.5)));
    } else {
        $zd = 0;
    }

    return $zd;
}

// 1.6
function z($zu, $yi, $yin, $zd)
{
    return $zu * ($yi - $yin) + ($zd * $yin);
}

// 1.9
function roppT($rop0, $p, $z, $p0, $T)
{
    return ($rop0 * $p * 273.15) / ($z * $p0 * $T);
}

// 1.10
function VpT($V0, $z, $p0, $T, $p)
{
    return ($V0 * $z * $p0 * $T) / ($p * 273.15);
}

/**
 * 3. Zadanie
 */

// 1.26
function pnT($pn, $TL, $T, $gama, $yimetan, $yiN)
{
    return $pn - (($TL - $T) / (9.157 + (701.8 / ($gama * ($yimetan - 0.8 * $yiN)))));
}

function Rp($p, $pnT)
{
    return ((1 + log10($p)) / (1 + log10($pnT))) - 1;
}

function mT($T, $rorop, $prop)
{
    return 1 + 0.029 * ($T - 293) * ($rorop * $prop * 0.001 - 0.7966);
}

function DT($rorop, $prop, $T)
{
    return 0.001 * $rorop * $prop * (4.5 - 0.00305 * ($T - 293)) - 4.785;
}

// 1.27
function VpoppT($gama, $Rp, $mT, $DT)
{
    return $gama * $Rp * $mT * ($DT * (1 + $Rp) - 1);
}

// 1.28
function VprpT($gama, $mT, $VpoppT)
{
    return $gama * $mT - $VpoppT;
}

//1.29
function a3($T)
{
    return 1 + 0.0054 * ($T - 293);
}

function u3($rorop, $gama)
{
    return 0.001 * $rorop * $gama - 186;
}

function propoppT($a3, $prop, $Rp, $u3)
{
    return $a3 * ($prop - 0.0036 * (1 + $Rp) * (105.7 + $u3 * $Rp));
}

// 1.30
function propLpT($gama, $a3, $mT, $prop, $propoppT, $VpoppT, $VprpT)
{
    return (($gama * ($a3 * $mT * $prop - (($propoppT * $VpoppT) / $gama))) / $VprpT);
}

// 1.31
function brpT($rorop, $VprpT, $lamdaT, $mT, $alfar, $T, $p)
{
    return 1 + ((1.0733 * 0.001 * $rorop * $VprpT * $lamdaT) / $mT) + $alfar * ($T - 293) - 6.5 * 0.0001 * $p;
}

// Pomocny vypocet k 1.31
function lamdaT($rorop, $propLpT, $a3, $VprpT)
{
    return 0.001 * (4.3 - 3.54 * 0.001 * $rorop + ((1.0337 * $propLpT) / $a3) + 5.584 * 0.000001 * $rorop * (1 - 1.61 * 0.000001 * $rorop * $VprpT) * $VprpT);
}

// 1.32
function alfar($rorop)
{
    if ($rorop >= 780 and $rorop <= 860) {
        return 0.001 * (3.083 - 2.638 * 0.001 * $rorop);
    } else if ($rorop >= 860 and $rorop <= 960) {
        return 0.001 * (2.513 - 1.975 * 0.001 * $rorop);
    } else {
        throw new Exception('Chyba pri vypocte alfar // 1.32');
    }
}

// 1.33
function rorpT($rorop, $propLpT, $VprpT, $a3, $mT, $brpT)
{
    return ($rorop * (1 + ((1.293 * 0.001 * $propLpT * $VprpT) / ($a3 * $mT)))) / $brpT;
}

// 1.34
function gamarop($rorop)
{
    if ($rorop > 845 and $rorop < 924) {
        return (((0.658 * $rorop * $rorop) / (886 * 1000 - $rorop * $rorop)) * ((0.658 * $rorop * $rorop) / (886 * 1000 - $rorop * $rorop)));
    } else if ($rorop > 780 and $rorop <= 845) {
        return (((0.456 * $rorop * $rorop) / (833 * 1000 - $rorop * $rorop)) * ((0.456 * $rorop * $rorop) / (833 * 1000 - $rorop * $rorop)));
    } else {
        throw new Exception('Chyba pri vypocte rorpT // 1.33');
    }
}

// 1.35
function gamaropT($gamarop, $T, $rorop)
{
    $a4 = pow(10, (-0.0175 * (293 - $T) - 2.58));
    $b4 = (8.0 * 0.00001 * $rorop - 0.047) * pow($gamarop, (0.13 + 0.002 * ($T - 293)));
    return $gamarop * pow(($T - 296), $a4) * exp($b4 * (293 - $T));
}

// 1.38
function VhvprpT($alfar, $VprpT, $rorop)
{
    return 1.055 * 0.001 * (1 + 5 * $alfar) * $VprpT * $rorop;
}

// 1.36
function gamarpT($VhvprpT, $gamaropT)
{
    $A3 = 1 + 0.0129 * $VhvprpT - 0.0364 * pow($VhvprpT, 0.85);
    $B3 = 1 + 0.0017 * $VhvprpT - 0.0228 * pow($VhvprpT, 0.667);
    return $A3 * pow($gamaropT, $B3);
}

// 1.39
function sigmarop($p, $T)
{
    return 1 / (pow(10, (1.58 + 0.05 * $p)) - 72 * 0.000001 * ($T - 305));
}

/**
 * 4. Zadanie
 */

// Pomocny vypocet k viacerym funkciam
function m($TL, $rorop, $prop)
{
    return 1 + 0.029 * ($TL - 293) * ($rorop * $prop * 0.001 - 0.7966);
}

// 1.40
function kappaL($gama, $m)
{
    return $gama * $m;
}

function a4($TL)
{
    return 1 + 0.0054 * ($TL - 293);
}

// 1.41
function proprLpLTL($a4, $m, $prop, $gama, $kappaL)
{
    return (($a4 * $m * $prop * $gama) / $kappaL);
}

// 1.42
function brL($rorop, $proprLpLTL, $m, $alfar, $a4, $TL, $pL, $kappaL)
{
    $lamda = 0.001 * (4.3 - 3.54 * 0.001 * $rorop + ((1.0337 * $proprLpLTL) / $a4) + 5.581 * 0.000001 * $rorop * (1 - 1.61 * 0.000001 * $rorop * $kappaL) * $kappaL);
    return 1 + ((1.0733 * 0.001 * $rorop * $lamda * $kappaL) / $m) + $alfar * ($TL - 293) - 6.5 * 0.0001 * $pL;
}

// 1.43
function rorL($rorop, $proprLpLTL, $kappaL, $m, $a4, $brL)
{
    return (($rorop * (1 + ((1.293 * 0.001 * $proprLpLTL * $kappaL) / ($m * $a4)))) / $brL);
}

// Pomocny vypocet pre 1.45 a 1.46
function kappahv($alfar, $gama, $rorop)
{
    return 1.055 * 0.001 * (1 + 5 * $alfar) * $gama * $rorop;
}

// 1.45
function A4N($kappahv)
{
    return 1 + 0.0129 * $kappahv - 0.0364 * pow($kappahv, 0.85);
}

// 1.46
function B4($kappahv)
{
    return 1 + 0.0017 * $kappahv - 0.0228 * pow($kappahv, 0.667);
}

// 1.44
function gamarN($A4N, $gamaropT, $B4)
{
    return $A4N * pow($gamaropT, $B4);
}

// 1.48
function delta($gamarN)
{
    if ($gamarN < 5)                                                    // nie sigma ale delta
        $delta = 0.0114 * $gamarN;
    else if (($gamarN > 5) && ($gamarN < 10))
        $delta = 0.057 + 0.023 * ($gamarN - 5);
    else if (($gamarN > 10) && ($gamarN < 25))
        $delta = 0.0171 + 0.031 * ($gamarN - 10);
    else if (($gamarN > 25) && ($gamarN < 45))
        $delta = 0.643 + 0.045 * ($gamarN - 25);
    else if (($gamarN > 45) && ($gamarN < 75))
        $delta = 1.539 + 0.058 * ($gamarN - 45);
    else if (($gamarN > 75) && ($gamarN < 85))
        $delta = 3.286 + 0.1 * ($gamarN - 75);
    else
        throw new Exception("Chyba pri vypocte delta // 1.48");

    return $delta;
}

// 1.47
function gamarL($gamarN, $delta, $pL, $pn)
{
    return $gamarN + $delta * ($pL - $pn);
}

// 1.49
function c($cc)
{
    return (100 * $cc) / (1000 + $cc);
}

// 1.51
function alfaT($T)
{
    return (0.048 / (pow(($T - 293), 0.2096)));
}

// 1.50
function pkappav($alfaT, $c)
{
    return 1 / (pow(10, ($alfaT * $c)));
}

// 1.54
function alfavT($T)
{
    // TODO: pozriet na vypocet alfavTst, ci sa nic nedosadzuje
    $alfavTst = 1.8 * 0.0001;
    return $alfavTst + 0.18 * 0.0001 * pow(($T - 293), 0.6746);
}

// 1.53
function deltabT($alfavT, $T)
{
    return $alfavT * ($T - 293);
}

// 1.55
function deltabkappav($T, $p, $pkappav)
{
    return (1.8829 + 0.0102 * ($T - 273)) * $p * $pkappav * 0.0001;
}

// 1.57
function betavT($betavTst, $T)
{
    return $betavTst + ($T - 293) * (3.125 * 0.0001 * ($T - 293) - 2.5 * 0.01) * 0.0001;
}

// 1.56
function deltabp($betavT, $p)
{
    return -$betavT * $p;
}

// 1.52
function bv($deltabT, $deltabkappav, $deltabp)
{
    return 1 + $deltabT + $deltabkappav + $deltabp;
}

// 1.60 - 1.61
function kappav($alfap, $pn, $alfaT, $c)
{
    if ($c == 0) {
        // 1.60
        return $alfap * ($pn - 0.101325);
    } else {
        // 1.61
        return ($alfap * ($pn - 0.101325)) / (pow(10, $alfaT * $c));
    }
}

// 1.59
function betavpTL($betavT, $kappav)
{
    return $betavT * (1 + 0.05 * $kappav);
}

// 1.58
function bvL($bv, $betavpTL, $p, $pn)
{
    return $bv * (1 - $betavpTL * ($p - $pn));
}

// Pomocny vypocet
function rovst($c)
{
    if (($c > 0) && ($c <= 12))
        $rovst = 1000 + 6.95 * $c;
    else if (($c > 12) && ($c <= 20))
        $rovst = 1010.5 + 6.08 * $c;
    else if (($c > 20) && ($c <= 26))
        $rovst = 1027.1 + 5.25 * $c;
    else
        throw new Exception("Chyba co vypocte rovst");
    return $rovst;
}

// 1.62
function rovL($rovst, $bvL)
{
    return $rovst / $bvL;
}

// 1.64
function gamav($rovst, $T)
{
    return (1.4 + 3.8 * 0.001 * ($rovst - 1000)) / pow(10, 0.0065 * ($T - 293));
}

// 1.65
function sigmavp($p)
{
    return 1 / pow(10, (1.19 + 0.01 * $p));
}

/**
 * 6. Zadanie
 */

// 1.67
function betavst($nv, $rovst, $rorop)
{
    return $nv / ($nv + (((1 - $nv) * $rovst) / $rorop));
}

// 1.68
function Qr($Qkst, $brpT, $betavst)
{
    return ($Qkst * $brpT * (1 - $betavst)) / 86400;
}

// 1.69
function Qv($Qkst, $bv, $betavst)
{
    return ($Qkst * $bv * $betavst) / 86400;
}

// Pomocny vypocet
function F($Dt)
{
    return 3.14 * pow($Dt / 2, 2);
}

// 1.71
function wzm($Qr, $Qv, $F)
{
    return ($Qr + $Qv) / $F;
}

// 1.72
function wzmkr($Dt)
{
    return 0.487 * pow((9.81 * $Dt), 0.5);
}

// 1.70
function betav($Qv, $Qr)
{
    return $Qv / ($Qv + $Qr);
}

// 1.73
function sigmarv($sigmavp, $sigmarop)
{
    return $sigmavp - $sigmarop;
}

// Pomocny vypocet
function odm($Dt)
{
    return pow((9.81 * $Dt), 0.5);
}

// 1.74
function lambdav($wvred, $wzm, $odm, $sigmarv, $rovp, $rorp)
{
    $moc1 = ((4 * $sigmarv * 9.81 * ($rovp - $rorp)) / $rorp * $rorp);
    $moc2 = pow($moc1, 0.25);
    return $wvred / ($wzm - (0.425 - ((0.827 * $wzm) / ($odm))) * $moc2);
}

// 1.76
function lambdarOdLamdav($lambdav)
{
    return 1 - $lambdav;
}

// 1.75
function wvred($Qv, $F)
{
    return $Qv / $F;
}

// 1.75a
function wrred($Qr, $F)
{
    return $Qr / $F;
}

// 1.74a
function lambdar($sigmarv, $rovp, $rorp, $wrred, $wzm, $betav, $odm)
{
    return ($wrred / ($wzm + (0.54 * (1.01 + pow($betav, 0.152)) - ($wzm / ($odm))) * (pow((4 * $sigmarv * 9.81 * ($rovp - $rorp)) / ($rovp * $rovp), 0.25))));
}

// 1.76a
function lambdavOdLamdar($lamdar)
{
    return 1 - $lamdar;
}

// 1.83
function wekr($betav, $odm)
{
    return 0.064 * pow(56, $betav) * $odm;
}

// 1.84
function lambdavE($betav)
{
    return $betav;
}

function lambdarE($betav)
{
    return 1 - $betav;
}

// 1.x
function rorv($rorp, $lambdar, $rovp, $lambdav)
{
    return $rorp * $lambdar + $rovp * $lambdav;
}

// 1.86
function rovr($rorp, $betav, $rovp)
{
    return $rorp * (1 - $betav) + $rovp * $betav;
}

// 1.87
function wzd($wzm, $Dt)
{
    return (8 * $wzm) / $Dt;
}

// 1.88
function A6($betav, $wzd)
{
    return 1 + 20 * pow($betav, 2) / pow($wzd, 0.48 * $betav);
}

function B6($A6, $gamarN)
{                                          // 1.88
    if ($A6 <= 1)
        return $gamarN;
    else if ($A6 > 1)
        return $A6 * $gamarN;
}

// 1.89
function gamaeVR($B6, $betav)
{
    return ($B6 * (1 + 2.9 * $betav)) / (1 - $betav);
}

// 1.90
function gamaeRV($betav, $gamav)
{
    return $gamav * pow(10, 3.2 * (1 - $betav));
}


/**
 * Vzorce pre cyklus
 */
function omegaT($TL, $T, $h)
{
    return ($TL - $T) / $h;
}

function omegaST($Qpo, $Dt, $omegaT)
{
    return (0.0034 + 0.79 * $omegaT) / exp($Qpo / (86400 * 20 * exp(2.67 * log($Dt)) * log(10)));
}

function TU($TL, $omegaST, $h)
{
    return $TL - $omegaST * $h;
}

function T($TU, $TL, $p, $pU)
{
    return $TU + ($TL - $TU) * (($p - $pU) / ($p - $pU));
}

function pgamak($gamak, $gamav)
{
    return $gamak / $gamav;
}

function c1($pgamak, $Dt, $D0)
{
    return ((2.2361 * exp(0.049 * $pgamak)) / (1 + 1.1002 * exp(0.049 * $pgamak))) - 8.17 * 0.001 * pow($pgamak, 0.6) * (($Dt / $D0) - 1);
}

function c2($pgamak, $Dt, $D0)
{
    if ((1 < $pgamak) && ($pgamak < 40))
        return ((1 + 0.1082 * exp(0.049 * $pgamak)) / (1 + 1.1002 * exp(0.049 * $pgamak))) - (0.1006 - 2.52 * 0.001 * ($pgamak - 1)) * (($Dt / $D0) - 1);
    else if ($pgamak > 40)
        return (1 + 0.1082 * exp(0.049 * $pgamak)) / (1 + 1.1002 * exp(0.049 * $pgamak));
    else
        throw new Exception("Problem vo vypocte c2");
}

function FrZM($wzm, $Dt)
{
    return ($wzm * $wzm) / (9.81 * $Dt);
}

function lamdarC($betav, $c1, $c2, $FrZM)
{
    return $betav / ($c1 + $c2 * pow($FrZM, -0.5));
}

function K($Dt)
{
    return (1.4 * 0.00001) / $Dt;
}

function rek($wzm, $Dt, $roZM, $gamak)
{
    return ($wzm * $Dt * $roZM) / $gamak;
}

function lambdaC($rek, $K)
{
    return 0.067 * pow((158 / ($rek + 2 * $K)), 0.2);
}

function dpdHTR($lambdaC, $wzm, $roZM, $Dt)
{
    return ($lambdaC * $wzm * $wzm * $roZM * 0.000001) / (2 * $Dt);
}

function dpdH($roZM, $dpdHTR)
{
    return $roZM * 9.81 * 0.000001 + $dpdHTR;
}

function dHdp($dpdH)
{
    return 1 / $dpdH;
}