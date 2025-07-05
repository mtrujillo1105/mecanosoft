<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Personalactivo_model extends CI_Model {
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire = $this->load->database('scire',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "Personal_Activo";
    }
    
    public function seleccionar($filter,$default="",$value=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->get($filter) as $indice=>$valor)
        {
            $indice1   = $valor->Periodo_Id;
            $valor1    = $valor->Descripcion;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }    
    
    public function listar($filter,$filter_not="",$number_items='',$offset=''){ 
        $where = array();     
        if(isset($filter->ccosto) && $filter->ccosto!='')               $where = array_merge($where,array("c.ccosto_id"=>$filter->ccosto)); 
        if(isset($filter->planilla) && $filter->planilla!='')           $where = array_merge($where,array("pa.Planilla_id"=>$filter->planilla));        
        if(isset($filter->periodo) && $filter->periodo!='')             $where = array_merge($where,array("pa.Periodo_Id"=>$filter->periodo));  
        if(isset($filter->ccosto_conta) && $filter->ccosto_conta != '') $where = array_merge($where, array("c.Codigo_Auxiliar2" => $filter->ccosto_conta));
        $this->scire->select('p.Personal_Id,
                            p.Nro_Doc,
                            p.Apellido_Paterno,
                            p.Apellido_Materno,
                            p.Nombres,
                            pa.Planilla_Id,
                            c.ccosto_id,
                            c.Descripcion as Area,
                            f.Afp_Id,
                            f.Descripcion as Afp,
                            c.Codigo_Auxiliar2 as Ccosto');
        $this->scire->from($this->table.' as pa');
        $this->scire->join('personal as p','p.Personal_Id=pa.personal_id','inner');
        $this->scire->join('ccosto as c','c.ccosto_id=pa.Ccosto_Id','inner');
        $this->scire->join('afp as f','f.Afp_Id=pa.Afp_Id','inner');
        if(isset($filter->order_by) && count($filter->order_by)>0 && $filter->order_by!=""){
            foreach($filter->order_by as $indice=>$value){
                $this->scire->order_by($indice,$value);
            }
        }          
        if(is_array($where) && isset($where))  $this->scire->where($where);     
        $query = $this->scire->get();
        $resultado = array();
        if($query->num_rows>0)   $resultado = $query->result();
        return $resultado;        
    }
}
?>
