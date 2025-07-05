<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Vale_salida_model extends CI_Model{
    var $entidad;
    var $table;

    var $vari='201126097';
    
    public function __construct(){
        parent::__construct();
        $this->entidad   = $this->session->userdata('entidad');
        $this->table     = "Pagos";
        $this->table_det = "Pagos_det";
        $this->table     = "Kardex";
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
    
    public function obtener(){
        $arrWhere  = array('p.numero'=>$this->vari);
        $this->db->select("*");
        $this->db->from("Kardex as p");
        $this->db->where($arrWhere);   
       /* if(isset($filter->numero) && $filter->numero!='')   $this->db->where('p.NroVoucher',$filter->numero);
        if(isset($filter->fecha) && $filter->fecha!='')     $this->db->where('p.FecPago',$filter->fecha);*/
        $query = $this->db->get();
        $resultado = array();
     /*   if($query->num_rows>1) exit('Existe mas de 55 resultado'); */   
        if($query->num_rows>0){
            $resultado = $query->row();
        }
        return $resultado;
    }  
    
    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $arrWhere  = array('p.codent'=>$this->entidad);
        $this->db->select("*");
        $this->db->from("Pagos as p");
        $this->db->where($arrWhere);   
        if(isset($filter->numero) && $filter->numero!='')   $this->db->where('p.NroVoucher',$filter->numero);
        if(isset($filter->fecha) && $filter->fecha!='')     $this->db->where('p.FecPago',$filter->fecha);
        if(isset($filter->fechai) && $filter->fechai!='')   $this->db->where('p.FecPago>=',$filter->fechai);
        if(isset($filter->fechaf) && $filter->fechaf!='')   $this->db->where('p.FecPago<=',$filter->fechaf);
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }

    public function listar_detalle($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $where = array("pdet.codent"=>$this->entidad,"p.Estado!="=>"A");
        //$this->db->select("pdet.ImpPdet,pdet.Igv,p.MO,p.Tc,p.NroVoucher,pdet.codot,(rdet.PrecUnit*rdet.CantAten) as pventa,pdet.DesPago,pdet.TipPago,pdet.CodCtrl,pdet.TipoDocRef,pdet.SerieDocRef,pdet.NroDocRef,p.NroCheque,p.TipSolPago,p.CodSolicita,convert(varchar,p.FecPago,103) as fecha2,convert(varchar,p.FecEmi,103) as fecemi");
        $this->db->select("pdet.ImpPdet,pdet.Igv,p.MO,p.Tc,p.NroVoucher,pdet.codot,pdet.DesPago,pdet.TipPago,pdet.CodCtrl,pdet.TipoDocRef,pdet.SerieDocRef,pdet.NroDocRef,p.NroCheque,p.TipSolPago,p.CodSolicita,convert(varchar,p.FecPago,103) as fecha2,convert(varchar,p.FecEmi,103) as fecemi");
        $this->db->from($this->table_det.' as pdet');
        $this->db->join($this->table.' as p','p.codent=pdet.codent and p.NroVoucher=pdet.NroVoucher','inner');
        //$this->db->join("".utf8_decode('Reposición_det')." as rdet",'rdet.codent=pdet.codent and rdet.CodOt=pdet.CodOt and rdet.TipDoc=pdet.TipoDocRef and rdet.SerieDoc=pdet.SerieDocRef and rdet.NroDoc=pdet.NroDocRef','left');
        if(isset($filter->codpartida) && $filter->codpartida!=''){
            $this->db->join('CtrlObras_Detalle as ctrl_det','ctrl_det.item=pdet.iteCtrl and ctrl_det.CodObras=pdet.CodCtrl and ctrl_det.NroDoc=pdet.Nea','inner');
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
        if(isset($filter->codpartida) && $filter->codpartida!='')         $this->db->where('ctrl_det.TipDoc',$filter->codpartida);
        if(isset($order_by) && is_array($order_by) && count($order_by)>0) $this->db->order_by($order_by);
        $query = $this->db->get();
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }      
    
    public function listar_totales($filter,$filter_not){
        $arrWhere  = array('det.codent'=>$this->entidad);
        $this->db->select("det.codot,sum(case p.MO when 2 then (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End) else (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End)*p.Tc end) as ImpSoles,sum(case p.MO when 3 then (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End) else (Case det.Igv When 'C' Then (det.ImpPdet/1.18) Else det.ImpPdet End)/p.Tc end) as ImpDolares");
        $this->db->from("Pagos_det as det");
        $this->db->join('Pagos as p','p.codent=det.codent and p.NroVoucher=det.NroVoucher','inner');
        $this->db->join('CtrlObras_Detalle as ctrl_det','ctrl_det.item=det.iteCtrl and ctrl_det.CodObras=det.CodCtrl and ctrl_det.NroDoc=det.Nea','left');
        $this->db->where($arrWhere);
        if(isset($filter->codpartida)){
            if(is_array($filter->codpartida)){
                if(count($filter->codpartida)>0) $this->db->where_in('ctrl_det.TipDoc',$filter->codpartida);  
             } 
             else{
                if($filter->codpartida!='') $this->db->where('ctrl_det.TipDoc',$filter->codpartida);
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
         
}
?>
