<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partida_model extends CI_Model{
    var $table;
    var $entidad;
    public function __construct(){
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "view_partidas";
    }
    
    public function seleccionar($filter,$default="",$value=''){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listar($filter) as $indice=>$valor)
        {
            $indice1   = $valor->cod_argumento;
            $valor1    = $valor->des_larga;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }  
    
    public function listar(){
        
    }
    
    public function listar_partidatipoproducto($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('pt.codent'=>$this->entidad);
        if(isset($filter->codtipoproducto) && $filter->codtipoproducto!='')   $arrWhere = array_merge($arrWhere,array("pt.codTipo"=>$filter->codtipoproducto));
        $this->db->select("pt.CodPartida,p.Des_Larga");                                                                                                                                                     
        $this->db->from('partidatipo as pt');
        $this->db->join('view_partidas as p','p.codent=pt.codent and p.Cod_Argumento=pt.CodPartida','inner');
        $this->db->where($arrWhere); 
        if(isset($order_by) && count($order_by)>0 && is_array($order_by)){
            foreach($order_by as $indice=>$value){
                $this->db->order_by($indice,$value);
            }
        }          
        $query = $this->db->get();
        $resultado = new stdClass();    
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('sp.codent'=>$this->entidad);
        if(isset($filter->codpartida) && $filter->codpartida!='')   $arrWhere = array_merge($arrWhere,array("sp.Valor_3"=>$filter->codpartida));
        $this->db->select("sp.cod_tabla,sp.cod_argumento,sp.valor_2,sp.des_larga,sp.Des_Corta");                                                                                                                                                     
        $this->db->from('view_subpartidas as sp');
        $this->db->where($arrWhere); 
        $query = $this->db->get();
        $resultado = new stdClass();    
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function obtener($filter,$filter_not){
        $arrWhere  = array('codent'=>$this->entidad);
        if(isset($filter->codpartida) && $filter->codpartida!='')  $arrWhere = array_merge($arrWhere,array("cod_argumento"=>$filter->codpartida));
        $this->db->select('cod_tabla,cod_argumento,valor_2,des_larga,Des_Corta');
        $query = $this->db->where($arrWhere)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit("Existe mas de 1 resultado ".$this->table."");
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        //echo $this->db->last_query();
        return $resultado;
    }
}
?>
