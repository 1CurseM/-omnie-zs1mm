<?php
// Wyniki zawodów biegowych. Czasy okrążeń w sekundach, po jednym na ukończone okrążenie.
$zawodnicy = [
    ['nazwisko' => 'Anna Kowalska',    'okrazenia' => [312, 298, 305]],
    ['nazwisko' => 'Piotr Nowak',      'okrazenia' => [355, 430, 341]],
    ['nazwisko' => 'Marek Zieliński',  'okrazenia' => []],
    ['nazwisko' => 'Ewa Wiśniewska',   'okrazenia' => [402, 377, 395]],
];
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
    .elita        { background: #d4f5d4; }
    .zaawansowany { background: #fff3c4; }
    .amator       { background: #f5d4d4; }
</style>
</head>
<body>
<h1>Zawody biegowe: czasy okrążeń</h1>
<table>
    <caption>Wyniki zawodników</caption>
    <thead>
        <tr>
            <th scope="col">Zawodnik</th>
            <th scope="col">Okrążenia</th>
            <th scope="col">Najlepsze</th>
            <th scope="col">Średnie</th>
            <th scope="col">Kategoria</th>  
            <th scope="col">Uwagi</th>
        </tr>
    </thead>
<tbody>
    <?php foreach ($zawodnicy as $dane): ?>

        <?php
        if (empty($dane['okrazenia'])) {
            $kategoria = '';
        } else {
            $najlepszy = min($dane['okrazenia']);

            if ($najlepszy < 300) {
                $kategoria = 'elita';
            } elseif ($najlepszy < 360) {
                $kategoria = 'zaawansowany';
            } else {
                $kategoria = 'amator';
            }
        }
        ?>

        <tr class="<?php echo $kategoria; ?>">
            <td><?php echo $dane['nazwisko']; ?></td>

            <td>
                <?php
                if (empty($dane['okrazenia'])) {
                    echo 'brak ukończonych okrążeń';
                } else {
                    $czasy = [];

                    foreach ($dane['okrazenia'] as $czas) {
                        $minuty = floor($czas / 60);
                        $sekundy = $czas % 60;

                        $czasy[] = $minuty . ':' . str_pad($sekundy, 2, '0', STR_PAD_LEFT);
                    }

                    echo implode(', ', $czasy);
                }
                ?>
            </td>

            <td>
                <?php
                if (empty($dane['okrazenia'])) {
                    echo 'brak';
                } else {
                    $najlepszy = min($dane['okrazenia']);

                    $minuty = floor($najlepszy / 60);
                    $sekundy = $najlepszy % 60;

                    echo $minuty . ':' . str_pad($sekundy, 2, '0', STR_PAD_LEFT);
                }
                ?>
            </td>

            <td>
                <?php
                if (empty($dane['okrazenia'])) {
                    echo 'brak';
                } else {
                    $srednia = round(array_sum($dane['okrazenia']) / count($dane['okrazenia']));

                    $minuty = floor($srednia / 60);
                    $sekundy = $srednia % 60;

                    echo $minuty . ':' . str_pad($sekundy, 2, '0', STR_PAD_LEFT);
                }
                ?>
            </td>

            <td>
                <?php
                if (empty($dane['okrazenia'])) {
                    echo 'brak';
                } else {
                    echo $kategoria;
                }
                ?>
            </td>

            <td>
                <?php
                if (empty($dane['okrazenia'])) {
                    echo 'brak ukończonych okrążeń';
                } else {
                    $najlepszy = min($dane['okrazenia']);
                    $slabe = false;
                    $rowne = true;

                    foreach ($dane['okrazenia'] as $czas) {
                        if ($czas >= 420) {
                            $slabe = true;
                        }

                        if ($czas > $najlepszy + 15) {
                            $rowne = false;
                        }
                    }

                    if ($slabe) {
                        echo 'słabe okrążenie';
                    } elseif ($rowne) {
                        echo 'równe tempo';
                    }
                }
                ?>
            </td>
        </tr>

    <?php endforeach; ?>
</tbody>


<tfoot>
    <tr>
        <td colspan="6">
            <?php
            $liczbaZawodnikow = count($zawodnicy);
            $liczbaElita = 0;
            $najlepszeOkrążenie = null;
            $liczbaOkrążeń = 0;

            foreach ($zawodnicy as $dane) {
                if (!empty($dane['okrazenia'])) {
                    $liczbaOkrążeń += count($dane['okrazenia']);

                    $najlepszy = min($dane['okrazenia']);

                    if ($najlepszeOkrążenie === null || $najlepszy < $najlepszeOkrążenie) {
                        $najlepszeOkrążenie = $najlepszy;
                    }

                    if ($najlepszy < 300) {
                        $liczbaElita++;
                    }
                }
            }

            $minuty = floor($najlepszeOkrążenie / 60);
            $sekundy = $najlepszeOkrążenie % 60;
            $najlepszeOkrążenie = $minuty . ':' . str_pad($sekundy, 2, '0', STR_PAD_LEFT);
            ?>

            Zawodników łącznie: <?php echo $liczbaZawodnikow; ?> |
            Elita: <?php echo $liczbaElita; ?> |
            Najlepsze okrążenie zawodów: <?php echo $najlepszeOkrążenie; ?> |
            Okrążeń łącznie: <?php echo $liczbaOkrążeń; ?>
        </td>
    </tr>
</tfoot>

</table>
</body>
</html>
