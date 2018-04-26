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
  if ($sqldate!=""){
    $temp = explode("-",$sqldate);
    $date = $temp[2].".".$temp[1].".".$temp[0];
    return $date;
  } else {
    return "";
  }
}

function datetosql($date)
{
  $temp = explode(".",$date);
  $sqldate = $temp[2]."-".$temp[1]."-".$temp[0];
  return $sqldate;
}
function whatsMyIP()
{
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
    {
        if (array_key_exists($key, $_SERVER) === true)
        {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip)
            {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                {
                    return $ip;
                }
            }
        }
    }
}
 ?>
