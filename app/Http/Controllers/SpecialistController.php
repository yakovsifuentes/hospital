<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\specialty;

class SpecialistController extends Controller
{

    // Consultar las especialidades
    public function index(Request $request){
    $speciality = Specialty::all();
    
    return response()->json(array(
        'speciality' => $speciality,
        'status' => 'success'
        ),200);  
    }
    
    
    // Registrar una especialidad
    public function store(Request $request){

        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
            
            // Recibir los datos
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);
            
            
            // Validacion
            $validate = \Validator::make($params_array,[
                'name' => 'required'
            ]);

            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }

            // Guardar la especialidad
            $speciality = new Specialty();
            $speciality->name = $params->name;

            $speciality->save();

            $data = array(
                'speciality' => $speciality,
                'status' => 'success',
                'code' => 200
                );

        }else{
           $data = array(
               'message' => 'Login incorrecto',
               'status' => 'error',
               'code' => 400
           );
        }

        return response()->json($data);    
    }


    // Metodo para actualizar especialidad
    public function update($id, Request $request){
        
        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){
        
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);

            // Validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);

            if($validate->fails()){
                return response()->json($validate->errors(),400);
            }

            //Actualizar el registro
            $speciality = Specialty::where('id', $id)->update($params_array);

            $data = array(
                'speciality' => $speciality,
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

    public function destroy($id, Request $request){

        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){

            // Validar
            $speciality = Specialty::find($id);

            if($speciality != null){
               
                $speciality->delete();

                $data = array(
                    'speciality' => $speciality,
                    'code' => 200,
                    'status' => 'success'
                );
               

            }else{
              
                $data = array(
                    'message' => 'Especiliadad no encontrada',
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

    public function show($id, Request $request){

        $hash = $request->header('Authorization', null);

        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if($checkToken){

            $speciality = Specialty::find($id);

            if($speciality != null){
                
                $data = array(
                    'speciality' => $speciality,
                    'status' => 'success',
                    'code' => 200               
                );

            }else{
                $data = array(
                    'message' => 'Objeto no encontrado',
                    'status' => 'Error',
                    'code' => 400
                );

            }            

        }else{
            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'Error',
                'code' => 300
            );
        }

        return response()->json($data);

    }
    
    
}
