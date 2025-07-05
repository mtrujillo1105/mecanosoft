<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Spreadsheet/Excel/Writer.php";

class Indicadores extends CI_Controller {
    
    var $entidad;
    var $login; 
//    
    public function __construct() {
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");    
        date_default_timezone_set('America/Los_angeles');
        $this->load->model(indicadores . 'indicadores_model');
        $this->load->model(compras.'ocompra_model');
        $this->load->model(almacen.'producto_model');
        $this->entidad = $this->session->userdata('entidad');
    }
    
    public function listar(){
        
        $kpi = $this->indicadores_model->getKPI();
        $areas = $this->indicadores_model->getAreas();
        $data['areas'] = $areas;
        $data['kpi'] = $kpi;
//        echo "<pre>";
//        print_r($kpi);
//        echo "</pre>";
//        exit;
        $this->load->view(indicadores . "indicadores_listar", $data);
        
    }
    
    /**
     * @param string $datestring Fecha ingresada de tipo string en formato mm/dd/YYYY
     * @return date Retorna la el dato en formato fecha
     */
    public function convertirFecha($datestring) {
//        $fecha= $datestring;
//        $fecha = explode("/", $fecha);
//        $mes = $fecha[0];
//        $dia = $fecha[1];
//        $anio = $fecha[2];
//        $new_fec = $anio . "/" . $mes . "/" . $dia;
//        $timestamp = strtotime($new_fec);
//        return $timestamp;
//        return date('d/m/Y', $timestamp);
        
        $fecha= $datestring;
        $fecha = explode("/", $fecha);
        $mes = $fecha[0];
        $dia = $fecha[1];
        $anio = $fecha[2];
        $new_fec = $dia. "/" . $mes . "/" . $anio;
        return $new_fec;
    }
    
    public function save() {
        $data = array(
            'kpi_det_date' => $this->convertirFecha($_POST['fecCreacion']),
            'kpi_code' => (int)$_POST['kpiCode'],
            'kpi_det_value' => $_POST['valorReal'], 
            'kpi_det_date_reg' => $_POST['fecRegistro'],
            'kpi_min_value' => $_POST['valorMin'],
            'kpi_max_value' => $_POST['valorMax'],
            'kpi_per_code' => (int)$_POST['perCode']
        );
        $this->indicadores_model->saveKPIDet($data);
    }
    
    public function recuperarIndicador($tipo,$fini,$ffin) {
//        echo "HOLA";
        echo $tipo . "<br>";
        switch($tipo) {
            case '08': $this->kpi08($fini,$ffin);
                break;
            case '09': $this->kpi08($fini,$ffin);
                break;
            case '10': $this->kpi09();
                break;
            case '11': $this->kpi09();
                break;
            case '12': $this->kpi09();
                break;
            case '13': $this->kpi09();
                break;
            default : exit;
        }
    }
    
    public function restarFechas($ini,$fin) {
        if($this->entidad == '01'){
            $fini = explode("-", $ini);
            $ffin = explode("-", $fin);
            $dini = $fini[2];
            $mini = $fini[1];
            $aini = $fini[0];
            $dfin = $ffin[2];
            $mfin = $ffin[1];
            $afin = $ffin[0];
            
            $timestamp1 = mktime(0, 0, 0, $mini, $dini, $aini);
            $timestamp2 = mktime(0, 0, 0, $mfin, $dfin, $afin);
            $segundos_diferencia = $timestamp2 - $timestamp1;
            $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
            return $dias_diferencia;
        }
        elseif($this->entidad == '02'){
            $fini = explode("/", $ini);
            $ffin = explode("/", $fin);
            $dini = $fini[0];
            $mini = $fini[1];
            $aini = $fini[2];
            $dfin = $ffin[0];
            $mfin = $ffin[1];
            $afin = $ffin[2];

            $timestamp1 = mktime(0, 0, 0, $mini, $dini, $aini);
            $timestamp2 = mktime(0, 0, 0, $mfin, $dfin, $afin);
            $segundos_diferencia = $timestamp2 - $timestamp1;
            $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
            return $dias_diferencia;
        }
    }
    
    public function kpi08($fini,$ffin) {
        $resultado = $this->indicadores_model->getOC($fini,$ffin);
        $fila = "";
        foreach($resultado as $indice=>$value){
            $codpro = $value->codpro;
            $serie_req = $value->serie_requerimiento;
            $numero_req = $value->numero_requerimiento;
            $fecha_req = $value->fecha_requerimiento;
            $serie_sc = $value->serie_sc;
            $numero_sc = $value->numero_sc;
            $fecha_sc = $value->fecha_sc;
            $serie_oc = $value->serie_oc;
            $numero_oc = $value->numero_oc;
            $fecha_oc = $value->fecha_oc;
            $fecha_apro = $value->fecha_aproboc;
            $fecha_reg = $value->fregistro_oc;
//            $RucCli = $value->RucCli;
            $productos = $this->producto_model->getProductoByCodigo($codpro);
            $despro = !isset($productos->despro) ? '' : $productos->despro;
            $neas = $this->indicadores_model->listarDetalle($serie_oc,$numero_oc,$codpro);
            if (count($neas) > 0) {
                foreach ($neas as $indice2 => $value2) {
                    $serie_nea = $value2->seriedoc;
                    $numero_nea = $value2->nrodoc;
                    $fecha_nea = $value2->fec_doc;
                    $fecha_guia = $value2->fecmov;
                    $fecha_reg2 = $value2->fec_doc;
                    $fila.="<tr>";
                    $fila.="<td>" . $serie_oc . "</td>";
                    $fila.="<td>" . $numero_oc . "</td>";
                    $fila.="<td>" . $fecha_oc . "</td>";
                    $fila.="<td>" . $fecha_apro . "</td>";
                    $fila.="<td>" . $fecha_reg . "</td>";
                    $fila.="<td>" . $serie_nea . "</td>";
                    $fila.="<td>" . $numero_nea . "</td>";
                    $fila.="<td>" . $fecha_nea . "</td>";
                    $fila.="<td>" . $fecha_guia . "</td>";
                    $fila.="<td>" . $fecha_reg2 . "</td>";
                    $fila.="<td>" . $codpro . "</td>";
                    $fila.="<td align='left'>" . $despro . "</td>";
                    $fila.="<td>" . floor($this->restarFechas($fecha_oc, $fecha_reg2)) . "</td>";
                    $fila.="</tr>";
                }
            }
            else {
                $fila.="<tr>";
                $fila.="<td>" . $serie_oc . "</td>";
                $fila.="<td>" . $numero_oc . "</td>";
                $fila.="<td>" . $fecha_oc . "</td>";
                $fila.="<td>" . $fecha_apro . "</td>";
                $fila.="<td>" . $fecha_reg . "</td>";
                $fila.="<td>&nbsp;</td>";
                $fila.="<td>&nbsp;</td>";
                $fila.="<td>&nbsp;</td>";
                $fila.="<td>&nbsp;</td>";
                $fila.="<td>&nbsp;</td>";
                $fila.="<td>&nbsp;</td>";
                $fila.="<td>&nbsp;</td>";
                $fila.="<td>&nbsp;</td>";
                $fila.="</tr>";
            }
        }
        $data['fila'] = $fila;
        $this->load->view(indicadores."contenido",$data);
    }
    
    public function kpi09() {
        
    }
    
    public function kpi10() {
        
    }
}