<?php

/**
 * Class ClonePlant
 */
class ClonePlant {

    private $_db,
        $_data,
        $_count;


    /**
     * Group constructor.
     */
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /**
     * Create data at groups table
     * @param array $fields: array( target => value) to create a field at Database
     * @throws Exception
     */
    public function create($fields = array()) {

        if(!$this->_db->insert('plants', $fields)) {
            throw new Exception('There was a problem creating this menu.');
        }
    }
    public function createlotID($fields = array()) {

        if(!$this->_db->insert('lot_id', $fields)) {
            throw new Exception('There was a problem creating this menu.');
        }
    }

    /**
     * dd
     * @param $cloneRoomID
     * @param $createdPlantQRCode
     * @throws Exception
     */
    public function CreateRelationCloneRoomAndPlant($cloneRoomID,$createdPlantQRCode) {
        $createdPlantID = $this->getValueOfAnyTable('plants','qr_code','=',$createdPlantQRCode);
        $createdPlantID = $createdPlantID->results();

        $this->deleteValueOfAnyTable('index_clone','plant_id','=',$createdPlantID[0]->id);
        if(!$this->_db->insert('index_clone', array(
            'room_id' => $cloneRoomID,
            'plant_id' => $createdPlantID[0]->id,
            'genetic_id' => $createdPlantID[0]->genetic,
        ))) {
            throw new Exception('There was a problem inserting......');
        }
    }

    /**
     * @param $cloneRoomID
     * @return bool|\bool\|DB|mixed|null
     */
    public function getPlantsListFromCloneRoomID($cloneRoomID) {
        $clonePlantsIDList = $this->getValueOfAnyTable('index_clone','room_id','=',$cloneRoomID,'plant_id');
        $clonePlantsIDList = $clonePlantsIDList->results();

        return $clonePlantsIDList;
    }



    /**
     * register history of user process on site
     * @param null $action : is action of permissions
     * @param null $moduleName : is module name of permissions
     * @param null $channel :  is channel of permissions
     * @param null $type : type of permissions
     * @throws Exception
     */
    public function registerHistoryPlant($plant_UID,$user_id,$event){

        if($event){
            if(!$this->_db->insert('history', array(
                'plant_UID' => $plant_UID,
                'user_id' => $user_id,
                'event' =>$event,
                'date' =>  date('Y-m-d H:i:s'),
            ))) {
                throw new Exception('There was a problem creating history.');
            }
        }
    }

    /**
     * @param $lot_ID
     * @param $user_id
     * @param $event
     * @throws Exception
     */
    public function registerHistoryLot($lot_ID,$user_id,$event, $user_name){

        if($event) {

            if ($lot_ID && $user_id && $event) {
                if (!$this->_db->insert('history', array(
                    'lot_id' => $lot_ID,
                    'user_id' => $user_id,
                    'event' => $event,
                    'date' => date('Y-m-d H:i:s'),
                    'user_name' => $user_name,
                ))
                ) {
                    throw new Exception('There was a problem creating history.');
                }

            }
        }
    }

    /**
     * Update data at groups table
     * @param array $fields: array( target => value) to update field at Database
     * @param null $id: The id of selected field
     * @throws Exception
     */
    public function update($fields = array(), $id = null) {
        if(!$this->_db->update('plants', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }

    public function updateLastIndex($fields = array(), $id = null) {
        if(!$this->_db->update('last_index', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }

    public function addPlantToIndexVeg($vegRoomID,$lot_ID,$plant_ID,$genetic_ID) {
        if(!$this->_db->insert('index_veg', array(
            'room_id' => $vegRoomID,
            'lot_id' => $lot_ID,
            'plant_id' => $plant_ID,
            'genetic_id' => $genetic_ID,
        ))) {
            throw new Exception('Problem addPlantToIndexVeg......');
        }
    }
    /**
     * Find data following id at table
     * @param null $id: The id is that to find field
     * @return bool|DB|null
     */
    public function find($id = null) {
        if($id) {
            // if user had a numeric username this FAILS...
            $data = $this->_db->get('room_clone', array('id', '=', $id));

            if($data->count()) {
                $this->_data = $data->first();
                $this->_count = $data->count();
                return $this->_data;
            }
        }
        return false;
    }


    /**
     * Get data at table
     * @param $table: table name
     * @param $field: target (ex: name)
     * @param $symbol: operator (ex: =)
     * @param $key: value (ex: jone)
     * @return bool|\bool\|DB|null
     */
    public function getValueOfAnyTable($table,$field,$symbol,$key,$order = null){

        $data = $this->_db->get($table, array($field, $symbol, $key),$order);

        if($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
        }
        return $data;
    }



    /**
     * Delete data at table
     * @param $table: table name
     * @param $field: target (ex: name)
     * @param $symbol: operator (ex: =)
     * @param $key: value (ex: jone)
     * @throws Exception
     */
    public function deleteValueOfAnyTable($table,$field,$symbol,$key){

        if(!$this->_db->delete($table,array($field, $symbol, $key))) {
            throw new Exception('There was a problem Deleteing......');
        }
    }

    /**
     * Get all data at table
     * @return bool|DB|null
     */
    public function getAllOfInfo(){
        $data = $this->_db->get('room_clone', array('1', '=', '1'));
        if($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
        }
        return $data;
    }


    /**
     * Count of selected data at table
     * @return mixed
     */
    public function count(){
        return $this->_count;
    }

    /**
     * Test existing of seleted data
     * @return bool
     */
    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }

    /**
     * Delete data at groups and permissions table
     * @param $id: menu id for groups table
     * @return bool|\bool\|DB|null
     */
    public function delete($id){
        return $this->_db->delete('room_clone', array('id', '=', $id));
    }

    /**
     * Get seleted data
     * @return mixed
     */
    public function data() {
        return $this->_data;
    }

    /**
     * Confirm exist same user at group
     * @param $qrcode : is name to confirm
     * @return bool
     */
    public function isSameQR($qrcode) {
        if($qrcode) {
            $data = $this->_db->get('plants', array('qr_code', '=', $qrcode));

            if($data->count()) {
                $this->_data = $data->first();
                $this->_count = $data->count();
                return true;
            }
        }
        return false;
    }
    public function isSamePlantUID($plant_UID) {
        if($plant_UID) {
            $data = $this->_db->get('plants', array('plant_UID', '=', $plant_UID));

            if($data->count()) {
                $this->_data = $data->first();
                $this->_count = $data->count();
                return true;
            }
        }
        return false;
    }

}