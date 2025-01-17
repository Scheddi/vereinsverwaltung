<?php
include 'common/include.php';
session_start();

/* include 'libs/ics.php'; */
if(isset($_POST['appID'])) {

    $n = new Termin;
    $n->load_by_id($_POST['appID']);

    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename='.$n->Datum."_".$n->Name.'.ics');

    date_default_timezone_set('Europe/Berlin');
    if($n->EndDatum) {
        $end = gmdate('Y-m-d H:i:s', strtotime($n->EndDatum." 23:59:00"));
        if($n->Uhrzeit2) {
            $end = gmdate('Y-m-d H:i:s', strtotime($n->EndDatum." ".$n->Uhrzeit2));
        }
        $begin = gmdate('Y-m-d H:i:s', strtotime($n->Datum." ".$n->Uhrzeit));
        if($n->Uhrzeit == NULL) {
            $begin = gmdate('Y-m-d H:i:s', strtotime($n->Datum." 00:00:00"));
            $end = gmdate('Y-m-d H:i:s', strtotime($n->EndDatum." 23:59:00"));
        }        
    }
    else {
        $end = gmdate('Y-m-d H:i:s', strtotime("+120 minutes", strtotime($n->Datum." ".$n->Uhrzeit)));

        if($n->Uhrzeit2) {
            $end = gmdate('Y-m-d H:i:s', strtotime($n->Datum." ".$n->Uhrzeit2));
        }

        $begin = gmdate('Y-m-d H:i:s', strtotime($n->Datum." ".$n->Uhrzeit));
        if($n->Uhrzeit == NULL) {
            $begin = gmdate('Y-m-d H:i:s', strtotime($n->Datum." 00:00:00"));
            $end = gmdate('Y-m-d H:i:s', strtotime($n->Datum." 23:59:00"));
        }
    }

    $ics = new ICS(array(
        'timezone' => 'Europe/Berlin',
        'location' => $n->getOrt(),
        'description' => $n->Beschreibung,
        'dtstart' => $begin,
        'dtend' => $end,
        'summary' => $n->Name
    ));

echo $ics->to_string();
}
else {
    echo "Error: Appointment not found.";
}

?>