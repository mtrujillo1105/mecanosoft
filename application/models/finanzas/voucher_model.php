<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Voucher_model extends CI_Model{
    var $compania;
    var $table;
    public function __construct(){
        parent::__construct();
        $this->compania   = $this->session->userdata('compania');
        $this->table     = "pagos";
        $this->table_det = "pagos_det";
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
    
    public function obtener($filter,$filter_not){
        $arrWhere  = array('p.codent'=>$this->entidad);
        $this->db->select("*");
        $this->db->from("Pagos as p");
        $this->db->where($arrWhere);   
        if(isset($filter->numero) && $filter->numero!='')   $this->db->where('p.NroVoucher',$filter->numero);
        if(isset($filter->fecha) && $filter->fecha!='')     $this->db->where('p.FecPago',$filter->fecha);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>1) exit('Existe mas de 1 resultado');    
        if($query->num_rows>0){
            $resultado = $query->row();
        }
        return $resultado;
    }  
    
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('COMPP_Codigo'=>$this->compania);
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->where($arrWhere);   
        if(isset($filter->numero) && $filter->numero!='')   $this->db->where('NroVoucher',$filter->numero);
//        if(isset($filter->fecha) && $filter->fecha!='')     $this->db->where('FecEmi',$filter->fecha);
//        if(isset($filter->fechai) && $filter->fechai!='')   $this->db->where('FecEmi>=',$filter->fechai);
//        if(isset($filter->fechaf) && $filter->fechaf!='')   $this->db->where('FecEmi<=',$filter->fechaf);
        if(isset($order_by) && count($order_by)>0 && is_array($order_by)){
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

    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $where = array("pdet.CodEnt"=>$this->entidad,"p.Estado!="=>"A");
        $this->db->select("pdet.ImpPdet,pdet.Igv,p.MO,p.Tc,p.NroVoucher,pdet.codot,pdet.DesPago,pdet.TipPago,pdet.CodCtrl,pdet.TipoDocRef,pdet.SerieDocRef,pdet.NroDocRef,p.NroCheque,p.TipSolPago,p.CodSolicita,convert(varchar,p.FecPago,103) as fecha2,convert(varchar,p.FecEmi,103) as fecemi,pdet.codpartida,ctrl_det.TipDoc as codobra");
        $this->db->from($this->table_det.' as pdet');
        $this->db->join($this->table.' as p','p.codent=pdet.codent and p.NroVoucher=pdet.NroVoucher','LEFT OUTER');
        //$this->db->join("".utf8_decode('Reposición_det')." as rdet",'rdet.codent=pdet.codent and rdet.CodOt=pdet.CodOt and rdet.TipDoc=pdet.TipoDocRef and rdet.SerieDoc=pdet.SerieDocRef and rdet.NroDoc=pdet.NroDocRef','left');
        if(isset($filter->codpartida) && $filter->codpartida!=''){
            $this->db->join('CtrlObras_Detalle as ctrl_det','ctrl_det.item=pdet.iteCtrl and ctrl_det.CodObras=pdet.CodCtrl and ctrl_det.NroDoc=pdet.Nea','LEFT OUTER');
        }
        $this->db->where($where);
        if(isset($filter->tipdocref) && $filter->tipdocref!='')           $this->db->where('pdet.TipoDocRef',$filter->tipdocref);
        if(isset($filter->seriedocref) && $filter->seriedocref!='')       $this->db->where("replicate ('0',(10 - len(pdet.SerieDocRef)))+pdet.SerieDocRef=",str_pad(trim($filter->seriedocref),10,'0',STR_PAD_LEFT));
        if(isset($filter->nrodocref) && $filter->nrodocref!='')           $this->db->where("replicate ('0',(10 - len(pdet.NroDocRef)))+pdet.NroDocRef=",str_pad(trim($filter->nrodocref),10,'0',STR_PAD_LEFT));
        if(isset($filter->codot) && $filter->codot!=''){
            if(is_array($filter->codot) && count($filter->codot)>0){
                $this->db->where_in('pdet.codot',$filter->codot);
            }
            else{
                $this->db->where('pdet.codot',$filter->codot);
            }            
        }
        if(isset($filter->fecha) && $filter->fecha!='')                   $this->db->where('p.FecPago',$filter->fecha);
        if(isset($filter->fechai) && $filter->fechai!='')                 $this->db->where('p.FecPago>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='')                 $this->db->where('p.FecPago<=',$filter->fechaf);
        if(isset($filter->numero) && $filter->numero!='')                 $this->db->where('p.NroVoucher',$filter->numero);
       // SMI: REVISAR POR QUE SE HACE ESTA CONDICION ???
        if(isset($filter->codpartida) && $filter->codpartida!=''){
            if($filter->codpartida!='13')   $this->db->where('ctrl_det.TipDoc',$filter->codpartida);
        }
        /*Array codpartida_not*/
        if(isset($filter_not->codpartida) && $filter_not->codpartida!=''){
            if(is_array($filter_not->codpartida) && count($filter_not->codpartida)>0){
                $this->db->where_not_in('ctrl_det.TipDoc',$filter_not->codpartida);
            }
            else{
                $this->db->where('ctrl_det.TipDoc!=',$filter_not->codpartida);
            }            
        }        
        if(isset($order_by) && is_array($order_by) && count($order_by)>0) $this->db->order_by($order_by);
        $query = $this->db->get();
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }      
    
   public function listar_detalle2($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $where = array("pdet.CodEnt"=>$this->entidad,"p.Estado!="=>"A");
        $this->db->select("pdet.ImpPdet,pdet.Igv,p.MO,p.Tc,p.NroVoucher,pdet.codot,pdet.DesPago,pdet.TipPago,pdet.CodCtrl,pdet.TipoDocRef,pdet.SerieDocRef,pdet.NroDocRef,p.NroCheque,p.TipSolPago,p.CodSolicita,convert(varchar,p.FecPago,103) as fecha2,convert(varchar,p.FecEmi,103) as fecemi,pdet.codpartida");
        $this->db->from($this->table_det.' as pdet');
        $this->db->join($this->table.' as p','p.codent=pdet.codent and p.NroVoucher=pdet.NroVoucher','LEFT OUTER');
        $this->db->where($where);
        if(isset($filter->tipdocref) && $filter->tipdocref!='')           $this->db->where('pdet.TipoDocRef',$filter->tipdocref);
        if(isset($filter->seriedocref) && $filter->seriedocref!='')       $this->db->where("replicate ('0',(10 - len(pdet.SerieDocRef)))+pdet.SerieDocRef=",str_pad(trim($filter->seriedocref),10,'0',STR_PAD_LEFT));
        if(isset($filter->nrodocref) && $filter->nrodocref!='')           $this->db->where("replicate ('0',(10 - len(pdet.NroDocRef)))+pdet.NroDocRef=",str_pad(trim($filter->nrodocref),10,'0',STR_PAD_LEFT));
        if(isset($filter->codot) && $filter->codot!=''){
            if(is_array($filter->codot) && count($filter->codot)>0){
                $this->db->where_in('pdet.codot',$filter->codot);
            }
            else{
                $this->db->where('pdet.codot',$filter->codot);
            }            
        }
        if(isset($filter->fecha) && $filter->fecha!='')                   $this->db->where('p.FecPago',$filter->fecha);
        if(isset($filter->fechai) && $filter->fechai!='')                 $this->db->where('p.FecPago>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='')                 $this->db->where('p.FecPago<=',$filter->fechaf);
        if(isset($filter->numero) && $filter->numero!='')                 $this->db->where('p.NroVoucher',$filter->numero);
        if(isset($filter->codpartida) && $filter->codpartida!='13')       $this->db->where('pdet.codpartida',$filter->codpartida);
        /*Array where_not codpartida*/
        if(isset($filter_not->codpartida) && $filter_not->codpartida!=''){
            if(is_array($filter_not->codpartida) && count($filter_not->codpartida)>0){
                $this->db->where_not_in('pdet.codpartida',$filter_not->codpartida);
            }
            else{
                $this->db->where('pdet.codpartida!=',$filter_not->codpartida);
            }            
        }
        /*Array where_not tipomov*/
        if(isset($filter_not->codtipomov) && $filter_not->codtipomov!=''){
            if(is_array($filter_not->codtipomov) && count($filter_not->codtipomov)>0){
                $this->db->where_not_in('pdet.TipPago',$filter_not->codtipomov);
            }
            else{
                $this->db->where('pdet.TipPago!=',$filter_not->codtipomov);
            }            
        }        
        /*Array order_by*/
        if(isset($order_by) && is_array($order_by) && count($order_by)>0){
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
    
   public function listar_totales2($filter,$filter_not){
        $arrWhere  = array('det.Codent'=>$this->entidad);
        if(isset($filter->group_by) && count($filter->group_by)>0 && $filter->group_by!="")     $campos = implode($filter->group_by);
        $sql  = $campos.",";
        $sql .= "sum(case p.MO when 2 then (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End) else (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End)*p.Tc end) as ImpSoles,sum(case p.MO when 3 then (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End) else (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End)/p.Tc end) as ImpDolares";
        $this->db->select($sql);
        $this->db->from("Pagos_det as det");
        $this->db->join('Pagos as p','p.codent=det.codent and p.NroVoucher=det.NroVoucher','inner');
        $this->db->where($arrWhere);
        /*Array partida*/
        if(isset($filter->codpartida)){
            if(is_array($filter->codpartida)){
                if(count($filter->codpartida)>0) $this->db->where_in('det.codpartida',$filter->codpartida);  
             } 
             else{
                if($filter->codpartida!='') $this->db->where('det.codpartida',$filter->codpartida);
             }                 
        }  
        /*Número o array de codpartidas que no se incluirán en el resultado*/
        if(isset($filter_not->codpartida) && $filter_not->codpartida!=''){
            if(is_array($filter_not->codpartida) && count($filter_not->codpartida)>0){
                $this->db->where_not_in('det.codpartida',$filter_not->codpartida);
            }
            else{
                $this->db->where('det.codpartida!=',$filter_not->codpartida);
            }            
        } 
        /*Número o array de codtipomov que no se incluirán en el resultado*/
        if(isset($filter_not->codtipomov) && $filter_not->codtipomov!=''){
            if(is_array($filter_not->codtipomov) && count($filter_not->codtipomov)>0){
                $this->db->where_not_in('det.TipPago',$filter_not->codtipomov);
            }
            else{
                $this->db->where('det.TipPago!=',$filter_not->codtipomov);
            }            
        }         
        if(isset($filter->fechai) && $filter->fechai!='')   $this->db->where('p.FecPago>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='')   $this->db->where('p.FecPago<=',$filter->fechaf);
        if(isset($filter->group_by) && count($filter->group_by)>0 && $filter->group_by!="") $this->db->group_by($filter->group_by);
        if(isset($filter->codot) && $filter->codot!='')     $this->db->having('det.codot',$filter->codot);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }    
    
    public function listar_totales($filter,$filter_not){
        $arrWhere  = array('det.Codent'=>$this->entidad);
        $this->db->select("det.codot,sum(case p.MO when 2 then (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End) else (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End)*p.Tc end) as ImpSoles,sum(case p.MO when 3 then (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End) else (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End)/p.Tc end) as ImpDolares");
        $this->db->from("Pagos_det as det");
        $this->db->join('Pagos as p','p.codent=det.codent and p.NroVoucher=det.NroVoucher','inner');
        $this->db->join('CtrlObras_Detalle as ctrl_det','ctrl_det.item=det.iteCtrl and ctrl_det.CodObras=det.CodCtrl and ctrl_det.NroDoc=det.Nea','left');
        $this->db->where($arrWhere);
        /*Array partida*/
        if(isset($filter->codpartida)){
            if(is_array($filter->codpartida)){
                if(count($filter->codpartida)>0) $this->db->where_in('ctrl_det.TipDoc',$filter->codpartida);  
             } 
             else{
                if($filter->codpartida!='') $this->db->where('ctrl_det.TipDoc',$filter->codpartida);
             }                 
        }  
        /*Array_not partida*/
        if(isset($filter_not->codpartida) && $filter_not->codpartida!=''){
            if(is_array($filter_not->codpartida) && count($filter_not->codpartida)>0){
                $this->db->where_not_in('ctrl_det.TipDoc',$filter_not->codpartida);
            }
            else{
                $this->db->where('ctrl_det.TipDoc!=',$filter_not->codpartida);
            }            
        }          
        if(isset($filter->fechai) && $filter->fechai!='')   $this->db->where('p.FecPago>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='')   $this->db->where('p.FecPago<=',$filter->fechaf);
        $this->db->group_by('det.codot');
        if(isset($filter->codot) && $filter->codot!='')     $this->db->having('det.codot',$filter->codot);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
   
    public function  ReqVou($filter5,$filter5_not){
        
       
        $where = array("p.CodEnt"=>$this->entidad);
        $this->db->select("p.NroVoucher, p.TipoDocRef");
        $this->db->from($this->table_det.' as p');
       
         $this->db->where($where);
           $tip='RS';
        if(isset($filter5->requis_voucher) && $filter5->requis_voucher!='')                 $this->db->where('p.NroDocRef',$filter5->requis_voucher);
        if(isset($filter5->requis_voucher) && $filter5->requis_voucher!='')                 $this->db->where('p.TipoDocRef',$tip);
            
            
        if(isset($order_by) && is_array($order_by) && count($order_by)>0) $this->db->order_by($order_by);
        

        
        
        $query = $this->db->get();
   
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->row();
        }
       
        return $resultado;
        
        
        
    }
    
    
    
    
         
}
?>
