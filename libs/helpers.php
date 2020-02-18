<?php
function loadconfig() {
    $sql = sprintf('SELECT * FROM `%sconfig`;',
		   $GLOBALS['dbprefix']
    );
    $dbr = mysqli_query($GLOBALS['conn'], $sql);
    sqlerror();
    $optionsDB = array();
    while($row = mysqli_fetch_array($dbr)) {
        $optionsDB += [$row['Parameter'] => $row['Value']];
    }
    return $optionsDB;
}
function requireAdmin() {
    if(!$_SESSION['admin']) die("Admin permissions required.");
}
function bool2string($val) {
    if($val) return "ja";
    return "nein";
}

function instrumentOption($val) {
    $sql = sprintf('SELECT * FROM `%sInstrument` ORDER BY `Register`, `Name`;',
		   $GLOBALS['dbprefix']
    );
    $dbr = mysqli_query($GLOBALS['conn'], $sql);
    sqlerror();
    while($row = mysqli_fetch_array($dbr)) {
        if($val == $row['Index']) {
            echo "<option value=\"".$row['Index']."\" selected>".$row['Name']."</option>\n";
        }
        else {
            echo "<option value=\"".$row['Index']."\">".$row['Name']."</option>\n";
        }
    }
}

function VehicleOption($val) {
    $sql = sprintf('SELECT * FROM `%svehicle`;',
		   $GLOBALS['dbprefix']
    );
    $dbr = mysqli_query($GLOBALS['conn'], $sql);
    sqlerror();
    while($row = mysqli_fetch_array($dbr)) {
        if($val == $row['Index']) {
            echo "<option value=\"".$row['Index']."\" selected>".$row['Name']."</option>\n";
        }
        else {
            echo "<option value=\"".$row['Index']."\">".$row['Name']."</option>\n";
        }
    }
}

function RegisterOption($val) {
    $sql = sprintf('SELECT * FROM `%sRegister` ORDER BY `Sortierung`;',
		   $GLOBALS['dbprefix']
    );
    $dbr = mysqli_query($GLOBALS['conn'], $sql);
    sqlerror();
    while($row = mysqli_fetch_array($dbr)) {
        if($val == $row['Index']) {
            echo "<option value=\"".$row['Index']."\" selected>".$row['Name']."</option>\n";
        }
        else {
            echo "<option value=\"".$row['Index']."\">".$row['Name']."</option>\n";
        }
    }
}
function getPage($string) {
    if($string == $_SESSION['page']) {
        echo $GLOBALS['optionsDB']['colorTitleBar'];
    }
    else {
        echo $GLOBALS['optionsDB']['colorNav'];
    }
}

function getAdminPage($string) {
    if($string == $_SESSION['page'] && $_SESSION['adminpage']) {
        echo $GLOBALS['optionsDB']['colorTitleBar'];
    }
    else {
        echo $GLOBALS['optionsDB']['colorNavAdmin'];
    }
}

function string2Date($string) {
    $y = substr($string, 0, 3);
    $m = substr($string, 5, 6);
    $d = substr($string, 8, 9);
}

function string2gDate($string) {
    $y = substr($string, 0, 4);
    $m = substr($string, 5, 2);
    $d = substr($string, 8, 2);
    return "new Date(".intval($y).", ".(intval($m)-1).", ".intval($d).")";
}

function germanDate($string, $monthLetters) {
    if($string == '') {
	return;
    }
    $months = array(
        "01" => "Januar",
        "02" => "Februar",
        "03" => "März",
        "04" => "April",
        "05" => "Mai",
        "06" => "Juni",
        "07" => "Juli",
        "08" => "August",
        "09" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Dezember"
    );
    $dows = array(
        1 => 'Montag',
        2 => 'Dienstag',
        3 => 'Mittwoch',
        4 => 'Donnerstag',
        5 => 'Freitag',
        6 => 'Samstag',
        7 => 'Sonntag'
    );
    $y = substr($string, 0, 4);
    $m = substr($string, 5, 2);
    $d = substr($string, 8, 2);

    $date = mktime(0,0,0, $m, $d, $y);
    $dow = date("N", $date);

    if($monthLetters) {
        $s = $dows[$dow].", ".$d.". ".$months[$m]." ".$y;
    } else {
        $s = $d.".".$m.".".$y;
    }
    return $s;
}

function mkAdmin() {
    $_SESSION['userid'] = 0;
    $_SESSION['admin'] = true;
    $_SESSION['username'] = 'SYSTEM';
}

function validateLink($hash) {
    $_SESSION['userid'] = 0;
    $sql = sprintf("SELECT * FROM `%sUser` WHERE `activeLink` = '%s';",
		   $GLOBALS['dbprefix'],
		   $hash
    );
    $dbr = mysqli_query($GLOBALS['conn'], $sql);
    sqlerror();
    while($row = mysqli_fetch_array($dbr)) {
        $_SESSION['userid'] = $row['Index'];
        $_SESSION['Vorname'] = $row['Vorname'];
        $_SESSION['Nachname'] = $row['Nachname'];
        $_SESSION['username'] = $row['Vorname']." ".$row['Nachname'];
        $_SESSION['admin'] = (bool)$row['Admin'];
        $_SESSION['singleUsePW'] = (bool)$row['singleUsePW'];
        $logentry = new Log;
        $logentry->info("Login via Link.");
        recordLogin();
        return true;
        break;
    }
    return false;
}
function validateUser($login, $password) {
    $_SESSION['userid'] = 0;
    $sql = sprintf("SELECT * FROM `%sUser` WHERE `login` = '%s';",
		   $GLOBALS['dbprefix'],
		   $login
    );
    $dbr = mysqli_query($GLOBALS['conn'], $sql);
    sqlerror();
    while($row = mysqli_fetch_array($dbr)) {
        if(password_verify($password, $row['Passhash'])) {
            $_SESSION['userid'] = $row['Index'];
            $_SESSION['Vorname'] = $row['Vorname'];
            $_SESSION['Nachname'] = $row['Nachname'];
            $_SESSION['username'] = $row['Vorname']." ".$row['Nachname'];
            $_SESSION['admin'] = (bool)$row['Admin'];
            $_SESSION['singleUsePW'] = (bool)$row['singleUsePW'];
            $logentry = new Log;
            $logentry->info("Login via Password.");
            recordLogin();
            return true;
        }
    }
    return false;
}

function recordLogin() {
    $sql = sprintf("UPDATE `%sUser` SET `LastLogin` = CURRENT_TIMESTAMP() WHERE `Index` = %d;",
		   $GLOBALS['dbprefix'],
		   $_SESSION['userid']
    );
    $dbr = mysqli_query($GLOBALS['conn'], $sql);
    sqlerror();
}

function loggedIn() {
    if(!isset($_SESSION['userid'])) {
	session_destroy();
	return false;
    }
    if($_SESSION['userid'] > 0) return true;
    session_destroy();
    return false;
}

function getActiveUsers($date) {
    $users = array();
    if($GLOBALS['optionsDB']['showConductor']) {
        $dirigent = '';
    }
    else {
        $dirigent = 'AND `iName` != "Dirigent"';
    }
    if($date) {
        $sql = sprintf('SELECT * FROM `%sUser` INNER JOIN (SELECT `Index` AS `iIndex`, `Name` AS `iName` FROM `%sInstrument`) `%sInstrument` ON `iIndex` = `Instrument` WHERE `Joined` >= "%s" AND (`DeletedOn` <= "%s" OR `DeletedOn` = NULL) AND `iName` != "Admin" %s ORDER BY `Nachname`, `Vorname`;',
        $GLOBALS['dbprefix'],
        $GLOBALS['dbprefix'],
        $GLOBALS['dbprefix'],
        $date,
        $date,
        $dirigent
        );
    }
    else {
        $sql = sprintf('SELECT * FROM `%sUser` INNER JOIN (SELECT `Index` AS `iIndex`, `Name` AS `iName` FROM `%sInstrument`) `%sInstrument` ON `iIndex` = `Instrument` WHERE `Deleted` = 0 AND `iName` != "Admin" %s ORDER BY `Nachname`, `Vorname`;',
        $GLOBALS['dbprefix'],
        $GLOBALS['dbprefix'],
        $GLOBALS['dbprefix'],
        $dirigent
        );
    }
    $dbr = mysqli_query($GLOBALS['conn'], $sql);
    sqlerror();
    while($row = mysqli_fetch_array($dbr)) {
        array_push($users, $row['Index']);
    }
    return $users;
}

function sql2time($time) {
    if($time != '') {
        return sql2timeRaw($time)." Uhr";
    }
}

function sql2timeRaw($time) {
    return substr($time, 0, 5);
}

function genitiv($string) {
    $last = substr($string, -1);
    if($last == "s" || $last == "x") {
        return $string.'\'';
    }
    else {
        return $string."s";
    }
}

function sqlerror() {
    if(mysqli_errno($GLOBALS['conn'])) {
        echo "<div class=\"w3-container ".$GLOBALS['optionsDB']['colorLogFatal']." w3-mobile w3-border w3-padding w3-border-black\"><b>SQL ERROR </b>".mysqli_errno($GLOBALS['conn']).": ".mysqli_error($GLOBALS['conn'])."</div>";
        $logentry = new Log;
        $logentry->error(mysqli_errno($GLOBALS['conn']).": ".mysqli_error($GLOBALS['conn']));
    }
}

function meldeWert($val) {
    switch($val) {
	case 1:
            return "ja";
	case 2:
            return "nein";
	case 3:
            return "vielleicht";
	default:
            break;
    }
}

function bin2date($v) {
    $c=array(false, false, false, false, false, false, false);
    for($i=7; $i>=1; $i--) {
        if($v/2**($i-1)>=1) {
            $c[$i-1]=true;
            $v=$v-2**($i-1);
        }
    }
    return $c;
}

function checkCronDate($v) {
    $c = bin2date($v);
    $dow = intval(date("N"));
    if($c[$dow-1] == false) { 
        return false;
    }
    return true;
}

function printOrchestra($tid) {
    $str="<svg width=\"1000\" height=\"600\">";
    $termin = new Termin;
    $termin->load_by_id($tid);
    
    $sql = sprintf('SELECT * FROM `%sRegister` ORDER BY `Row`;',
    $GLOBALS['dbprefix']
    );
$dbregister = mysqli_query($GLOBALS['conn'], $sql);
sqlerror();
$k=0;
$i=0;
$j=0;
$lastrow=0;
$lmaxradius = array();
$rmaxradius = array();
$radius=0;
array_push($lmaxradius, 0);
array_push($rmaxradius, 0);
while($register = mysqli_fetch_array($dbregister)) {
    if($lastrow != $register['Row']) {
        array_push($lmaxradius, $lmaxradius[count($lmaxradius)-1]+60);
        array_push($rmaxradius, $rmaxradius[count($rmaxradius)-1]+60);
    }
    $lastrow = $register['Row'];
    if($register['Row'] > 0) {
        if($register['ArcMin'] < 90) {
            $radius = $lmaxradius[$register['Row']-1]+60;
        }
        else {
            $radius = $rmaxradius[$register['Row']-1]+60;
        }
    }
    if($radius<150) {
        $radius = 150;
    }
    $r = new Register;
    $r->load_by_id($register['Index']);
    
    $sql = sprintf('SELECT * FROM `%sInstrument` WHERE `Register` = %d ORDER BY `Sortierung`;',
    $GLOBALS['dbprefix'],
    $r->Index
    );
    $dbinstrument = mysqli_query($GLOBALS['conn'], $sql);
    sqlerror();
    while($instrument = mysqli_fetch_array($dbinstrument)) {
        $sql = sprintf('SELECT * FROM `%sUser` WHERE `Instrument` = %d AND `Deleted` = 0 ORDER BY `Nachname`;',
        $GLOBALS['dbprefix'],
        $instrument['Index']
        );
        $dbuser = mysqli_query($GLOBALS['conn'], $sql);
        while($user = mysqli_fetch_array($dbuser)) {
            $u = new User;
            $u->load_by_id($user['Index']);
            if($register['Row']==0) {
                $radius=0;
                $arc=0;
            }
            else {
                $arc = $register['ArcMin']+$k*($register['ArcMax']-$register['ArcMin'])/abs($register['ArcMax']-$register['ArcMin'])*40/(2*pi()*$radius)*360;
                if($register['ArcMin'] < $register['ArcMax']) {
                    if($arc+20/(2*pi()*$radius)*360 >=$register['ArcMax']) {
                        $j++;
                        $radius += 40;
                        $k=0;
                    }
                }
                elseif($register['ArcMin'] > $register['ArcMax']) {
                    if($arc-20/(2*pi()*$radius)*360 <=$register['ArcMax']) {
                        $j++;
                        $radius += 40;
                        $k=0;
                    }
                }
                if($register['ArcMin'] < 90) {
                    if($radius > $lmaxradius[$register['Row']]) {
                        $lmaxradius[$register['Row']] = $radius;
                    }
                }
                else {
                    if($radius > $rmaxradius[$register['Row']]) {
                        $rmaxradius[$register['Row']] = $radius;
                    }
                }
                $arc = $register['ArcMin']+$k*($register['ArcMax']-$register['ArcMin'])/abs($register['ArcMax']-$register['ArcMin'])*40/(2*pi()*$radius)*360;
                }
                $x = 500-$radius*cos($arc/180*pi());
                $y = 40+$radius*sin($arc/180*pi());
                $m = $termin->getMeldungenByUser($u->Index);
                if(count($m)) {
                    $meldung = new Meldung;
                    $meldung->load_by_id($m[0]);
                    switch($meldung->Wert) {
                    case 1:
                        $color = "#4CAF50";
                        break;
                    case 2:
                        $color = "#f44336";
                        break;
                    case 3:
                        $color = "#2196F3";
                        break;
                    }
                }
                else {
                    $color = "#ffffff";
                }
                
                $str=$str."<circle cx=\"".$x."\" cy=\"".$y."\" r=\"18\" stroke=\"black\" stroke-width=\"2\" fill=\"".$color."\" />\n";
                /* $str=$str."<circle cx=\"".$x."\" cy=\"".$y."\" r=\"18\" stroke=\"black\" stroke-width=\"2\" fill=\"".$register['Color']."\" />\n"; */
                $str=$str."<text text-anchor=\"middle\" alignment-baseline=\"central\" fill=\"#000000\" font-size=\"10\" x=\"".$x."\" y=\"".$y."\">".$u->getShort()."</text>\n";

                $k++;
            }
        }
        $k=0;
        $j=0;
        $i++;
    }

    $str=$str."</svg>";
    return $str;
}
?>
