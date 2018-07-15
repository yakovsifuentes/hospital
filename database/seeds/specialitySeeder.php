<?php

use Illuminate\Database\Seeder;
require_once 'PHPExcel/Classes/PHPExcel.php';

class specialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        
        
        
        $archivo = 'doctores_especialidad.xlsx';
        $inputFileType = PHPExcel_IOFactory::identify($archivo);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($archivo);
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 2; $row <= $highestRow; $row++){ 
            //echo $sheet->getCell("A".$row)->getValue()." - ";
            $sp = $sheet->getCell("B".$row)->getValue();            
                        
            $duplicated = DB::table('specialtys')->where('name', $sp)->first();
            
            if($duplicated == null && (!is_null ($sp))){
                
                DB::table('specialtys')->insert(array(
                    'name'=> $sp 
                ));
            }
            
         }

         for($row = 2; $row <= $highestRow; $row++){

            $name_doctor = $sheet->getCell("A".$row)->getValue();

            $duplicated_doctor = DB::table('doctors')->where('name', $name_doctor)->first();


            if($duplicated_doctor == NULL && (!is_null($name_doctor))){
                $sp = $sheet->getCell("B".$row)->getValue();

                $id_speciality = DB::table('specialtys')->select('id')->where('name', $sp)->first();
                
                $turn_doctor = $sheet->getCell("C".$row)->getValue();

                DB::table('doctors')->insert(array(
                        'name' => $name_doctor,
                        'id_specialty' => $id_speciality->id,
                        'turn' => $turn_doctor,
                        'patients_sub' => '8',
                        'weekend' => 'FALSE',
                        'status' => 'ACTIVO'
                ));

            }
            
         }
                 
         $this->command->info('tablas actualizadas correctamente');
    }

    
}
