<?php header("Content-type: text/html; charset=utf-8"); if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type: text/html; charset=utf-8");
require_once "Spreadsheet/Excel/Writer.php";
class Servicios extends CI_Controller {
    var $entidad;
   
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");       
        
        $this->load->model(contabilidad.'servicios_model');
        $this->load->model(compras.'requiser_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(compras.'facturac_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(produccion.'tareo_model');
        $this->load->model(finanzas.'voucher_model');
        $this->load->model(finanzas.'caja_model');
        $this->load->model(ventas.'cliente_model');
        $this->load->model(ventas.'ctrlobras_model');
        $this->load->model(ventas.'partida_model');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index()
    {
        redirect("contabilidad/servicios/rpt_servicios_conta");    
    }

    public function rpt_servicios_conta(){

        $reporte = "";
                $filter14 = new stdClass();
                
              
                $reporte = $this->servicios_model->listar($filter14,new stdClass());
       
        
        $this->load->view(contabilidad."rpt_servicios_conta",$reporte);
    }
 
}
?>