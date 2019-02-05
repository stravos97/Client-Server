<?php
  //Just retries all the data from every table
  //Don't instatiate this, instantiate the dataset.You can then use the DataSet class to use methods like getID_Camps()
class UserAccountsData {

    protected $_user_id, $_user_email, $_user_phone_number, $_user_passwords, $_user_phn_country_code;
    protected $_user_country, $_user_gender, $_user_age, $_user_admin;

    public function __construct($dbRow) {
        $this->_user_id = $dbRow['user_id'];
        $this->_user_email = $dbRow['user_email'];
        $this->_user_phone_number = $dbRow['user_phone_number'];
        $this->_user_passwords = $dbRow['user_passwords'];
        $this->_user_phn_country_code = $dbRow['user_phn_country_code'];
        $this->_user_country = $dbRow['user_country'];
        //if ($dbRow['visible_camp'] && $this->) $this->_time_opening_camp = 'yes'; else $this->_time_opening_camp = 'no';
        $this->_user_gender = $dbRow['user_gender'];
        $this->_user_age = $dbRow['user_age'];
        $this->_user_admin = $dbRow['user_admin'];
    }

    public function getUser_ID() {
        return $this->_user_id;
    }

    public function getUser_Email() {
       return $this->_user_email;
    }

    public function getUser_phone_number() {
       return $this->_user_phone_number;
    }

    public function getUser_passwords() {
       return $this->_user_passwords;
    }

    public function getUser_phn_country_code() {
       return $this->_user_phn_country_code;
    }

    public function getUser_country() {
       return $this->_user_country;
    }

    public function getUser_gender() {
       return $this->_user_gender;
    }
    public function getUser_age() {
        return $this->_user_age;
    }
    public function getUser_admin() {
        return $this->_user_admin;
}


public function setUser_country($country) {
   return $this->_user_country = $country;
}

public function setUser_gender($gender) {
   return $this->_user_gender = $gender;
}
public function setUser_age($age) {
    return $this->_user_age = $age;
}
}
