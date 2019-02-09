<?php
// if ($statement) {
//   echo "It worked";
// }else {
//   echo "statement in UserData Set Failed"
// }

require_once ('Models/Database.php');
require_once ('Models/RatingData.php');

class RatingDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function addRating($userID, $rating){

      $sqlQuery = "INSERT INTO ratings ratings(user_id, user_rating)
                   VALUES ('$userID', '$rating')";

                   $statement = $this->_dbHandle->prepare($sqlQuery);
                   $statement->execute();


    }

    // public function getRating(){
    //
    //   $sqlQuery = "INSERT INTO ratings ratings(user_id, user_rating)
    //                VALUES ('$userID', '$rating')";
    //
    //                $statement = $this->_dbHandle->prepare($sqlQuery);
    //                $statement->execute();
    //
    //
    // }

}
