<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Facturac extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");  
        $this->load->model(almacen.'producto_model');
        $this->load->model(compras.'facturac_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(compras.'proveedor_model');
        $this->load->model(maestros.'formapago_model');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index(){
        
    }
    
    public function listar(){
        $this->load->view(ventas."ot_listar");
    }
    
    public function obtener(){
        
        $this->load->view(ventas."ot_listar");
    }    
    
    public function ver(){
        $numero         = $this->input->get_post('numero');
        $serie          = $this->input->get_post('serie');
        $tipo           = 'FV';
        $alaorden       = $this->input->get_post('codpartida');
        $codot          = $this->input->get_post('codot');
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->serie  = $serie;
        $filter->numero = $numero;
        $filter->codot  = $codot;
        $filter->rucproveedor=$alaorden;
        $facturas       = $this->facturac_model->obtenerx($filter,$filter_not);
        //$facturas       = $this->facturac_model->obtener_dbf($filter,$filter_not);
        if(isset($facturas->NroDoc)){
            $ruccli    = $facturas->RucCli;
            $seroc     = $facturas->SerOC;
            $nrooc     = $facturas->NroOC;
            $serieref  = $facturas->SerieDocRef;
            $numeroref = $facturas->NroDocRef;
            $tipdocref = $facturas->TipDocRef;
            $fecvcto   = $facturas->FecVcto;
            $fec_reg   = $facturas->Fec_Reg;
            $mo        = $facturas->Mo;
            $codot     = $facturas->CodOt;
            $forpago   = $facturas->ForPago;
            $fecpago   = $facturas->FecPago;
            $filter2     = new stdClass();
            $filter2_not = new stdClass();
            $filter2->codot = $codot;
            $ots         = $this->ot_model->obtenerg($filter2,$filter2_not);
            $nroOt       = $ots->NroOt;
            $dirOt       = $ots->DirOt;
            $ot       = $ots->CodOt;
            $moneda      = $mo==2?"NUEVOS SOLES":"DOLARES AMERICANOS";
            $filter3     = new stdClass();
            $filter3_not = new stdClass();
            $filter3->ruccliente = $ruccli;
            $proveedores = $this->proveedor_model->obtener($filter3,$filter3_not);
            $razcli      = $proveedores->RazCli;
            /*Cabecera*/
            $this->load->library("fpdf/pdf");
            $CI = & get_instance();
            $CI->pdf->FPDF('P');
            $CI->pdf->AliasNbPages();
            $CI->pdf->AddPage();
            $CI->pdf->SetTextColor(0,0,0);
            $CI->pdf->SetFillColor(255,255,255);
            $CI->pdf->SetFont('Arial','B',11);
            $CI->pdf->SetTextColor(0,0,0);
            $CI->pdf->SetFillColor(216,216,216);
            
            $CI->pdf->Image('img/mimco_ruc.jpg',10,8,55);
            $CI->pdf->Cell(0,8, "FACTURA No ".$serie."  -  ".$numero,0,1,"C",0);
            $CI->pdf->Cell(120,15, "" ,0,1,"L",0);
             $CI->pdf->SetFont('Arial','B',7);
            $CI->pdf->Cell(60,5, "NRO OT : ".$nroOt ,0,0,"L",0);
            $CI->pdf->Cell(60,5, "REQ.SERVICIO : ".$seroc."  -  ".$nrooc ,0,0,"L",0);
            $CI->pdf->Cell(60,5,  "FECHA REGISTRO :  ".$fec_reg,0,1,"L",0);
            $CI->pdf->Cell(60,5, "TIPO DOC. REF. : ".$tipdocref ,0,0,"L",0);
            $CI->pdf->Cell(60,5,  "NRO DOC. REF. :".$serieref."-".$numeroref ,0,0,"L",0);
            $CI->pdf->Cell(60,5, "MONEDA : ".$moneda ,0,1,"L",0);
            $CI->pdf->Cell(120,5, "PROVEEDOR : ".$ruccli."   -   ".$razcli ,0,0,"L",0);
            $CI->pdf->Cell(120,5, "FECHA VENC. : ".$fecvcto ,0,1,"L",0);
            
            $formapago = $this->formapago_model->obtener($forpago);
            $fp=$formapago->Des_Larga;
            $CI->pdf->Cell(120,5, "FORMA PAGO : ".$fp ,0,0,"L",0);
            $CI->pdf->Cell(60,5, "FECHA PAGO. : ".$fecpago ,0,1,"L",0);
            $CI->pdf->SetTextColor(255,255,255);
            $CI->pdf->SetFillColor(192,192,192);
            /*Detalle*/
            $CI->pdf->Cell(0,5, "" ,0,1,"R",0);
            $CI->pdf->SetFillColor(0,0,128);
           
            $CI->pdf->Cell( 10,5,"Cant",0,0,"C",1);
            $CI->pdf->Cell(25,5,"Codigo",0,0,"C",1);
            //$CI->pdf->Cell( 10,5,"Unidad",0,0,"C",1);
            $CI->pdf->Cell( 90,5,"Descripcion",0,0,"C",1);
            $CI->pdf->Cell( 20,5,"P.Unitario",0,0,"C",1);
            $CI->pdf->Cell( 20,5,"P.Venta",0,0,"C",1);
            $CI->pdf->Cell( 15,5,"OT",0,1,"C",1);
            $CI->pdf->SetFont('Arial','B',7);
            $CI->pdf->SetTextColor(0,0,0);
            $filter4     = new stdClass();
            $filter4_not = new stdClass();        
            $filter4->serie  = $serie;
            $filter4->numero = $numero;
            
            
            
           $filter4->ruc  = $ruccli;
            $filter4->nrooc  = $nrooc;
            $facturas_det    = $this->facturac_model->listar_detalle1($filter4,$filter4_not);
            
           // print_R($facturas_det);die;
           // print_r($facturas_det);die;
            $subtotal=0;
            foreach($facturas_det as $indice => $value){
                $cantidad  = $value->CantSolRep;
                $codigo    = $value->CodPro;
                
               // print_r($codigo);
                $punitario = $value->PrecUnit;
                $codot2    = $value->ots;
                $filter6        = new stdClass();
                $filter6_not    = new stdClass();
                $filter6->codot = trim($codot2); 
                $oOt2           = $this->ot_model->obtenerg($filter6,$filter6_not);
                $numOt          = $oOt2->NroOt; 
                $productos      = $this->producto_model->obtener($codigo);
                 
     
                $descripcion   = $productos->DesPro;
                $codunidad      = $productos->UniMed;
             
                $pv=$punitario*$cantidad;
                $CI->pdf->Cell(10,5,number_format($cantidad,2),1,0,"C",0);
                $CI->pdf->Cell(25,5,$codigo,1,0,"C",0);
              //  $CI->pdf->Cell(10,5,$codunidad,1,0,"L",0);
                $CI->pdf->Cell(90,5,$descripcion,1,0,"L",0);
                $CI->pdf->Cell(20,5,number_format($punitario,4),1,0,"R",0);    
                $CI->pdf->Cell(20,5,number_format($pv,4),1,0,"R",0);      
                $CI->pdf->Cell(15,5,$numOt,1,1,"C",0); 
                
                $subtotal=$pv+$subtotal;
            }
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            $igv=$subtotal*0.18;
            $total=$igv+$subtotal;
            
        $CI->pdf->Cell(145,5, "" ,0,1,"C",0);
        $CI->pdf->Cell(125,5, "" ,0,0,"C",0);
        $CI->pdf->Cell(20,5, "SUBTOTAL" ,0,0,"R",0);
        $CI->pdf->Cell(20,5, number_format($subtotal,4) ,0,1,"R",0);
        $CI->pdf->Cell(125,5,  "",0,0,"C",0);
        $CI->pdf->Cell(20,5, "IMP (18%)" ,0,0,"R",0);
        $CI->pdf->Cell(20,5, number_format($igv,4) ,0,1,"R",0);
        $CI->pdf->Cell(125,5, "" ,0,0,"C",0);
        $CI->pdf->Cell(20,5, "TOTAL" ,0,0,"R",0);
        $CI->pdf->Cell(20,5, number_format($total,4) ,0,1,"R",0);
        
            
            
        $CI->pdf->Cell(60,20, "",0,1,"C",0);
        $CI->pdf->Cell(60,5, "--------------------------------",0,0,"C",0);
        $CI->pdf->Cell( 60,5,"------------------------------",0,0,"C",0);
        $CI->pdf->Cell( 60,5,"------------------------------",0,1,"C",0);
        $CI->pdf->Cell( 60,5,"SOLICITANTE",0,0,"C",0);
        $CI->pdf->Cell( 60,5,"VoBo JEFE DE AREA",0,0,"C",0);
        $CI->pdf->Cell( 60,5,"VoBo GERENTE",0,1,"C",0); 
        $CI->pdf->Output();
        }
    }        
}
?>
