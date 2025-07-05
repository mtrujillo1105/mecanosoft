<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once "Spreadsheet/Excel/Writer.php";
class Cargo extends CI_Controller {
    var $entidad;
    var $login;
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");   
        $this->load->model(maestros.'cargo_model');
        $this->entidad = $this->session->userdata('entidad');
        $this->login   = $this->session->userdata('login');
    }
    
    public function index(){

    }
    public function listar($j='0'){
        $data['txtCargo']   = "";
        $data['registros']  = count($this->cargo_model->listar_cargos());
        $conf['base_url']   = site_url('maestros/cargo/cargos/');
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
        $listado_cargos     = $this->cargo_model->listar_cargos($conf['per_page'],$offset);
        $item               = $j+1;
        $lista                = array();
        if(count($listado_cargos)>0){
             foreach($listado_cargos as $indice=>$valor){
                 $codigo         = $valor->CARGP_Codigo;
                 $editar         = "<a href='#' onclick='editar_cargo(".$codigo.")'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                 $ver            = "<a href='#' onclick='ver_cargo(".$codigo.")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                 $eliminar       = "<a href='#' onclick='eliminar_cargo(".$codigo.")'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
                 $lista[]        = array($item++,$valor->CARGC_Descripcion,$editar,$ver,$eliminar);
             }
        }
        $data['action']          = base_url()."index.php/maestros/cargo/buscar_cargos";
        $data['titulo_tabla']    = "RELACI&Oacute;N de CARGOS";
        $data['titulo_busqueda'] = "BUSCAR CARGO";
        $data['lista']      = $lista;
        $data['oculto']     = form_hidden(array('base_url'=>base_url()));
        $this->load->view(maestros."cargos_index",$data);
    }
    public function nuevo(){
            $this->load->library('layout','layout');
            $modo      = "";
            $accion    = "";
            $modo      = "insertar";
            $codigo    = "";
            $data['form_open']  = form_open(base_url().'index.php/maestros/cargo/insertar_cargo',array("name"=>"frmCargo","id"=>"frmCargo"));
            $data['form_close'] = form_close();
            $lblCargo  = form_label('NOMBRE DEL CARGO','nombre');
            $txtCargo  = form_input(array('name'=>'nombre','id'=>'nombre','value'=>'','maxlength'=>'30','class'=>'cajaMedia'));
            $oculto    = form_hidden(array('accion'=>$accion,'codigo'=>$codigo,'modo'=>$modo,'base_url'=>base_url()));
            $data['titulo']     = "REGISTRAR CARGOS";
            $data['formulario'] = "frmProveedor";
            $data['campos']     = array($lblCargo);
            $data['valores']    = array($txtCargo);
            $data['oculto']     = $oculto;
            $data['onload']		= "onload=\"$('#nombre').focus();\"";
            $this->layout->view('maestros/cargo_nuevo',$data);
    }
    public function insertar(){
            $this->form_validation->set_rules('nombre','Nombre de cargo ','required');
            if($this->form_validation->run() == FALSE){
                    $this->nuevo_cargo();
            }
            else{
                    $nombre = $this->input->post('nombre');
                    $this->cargo_model->insertar_cargo($nombre);
                    $this->cargos();
            }
    }
    public function modificar($codigo){
        $this->load->library('layout','layout');
        $accion       = "";
        $modo         = "modificar";
        $datos_cargo  = $this->cargo_model->obtener_cargo($codigo);
        $nombre_cargo = $datos_cargo[0]->CARGC_Descripcion;
        $data['form_open']  = form_open(base_url().'index.php/maestros/cargo/modificar_cargo',array("name"=>"frmCargo","id"=>"frmCargo"));
        $data['form_close'] = form_close();
        $lblCargo     = form_label('NOMBRE DEL CARGO','nombre');
        $txtCargo     = form_input(array('name'=>'nombre','id'=>'nombre','value'=>$nombre_cargo,'maxlength'=>'30','class'=>'cajaMedia'));
        $oculto       = form_hidden(array('accion'=>$accion,'codigo'=>$codigo,'modo'=>$modo,'base_url'=>base_url()));
        $data['titulo']     = "EDITAR CARGOS";
        $data['formulario'] = "frmProveedor";
        $data['campos']     = array($lblCargo);
        $data['valores']    = array($txtCargo);
        $data['oculto']     = $oculto;
        $data['onload']		= "onload=\"$('#nombre').select();$('#nombre').focus();\"";
        $this->layout->view('maestros/cargo_nuevo',$data);
    }
    public function modificar_cargo(){
        $this->form_validation->set_rules('nombre','Nombre de cargo','required');
        if($this->form_validation->run() == FALSE){
                $this->nuevo_cargo();
        }
        else{
                $cargo  = $this->input->post('codigo');
                $nombre = $this->input->post('nombre');
                $this->cargo_model->modificar_cargo($cargo,$nombre);
                $this->cargos();
        }
    }
    public function eliminar(){
        $cargo = $this->input->post('cargo');
        $this->cargo_model->eliminar_cargo($cargo);
    }
    public function ver($codigo)
    {
        $this->load->library('layout','layout');
        $data['datos_cargo'] = $this->cargo_model->obtener_cargo($codigo);
        $data['titulo']      = "VER CARGO";
        $data['oculto']      = form_hidden(array('base_url'=>base_url()));
        $this->layout->view('maestros/cargo_ver',$data);
    }
    public function buscar($j='0')
    {
        $this->load->library('layout','layout');
        $nombre_cargo       = $this->input->post('txtCargo');
        $filter=new stdClass();
        $filter->nombre_cargo = $nombre_cargo;
        $data['txtCargo']   = $nombre_cargo;
        $data['registros']  = count($this->cargo_model->buscar_cargos($filter));
        $conf['base_url']   = site_url('maestros/cargo/buscar_cargos/');
        $conf['per_page']   = 10;
        $conf['num_links']  = 3;
        $conf['next_link'] = "&gt;";
        $conf['prev_link'] = "&lt;";
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['uri_segment'] = 4;
        $conf['total_rows'] = $data['registros'];
        $offset             = (int)$this->uri->segment(4);
        //echo $conf['per_page'].' - '.$offset;
        $listado_cargos     = $this->cargo_model->buscar_cargos($filter,$conf['per_page'],$offset);
        //echo '<br/>'.count($listado_cargos);
        $item               = $j+1;
        $lista              = array();
        if(count($listado_cargos)>0){
            foreach($listado_cargos as $indice=>$valor){
                $codigo         = $valor->CARGP_Codigo;
                $editar         = "<a href='#' onclick='editar_cargo(".$codigo.")' target='_parent'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                $ver            = "<a href='#' onclick='ver_cargo(".$codigo.")' target='_parent'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                $eliminar       = "<a href='#' onclick='eliminar_cargo(".$codigo.")' target='_parent'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
                $lista[]        = array($item++,$valor->CARGC_Descripcion,$editar,$ver,$eliminar);
            }
        }
        $data['action']          = base_url()."index.php/maestros/cargo/buscar_cargos";
        $data['titulo_tabla']    = "RESULTADO DE BUSQUEDA de CARGOS";
        $data['titulo_busqueda'] = "BUSCAR CARGO";
        $data['lista']      = $lista;
        $data['oculto']     = form_hidden(array('base_url'=>base_url()));
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $this->layout->view('maestros/cargo_index',$data);
    }
}
?>