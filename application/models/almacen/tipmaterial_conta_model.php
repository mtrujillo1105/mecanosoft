<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tipmaterial_conta_model extends CI_Model{
    var $entidad;
    var $table;

    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "view_tipMaterial_conta";
         $this->table_m   = "movimiento";
    }
    
    public function seleccionar($filter,$default="",$value='')
    {
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar($filter,new stdClass()) as $indice=>$valor)
        {
            $indice1   = $valor->Cod_Argumento;
            $valor1    = $valor->Cod_Argumento.'-'.$valor->Des_Larga;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    
    public function seleccionar_movi($filter,$default="",$value='')
    {
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar_movi($filter,new stdClass()) as $indice=>$valor)
        {
            $indice1   = $valor->codmov;
            $valor1    = $valor->codmov.'-'.$valor->tipmov;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
    
    public function obtener($id)
    {
        $where = array("ALMAP_Codigo"=>$id);
        $query = $this->db->order_by('ALMAC_Descripcion')->where($where)->get('cji_almacen',1);
        if($query->num_rows>0){
          return $query->result();
        }
    }
    
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset='')
    {
            if(isset($filter->codot) && $filter->codot!=''){
                if(is_array($filter->codot) && count($filter->codot)>0){
                    $interv = 20;
                    for($i=0;$i<ceil(count($filter->codot)/$interv);$i++){
                        $arrcodot = array_slice($filter->codot,$i*$interv,$interv);
                        $filtroot .= ($i==0?"and (":"or")." k.codot in ('".str_replace(",","','",implode(',',$arrcodot))."')";
                    }
                    $filtroot .= ")";
                }
                else{
                    $filtroot  = "and k.codot='".$filter->codot."'";    
                }
            }        
            $where = array("codent"=>$this->entidad);
            $this->db->select('*');
            $this->db->from($this->table,$number_items,$offset);
            $this->db->where($where);
            if(isset($filter->cod_argumento) && $filter->cod_argumento!=''){
                if(is_array($filter->cod_argumento) && count($filter->cod_argumento)>0){
                    $this->db->where_in('Cod_Argumento',$filter->cod_argumento);  
                }
                else{
                    $this->db->where('Cod_Argumento',$filter->cod_argumento);                
                }
            }            
            $this->db->where_not_in('Cod_Argumento','01');
            $query = $this->db->get();
            if($query->num_rows>0){
                $resultado = $query->result();
            }            
            return $resultado;
    }
    
    
    
    public function listar_movi($filter,$filter_not,$order_by='',$number_items='',$offset='')
    {
                   
            $where = array("estado"=>1);
            $this->db->select('*');
            $this->db->from($this->table_m,$number_items,$offset);
            $this->db->where($where);
            if(isset($filter->codmov) && $filter->codmov!=''){
                if(is_array($filter->codmov) && count($filter->codmov)>0){
                    $this->db->where_in('codmov',$filter->codmov);  
                }
                else{
                    $this->db->where('codmov',$filter->codmov);                
                }
            }            
           
            $query = $this->db->get();
            if($query->num_rows>0){
                $resultado = $query->result();
            }            
            return $resultado;
    }

    public function insertar(stdClass $filter = null)
    {
        $this->db->insert("cji_almacen",(array)$filter);
    }
    public function modificar($id,$filter)
    {
        $this->db->where("ALMAP_Codigo",$id);
        $this->db->update("cji_almacen",(array)$filter);
    }
    public function eliminar($id)
    {
        $this->db->delete('cji_almacen',array('ALMAP_Codigo' => $id));
    }
    public function buscar($filter,$number_items='',$offset='')
    {
        $this->db->select('*');
        $this->db->from('cji_almacen',$number_items='',$offset='');
        $this->db->join('cji_tipoalmacen','cji_tipoalmacen.TIPALMP_Codigo=cji_almacen.TIPALM_Codigo');
        $this->db->where('cji_almacen.COMPP_Codigo',$this->somevar['compania']);
        if(isset($filter->ALMAC_Descripcion) && $filter->ALMAC_Descripcion!="")
            $this->db->like('cji_almacen.ALMAC_Descripcion',$filter->ALMAC_Descripcion);
        if(isset($filter->TIPALM_Codigo) && $filter->TIPALM_Codigo!="")
            $this->db->like('cji_almacen.TIPALM_Codigo',$filter->TIPALM_Codigo);
        $query = $this->db->get();
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                    $data[] = $fila;
            }
            return $data;
        }
    }
}
?>