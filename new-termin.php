<?php
session_start();
$_SESSION['page']='newtermin';
include "common/header.php";
requireAdmin();
$fill = false;
if(isset($_POST['id'])) {
    $n = new Termin;
    $n->load_by_id($_POST['id']);
    if($n->Index > 0) {
        $fill = true;
    }
}
?>
<div class="w3-container <?php echo $GLOBALS['optionsDB']['colorTitleBar']; ?>">
    <h2>neuen Termin erstellen</h2>
</div>
<div class="w3-panel w3-mobile w3-center w3-col s3 l4">
</div>
<div class="w3-panel w3-mobile w3-center w3-border w3-col s6 l4">
<form class="w3-container w3-margin" action="termine.php" method="POST">
    <label>Datum</label>
    <input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Datum" type="date" <?php if($fill) echo "value=\"".$n->Datum."\""; ?>>
    <label>Beginn (optional) <b onclick="clearInput('Uhrzeit')">&#10006;</b></label>
    <input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Uhrzeit" type="time" <?php if($fill) echo "value=\"".$n->Uhrzeit."\""; ?>>
    <label>Ende (optional) <b onclick="clearInput('Uhrzeit2')">&#10006;</b></label>
    <input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Uhrzeit2" type="time" <?php if($fill) echo "value=\"".$n->Uhrzeit2."\""; ?>>
<?php
if($GLOBALS['optionsDB']['showVehicle'] || $GLOBALS['optionsDB']['showTravelTime']) {
?>
    <label>Abfahrt</label>
<?php
}
if($GLOBALS['optionsDB']['showVehicle']) {
?>
<select class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Vehicle">
      <?php
  if($fill) {
    VehicleOption($n->Vehicle);
  }
  else {
    VehicleOption(0);
  }
?>
    </select>
<?php
}
if($GLOBALS['optionsDB']['showTravelTime']) {
?>
<input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Abfahrt" type="time" <?php if($fill) echo "value=\"".$n->Abfahrt."\""; ?>>
<?php } ?>
    <label>Veranstaltung</label>
    <input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Name" type="text" placeholder="Name" <?php if($fill) echo "value=\"".$n->Name."\""; ?>>
    <label>Beschreibung</label>
    <input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Beschreibung" type="text" placeholder="Beschreibung" <?php if($fill) echo "value=\"".$n->Beschreibung."\""; ?>>
<label>Veranstaltungsort (z.B. Rochuskirche)</label>
    <input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Ort1" type="text" placeholder="Ort" <?php if($fill) echo "value=\"".$n->Ort1."\""; ?>>
<label>Straße, Hausnummer</label>
    <input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Ort2" type="text" placeholder="Ort" <?php if($fill) echo "value=\"".$n->Ort2."\""; ?>>
    <label>Stadtteil</label>
    <input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Ort3" type="text" placeholder="Ort" <?php if($fill) echo "value=\"".$n->Ort3."\""; ?>>
    <label>Stadt</label>
    <input class="w3-input w3-border <?php echo $GLOBALS['commonColors']['inputs']; ?> w3-margin-bottom w3-mobile" name="Ort4" type="text" placeholder="Ort" <?php if($fill) echo "value=\"".$n->Ort4."\""; ?>>
    <input class="w3-check" type="checkbox" name="Auftritt" value="1" <?php if($fill && (bool)$n->Auftritt) echo "checked"; ?>>
    <label>Auftritt</label>
    <input class="w3-check" type="checkbox" name="published" value="1" <?php if($fill && (bool)$n->published) echo "checked"; ?>>
    <label>sichtbar</label>
    <div class="w3-container w3-mobile">
    <input class="w3-btn <?php echo $GLOBALS['commonColors']['submit']; ?> w3-border w3-margin w3-mobile" type="submit" name="insert" value="speichern">
    <?php
      if($fill) {
      ?>
    <input type="hidden" name="Index" <?php if($fill) echo "value=\"".$n->Index."\""; ?>>
    <input type="hidden" name="new" <?php if($fill) echo "value=\"".$n->new."\""; ?>>
    <input class="w3-btn <?php echo $GLOBALS['commonColors']['submit']; ?> w3-border w3-margin w3-mobile" type="submit" name="delete" value="löschen">
          <?php
      }
?>
    </div>
</form>
</div>
<div class="w3-panel w3-mobile w3-center w3-col s3 l4">
</div>
<script>
function clearInput(name) {
  var x = document.getElementsByName(name);
  for(i=0; i<x.length; i++) {
      x[i].value = '';
  }
}
</script>
      
<?php
include "common/footer.php";
?>
