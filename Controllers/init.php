<?php

error_reporting(E_ALL ^ E_NOTICE);
session_start();


$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'localhost',
        'db' => 'malheiro_farm',
        'username' => 'root',
        'password' => ''
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800 // time in seconds
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
);

/**
 * Auto Load
 */
spl_autoload_register(function($class) {
    require_once '../Entity/' . $class . '.php';
});

require_once '../Logic/sanitize.php';

$p_general = new General();

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    // user asked to be remembered 
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    // $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    // if($hashCheck->count()) {
    //     // hash matches log user in
    //     // make sure the db field is large enough for hash - 64 charecters //
    //     $user = new User($hashCheck->first()->user_id);
    //     $user->login();
    // }
}
