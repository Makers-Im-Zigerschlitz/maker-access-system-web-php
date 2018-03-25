function catChange() {
  var e = document.getElementById("catSelect");
  var value = e.options[e.selectedIndex].value;
  var text = e.options[e.selectedIndex].text;
  if (value != "all") {
    var path = document.location.pathname + "?cat=" + value;
  }
  else {
    var path = document.location.pathname;  
  }
  window.location.replace(path);
}
