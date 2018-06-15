<?php
    include 'utils.php';
    make_header();
    initialise_tables();
?>
        <h2>Player overview</h2>
        <table class="display compact">
            <thead><tr><th>Player</th><th>Games played</th><th>Victories</th><th>Win ratio</th><th>Average score</th><th>Most played wonder</th></tr></thead>
            <tbody>
<?php
    foreach (all_player_names() as $name) {
?>

                <tr>
                    <td><?= $name ?></td>
                    <td><?= player_games_played($name) ?></td>
                    <td><?= player_victories($name) ?></td>
                    <td><?= round(player_victories($name) / player_games_played($name), 2) ?></td>
                    <td><?= round(player_average_score($name), 2) ?></td>
                    <td><?= player_most_played_wonder($name) ?></td>
                </tr>
<?php
    }
?>
            </tbody>
        </table>

        <h2>Highest scores</h2>
        <table class="display compact">
            <thead>
                <tr>
                    <th>Player</th>
                    <th>Total</th>
<?php
    foreach ($score_fields as $field) {
?>
                    <th><?= ucfirst($field) ?></th>
<?php
    }
?>
                </tr>
            </thead>
            <tbody>
<?php
    foreach (all_player_names() as $name) {
?>
                <tr>
                    <td><?= $name ?></td>
                    <td><?= player_high_score($name) ?></td>
<?php
        foreach (player_component_high_score($name) as $score) {
?>
                    <td><?= $score ?></td>
<?php
        }
?>
                </tr>
<?php
    }
?>
            </tbody>
        </table>

        <h2>Averages by wonder</h2>
        <table class="display compact">
            <thead>
                <tr>
                    <th>Wonder</th>
                    <th>Side</th>
                    <th>Times played</th>
                    <th>Total</th>
<?php
    foreach ($score_fields as $field) {
?>
                    <th><?= ucfirst($field) ?></th>
<?php
    }
?>
                </tr>
            </thead>
            <tbody>
<?php
    foreach (all_wonder_names() as $wonder) {
        foreach (array('A', 'B') as $side) {
            $scores = wonder_side_average_scores($wonder, $side);
?>
                <tr>
                    <td><?= $wonder ?></td>
                    <td><?= $side ?></td>
                    <td><?= $scores['count'] ?></td>
                    <td><?= round($scores['total'], 2) ?></td>
<?php
            foreach ($score_fields as $field) {
?>
                    <td><?= round($scores[$field], 2) ?></td>
<?php
            }
?>
                </tr>
<?php
        }
    }
?>
            </tbody>
        </table>
<?php
    make_footer();
?>
