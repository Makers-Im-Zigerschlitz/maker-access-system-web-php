function catChange() {
  var e = document.getElementById("catSelect");
  var value = e.options[e.selectedIndex].value;
  var text = e.options[e.selectedIndex].text;
  var path = document.location.href;
  if (value != "all") {
		path = document.location.pathname + "?site=logs&cat=" + value;
  }
  else {
    path = document.location.pathname;  
  }
  window.location.replace(path);
}
