<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ctrlobras_model extends CI_Model{
    var $entidad;
    var $table;
   
    public function __construct(){
        parent::__construct();
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "CtrlObras";
    }
    
    public function obtener($filter,$filter_not){ 
        $arrWhere  = array('ctrl.codent'=>$this->entidad);
        if(isset($filter->codpartida) && $filter->codpartida!='') $arrWhere = array_merge($arrWhere,array("ctrl.TipDoc"=>$filter->codpartida));
        if(isset($filter->estado) && $filter->estado!='')         $arrWhere = array_merge($arrWhere,array("ctrl.EstDoc"=>$filter->estado));
        $this->db->select("ctrl.TipDoc,ctrl.NroDoc,convert(char,ctrl.fecini,102) as fecha2,ctrl.MtoDoc,ctrl.Mtomod,ctrl.Mo,ctrl.TipSol,ctrl.Obs,dbo.getTipoCambioV(ctrl.fecini,'".$this->entidad."') as tcambio,(case ctrl.IGV when 'S' then ctrl.MtoDoc/1.18 else ctrl.MtoDoc end) as MtoDoc2,(case ctrl.IGV when 'S' then ctrl.Mtomod/1.18 else ctrl.Mtomod end) as Mtomod2,ctrl.IGV");                                                                                                                                                     
        $this->db->from('CtrlObras as ctrl');
        $this->db->where($arrWhere); 
        if(isset($filter->codot) && $filter->codot!=''){
            if(is_array($filter->codot) && count($filter->codot)>0){
                $this->db->where_in("ctrl.codot",$filter->codot);
            }
            else{
                $this->db->where("ctrl.codot",$filter->codot);
            }
        }        
        $query = $this->db->get();
        $resultado = new stdClass();
        if($query->num_rows>1){
            echo $this->db->last_query();
            exit("Existe mas de 1 resultado en la tabla ".$this->table."");        
        }
        if($query->num_rows>0){
            $resultado = $query->row();
        }
        return $resultado;
    }
    
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('ctrl.codent'=>$this->entidad);
        if(isset($filter->codot) && $filter->codot!='')           $arrWhere = array_merge($arrWhere,array("ctrl.codot"=>$filter->codot));
        if(isset($filter->codpartida) && $filter->codpartida!='') $arrWhere = array_merge($arrWhere,array("ctrl.TipDoc"=>$filter->codpartida));
        if(isset($filter->estado) && $filter->estado!='')         $arrWhere = array_merge($arrWhere,array("ctrl.EstDoc"=>$filter->estado));
        $this->db->select("ctrl.TipDoc,ctrl.NroDoc,convert(char,ctrl.fecini,102) as fecha2,ctrl.MtoDoc,ctrl.Mtomod,ctrl.Mo,ctrl.TipSol,ctrl.Obs,dbo.getTipoCambioV(ctrl.fecini,'".$this->entidad."') as tcambio,(case ctrl.IGV when 'S' then ctrl.MtoDoc/1.18 else ctrl.MtoDoc end) as MtoDoc2,(case ctrl.IGV when 'S' then ctrl.Mtomod/1.18 else ctrl.Mtomod end) as Mtomod2,ctrl.IGV");                                                                                                                                                     
        $this->db->from('CtrlObras as ctrl');
        $this->db->where($arrWhere); 
        $query = $this->db->get();
        $resultado = new stdClass();    
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('ctrldet.codent'=>$this->entidad);
        if(isset($filter->codsubpartida) && $filter->codsubpartida!='')  $arrWhere = array_merge($arrWhere,array("ctrldet.CodObras"=>$filter->codsubpartida));
        if(isset($filter->codpartida) && $filter->codpartida!='')        $arrWhere = array_merge($arrWhere,array("ctrldet.Tipdoc"=>$filter->codpartida));
        if(isset($filter->numero) && $filter->numero!='')                $arrWhere = array_merge($arrWhere,array("ctrldet.NroDoc"=>$filter->numero));
        if(isset($filter->estado) && $filter->estado!='')                $arrWhere = array_merge($arrWhere,array("ctrldet.Estado"=>$filter->estado));
        if(isset($filter->codot) && $filter->codot!='')                  $arrWhere = array_merge($arrWhere,array("ctrl.codot"=>$filter->codot));
        $this->db->select("(case ctrl.IGV when 'S' then ctrldet.Cargo/1.18 else ctrldet.Cargo end) as Cargo2,(case ctrl.IGV when 'S' then ctrldet.Abono/1.18 else ctrldet.Abono end) as Abono2,(case ctrl.IGV when 'S' then ctrldet.Saldo/1.18 else ctrldet.Saldo end) as Saldo2,ctrldet.Obs,ctrldet.Impto,ctrldet.Subtotal,ctrl.IGV,ctrldet.Cargo as Cargo,ctrldet.Abono as Abono,ctrldet.Saldo as Saldo");                                                                                                                                                     
        $this->db->from('CtrlObras_Detalle as ctrldet');
        $this->db->join('CtrlObras as ctrl','ctrl.TipDoc=ctrldet.TipDoc and ctrl.NroDoc=ctrldet.NroDoc and ctrl.CodEnt=ctrldet.CodEnt','inner');
        $this->db->where($arrWhere); 
        $query = $this->db->get();
        $resultado = new stdClass();    
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    

}
?>