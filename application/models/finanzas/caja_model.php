<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Caja_model extends CI_Model{
    var $entidad;
    var $table;

    public function __construct(){
        parent::__construct();
        $this->entidad   = $this->session->userdata('entidad');
        $this->table     = "Caja";
        $this->table_det = "Caja_det";
    }

    public function seleccionar($tipOt,$default=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->listar($tipOt) as $indice=>$valor)
        {
            $indice1   = $valor->CodOt;
            $valor1    = $valor->NroOt;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }

    public function listar(){

    }
    
    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $where = array("det.codent"=>$this->entidad);
        $this->db->select("det.NroCaja,det.NroOperacion,det.Codcli,convert(char,det.FecEmision,103) as fecha2,det.DesOperacion,det.Mo,det.Subtotal,det.igv,det.ImpOperacion,det.Tc,det.TipDocRef,det.SerieDocRef,det.NroDocRef,det.CodGas,det.Motivo,ot.dirOt,ot.CodOt,ot.NroOt,det.tPer");
        $this->db->from('caja_det as det');
        $this->db->join('ot as ot','ot.codent=det.codent and ot.codot=det.codot','inner');
        $this->db->where($where);
        if(isset($filter->fechai) && $filter->fechai!='')                 $this->db->where('det.FecEmision>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='')                 $this->db->where('det.FecEmision<=',$filter->fechaf);
        if(isset($filter->numero) && $filter->numero!='')                 $this->db->where('det.NroCaja',$filter->numero);
        if(isset($filter->codot) && $filter->codot!=''){
            if(is_array($filter->codot) && count($filter->codot)>0){
                $this->db->where_in('det.codot',$filter->codot);  
            }
            else{
                $this->db->where('det.codot',$filter->codot);                
            }
        }
        /*Order by*/
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
    
    public function listar_totales($filter,$filter_not){
        $arrWhere  = array('det.codent'=>$this->entidad);
        if(isset($filter->group_by) && $filter->group_by!='') $campos = implode(",",$filter->group_by);
        if(isset($filter->fechai) && $filter->fechai!='')     $arrWhere = array_merge($arrWhere,array("det.FecEmision>="=>$filter->fechai));
        if(isset($filter->fechaf) && $filter->fechaf!='')     $arrWhere = array_merge($arrWhere,array("det.FecEmision<="=>$filter->fechaf));
        $sql  = "".$campos.",";
        $sql .= "sum(case det.MO WHEN 2 THEN det.ImpOperacion ELSE det.ImpOperacion*det.Tc END) as impSoles,sum(case det.MO WHEN 2 THEN det.SubTotal ELSE det.SubTotal*det.Tc END) as subSoles,sum(case det.MO WHEN 3 THEN det.ImpOperacion ELSE det.ImpOperacion/det.Tc END) as impDolar,sum(case det.MO WHEN 3 THEN det.SubTotal ELSE det.SubTotal/det.Tc END) as subDolar";
        $this->db->select($sql);
        $this->db->from("caja_det as det");
        $this->db->where($arrWhere);
        $this->db->group_by($campos);
        if(isset($filter->codot) && $filter->codot!='')   $this->db->having("det.codot",$filter->codot);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function obtener($filter,$filter_not){
        $where = array("CodEnt"=>$this->entidad);
        if(isset($filter->numero) && $filter->numero!='')  $where = array_merge($where,array("NroCaja"=>$filter->numero));
        $query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }      
}
?>