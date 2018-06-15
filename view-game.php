<?php
    include 'utils.php';
    make_header();
    initialise_tables(false);

    if (isset($_GET['id'])) {
        $query = $dbh->prepare('SELECT * FROM games WHERE gameid = :id');
        $query->bindParam('id', $_GET['id']);
?>
        <table class="display compact">
<?php
        make_view_header();
?>
            <tbody>
<?php
        $query->execute();
        foreach ($query->fetchAll() as $row) {
?>
                <tr>
                    <td><?= $row['playername'] ?></td>
                    <td><?= $row['wondername']?></td>
                    <td><?= $row['wonderside']?></td>
<?php
    foreach ($score_fields as $field) {
?>
                    <td><?= $row[$field] ?></td>
<?php
    }
?>
                    <td><b><?= calculate_score($row) ?></b></td>
                </tr>
<?php
        }
?>
            </tbody>
        </table>
<?php
    }

    make_footer();
?>
