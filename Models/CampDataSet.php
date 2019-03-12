<?php
//preforms functions on data once it checks database is conected
//mainly to  the the camp data table in the dB
require_once ('Models/Database.php');
require_once ('Models/CampData.php');
require_once ('Models/FacilitiesData.php');
require_once ('Models/CampRecordsData.php');

class CampDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }
/**
* Fetches all the camps and puts them in an array which can be retrieved using the controller
**/
    public function fetchAllCamps($page) {
      $noOfRecords = 10;
      $offset = ($page-1) * $noOfRecords;

        $sqlQuery = "SELECT *, longitude_camp, latitude_camp
                     FROM Camp_records
                     LEFT JOIN Camp_details ON Camp_records.id_Camps = Camp_details.id_Camps
                     LIMIT $offset, $noOfRecords"; //second number is how many camps are shown

        //$table = "Camp_records";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->execute();
        if ($statement) {
          echo "It worked";
        }else {
          echo "statement -fetchall in Camp Set Failed";
        }

        $dataSet = [];
      //  echo $sqlQuery;
        while ($row = $statement->fetch()) { //executes the statement, fetches all the data and puts each sql function in the campdata constructor using row
           $dataSet[] = new CampData($row);
        }
        return $dataSet;
    }

    /**
    * Just get the Country Name which is used for autosuggest
    **/

    public function getAllCountryName() {

        $sqlQuery = "SELECT country_camp
                     FROM Camp_records";


        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_COLUMN, 0); //VERY IMPORTANT IT ECHOS LIKE THIS, look at the var dump
        // this will also stop country_camp and name_camp
        return $results;
    }

/**
* Adds a camp to Camp_records Camp_details, There is a fk relationship between the,. so id Number cannot be null
* Checks bool of $statement.
* Does an internal method call and auto increments the id Number from the max vaule
**/
public function addCamp($name_camp, $date, $time, $city, $country, $latitude, $longitude, $image, $block, $j,$k,$l,$m){
  $number = (CampDataSet::maxID());
  $id_Camps = ($number + 1);
  $sqlQuery = "BEGIN;
              INSERT INTO sgb206_campsite_records.facilities (id_Camps, shower_camps, wifi_camps, laundry_camps, disabled_facilities_camps)
              VALUES('$id_Camps', '$j', '$k', '$l', '$m');
              INSERT INTO Camp_records (id_Camps, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp)
              VALUES('$id_Camps', '$name_camp', '$date', '$time', '$city', '$country', '$latitude', '$longitude');
              INSERT INTO sgb206_campsite_records.Camp_details (id_Camps, camp_images, camp_fullDetails)
              VALUES('$id_Camps', '$image', '$block');
              COMMIT;";
              echo $sqlQuery;
              $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

              $statement->execute();
              if ($statement) {

              }else {
                echo "statement -fetchall in Camp Set Failed";
              }
}

/**
* Method that gets the max id Number from the Camp records table and returns it
**/

public function maxID(){
  $sqlQuery = "SELECT MAX(id_Camps) FROM sgb206_campsite_records.Camp_records";
  $statement = $this->_dbHandle->prepare($sqlQuery);
  $statement->execute();

  while ($row = $statement->fetch()) {
    return $row[0];
    var_dump($row);
  }
}

/**
* Usees the search Input sent by the user on 2.php and info content to see if a country is on a lsit
**/

    public function search($searchText, $page){
      $noOfRecords = 5;
      $offset = ($page-1) * $noOfRecords;
      $sqlQuery = "SELECT *, longitude_camp, latitude_camp
                     FROM Camp_records
                     LEFT JOIN Camp_details ON Camp_records.id_Camps = Camp_details.id_Camps WHERE country_camp = '$searchText'
                     LIMIT $offset, $noOfRecords";


      $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
      $statement->execute();

      // if ($statement) {
      //   echo "It worked";
      // }else {
      //   echo "statement -search in UserData Set Failed";
      // }


      $dataSet = [];
      while ($row = $statement->fetch()) {
        $dataSet[] = new CampData($row);
      }
      //var_dump($dataSet);
      return $dataSet;


    }
    /**
    * Checkbox filtering based on all possible combinations of checkbox seleceted
    * Returns an array which is used on 2.phtml
    **/

    public function idNumbers($shower,$wifi,$disabled, $laundry, $name, $page){  //makes sure the output is always in one array

      // Two seperate tables are used

      if ((strcmp($shower,"") == 0) and (strcmp($wifi,"") == 0) and (strcmp($disabled,"") == 0) and (strcmp($laundry,"") == 0)) //if no options are slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT *
                    FROM Camp_records
                    WHERE country_camp = '$name'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($shower,"") == 0) and (strcmp($wifi,"") == 0) and (strcmp($disabled,"") == 0)) //if only laundry is selected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        // query that gets the last one
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

      else if ((strcmp($wifi,"") == 0) and (strcmp($disabled,"") == 0) and (strcmp($laundry,"") == 0)) //if only shower is selected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

      else if ((strcmp($shower,"") == 0) and (strcmp($disabled,"") == 0) and (strcmp($laundry,"") == 0)) //if only wifi is selected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, wifi_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($shower,"") == 0) and (strcmp($wifi,"") == 0) and (strcmp($laundry,"") == 0)) //if only disabled is selected
      {
        // query that gets the last one
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, disabled_facilities_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

      else if ((strcmp($shower,"") == 0) and (strcmp($wifi,"") == 0)) // if laundry and disabled is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps, disabled_facilities_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND disabled_facilities_camps = '$disabled'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($shower,"") == 0) and (strcmp($disabled,"") == 0)) // if wifi and laundry is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, wifi_camps, laundry_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($laundry,"") == 0) and (strcmp($disabled,"") == 0)) // if wifi and shower is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, wifi_camps, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND shower_camps = '$shower' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($laundry,"") == 0) and (strcmp($shower,"") == 0)) // if wifi and disabled is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, wifi_camps, disabled_facilities_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($wifi,"") == 0) and (strcmp($disabled,"") == 0)) // if laundry and shower is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($wifi,"") == 0) and (strcmp($laundry,"") == 0)) // if disabled and shower is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, disabled_facilities_camps, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($shower,"") == 0) and (strcmp($laundry,"") == 0)) // if disabled and wifi is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, disabled_facilities_camps, wifi_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($disabled,"") == 0) and (strcmp($laundry,"") == 0)) // if shower and wifi is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, shower_camps, wifi_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND shower_camps = '$shower' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

      else if ((strcmp($shower,"") == 0)) // if laundry and disabled and wifi is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps, disabled_facilities_camps, wifi_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND disabled_facilities_camps = '$disabled' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($wifi,"") == 0)) // if laundry and disabled and shower is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps, disabled_facilities_camps, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND disabled_facilities_camps = '$disabled' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($disabled,"") == 0)) // if shower, wifi, laundry
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, shower_camps, wifi_camps, laundry_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND wifi_camps = '$wifi' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($laundry,"") == 0)) // if shower, wifi, disabled
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, shower_camps, wifi_camps, disabled_facilities_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE country_camp = '$name' AND Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled' AND wifi_camps = '$wifi' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

    }



    public function allIdNumbers($shower,$wifi,$disabled, $laundry, $page){  //makes sure the output is always in one array

      // Two seperate tables are used

      if ((strcmp($shower,"") == 0) and (strcmp($wifi,"") == 0) and (strcmp($disabled,"") == 0) and (strcmp($laundry,"") == 0)) //if no options are slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT *
                    FROM Camp_records
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($shower,"") == 0) and (strcmp($wifi,"") == 0) and (strcmp($disabled,"") == 0)) //if only laundry is selected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        // query that gets the last one
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry'
                    LIMIT $offset, $noOfRecords";
                  //  echo $sqlQuery;
                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

      else if ((strcmp($wifi,"") == 0) and (strcmp($disabled,"") == 0) and (strcmp($laundry,"") == 0)) //if only shower is selected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

      else if ((strcmp($shower,"") == 0) and (strcmp($disabled,"") == 0) and (strcmp($laundry,"") == 0)) //if only wifi is selected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, wifi_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($shower,"") == 0) and (strcmp($wifi,"") == 0) and (strcmp($laundry,"") == 0)) //if only disabled is selected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        // query that gets the last one
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, disabled_facilities_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

      else if ((strcmp($shower,"") == 0) and (strcmp($wifi,"") == 0)) // if laundry and disabled is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps, disabled_facilities_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND disabled_facilities_camps = '$disabled'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($shower,"") == 0) and (strcmp($disabled,"") == 0)) // if wifi and laundry is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, wifi_camps, laundry_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($laundry,"") == 0) and (strcmp($disabled,"") == 0)) // if wifi and shower is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, wifi_camps, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND shower_camps = '$shower' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($laundry,"") == 0) and (strcmp($shower,"") == 0)) // if wifi and disabled is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, wifi_camps, disabled_facilities_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($wifi,"") == 0) and (strcmp($disabled,"") == 0)) // if laundry and shower is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($wifi,"") == 0) and (strcmp($laundry,"") == 0)) // if disabled and shower is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, disabled_facilities_camps, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($shower,"") == 0) and (strcmp($laundry,"") == 0)) // if disabled and wifi is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, disabled_facilities_camps, wifi_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($disabled,"") == 0) and (strcmp($laundry,"") == 0)) // if shower and wifi is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, shower_camps, wifi_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND shower_camps = '$shower' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

      else if ((strcmp($shower,"") == 0)) // if laundry and disabled and wifi is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps, disabled_facilities_camps, wifi_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND disabled_facilities_camps = '$disabled' AND wifi_camps = '$wifi'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($wifi,"") == 0)) // if laundry and disabled and shower is slected
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, laundry_camps, disabled_facilities_camps, shower_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND disabled_facilities_camps = '$disabled' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($disabled,"") == 0)) // if shower, wifi, laundry
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, shower_camps, wifi_camps, laundry_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND laundry_camps = '$laundry' AND wifi_camps = '$wifi' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }
      else if ((strcmp($laundry,"") == 0)) // if shower, wifi, disabled
      {
        $noOfRecords = 5;
        $offset = ($page-1) * $noOfRecords;
        $sqlQuery = "SELECT Camp_records.id_Camps, Camp_details.camp_images, name_camp, date_opening_camp, time_opening_camp, city_camp, country_camp, latitude_camp, longitude_camp, shower_camps, wifi_camps, disabled_facilities_camps
                    FROM Camp_records, Facilities, Camp_details
                    WHERE Camp_records.id_Camps = Facilities.id_Camps AND Camp_records.id_Camps = Camp_details.id_Camps AND disabled_facilities_camps = '$disabled' AND wifi_camps = '$wifi' AND shower_camps = '$shower'
                    LIMIT $offset, $noOfRecords";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
                      $statement->execute();

                      if ($statement->rowCount()>0){


                        //Fetch our rows. Array (empty if no rows). False on failure.
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        return $rows;


                      } else {
                        echo "No results found";
                        return false;
                      }
      }

    }


//check to see if ths works
    public function removeCamp($id) {
      $sqlQuery = "START TRANSACTION;
                    SET foreign_key_checks = OFF;
                    DELETE FROM camp_records WHERE id_Camps='$id';
                    DELETE FROM camp_details WHERE id_Camps='$id';
                    DELETE FROM facilities WHERE id_Camps='$id';
                    SET FOREIGN_KEY_CHECKS=1;
                    COMMIT";

                    $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

                    $statement->execute();
                    if ($statement) {
                      echo "It worked";
                    }else {
                      echo "statement -fetchall in Camp Set Failed";
                    }
    }
}
