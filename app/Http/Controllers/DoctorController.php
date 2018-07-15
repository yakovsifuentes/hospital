<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Doctor;
use App\specialty;
use App\Helpers\AdminAuth;

class DoctorController extends Controller{

    // @Function: Consulta todos los registros de la tabla doctores.
    // @Params  : (Autorizathion)Token de usuario logueado.
    // @Return  : Json con todos los doctores registrados en la tabla doctores(200) / Error de logueo(300).
    public function index(Request $request){
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){
            $doctor = Doctor::all();
            $data = array(
                'doctor' => $doctor,
                'status' => 'success',
                'code' => 200
            );        
        }else{
            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'error',
                'code' => 300
            );        
        }
        return response()->json($data);            
    }

    //@Function: Registra un nuevo doctor en la tabla doctors travez de parametros enviados.
    //Params   : (Autorizathion) = Token de usuario logueado,(Json) = nombre completo del doctor(string),
    //           turno del doctor(string), numero de pacientes subsecuentes(numeric), 
    //           trabaja los fines de semana(boolean) y un id de la especialidad relacionada al doctor(numeric).
    //@Return  : Json de Error de logueo(300) / Falta de permisos para la operacion(400) / El nuevo registro almacenado(200). 
    public function store(Request $request){
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){
            // Validar permisos Administrador
            $adminAuth = new AdminAuth();
            $user = $adminAuth->auth($hash);

            if($user == true){
                // Recoger los datos
                $json = $request->input('json',null);
                $params = json_decode($json);
                $params_array = json_decode($json, true);
                // Validar los datos
                $validate = \Validator::make($params_array,[
                    'name' => 'required',
                    'turn' => 'required',
                    'patients_sub' => 'required',
                    'weekend' => 'required',
                    'status' => 'required',
                    'id_specialty' => 'required'
                ]);

                if($validate->fails()){
                    return response()->json($validate->errors(),400);
                }
                
                $doctor = New doctor();                
                $doctor->id_specialty = $params->id_specialty;
                $doctor->name = $params->name;
                $doctor->turn = $params->turn;
                $doctor->patients_sub = $params->patients_sub;
                $doctor->weekend = $params->weekend;
                $doctor->status = $params->status;
                $doctor->save();
                $data = array(
                    'doctor' => $doctor,
                    'status' => 'success',
                    'code' => 200
                );             
            }else{
                $data = array(
                    'message' => 'No tienes permisos de esta operacion',
                    'status' => 'error',
                    'code' => 400 
                );
            }
        }else{
            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'error',
                'code' => 300
            );    
        }
        return response()->json($data);
    }

    //Function: Muestra el registro de un doctor en particular a travez de su id.
    //Params  : (URL)id del doctor(numeric),(Autorizathion) Token de usuario logueado.
    //Return  : Json de Error de logueo(300) / Falta de permisos para la operacion(400) / registro del doctor buscado(200).
    public function show($id, Request $request){

        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken){

            $doctor = Doctor::find($id);
            if($doctor != null){

                $data = array(
                    'doctor' => $doctor,
                    'status' => 'success',
                    'code' => 200
                );

            }else{
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'doctor no registrado'
                );
            }

        }else{

            $data = array(
                'status' => 'error',
                'code' => 300,
                'message' => 'Login incorrecto'
            );
        }
        return response()->json($data);
    }
    
    //@Function: Actualiza el registro de un doctor en particular en la tabla doctors travez de parametros enviados.
    //Params   : (URL)id del doctor, (Autorizathion) = Token de usuario logueado,(Json) = nombre completo del doctor(string),
    //           turno del doctor(string), numero de pacientes subsecuentes(numeric), 
    //           trabaja los fines de semana(boolean) y un id de la especialidad relacionada al doctor(numeric).
    //@Return  : Json de Error de logueo(300) / Falta de permisos para la operacion(400) / El nuevo registro actualizado(200).
    public function update($id, Request $request){

        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            
            // Recoger los datos
            $json = $request->input('json',null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);

            // Validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'id_specialty' => 'required',
                'turn' => 'required',
                'patients_sub' => 'required',
                'weekend' => 'required',
                'status' => 'required'                
            ]);

            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }
            
            // Actualizar Datos
            $doctor = Doctor::where('id', $id)->update($params_array);
            $doctorData = Doctor::where('id',$id)->first();
            $data = array(
                'doctor' => $doctorData,
                'message' =>'Doctor Actualizado Correctamente',
                'status' => 'success',
                'code' => 200
            );
        }else{
            $data = array(
                'status'=> 'error',
                'code' => 300,
                'message' => 'Login incorrecto'
            );
        }
        return response()->json($data);
    }

    //Function: Elimina el registro de un doctor en particular a travez de su id.
    //Params  : (URL)id del doctor(numeric),(Autorizathion) Token de usuario logueado.
    //Return  : Json de Error de logueo(300) / Falta de permisos para la operacion(400) / registro del doctor eliminado(200).
    public function destroy($id, Request $request){

        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);               
        if($checkToken){
            $doctor = Doctor::find($id);
            if($doctor != null){
                $doctor->delete();
                $data = array(
                    'doctor' => $doctor,
                    'status' => 'success',
                    'code' => 200                    
                );
            }else{
                $data = array(
                    'status' => 'error',
                    'message' => 'Doctor no registrado',
                    'code' => 400
                );
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'Login incorrecto',
                'code' => 300
            );
        }
        return response()->json($data);
    }    
}
