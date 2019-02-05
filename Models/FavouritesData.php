<?php

class FavouritesData {

    protected $_id_Camps, $_id_Favourites, $_user_id;

    public function __construct($dbRow) {
        $this->_id_Camps = $dbRow['id_Camps'];
        $this->_id_Favourites = $dbRow['id_Favourites'];
        $this->_user_id = $dbRow['user_id'];
    }

    public function getID_Camps() {
        return $this->_id_Camps;
    }

    public function getId_Favourites() {
       return $this->_id_Favourites;
    }

    public function getUser_id() {
       return $this->_user_id;
    }

}
