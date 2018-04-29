<?php
    try { $dbh = new PDO('sqlite:games.sqlite'); }
    catch(PDOException $e) { fail(); }
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $score_fields = array('military', 'gold', 'wonder', 'culture', 'trade', 'guilds', 'science');

    if (filesize('games.sqlite') == 0) {
        $dbh->exec(
            "CREATE TABLE games(
                gameid     INTEGER NOT NULL,
                playername TEXT    NOT NULL,
                wondername TEXT    NOT NULL,
                wonderside TEXT    NOT NULL,
                {$score_fields[0]} INTEGER,
                {$score_fields[1]} INTEGER,
                {$score_fields[2]} INTEGER,
                {$score_fields[3]} INTEGER,
                {$score_fields[4]} INTEGER,
                {$score_fields[5]} INTEGER,
                {$score_fields[6]} INTEGER
            );"
        );
    }

    function calculate_score($row) {
        $total_score = 0;
        for ($i = 4; $i < 11; $i++) {
            $total_score += $row[$i];
        }
        return $total_score;
    }

    function all_player_names() {
        global $dbh;

        $names = array();
        foreach ($dbh->query('SELECT DISTINCT playername FROM games') as $row) {
            $names[] = $row['playername'];
        }

        return $names;
    }

    function all_player_scores($player) {
        global $dbh;

        $scores = array();
        foreach ($dbh->query("SELECT * FROM games WHERE playername = '$player'") as $row) {
            $scores[] = calculate_score($row);
        }

        return $scores;
    }

    function player_high_score($player) {
        return max(all_player_scores($player));
    }

    function player_average_score($player) {
        $scores = all_player_scores($player);
        return array_sum($scores) / count($scores);
    }

    function player_component_high_score($player) {
        global $dbh;
        global $score_fields;

        $component_scores = array();
        foreach ($score_fields as $field) {
            $max = -7;
            foreach ($dbh->query("SELECT MAX($field) as $field FROM games WHERE playername = '$player'") as $score) {
                $max = max($max, $score[$field]);
            }

            $component_scores[$field] = $max;
        }

        return $component_scores;
    }

    function player_games_played($player) {
        global $dbh;

        foreach ($dbh->query("SELECT COUNT(gameid) as count FROM games WHERE playername = '$player'") as $row) {
            return $row['count'];
        }
    }

    function player_victories($player) {
        global $dbh;

        $player_victories = 0;
        foreach ($dbh->query("SELECT gameid FROM games WHERE playername = '$player'") as $id) {
            $scores = array();
            foreach ($dbh->query("SELECT * FROM games WHERE gameid = {$id['gameid']}") as $row) {
                $scores[$row['playername']] = calculate_score($row);
            }

            if (max($scores) == $scores[$player]) {
                $player_victories++;
            }
        }

        return $player_victories;
    }

    function player_all_played_wonders($player) {
        global $dbh;

        $wonder_counts = array();
        foreach ($dbh->query("SELECT wondername, COUNT(wondername) as count FROM games WHERE playername = '$player' GROUP BY wondername") as $row) {
            $wonder_counts[$row['wondername']] = $row['count'];
        }

        return $wonder_counts;
    }

    function player_most_played_wonder($player) {
        $wonder_counts = player_all_played_wonders($player);
        if (count($wonder_counts) > 1) {
            unset($wonder_counts['Unknown']);
        }

        $max = array_keys($wonder_counts, max($wonder_counts));
        return $max[0];
    }

    function fail($location = 'index.php') {
        header("Location: $location");
        exit();
    }

    function make_header() {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <title>7 Wonders Statistics</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
<?php
    }

    function make_footer() {
?>
    </body>
</html>
<?php
    }

    function make_entry_header() {
        global $score_fields;
?>
                <tr>
                    <th>Player</th>
                    <th>Wonder</th>
                    <th>Side</th>
<?php
        foreach ($score_fields as $sf) {
?>
                    <th><?= ucfirst($sf) ?></th>
<?php
        }
?>
                </tr>
<?php
    }

    function make_view_header() {
        global $score_fields;
?>
            <tr>
                <th>Player</th>
                <th>Wonder</th>
                <th>Side</th>
                <?php
        foreach ($score_fields as $sf) {
?>
                <th><?= ucfirst($sf) ?></th>
<?php
        }
?>
                <th>Total</th>
            </tr>
<?php
    }
?>
