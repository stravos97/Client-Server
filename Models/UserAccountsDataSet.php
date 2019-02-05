<?php
//Logs in without applying sessions
require_once ('Models/Database.php');
require_once ('Models/UserAccountsData.php');

class UserAccountsDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
    * Checks to see if the details exist in the dB
    **/

  public function checkDetails($email)
    {
      $sqlQuery = "SELECT *
                   FROM user_accounts
                   WHERE user_email = '".$email."' AND user_country = '' AND user_gender = ''";
      //$table = "Camp_records";
      $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
      $statement->execute();

      if ($statement) {
        echo "It worked";
      }else {
        echo "statement in UserData Set Failed";
        header("Location: 404.php");
      }

      $dataSet = [];

      while ($row = $statement->fetch()) {
         $dataSet[] = new UserAccountsData($row);
      }
      var_dump($dataSet);
      return $dataSet;
    }

/**
* imputs the email and the password imto the dB to see if they exist.
* If a row is returned, then sessions are set for the userID, ID-num as well as a cookie if the user is an user_admin
* the passswords are hashed using md5 and the whole statement is bool, if those correct details are returned
*
**/

    public function login($inputEmail, $password) {
      $password = md5($password);
        $sqlQuery = "SELECT user_email, user_passwords
                     FROM user_accounts
                     WHERE user_email = '".$inputEmail."' && user_passwords = '".$password."'";

        //$table = "Camp_records";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); //exectue and check for the rowCOunt

if ($statement->rowCount()>0) {
  //echo "You have registerd";
  $_SESSION['userID']=$inputEmail;
  $_SESSION['ID-num'] = UserAccountsDataSet::getID($inputEmail); //internal method call to getID

  if (UserAccountsDataSet::checkAdmin($inputEmail)) {
    setcookie("admin", "true", time() + 3600, '/');

  }

  return true;
}else {
  //echo "You haven't already registered";
  return false;
}
    }

/**
* Registers a user base don email, passwrod, phone number and phone country code
*
**/
  public function register($email, $password, $phoneNumber, $phn_country) {
      $password = md5($password);
      $sqlQuery = "INSERT INTO user_accounts(user_email, user_passwords, user_phone_number, user_phn_country_code, user_admin)
                  VALUES ('$email', '$password', '$phoneNumber', '$phn_country', '0')";
                  $statement = $this->_dbHandle->prepare($sqlQuery);
                  $statement->execute();

    }

    /**
    * Gets the id number of a specific email and returns it
    *
    **/

    public function getID($email){
      $sqlQuery = "SELECT user_id
                   FROM user_accounts
                   WHERE user_email = '".$email."' ";
       $statement = $this->_dbHandle->prepare($sqlQuery);
       $statement->execute();

       while ($row = $statement->fetch()) {
         return $row[0];
         var_dump($row);
       }

    }

    //Checks to see if a user is an admin

    public function checkAdmin($inputEmail){
      $sqlQuery = "SELECT user_id, user_admin FROM user_accounts WHERE user_admin = '1' AND user_email = '".$inputEmail."'";
      $statement = $this->_dbHandle->prepare($sqlQuery);
      $statement->execute();

      if ($statement->rowCount()>0) {
        return true;
      }
    }

}
