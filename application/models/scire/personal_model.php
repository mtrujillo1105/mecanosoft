<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personal_model extends CI_Model {
    
    public $entidad;
    public $table_personal = 'Personal';
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
    }
    
    public function getObreros() {
        // 20 => OBRERO
        // 21 => EMPLEADO
        $where = array(
                    "Tipo_Trabajador_id" => 20,
                    "Categoria2_id" => $this->entidad
                 );
        $this->scire->select('*');
        $this->scire->from($this->table_personal);
        $this->scire->where($where);
        $query = $this->scire->get();
        if($query->num_rows > 0) {
            return $query->result();
        }
    }
    
    public function getPersonalById($id) {
        $this->scire->select("*");
        $this->scire->from($this->table_personal.' as p');
        $where = array("p.Personal_Id" => $id);
        $this->scire->where($where);
        $query = $this->scire->get();
        $resultado = $query->row();
        return $resultado;
    }
    
    public function get($filter,$filter_not="",$number_items='',$offset=''){
        $where = array("p.Categoria2_id" => $this->entidad);
        if(isset($filter->entidad)){
            if($filter->entidad == "")
                $where = array();
            else
                $where = array_merge($where,array("p.Categoria2_id"=>$filter->entidad));
        }
        if(isset($filter->tipo_trabajador) && $filter->tipo_trabajador != '') $where = array_merge($where, array("p.Tipo_Trabajador_Id" => $filter->tipo_trabajador));
        if(isset($filter->personal_id) && $filter->personal_id != '')         $where = array_merge($where, array("p.Personal_Id" => trim($filter->personal_id)));        
        if(isset($filter->ccosto) && $filter->ccosto != '')                   $where = array_merge($where, array("p.Ccosto_Id" => $filter->ccosto));
        if(isset($filter->estado) && $filter->estado != '')                   $where = array_merge($where, array("p.Estado_Id" => $filter->estado));
        $this->scire->select("p.Personal_Id,p.Apellido_Paterno + ' ' + p.Apellido_Materno + ' ' + p.Nombres as Nombres,c.Descripcion as Ccosto,p.Nro_Doc,p.Tipo_Trabajador_Id,p.Cargo_Id,p.Ccosto_Id,p.Cargo_Id");
        $this->scire->from($this->table_personal.' as p');
        $this->scire->join('Ccosto as c','c.ccosto_id = p.Ccosto_Id','left');
        $this->scire->where($where);
        if(isset($filter->order_by) && count($filter->order_by)>0 && $filter->order_by!=""){
            foreach($filter->order_by as $indice=>$value){
                $this->scire->order_by($indice,$value);
            }
        }          
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>1)   $resultado = $query->result();
        if($query->num_rows==1)  $resultado = $query->row();
        return $resultado;
    }
}

?>
