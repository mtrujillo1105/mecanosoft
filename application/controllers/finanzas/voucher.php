<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once "Spreadsheet/Excel/Writer.php";
class Voucher extends CI_Controller {
    var $compania;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");   
        $this->load->model(finanzas.'voucher_model');
        $this->load->model(ventas.'orden_model');
        $this->load->model(personal.'responsable_model');
        $this->load->model(maestros.'banco_model');
        //$this->load->model(personal.'responsable_model'); 
        $this->load->model(compras.'proveedor_model');
        $this->load->model(maestros.'tipomov_model');
        $this->compania = $this->session->userdata('compania');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index()
    {
        $this->load->view(ventas."ot_listar");
    }
    
    public function listar(){
        $offset             = (int)$this->uri->segment(3);
        $conf['base_url']   = site_url('finanzas/voucher/listar/');
        $conf['per_page']   = 30;
        $conf['num_links']  = 3;
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['next_link']  = "&gt;";
        $conf['prev_link']  = "&lt;";
        $conf['uri_segment']= 4;
        $conf['total_rows'] = 100;
        $j = 0;
        $filter             = new stdClass();
        $filter_not         = new stdClass();
        $filter->fechai     = "01/01/2013";
        $filter->fechaf     = "31/12/2013";
        $listado            = $this->voucher_model->listar($filter,$filter_not,array('FecEmi'=>'desc','NroVoucher'=>'desc'),$conf['per_page'],$offset);
        $item               = $j+1;
        $fila               = "";
        if(count($listado)>0){
             foreach($listado as $indice=>$valor){
                 $numero    = $valor->NroVoucher;
                 $forpago   = $valor->ForPago;
                 $fecpago   = $valor->FecPago;
                 $fecemi    = $valor->fecha;
                 $nrocheque = $valor->NroCheque;
                 $cuenta    = $valor->NroCta;
                 $moneda    = $valor->MO;
                 $tipsol    = $valor->TipSolPago;
                 $codsoli   = $valor->CodSolicita;
                 $tcambio   = $valor->Tc;
                 $codbanco  = $valor->CodBco;
                 $mtopago   = $valor->MtoPago;
                 $fentrega  = $valor->fEntrega;
                 $descripcion = $valor->d_descripcion;
                 $fila  .= "<tr class='".($indice%2==0?'itemParTabla':'itemParTabla')."'>";
                 $fila  .= "<td align='center'>".$item++."</td>";
                 $fila  .= "<td align='center'>".$fecemi."</td>";
                 $fila  .= "<td align='center'>".$numero."</td>";
                 $fila  .= "<td align='center'>".$codsoli."</td>";
                 $fila  .= "<td align='center'>".$codbanco."</td>";
                 $fila  .= "<td align='center'>".$cuenta."</td>";
                 $fila  .= "<td align='center'>".$nrocheque."</td>";
                 $fila  .= "<td align='center'>".$moneda."</td>";
                 $fila  .= "<td align='right'>".number_format($mtopago,2)."</td>";
                 $fila  .= "<td align='center'>".$descripcion."</td>";
                 $fila  .= "<td align='center'><a href='#' onclick='editar(".$numero.")'><img src='".base_url()."img/modificar.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='ver(".$numero.")'><img src='".base_url()."img/ver.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='eliminar(".$numero.")'><img src='".base_url()."img/eliminar.png' width='16' height='16' border='0' title='Modificar'></a></td>";                 
                 $fila  .= "</tr>";
             }
        }
        $data['fila']           = $fila;
        $data['titulo_busqueda'] = "Buscar Voucher";
        $data['titulo_tabla']    = "Relaci&oacute;n de Vouchers";
        $this->pagination->initialize($conf);
        $data['paginacion']      = $this->pagination->create_links();
        $this->load->view(finanzas."voucher_listar",$data);
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
        $voucher     = $this->voucher_model->obtener($filter,$filter_not);
        $fecemi      = $voucher->FecEmi;
        $fecpago     = $voucher->FecPago;
        $nrocheque   = $voucher->NroCheque;
        $nrocta      = $voucher->NroCta;
        $tipSolPago  = $voucher->TipSolPago;
        $codsolicita = $voucher->CodSolicita;
        $Est_vuelto  =$voucher->Est_Vuelto;
        $mtoPago     = $voucher->MtoPago;
        $fentrega    = $voucher->fEntrega;
        $Mo          = $voucher->MO;
        $observacion = $voucher->d_descripcion;
        $cambio = $voucher->Tc;
        $moneda      = $Mo==2?"NUEVOS SOLES":"DOLARES AMERICANOS";
        
        
       $codbco      = $voucher->CodBco;
       $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->bancos = $codbco;    
        $ind   = $this->banco_model->obtener($filter,$filter_not);
      /*  PRINT_r($ind);*/
     $banco= $ind->b_banco; 
    /*    $codsolicita = $voucher->CodSolicita;
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->codresponsable = $codsolicita;    
        $indice   = $this->responsable_model->obtener($filter,$filter_not);
        echo 
        $alaorden           = $indice->NomPer;*/
        
        
      $gpersonal   = $voucher->CodSolicita;
       /*  print_r($gruc);die;*/
       
        
        
        
       
       if ($Est_vuelto=='P')
        {
        $filter->ruccliente = $gpersonal; 
        
        $responsable   = $this->proveedor_model->obtener1($filter,$filter_not);
      //  print(count($responsable));die;
        if(isset($responsable->RazCli)){
        $personal    = $responsable->RazCli;
        $gdni    = $responsable->CodCli;}
        
         else
            $Est_vuelto='C';
         
       //  print_r($Est_vuelto);die;
          
        }
          
        if ($Est_vuelto=='C')
        {
        $filter->codresponsable = $gpersonal; 
        $responsable   = $this->responsable_model->obtener($filter,$filter_not);
        $personal    = $responsable->nomper;
        $gdni    = $responsable->dniper;
      
        
        
        }
        
        
        else
        { $filter->ruccliente = $gpersonal; 
        $responsable   = $this->proveedor_model->obtener1($filter,$filter_not);
        $personal    = $responsable->RazCli;
        $gdni    = $responsable->CodCli;}
        
        
        $this->load->library("fpdf/pdf");
        $CI = & get_instance();
        $CI->pdf->FPDF('P');
        $CI->pdf->AliasNbPages();
        $CI->pdf->AddPage();
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        /*Cabecera*/
        $CI->pdf->SetFont('Arial','B',11);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(216,216,216);
        //$CI->pdf->Image('images/anadir.jpg',11,4,30);
        
         $CI->pdf->Image('img/mimco_ruc.jpg',10,8,55);
        $CI->pdf->Cell(0,8, "VOUCHER No ".$numero,0,1,"C",0);
         $CI->pdf->SetFont('Arial','B',7);
        $CI->pdf->Cell(120,5, "" ,0,1,"L",0);
        $CI->pdf->Cell(60,8,  "",0,1,"L",0);
        $CI->pdf->Cell(60,5, "BANCO : ".$banco,0,0,"L",0);
        $CI->pdf->Cell(60,5, "NRO CTA CTE : ".$nrocta ,0,0,"L",0);
        $CI->pdf->Cell(60,5,  "FECHA EMISION :  ".$fecemi,0,1,"L",0);
        $CI->pdf->Cell(60,5, "NRO CHEQUE : ".$nrocheque ,0,0,"L",0);
        $CI->pdf->Cell(60,5, "MONEDA : ".$moneda ,0,0,"L",0);
       
        $CI->pdf->Cell(60,5, "FECHA PAGO : ".$fecpago ,0,1,"L",0);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFillColor(255,255,255);
        $CI->pdf->Cell(120,5,  "A LA ORDEN :   ". $gdni."   -   " . $personal,0,0,"L",0);
       
        $CI->pdf->Cell(60,5,  "T.C. :  ".$cambio ,0,1,"L",0);
        $CI->pdf->Cell(120,5, "OBSERVACION : ".$observacion ,0,0,"L",1);
        $CI->pdf->Cell(120,5, "" ,0,1,"L",1);
     
        $CI->pdf->SetTextColor(255,255,255);
        $CI->pdf->SetFillColor(192,192,192);
        /*Detalle*/
        $CI->pdf->Cell(0,5, "" ,0,1,"R",0);
        $CI->pdf->SetFillColor(0,0,128);
       /* $CI->pdf->Cell( 5,5,"Item",0,0,"C",1);*/
        $CI->pdf->Cell( 45,5,"TIPO MOV.",0,0,"C",1);
        $CI->pdf->Cell(13,5,"OT/CC",0,0,"C",1);
        $CI->pdf->Cell( 40,5,"SITE",0,0,"C",1);
        $CI->pdf->Cell( 60,5,"DESCRIPCION",0,0,"C",1);
        $CI->pdf->Cell( 21,5,"Nro. Doc.",0,0,"C",1);
        $CI->pdf->Cell( 10,5,"PAGO",0,0,"C",1);
        $CI->pdf->Cell( 7,5,"IGV",0,1,"C",1);
        $CI->pdf->SetTextColor(0,0,0);
        $CI->pdf->SetFont('Arial','',6);
        $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->numero = $numero;
        $voucher_det = $this->voucher_model->listar_detalle2($filter2,$filter2_not);
        $i = 1;
        $import=0;
        foreach($voucher_det as $indice=>$value){
            $importe     = $value->ImpPdet;
            $descripcion = $value->DesPago;
            $tipodoc     = $value->TipoDocRef;
            $numerodoc   = $value->NroDocRef;
            $seriedoc    = $value->SerieDocRef;
            $codot       = $value->codot;
            $tipPago     = $value->TipPago;
            $igv         = $value->Igv;
            
             $igv      = $igv=='C'?"Inc.":"Sin.";
            
            
            
        
        $filter->tipomov = $tipPago; 
        $valor   = $this->tipomov_model->obtener($filter,$filter_not);
        $tipomovi    = $valor->Des_Larga;
    
             
             
             
            /*Obtenemos OT*/
            $ots         = $this->ot_model->obtener($codot);
            $nroot       = $ots->NroOt;
            $dirot       = $ots->DirOt;
         /*   $CI->pdf->Cell(5,6,$i,0,0,"C",0);*/
            $CI->pdf->Cell(45,6,$tipomovi,1,0,"L",0);
            $CI->pdf->Cell(13,6,$nroot,1,0,"R",0);
            $CI->pdf->Cell(40,6,$dirot,1,0,"L",0);
            $CI->pdf->Cell(60,6,$descripcion,1,0,"L",0);    
            $CI->pdf->Cell(21,6,$tipodoc."-".$seriedoc."-".$numerodoc,1,0,"C",0);    
            $CI->pdf->Cell(10,6,number_format($importe,2),1,0,"R",0);    
            $CI->pdf->Cell(7,6,$igv,1,1,"c",0);    
            $i++;
            $import=$importe+$import;
        }
        $CI->pdf->Cell( 161,5,'',0,1,"C",0);
        $CI->pdf->Cell( 161,5,'',0,0,"C",0);
        $CI->pdf->Cell( 15,5,"TOTAL",1,0,"C",0);
        $CI->pdf->Cell( 15,5,number_format($import,2),1,1,"C",0);
        
        
        
        $CI->pdf->Output();
    }

    public function buscar(){

    }
}
?>
