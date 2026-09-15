<?php
$zawodnicy = [
    ['nazwisko' => 'Anna Kowalska',   'okrazenia' => [312, 298, 305]],
    ['nazwisko' => 'Piotr Nowak',     'okrazenia' => [355, 430, 341]],
    ['nazwisko' => 'Marek Zieliński', 'okrazenia' => []],
    ['nazwisko' => 'Ewa Wiśniewska',  'okrazenia' => [402, 377, 395]],
];

function formatujCzas($sekundy, $separator = ':') {
    return floor($sekundy / 60) . $separator . str_pad($sekundy % 60, 2, '0', STR_PAD_LEFT);
}

function kategoria($okrazenia) {
    if (empty($okrazenia)) return '';
    $najlepszy = min($okrazenia);
    if ($najlepszy < 300) return 'elita';
    if ($najlepszy < 360) return 'zaawansowany';
    return 'amator';
}

function uwagi($okrazenia) {
    if (empty($okrazenia)) return ['brak ukończonych okrążeń'];

    $najlepszy = min($okrazenia);
    $slabe = false;
    $rowne = true;

    foreach ($okrazenia as $czas) {
        if ($czas >= 420) $slabe = true;
        if ($czas > $najlepszy + 15) $rowne = false;
    }

    $uwagi = [];
    if ($slabe) $uwagi[] = 'słabe okrążenie';
    if ($rowne) $uwagi[] = 'równe tempo';

    return $uwagi;
}

function srednia($okrazenia) {
    return round(array_sum($okrazenia) / count($okrazenia));
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Wyniki zawodów</title>
<style>
    body { font-family: sans-serif; margin: 2rem; }
    table { border-collapse: collapse; }
    th, td { border: 1px solid #999; padding: 0.4rem 0.8rem; text-align: left; }
    caption { font-weight: bold; margin-bottom: 0.5rem; }
    tfoot td { font-weight: bold; }
    .elita { background: #d4f5d4; }
    .zaawansowany { background: #fff3c4; }
    .amator { background: #f5d4d4; }
</style>
</head>
<body>

<h1>Zawody biegowe: czasy okrążeń</h1>

<table>
<caption>Wyniki zawodników</caption>

<thead>
<tr>
    <th>Zawodnik</th>
    <th>Okrążenia</th>
    <th>Najlepsze</th>
    <th>Średnie</th>
    <th>Kategoria</th>
    <th>Uwagi</th>
</tr>
</thead>

<tbody>
<?php foreach ($zawodnicy as $dane): ?>
<?php
    $okrazenia = $dane['okrazenia'];
    $kat = kategoria($okrazenia);
?>

<tr class="<?php echo $kat; ?>">

    <td><?php echo $dane['nazwisko']; ?></td>

    <td>
        <?php
        if (empty($okrazenia)) {
            echo 'brak ukończonych okrążeń';
        } else {
            $czasy = [];
            foreach ($okrazenia as $czas) {
                $czasy[] = formatujCzas($czas);
            }
            echo implode(', ', $czasy);
        }
        ?>
    </td>

    <td>
        <?php echo empty($okrazenia) ? 'brak' : formatujCzas(min($okrazenia)); ?>
    </td>

    <td>
        <?php echo empty($okrazenia) ? 'brak' : formatujCzas(srednia($okrazenia)); ?>
    </td>

    <td>
        <?php echo empty($okrazenia) ? 'brak' : $kat; ?>
    </td>

    <td>
        <?php echo implode(', ', uwagi($okrazenia)); ?>
    </td>

</tr>
<?php endforeach; ?>
</tbody>

<tfoot>
<tr>
<td colspan="6">
<?php
$liczbaElita = 0;
$najlepszeOkrążenie = null;
$liczbaOkrążeń = 0;

foreach ($zawodnicy as $dane) {
    $okrazenia = $dane['okrazenia'];

    if (!empty($okrazenia)) {
        $liczbaOkrążeń += count($okrazenia);
        $najlepszy = min($okrazenia);

        if ($najlepszeOkrążenie === null || $najlepszy < $najlepszeOkrążenie) {
            $najlepszeOkrążenie = $najlepszy;
        }

        if (kategoria($okrazenia) == 'elita') {
            $liczbaElita++;
        }
    }
}
?>

Zawodników łącznie: <?php echo count($zawodnicy); ?> |
Elita: <?php echo $liczbaElita; ?> |
Najlepsze okrążenie zawodów: <?php echo formatujCzas($najlepszeOkrążenie); ?> |
Okrążeń łącznie: <?php echo $liczbaOkrążeń; ?>

</td>
</tr>
</tfoot>

</table>
</body>
</html>
