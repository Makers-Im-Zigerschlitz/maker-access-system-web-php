<?php
include '../../config/config.inc.php';
/**
 *Class for sending prepared mails
 */
class MASMail
{
  public function __construct($_username, $_template, $_conn)
  {
    $this->username = $_username;
    $this->template = $_template;
    $this->conn = $_conn;
    $this->destadress = "";
    $this->mailcontent = "";
    $this->prepared = false;
  }
  public function prepareMail()
  {
    $username = $this->username;
    $template = $this->template;
    $conn = $this->conn;
    $sql = "SELECT * FROM tblMembers INNER JOIN tblUsers ON tblMembers.uid=tblUsers.uid WHERE tblUsers.username = '$username'";
    $result = $conn->query($sql);
    $result = mysqli_fetch_assoc($result);
    $variables = array();
    $this->destadress = $result["Mail"];
    $variables['firstname'] = $result["Firstname"];
    $variables['serverlink'] = $_SERVER['HTTP_HOST'];
    $variables['lastname'] = $result["Lastname"];
    $variables['username'] = $this->username;
    $template = file_get_contents("templates/".$template.".html");
    foreach($variables as $key => $value)
    {
        $template = str_replace('{{ '.$key.' }}', $value, $template);
    }
    //echo $template;
    $this->mailcontent = $template;
    $this->prepared = true;
  }

  public function sendMail($subject)
  {
    if ($this->prepared) {
      $header = "From: donotreply@" . $_SERVER["HTTP_HOST"]  . "\r\n" . 'X-Mailer: PHP/' . phpversion();
      $header .= 'MIME-Version: 1.0' . "\r\n";
      $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      if(mail($this->destadress , $subject , $this->mailcontent, $header))
      {
        echo "Mail sent!";
      }
    }
  }
}
 ?>
