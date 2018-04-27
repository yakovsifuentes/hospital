<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facedes\DB;
use App\User;


class JwtAuth{

	public $key;

	public function __construct(){
		$this->key = 'clave-secreta-issste';
	}

	public function signup($name, $password, $getToken = null){

		$user = User::where(
			array(
				'name' => $name,
				'password' => $password
			))->first();

		$signup = false;

		if(is_object($user)){			
			$signup = true;		
		}

		if($signup){			
			//Generar el token y devolverlo
			$token = array(
			'sub' => $user->id,
			'name' => $user->name,
			'rol' => $user->rol,
			'iat' => time(),
			'exp' => time() + (7*24*60*60)
			);

		$jwt = JWT::encode($token, $this->key, 'HS256');
		$decoded = JWT::decode($jwt, $this->key, array('HS256'));

		if(is_null($getToken)){
			return $jwt;
		}else{
			return $decoded;
		}

		}else{
			// Devolver un error
			return array('status' => 'error', 'message' => 'Login ha fallado');
		}
	}


	public function checkToken($jwt, $getIdentity = false){
		$auth = false;

		try{
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));

		}catch(\UnexpectedValueException $e){
			$auth = false;
		}catch(\DomainException $e){
			$auth = false;
		}

		if(isset($decoded->sub) && is_object($decoded) && (isset($decoded->sub)) ){
			$auth = true;	
		}else{
			$auth = false;
		}

		if($getIdentity){
			return $decoded;
		}

		return $auth;
	}	






}