<?php
    include 'utils.php';

    make_header();

    $valid = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $valid = true;
        $values = array();

        $maxq = $dbh->query('SELECT MAX(gameid) as gameid FROM games');
        foreach ($maxq as $val) {
            $gameid = isset($val['gameid']) ? $val['gameid'] + 1 : 1;
        }

        $insert = $dbh->prepare("INSERT INTO games
                                (
                                    gameid,
                                    playername,
                                    wondername,
                                    wonderside,
                                    {$score_fields[0]},
                                    {$score_fields[1]},
                                    {$score_fields[2]},
                                    {$score_fields[3]},
                                    {$score_fields[4]},
                                    {$score_fields[5]},
                                    {$score_fields[6]}
                                )
                                values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                    );

        for ($i = 0; $i < 7; $i++) {
            $valuecount = 0;
            for ($j = 0; $j < 10; $j++) {
                if ($_POST["value$i$j"] !== '') {
                    $valuecount++;
                    array_push($values, $_POST["value$i$j"]);
                }
            }

            if (!($valuecount == 0 || $valuecount == 10)) {
                $valid = false;
?>
        Row <?= ($i + 1) ?> is only partially completed.<br />
<?php
            } else if ($valuecount == 10) {
                array_splice($values, count($values) - 10, 0, $gameid);
            }
        }

        if ($valid) {
            for ($i = 0; $i < (int)(count($values) / 11); $i++) {
                $insert->execute(array_slice($values, $i * 11, 11));
            }
        }
    }
?>
        <form method="post">
            <table>
<?php
    make_entry_header();
    for($i = 0; $i < 7; $i++) {
?>
                <tr>
                    <td><input name="<?= "value{$i}0" ?>" type="text" value="<?= isset($_POST["value{$i}0"]) && !$valid ? htmlspecialchars($_POST["value{$i}0"]) : '' ?>"></td>
                    <td>
                        <select name="<?= "value{$i}1" ?>" id="<?= "value{$i}1" ?>">
                            <option selected></option>
                            <option value="Unknown">Unknown</option>
                            <option value="Alexandria">Alexandria</option>
                            <option value="Babylon">Babylon</option>
                            <option value="Colossus">Colossus</option>
                            <option value="Ephesos">Ephesos</option>
                            <option value="Gizah">Gizah</option>
                            <option value="Halikarnāssós">Halikarnāssós</option>
                            <option value="The Great Wall">The Great Wall</option>
                            <option value="Manneken Pis">Manneken Pis</option>
                            <option value="Olympia">Olympia</option>
                            <option value="Stonehenge">Stonehenge</option>
                        </select>
                    </td>
                    <td>
                        <select name="<?= "value{$i}2" ?>" id="<?= "value{$i}2" ?>">
                            <option selected></option>
                            <option value="Unknown">Unknown</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </td>
<?php
        for($j = 3; $j < 10; $j++) {
?>
                    <td><input name="<?= "value$i$j" ?>" type="number"<?= $j > 3 ? ' min="0"' : '' ?> style="width:100px" value="<?= isset($_POST["value$i$j"]) && !$valid ? htmlspecialchars($_POST["value$i$j"]) : '' ?>"></td>
<?php
        }
?>
                </tr>
<?php
    }
?>
            </table>
            <input id="add-button" type="submit" value="Add">
        </form>

        <script type="text/javascript">
<?php
            for ($i = 0; $i < 7; $i++) {
?>
            document.getElementById("<?= "value{$i}1" ?>").value = "<?= isset($_POST["value{$i}1"]) && !$valid ? htmlspecialchars($_POST["value{$i}1"]) : '' ?>";
            document.getElementById("<?= "value{$i}2" ?>").value = "<?= isset($_POST["value{$i}2"]) && !$valid ? htmlspecialchars($_POST["value{$i}2"]) : '' ?>";
<?php
            }
?>
        </script>
<?php
    make_footer();
?>
