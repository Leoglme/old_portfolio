<?php
    $array = array("firstname" => "", "name" => "", "email" => "", "phone" => "","message" => "", "firstnameError" => "", "nameError" => "", "emailError" => "", "phoneError" => "", "messageError" => "", "isSucces" => false);
    $emailTo = "contact@dibodev.com";

  if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $array["firstname"] = verifyInput($_POST["firstname"]);
        $array["name"] = verifyInput($_POST["name"]);
        $array["email"] = verifyInput($_POST["email"]);
        $array["phone"] = verifyInput($_POST["phone"]);
        $array["message"] = verifyInput($_POST["message"]);
        $array["isSucces"] = true;
        $emailText = "";


        if(empty($array["firstname"])) //champs requis server
        {
           $array["firstnameError"] = "Je veux connaitre ton prénom !";
           $array["isSucces"] = false;
        }
        else
        {
            $emailText .= "Firstname: {$array["firstname"]}\n";
        }

        if(empty($array["name"]))
        {
           $array["nameError"] = "Je veux connaitre ton nom !";
           $array["isSucces"] = false;
        }
        else
        {
           $emailText .= "Name: {$array["name"]}\n";
        }


        if(!isEmail($array["email"]))
        {
           $array["emailError"] = "Email invalide !" ;
           $array["isSucces"] = false;
        }
         else
        {
           $emailText .= "email: {$array["email"]}\n";
        }

        if(!isPhone($array["phone"]))
        {
           $array["phoneError"] = "Numéro invalide ( Chiffres uniquement) !" ;
           $array["isSucces"] = false;
        }
         else
        {
           $emailText .= "Téléphone: {$array["phone"]}\n";
        }

         if(empty($array["message"]))
        {
           $array["messageError"] = "Que voulez-vous me dire ?";
           $array["isSucces"] = false;
        }
         else
        {
           $emailText .= "Message: {$array["message"]}\n";
        }

        if($array["isSucces"])
        {
            $headers = "From: {$array["firstname"]} {$array["name"]} <{$array["email"]}>\r\nReply-To: {$array["email"]}";
            mail($emailTo, "Un message de mon site", $emailText, $headers);
            $dbh = new PDO('mysql:host=localhost;dbname=test', 'root', '');
            $req = $dbh->prepare('INSERT INTO email (prenom, nom, mail, telephone, message) VALUES (?, ?, ?, ?, ?)');
            $req->execute(array($array["firstname"], $array["name"], $array["email"], $array["phone"], $array["message"]));
        }

}

        echo json_encode($array);




    function isPhone($var)
    {
        return preg_match("/^[0-9 ]*$/", $var);
    }

    function isEmail($var)
    {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    function verifyInput($var) //protection xss
    {
        $var = trim($var);
        $var = stripslashes($var);
        $var = htmlspecialchars($var);
        return $var;
    }

?>
