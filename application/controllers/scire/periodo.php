<?php
class Periodo extends CI_Controller {
    var $entidad;
    var $login;
    
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");
        $this->load->model(scire . 'planillas_model');        
        $this->load->model(scire . 'dvariables_model');
        $this->load->model(scire . 'periodo_model');
        $this->load->model(scire . 'personal_model');
        $this->load->model(scire . 'planillas_model');
        $this->load->model(scire . 'planilla_model');
        $this->load->model(scire . 'procesos_model');
        $this->load->model(scire . 'mes_model');
        $this->load->model(scire . 'ccosto_model');
        $this->load->model(scire . 'ccosto_conta_model');
        $this->load->model(scire . 'ctecorriente_model');
        $this->load->model(scire . 'asistencia_registro_model');
        $this->load->model(scire . 'tipo_trabajador_model');
        $this->load->model(scire . 'cargo_model');
        $this->load->model(scire . 'ejercicio_model');
        $this->load->model(scire . 'conceptos_model');
        $this->load->model(scire . 'proyecto_model');
        $this->load->model(scire . 'personalactivo_model');
    }
    
    public function obtener($periodo){
        $obj = new stdClass();
        $obj->periodo  = $periodo;
        $periodos = $this->periodo_model->listar($obj);
        $arrfInicio = explode("/",$periodos->fInicio);
        $arrfFin    = explode("/",$periodos->fFin);
        $periodos->fInicio = "21/".str_pad(($arrfInicio[1]-1),2,"0",STR_PAD_LEFT)."/".$arrfInicio[2];//fALTA
        $periodos->fFin    = "20/".$arrfFin[1]."/".$arrfFin[2];
        echo json_encode($periodos);
    }   
}
?>