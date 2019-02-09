<?php

class RatingData {

    protected $_user_id, $_user_rating;

    public function __construct($dbRow) {
      $this->_user_id = $dbRow['user_id'];
      $this->_user_rating = $dbRow['user_rating'];
    }


        public function getUser_id() {
            return $this->_user_id;
        }
        public function getUser_rating() {
            return $this->_user_rating;
        }


}
