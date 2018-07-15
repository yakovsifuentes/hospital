<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Carbon\Carbon;
use App\Doctor;
use App\Patient;
use App\Appointment;
class AppointmentController extends Controller{

    public $vesperinoMin = "08:00:00";
    public $vesperinoMax = "12:30:00";

    public $matutinoMin = "02:00";
    public $matutinoMax = "08:00";

    public $cancelado = "cancelado";


    public function index($rfc){
      $patient = new Patient();

      $array_patient = Patient::where('rfc','=',$rfc)->first();

      if($array_patient){
          $data = array(
            'patient' => $array_patient,
            'message'=>'usuario subsecuente',
            'code'=> 200
          );
      }else{
        $data = array(
          'message' => 'Paciente de primera vez',
          'code' => 200
        );
      }

      return response()->json($data);
    }


    public function store(Request $request){

      
      $json = $request->input('json', null);
      $params = json_decode($json);
        
        $nombre = $params->name;
        $rfc = $params->rfc;
        $phone = $params->phone;
        $turn = $params->turn;
        $type_patient = $params->type_patient;
        $doctor = $params->doctor;
        $speciality = $params->specialist;

        date_default_timezone_set('America/Mexico_City');
        $actualDate = Carbon::now();
        
        $vespertinoMin = Carbon::create(null, null, null, 8, 00, 00);
        $vesperinoMax = Carbon::create(null, null, null, 12, 30, 00); 
        $matutinoMin = Carbon::create(null, null, null, 14, 00, 00);
        $matutinoMax = Carbon::create(null, null, null, 20, 00, 00);
        
        $tomorrow =$vesperinoMin->addDay();
        $date_tomorrow = $tomorrow->toDateString();


        $appointment = new Appointment();
        

        DB::table('festival')->where('date_festival',$date_tomorrow);
                






















      if($turn == 'VESPERTINO'){

        
        if(type_patient == 'PRIMERA VEZ'){
          /*
          $id_doctor = DB::table('doctors')->select('id')->where('name',$doctor);
          
          $total_appointment = DB::table('appointments')
          ->select(DB::raw('COUNT(id)'))
          ->where('id_doctor','=',$id_doctor)
          ->where('type_patient','=', $)
          ->where('date_appointment','=',$date_tomorrow);

*/

        }else{

        }
        
        



















          //return "es turno vespertirno ".$time;
          
          // Validar la hora actual 
          //$fechas = DB::table('appointments')->get();
          
          //return var_dump($fechas[1]);
          //foreach($fechas as $f => $fecha) {
            
            //$fecha->date_appointment
          //}
          
          //$first->addHours(8); 
          //$hora =  $first->toDateTimeString();

          
          $vespertino_inicial = $vespertinoMin->toDateTimeString(); //string
          var_dump($vespertinoMin->hour);
          die();
          $citas_totales = DB::table('appointments')->where('date_appointment',$actualDate);






          $results = DB::table('appointments')->where('date_appointment',$vespertino_inicial)->first();
          
          if($results){
            
            $data = array(
              'patient' => $results,
              'message'=>'si hay result',
              'code'=> 200
            );
          }else{

            $data = array(
              'patient' => $results,
              'message'=>'no hay result',
              'code'=> 200
            );

          }

          return response()->json($data);




         










          
          //¿es dia festivo?

          //¿Es fin de semana?

          //Busqueda de cita por dia
          //$actualDate->addDay();
          
          //Busqueda de cita por hora


          // Busqueda de Cita cada 20 Minutos dentro de una hora    
          /*    
        $i=0;
        while($i<=60){
          $i+20;
        }

        
        $array_appointment = Appointment::where('appointment_date','=', $date)->where('appointment_time','=',$time);

        if($array_appointment){
          
        }*/
      }else{
        return "Es turno Matutino";
      }
      


        









        /*
        $medico = new Doctor();
        $array_doctor = Doctor::where('name','=',$doctor)->first();                   
        $array_appointment = Appointment::where();

*/

      

        //buscar dias no registrados en base al horario del doctor, 
        //tomando en cuenta si el doctor trabaja en fin de semana y el numero de pacientes sub-secuentes 




        /*
        $date = Carbon::now();
        $date = $date->toDateTimeString();

        $Appointment = new Appointment();
        */

        //Revisar citas canceladas
        //$dateCancelled = Appointment::where('status',$this->cancelado)->first();

        //var_dump($dateCancelled);
        //return $result = Carbon::createFromDate(null, 12, 25);



    }




    // Metodo para registrar una cita
    // Metodo para buscar una cita en concreto
    // Metodo para editar una cita
    // Metodo para eliminar una cita
    // Metodo para cancelar una cita

    // Dar de alta servicio: para autenticar usuario para eliminacion y cancelacion de citas
    // Dar de alta servicio: para busqueda de fecha-hora citas

}
