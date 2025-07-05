<?php header("Content-type: text/html; charset=utf-8"); 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notaingreso extends CI_Controller{
    var $configuracion;    
    public function __construct(){
        parent::__construct();
        $this->load->model(produccion.'notaingreso_model');
        $this->load->model(produccion.'tipomaterial_model');        
        $this->configuracion = $this->config->item('conf_pagina');
    }
  
    public function index(){
        $this->load->view('seguridad/inicio');
    }    
    
    public function listar($j='0'){
        $filter     = new stdClass();
        $filter_not = new stdClass(); 
        $filter->order_by    = array("UNDMED_Descripcion"=>"asc");
        $registros = count($this->unidadmedida_model->listar($filter,$filter_not));
        $umedida   = $this->unidadmedida_model->listar($filter,$filter_not,$this->configuracion['per_page'],$j);
        $item      = 1;
        $lista     = array();
        if(count($umedida)>0){
            foreach($umedida as $indice=>$valor){             
                $lista[$indice]              = new stdClass();
                $lista[$indice]->codigo      = $valor->UNDMED_Codigo;
                $lista[$indice]->descripcion = $valor->UNDMED_Descripcion;
                $lista[$indice]->simbolo     = $valor->UNDMED_Simbolo;
            }
        }       
        $configuracion = $this->configuracion;
        $configuracion['base_url']    = base_url()."index.php/almacen/unidadmedida/listar";
        $configuracion['total_rows']  = $registros;
        $this->pagination->initialize($configuracion);
        $data['titulo_tabla']    = "RELACI&Oacute;N de UNIDADES DE MEDIDA";
        $data['titulo_busqueda'] = "BUSCAR UNIDAD MEDIDA";
        $data['form_open']  = form_open(base_url().'index.php/almacen/unidadmedida/buscar',array("name"=>"form_busquedaUnidadmedida","id"=>"form_busquedaUnidadmedida"));
        $data['form_close'] = form_close();           
        $data['lista']      = $lista;
        $data['codigo']     = "";
        $data['nombre']     = "";
        $data['familia']    = "";
        $data['registros']  = $registros;
        $data['paginacion'] = $this->pagination->create_links();
        $data['oculto']     = form_hidden(array('accion'=>'','codigo'=>''));	
        $this->load->view('almacen/unidadmedida_index',$data);
    }
    
    public function editar($accion='n',$codigo="")
    {
        $lista = new stdClass();
        if($accion == "e"){
            $filter              = new stdClass();
            $filter->unidad      = $codigo;
            $unidades            = $this->unidadmedida_model->obtener($filter);
            $lista->descripcion  = $unidades->UNDMED_Descripcion;
            $lista->simbolo      = $unidades->UNDMED_Simbolo;
            $lista->fecha        = "";
            $lista->tipomaterial = "";
        }    
        elseif($accion == "n"){
            $lista->descripcion  = "";
            $lista->simbolo      = ""; 
            $lista->fecha        = date('d/m/Y',time());
            $lista->tipomaterial = "";
        }
        $arrGanchera         = array("1"=>"Ganchera N°1","2"=>"Ganchera N°2","3"=>"Ganchera N°3","4"=>"Ganchera N°4","5"=>"Ganchera N°5","6"=>"Ganchera N°6","7"=>"Ganchera N°7","8"=>"Ganchera N°8","9"=>"Ganchera N°9","10"=>"Ganchera N°10");
        $seltipomaterial     = form_dropdown('tipomaterial',$this->tipomaterial_model->seleccionar(''),$lista->tipomaterial,"id='tipomaterial' class='comboPequeno'");                 
        $selganchera         = form_dropdown('ganchera',$arrGanchera,$lista->tipomaterial,"id='ganchera' class='comboPequeno'");                 
        $data['titulo']      = "NOTA DE ENTRADA - LINEA DE GALVANIZADO";        
        $data['form_open']   = form_open(base_url().'index.php/almacen/unidadmedida/grabar/',array("name"=>"frmUnidadmedida","id"=>"frmUnidadmedida"));
        $data['form_close']  = form_close();
        $data['lista']	     = $lista;
        $data['seltipomat']  = $seltipomaterial;  
        $data['selganchera'] = $selganchera;  
        $data['oculto']      = form_hidden(array('accion'=>$accion,'codigo'=>$codigo));
        $this->load->view("produccion/notaingreso_nuevo",$data);
    }
    
    public function grabar()
    {
        $accion = $this->input->get_post('accion');
        $codigo = $this->input->get_post('codigo');
        $data   = array(
                        "UNDMED_Descripcion" => strtoupper($this->input->post('descripcion')),
                        "UNDMED_Simbolo"     => strtoupper($this->input->post('simbolo'))
                       );
        if($accion == "n"){
            $this->unidadmedida_model->insertar($data);            
        }
        elseif($accion == "e"){
            $this->unidadmedida_model->modificar($codigo,$data);            
        }
    }
    
    public function eliminar()
    {
        $codigo = $this->input->post('codigo');
        $this->unidadmedida_model->eliminar($codigo);
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