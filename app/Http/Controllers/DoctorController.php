<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Helpers\JwtAuth;
use App\Doctor;
use App\specialty;
use App\Helpers\AdminAuth;

class DoctorController extends Controller
{

    // Consultar todos los doctores
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


    // Metodo para dar de alta un doctor
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

    // Metodo para visualizar un doctor en particular
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
    
    // Metodo para actualizar un doctor
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

            $data = array(
                'doctor' => $doctor,
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

    // Eliminar un doctor en especifico
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
