<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Requis_ser extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");  
        $this->load->model(almacen.'producto_model');
         $this->load->model(almacen.'servicio_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(compras.'proveedor_model');
        
        $this->load->model(compras.'facturac_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(maestros.'tipo_dcto_model');
        $this->load->model(maestros.'formapago_model');
        $this->load->model(maestros.'ubigeo_model');
        $this->dbase   = $this->load->database('dsn',TRUE);
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
        
       $hora_Actual=date("H:i:s");
       /* $serie          = $this->input->get_post('serie');*/
        $numero         = $this->input->get_post('numero');
        $coot         = $this->input->get_post('codot');
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        /*$filter->serie  = $serie;*/
        $filter->numero = $numero;
       
        $facturas       = $this->facturac_model->obtener_rs($filter,$filter_not);
     /*   if(isset($facturas->NroDoc)){*/
            $gserguia    = $facturas->gserguia;
            $gnumguia     = $facturas->gnumguia;
           
            $fecemi     = $facturas->fecemi;
            $got = $facturas->got;$nlote   = $facturas->nlote;
          
            $nrod        = $facturas->nrod;
            $gfentrega  = $facturas->gfentrega;
            $gpeso = $facturas->gpeso;
            $ghora = $facturas->ghora;$tipod   = $facturas->tipod;
            $seried   = $facturas->seried;
            $detalle   = $facturas->gobserva;$fdespacho  = $facturas->fdespacho;
            $fpago = $facturas->fpago;
            $igv = $facturas->igv;
            $subtotal   = $facturas->subtotal;
            $gdetrac   = $facturas->gdetrac;
            $gp_detrac        = $facturas->gp_detrac;
            $mo        = $facturas->moneda;
            $cambio       = $facturas->cambio;
            $dot     = $facturas->got;
            $filter2     = new stdClass();
            $filter2_not = new stdClass();
            //$filter2->numero = $dot;
            $filter2->codot = $coot;
            //print_r($filter2);
            $oOt         = $this->ot_model->obtenerg($filter2,$filter2_not);
             $nroOt       = $oOt->NroOt;
             $dirOt       = $oOt->DirOt;
            
          
      /*       $nrod        = $facturas->tipod;echo $nrod;*/
             
            $numd        = $this->tipo_dcto_model->obtener($tipod);
            $docu        = $numd->docdescri;
           
            
            
         
            $tp            = $this->formapago_model->obtener($fpago);
            $fpdes         = $tp->Des_Larga;    
             
             
            
            $gruc   = $facturas->gruc;
             if($gruc==0)
             $gruc       ='00000000000';
                
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->ruccliente = $gruc;
            
                
            
            $pro            = $this->proveedor_model->obtener($filter,$filter_not);
            $razonsocial           = $pro->RazCli;  
             
             
            
            
            $gcontacto        = $facturas->gcontacto;  
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->ruccliente = $gcontacto;
            $pro            = $this->proveedor_model->obtener($filter,$filter_not);
            $contactox           = isset($pro->RazCli)?$pro->RazCli:"";  
             
            
            $gcodser = $facturas->gcodser;
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->codservicio = $gcodser;    
            $service   = $this->servicio_model->obtener($filter,$filter_not);
            /*print_r($service);*/
            $servdetalle           = $service->DesPro; 
            
            
            
            
            
            $gdestino   = $facturas->gdestino;
            $filter         = new stdClass();
            $filter_not     = new stdClass();
            $filter->ubica = $gdestino;    
            $ubic   = $this->ubigeo_model->obtener_ubigeo1($filter,$filter_not);
            $unionx           = $ubic->union; 
            
            
            
            
            
       /* echo $service->despro;die;*/
             
             
             
       $gpersonal  = $facturas->gpersonal;
        $filter->codresponsable = $gpersonal; 
       
        $responsable   = $this->responsable_model->obtener($filter,$filter_not);
              $sol     = $responsable->nomper;
            
            
            $moneda      = $mo=='S'?"NUEVOS SOLES":"DOLARES AMERICANOS";
           /* $filter3     = new stdClass();
            $filter3_not = new stdClass();
            $filter3->ruccliente = $ruccli;
            $proveedores = $this->proveedor_model->obtener($filter3,$filter3_not);
            $razcli      = $proveedores->RazCli;*/
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
            //$CI->pdf->Image('images/anadir.jpg',11,4,30);
            $CI->pdf->Image('img/mimco_ruc.jpg',10,8,55);
             
            $CI->pdf->Cell(120,8, "No SERVICIO:  ".$gserguia."-".$gnumguia,0,0,"R",0);
            $CI->pdf->SetFont('Arial','B',7);
            $CI->pdf->Cell(50,8,"Hora:".$hora_Actual,0,1,"R",0);
           
            
            
            
           /* $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
            $CI->pdf->Cell(60,5, "NRO OT : ".$nroOt ,0,0,"L",0);
            $CI->pdf->Cell(60,5, "REQ.SERVICIO : ".$seroc."-".$nrooc ,0,0,"L",0);*/
            $CI->pdf->Cell(60,15,  "",0,1,"L",0);
            $CI->pdf->Cell( 25,5,"FECHA",0,0,"L",0);
            $CI->pdf->Cell( 25,5,":   ".date_sql($fecemi),0,1,"L",0);
            
           /* $CI->pdf->Cell(60,5, "TIPO DOC. REF. : ".$tipdocref ,0,0,"L",0);
            $CI->pdf->Cell(60,5,  "NRO DOC. REF. :".$serieref."-".$numeroref ,0,0,"L",0);
            $CI->pdf->Cell(60,5, "MONEDA : ".$moneda ,0,1,"L",0);
            $CI->pdf->Cell(120,5, "PROVEEDOR : ".$ruccli." ".$razcli ,0,0,"L",1);
            $CI->pdf->Cell(120,5, "FEHCA VENC. : ".$fecvcto ,0,1,"L",0);
            $CI->pdf->SetTextColor(255,255,255);
            $CI->pdf->SetFillColor(192,192,192);*/
            /*Detalle*/
            $CI->pdf->Cell( 25,5,"SOLICITANTE" ,0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$gpersonal." -    ". $sol. "      No Lote:  ". $nlote,0,1,"L",0);
           
           /* $CI->pdf->SetFillColor(0,0,128);*/
            $CI->pdf->Cell(  25,5,"OT/PT/CC",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$got. " -    ". $dirOt,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"T. SERVICIO",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$servdetalle,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"PTO. LLEGADA",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$unionx,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"CONTACTO",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$gcontacto."   -    ".$contactox,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"F. ENTREGA",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".date_sql($gfentrega)."                  PESO:  ".$gpeso."                   HORA ENTREGA:  ".$ghora,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"PROVEEDOR",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$gruc."   -    ".$razonsocial,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"T. DCTO",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$tipod. " -    ".$docu."          SERIE / NRO. DCTO  :  ".$seried." - ".$nrod,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"F. REALIZADO",0,0,"L",0);
            $CI->pdf->Cell( 25,5,($fdespacho!=''?":  ".date_sql($fdespacho):'//'),0,1,"L",0);
             
           
            $CI->pdf->Cell( 25,5,"FORMA PAGO",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$fpago. "   -    ".$fpdes,0,1,"L",0);
             
            $CI->pdf->Cell( 25,5,"I.G.V.",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".Number_format($igv,2),0,0,"L",0);
              $CI->pdf->Cell( 20,5,"SUBTOTAL",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".Number_format($subtotal,2),0,0,"L",0);
             $CI->pdf->Cell( 15,5,"TOTAL",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".Number_format($subtotal+$igv,2),0,1,"L",0);
             
             
             
            $CI->pdf->Cell( 25,5,"DETRACCION",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".Number_format($gdetrac,2)."   %   ".Number_format($gp_detrac,2),0,1,"L",0);
            
            $CI->pdf->Cell( 25,5,"MONEDA",0,0,"L",0);
             $CI->pdf->Cell( 25,5,":   ".$moneda."        TC.:  ".$cambio,0,1,"L",0);
            
            
            
            
            $CI->pdf->SetFont('Arial','',8);
            $CI->pdf->SetTextColor(0,0,0);
            $filter4     = new stdClass();
            $filter4_not = new stdClass();        
          /*  $filter4->serie  = $serie;
            $filter4->numero = $numero;
            $filter4->codot  = $codot;*/
           /* $facturas_det    = $this->facturac_model->listar_detalle($filter4,$filter4_not);
            foreach($facturas_det as $indice => $value){
                $cantidad  = $value->CantSolRep;
                $codigo    = $value->CodPro;
                $punitario = $value->PrecUnit;
                $codot2    = $value->CodOt;*/
                /*Nombre OT*/
                $filter6        = new stdClass();
                $filter6_not    = new stdClass();
            /*    $filter6->codot = trim($codot2); */
             /*   $oOt2          = $this->ot_model->obtenerg($filter6,$filter6_not);
                $nroOt2        = $oOt2->NroOt;  
                $filter5     = new stdClass();
                $filter5_not = new stdClass();
                $filter5->codot = $codot2;
                $ots2           = $this->ot_model->obtenerg($filter5,$filter5_not);
                $nroOt          = $ots2->NroOt;
                $productos      = $this->producto_model->obtener($codigo);
                $descripcion    = $productos->DesPro;
                $codunidad      = $productos->UniMed;
                $CI->pdf->Cell(5,5,$indice+1,1,0,"C",0);
                $CI->pdf->Cell(10,5,$cantidad,1,0,"C",0);
                $CI->pdf->Cell(30,5,$codigo,1,0,"C",0);
                $CI->pdf->Cell(20,5,$codunidad,1,0,"L",0);
                $CI->pdf->Cell(69,5,$descripcion,1,0,"L",0);
                $CI->pdf->Cell(20,5,number_format($punitario,2),1,0,"R",0);    
                $CI->pdf->Cell(20,5,number_format($punitario*$cantidad,2),1,0,"R",0);      
                $CI->pdf->Cell(15,5,$nroOt2,1,1,"L",0); 
            }*/$CI->pdf->Cell( 25,5,"",0,1,"L",0);
            $CI->pdf->Cell( 25,5,"",0,1,"L",0);
             $CI->pdf->SetFont('Arial','B',8);
             
              $CI->pdf->Cell( 0,5,"R E Q U I S I C I O N      D E     S E R V I C I O S",0,1,"C",0);
             $CI->pdf->Cell( 25,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"L",0);
          
             $CI->pdf->Cell( 10,5,"ITEM",1,0,"C",1);
             $CI->pdf->Cell( 10,5,"CANT.",1,0,"C",1);
             $CI->pdf->Cell( 20,5,"CODIGO",1,0,"C",1);
             
             $CI->pdf->Cell( 10,5,"UND",1,0,"C",1);
             $CI->pdf->Cell( 95,5,"DESCRIPCION",1,0,"C",1);
             $CI->pdf->Cell( 15,5,"OT",1,0,"C",1);
             $CI->pdf->Cell( 15,5,"P.UNIT.",1,0,"C",1);
                       
             $CI->pdf->Cell( 15,5,"P.VENT.",1,1,"C",1);
          
             
             
             
             
             
             
             
             
             $CI->pdf->Cell( 25,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"L",0);
             $CI->pdf->Cell( 25,5,"OBSERVACIONES",0,1,"L",0);
             $CI->pdf->Cell( 25,5,"$detalle",0,1,"L",0);
             $CI->pdf->Cell( 25,5,"----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------",0,1,"L",0);
          
             
               $CI->pdf->Cell(65,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,1,"C",0);
           $CI->pdf->Cell(92,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,0,"C",0);
        $CI->pdf->Cell( 65,20,"",0,1,"C",0);
        $CI->pdf->Cell(92,5, "------------------------------------------------------------",0,0,"C",0);
       
        $CI->pdf->Cell( 92,5,"------------------------------------------------------------",0,1,"C",0);
        $CI->pdf->Cell( 92,5,"REQUERIDO POR",0,0,"C",0);
       
        $CI->pdf->Cell( 92,5,"VoBo GERENTE Y/O VoBo LOGISTICA",0,1,"C",0);
            $CI->pdf->Cell(92,5,$sol,0,0,"C",0);
        
        $CI->pdf->Cell( 92,5,"JORGE VILLAVICENCIO Y/O MARTIN GALINDO",0,1,"C",0);
             
             
             
             $CI->pdf->Output();
        }
        
        
        
        
   /* } */
}
?>
