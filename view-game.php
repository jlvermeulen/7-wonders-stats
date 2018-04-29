<?php
    include 'utils.php';
    make_header();

    if (isset($_GET['id'])) {
        $query = $dbh->prepare('SELECT * FROM games WHERE gameid = :id');
        $query->bindParam('id', $_GET['id']);
?>
        <table>
<?php
        make_view_header();
        $query->execute();
        foreach ($query->fetchAll() as $row) {
?>
            <tr>
                <td style="width:150px"><?= $row['playername'] ?></td>
                <td style="width:150px"><?= $row['wondername']?></td>
                <td><?= $row['wonderside']?></td>
                <td><?= $row['military']?></td>
                <td><?= $row['gold']?></td>
                <td><?= $row['wonder']?></td>
                <td><?= $row['culture']?></td>
                <td><?= $row['trade']?></td>
                <td><?= $row['guilds']?></td>
                <td><?= $row['science']?></td>
                <td><b><?= calculate_score($row) ?></b></td>
            </tr>
<?php
        }
?>
        </table>
<?php
    }

    make_footer();
?>