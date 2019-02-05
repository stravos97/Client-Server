<?php

class FacilitiesData {

    protected $_id_Camps, $_shower_camps, $_wifi_camps, $_disabled_facilities_camps, $_laundry_camps;

    public function __construct($dbRow) {
      $this->_shower_camps = $dbRow['shower_camps'];
      $this->_wifi_camps = $dbRow['wifi_camps'];
      $this->_disabled_facilities_camps = $dbRow['disabled_facilities_camps'];
      $this->_laundry_camps = $dbRow['laundry_camps'];
      $this->_id_Camps = $dbRow['id_Camps'];
    }


        public function getShower_camps() {
            return $this->_shower_camps;
        }
        public function getWifi_camps() {
            return $this->_wifi_camps;
        }
        public function getDisabled_facilities_camps() {
            return $this->_disabled_facilities_camps;
        }
        public function getLaundry_camps() {
            return $this->_laundry_camps;
        }
        public function getId_Camps() {
            return $this->_id_Camps;
        }

}
