<?php

namespace App\Http\Controllers;
use Mail;
use File;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function Backup(){

    	$file_backup = "BD_".date('Ymd').'.sql.gz';
    	$file_backup_2 = "BK_".date('dmY-his').'.sql.gz';

    	$file = "../../backup_bd/BD/".$file_backup;
    	$exists = is_file( $file );

    	if($exists){
    		//dd(base_path() . ' - '. public_path());

			File::move('/home/encticke/public_html/backup_bd/BD/'.$file_backup, '/home/encticke/public_html/backup_bd/BD/'.$file_backup_2);
			//File::move(public_path().'../../backup_bd/BD/'.$file_backup, public_path().'../../backup_bd/BD/'.$file_backup_2);

			$file = "../../backup_bd/BD/".$file_backup_2;
    		$exists = is_file( $file );
    		if ($exists) {
    			$backup = $file;

    		}else{
    			dd("Ocurrio un error al crear el backup.");
    		}


    		// OBTENER EL ULTIMO ZIP DEL CODE
    		//$file_code = "backup_bd/CODE/BK_".date("dmY-his").".zip";

    		$directorio = '/home/encticke/public_html/backup_bd/CODE/';
    		//$directorio = '../../backup_bd/CODE/';
    		$files 		= glob($directorio . '*.zip');
    		

			//Verifica la cantidad de ficheros y si es que hay que borrar y cual.
			if ( $files !== false ){
				//dd($files);
			    $cant = count( $files );
			    if($cant >= 1){

			    	array_multisort(
					array_map( 'filemtime', $files ),
					SORT_NUMERIC,
					SORT_DESC, //SORT_ASC
					$files
					);

			    	//unlink($files[0]);
			    	
			    	$a = substr($files[0], 27);
			    	$file_code = 'http://enc-ticketing.org/'.$a;
			    	//$file_code = 'http://enc-ticketing.org/'.$files[0];

					$creaBU 	= true;
			    }else{
			    	//áun no se llega a los 6 respaldos
			    	$creaBU 	= true;
			    	$file_code = "#";
			    	
			    }
			}else{
				//si no encuentra quiere decir que no hay ficheros y crea el back de todas formas.
				$creaBU 	= true;
				$file_code  = "#_";
			}

    		//

    		$dia = date('d/m/Y');
    		$hora = date('h:i');

    		$email = "nvaldivia@contraloria.gob.pe";
    		$email_2 = "hdelcarpio@contraloria.gob.pe";
    		$name = "Administrador TI";
    		$asunto = "Backup Ticketing 2.0";

    		$img = rand(1,2);

    		$datos_email = array(
                'email' => $email,
                'email_2' => $email_2,
                'name' => $name,
                'asunto'    => $asunto,
                'file'      => $file
            );

            $data = array(
                'backup'  => $backup,
                'dia'  => $dia,
                'hora'  => $hora,
                'img'	=> $img,
                'backup_link'	=> $file_code
            );


            Mail::send('email.backup', $data, function ($mensaje) use ($datos_email){
	            //$mensaje->from('admin@enc.pe','Admin');
	            $mensaje->to($datos_email['email'], $datos_email['name'])
	            ->subject($datos_email["asunto"]);
	            
	            $mensaje->attach($datos_email['file']);
	        });

	        Mail::send('email.backup', $data, function ($mensaje) use ($datos_email){
	            $mensaje->to($datos_email['email_2'], $datos_email['name'])->subject($datos_email["asunto"]);
	            $mensaje->attach($datos_email['file']);
	        });

	        /*Mail::send('emails.welcome', $data, function ($message) {
			    $message->from('us@example.com', 'Laravel');
			    $message->to('foo@example.com')->cc('bar@example.com');
			});*/

		
			File::move('/home/encticke/public_html/backup_bd/BD/'.$file_backup_2, '/home/encticke/public_html/backup_bd/BD/backup_history/'.$file_backup_2);
			//File::move(public_path().'../../backup_bd/BD/'.$file_backup_2, public_path().'../../backup_bd/BD/backup_history/'.$file_backup_2);
			//$files_his = glob('../../backup_bd/BD/backup_history/*'); //obtenemos el nombre de todos los ficheros

			$directorio_2 = '/home/encticke/public_html/backup_bd/BD/backup_history/';
    		
    		$files_his 		= glob($directorio_2 . '*.gz');
			

			if ( $files_his !== false ){
			    $cant = count( $files_his );
			    if($cant >= 60){

			    	array_multisort(
					array_map( 'filemtime', $files_his ),
					SORT_NUMERIC,
					SORT_ASC,//SORT_DESC
					$files_his
					);

			    	unlink($files_his[0]);
					$creaBU 	= true;
			    }else{
			    	//áun no se llega a los 6 respaldos
			    	$creaBU 	= true;
			    	
			    }
			}else{
				//si no encuentra quiere decir que no hay ficheros y crea el back de todas formas.
				$creaBU 	= true;
			}
			

	        return "<h1>El backup se ha generado exitosamente.</h1>";

	    }else{

				
	    	dd("No existe un backup para enviar.");
	    }


    }
}
