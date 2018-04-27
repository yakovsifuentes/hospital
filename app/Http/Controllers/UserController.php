<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\User;

class UserController extends Controller{
    
    public function register(Request $request){
    	
    	// Recibir parametros
    	$json = $request->input('json', null);
    	$params = json_decode($json);

    	$name = (!is_null ($json) && isset($params->name)) ? $params->name : null;
    	$password = (!is_null ($json) && isset($params->password)) ? $params->password : null;
    	$rol = (!is_null ($json) && isset($params->rol)) ? $params->rol : null;		
    	
    	if(!is_null($name) && !is_null($password) && !is_null($rol)){

    		$user = new User();
    		$user->name = $name;
    		$user->rol = $rol;

    		// Encriptar el password
    		$pwd = hash('sha256',$password);
    		$user->password = $pwd;

    		$isset_user = User::where('name' , '=', $name)->first();

    		if(count($isset_user) == 0){

    			$user->save();

    			$data = array(
    				'status' => 'success',
    				'code' => 200,
    				'message' => 'El usuario se registro correctamente'
    			);

    		}else{
    			// Usuario duplicado
    			$data = array(
    				'status' => 'error',
    				'code' => 400,
    				'message' => 'No se puede registar, Usuario duplicado'
    			);
    		}

    	}else{

    		$data = array(
    			'status' => 'error',
    			'code' => 400,
    			'message' => 'usuario no creado correctamente'
    		);
    	}

    	return response()->json($data);
    }


    public function login(Request $request){
        
        $JwtAuth = new JwtAuth();

        // Recibir los datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);

		// Validar los datos enviados
        $name = (!is_null($json) && isset($params->name)) ? $params->name : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
		$getToken = (!is_null($json) && isset($params->getToken)) ? $params->getToken : null; 

		// cifrar el password
		$pwd = hash('sha256', $password);

		if(!is_null($name) && !is_null($password) && ($getToken == null || $getToken == 'false')){
			
			// Obtener el token codificado
			$singup = $JwtAuth->signup($name, $pwd);

		}elseif($getToken != null){
			
			// Obtener el token decodificado
			$singup = $JwtAuth->signup($name, $pwd, $getToken);

		}else{
			
			$singup = array(
				'status' => 'error',
				'message' => 'Envia tus datos por post'
			);
		 }
		 
		 return response()->json($singup);		
	}
	
}
