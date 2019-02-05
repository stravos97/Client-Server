<?php
class CampRecordsData {

    protected $_id_Camps, $_camp_fullDetails, $_camp_images;

    public function __construct($dbRow) {
        $this->_id_Camps = $dbRow['id_Camps'];
        $this->_camp_fullDetails = $dbRow['camp_fullDetails'];
        $this->_camp_images = $dbRow['camp_images'];
        //if ($dbRow['visible_camp'] && $this->) $this->_time_opening_camp = 'yes'; else $this->_time_opening_camp = 'no';
    }

    public function getId_Camps() {
       return $this->_id_Camps;
    }

    public function getCamp_fullDetails() {
        return $this->_camp_fullDetails;
    }
    public function getCamp_images() {
       return $this->_camp_images;
    }


}
