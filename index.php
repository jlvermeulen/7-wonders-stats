<?php
    include 'utils.php';
    make_header();

?>
        <h2>Player overview</h2>
        <table>
            <tr><th style="width:100px">Player</th><th>Games played</th><th>Victories</th><th>Win ratio</th><th>Average score</th><th>Most played wonder</th></tr>
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
        </table>

        <h2>Highest scores</h2>
        <table>
            <tr>
                <th style="width:100px">Player</th>
                <th>Total</th>
<?php
            foreach ($score_fields as $field) {
?>
                <th><?= ucfirst($field) ?></th>
<?php
            }
?>
            </tr>
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
        </table>
<?php
    make_footer();
?>
