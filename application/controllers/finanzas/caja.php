<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";
class Caja extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");   
        $this->load->model(finanzas.'caja_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(maestros.'tipodocumento_caja_model');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
 
    public function index()
    {
        $this->load->view(ventas."ot_listar");
    }
    
    public function listar(){
        $this->load->view(ventas."ot_listar");
    }

    public function nuevo(){     
        $this->load->view(ventas."ot_nuevo");
    }
	
    public function editar(){

    }
    
    public function grabar(){

        
    }
	
    public function eliminar(){

    }

    public function ver(){
        $numero         = $this->input->get_post('numero');
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->numero = $numero;
        $caja        = $this->caja_model->obtener($filter,$filter_not);
        $fecapertura  = $caja->FecApertura;
        $voucher      = $caja->AccionCaja;
        $saldo        = $caja->SalCaja;
        $reembolso    = $caja->RmbCaja;
        $monto        = $caja->MtoCaja;
        $feccierre    = $caja->FecCie;
        $gasto        = $caja->Gasto;
        /*Cabecera*/
        $this->load->library("fpdf/pdf");
        $CI = & get_instance();
        $CI->pdf->FPDF('P');
        $CI->pdf->AliasNbPages();
        $CI->pdf->AddPage();
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        $CI->pdf->SetFont('Arial','B',11);
        $CI->pdf->SetFillColor(216,216,216);
        $CI->pdf->Image('img/mimco_ruc.jpg',10,8,55);
        //$CI->pdf->Image('images/anadir.jpg',11,4,30);
        $CI->pdf->Cell(0,8, "CAJA No ".$numero,0,1,"C",0);
        $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
        
        $CI->pdf->SetFont('Arial','B',7);
        $CI->pdf->Cell(70,8, "" ,0,1,"L",0);
        $CI->pdf->Cell(70,5, "FECHA INICIO: ".$fecapertura ,0,0,"L",0);
        $CI->pdf->Cell(70,5, "FECHA CIERRE : ".$feccierre ,0,0,"L",0);
        $CI->pdf->Cell(70,5, "MONEDA : SOLES" ,0,1,"L",0);
        $CI->pdf->Cell(50,5, "REEMBOLSO : ".number_format($reembolso,2) ,0,0,"L",0);
        $CI->pdf->Cell(50,5,  "SALDO CAJA : ".number_format($saldo,2) ,0,0,"L",0);
        $CI->pdf->Cell(50,5, "MONTO : ".number_format($monto,2) ,0,0,"L",0);
        $CI->pdf->Cell(50,5, "GASTOS : ".number_format($gasto,2) ,0,1,"L",0);
        $CI->pdf->SetTextColor(255,255,255);
        $CI->pdf->SetFillColor(192,192,192);
        /*Detalle*/
        $CI->pdf->SetFont('Arial','',6);        
        $CI->pdf->Cell(0,5, "" ,0,1,"R",0);
        $CI->pdf->SetFillColor(0,0,128);
        $CI->pdf->SetTextColor(255,255,255); 
        $CI->pdf->Cell( 5,5,"Item",0,0,"C",1);
        $CI->pdf->Cell( 20,5,"Nro OT",0,0,"C",1);
        $CI->pdf->Cell(45,5,"Referencia",0,0,"C",1);
        $CI->pdf->Cell( 60,5,"Proveedor",0,0,"C",1);
        $CI->pdf->Cell( 10,5,"Dcto",0,0,"C",1);
        $CI->pdf->Cell( 20,5,"Nro.Dcto.",0,0,"C",1);
        $CI->pdf->Cell( 20,5,"Fecha Dcto",0,0,"C",1);
        $CI->pdf->Cell( 12,5,"Importe",0,1,"C",1);
        $CI->pdf->SetTextColor(0,0,0);        
        $importe_total = 0;
        $caja_det      = $this->caja_model->listar_detalle($filter,$filter_not,array("det.NroOperacion"=>"asc"));
        foreach($caja_det as $indice => $value){
            $nroOperacion = $value->NroOperacion;
            $codcliente   = $value->Codcli;
            $desOperacion = $value->DesOperacion;
            $subtotal     = $value->Subtotal;
            $importe      = $value->ImpOperacion;
            $tcambio      = $value->Tc;
            $tipdocref    = $value->TipDocRef;
            $seriedocref  = $value->SerieDocRef;
            $nrodocref    = $value->NroDocRef;
            $codgas       = $value->CodGas;
            $motivo       = $value->Motivo;
            $dirot        = $value->dirOt;
            $codot        = $value->CodOt;
            $nroot        = $value->NroOt;
            $tper         = $value->tPer;
            $fecemision   = $value->fecha2;
            $mo           = $value->Mo;
            $moneda       = $mo=='2'?"S/":"$";
            /*Cliente*/
            $filter2     = new stdClass();
            $filter2_not = new stdClass();
            if($tper=='02'){
                if(strlen(trim($codcliente))>6) $codcliente = substr(trim($codcliente),2,6);
                $filter2->codcliente = $codcliente;
                $oCliente  = $this->proveedor_model->obtener($filter2,$filter2_not);
                $razcli    = $oCliente->RazCli; 
            }
            elseif($tper=='03'){
                $filter2->codresponsable = substr(trim($codcliente),2,6);
                $oCliente  = $this->responsable_model->obtener($filter2,$filter2_not);
                $razcli    = isset($oCliente->nomper)?$oCliente->nomper:""; 
            }    
            /*Tipo rodumento referencia*/
            $tipodoc = $this->tipodocumento_caja_model->obtener($tipdocref);
            $tipodocref_nombre = $tipodoc->Des_Larga;
            $CI->pdf->Cell(5,5,$nroOperacion,1,0,"C",0);
            $CI->pdf->Cell(20,5,$nroot,1,0,"C",0);
            $CI->pdf->Cell(45,5,substr($dirot,0,33),1,0,"L",0);
            $CI->pdf->Cell(60,5,substr($razcli,0,46),1,0,"L",0);
            $CI->pdf->Cell(10,5,substr($tipodocref_nombre,0,2),1,0,"C",0);    
            $CI->pdf->Cell(20,5,$seriedocref."-".$nrodocref,1,0,"C",0);    
            $CI->pdf->Cell(20,5,trim($fecemision),1,0,"C",0);    
            $CI->pdf->Cell(12,5,$moneda." ".number_format($importe,2),1,1,"R",0); 
            $importe_total = $importe_total + $importe;
        }
        $CI->pdf->Cell(192,5,$moneda." ".number_format($importe_total,2),0,1,"R",0);
        $CI->pdf->Output();
    }

    public function buscar(){

    }
}
?>
