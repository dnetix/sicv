<?php

function public_assets($path_to_file){
    return '/public/'.$path_to_file;
}

/** Some Time Helpers, TO MAKE A NEW CLASS */

    function fecha_diff($fi, $ff = ''){

        $fechainicial = new DateTime($fi);
        if($ff != ''){
            $fechafinal = new DateTime($ff);
        }else{
            $fechafinal = new DateTime(date('Y-m-d'));
        }

        return $fechainicial->diff($fechafinal);

    }

    function month_diff($fi, $ff = ''){

        $dif = fecha_diff($fi, $ff);

        return ($dif->y * 12) + $dif->m;

    }

    function date_print_format($fecha){

        $dt_fecha = getdate(strtotime($fecha));

        $dias = array('Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'Sabado');
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        return $dias[$dt_fecha['wday']].', '.$dt_fecha['mday'].' de '.$meses[$dt_fecha['mon'] - 1].' de '.$dt_fecha['year'];

    }

    function date_contract_format($fecha){

        $dt_fecha = getdate(strtotime($fecha));

        $dias = array('Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'Sabado');
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        return $dt_fecha['mday'].' d&iacute;as, del mes de '.$meses[$dt_fecha['mon'] - 1].' del a&ntilde;o '.$dt_fecha['year'];

    }

    function get_month_name($mes, $minus = TRUE, $short = FALSE){

        if($minus){
            $mes--;
        }
        if($short){
            $meses = array('Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic.');
        }else{
            $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        }
        return $meses[$mes];

    }
