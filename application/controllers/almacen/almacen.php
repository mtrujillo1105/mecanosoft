<?php header("Content-type: text/html; charset=utf-8"); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Almacen extends CI_Controller {
    var $compania;
    var $configuracion; 
    public function __construct(){
        parent::__construct();
        date_default_timezone_set('America/Los_Angeles');       
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");    
        $this->load->model(almacen.'almacen_model');
        $this->load->model(almacen.'tipoalmacen_model');        
        $this->configuracion = $this->config->item('conf_pagina');        
    }

    public function index(){
        $this->load->view('seguridad/inicio');
    }     
    
    public function listar($j='0')
    {
        $filter     = new stdClass();
        $filter_not = new stdClass(); 
        $filter->order_by    = array("c.ALMAC_Descripcion"=>"asc");
        $registros = count($this->almacen_model->listar($filter,$filter_not));
        $almacenes = $this->almacen_model->listar($filter,$filter_not,$this->configuracion['per_page'],$j);      
        $item      = 1;
        $lista     = array();
        if(count($almacenes)>0){
            foreach($almacenes as $indice=>$valor){             
                $lista[$indice]              = new stdClass();
                $lista[$indice]->codigo      = $valor->ALMAP_Codigo;
                $lista[$indice]->descripcion = $valor->ALMAC_Descripcion;
                $lista[$indice]->direccion   = $valor->ALMAC_Direccion;
                $lista[$indice]->tipoalmacen = $valor->TIPALM_Descripcion;
            }
        }  

        $configuracion = $this->configuracion;
        $configuracion['base_url']    = base_url()."index.php/almacen/almacen/listar";
        $configuracion['total_rows']  = $registros;
        $this->pagination->initialize($configuracion);
        $data['titulo_tabla']    = "RELACI&Oacute;N de ALMACENES";
        $data['titulo_busqueda'] = "BUSCAR ALMACEN";
        $data['form_open']  = form_open('',array("name"=>"frmBuscar","id"=>"frmBuscar"));
        $data['form_close'] = form_close();           
        $data['lista']      = $lista;
        $data['codigo']     = "";
        $data['nombre']     = "";
        $data['familia']    = "";
        $data['registros']  = $registros;
        $data['paginacion'] = $this->pagination->create_links();
        $data['oculto']     = form_hidden(array('accion'=>'','codigo'=>''));	
        $this->load->view('almacen/almacen_index',$data);
			
    }

    public function editar($accion,$codigo="")
    {
        $lista = new stdClass();
        if($accion == "e"){
            $filter             = new stdClass();
            $filter->almacen    = $codigo;
            $almacenes          = $this->almacen_model->obtener($filter);
            $lista->descripcion = $almacenes->ALMAC_Descripcion;
            $lista->direccion   = $almacenes->ALMAC_Direccion;
            $lista->tipoalmacen = $almacenes->TIPALM_Descripcion;
        }    
        elseif($accion == "n"){
            $lista->descripcion = "";
            $lista->direccion   = ""; 
            $lista->tipoalmacen = ""; 
        }
        $data['titulo']     = "EDITAR ALMACEN";        
        $data['form_open']  = form_open('',array("name"=>"frmAlmacen","id"=>"frmAlmacen"));
        $data['form_close'] = form_close();
        $data['lista']	    = $lista;
        $data['oculto']     = form_hidden(array('accion'=>$accion,'codigo'=>$codigo));
        $this->load->view('almacen/almacen_nuevo',$data);
    }
    
    public function grabar()
    {
        $accion = $this->input->get_post('accion');
        $codigo = $this->input->get_post('codigo');
        $data   = array(
                        "ALMAC_Descripcion" => strtoupper($this->input->post('descripcion')),
                        "ALMAC_Direccion"     => strtoupper($this->input->post('direccion'))
                       );
        if($accion == "n"){
            $this->almacen_model->insertar($data);            
        }
        elseif($accion == "e"){
            $this->almacen_model->modificar($codigo,$data);            
        }
    }
    
    public function eliminar()
    {
        $codigo = $this->input->post('codigo');
        $this->almacen_model->eliminar($codigo);
    }
    
    public function ver($codigo)
    {
        $this->load->library('layout', 'layout');
        $datos_umedida         = $this->unidadmedida_model->obtener($codigo);
        $nombre_umedida        = $datos_umedida[0]->UNDMED_Descripcion;
        $simbolo               = $datos_umedida[0]->UNDMED_Simbolo;
        $data['nombre_unidadmedida']     = $nombre_umedida;
        $data['simbolo']       = $simbolo;
        $data['titulo']        = "VER UNIDAD MEDIDA";
        $data['oculto']        = form_hidden(array('base_url'=>base_url()));
        $this->layout->view('almacen/unidadmedida_ver',$data);
    }
}
?>