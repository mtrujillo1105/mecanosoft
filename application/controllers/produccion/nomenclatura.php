<?php
class Nomenclatura extends CI_Controller {
    var $entidad;
    var $login;
    
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");
        $this->load->model(siddex . 'listamat_model');  
        $this->load->model(ventas.'ot_model');
        $this->load->model(maestros.'ttorre_model');
    }
    
    public function index(){
        redirect(siddex."nomenclatura/listar");
    }
    
    public function lista_conjuntognal(){
        
    }
    
    public function lista_conjunto(){
        
    }
    
    public function lista_material(){
        $fecha_ini     = $this->input->get_post('fecha_ini');
        $fecha_fin     = $this->input->get_post('fecha_fin'); 
        $tipoexport    = $this->input->get_post('tipoexport');
        $numero        = $this->input->get_post('numero');
        $fecha         = $this->input->get_post('fecha');
        $codot         = $this->input->get_post('codot');
        $arrCodOT      = $codot!=''?array($codot):explode(",",$cadenaot);
        $arrnumero     = array();
        $fila          = "";
        foreach($arrCodOT as $item => $value){
            $filter = new stdClass();
            $filter->codot = $value;
            $oOt         = $this->ot_model->obtenerg($filter,new stdClass());
            $numero      = trim(str_replace("-","",$oOt->NroOt));  
            $arrnumero[] = $numero;
            $arrsite[]   = $oOt->DirOt;
            $torres      = $this->ttorre_model->obtener($oOt->Torre);
            $arrtorre[$numero]  = $torres->Des_Larga;
        }
        $filter = new stdClass();
        $filter->numero = $arrnumero;
        $filter->order_by = array("lm.CodigoMaquina"=>"asc","lm.CodigoConjunto"=>"asc","lm.Marca"=>"asc");
        $listamateriales = $this->listamat_model->listar($filter);
        $peso  = 0;
        $arr_export_detalle = array();
        if(count($listamateriales)>0){
            foreach($listamateriales as $item => $value){
                $arr_data = array();
                $fila  .= "<tr class='".($item%2==0?'itemParTabla':'itemParTabla')."'>";
                $fila  .= "<td align='center'>".$value->NumeroOrden."</td>";
                $arr_data[] =$value->NumeroOrden;
                $fila  .= "<td align='center'>".$arrtorre[trim($value->NumeroOrden)]."</td>";
                $arr_data[] =utf8_encode($arrtorre[trim($value->NumeroOrden)]);
                $fila  .= "<td align='center'>".$value->Marca."</td>";
                $arr_data[] =$value->Marca;
                $fila  .= "<td align='center'>".$value->CodigoMaquina."</td>";
                $arr_data[] =$value->CodigoMaquina;
                $fila  .= "<td align='center'>".$value->CodigoConjunto."</td>";
                $arr_data[] =$value->CodigoConjunto;
                $fila  .= "<td align='center'>".$value->CodigoMP."</td>";
                $arr_data[] =$value->CodigoMP;
                $fila  .= "<td align='center'>".$value->Familia."</td>";
                $arr_data[] =utf8_encode($value->Familia);
                $fila  .= "<td align='left'>".$value->DescripcionMP."</td>";
                $arr_data[] =utf8_encode($value->DescripcionMP);
                $fila  .= "<td align='right'>".number_format($value->CantidadConjuntoG,2)."</td>";
                $arr_data[] =$value->CantidadConjuntoG;
                $fila  .= "<td align='right'>".number_format($value->CantidadConjunto,2)."</td>";
                $arr_data[] =$value->CantidadConjunto;
                $fila  .= "<td align='right'>".number_format($value->CantidadAlmacen,2)."</td>";
                $arr_data[] =$value->CantidadAlmacen;
                $fila  .= "<td align='right'>".$value->LargoPieza."</td>";
                $arr_data[] =$value->LargoPieza;
                $fila  .= "<td align='right'>".number_format($value->AnchoPieza,2)."</td>";
                $arr_data[] =$value->AnchoPieza;
                $fila  .= "<td align='right'>".number_format($value->EspesorMedicion,2)."</td>";
                $arr_data[] =$value->EspesorMedicion;
                $fila  .= "<td align='right'>".number_format($value->Peso,2)."</td>";
                $arr_data[] =$value->Peso;
                $fila  .= "</tr>";    
                $peso   = $peso + $value->Peso;
                array_push($arr_export_detalle,$arr_data);
            }
            $fila  .= "<tr>";                
            $fila  .= "<td align='center' colspan='14'>&nbsp;</td>";
            $fila  .= "<td align='right'>".number_format($peso,2)."</td>";
            $fila  .= "</tr>";  
            $var_export = array('rows' => $arr_export_detalle);
            $this->session->set_userdata('data_listar_nomenclatura', $var_export);            
        }
        else{
            $fila  .= "<tr><td colspan='15' align='center'>::: NO EXISTEN REGISTROS :::</td></tr>   ";
        }    
        $data['fila']        = $fila;
        $data['tipoexport']  = $tipoexport;
        $data['arrnumero']   = $arrnumero;
        $data['arrsite']     = $arrsite;
        $data['fecha_ini']   = $fecha_ini;
        $data['fecha_fin']   = $fecha_fin;
        $this->load->view(siddex."lista_material",$data);        
    }
    
    public function listar(){
        $offset             = (int)$this->uri->segment(3);
        $conf['base_url']   = site_url('almacen/nsalida/listar/');
        $conf['per_page']   = 30;
        $conf['num_links']  = 3;
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['next_link']  = "&gt;";
        $conf['prev_link']  = "&lt;";
        $conf['uri_segment']= 4;
        $conf['total_rows'] = 100;
        $filter             = new stdClass();
        $filter_not         = new stdClass();
        $filter->fechai     = "01/01/2012";
        $listado            = $this->ningreso_model->listar($filter,$filter_not,"",$conf['per_page'],$offset);
        $item               = $j+1;
        $fila               = "";
        if(count($listado)>0){
             foreach($listado as $indice=>$valor){
                 $fecha  = $valor->fecha;
                 $codot  = $valor->codot;
                 $serie  = $valor->serie;
                 $numero = $valor->numero;
                 $numoc  = $valor->numoc;
                 $numcom = $valor->numcom;
                 $fila  .= "<tr class='".($indice%2==0?'itemParTabla':'itemParTabla')."'>";
                 $fila  .= "<td align='center'>".$item++."</td>";
                 $fila  .= "<td align='center'>".date_sql($fecha)."</td>";
                 $fila  .= "<td align='center'>".$codot."</td>";
                 $fila  .= "<td align='center'>".$serie."</td>";
                 $fila  .= "<td align='center'>".$numero."</td>";
                 $fila  .= "<td align='center'>".$numoc."</td>";
                 $fila  .= "<td align='center'>".$numcom."</td>";
                 $fila  .= "<td align='center'><a href='#' onclick='editar(".$numcom.")'><img src='".base_url()."img/modificar.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='ver(".$numcom.")'><img src='".base_url()."img/ver.png' width='16' height='16' border='0' title='Modificar'></a></td>";
                 $fila  .= "<td align='center'><a href='#' onclick='eliminar(".$numcom.")'><img src='".base_url()."img/eliminar.png' width='16' height='16' border='0' title='Modificar'></a></td>";                 
                 $fila  .= "</tr>";
             }
        }
        $data['fila']           = $fila;
        $data['titulo_busqueda'] = "Buscar Notas de Ingreso";
        $data['titulo_tabla']    = "Relaci&oacute;n de Notas de Ingreso";
        $this->pagination->initialize($conf);
        $data['paginacion']      = $this->pagination->create_links();
        $this->load->view(almacen."ningreso_listar",$data);		
    }
    
     public function export_excel($type) {
        if($this->session->userdata('data_'.$type)){
            $result = $this->session->userdata('data_'.$type);
            $arr_columns = array();            
            switch ($type) {
                case 'listar_nomenclatura':
                    $arr_export_detalle = array();
                    $arr_columns[]['STRING']  = 'NRO.OT';
                    $arr_columns[]['STRING']  = 'T.TORRE';
                    $arr_columns[]['STRING']  = 'MARCA';
                    $arr_columns[]['STRING']  = 'CJTO.GENERAL';
                    $arr_columns[]['STRING']  = 'CONJUNTO';
                    $arr_columns[]['STRING']  = 'CODIGO';
                    $arr_columns[]['STRING']  = 'FAMILIA';
                    $arr_columns[]['STRING']  = 'DESCRIPCION';
                    $arr_columns[]['NUMERIC'] = 'CANT.CJTO.GNAL.';
                    $arr_columns[]['NUMERIC'] = 'CANT.CJTO.';
                    $arr_columns[]['NUMERIC'] = 'PIEZAS LISTA';
                    $arr_columns[]['NUMERIC'] = 'LARGO(MM)';
                    $arr_columns[]['NUMERIC'] = 'ANCHO(MM)';
                    $arr_columns[]['NUMERIC'] = 'ESPESOR(MM)';
                    $arr_columns[]['NUMERIC'] = 'P.TOTAL(KG)';
                    $arr_group = array();
                    $this->reports_model->rpt_general('rpt_'.$type,'Nomenclatura',$arr_columns,$result["rows"],$arr_group); 
                    break;
            }
        }else{
            echo "<div style='color:rgb(150,150,150);padding:10px;width:560px;height:160px;border:1px solid rgb(210,210,210);'>
                No hay datos para exportar
                </div>";
        }
    }    
}
?>