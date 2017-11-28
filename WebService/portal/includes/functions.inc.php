<?php
function changelang($lang)
{
try {
  setcookie("language",$lang,mktime()+31536000);
} catch (Exception $e) {}
}

function sql_replace($instring)
{
  // return htmlspecialchars(str_replace("\"","\"\"",str_replace("'","''",$instring)));
  return htmlspecialchars(str_replace("'","''",$instring));
}

function sqltodate($sqldate)
{
  $temp = explode("-",$sqldate);
  $date = $temp[2].".".$temp[1].".".$temp[0];
  return $date;
}

function datetosql($date)
{
  $temp = explode(".",$date);
  $sqldate = $temp[2]."-".$temp[1]."-".$temp[0];
  return $sqldate;
}
 ?>
