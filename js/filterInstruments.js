function filterMusiker() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("filterString");
    filter = input.value.toUpperCase();
    table = document.getElementById("Liste");
    tr = table.getElementsByTagName("div");
    for (i = 0; i < tr.length; i++) {
	if(tr[i].className=="w3-modal" || tr[i].className=="w3-modal-content") continue;
	if(tr[i].parentNode !== table) continue
	td = tr[i].getElementsByTagName("div");
	var show=0;
	for(j=0; j < td.length; j++) {
	    txtValue = td[j].textContent || td[j].innerText;
	    if (txtValue.toUpperCase().indexOf(filter) > -1) {
		show=1;
	    }
	}
	if (show) {
	    tr[i].style.display = "";
	} else {
	    tr[i].style.display = "none";
	}
    }
}
