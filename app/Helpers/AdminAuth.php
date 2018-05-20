<?php

namespace App\Helpers;
use Firebase\JWT\JWT;
use App\Helpers\JwtAuth;

class AdminAuth{
    
    public $key;

    public function __construct(){
        $jwt = new JwtAuth();
        $this->key = $jwt->key;
    }

    public function auth($jwt){
        $AdminAuth = 'false';
        
        try{
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));

		}catch(\UnexpectedValueException $e){
			$AdminAuth = false;
		}catch(\DomainException $e){
			$AdminAuth = false;
        }
        
        if(isset($decoded->sub) && is_object($decoded) && (isset($decoded->rol) && ($decoded->rol == 'administrador')) ){
            $AdminAuth = true;	
		}else{
			$AdminAuth = false;
        }
        return $AdminAuth;

    }

}
?>