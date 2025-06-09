<?php
    session_start();
    require_once 'cabeceras.ajax.php';
    require_once '../../funcionalidad/funciones.php';
    require_once '../../funcionalidad/config.php';
    require_once '../../funcionalidad/conexion_ddbb.php';

    if($_SERVER["REQUEST_METHOD"]=="POST"){
         $respuesta_ajax=[];

        foreach ($_POST as $key => $value) {
           $_POST[$key]= htmlspecialchars(addslashes(trim($value)));
        }


        if (!isset($_SESSION[$nombre_session_app]["agenda"]['a'])||!isset($_SESSION[$nombre_session_app]["agenda"]['m'])||!isset($_SESSION[$nombre_session_app]["agenda"]['M'])||!isset($_SESSION[$nombre_session_app]["agenda"]['d'])||!isset($_SESSION[$nombre_session_app]["agenda"]['D'])) {
            $_SESSION[$nombre_session_app]["agenda"]['a']=intval(date("Y",time()));
            $_SESSION[$nombre_session_app]["agenda"]['m']=intval(date("n",time()));
            $_SESSION[$nombre_session_app]["agenda"]['M']=date("m",time());
            $_SESSION[$nombre_session_app]["agenda"]['d']=intval(date("d",time()));
            $_SESSION[$nombre_session_app]["agenda"]['D']=date("d",time());
      
        }
        if (!isset($_SESSION[$nombre_session_app]["agenda"]['ocultar_tareas_realizadas'])) {
            $_SESSION[$nombre_session_app]["agenda"]['ocultar_tareas_realizadas']=true;
        }

        no_vacio($_POST["fecha"])==true ? $respuesta_ajax["fecha"]=["OK","fecha relleno",'fecha']:$respuesta_ajax["fecha"]=["KO","fecha vacia","fecha"];
    
        if ($respuesta_ajax["fecha"][0]=="OK") {
          
            $a=intval(date("Y",strtotime($_POST["fecha"])));
            $m=intval(date("n",strtotime($_POST["fecha"])));
            $M=date("m",strtotime($_POST["fecha"]));
            $d=intval(date("d",strtotime($_POST["fecha"])));
            $D=date("d",strtotime($_POST["fecha"]));

                $_SESSION[$nombre_session_app]["agenda"]['a']=$a;
                $_SESSION[$nombre_session_app]["agenda"]['m']=$m;
                $_SESSION[$nombre_session_app]["agenda"]['M']=$M;
                $_SESSION[$nombre_session_app]["agenda"]['d']=$d;
                $_SESSION[$nombre_session_app]["agenda"]['D']=$D;

                $fecha = new DateTime("{$a}-{$m}-1"); 
                $cantidadDias = $fecha->format("t");
                $N=$fecha->format('N');
                $hoy = new DateTime(); 
                $hoy = $hoy->format("Y-m-d"); 

                $meses = [1 => "enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"]; 
                $marcadoresDias = ["L","M","X","J","V","S","D"];

                $dia_semana=$N+$d-2;
                $fecha_post = new DateTime("{$a}-{$m}-{$d}"); 
                $fecha_post = $fecha_post->format("Y-m-d"); 
                    while ($dia_semana>6) {
                        $dia_semana=$dia_semana-7;
                    }

                $dia_semana_post=$marcadoresDias[$dia_semana];


                $eventos=[];
                $diasEventos = [];
                $agenda=[];

                $consulta="SELECT *, DAY(fecha_evento) as dia , MONTH(fecha_evento) as mes , YEAR(fecha_evento) as year , HOUR(fecha_evento) as hora, MINUTE(fecha_evento) as minuto  FROM agenda WHERE MONTH(fecha_evento) LIKE ? AND YEAR(fecha_evento) LIKE ? AND borrado LIKE 0 ORDER BY fecha_evento ASC";
    $consulta_preparada= $conexion->stmt_init();
    if (!$consulta_preparada->prepare($consulta)) {
      die($consulta_preparada->error);
    }else{
      $consulta_preparada->bind_param("ss",$m,$a);
      $consulta_preparada->execute();
      $resultado=$consulta_preparada->get_result();
      if ($resultado->num_rows>0) {
        while($fila = $resultado->fetch_assoc()){

            array_push($eventos,$fila);
            array_push($diasEventos,$fila["dia"]);

           
        }
      }
    }
    
            $respuesta_ajax["post"]= $_POST;
            $respuesta_ajax["session"]= $_SESSION[$nombre_session_app];
            $respuesta_ajax["eventos"]=$eventos;
            $respuesta_ajax["diasEventos"]=$diasEventos;
            $respuesta_ajax["marcadoresDias"]=$marcadoresDias;
            $respuesta_ajax["cantidadDias"]=$cantidadDias;
            $respuesta_ajax["empezar_dia"]=$fecha->format('N');
            $respuesta_ajax["year"]=$fecha->format("Y");
            $respuesta_ajax["mes"]=$meses[$fecha->format("n")];
            $respuesta_ajax['d']=$d;
            $respuesta_ajax['D']=$D;
            $respuesta_ajax['a']=$a;
            $respuesta_ajax['m']=$m;
            $respuesta_ajax['M']=$M;
            $respuesta_ajax['N']=$N;
            $respuesta_ajax["dia_semana_post"]= $dia_semana_post;
            $respuesta_ajax["fecha_post"]= $fecha_post;
            $respuesta_ajax["hoy"]= $hoy;
            $respuesta_ajax["agenda"]=$agenda;
        
        }
        header('Content-type: application/json');
        echo json_encode($respuesta_ajax);
    }
    else{
        $respuesta_ajax["error"]=["KO","sin permisos","error"];
         header('Content-type: application/json');
        echo json_encode($respuesta_ajax);
        exit();
    }
?>