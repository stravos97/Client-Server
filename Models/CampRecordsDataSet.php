<?php
//preforms functions on data once it checks database is conected
require_once ('Models/Database.php');
require_once ('Models/CampRecordsData.php');

class CampRecordsDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
    * Fetches all the paragrahs and puts them in an array which can be retrieved using the controller
    **/

    public function fetchParagraph($id) {
        $sqlQuery = "SELECT * FROM Camp_details WHERE id_Camps = '$id'";
        //$table = "Camp_records";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->execute();
        if ($statement) {
          //echo "It worked";
        }else {
          echo "statement -fetchall in Camp Set Failed";
        }

        $dataSet = new CampRecordsData($statement->fetch());

        return $dataSet;
    }

}
