<?php
  //Just retries all the data from every table
  //Don't instatiate this, instantiate the dataset.You can then use the DataSet class to use methods like getID_Camps()
class CampData {

    protected $_id_Camps, $_name_camp, $_date_opening_camp, $_time_opening_camp;
    protected $_city_camp, $_country_camp, $_visible_camp, $_longitude, $_latitude, $_camp_images;


    public function __construct($dbRow) {
        $this->_id_Camps = $dbRow['id_Camps'];
        $this->_name_camp = $dbRow['name_camp'];
        //$this->_image_camp = $dbRow['image_camp'];
        $this->_city_camp = $dbRow['city_camp'];
        $this->_country_camp = $dbRow['country_camp'];
        $this->_visible_camp = $dbRow['visible_camp'];
        //if ($dbRow['visible_camp'] && $this->) $this->_time_opening_camp = 'yes'; else $this->_time_opening_camp = 'no';
        $this->_date_opening_camp = $dbRow['date_opening_camp'];
        $this->_time_opening_camp = $dbRow['time_opening_camp'];
        $this->_longitude = $dbRow['longitude_camp'];
        $this->_latitude = $dbRow['latitude_camp'];
        $this->_camp_images = $dbRow['camp_images'];

    }

    public function getID_Camps() {
        return $this->_id_Camps;
    }

    public function getName_Camp() {
       return $this->_name_camp;
    }

    // public function getImage_Camp() {
    //    return $this->_image_camp;
    // }

    public function getTime_Opening() {
       return $this->_time_opening_camp;
    }

    public function getDate_Opening() {
       return $this->_date_opening_camp;
    }

    public function getCity() {
       return $this->_city_camp;
    }
    public function getCountry() {
       return $this->_country_camp;
    }
    public function getVisbility() {
       return $this->_visible_camp;
    }
    public function getLongitude() {
        return $this->_longitude;
    }
    public function getLatitude() {
        return $this->_latitude;
    }
    public function getCamp_images() {
        return $this->_camp_images;
    }

}
