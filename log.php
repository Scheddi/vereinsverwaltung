<?php
session_start();
$_SESSION['page']='log';
include "common/header.php";
/* $sql = sprintf('SELECT * FROM `User`;'); */
/* $dbr = mysqli_query($conn, $sql); */
/* sqlerror(); */
?>
<div id="header" class="w3-container <?php echo $GLOBALS['commonColors']['titlebar']; ?>">
<h2>Log</h2>
</div>
<?php
$now = date("Y-m-d");
$sql = sprintf('SELECT `Index` FROM `%sLog` ORDER BY `Timestamp` DESC LIMIT 1000;',
$GLOBALS['dbprefix']
);
$dbr = mysqli_query($conn, $sql);
sqlerror();
while($row = mysqli_fetch_array($dbr)) {
    $M = new Log;
    $M->load_by_id($row['Index']);
    echo $M->printTableLine();
}
?>
<script>
Element.prototype.appendAfter = function (element) {
    element.parentNode.insertBefore(this, element.nextSibling);
}, false;

    function getLog() {
	if (window.XMLHttpRequest) {
	    // AJAX nutzen mit IE7+, Chrome, Firefox, Safari, Opera
	    xmlhttp=new XMLHttpRequest();
	}
	else {
	    // AJAX mit IE6, IE5
	    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200 && xmlhttp.responseText) {
            var parent = document.getElementById("header");
            var NewElement = document.createElement("div");
            NewElement.appendAfter(parent.nextSibling);
            let doc = new DOMParser().parseFromString(xmlhttp.responseText, 'text/html');
            let div = doc.body.firstChild;
            NewElement.parentNode.replaceChild(div, NewElement);
	    }
	}
    var parent = document.getElementById("header");
    var first = parent.nextSibling.nextSibling;
    var maxIndex = parseInt(first.id);
    if(maxIndex > 0) {
        var str = "getLog.php?id="+<?php echo "\"".$GLOBALS['cronID']."\""; ?>+"&maxIndex="+maxIndex;
        xmlhttp.open("GET", str, true);
        xmlhttp.send();
    }
    }
var interval = setInterval(getLog, 5000);

</script>
<?php
include "common/footer.php";
?>