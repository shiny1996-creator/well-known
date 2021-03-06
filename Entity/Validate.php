<?php

/**
 * Class Validate
 */

class Validate {
    
    private $_passed = false,
            $_errors = array(),
            $_db = null;
    
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /**
     * check existing
     * @param $source
     * @param array $items
     * @return $this
     */
    public function check($source, $items = array()) {
        foreach($items as $item => $rules) {
            foreach($rules as $rule => $rule_value) {
                
                $value = trim($source[$item]);
                $item = escape($item);
                
                if($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if(!empty($value)) {
                    switch($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            } 
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("{$item} can only be a maximum of {$rule_value} characters.");
                            } 
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}.");
                            }
                        break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()) {
                                $this->addError("{$item} already exists.");
                            }
                        break;
                    }
                }
                
            }
        }
        
        if(empty($this->_errors)) {
            $this->_passed = true;
        }
        
        return $this;
    }

    /**
     * add error
     * @param $error
     */
    private function addError($error) {
        $this->_errors[] = $error;
    }

    /**
     * get error
     * @return array
     */
    public function errors() {
        return $this->_errors;
    }

    /**
     * get result of comfirm
     * @return bool
     */
    public function passed() {
        return $this->_passed;
    }
}