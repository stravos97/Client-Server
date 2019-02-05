<?php

require_once ('Models/Database.php');
require_once ('Models/FavouritesData.php');

class FavouritesDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }
    /**
    * Fetches all the favourites and puts them in an array which can be retrieved using the controller
    * takes input as the user id number sent
    **/

    public function fetchAllFavourites($id) {
        $sqlQuery = "SELECT * FROM Favourites WHERE user_id = '$id'";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FavouritesData($row);
        }
        return $dataSet;
    }
    /**
    * inserts an option selected from info_content
    * takes the id of the camp, and the user id
    **/

    public function insertIntoFavourites($id_Camps, $user_id){
      $sqlQuery = "INSERT INTO Favourites(id_Camps, user_id)
                  VALUES ('$id_Camps', '$user_id')";
                  $statement = $this->_dbHandle->prepare($sqlQuery);
                  echo $sqlQuery;
                  $statement->execute();
    }

    /**
    * Deletes a favourite from the users favourite list
    */

    public function deleteFavourite($user_id, $id_Favourites){
        $sqlQuery = "DELETE FROM Favourites
                     WHERE user_id = '$user_id' AND id_Favourites = '$id_Favourites'";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        echo $sqlQuery;
        $statement->execute();
    }
}
