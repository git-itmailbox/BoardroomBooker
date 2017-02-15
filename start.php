<?php

class Db
{
    private static $instance = NULL;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance($dbname,$login, $passwd) {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            $pdo_options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
            //self::$instance = new PDO('mysql:host=localhost;dbname=booker', 'root', '123', $pdo_options);
            self::$instance = new PDO("mysql:host=localhost;dbname=$dbname", 'root', '1', $pdo_options);
        }
        return self::$instance;
    }
 
}

 function install($dbname,$login, $passwd)
    {
        $db = Db::getInstance($dbname,$login, $passwd);
        if(!$db) {
        echo "cant connect to db";
        return;
        }
        
        $sql = file_get_contents('dump1502.sql');
       
         $db->query($sql);
      
    }
    
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $dbname=$_POST['dbname'];
 $login=$_POST['login'];
 $password=$_POST['password'];
 if($dbname!=='' & $login!=='' )  install($dbname,$login, $password);
 else echo "Please fill dbname and login. Password if needed <BR>";
 }
?>
Please create DB before and then fill the form
<form method="POST">
<table align="center">
        <tr>
            <td>Your dbname</td>
            <td> <input id="login" name="dbname" class="" style="width: 150px; padding: 5px; margin-left: 20px;" type="text"></td>
        </tr>
         <tr>
            <td>Login (for mysql)</td>
            <td> <input id="login" name="login" class="" style="width: 150px; padding: 5px; margin-left: 20px;" type="text"></td>
        </tr>
        <tr>
            <td>password</td>
            <td><input id="password" name="password" class="" style="width: 150px; padding: 5px; margin-left: 20px;" type="password"></td>
        </tr>
        <tr>
            <td align="center" colspan="2"><input name="submit" type="submit" value="Restore DB" style="width: 250px; "></td>
        </tr>
    </table>
</form>