<?php
session_start();
$_SESSION['page']='register';
include "common/header.php";
if($_SESSION['admin']) {
?>
<div class="w3-container <?php echo $GLOBALS['commonColors']['titlebar']; ?>">
    <h2>Registerübersicht</h2>
</div>
<?php
$sql = 'SELECT `Index` FROM `Register` ORDER BY `Sortierung`;';
$dbr = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($dbr)) {
    $M = new Register;
    $M->load_by_id($row['Index']);
    $M->memberTable();
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