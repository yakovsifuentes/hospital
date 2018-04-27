<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Http\Requests;
use App\Helpers\JwtAuth;


class PatientController extends Controller
{
    // Mostrar todos los pacientes
    public function index(Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            
            $patient = Patient::all();
            
            $data = array(
                'patient' => $patient,
                'code' => 200,
                'status' => 'success'
            );

        }else{
            $data = array(
                'status' => 'error',
                'code' => 300,
                'message' => 'Login incorrecto'
            );
        }

        return response()->json($data);
    }

    // Registrar a un nuevo paciente
    public function store(Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            
            // Recoger los datos
            $json = $request->input('json',null);
            $params = json_decode($json);
            $params_array = json_decode($json,true);
            //Valdiar datos
            $validate = \Validator::make($params_array,[
                'name' => 'required',
                'rfc' => 'required',
                'phone' => 'required',
                'origin' => 'required'                
            ]);

            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }
            // Guardar datos
            $patient = new Patient();
            $patient->name = $params->name;
            $patient->rfc = $params->rfc;
            $patient->phone = $params->phone;
            $patient->origin = $params->origin;

            $patient->save();

            $data = array(
                'patient' => $patient,
                'status' => 'success',
                'code' => 200
            );
        
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'login incorrecto',
                'code' => 300
            );
        }

        return response()->json($data);
    }

    // Encontrar a un paciente en especifico
    public function show($id, Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){

            $patient = Patient::find($id);

            if($patient != null){
                $data = array(
                    'patient' => $patient,
                    'status' => 'success',
                    'code' => 200
                );

            }else{
                $data = array(
                    'status' => 'error',
                    'message' => 'Paciente no registrado',
                    'code' => 400
                );
            }            

        }else{
            $data = array(
                'status' => 'error',
                'message' => 'login incorrecto',
                'code' => 300
            );
        }
        
        return response()->json($data);
    }


    public function update($id, Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){

            // Recoger los datos
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json,true);
            
            // Validar los datos            
            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'rfc' => 'required',
                'phone' => 'required',
                'origin' => 'required'                
            ]);

            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }

            // Actualizar datos
            $patient = Patient::where('id',$id)->update($params_array);

            $data = array(
                'patient' => $patient,
                'status' => 'success',
                'code' => 200
            );
        
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'login incorrecto',
                'code' => 300
            );
        }            
        return response()->json($data);
    }


    public function destroy($id, Request $request){
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){

            $patient = Patient::find($id);

            if($patient != null){
                
                $patient->destroy();
                
                $data = array(
                    'doctor' => $patient,
                    'status' => 'success',
                    'code' => 200                    
                );
            }else{

                $data = array(
                    'message' => 'paciente no registrado',
                    'status' => 'success',
                    'code' => 200                    
                );
            }

        }else{
            
            $data = array(
                'status' => 'error',
                'message' => 'login incorrecto',
                'code' => 300
            );
        }

        return response()->json($data);
    }
}
