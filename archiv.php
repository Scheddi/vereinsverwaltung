<?php
session_start();
$_SESSION['page']='archiv';
$_SESSION['adminpage']=true;
include "common/header.php";
requireAdmin();

?>
<div class="w3-container <?php echo $GLOBALS['optionsDB']['colorTitleBar']; ?>">
  <h2>Rückmeldungen - Archiv</h2>
</div>
<div class="w3-row">
  <div class="w3-panel w3-col l3 w3-left"></div>
  <div class="w3-col l6 s12 m12">
<?php
$now = date("Y-m-d");
$sql = sprintf('SELECT `Index` FROM `%sTermine` WHERE `Datum` <= "%s" ORDER BY `Datum` DESC, `Uhrzeit`;',
$GLOBALS['dbprefix'],
$now
);
$dbr = mysqli_query($conn, $sql);
sqlerror();
while($row = mysqli_fetch_array($dbr)) {
    $M = new Termin;
    $M->load_by_id($row['Index']);
    echo $M->printResponseLine();
}
?>
  </div>
  <div class="w3-col l3"></div>
</div>
<?php
include "common/footer.php";
?>