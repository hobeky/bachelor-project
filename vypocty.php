<?php
require_once('vzorce.php');
// Podielove mnozstva
$yi = [
    'metan' => 0.385,
    'etan' => 0.219,
    'propan' => 0.184,
    'ibutan' => 0.035,
    'nbutan' => 0.067,
    'ipentan' => 0.028,
    'npentan' => 0.017,
    'hexan' => 0.021,
    'CO2' => 0.005,
    'N' => 0.039
];
$mi = Mi();

$yimi = yiMi($yi, $mi);
$yi['celkove'] = array_sum($yi);

// Zadavane hodnoty
$p = 5;
$pU = 2;
$T = 313;
$h = 1100;
$TL = 400.5;
$pL = 17.5;
$rovp = 1089.9;
$rorp = 818.3;
$rorop = 868;
$Qkst = 150;
$pn = 9.2;
$gama = 55.6;
$cc = 150;
$prop = 1.119;
$prod = 0.970;
$rop0 = 1.447;
$p0 = 0.101325;
$Dt = 0.0635;
$nv = 0.2;
$alfap = 0.15;
$betavTst = 4.7 * 0.0001;
$odm = odm($Dt);
$Qpo = 150;
$D0 = 0.015;
$dHdpCollection = array();
$resultP = [$p];
$resultH = [0];
$viskozita = array();

$i = 0;
while ($i < 15) {
//while ($p <= $pL) {
    /**
     * 1. Zadanie
     * 2. Zadanie
     */
    $proup = proup($prop, $prod, $yi['N']);
    $pred = pred($p, $proup);
    $Tred = Tred($T, $proup);
    $zu = zu($pred, $Tred);
    $zd = zd($T, $p, $yi['N']);
    $z = z($zu, $yi['celkove'], $yi['N'], $zd);
    $roppT = roppT($rop0, $p, $z, $p0, $T);
    $pnt = pnT($pn, $TL, $T, $gama, $yi['metan'], $yi['N']);
    $Rp = Rp($p, $pnt);
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
    $a3 = a3($T);
    $u3 = u3($rorop, $gama);
    $propoppT = propoppT($a3, $prop, $Rp, $u3);
    $VpoppT = ($p > $pn) ? $VpoppT : 0;
    $VprpT = VprpT($gama, $mT, $VpoppT);
    $propLpT = propLpT($gama, $a3, $mT, $prop, $propoppT, $VpoppT, $VprpT);
    $lamdaT = lamdaT($rorop, $propLpT, $a3, $VprpT);
    $alfar = alfar($rorop);
    $brpT = brpT($rorop, $VprpT, $lamdaT, $mT, $alfar, $T, $p);
    $rorpT = rorpT($rorop, $propLpT, $VprpT, $a3, $mT, $brpT);
    $gamarop = gamarop($rorop);
    $VhvprpT = VhvprpT($alfar, $VprpT, $rorop);

    if ($p > $pn) {
        $gamaropT = gamaropT($gamarop, $T, $rorop);
        $gamarpT = gamarpT($VhvprpT, $gamaropT);
        $sigmarop = sigmarop($p, $T);
    }
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

    $rovL = rovL($rovst, (isset($bvL)) ? $bvL : $bv);
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
    $sigmarv = (isset($sigmarop)) ? sigmarv($sigmarv, $sigmarop) : $sigmavp;
    $betav = betav($Qv, $Qr);

    if ($wzm < $wzmkr) {
        // Kvapkova struktura
        // V/R
        if ($betav <= 0.5) {
            $wvred = wvred($Qv, $F);
            $lambdav = lambdav($wvred, $wzm, $odm, $sigmarv, $rovp, $rorp);
            $lambdar = lambdarOdLamdav($lambdav);
            $gamarv = $gamarL;
        } // R/V
        else {
            $wrred = wrred($Qr, $F);
            $lambdar = lambdar($sigmarv, $rovp, $rorp, $wrred, $wzm, $betav, $odm);
            $lambdav = lambdavOdLamdar($lamdar);
            $gamarv = $gamav;
        }
        $gamak = $gamarv;
        // Koniec Kvapkovej struktury
    } else if ($wzm > $wzmkr) {
        // Emulzna struktura
        $wekr = wekr($betav, $odm);
        if ($wzm > $wekr and $betav <= 0.5) {
            // V/R
            $wzd = wzd($wzm, $Dt);
            $A6 = A6($betav, $wzd);
            $B6 = B6($A6, $gamarN);
            $gamae = gamaeVR($B6, $betav);
            $gamarv = $gamarL;
        } else if (($wzm < $wekr and $betav <= 0.5) or $betav > 0.5) {
            // R/V
            $gamae = gamaeRV($betav, $gamav);
            $gamarv = $gamav;
        } else {
            throw new Exception("Emulzna struktura sa nedokazala vyratat");
        }
        $gamak = $gamae;
    } else {
        throw new Exception("wzm sa nesmie rovnat wzmkr");
    }

    $pgamak = pgamak($gamak, $gamav);
    $c1 = c1($pgamak, $Dt, $D0);
    $c2 = c2($pgamak, $Dt, $D0);

    if ($betav > 0)
        $wzm = $Qv / ($Qv + $Qr);
    else if ($betav == 0)
        $wzm = $Qr / $F;

    $FrZM = FrZM($wzm, $Dt);
    $lamdarC = lamdarC($betav, $c1, $c2, $FrZM);
    $K = K($Dt);

    if (isset($lambdar) and isset($lambdav)) {
        $roZM = rorv($rorp, $lambdar, $rovp, $lambdav);
    } else {
        $roZM = rovr($rorp, $betav, $rovp);
    }
    $rek = rek($wzm, $Dt, $roZM, $gamak);
    $lambdaC = lambdaC($rek, $K);
    $dpdHTR = dpdHTR($lambdaC, $wzm, $roZM, $Dt);
    $dpdH = dpdH($roZM, $dpdHTR);
    $dHdp = array_push($dHdpCollection, dHdp($dpdH));

    $omegaT = omegaT($TL, $T, $h);
    $omegaST = omegaST($Qpo, $Dt, $omegaT);
    $TU = TU($TL, $omegaST, $h);
    $T = T($TU, $TL, $p, $pU); // todo: zatial vychadza 1

    array_push($viskozita, $gamak);
    if ($i > 0) {
        $posN = $i;
        $posO = $posN - 1;
        $p += 0.01;
        array_push($resultP, $p);
        $plusA = 0;
        if ($i > 1) {
            $plusA = array();
            for ($j = 1; $j <= $posO; $j++) {
                array_push($plusA, $dHdpCollection[$j]);
            }
            $plusA = array_sum($plusA);
        }
        $H = ($resultP[$posN] - $resultP[$posO]) * ((($dHdpCollection[0] + $dHdpCollection[$posN]) / 2) + $plusA);
        array_push($resultH, $H);
    }
    $i++;
} // endwhile

$graf = array();
for ($i = 0; $i < count($resultH); $i++) {
    array_push($graf, [
        'y' => $resultH[$i],
        'x' => $viskozita[$i]
    ]);
}
