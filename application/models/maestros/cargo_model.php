<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cargo_model extends CI_Model{
    var $entidad;
    var $table;

    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "Pagos";
    }
    
    public function listar($number_items='',$offset='')
    {
     $compania = $this->somevar['compania'];
     $where = array("COMPP_Codigo"=>$compania,"CARGC_FlagEstado"=>"1");
            $query = $this->db->order_by('CARGC_Descripcion')->where_not_in('CARGP_Codigo','0')->where($where)->get('cji_cargo',$number_items,$offset);
            if($query->num_rows>0){
                    foreach($query->result() as $fila){
                            $data[] = $fila;
                    }
                    return $data;
            }
    }
    
    public function obtener($cargo)
    {
            $query = $this->db->where('CARGP_Codigo',$cargo)->get('cji_cargo');
            if($query->num_rows>0){
                    foreach($query->result() as $fila){
                            $data[] = $fila;
                    }
                    return $data;
            }
    }
    
    public function insertar($descripcion)
    {
     $compania = $this->somevar['compania'];
            $nombre = strtoupper($descripcion);
            $data = array(
                            "CARGC_Descripcion"=>$nombre,
                            "COMPP_Codigo"=>$compania
                            );
            $this->db->insert("cji_cargo",$data);
    }
    
    public function modificar($cargo,$nombre)
    {
            $nombre = strtoupper($nombre);
            $data  = array("CARGC_Descripcion"=>$nombre);
            $this->db->where("CARGP_Codigo",$cargo);
            $this->db->update('cji_cargo',$data);
    }
    
    public function eliminar($cargo)
    {
            $where = array("CARGP_Codigo"=>$cargo);
            $this->db->delete('cji_cargo',$where);
    }
    
    public function buscar($filter,$number_items='',$offset='')
    {
        $this->db->where('COMPP_Codigo',$this->somevar['compania']);
        if(isset($filter->nombre_cargo) && $filter->nombre_cargo!='')
            $this->db->like('CARGC_Descripcion',$filter->nombre_cargo,'both');
        $this->db->where_not_in('CARGP_Codigo','0');
        $query = $this->db->get('cji_cargo',$number_items,$offset);

        if($query->num_rows>0){
            foreach($query->result() as $fila){
                    $data[] = $fila;
            }
            return $data;
        }
    }
}
?>