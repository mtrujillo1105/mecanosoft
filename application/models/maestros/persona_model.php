<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Persona_model extends CI_Model
{
    var $compania;
    var $table;  
    public function __construct(){
        parent::__construct();
        $this->compania = $this->session->userdata('compania');
        $this->table   = "persona";
    }
    
    public function seleccionar($default='',$filter='',$filter_not='',$number_items='',$offset=''){
        if($default!="") $arreglo = array($default=>':: Seleccione ::');
        foreach($this->listar($filter,$filter_not,$number_items,$offset) as $indice=>$valor)
        {
            $indice1   = $valor->PERSP_Codigo;
            $valor1    = $valor->PERSC_ApellidoPaterno." ".$valor->PERSC_ApellidoMaterno." ".$valor->PERSC_Nombre;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function listar($filter,$filter_not='',$number_items='',$offset=''){
        $where = array("p.COMPP_Codigo"=>$this->compania);
        if(isset($filter->persona) && $filter->persona!='')    $where = array_merge($where,array("p.PERSP_Codigo"=>$filter->persona));
        $this->db->select('*');
        $this->db->from($this->table." as p",$number_items,$offset);
        $this->db->where($where);    
        if(isset($filter_not->persona) && $filter_not->persona!=''){
            if(is_array($filter_not->persona) && count($filter_not->persona)>0){
                $this->db->where_not_in('p.PERSP_Codigo',$filter_not->persona);
            }
            else{
                $this->db->where('p.PERSP_Codigo !=',$filter_not->persona);
            }            
        }            
        if(isset($filter->order_by) && count($filter->order_by)>0){
            foreach($filter->order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }           
        $this->db->limit($number_items, $offset);         
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows > 0){
            $resultado = $query->result();
        }
        return $resultado; 
    }

    public function obtener($filter,$filter_not='',$number_items='',$offset=''){
        $listado = $this->listar($filter,$filter_not='',$number_items='',$offset='');
        if(count($listado)>1)
            $resultado = "Existe mas de un resultado";
        else
            $resultado = (object)$listado[0];
        return $resultado;
    }

    public function insertar($data){
       $data['COMPP_Codigo'] = $this->compania; 
       $this->db->insert($this->table,$data);
       return $this->db->insert_id();
    }

    public function modificar($codigo,$data){
        $this->db->where("PERSP_Codigo",$codigo);
        $this->db->update($this->table,$data);
    }

    public function eliminar($codigo){
        $this->db->delete($this->table,array('PERSP_Codigo' => $codigo));        
    }

    public function buscar_personas($filter,$number_items='',$offset='')
    {
        if(isset($filter->PERSC_NumeroDocIdentidad) && $filter->PERSC_NumeroDocIdentidad!="")
            $this->db->where('PERSC_NumeroDocIdentidad',$filter->PERSC_NumeroDocIdentidad);
        if(isset($filter->nombre) && $filter->nombre!=""){
            $this->db->like('PERSC_Nombre',$filter->nombre);
            $this->db->or_like('PERSC_ApellidoPaterno',$filter->nombre);
            $this->db->or_like('PERSC_ApellidoMaterno',$filter->nombre);
       }
        if(isset($filter->PERSC_Telefono) && $filter->PERSC_Telefono!="")
            $this->db->like('PERSC_Telefono',$filter->PERSC_Telefono)->or_like('PERSC_Movil',$filter->PERSC_Telefono);

        $query = $this->db->order_by('PERSC_Nombre')
                          ->where('PERSC_FlagEstado','1')
                          ->get('cji_persona',$number_items='',$offset='');
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                    $data[] = $fila;
            }
            return $data;
        }
    }
    /*varios*/
    public function  valida_ruc($ruc, $id=''){
        
        if($id!='')
            $query = $this->db->where('PERSC_Ruc',$ruc)->not_like('PERSP_Codigo',$id)->get('cji_persona');
        else
            $query = $this->db->where('PERSC_Ruc',$ruc)->get('cji_persona');
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                $data[] = $fila;
            }
            return $data;
        }
    }
    public function  busca_xnumeroDoc($tipo_docummento, $numero_documento){
        $query = $this->db->where('PERSC_NumeroDocIdentidad',$numero_documento)->where('PERSC_TipoDocIdentidad',$tipo_docummento)->get('cji_persona');
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                $data[] = $fila;
            }
            return $data;
        }
    }
}
?>