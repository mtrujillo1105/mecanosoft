<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once "Spreadsheet/Excel/Writer.php";
class Tcambio extends CI_Controller {
    var $compania;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");   
        $this->load->model(maestros.'tc_model');
        $this->compania = $this->session->userdata('compania');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index(){
        $this->load->view(maestros."tcambio_index");
    }
    public function listar($j='0')
    {

        $data['registros']  = count($this->tc_model->listar());
        $conf['base_url']   = site_url('maestros/tipocambio/listar/');
        $conf['total_rows'] = $data['registros'];
        $conf['per_page']   = 10;
        $conf['num_links']  = 3;
        $conf['next_link'] = "&gt;";
        $conf['prev_link'] = "&lt;";
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['uri_segment'] = 4;
        $offset             = (int)$this->uri->segment(4);
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $listado            = $this->tc_model->listar('', $conf['per_page'],$offset);
        $item               = $j+1;
        $lista              = array();
        $listado_moneda =$this->moneda_model->listar();
        if(count($listado)>0){
            
            foreach($listado as $indice=>$valor)
            {   $codigo = $valor->TIPCAMP_Codigo;
                $fecha = $valor->TIPCAMC_Fecha;
                
                $valores_tipocam=array();
                foreach($listado_moneda as $reg){
                    if($reg->MONED_Codigo!=1){
                        $filter=new stdClass();
                        $filter->TIPCAMC_MonedaDestino=$reg->MONED_Codigo;
                        $filter->TIPCAMC_Fecha=$fecha;
                        $temp=$this->tipocambio_model->buscar($filter);
                        if(count($temp)>0)
                            $valores_tipocam[]=$temp[0]->TIPCAMC_FactorConversion;                        
                        else
                            $valores_tipocam[]='';
                    }
                }
                $ver            = "<a href='#' onclick='ver_tipocambio(".str_replace('-', '',$fecha).")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                $lista[]        = array($item++,$fecha,$valores_tipocam,$ver);
            }
        }
        $data['listado_moneda']   = $listado_moneda;
        $data['lista']           = $lista;
        $data['titulo_busqueda'] = "BUSCAR TIPO DE CAMBIO";
        $data['fecha']  	 = form_input(array("name"=>"fecha","id"=>"fecha","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>""));
        $data['form_open']       = form_open(base_url().'index.php/maestros/tipocambio/buscar',array("name"=>"form_busquedaTipoCambio","id"=>"form_busquedaTipoCambio"));
        $data['form_close']      = form_close();
        $data['titulo_tabla']    = "Relaci&oacute;n DE TIPO DE CAMBIOS";
        $data['oculto']          = form_hidden(array('base_url'=>base_url()));	
        $this->load->view(maestros."tcambio_index");
			
    }
    public function nuevo($ventana=false)
    {
        $this->load->library('layout', 'layout');
        $data['lista_monedas']=$this->moneda_model->listar();

        $data['titulo']     = "REGISTRAR TIPO DE CAMBIO DEL DIA : ".date('d/m/Y');
        $data['form_open']  = form_open(base_url().'index.php/maestros/tipocambio/grabar',array("name"=>"frmTipoCambio","id"=>"frmTipoCambio"));
        $data['form_close'] = form_close();
        $data['oculto']     = form_hidden(array('base_url'=>base_url()));
        if($ventana==false)
            $this->layout->view('maestros/tipocambio_nuevo',$data);
        else
            $this->load->view('maestros/tipocambio_ventana_configura',$data);
        
    }
    public function grabar()
    {
        $tipocambios  = $this->input->post("tipocambio");
        $monedas = $this->input->post("moneda");
        $moneda_origen = $this->input->post("moneda_origen");
        $fecha = $this->input->post("fecha");

        $filter = new stdClass();
        $filter->TIPCAMC_Fecha = $fecha;
        $this->tipocambio_model->eliminar_varios($filter);
        
        foreach($tipocambios as $item=>$tipocambio){
            if($tipocambio!=''){
                $filter = new stdClass();
                $filter->TIPCAMC_MonedaOrigen  = $moneda_origen;
                $filter->TIPCAMC_MonedaDestino = $monedas[$item];
                $filter->TIPCAMC_Fecha = $fecha;
                $filter->TIPCAMC_FactorConversion = $tipocambio;
                $filter->COMPP_Codigo = $this->somevar['compania'];

                $this->tipocambio_model->insertar($filter);
            }
        }
    }
    public function eliminar()
    {
        $id = $this->input->post('almacen');
        $this->almacen_model->eliminar($id);
    }
    public function ver($fecha)
    {   $this->load->library('layout', 'layout');
        
        if(strlen($fecha)!=8)
            show_error('La fecha enviada es incorrecta.');

        $fecha=substr($fecha,0,4).'-'.substr($fecha,4,2).'-'.substr($fecha,6,2);
    
        $lista_monedas=$this->moneda_model->listar();
        $data['lista_monedas']=$lista_monedas;
        $valores=array();

        foreach($lista_monedas as $reg){
            if($reg->MONED_Codigo!=1){
                $filter=new stdClass();
                $filter->TIPCAMC_Fecha=$fecha;
                $filter->TIPCAMC_MonedaDestino =$reg->MONED_Codigo;
                $temp=$this->tipocambio_model->buscar($filter);
                if(count($temp)>0)
                    $valores[$reg->MONED_Codigo]=$temp[0]->TIPCAMC_FactorConversion;
                else
                    $valores[$reg->MONED_Codigo]='';
            }
        }
        $data['valores']=$valores;
        $data['titulo']        = "VER TIPO DE CAMBIO DEL DIA : ".substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
        $data['oculto']=form_hidden(array('base_url'=>base_url()));	
        $this->layout->view("maestros/tipocambio_ver", $data);
    }
    public function buscar($j=0)
    {
        $this->load->library('layout','layout');
        $fecha                 = $this->input->post('fecha');
        $data['registros']      = count($this->tipocambio_model->listar($fecha));
        $conf['base_url']       = site_url('almacen/almacen/buscar/');
        $conf['per_page']       = 10;
        $conf['num_links']      = 3;
        $conf['first_link']     = "&lt;&lt;";
        $conf['last_link']      = "&gt;&gt;";
        $conf['total_rows']     = $data['registros'];
        $offset                 = (int)$this->uri->segment(4);
        $listado                = $this->tipocambio_model->listar($fecha,$conf['per_page'],$offset);
        $item                   = $j+1;
        $lista                  = array();
        $listado_moneda =$this->moneda_model->listar();
        if(count($listado)>0){
            
            foreach($listado as $indice=>$valor)
            {   $codigo = $valor->TIPCAMP_Codigo;
                $fecha = $valor->TIPCAMC_Fecha;
                
                $valores_tipocam=array();
                foreach($listado_moneda as $reg){
                    if($reg->MONED_Codigo!=1){
                        $filter=new stdClass();
                        $filter->TIPCAMC_MonedaDestino=$reg->MONED_Codigo;
                        $filter->TIPCAMC_Fecha=$fecha;
                        $temp=$this->tipocambio_model->buscar($filter);
                        if(count($temp)>0)
                            $valores_tipocam[]=$temp[0]->TIPCAMC_FactorConversion;                        
                        else
                            $valores_tipocam[]='';
                    }
                }
                $ver            = "<a href='#' onclick='ver_tipocambio(".str_replace('-', '',$fecha).")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                $lista[]        = array($item++,$fecha,$valores_tipocam,$ver);
            }
        }
        $data['titulo_tabla']    = "RESULTADO DE BUSQUEDA de TIPO DE CAMBIO DEL DIA";
        $data['titulo_busqueda'] = "BUSCAR TIPO DE CAMBIO";
        $data['fecha']  	 = form_input(array("name"=>"fecha","id"=>"fecha","class"=>"cajaPequena","readonly"=>"readonly","maxlength"=>"10","value"=>$fecha));
        $data['form_open']       = form_open(base_url().'index.php/maestros/tipocambio/buscar',array("name"=>"form_busquedaTipoCambio","id"=>"form_busquedaTipoCambio"));
        $data['form_close']      = form_close();
        $data['listado_moneda']   = $listado_moneda;
        $data['lista']           = $lista;
        $data['oculto']          = form_hidden(array('base_url'=>base_url()));
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $this->layout->view('maestros/tipocambio_index',$data);
    }
}
?>
