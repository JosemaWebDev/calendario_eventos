<?php
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
    $a = intval($_SESSION[$nombre_session_app]["agenda"]['a']);
    $m = intval($_SESSION[$nombre_session_app]["agenda"]['m']);
    $M = $_SESSION[$nombre_session_app]["agenda"]['M'];
    $d = intval($_SESSION[$nombre_session_app]["agenda"]['d']);
    $D = $_SESSION[$nombre_session_app]["agenda"]['D'];
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

?> 