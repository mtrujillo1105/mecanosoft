<?php
class Formapago extends controller
{
    public function __construct()
    {
        parent::Controller();
        $this->load->helper('html');
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->library('html');
        $this->load->library('form_validation');
        $this->load->model('maestros/formapago_model');
        $this->somevar['compania'] = $this->session->userdata('compania');
    }
    public function listar($j='0')
    {
        $this->load->library('layout', 'layout');
        $data['nombre_formapago']  = "";
        $data['registros']   = count($this->formapago_model->listar());
        $conf['base_url']    = site_url('maestros/formapago/listar/');
        $conf['total_rows']  = $data['registros'];
        $conf['per_page']    = 10;
        $conf['num_links']   = 3;
        $conf['next_link']   = "&gt;";
        $conf['prev_link']   = "&lt;";
        $conf['first_link']  = "&lt;&lt;";
        $conf['last_link']   = "&gt;&gt;";
        $conf['uri_segment'] = 4;
        $offset              = (int)$this->uri->segment(4);
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $listado            = $this->formapago_model->listar($conf['per_page'],$offset);
        $item               = $j+1;
        foreach($listado as $indice=>$valor)
        {
            $codigo         = $valor->FORPAP_Codigo;
            $editar         = "<a href='#' onclick='editar_formapago(".$codigo.")'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
            $ver            = "<a href='#' onclick='ver_formapago(".$codigo.")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
            $eliminar       = "<a href='#' onclick='eliminar_formapago(".$codigo.")'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
            $lista[]        = array($item++,$valor->FORPAC_Descripcion,$editar,$ver,$eliminar);
        }
        $data['lista']            = $lista;
        $data['titulo_busqueda']  = "BUSCAR FORMA DE PAGO";
        $data['nombre_formapago'] = form_input(array( 'name'  => 'nombre_formapago','id' => 'nombre_formapago','value' => '','maxlength' => '100','class' => 'cajaMedia'));
        $data['form_open']        = form_open(base_url().'index.php/maestros/formapago/buscar',array("name"=>"form_busquedaFormapago","id"=>"form_busquedaFormapago"));
        $data['form_close']       = form_close();
        $data['titulo_tabla']     = "Relaci&oacute;n DE FORMAS DE PAGO";
        $data['oculto']           = form_hidden(array('accion'=>"",'codigo'=>"",'modo'=>"insertar",'base_url'=>base_url()));
        $this->layout->view('maestros/formapago_index',$data);
			
    }
    public function nuevo()
    {
        $this->load->library('layout', 'layout');
        $lblDescripcion     = form_label("Nombre Forma de pago","Nombre Forma de pago");
        $nombre_formapago   = form_input(array( 'name'  => 'nombre_formapago','id' => 'nombre_formapago','value' => '','maxlength' => '100','class' => 'cajaMedia'));
        $data['titulo']     = "REGISTRAR FORMA DE PAGO";
        $data['form_open']  = form_open(base_url().'index.php/maestros/formapago/grabar',array("name"=>"frmFormapago","id"=>"frmFormapago"));
        $data['form_close'] = form_close();
        $data['campos']     = array($lblDescripcion);
        $data['valores']    = array($nombre_formapago);
        $data['oculto']     = form_hidden(array('codigo'=>"",'base_url'=>base_url(),'formapago_id'=>''));
        $data['onload']	    = "onload=\"$('#nombres').focus();\"";
        $this->layout->view('maestros/formapago_nuevo',$data);
    }
    public function editar($id)
    {
        $this->load->library('layout', 'layout');
        $oFormapago             = $this->formapago_model->obtener($id);
        $lblDescripcion         = form_label("Nombre Forma pago","Nombre Forma pago");
        $nombre_formapago       = form_input(array( 'name'  => 'nombre_formapago','id' => 'nombre_formapago','value' => $oFormapago[0]->FORPAC_Descripcion,'maxlength' => '100','class' => 'cajaMedia'));
        $data['form_open']      = form_open(base_url().'index.php/maestros/formapago/grabar/',array("name"=>"frmFormapago","id"=>"frmFormapago"));
        $data['campos']         = array($lblDescripcion);
        $data['valores']        = array($nombre_formapago);
        $data['oculto']         = form_hidden(array('codigo'=>"",'base_url'=>base_url(),'formapago_id'=>$id));
        $data['form_hidden']    = form_hidden("base_url",base_url());
        $data['form_close']     = form_close();
        $data['titulo']  = "EDITAR FORMA DE PAGO";
        $this->layout->view('maestros/formapago_nuevo',$data);
    }
    public function grabar()
    {
        $this->form_validation->set_rules('nombre_formapago','Nombre de forma de pago','required');
        if($this->form_validation->run() == FALSE){
            $this->nuevo();
        }
        else{
            $descripcion  = $this->input->post("nombre_formapago");
            $formapago_id   = $this->input->post("formapago_id");
            $filter = new stdClass();
            $filter->FORPAC_Descripcion = strtoupper($descripcion);
            if(isset($formapago_id) && $formapago_id>0){
              $this->formapago_model->modificar($formapago_id,$filter);
            }
            else{
               $this->formapago_model->insertar($filter);
            }
            header("location:".base_url()."index.php/maestros/formapago/listar");
        }
    }
    public function eliminar()
    {
        $id = $this->input->post('formapago');
        $this->formapago_model->eliminar($id);
    }
    public function ver($codigo)
    {
        $this->load->library('layout', 'layout');
        $datos_formapago       = $this->formapago_model->obtener($codigo);
        $data['nombre_formapago']= $datos_formapago[0]->FORPAC_Descripcion;
        $data['formapago']= $datos_formapago[0]->FORPAP_Codigo;
        $data['titulo']        = "VER FORMA DE PAGO";
        $data['oculto']        = form_hidden(array('base_url'=>base_url()));
        $this->layout->view('maestros/formapago_ver',$data);
    }
    public function buscar($j=0)
    {
        $this->load->library('layout','layout');
        $nombre_formapago = $this->input->post('nombre_formapago');
        $filter = new stdClass();
        $filter->FORPAC_Descripcion = $nombre_formapago;
        $data['registros']      = count($this->formapago_model->buscar($filter));
        $conf['base_url']       = site_url('maestros/almacen/buscar/');
        $conf['total_rows']     = $data['registros'];
        $conf['per_page']       = 10;
        $conf['num_links']      = 3;
        $conf['first_link']     = "&lt;&lt;";
        $conf['last_link']      = "&gt;&gt;";
        $offset                 = (int)$this->uri->segment(4);
        $listado                = $this->formapago_model->buscar($filter,$conf['per_page'],$offset);
        $item                   = $j+1;
        $lista                  = array();
        if(count($listado)>0){
            foreach($listado as $indice=>$valor){
                $codigo       = $valor->FORPAP_Codigo;
                $editar       = "<a href='#' onclick='editar_formapago(".$codigo.")' target='_parent'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                $ver          = "<a href='#' onclick='ver_formapago(".$codigo.")' target='_parent'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                $eliminar     = "<a href='#' onclick='eliminar_formapago(".$codigo.")' target='_parent'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
                $lista[]      = array($item++,$valor->FORPAC_Descripcion,$editar,$ver,$eliminar);
            }
        }
        $data['titulo_tabla']    = "RESULTADO DE BUSQUEDA de FORMAS DE PAGO";
        $data['titulo_busqueda'] = "BUSCAR FORMA DE PAGO";
        $data['nombre_formapago']  = form_input(array( 'name'  => 'nombre_formapago','id' => 'nombre_formapago','value' => $nombre_formapago,'maxlength' => '100','class' => 'cajaMedia'));
        $data['form_open']       = form_open(base_url().'index.php/maestros/formapago/buscar',array("name"=>"form_busquedaFormapago","id"=>"form_busquedaFormapago"));
        $data['form_close']      = form_close();
        $data['lista']           = $lista;
        $data['oculto']          = form_hidden(array('base_url'=>base_url()));
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $this->layout->view('maestros/formapago_index',$data);
    }
}
?>