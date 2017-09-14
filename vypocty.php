<?php
require_once('vzorce.php');
// Podielove mnozstva
$yi = [
    'metan' => 0.518,
    'etan' => 0.224,
    'propan' => 0.07,
    'ibutan' => 0.07,
    'nbutan' => 0.112,
    'ipentan' => 0.0,
    'npentan' => 0.006,
    'hexan' => 0.0,
    'CO2' => 0.0,
    'N' => 0.0
];
$mi = Mi();
$yimi = yiMi($yi, $mi);
$yiCelkove = array_sum($yi);

// Hodnoty
$p = 0.8;
$pU = 0.8;
$pn = 20.65;
$pL = 11.42;
$T = 309;
$h = 2500;
$TL = 349.95;
$rorop = 838;
$Qkst = 12;
$gama = 111;
$cc = 150;
$prod = 0.970;
$p0 = 0.101325;
$Dt = 0.0635;
$nv = 0.92;
$alfap = 0.15;
$betavTst = 4.7 * 0.0001;
$odm = odm($Dt);
$D0 = 0.015;
$dHdpCollection = array();
$resultP = [$p];
$resultH = [0];
$resultRor = [];
$viskozita = [];
$resultBrpT = [];

$i = 0;
while ($p <= $pL) {
    /**
     * 1. Zadanie
     * 2. Zadanie
     */
    $yiMi = yiMi($yi, $mi);
    $rop0 = rop0($yiMi);
    $prop = prop($rop0);
    $proup = proup($prop, $prod, $yi['N']);
    $a3 = a3($T);
    $u3 = u3($rorop, $gama);
    $pnt = pnT($pn, $TL, $T, $gama, $yi['metan'], $yi['N']);
    $Rp = Rp($p, $pnt);
    $propoppT = propoppT($a3, $prop, $Rp, $u3);
    $pred = pred($p, $proup);
    $Tred = Tred($T, $proup);
    $zu = zu($pred, $Tred);
    $zd = zd($T, $p, $yi['N']);
    $z = z($zu, $yiCelkove, $yi['N'], $zd);
    $roppT = roppT($rop0, $p, $z, $p0, $T);
    $mT = mT($T, $rorop, $prop);
    $DT = DT($rorop, $prop, $T);
    $VpoppT = VpoppT($gama, $Rp, $mT, $DT);
    $VpT = VpT($VpoppT, $z, $p0, $T, $p);
    /**
     * Koniec 1. Zadanie
     * Koniec 2. Zadanie
     */

    /**
     * 3. Zadanie
     */
    $VpoppT = ($p > $pn) ? $VpoppT : 0;
    $VprpT = VprpT($gama, $mT, $VpoppT);
    $propLpT = propLpT($gama, $a3, $mT, $prop, $propoppT, $VpoppT, $VprpT);
    $lamdaT = lamdaT($rorop, $propLpT, $a3, $VprpT);
    $alfar = alfar($rorop);
    $brpT = brpT($rorop, $VprpT, $lamdaT, $mT, $alfar, $T, $p);
    $rorpT = rorpT($rorop, $propLpT, $VprpT, $a3, $mT, $brpT);
    $gamarop = gamarop($rorop);
    $VhvprpT = VhvprpT($alfar, $VprpT, $rorop);
    $gamaropT = gamaropT($gamarop, $T, $rorop);
    $gamarpT = gamarpT($VhvprpT, $gamaropT);
    $sigmarop = sigmarop($p, $T);
    /**
     * Koniec 3. Zadanie
     */

    /**
     * 4. Zadanie
     */
    $m = m($TL, $rorop, $prop);
    $kappaL = kappaL($gama, $m);
    $a4 = a4($TL);
    $proprLpLTL = proprLpLTL($a4, $m, $prop, $gama, $kappaL);
    $brL = brL($rorop, $proprLpLTL, $m, $alfar, $a4, $TL, $pL, $kappaL);
    $rorL = rorL($rorop, $proprLpLTL, $kappaL, $m, $a4, $brL);
    $kappahv = kappahv($alfar, $gama, $rorop);
    $A4N = A4N($kappahv);
    $B4 = B4($kappahv);
    $gamarN = ($p > $pn) ? gamarN($A4N, $gamaropT, $B4) : gamarpT($VhvprpT, $gamarop);
    $delta = delta($gamarN);
    $gamarL = gamarL($gamarN, $delta, $pL, $pn);
    /**
     * Koniec 4. Zadanie
     */

    /**
     * 5. Zadanie
     */
    $c = c($cc);
    $rovst = rovst($c);
    $alfaT = alfaT($T);
    $pkappav = pkappav($alfaT, $c);
    $alfavT = alfavT($T);
    $deltabT = deltabT($alfavT, $T);
    $deltabkappav = deltabkappav($T, $p, $pkappav);
    $betavT = betavT($betavTst, $T);
    $deltabp = deltabp($betavT, $p);
    $bv = bv($deltabT, $deltabkappav, $deltabp);

    if ($p > $pn == false) {
        $kappav = kappav($alfap, $pn, $alfaT, $c);
        $betavpTL = betavpTL($betavT, $kappav);
        $bvL = bvL($bv, $betavpTL, $p, $pn);
    }

    $rovL = rovL($rovst, $bv);
    $gamav = gamav($rovst, $T);
    $sigmavp = sigmavp($p);
    /**
     * Koniec 5. Zadanie
     */

    /**
     * 6. Zadanie
     */
    $betavst = betavst($nv, $rovst, $rorop);
    $Qr = Qr($Qkst, $brpT, $betavst);
    $Qv = Qv($Qkst, $bv, $betavst);
    $F = F($Dt);
    $wzm = wzm($Qr, $Qv, $F);
    $wzmkr = wzmkr($Dt);
    $sigmarv = sigmarv($sigmavp, $sigmarop);
    $betav = betav($betavst, $brpT);

    if ($wzm < $wzmkr) {
        // Kvapkova struktura
        // V/R
        if ($betav <= 0.5) {
            $wvred = wvred($Qv, $F);
            $lambdav = lambdav($wvred, $wzm, $odm, $sigmarv, $rovL, $rorpT);
            $lambdar = lambdarOdLamdav($lambdav);
            $gamarv = $gamarL;
        } // R/V
        else {
            $wrred = wrred($Qr, $F);
            $lambdar = lambdar($sigmarv, $rovL, $rorpT, $wrred, $wzm, $betav, $odm);
            $lambdav = lambdavOdLamdar($lambdar);
            $gamarv = $gamav;
        }
        $gamak = $gamarv;

        // Koniec Kvapkovej struktury
    } else {
        if ($wzm > $wzmkr) {
            // Emulzna struktura
            $wekr = wekr($betav, $odm);
            if ($wzm > $wekr and $betav <= 0.5) {
                // V/R
                $wzd = wzd($wzm, $Dt);
                $A6 = A6($betav, $wzd);
                $B6 = B6($A6, $gamarN);
                $gamae = gamaeVR($B6, $betav);
                $gamarv = $gamarL;
            } else {
                if (($wzm < $wekr and $betav <= 0.5) or $betav > 0.5) {
                    // R/V
                    $gamae = gamaeRV($betav, $gamav);
                    $gamarv = $gamav;
                } else {
                    throw new Exception("Emulzna struktura sa nedokazala vyratat");
                }
            }
            $gamak = $gamae;

        } else {
            throw new Exception("wzm sa nesmie rovnat wzmkr");
        }
    }

    $pgamak = pgamak($gamak, $gamav);
    $c1 = c1($pgamak, $Dt, $D0);
    $c2 = c2($pgamak, $Dt, $D0);

    if ($betav > 0) {
        $wzm = $Qv / ($Qv + $Qr);
    } else {
        if ($betav == 0) {
            $wzm = $Qr / $F;
        }
    }

    $FrZM = FrZM($wzm, $Dt);
    $lamdarC = lamdarC($betav, $c1, $c2, $FrZM);
    $K = K($Dt);

    if (isset($lambdar) and isset($lambdav)) {
        $roZM = rorv($rorpT, $lambdar, $rovL, $lambdav);
    } else {
        $roZM = rovr($rorpT, $betav, $rovL);
    }
    $rek = rek($wzm, $Dt, $roZM, $gamak);
    $lambdaC = lambdaC($rek, $K);
    $frZM = frZM($wzm, $Dt);
    $fiR = fiR($betav, $c1, $c2, $frZM);
    $rofiR = rofiR($roZM, $fiR, $roppT);
    $dpdHTR = dpdHTR($lambdaC, $wzm, $rofiR, $Dt);
    $dpdH = dpdH($roZM, $dpdHTR);
    $dHdp = array_push($dHdpCollection, dHdp($dpdH));

    $omegaT = omegaT($TL, $T, $h);
    $omegaST = omegaST($Qkst, $Dt, $omegaT);
    $TU = TU($TL, $omegaST, $h);
    $T = T($TU, $TL, $p, $pU, $pL);
    array_push($viskozita, $gamaropT);
    array_push($resultRor, $rorpT);
    array_push($resultBrpT, $brpT);
    if ($i > 0) {
        $posN = $i;
        $posO = $posN - 1;
        $p += 0.1;
        array_push($resultP, $p);
        $plusA = 0;
        if ($i > 1) {
            $plusA = array();
            for ($j = 1; $j <= $posO; $j++) {
                array_push($plusA, ($dHdpCollection[$j]));
            }
            $plusA = array_sum($plusA);
        }
        $H = ($resultP[$posN] - $resultP[$posO]) * ((($dHdpCollection[0] + $dHdpCollection[$posN]) / 2) + $plusA);
        array_push($resultH, $H);

    }
    $i++;
} // endwhile

$graf = [];
$grafRor = [];
$grafBrpT = [];
for ($i = 0; $i < count($resultP); $i++) {
    array_push($graf, [
        'y' => $resultP[$i],
        'x' => $viskozita[$i]
    ]);

    if (array_key_exists($i, $resultRor)) {
        array_push($grafRor, [
            'y' => $resultP[$i],
            'x' => $resultRor[$i]
        ]);
    }

    if (array_key_exists($i, $resultBrpT)) {
        array_push($grafBrpT, [
            'y' => $resultP[$i],
            'x' => $resultBrpT[$i]
        ]);
    }
}

