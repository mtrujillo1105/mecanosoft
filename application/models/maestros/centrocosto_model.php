<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Centrocosto_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct()
    {
        parent::__construct();
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "ot";
    }
    
    public function seleccionar($default="",$value=''){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar_principales() as $indice=>$valor)
        {
            $indice1   = $valor->codot2;
            $valor1    = $valor->NroOt." - ".$valor->DirOt;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function listar($filter,$filter_not,$order_by='',$like='',$number_items='',$offset=''){
        $arrWhere  = array('ot.CodEnt'=>$this->entidad,"ot.TipOt"=>'04');
        if(isset($filter->estado) && $filter->estado!='000')   $arrWhere = array_merge($arrWhere,array("ot.Estado"=>$filter->estado));
        if(isset($filter->codot) && $filter->codot!='0000000') $arrWhere = array_merge($arrWhere,array("ot.Codot"=>$filter->codot));
        $this->db->select('*,ot.codot as codot2');                                                                                                                                                     
        $this->db->from($this->table,$number_items,$offset);
        $this->db->join('responsable','responsable.codent=ot.codent and responsable.codres=ot.codres');
        $this->db->where($arrWhere);
        if(is_array($like) && count($like)>0){
            foreach($like as $indice=>$value){
                foreach($value as $indice2 => $value2){
                    $this->db->like(trim($indice),trim($indice2),trim($value2));   
                }
            }
        }                  
        if(is_array($order_by) && count($order_by)>0){
            foreach($order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }    
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }	

    public function listar_principales($order_by='',$number_items='',$offset=''){
        $arrWhere  = array('ot.CodEnt'=>$this->entidad,"ot.TipOt"=>'04',"ot.Estado"=>'P');
        $this->db->select('*,ot.CodOt as codot2');                                                                                                                                                     
        $this->db->from($this->table,$number_items,$offset);
        $this->db->join('responsable','responsable.codent=ot.codent and responsable.codres=ot.codres','left');
        $this->db->where($arrWhere);
        $this->db->like('ot.NroOt', '00-00', 'before'); 
        $this->db->order_by('ot.NroOt','asc');
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }    
    
    public function obtener($ccosto){
            $query = $this->db->where('CENCOSP_Codigo',$ccosto)->get('cji_centrocosto');
            if($query->num_rows>0){
                    foreach($query->result() as $fila){
                            $data[] = $fila;
                    }
                    return $data;
            }
    }
    
    public function insertar($descripcion){
         $compania = $this->somevar['compania'];
		$data = array(
					"CENCOSC_Descripcion" => $descripcion,
                    "COMPP_Codigo"              => $compania
					);
		$this->db->insert("cji_centrocosto",$data);
    }
    
    public function modificar($ccosto,$descripcion){
         $data     = array("CENCOSC_Descripcion"=>$descripcion);
         $where = array("CENCOSP_Codigo"=>$ccosto);
		$this->db->where($where);
		$this->db->update('cji_centrocosto',$data);
    }
    
    public function eliminar($ccosto){
		$data      = array("CENCOSC_FlagEstado"=>'0');
		$where = array("CENCOSP_Codigo"=>$ccosto);
		$this->db->where($where);
		$this->db->update('cji_centrocosto',$data);
    }	
}
?>
