<?php
session_start();
$_SESSION['page']='mitglied';
$_SESSION['adminpage']=true;
include "common/header.php";
requireAdmin();

if($_SESSION['admin']) {
    $sql = sprintf('SELECT COUNT(`Index`) AS `Count` FROM `%sUser` WHERE `Mitglied` = 1 AND `Instrument` > 0;',
    $GLOBALS['dbprefix']
    );
$dbr = mysqli_query($conn, $sql);
sqlerror();
$row = mysqli_fetch_array($dbr);
$nMusiker = $row['Count'];
?>
<div class="w3-container <?php echo $GLOBALS['optionsDB']['colorTitleBar']; ?>">
    <h2>Liste aller aktiven Mitglieder (<?php echo $nMusiker; ?>)</h2>
</div>
<?php
$sql = sprintf('SELECT `Index` FROM `%sUser` WHERE `Mitglied` = 1 AND `Instrument` > 0 AND `Deleted` != 1 ORDER BY `Nachname`, `Vorname`;',
$GLOBALS['dbprefix']
);
$dbr = mysqli_query($conn, $sql);
sqlerror();
while($row = mysqli_fetch_array($dbr)) {
    $M = new User;
    $M->load_by_id($row['Index']);
    $M->printTableLine();
}
?>
<?php }
else {
?>
    <meta http-equiv="refresh" content="0; URL=index.php" />
<?php
}
include "common/footer.php";
?>
