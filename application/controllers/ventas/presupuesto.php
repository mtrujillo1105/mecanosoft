<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";  
class Presupuesto extends CI_Controller {
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");                
        $this->load->model(ventas.'presupuesto_model');
        $this->load->model(ventas.'presupuestosubpartida_model');
        $this->load->model(ventas.'cliente_model');
        $this->load->model(ventas.'ot_model');
        $this->load->model(ventas.'ctrlobras_model');
        $this->load->model(ventas.'partida_model');
        $this->load->model(maestros.'periodoot_model');
        $this->load->model(maestros.'tipoproducto_model');
        $this->load->model(maestros.'tipoproducto_old_model');
        $this->load->model(maestros.'estadoot_model');
        $this->load->model(maestros.'proyecto_model');
        $this->load->model(maestros.'tc_model');
        $this->load->model(almacen.'nsalida_model');
        $this->load->model(compras.'requiser_model');
        $this->load->model(produccion.'tareo_model');
        $this->load->model(finanzas.'caja_model');
        $this->load->model(finanzas.'voucher_model');
        $this->load->helper('date');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');

    }

    public function index()
    {
        $this->load->view(ventas."presupuesto_nuevo_1");
    } 

    public function listar2(){   
        $codproyecto = $this->input->get_post('codproyecto');  
        $codestado   = $this->input->get_post('codestado');  
        $fInicio     = $this->input->get_post('fInicio');
        $fFin        = $this->input->get_post('fFin');           
        $tipo        = $this->input->get_post('tipo'); 
        $estado      = $this->input->get_post('estado'); 
        $fecha_ini   = $this->input->get_post('fecha_ini'); 
        $fecha_fin   = $this->input->get_post('fecha_fin');  
        $cliente     = $this->input->get_post('cliente');  
        $moneda      = $this->input->get_post('moneda');  
        if($estado=="")      $estado     = 'P';  
        if($fecha_ini=="")   $fecha_ini  = "01/01/2013";
        if($fecha_fin=="")   $fecha_fin  = date("d/m/Y",time());          
        if($tipo=="")        $tipo       = "html";
        if($moneda=="")      $moneda     = "S";
        $selproyecto         = form_dropdown('codproyecto',$this->proyecto_model->seleccionar("::Seleccione:::","000"),$codproyecto," size='1' id='codproyecto' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\" ");               
        $selecestado         = form_dropdown('estado',$this->estadoot_model->seleccionar("::Seleccione:::","000"),$estado," size='1' id='estado' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\" ");                       
        $selcliente          = form_dropdown('cliente',$this->cliente_model->seleccionar2('','000000'),$cliente,"id='cliente' class='comboMedio'"); 
        $selmoneda           = form_dropdown('moneda',array(""=>"::Seleccione:::","S"=>"SOLES","D"=>"DOLARES"),$moneda," size='1' id='moneda' class='comboMedio' onchange=\"$('#frmBusqueda').attr('target','_self');$('#frmBusqueda').attr('action','');$('#tipoexport').val('');$('#numero').val('');\" ");               
        /*Listado de Presupuestos*/
        $filter              = new stdClass();
        $filter_not          = new stdClass();
        $filter->estado      = "P";
        if($cliente!="")       $filter->codcliente  = $cliente;
        if($codproyecto!="")   $filter->codproyecto = $codproyecto;
        if($fecha_ini!="")     $filter->fechai      = $fecha_ini;
        if($fecha_fin!="")     $filter->fechaf      = $fecha_fin;
        $presupuestos        = $this->presupuesto_model->listar($filter,$filter_not,array("pre.NroPres"=>"desc"));  
        $cantidad            = count($presupuestos);
        $offset              = (int)$this->uri->segment(4);
        $conf['first_link']  = "&lt;&lt;";
        $conf['last_link']   = "&gt;&gt;";
        $conf['next_link']   = "&gt;";
        $conf['prev_link']   = "&lt;";   
        $conf['base_url']    = site_url('ventas/presupuesto/listar2/');
        $conf['per_page']    = 5;    
        $conf['total_rows']  = $cantidad;
        $conf['uri_segment'] = 4;        
        $this->pagination->initialize($conf); 
        $fila                = "";
        if(count($presupuestos)>0){
           foreach($presupuestos as $indice=>$value){
                $codpresupuesto = $value->CodPresupuesto;
                $fecha          = $value->Fecha;
                $codproyecto    = $value->CodProyecto;
                $site           = $value->Site;
                $rsocial        = $value->RazCli;
                $monedapres     = $value->Mo;
                $monto          = $value->ImpDcto;
                $estado         = $value->Estado;
                $numero         = $value->NroPres;
                $arrTc          = $this->tc_model->obtener($fecha);
                $tcambio       = $arrTc->Valor_3;
                if($moneda=="S")
                  $monto = ($monedapres==2?$monto:$monto*$tcambio);  
                elseif($moneda=="D")
                  $monto = ($monedapres==3?$monto:$monto/$tcambio);  
                switch($estado){
                    case 'P':
                        $nomestado = "Pendiente";
                        break;
                    case 'C':
                        $nomestado = "Cancelado";
                        break;
                    default:
                        $nomestado = "";
                }
                /*Nomproeycto*/
                $proyectos      = $this->proyecto_model->obtener($codproyecto);
                $nomproyecto    = isset($proyectos->Des_Larga)?$proyectos->Des_Larga:"";
                $clase          = $indice%2==0?"itemParTabla":"itemImparTabla";
                if($indice >= $offset && $indice < ($offset + $conf['per_page']) ){
                    $fila          .= "<tr class='".$clase."' id='".$codpresupuesto."'>";
                    $fila          .= "<td align='center'>".$numero."</td>";
                    $fila          .= "<td align='center'>".$fecha."</td>";
                    $fila          .= "<td align='left'>".$nomproyecto."</td>";
                    $fila          .= "<td align='left'>".$site."</td>";
                    $fila          .= "<td align='center'>".$rsocial."</td>";
                    $fila          .= "<td align='center'>".number_format($monto,2)."</td>";
                    $fila          .= "<td align='center'>".$nomestado."</td>";
                    $fila          .= "<td align='center'><a href='#' onclick='editar(\"".$codpresupuesto."\")'><img src='".base_url()."img/modificar.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                    $fila          .= "<td align='center'><a href='#' onclick='ver(".$codpresupuesto.")'><img src='".base_url()."img/ver.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                    $fila          .= "<td align='center'><a href='#' onclick='eliminar(".$codpresupuesto.")'><img src='".base_url()."img/eliminar.png' width='16' height='16' border='0' title='Modificar'></a></td>";                 
                    $fila          .= "</tr>";                          
                }       
            }
        }
        $data['selestado']       = $selecestado;
        $data['selproyecto']     = $selproyecto;  
        $data['selcliente']      = $selcliente;  
        $data['selmoneda']       = $selmoneda;
        $data['fila']            = $fila;
        $data['cantidad']        = $cantidad;
        $data['fecha_ini']       = $fecha_ini;
        $data['fecha_fin']       = $fecha_fin;
        $data['titulo_busqueda'] = "Buscar Presupuesto";
        $data['titulo_tabla']    = "Relaci&oacute;n de Presupuestos";
        $this->pagination->initialize($conf);
        $data['paginacion']      = $this->pagination->create_links();     
        $this->load->view(ventas."presupuesto_listar2",$data);
    }
    
    public function nuevo2($codigo){
        $presupuesto = $this->input->get_post('presupuesto');        
        $analisis    = $this->input->get_post('analisis'); 
        $fecha       = $this->input->get_post('fecha');        
        $cliente     = $this->input->get_post('cliente');        
        $site        = $this->input->get_post('site');        
        $proyecto    = $this->input->get_post('proyecto');
        if($fecha=="")  $fecha = date("Y-m-d",time());
        $selcliente  = form_dropdown('cliente',$this->cliente_model->seleccionar2('','000000'),$cliente,"id='cliente' class='comboMedio'"); 
        $selproyecto = form_dropdown('proyecto',$this->proyecto_model->seleccionar('','000'),$proyecto,"id='proyecto' class='comboMedio'");
        /*Principales*/
        if(!isset($codigo)){
            
        }
        else{
            
        }
        /*Detalle tipo*/
        $fila_tipo     = "";
        $fila_partida  = "";
        $fila_spartida = "";
        $arrayTipo    = array('FABIRACION','OOCC','INFRAES','SDFSD');
        foreach($arrayTipo as $indice => $value){  
            $clase          = $indice%2==0?"itemParTabla":"itemImparTabla";
            $fila_tipo .= "<tr class='".$clase."' id='".$indice."'>";
            $fila_tipo .= "<td align='left'><font size='1'>".$value."</font></td>";
            $fila_tipo .= "<td align='left'><font size='1'></font></td>";
            $fila_tipo .= "</tr>";
        }
        /*Detalle partida*/
        $filter     = new stdClass();
        $filter_not = new stdClass();
        $filter->codtipoproducto = "03";
        $partidas   = $this->partida_model->listar_partidatipoproducto($filter,$filter_not); 
        foreach($partidas as $indice => $value){  
            $clase         = $indice%2==0?"itemParTabla":"itemImparTabla";
            $fila_partida .= "<tr class='".$clase."' id='".$value->CodPartida."'>";
            $fila_partida .= "<td align='left'><font size='1'>".$value->Des_Larga."</font></td>";
            $fila_partida .= "<td align='left'><font size='1'></font></td>";
            $fila_partida .= "</tr>";
        }
        /*Fila subpartida*/
        $filter3     = new stdClass();
        $filter3_not = new stdClass();
        $filter3->codtipoproducto = "03";
        $filter3->codpartida      = "03";
        $subpartidas  = $this->presupuestosubpartida_model->listar($filter3,$filter3_not);

        
        $data['presupuesto']     = $presupuesto;
        $data['analisis']     = $analisis;
        $data['fecha']       = $fecha;
        $data['site']        = $site;
        $data['selcliente']  = $selcliente;
        $data['selproyecto'] = $selproyecto;
        $data['fila_tipo']   = $fila_tipo;
        $data['fila_partida']  = $fila_partida;
        $data['descripcion']   = "";
        $this->load->view(ventas."presupuesto_nuevo",$data);
    }
    
    public function listar($codpres=""){
        $filter              = new stdClass();
        $filter_not          = new stdClass();
        $filter->estado      = "P";
        $presupuestos        = $this->presupuesto_model->listar($filter,$filter_not);
        $fila                = "";
        $fila_fab            = "";
        $fila_montaje        = "";
        $fila_ociviles       = "";
        $fila_transporte     = "";
        $fila_singenieria    = "";
        $fila_pespeciales    = "";
        $fila_otros          = "";
        $fila_pingenieria    = "";
        if(count($presupuestos)>0){
            foreach($presupuestos as $indice=>$value){
                $codpresupuesto = $value->CodPresupuesto;
                $fecha          = $value->Fecha;
                $codproyecto    = $value->CodProyecto;
                $site           = $value->Site;
                $rsocial        = $value->RazCli;
                $moneda         = $value->Mo;
                $monto          = $value->ImpDcto;
                $estado         = $value->Estado;
                $numero         = $value->NroPres;
                /*Nomproeycto*/
                $proyectos      = $this->proyecto_model->obtener($codproyecto);
                $nomproyecto    = isset($proyectos->Des_Larga)?$proyectos->Des_Larga:"";
                $clase          = $indice%2==0?"itemParTabla":"itemImparTabla";
                $fila          .= "<tr class='".$clase."' id='".$codpresupuesto."' onclick='verpresupuesto(this);'>";
                $fila          .= "<td align='center'><font size='1'>".$numero."</font></td>";
                $fila          .= "<td align='center'><font size='1'>".$fecha."</font></td>";
                $fila          .= "<td align='left'><font size='1'>".$nomproyecto."</font></td>";
                $fila          .= "<td align='left'><font size='1'>".$site."</font></td>";
                $fila          .= "<td align='center'><font size='1'>".$rsocial."</font></td>";
                $fila          .= "<td align='center'><font size='1'>".($moneda==2?'S/':'$')." ".$monto."</font></td>";
                $fila          .= "<td align='center'><font size='1'>".$estado."</font></td>";
                $fila          .= "</tr>";             
            }
        }
        $filter2          = new stdClass();
        $tipoproductos    = $this->tipoproducto_model->listar($filter2);
        foreach($tipoproductos as $indice3=>$value3){
            $tipo         = $value3->cod_argumento;
            echo "$codpres  $tipo";
            $partidas     = $this->listar_tipo($codpres,$tipo);
            /*Obtengo el monto total por el tipo de producto*/
            $filter3     = new stdClass();
            $filter3_not = new stdClass();
            $filter3->codpresupuesto  = $codpres;
            $filter3->codtipoproducto = $tipo;
            $pres_detalle = $codpres!=""?$this->presupuesto_model->obtener_detalle($filter3,$filter3_not):new stdClass();            
            $total_tipoproducto = isset($pres_detalle->ImpDcto)?$pres_detalle->ImpDcto:0;
            /*Relacion de partidas por tipo de producto*/
            if(count($partidas)>0){
                $fila_tipo      = "";
                foreach($partidas as $indice2=>$value2){
                    $codpartida = $value2->CodPartida;
                    $nompartida = $value2->Des_Larga;
                    /*Total por partida*/
                    $filter4     = new stdClass();
                    $filter4_not = new stdClass();
                    $filter4->codpartida      = $codpartida;
                    $filter4->codpresupuesto  = $codpres;
                    $filter4->codtipoproducto = $tipo; 
                    $presupuesto_subpartidas  = $this->presupuestosubpartida_model->listar($filter4,$filter4_not);
                    $importe_partida          = 0;
                    if(count($presupuesto_subpartidas)>0){
                        foreach($presupuesto_subpartidas as $indice4=>$value4){
                            $importe_partida = $value4->ImpDcto + $importe_partida;
                        }
                    }
                    $fila_tipo .= "<tr bgcolor='#FFFFFF' id='".$codpartida."' class='verSubpartida'>";
                    $fila_tipo .= "<td align='left'><a href='#'>".$nompartida."</a></td>";
                    $fila_tipo .= "<td align='right'><input type='text' name='partida[".$codpartida."]' id='partida[".$codpartida."]' value='".number_format($importe_partida,2)."' class='cajaSlimPequena' style='text-align:right;'></td>";
                    $fila_tipo .= "</tr>";          
                }
                $fila_tipo .= "<tr bgcolor='#FFFFFF' id='1' class='verDetPartida'>";
                $fila_tipo .= "<td align='right'>TOTAL</td>";
                $fila_tipo .= "<td align='right'><input type='text' name='total_partida' id='total_partida' value='".number_format($total_tipoproducto,2)."' class='cajaSlimPequena' style='text-align:right;'></td>";
                $fila_tipo .= "</tr>";
                if($tipo=='02')  $fila_fab         = $fila_tipo;
                if($tipo=='03')  $fila_montaje     = $fila_tipo;
                if($tipo=='04')  $fila_ociviles    = $fila_tipo;
                if($tipo=='05')  $fila_transporte  = $fila_tipo;
                if($tipo=='06')  $fila_singenieria = $fila_tipo;
                if($tipo=='07')  $fila_pespeciales = $fila_tipo;
                if($tipo=='08')  $fila_otros       = $fila_tipo;
                if($tipo=='09')  $fila_pingenieria = $fila_tipo;
            }            
        }
        $data                  = $this->nuevo($codpres);
        $data['fila']          = $fila;
        $data['fila_fab']      = $fila_fab;
        $data['fila_montaje']  = $fila_montaje;
        $data['fila_ociviles'] = $fila_ociviles;
        $data['fila_transporte']  = $fila_transporte;
        $data['fila_singenieria'] = $fila_singenieria;
        $data['fila_pespeciales'] = $fila_pespeciales;
        $data['fila_otros']       = $fila_otros;
        $data['fila_pingenieria'] = $fila_pingenieria;
        $this->load->view(ventas."presupuesto",$data);
    }

    public function listar_tipo($codpres,$tipo){
        $partidas   = array();
        if($codpres!=''){
            $filter     = new stdClass();
            $filter_not = new stdClass();
            $filter->codtipoproducto = $tipo;
            $partidas   = $this->partida_model->listar_partidatipoproducto($filter,$filter_not);            
        }
        return $partidas;
    }
    
    public function listar_subpartidapresupuestada(){
        $codpartida  = $this->input->get_post('codpartida'); 
        $codpresupuesto  = $this->input->get_post('codpresupuesto'); 
        $codtipoproducto = $this->input->get_post('codtipoproducto'); 
        /*Nombre partida*/
        $filter2     = new stdClass();
        $filter2_not = new stdClass();
        $filter2->codpartida = $codpartida;
        $partidas    = $this->partida_model->obtener($filter2,$filter2_not);
        $nombrepartida = $partidas->des_larga;
        /*Listamos las subpartidas*/
        $filter      = new stdClass();
        $filter_not  = new stdClass();
        $filter->codpartida = $codpartida;
        $subpartidas = $this->partida_model->listar_detalle($filter,$filter_not);
        $fila        = "";
        if(count($subpartidas)>0){
            foreach($subpartidas as $indice=>$value){
                $nomsubpartida = $value->des_larga;
                $codsubpartida = $value->cod_argumento;
                /*Obtengo lo presupuestado para la subpartida*/
                $filter3     = new stdClass();
                $filter3_not = new stdClass();
                $filter3->codpresupuesto  = $codpresupuesto; 
                $filter3->codpartida      = $codpartida;
                $filter3->codsubpartida   = $codsubpartida;
                $filter3->codtipoproducto = $codtipoproducto;
                $presupuesto_subpartidas  = $this->presupuestosubpartida_model->obtener($filter3,$filter3_not);
                $importe_subpartida       = isset($presupuesto_subpartidas->ImpDcto)?$presupuesto_subpartidas->ImpDcto:0;
                $fila.="<tr bgcolor='#FFFFFF' id='1'>";
                $fila.="<td align='left'>".$nomsubpartida."</td>";
                $fila.="<td align='right'><span id='txtEjec1'><input name='monto[".$codsubpartida."]' id='monto[".$codsubpartida."]' type='text' class='cajaPequena' value='".number_format($importe_subpartida,2)."' style='text-align:right;'></span></td>";
                $fila.="</tr>";
            }
        }
        $data['fila']            = $fila;
        $data['codpartida']      = $codpartida;
        $data['codpresupuesto']  = $codpresupuesto;
        $data['codtipoproducto'] = $codtipoproducto;
        $data['nombrepartida']   = $nombrepartida;
        $this->load->view(ventas."subpartida_listar",$data);
    }    
    
    public function nuevo($codpres){
        $c_fabricacion       = 0;
        $c_montaje           = 0;
        $c_ociviles          = 0;
        $c_transporte        = 0;
        $c_singenieria       = 0;
        $c_pespeciales       = 0;
        $c_otros             = 0;
        $c_pingenieria       = 0;
        $v_fabricacion       = 0;
        $v_montaje           = 0;
        $v_ociviles          = 0;
        $v_transporte        = 0;
        $v_singenieria       = 0;
        $v_pespeciales       = 0;
        $v_otros             = 0;
        $v_pingenieria       = 0;        
        $codproyecto         = "000";
        $codcliente           = "000";
        $nroPres             = "";
        $fecha               = date("d/m/Y",time());
        $moneda              = "";
        $observacion         = "";
        $descripcion         = "";
        $accion              = "";
        $site                = "";
        if($codpres!=""){
            /*Recupero cabecera*/
            $filter      = new stdClass();
            $filter_not  = new stdClass();
            $filter->codpresupuesto = $codpres;
            $accion      = "M";
            $presupuestos = $this->presupuesto_model->obtener($filter,$filter_not);
            $codproyecto = isset($presupuestos->CodProyecto)?$presupuestos->CodProyecto:"";
            $codcliente  = isset($presupuestos->CodCli)?$presupuestos->CodCli:"";
            $fecha       = isset($presupuestos->Fecha)?$presupuestos->Fecha:'';
            $nroPres     = isset($presupuestos->NroPres)?$presupuestos->NroPres:"";
            $moneda      = isset($presupuestos->Mo)?$presupuestos->Mo:"";
            $monto       = isset($presupuestos->ImpDcto)?$presupuestos->ImpDcto:"";
            $descripcion = isset($presupuestos->Descripcion)?$presupuestos->Descripcion:"";
            $observacion = isset($presupuestos->Observacion)?$presupuestos->Observacion:"";
            $site        = isset($presupuestos->Site)?$presupuestos->Site:"";
            /*Recupero detalle*/
            $filter2     = new stdClass();
            $filter2_not = new stdClass();
            $filter2->codpresupuesto = $codpres;
            $presupuestos_det = $this->presupuesto_model->listar_detalle($filter2,$filter2_not);
            if(count($presupuestos_det)>0){
               foreach($presupuestos_det as $indice=>$value){
                   $tipo     = $value->Tipo;
                   $montodet = $value->ImpDcto;
                   $montodet_vta = $value->ImpVenta;
                   if(trim($tipo)=='02')  $c_fabricacion = $montodet;
                   if(trim($tipo)=='03')  $c_montaje     = $montodet;
                   if(trim($tipo)=='04')  $c_ociviles    = $montodet;
                   if(trim($tipo)=='05')  $c_transporte  = $montodet;
                   if(trim($tipo)=='06')  $c_singenieria = $montodet;
                   if(trim($tipo)=='07')  $c_pespeciales = $montodet;
                   if(trim($tipo)=='08')  $c_otros       = $montodet;
                   if(trim($tipo)=='09')  $c_pingenieria = $montodet;
                   if(trim($tipo)=='02')  $v_fabricacion = $montodet_vta;
                   if(trim($tipo)=='03')  $v_montaje     = $montodet_vta;
                   if(trim($tipo)=='04')  $v_ociviles    = $montodet_vta;
                   if(trim($tipo)=='05')  $v_transporte  = $montodet_vta;
                   if(trim($tipo)=='06')  $v_singenieria = $montodet_vta;
                   if(trim($tipo)=='07')  $v_pespeciales = $montodet_vta;
                   if(trim($tipo)=='08')  $v_otros       = $montodet_vta;
                   if(trim($tipo)=='09')  $v_pingenieria = $montodet_vta;                   
               } 
            }
        }
        $selproyecto           = form_dropdown('codproyecto',$this->proyecto_model->seleccionar("::Seleccione:::","000"),$codproyecto," size='1' id='codproyecto' class='comboMedio'");               
        $selcliente            = form_dropdown('codcliente',$this->cliente_model->seleccionar("::Seleccione:::","000000"),$codcliente," size='1' id='codcliente' class='comboMedio'");               
        $c_total               = $c_fabricacion+$c_montaje+$c_ociviles+$c_transporte+$c_singenieria+$c_pespeciales+$c_otros+$c_pingenieria;
        $v_total               = $v_fabricacion+$v_montaje+$v_ociviles+$v_transporte+$v_singenieria+$v_pespeciales+$v_otros+$v_pingenieria;
        $data['selproyecto']   = $selproyecto;
        $data['selcliente']    = $selcliente;
        /*Presupestado*/
        $data['c_fabricacion'] = $c_fabricacion;
        $data['c_montaje']     = $c_montaje;
        $data['c_ociviles']    = $c_ociviles;
        $data['c_transporte']  = $c_transporte;
        $data['c_singenieria'] = $c_singenieria;
        $data['c_pespeciales'] = $c_pespeciales;
        $data['c_otros']       = $c_otros;
        $data['c_pingenieria'] = $c_pingenieria;
        $data['c_total']       = $c_total;
        /*Ventas*/
        $data['v_fabricacion'] = $v_fabricacion;
        $data['v_montaje']     = $v_montaje;
        $data['v_ociviles']    = $v_ociviles;
        $data['v_transporte']  = $v_transporte;
        $data['v_singenieria'] = $v_singenieria;
        $data['v_pespeciales'] = $v_pespeciales;
        $data['v_otros']       = $v_otros;
        $data['v_pingenieria'] = $v_pingenieria;
        $data['v_total']       = $v_total;        
        $data['nroPres']       = $nroPres;
        $data['moneda']        = $moneda;        
        $data['fecha']         = $fecha;  
        $data['observacion']   = $observacion;  
        $data['descripcion']   = $descripcion;  
        $data['accion']        = $accion;
        $data['codpresupuesto']= $codpres;
        $data['site']          = $site;
        echo $data;
    }
    

    
    public function editar(){

    }

    public function grabar(){
        $codproyecto  = $this->input->get_post('codproyecto'); 
        $codcliente   = $this->input->get_post('codcliente'); 
        $moneda       = $this->input->get_post('moneda'); 
        $fecha        = $this->input->get_post('fecha'); 
        $site         = strtoupper($this->input->get_post('site')); 
        $codot        = $this->input->get_post('codot'); 
        $tipot        = $this->input->get_post('tipot');         
        /*Monto presupesto*/
        $c_fabricacion  = $this->input->get_post('cfabricacion'); 
        $c_montaje      = $this->input->get_post('cmontaje'); 
        $c_ociviles     = $this->input->get_post('cociviles'); 
        $c_transporte   = $this->input->get_post('ctransporte'); 
        $c_singenieria  = $this->input->get_post('csingenieria'); 
        $c_otros        = $this->input->get_post('cotros'); 
        $c_pespeciales  = $this->input->get_post('cpespeciales'); 
        $c_pingenieria  = $this->input->get_post('cpingenieria'); 
        $c_total        = $this->input->get_post('ctotal'); 
        /*Valor de venta*/
        $v_fabricacion  = $this->input->get_post('vfabricacion'); 
        $v_montaje      = $this->input->get_post('vmontaje'); 
        $v_ociviles     = $this->input->get_post('vociviles'); 
        $v_transporte   = $this->input->get_post('vtransporte'); 
        $v_singenieria  = $this->input->get_post('vsingenieria'); 
        $v_otros        = $this->input->get_post('votros'); 
        $v_pespeciales  = $this->input->get_post('vpespeciales'); 
        $v_pingenieria  = $this->input->get_post('vpingenieria'); 
        $v_total        = $this->input->get_post('vtotal');
        $observacion    = strtoupper($this->input->get_post('observacion')); 
        $descripcion    = strtoupper($this->input->get_post('descripcion')); 
        $accion         = $this->input->get_post('accion'); 
        $codpres        = $this->input->get_post('codpresupuesto'); 
        $filter         = new stdClass();
        $filter_not     = new stdClass();
        $filter->maximo = "NroPres";
        $presupuestos   = $this->presupuesto_model->obtener($filter,$filter_not);
        $numero         = str_pad((int)$presupuestos->NroPres + 1,6,"0",STR_PAD_LEFT);
        $filter3         = new stdClass();
        $filter3_not     = new stdClass();
        $filter3->maximo = "CodPresupuesto";
        $presupuestos2   = $this->presupuesto_model->obtener($filter3,$filter3_not);
        $codpresupuesto  = str_pad((int)$presupuestos2->CodPresupuesto + 1,7,"0",STR_PAD_LEFT);  
        if($codproyecto!="000" && $moneda!=""){
            /*Grabo en la tabla presupuesto*/
            $filter2        = new stdClass();
            $filter2->NroPres = $numero;
            $filter2->CodPresupuesto = $codpresupuesto;
            $filter2->FecDoc  = $fecha;
            $filter2->CodEnt  = $this->entidad;
            $filter2->ImpDcto = $c_total;
            $filter2->ImpVenta = $v_total;
            $filter2->Mo      = $moneda;
            $filter2->Descripcion = $descripcion;
            $filter2->Observacion = $observacion;
            $filter2->CodProyecto = $codproyecto;
            $filter2->CodRes      = "000514";
            $filter2->CodCli      = $codcliente;
            $filter2->Site        = $site;
            $filter2->CodOt       = $codot;
            $filter2->TipOt       = $tipot;
            if($accion!='M'){
                unset($filter2->CodOt);
                unset($filter2->TipOt);
                $this->presupuesto_model->insertar($filter2);    
            }
            else{
                unset($filter2->CodPresupuesto);
                unset($filter2->CodEnt);
                $this->presupuesto_model->modificar($codpres,$filter2);
            }
            /*Grabo en la tabla detalle_presupuesto*/
            $filter4     = new stdClass();
            $order_by    = "Des_Corta";
            $tipos = $this->tipoproducto_model->listar($filter4,$order_by);
            $importe     = 0;
            foreach($tipos as $indice=>$value){
                $tipo = $value->cod_argumento;
                if($tipo=='02') $importe  = $c_fabricacion;
                if($tipo=='03') $importe  = $c_montaje;
                if($tipo=='04') $importe  = $c_ociviles;
                if($tipo=='05') $importe  = $c_transporte;
                if($tipo=='06') $importe  = $c_singenieria;
                if($tipo=='07') $importe  = $c_pespeciales;
                if($tipo=='08') $importe  = $c_otros;
                if($tipo=='09') $importe  = $c_pingenieria;
                
                if($tipo=='02') $vimporte = $v_fabricacion;
                if($tipo=='03') $vimporte = $v_montaje;
                if($tipo=='04') $vimporte = $v_ociviles;
                if($tipo=='05') $vimporte = $v_transporte;
                if($tipo=='06') $vimporte = $v_singenieria;
                if($tipo=='07') $vimporte = $v_pespeciales;
                if($tipo=='08') $vimporte = $v_otros;
                if($tipo=='09') $vimporte = $v_pingenieria;
                
                $filter5 = new stdClass();
                $filter5->CodPresupuesto = $codpresupuesto;
                $filter5->CodEnt   = $this->entidad;
                $filter5->Tipo     = $tipo;
                $filter5->ImpDcto  = $importe;
                $filter5->ImpVenta = $vimporte;
                if($accion!='M'){
                    $this->presupuesto_model->insertar_detalle($filter5);    
                }
                else{
                    $where = new stdClass();
                    $where->codpresupuesto = $codpres;
                    $where->tipo           = $tipo;
                    unset($filter5->CodPresupuesto);
                    unset($filter5->CodEnt);
                    unset($filter5->Tipo);
                    $this->presupuesto_model->modificar_detalle($where,$filter5);
                }
            }
        }
    }

    public function grabar_detalle(){
        $arrMonto        = $this->input->get_post('monto');
        $codpartida      = $this->input->get_post('codpartida');
        $codpresupuesto  = $this->input->get_post('codpresupuesto');
        $codtipoproducto = $this->input->get_post('codtipoproducto');
        if(count($arrMonto)>0){
            foreach($arrMonto as $indice=>$value){
                $codsubpartida = $indice;
                $monto         = $value;
                $filter        = new stdClass();
                $filter->CodEnt = $this->entidad;
                $filter->CodPartida     = $codpartida;
                $filter->codSubpartida  = $codsubpartida;
                $filter->CodPresupuesto = $codpresupuesto;
                $filter->Tipo           = $codtipoproducto;
                $filter->ImpDcto        = (double)$monto;
                $this->presupuestosubpartida_model->insertar($filter);
            }
        }
        $this->presupuesto_partida($codtipoproducto);
    }
    
    public function presupuesto_partida($codtipoproducto){
        $fila         = "";
        $partidas     = $this->listar_tipo("",$tipo);
        /*Obtengo el monto total por el tipo de producto*/
        $filter3     = new stdClass();
        $filter3_not = new stdClass();
        $filter3->codpresupuesto  = $codpres;
        $filter3->codtipoproducto = $tipo;
        $pres_detalle = $codpres!=""?$this->presupuesto_model->obtener_detalle($filter3,$filter3_not):new stdClass();            
        $total_tipoproducto = isset($pres_detalle->ImpDcto)?$pres_detalle->ImpDcto:0;
        if(count($partidas)>0){
            $fila_tipo      = "";
            foreach($partidas as $indice2=>$value2){
                $codpartida = $value2->CodPartida;
                $nompartida = $value2->Des_Larga;
                /*Total por partida*/
                $filter4     = new stdClass();
                $filter4_not = new stdClass();
                $filter4->codpartida      = $codpartida;
                $filter4->codpresupuesto  = $codpres;
                $filter4->codtipoproducto = $tipo; 
                $presupuesto_subpartidas  = $this->presupuestosubpartida_model->listar($filter4,$filter4_not);
                $importe_partida          = 0;
                if(count($presupuesto_subpartidas)>0){
                    foreach($presupuesto_subpartidas as $indice4=>$value4){
                        $importe_partida = $value4->ImpDcto + $importe_partida;
                    }
                }
                $fila_tipo .= "<tr bgcolor='#FFFFFF' id='".$codpartida."' class='verSubpartida'>";
                $fila_tipo .= "<td align='left'><a href='#'>".$nompartida."</a></td>";
                $fila_tipo .= "<td align='right'><input type='text' name='partida[".$codpartida."]' id='partida[".$codpartida."]' value='".number_format($importe_partida,2)."' class='cajaSlimPequena' style='text-align:right;'></td>";
                $fila_tipo .= "</tr>";          
            }
            $fila_tipo .= "<tr bgcolor='#FFFFFF' id='1' class='verDetPartida'>";
            $fila_tipo .= "<td align='right'>TOTAL</td>";
            $fila_tipo .= "<td align='right'><input type='text' name='total_partida' id='total_partida' value='".number_format($total_tipoproducto,2)."' class='cajaSlimPequena' style='text-align:right;'></td>";
            $fila_tipo .= "</tr>";
            if($tipo=='02')  $fila_fab         = $fila_tipo;
            if($tipo=='03')  $fila_montaje     = $fila_tipo;
            if($tipo=='04')  $fila_ociviles    = $fila_tipo;
            if($tipo=='05')  $fila_transporte  = $fila_tipo;
            if($tipo=='06')  $fila_singenieria = $fila_tipo;
            if($tipo=='07')  $fila_pespeciales = $fila_tipo;
            if($tipo=='08')  $fila_otros       = $fila_tipo;
            if($tipo=='09')  $fila_pingenieria = $fila_tipo;
        }  
        $data['fila_fab']          = $fila_fab;
        $data['fila_montaje']      = $fila_montaje;
        $data['fila_ociviles']     = $fila_ociviles;
        $data['fila_transporte']   = $fila_transporte;
        $data['fila_singenieria']  = $fila_singenieria;
        $data['fila_pespeciales']  = $fila_pespeciales;
        $data['fila_otros']        = $fila_otros;   
        $data['fila_pingenieria']  = $fila_pingenieria;   
        $data['tipo']              = $codtipoproducto;
        $this->load->view(ventas."presupuesto_partida",$data);
    }
    
    public function eliminar(){
        $codpresupuesto = $this->input->get_post('codpresupuesto');
        $this->presupuesto_model->eliminar_detalle($codpresupuesto);
        $this->presupuesto_model->eliminar($codpresupuesto);
    }

    public function ver(){

    }

    public function buscar(){

    }
    
    public function crear_ot(){
        $codpresupuesto  = $this->input->get_post('codpresupuesto');
        $codot = "00000";
        $this->grabar();
        die();
    }
    
    public function rptDocumentos(){
        
        $codent = $this->entidad;
        $tipoexport = $this->input->get_post('tipoexport');
        if(isset($_POST["tipdcto"])){$tipdcto = $_POST["tipdcto"];} else{$tipdcto = "FV";}
        if(isset($_POST["moneda"])){$moneda_rpt = $_POST["moneda"];} else{$moneda_rpt = "2";}
        if(isset($_POST["codcli"])){$codcli = $_POST["codcli"];} else{$codcli = "";}
        if(isset($_POST["tipodetalle"]) && $_POST['tipodetalle']!=''){$tipocontenido = $_POST["tipodetalle"];} else{$tipocontenido = "D";}
        if(isset($_REQUEST['fecha1']) && $_REQUEST['fecha1']!='')  $fecha1  = $_REQUEST['fecha1']; else $fecha1 = "01/01/2012";
        if(isset($_REQUEST['fecha2']) && $_REQUEST['fecha2']!='')  $fecha2  = $_REQUEST['fecha2']; else $fecha2 = date("d/m/Y");
        $ComboCliente  = form_dropdown('codcli',$this->cliente_model->seleccionar("::Seleccione:::","000"),$codcli," size='1' id='codcli' class='comboGrande' onchange=\"$('#tipoexport').val('');submit();\" ");               
        $filtrocodcli="";
        if($codcli=='000'){$filtrocodcli="";}else{$filtrocodcli=" and ot.codcli='".$codcli."'";}
        if($tipocontenido=='D'){
            $detalles = $this->presupuesto_model->listar_documentos1($tipdcto,$fecha1,$fecha2,$filtrocodcli);
        }
        if($tipocontenido=='C'){        
            $detalles = $this->presupuesto_model->listar_documentos2($tipdcto,$fecha1,$fecha2);
        }       
        $fila    = "";
        $j=0; 
        

            foreach($detalles as $indice => $value){

            $serie   = $value->seriedcto;
            $numdoc  = $value->nrodcto;
            $razcli  = $value->razcli;
            $observ  = $value->detalle;
            $numero  = $value->nroot;
            $fecdcto = $value->FecDcto;
            $fecvcto = $value->FecVcto;
            $fpago   = $value->Des_larga;
            $peso    = $value->canpro;
            $pu      = $value->preuni;
            $subtotal= $value->total;
            $igv     = $value->igv;
            $tcambio = $value->tcambio;
            $moneda  = $value->mndcto;
            $estado  = $value->estdcto;
            $pesoC   = $value->peso;
            $total   = $subtotal + $igv;
            $pu_s    = $moneda==2?$pu:$pu*$tcambio;
            $pu_d    = $moneda==3?$pu:$pu/$tcambio;
            $subtotal_s = $moneda==2?$subtotal:$subtotal*$tcambio;
            $subtotal_d = $moneda==3?$subtotal:$subtotal/$tcambio;
            $igv_s      = $moneda==2?$igv:$igv*$tcambio;
            $igv_d      = $moneda==3?$igv:$igv/$tcambio;
            $total_s    = $moneda==2?$total:$total*$tcambio;
            $total_d    = $moneda==3?$total:$total/$tcambio;
                

            
             if($tipocontenido=='C')
             {
                $numero  = "<ul>";

                $ots = $this->presupuesto_model->listar_NroOts($serie,$numdoc,$tipdcto);
                    foreach($ots as $indice => $value2)
                    {
                    $numero  .= "<li>".$value2->nroot."</li>";
                    }
                    
                $numero  .= "<ul>";  
             }
      
//          $verpeso = $codent=='01'?$pesoC:$peso; otra manera de if
            if($codent=='01'){$verpeso =$pesoC;} else{$verpeso =$peso;}   
            
            
            $color   = trim($estado)=='A'?"color='#FF0000'":"a";
            $fila .= "<tr>";
            $fila .= "<td width='2.00%' align='center'><font size='2' ".$color.">".$serie."</font></td>";
            $fila .= "<td width='5.53%' align='center'><font size='2' ".$color.">".$numdoc."</font></td>";
            $fila .= "<td width='15.8%' align='left'><font size='2' ".$color.">".$razcli."</font></td>";
            $fila .= "<td width='8.5%' align='left'><div style='width: 100px; height: auto; overflow-x: hidden; overflow-y: hidden;'><font size='2' ".$color.">".$observ."</font></div></td>";
            $fila .= "<td width='6.30%'><font size='2' ".$color.">".$numero."</font></td>";
            $fila .= "<td width='6.0%' align='center'><font size='2' ".$color.">".$fecdcto."</font></td>";
            $fila .= "<td width='6.0%' align='center'><font size='2' ".$color.">".$fecvcto."</font></td>";
            $fila .= "<td width='7.02%' align='left'><font size='2' ".$color.">".$fpago."</font></td>";
            
            if($tipocontenido=='D'){
            $fila .= "<td width='6.51%' align='right'><font size='2' ".$color.">".number_format($verpeso,2)."</font></td>";
            $fila .= "<td width='6.10%' align='right'><font size='2' ".$color.">".number_format(($moneda_rpt==2?$pu_s:$pu_d),2)."</font></td>";  
            }
            
            $fila .= "<td width='6.83%' align='right'><font size='2' ".$color.">".number_format(($moneda_rpt==2?$subtotal_s:$subtotal_d),2)."</font></td>";
            $fila .= "<td width='5.41%' align='right'><font size='2' ".$color.">".number_format(($moneda_rpt==2?$igv_s:$igv_d),2)."</font></td>";
            $fila .= "<td width='5.12%' align='right'><font size='2' ".$color.">".number_format(($moneda_rpt==2?$total_s:$total_d),2)."</font></td>";
            $fila .= "</tr>";
            
            
            
            $j++;
            
         
         
      }
 
        
        

       if($tipoexport=="excel"){
              $xls = new Spreadsheet_Excel_Writer();
              $xls->send("RPTDocumentos.xls");
              $sheet  =$xls->addWorksheet("Documentos");
              $sheet->setColumn(0,0,5.6); //COLUMNA A1
              $sheet->setColumn(1,1,10); //COLUMNA B2
              $sheet->setColumn(2,2,33); //COLUMNA C3
              $sheet->setColumn(3,3,20); //COLUMNA D4
              $sheet->setColumn(4,4,10); //COLUMNA E5
              $sheet->setColumn(5,5,14); //COLUMNA F6
              $sheet->setColumn(6,6,11); //COLUMNA G7
              $sheet->setColumn(7,7,14); //COLUMNA H8
              
              
              $sheet->setColumn(8,8,15); //COLUMNA I9
              $sheet->setColumn(9,9,15); //COLUMNA J10
              
              
              $sheet->setColumn(10,10,15); //COLUMNA K11
              $sheet->setColumn(11,11,15); //COLUMNA L12
              $sheet->setColumn(12,12,15); //COLUMNA M13
              $sheet->setColumn(13,13,15); //COLUMNA N14
              $sheet->setRow(0, 51);
              $sheet->setRow(1,42);
              $format_bold=$xls->addFormat();
              $format_bold->setBold();
              $format_bold->setvAlign('vcenter');
              $format_bold->sethAlign('center');
              $format_bold->setBorder(1);
              $format_bold->setTextWrap();             
              $format_titulo=$xls->addFormat();
              $format_titulo->setBold();
              $format_titulo->setSize(10);
              $format_titulo->setvAlign('vcenter');
              $format_titulo->sethAlign('center');
              $format_titulo->setBorder(1);
              $format_titulo->setTextWrap();
               $sheet->mergeCells(0,0,0,13); 
              $sheet->write(0,3,"REPORTE",$format_titulo); $sheet->write(0,4,"DE",$format_titulo);  $sheet->write(0,5,"DOCUMENTOS",$format_titulo);  $sheet->write(0,6,"GENERAL",$format_titulo);  
              
              $sheet->write(1,0,"SERIE",$format_bold);  $sheet->write(1,1,"NUMERO",$format_bold);  $sheet->write(1,2,"RAZON SOCIAL",$format_bold);  $sheet->write(1,3,"OBSERVACIONES",$format_bold);   $sheet->write(1,4,"OT",$format_bold);      $sheet->write(1,5,"FEC DCTO",$format_bold);   $sheet->write(1,6,"FEC VCTO",$format_bold);    $sheet->write(1,7,"FORMA PAGO",$format_bold);      if($tipocontenido=='D'){$sheet->write(1,8,"PESO",$format_bold);}else{$sheet->write(1,8,($moneda_rpt==2?"SUBTOTAL S/.":"SUBTOTAL $"),$format_bold);}         if($tipocontenido=='D'){$sheet->write(1,9,"P.UNITARIO",$format_bold);}else{$sheet->write(1,9,($moneda_rpt==2?"IGV S/.":"IGV $"),$format_bold);}           if($tipocontenido=='D'){$sheet->write(1,10,($moneda_rpt==2?"SUBTOTAL S/.":"SUBTOTAL $"),$format_bold);}else{$sheet->write(1,10,($moneda_rpt==2?"TOTAL S/.":"TOTAL $"),$format_bold);}              if($tipocontenido=='D'){$sheet->write(1,11,($moneda_rpt==2?"IGV S/.":"IGV $"),$format_bold);}          if($tipocontenido=='D'){$sheet->write(1,12,($moneda_rpt==2?"TOTAL S/.":"TOTAL $"),$format_bold);}  
              
         
                $z=2;
    
            foreach($detalles as $indice3 => $value3){

            $serie   = $value3->seriedcto;
            $numdoc  = $value3->nrodcto;
            $razcli  = $value3->razcli;
            $observ  = $value3->detalle;
            $numero  = $value3->nroot;
            $fecdcto = $value3->FecDcto;
            $fecvcto = $value3->FecVcto;
            $fpago   = $value3->Des_larga;
            $peso    = $value3->canpro;
            $pu      = $value3->preuni;
            $subtotal= $value3->total;
            $igv     = $value3->igv;
            $tcambio = $value3->tcambio;
            $moneda  = $value3->mndcto;
            $estado  = $value3->estdcto;
            $pesoC   = $value3->peso;
            $total   = $subtotal + $igv;
            $pu_s    = $moneda==2?$pu:$pu*$tcambio;
            $pu_d    = $moneda==3?$pu:$pu/$tcambio;
            $subtotal_s = $moneda==2?$subtotal:$subtotal*$tcambio;
            $subtotal_d = $moneda==3?$subtotal:$subtotal/$tcambio;
            $igv_s      = $moneda==2?$igv:$igv*$tcambio;
            $igv_d      = $moneda==3?$igv:$igv/$tcambio;
            $total_s    = $moneda==2?$total:$total*$tcambio;
            $total_d    = $moneda==3?$total:$total/$tcambio;
                
                
                
             if($tipocontenido=='C')
             { 
                 $numeros="";
                 $ots = $this->presupuesto_model->listar_NroOts($serie,$numdoc,$tipdcto);
                    foreach($ots as $indice4 => $value4)
                    {
                        $numeros = $numeros." ".$value4->nroot;
                    }                 
             }


            if($codent=='01'){$verpeso =$pesoC;} else{$verpeso =$peso;}   
   
                        $sheet->write($z,0,$serie,$format_bold); 
                        $sheet->write($z,1,$numdoc,$format_bold);
                        $sheet->write($z,2,$razcli,$format_bold);
                        $sheet->write($z,3,$observ,$format_bold);
                        $sheet->write($z,4,($tipocontenido=='D'?$numero:$numeros),$format_bold);
                        $sheet->write($z,5,$fecdcto,$format_bold);
                        $sheet->write($z,6,$fecvcto,$format_bold);  
                        $sheet->write($z,7,$fpago,$format_bold); 
                        
                         if($tipocontenido=='D'){
                          $sheet->write($z,8,(number_format($verpeso,2)),$format_bold);  
                          $sheet->write($z,9,(number_format(($moneda_rpt==2?$pu_s:$pu_d),3, '.', '')),$format_bold);
                          }
                          
                         //formato frances para import a excel .... number_format($numero, 2, '.', '');

                          
                       $sheet->write($z,($tipocontenido=='D'?10:8),(number_format(($moneda_rpt==2?$subtotal_s:$subtotal_d),3, '.', '')),$format_bold);  
                       $sheet->write($z,($tipocontenido=='D'?11:9),(number_format(($moneda_rpt==2?$igv_s:$igv_d),3, '.', '')),$format_bold); 
                       $sheet->write($z,($tipocontenido=='D'?12:10),(number_format(($moneda_rpt==2?$total_s:$total_d),3, '.', '')),$format_bold); 
                            

//                    for($i=1;$i<=7;$i++){
//                        $indicador = str_pad($i,3,"0",STR_PAD_LEFT);
//                        $sheet->write($jj,7+$i,$numeros,$format_bold);
//                        $sheet->write($jj+1,7+$i,'',$format_bold);
//                        $sheet->write($jj+2,7+$i,'',$format_bold);
//                        $sheet->write($jj+3,7+$i,'',$format_bold);
//                        $sheet->write($jj+4,7+$i,'',$format_bold);
                    

                    $z++;
                }     
        $xls->close();
        }        
        $data['fecha1']        = $fecha1;
        $data['fecha2']        = $fecha2;
        $data['tipocontenido'] = $tipocontenido;
        $data['moneda_rpt']    = $moneda_rpt;
        
        $data['j']             = $j;
        $data['codent']        = $codent;
        $data['fila']          = $fila;
        $data['tipdcto']       = $tipdcto;
        $data['ComboCliente']  = $ComboCliente;
        $data['tipoexport']    = $tipoexport;
        $this->load->view(ventas."rpt_documento",$data);
    }
    
    public function rpt_control(){
        $tipOt        = $this->input->get_post('tipot'); 
        $codproyecto  = $this->input->get_post('codproyecto'); 
        $tiproducto   = $this->input->get_post('tiproducto'); 
        $estado       = $this->input->get_post('estado'); 
        $moneda       = $this->input->get_post('moneda'); 
        $tipo_reporte = $this->input->get_post('tipo_reporte'); 
        $tipoexport   = $this->input->get_post('tipoexport'); 
        if($tipOt=="")        $tipOt  = 18;
        if($estado=="")       $estado       = 'P';
        if($moneda=="")       $moneda       = 'S';
        if($tipo_reporte=="") $tipo_reporte = 'G';
        $fila   = "";
        $j      = 0;
        $selecttipoot       = form_dropdown('tipot',$this->periodoot_model->seleccionar("::Seleccione:::",""),$tipOt," size='1' id='tipot' class='comboMedio' onchange=\"$('#tipoexport').val('');submit();\" ");               
        $selectipoproducto  = form_dropdown('tiproducto',$this->tipoproducto_model->seleccionar2(new stdClass(),"valor_2","::Seleccione:::",""),$tiproducto," size='1' id='tiproducto' class='comboMedio' onchange=\"$('#tipoexport').val('');submit();\" ");               
        $selproyecto        = form_dropdown('codproyecto',$this->proyecto_model->seleccionar("::Seleccione:::","000"),$codproyecto," size='1' id='codproyecto' class='comboMedio' onchange=\"$('#tipoexport').val('');submit();\" ");               
        $selecestado        = form_dropdown('estado',$this->estadoot_model->seleccionar("::Seleccione:::","000"),$estado," size='1' id='estado' class='comboMedio' onchange=\"$('#tipoexport').val('');submit();\" ");               
        $selmoneda          = form_dropdown('moneda',array("000"=>"::Seleccione:::","S"=>"SOLES","D"=>"DOLARES"),$moneda," size='1' id='moneda' class='comboMedio' onchange=\"$('#tipoexport').val('');submit();\" ");               
        $this->form_validation->set_rules('tipot','Tipo OT','required');
        $this->form_validation->set_rules('tiproducto','Tipo Producto','required');
        if($this->form_validation->run() == true){
            /*Se extrae las Materias primas por OT y se ingresan en un array(CONSULTA AL DBF)*/
            /* se suma todas las salidas menos todos los ingresos por devolucion */
            $filter3 = new stdClass();
            $filter3->tipoot = $tipOt;
            //$Omateria_prima = $this->nsalida_model->listar_totales2($filter3,new stdClass());
            $Omateria_prima = $this->nsalida_model->listar_totales($filter3,new stdClass());
            foreach($Omateria_prima as $indice3=>$value3){
                $codigo       = $value3->codot;
                $montoD       = $value3->sum_exp_2;
                $monto        = $value3->sum_exp_3;
                $arrMateriales[$codigo]  = $monto;
                $arrMaterialesD[$codigo] = $montoD;
            }
            
            /*Se extraen las Requi_ser y se ingresan en un array(DBF)*/
            $filter4    = new stdClass();
            $filternot4 = new stdClass();
            //$filternot4->codservicio = array('000000000010','000000000002','000000000074','000000000057','000000000001','000000000086','000000000084','000000000048','000000000049','000000000047','000000000046','000000000071');
            $oServicios = $this->requiser_model->listar_totales($filter4,$filternot4,"");
            foreach($oServicios as $indice4 => $value4){
                $codigo       = $value4->codot;
                $tipser       = $value4->tipser;
                $total        = $value4->sum_exp_3;
                $subtotal     = $value4->sum_exp_4;
                $totalD       = $value4->sum_exp_5;
                $subtotalD    = $value4->sum_exp_6;
                $arrServicio[$codigo][$tipser] = $moneda=='S'?$subtotal:$subtotalD;
            }

            /*Listado de OTs*/
            $filter = new stdClass();
            if($codproyecto!="000")  $filter->codproyecto = $codproyecto;
            if($estado!=' ')         $filter->estado   = $estado;
            $filter->tipoot = $tipOt;
            $filter->fechai = '15/05/2012';
            /*Obtengo el tipo de producto antiguo*/
            $filter2 = new stdClass();
            $filter2->Valor_3 = $tiproducto;
            $producto_old = $this->tipoproducto_old_model->listar($filter2);
            foreach($producto_old as $idice=>$value){
                $arrTipo[] = $value->cod_argumento;
            }
            $filter->tipo   = $arrTipo;
            /*****/ 
            if($tipoexport=="excel"){
                $xls = new Spreadsheet_Excel_Writer();
                $xls->send("Rpt_costomateriales.xls");
                $sheet  =$xls->addWorksheet('Reporte');
                $sheet->setColumn(0,0,9); //COLUMNA A1
                $sheet->setColumn(1,1,41); //COLUMNA B2
                $sheet->setColumn(2,2,29); //COLUMNA C3
                $sheet->setColumn(3,3,12); //COLUMNA D4
                $sheet->setColumn(4,4,15); //COLUMNA E5
                $sheet->setColumn(5,5,18); //COLUMNA F6
                $sheet->setColumn(6,6,18); //COLUMNA G7
                $sheet->setRow(0,50);
                $sheet->setRow(1,42);
                $format_bold=$xls->addFormat();
                $format_bold->setBold();
                $format_bold->setvAlign('vcenter');
                $format_bold->sethAlign('left');
                $format_bold->setBorder(1);
                $format_bold->setTextWrap();
                $format_bold2=$xls->addFormat();
                $format_bold2->setBold();
                $format_bold2->setvAlign('vcenter');
                $format_bold2->sethAlign('center');
                $format_bold2->setBorder(1);
                $format_bold2->setTextWrap();
                $format_titulo=$xls->addFormat();
                $format_titulo->setBold();
                $format_titulo->setSize(16);
                $format_titulo->setvAlign('vcenter');
                $format_titulo->sethAlign('center');
                $format_titulo->setBorder(1);
                $format_titulo->setTextWrap();
                $format_titulo2=$xls->addFormat();
                $format_titulo2->setBold();
                $format_titulo2->setSize(12);
                $format_titulo2->setvAlign('vcenter');
                $format_titulo2->sethAlign('center');
                $format_titulo2->setBorder(1);
                $format_titulo2->setTextWrap();
                $sheet->mergeCells(0,0,0,9);   
                //$sheet->write(0,1,"Reporte de Costos por OT del ".$fecha_ini." al ".$fecha_fin." ",$format_titulo);  
                //$sheet->write(0,4,"OT: ".$fecha_ini."   ".$fecha_fin." ",$format_titulo); 
                //$sheet->write(1,0,"CODIGO",$format_titulo2);  $sheet->write(1,1,"LINEA",$format_titulo2);  $sheet->write(1,2,"DESCRIPCION",$format_titulo2);  $sheet->write(1,3,"FECHA",$format_titulo2);   $sheet->write(1,4,"CANTIDAD",$format_titulo2);   if($monedadoc=='S'){    $sheet->write(1,5,"PRECIO S/.",$format_titulo2);   $sheet->write(1,6,"TOTAL S/.",$format_titulo2);     $sheet->write(1,7,"NUMERO DOCUMENTO",$format_titulo2);  }else{    $sheet->write(1,5,"PRECIO $.",$format_titulo2);   $sheet->write(1,6,"TOTAL $.",$format_titulo2);     $sheet->write(1,7,"NUMERO DOCUMENTO",$format_titulo2);   }        
                $z=2;
                $y=2; 
            }
            $total_monto_doc = 0;
            $total_monto_mod = 0;
            $total_total     = 0;
            $total_margen    = 0;
            $ots  = $this->ot_model->listarg($filter,array('ot.nroOt'=>'asc'));
            foreach($ots as $indice2 => $value2){
                $codot = $value2->CodOt;
                $nroOt = $value2->NroOt;
                $dirOt = $value2->DirOt;
                $proyecto = $value2->Proyecto;
                $fecOt    = $value2->fecha;
                $mtoPre   = $value2->MtoPre;
                $tcOt     = $value2->tcOt;
                $impOt    = $value2->ImpOt;
                $monedaOt = $value2->EstOt;
                $fteOt    = $value2->FteOt;
                $peso     = $value2->peso;
                
                /*Nombre de proyecto*/
                $proyectos = $this->proyecto_model->obtener($proyecto);
                $nomproyecto = $proyectos->Des_Larga;
                
                /*Lo presupuestado*/                
                $monto_doc = 0;
                $monto_mod = 0;
                $mtodoc2_soles_t = 0;
                $mtomod2_soles_t = 0;
                $mtodoc2_dolar_t = 0;
                $mtomod2_dolar_t = 0;  
                if($tiproducto=='03' || $tiproducto=='04' || $tiproducto=='07'){
                    $filter5      = new stdClass();
                    $filter5_not  = new stdClass();
                    $filter5->codot      = $codot;
                    $oCtrlObras = $this->ctrlobras_model->listar($filter5,$filter5_not);
                    if(count((array)$oCtrlObras)>0){                    
                        foreach($oCtrlObras as $indice3 => $value3){ 
                            $mtodoc = $value3->MtoDoc2;
                            $mtomod = $value3->Mtomod2;
                            $mo_pre = $value3->Mo;
                            $tc_pre = $value3->tcambio;
                            $mtodoc2_soles = $mo_pre==2?$mtodoc:$mtodoc*$tc_pre;
                            $mtomod2_soles = $mo_pre==2?$mtomod:$mtomod*$tc_pre;
                            $mtodoc2_dolar = $mo_pre==3?$mtodoc:$mtodoc/$tc_pre;
                            $mtomod2_dolar = $mo_pre==3?$mtomod:$mtomod/$tc_pre;        
                            $mtodoc2_soles_t = $mtodoc2_soles_t + $mtodoc2_soles;
                            $mtomod2_soles_t = $mtomod2_soles_t + $mtomod2_soles;
                            $mtodoc2_dolar_t = $mtodoc2_dolar_t + $mtodoc2_dolar;
                            $mtomod2_dolar_t = $mtomod2_dolar_t + $mtomod2_dolar;
                        }
                    }                    
                }
                else{
                    $mtodoc2_soles_t = $mtoPre;
                    $mtomod2_soles_t = 0;
                    $mtodoc2_dolar_t = $mtoPre/$tcOt;
                    $mtomod2_dolar_t = 0; 
                }
                if($moneda=='S'){
                    $monto_doc = $mtodoc2_soles_t;
                    $monto_mod = $mtomod2_soles_t;
                }
                elseif($moneda=='D'){
                    $monto_doc = $mtodoc2_dolar_t;
                    $monto_mod = $mtomod2_dolar_t;        
                }
                
                /*Materiales*/
                $materiales  = "";       
                $materialesD = "";       
                foreach($arrMateriales as $id=>$value){
                    if($id==$codot) {$materiales=$value;break;}
                }
                foreach($arrMaterialesD as $id=>$value){
                    if($id==$codot) {$materialesD=$value;break;}
                } 
                if($moneda=='S'){
                    $materiales_totales = $materiales;
                }
                else{
                    $materiales_totales = $materialesD;
                }

                /*SERVICIOS*/
                $servicios= "";
                foreach($arrServicio as $id=>$value)
                {
                    if($id==$codot)
                    {
                        $servicios=$value;
                        break;
                    }
                }
                $servicios1 = "";
                $servicios2 = "";
                $servicios3 = "";        
                foreach($arrServicio as $id=>$value){
                    if($id==$codot) {
                        foreach($value as $id2=>$value2){
                            if($id2=='01') $servicios1 = $value2;
                            if($id2=='02') $servicios2 = $value2;
                            if($id2=='03') $servicios3 = $value2;                    
                        }
                    }
                }
                $servicios = $servicios1 + $servicios2 + $servicios3; 
                
                /*Mano de obra*/
                $manoobra        = 0;
                $manoobra_real   = 0;
                $manoobraD       = 0;
                $manoobra_realD  = 0;
                $filter8         = new stdClass();
                $filter8->codot  = $codot;
                $filter8->group_by = array("a.codot");
                $oManoObra       = $this->tareo_model->listar_totales($filter8,new stdClass());
                if(count($oManoObra)>0){
                    if($moneda=='S'){
                        $manoobra       = $oManoObra[0]->simple;
                        $manoobra_real  = $oManoObra[0]->real;
                    }
                    elseif($moneda=='D'){
                        $manoobra      = $oManoObra[0]->simpleD;
                        $manoobra_real = $oManoObra[0]->realD;
                    }
                }
                
                /*Caja Chica*/
                $caja_chica = 0;
                $filter9   = new stdClass();
                $filter9->codot   = $codot;
                $filter9->group_by = array("det.codot");
                $oCaja = $this->caja_model->listar_totales($filter9,new stdClass());
                if(count($oCaja)>0){
                    if($moneda=='S'){
                        $caja_chica = $oCaja[0]->subSoles;
                    }
                    elseif($moneda=='D'){
                        $caja_chica = $oCaja[0]->subDolar;
                    } 
                }
                
                /*Costo Partidas Infraestructura*/
                $gastos_infraestructura = 0;
                $filter10 = new stdClass();
                $filter10->codot   = $codot;
                $filter10->codpartida = array('03','04','05','06','07','08','09','10');
                $oGastoInfraestructura = $this->voucher_model->listar_totales($filter10,new stdClass());
                if(count($oGastoInfraestructura)>0){
                    if($moneda=='S'){
                        $gastos_infraestructura = $oGastoInfraestructura[0]->ImpSoles;
                    }
                    elseif($moneda=='D'){
                        $gastos_infraestructura = $oGastoInfraestructura[0]->ImpDolares;
                    }
                }                  
                
                /*Total*/
                $j++;
                $total  = $materiales_totales + $manoobra + $servicios + $caja_chica + $gastos_infraestructura;
                $margen = $monto_doc - $total;
                $color = $margen<0?"color='#FF0000'":"";
                if($tipoexport==""){
                    $fila .= "<tr id='".$codot."'>";
                    $fila .= "<td><a href='#' onclick='rpt_presupuesto(this);'>".$nroOt."</a></td>";
                    $fila .= "<td align='left'>".$dirOt."</td>";
                    $fila .= "<td align='left'>".$nomproyecto."</td>";
                    $fila .= "<td>".$fecOt."</td>";
                    $fila .= "<td>".$fteOt."</td>";
                    $fila .= "<td align='right'><font ".$color.">".number_format($monto_doc,2)."</font></td>";   
                    $fila .= "<td align='right'>".number_format($monto_mod,2)."</font></td>";   
                    $fila .= "<td align='right'><font ".$color.">".number_format($total,2)."</font></td>";   
                    $fila .= "<td align='right'><font ".$color.">".number_format($margen,2)."</font></td>";   
                    $fila .= "</tr>";                    
                }
                elseif($tipoexport=="excel"){
                   $sheet->write($z,0,$nroOt,$format_bold);
                   $sheet->write($z,1,$dirOt,$format_bold);
                   $sheet->write($z,2,$nomproyecto,$format_bold);
                   $sheet->write($z,3,$fecOt,$format_bold);
                   $sheet->write($z,4,$fteOt,$format_bold);
                   $sheet->write($z,5,number_format($monto_doc,2),$format_bold);
                   $sheet->write($z,6,number_format($monto_mod,2),$format_bold);
                   $sheet->write($z,7,number_format($total,2),$format_bold);
                   $sheet->write($z,7,number_format($margen,2),$format_bold);
                   $z++; 
                }
                $total_monto_doc = $total_monto_doc + $monto_doc;
                $total_monto_mod = $total_monto_mod + $monto_mod;
                $total_total     = $total_total + $total;
                $total_margen    = $total_margen + $margen;
            }
            $fila .= "<tr>";
            $fila .= "<td colspan='5'>&nbsp;</td>";
            $fila .= "<td align='right'>".number_format($total_monto_doc,2)."</td>";
            $fila .= "<td align='right'>".number_format($total_monto_mod,2)."</td>";
            $fila .= "<td align='right'>".number_format($total_total,2)."</td>";
            $fila .= "<td align='right'>".number_format($total_margen,2)."</td>";
            $fila .= "</tr>"; 
            if($tipoexport=='excel'){
                $xls->close(); 
            }
        }
        $data['seltipot']     = $selecttipoot;
        $data['selproducto']  = $selectipoproducto;
        $data['selestado']    = $selecestado;
        $data['selproyecto']  = $selproyecto;
        $data['selmoneda']    = $selmoneda;
        $data['tipo_reporte'] = $tipo_reporte;
        $data['fila']         = $fila;
        $data['j']            = $j;
        $data['tiproducto']   = $tiproducto;
        $data['verencabezado'] = "";
        $data['oculto']       = form_hidden(array('serie'=>'','numero'=>'','cadenaot'=>'','codot'=>'','tipoexport'=>''));     
        $this->load->view(ventas."rpt_control",$data);
    }
}