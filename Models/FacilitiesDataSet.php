<?php
// if ($statement) {
//   echo "It worked";
// }else {
//   echo "statement in UserData Set Failed"
// }
require_once ('Models/Database.php');
require_once ('Models/FacilitiesData.php');
class FacilitiesDataSet {
    protected $_dbHandle, $_dbInstance;
    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }
}
