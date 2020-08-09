<!DOCTYPE html>
<html>
    <head>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
<style>
body {
    font-family: 'Roboto';font-size: 22px;
}
</style>
    </head>
<body>
<?php

class Tr34
{

    const C25_30 = array('fck' => 25, 'fcu' => 30, 'fcm' => 33, 'fctm' => 2.6, 'Ecm' => 31000);
    const C28_35 = array('fck' => 28, 'fcu' => 35, 'fcm' => 36, 'fctm' => 2.8, 'Ecm' => 32000);
    const C30_37 = array('fck' => 30, 'fcu' => 37, 'fcm' => 38, 'fctm' => 2.9, 'Ecm' => 33000);
    const C32_40 = array('fck' => 32, 'fcu' => 40, 'fcm' => 40, 'fctm' => 3.0, 'Ecm' => 33000);
    const C35_45 = array('fck' => 35, 'fcu' => 45, 'fcm' => 43, 'fctm' => 3.2, 'Ecm' => 34000);
    const C40_50 = array('fck' => 40, 'fcu' => 50, 'fcm' => 48, 'fctm' => 3.5, 'Ecm' => 35000);

    public $concretes = array(
        'a' => Tr34::C25_30, 
        'b' => Tr34::C28_35,
        'c' => Tr34::C30_37,
        'd' => Tr34::C32_40,
        'e' => Tr34::C35_45,
        'f' => Tr34::C40_50
    );

    function calculate($conc, $h, $fyk, $As, $bar_dia, $cover, $Qk, $tyre_area, $k30, $N, $r)
    {

        $yc = 1.5;
        $ys = 1.15;
        $yQ = 1.5;
        $poisson = 0.2;
        $k30 = $k30 / 100;

        $d = ($h - $cover - $bar_dia); // Effective depth of reinforcement
        $li = sqrt($tyre_area); // Length of loaded area
        $lw = sqrt($tyre_area); // Width of loaded area
        $u0 = 2 * ($li + $lw); // Length of perimeter at face of load
        $a = sqrt((($li * $lw) / pi())); // Equivalent contact radius of single load
        $u1 = 2 * ($li + $lw + (2 * $d * pi())); // Length of perimeter at 2d from face of load

        // For slabs thinner than 600mm flexural tensile strength is:
        // fctd_fl = fctm * (1.6 – h/1000)/γm
        $fctd_fl = (3.0 * (1.6 - ($h / 1000)) / 1.5);

        // The moment capacity of plain concrete per unit width of slab is (hogging moment):
        // Mun = fctd_fl*(h^2 / 6)
        $Mun = ($fctd_fl * (pow($h, 2) / 6)) / 1000;

        // Moment capacity Mpfab of steel fabric reinforced concrete (sagging moment) per unit width of slab is calculated
        // from: Mpfab = 0.95 * As * fyk * d / ym
        $Mpfab = (0.95 * $As * $fyk * $d / $ys) / (1000 * 1000); // 1000*1000 converts from Nmm to kNm

        // Shear at the face of the loaded area
        $fcd = $conc['fck'] / $yc;
        $k2 = 0.6 * (1 - ($conc['fck'] / 250));
        $vmax = 0.5 * $k2 * $fcd;
        $Ppmax = ($vmax * $u0 * $d) / 1000;

        # Shear on the critical perimeter (unreinforced)
        $ks = 1 + pow((200 / $d), 0.5);
        $vrdc = 0.035 * pow($ks, 1.5) * pow($conc['fck'], 0.5);

        if ($r == 'r') {

            // Shear on the critical perimeter (reinforced)
            $rhoX = $As / (1000 * $d);
            $rhoY = $As / (1000 * $d);
            $rho1 = sqrt($rhoX * $rhoY);
            $ks = 1 + pow((200 / $d), 0.5);
            if ($ks > 2) {
                $ks = 2;
            } else {
                $ks = $ks;
            }
            $vrdc = (0.18 * $ks) * pow((100 * $rho1 * $conc['fck']), 0.33);
            $vrdc_min = 0.035 * pow($ks, 1.5) * pow($conc['fck'], 0.5);
            if ($vrdc >= $vrdc_min) {
                echo "<h6 style=color:green;>>>>    vrdc OK</h6>";
            } else {
                echo "<h6 style=color:red;>>>>    vrdc FAIL</h6>";
            }
            echo "<h6 style=color:blue;>>>>    *** REINFORCED ***";

        } elseif ($r == 'ur') {

            // Shear on the critical perimeter (unreinforced)
            $ks = 1 + pow((200 / $d), 0.5);
            $vrdc = 0.035 * pow($ks, 1.5) * pow($conc['fck'], 0.5);
            echo "<h6 style=color:brown;>>>>    *** UN-REINFORCED ***</h6>";
        }

        // Shear capacity critical perimeter
        $Pp = $vrdc * $u1 * $d / 1000;

        $top = $conc['Ecm'] * pow($h, 3);
        $bottom = 12 * (1 - pow($poisson, 2)) * $k30;
        $combined = $top / $bottom;
        $l = pow($combined, 0.25);
        echo "<h6>>>>    l = " . number_format($l, 2) . " mm</h6>";
        $rr = $a / $l;

        if ($r == 'r') {
            $Pu = (4 * pi() * ($Mpfab + $Mun)) / (1 - ($a / (3 * $l)));
        } elseif ($r == 'ur') {
            $Pu = (4 * pi() * ($Mun + $Mun)) / (1 - ($a / (3 * $l)));
        }

        // Ultimate limit state
        $Fuls = $N * $Qk * $yQ;

        echo "<h6>>>>    a/l > 0.2 = " . number_format($rr, 2) . "</h6>";
        echo "<h6>>>>    Fuls = " . number_format($Fuls, 2) . " kN</h6>";
        echo "<h6>>>>    Pu (ult. capacity single conc. internal load) = " . number_format($Pu, 2) . " kN</h6>";
        echo "<h6>>>>    PPmax (punching shear capacity) = " . number_format($Ppmax, 2) . " kN</h6>";
        echo "<h6>>>>    Pp (shear capacity critical perimeter) = " . number_format($Pp, 2) . " kN</h6>";
        echo "<h6>>>>    Mpfab (Moment capacity steel fabric reinforced) = " . number_format($Mpfab, 2) . " kNm/m</h6>";
        echo "<h6>>>>    Mun (Moment capacity un-reinforced) = " . number_format($Mun, 2) . " kNm/m</h6>";
        
        if (($Fuls < $Ppmax) && ($Fuls < $Pp) && ($Fuls < $Pu)) {
            echo "<h6 style=color:green;>>>>    *** PASS ***</h6>";
        } else {
            echo "<h6 style=color:red;>>>>    *** FAIL ***</h6>";
        }

    }

}

$example = new Tr34();

$example->calculate(
    $conc = $example->concretes[$_POST['conc']],
    $h = $_POST['h'],
    $fyk = $_POST['fyk'],
    $As = $_POST['As'],
    $bar_dia = $_POST['bar_dia'],
    $cover = $_POST['cover'],
    $Qk = $_POST['Qk'],
    $tyre_area = $_POST['tyre_area'],
    $k30 = $_POST['k30'],
    $N = $_POST['N'],
    $r = $_POST['r']
);

?>
    
</body>

</html>
