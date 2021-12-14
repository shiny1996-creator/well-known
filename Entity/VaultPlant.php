<?php

/**
 * Class VaultPlant
 */
class VaultPlant {

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

    public function CreateRelationVaultRoomAndPlant($flowerRoomID,$createdPlantQRCode) {
        $createdPlantID = $this->getValueOfAnyTable('plants','qr_code','=',$createdPlantQRCode);
        $createdPlantID = $createdPlantID->results();
        $this->deleteValueOfAnyTable('index_vault','plant_id','=',$createdPlantID[0]->id);
        if(!$this->_db->insert('index_vault', array(
            'room_id' => $flowerRoomID,
            'plant_id' => $createdPlantID[0]->id,
        ))) {
            throw new Exception('There was a problem inserting......');
        }
    }

    /**
     * @param $vaultRoomID
     * @return bool|\bool\|DB|mixed|null
     */
    //dd
    public function getlotIDListFromVaultRoomID($vaultRoomID) {
        $lotIDList = $this->getValueOfAnyTable('index_vault','room_id','=',$vaultRoomID);
        $lotIDList = $lotIDList->results();
        return $lotIDList;
    }



    /**
     * register history of user process on site
     * @param null $action : is action of permissions
     * @param null $moduleName : is module name of permissions
     * @param null $channel :  is channel of permissions
     * @param null $type : type of permissions
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
        if(!$this->_db->update('vault', $id, $fields)) {
            throw new Exception('There was a problem updating.');
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
            $data = $this->_db->get('room_vault', array('id', '=', $id));

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
    public function getValueOfAnyTable($table,$field,$symbol,$key){

        $data = $this->_db->get($table, array($field, $symbol, $key));

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

        $data = $this->_db->get('room_vault', array('1', '=', '1'));
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
        return $this->_db->delete('room_vault', array('id', '=', $id));
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