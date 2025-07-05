<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Servicios_model extends CI_Model{
    var $entidad;
    var $table;

    var $vari='201126097';
    
    public function __construct(){
        parent::__construct();
         $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad   = $this->session->userdata('entidad');
        $this->table     = "reposición";
        //$this->table_det = "reposición";
        $this->table2     = "ot";
            $this->table3     = "producto";
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
    
//    public function listar($filter,$filter_not,$order_by='',$number_items='',$offset=''){
//        $arrWhere  = array('p.codent'=>$this->entidad);
//        $this->db->select("*");
//        $this->db->from("Pagos as p");
//        $this->db->where($arrWhere);   
//        if(isset($filter->numero) && $filter->numero!='')   $this->db->where('p.NroVoucher',$filter->numero);
//        if(isset($filter->fecha) && $filter->fecha!='')     $this->db->where('p.FecPago',$filter->fecha);
//        if(isset($filter->fechai) && $filter->fechai!='')   $this->db->where('p.FecPago>=',$filter->fechai);
//        if(isset($filter->fechaf) && $filter->fechaf!='')   $this->db->where('p.FecPago<=',$filter->fechaf);
//        $query = $this->db->get();
//        $resultado = array();
//        if($query->num_rows>0){
//            $resultado = $query->result();
//        }
//        return $resultado;
//    }

    public function listar(){
       $cadena  = "select fecrep,nrodoc,codserv,despro
         ,CASE WHEN r.mo = '3' THEN r.mtosub * tc ELSE r.mtosub END
                   ,o.nroot
,CASE WHEN ESTREP = 'C' THEN 'ATENDIDO' ELSE 'NO ATEND' END
from reposición as r 
left join ot as o on
o.codot = r.codot and o.codent = r.codent
left join producto as p on
 r.codserv = p.codpro and r.codent = p.codent
where r.codent = '02' and r.tipdoc = 'RS' and r.fecrep between '01/08/2013' and '31/08/2013'
AND R.ESTREP !='A'";

//        $this->db->select("convert(varchar,r.fecrep,103) as fecha ,r.nrodoc,r.codserv,r.despro,CASE WHEN r.mo = '3' THEN r.mtosub * tc ELSE r.mtosub END,o.nroot,CASE WHEN ESTREP = 'C' THEN 'ATENDIDO' ELSE 'NO ATEND' END");
//        $this->db->from($this->table.' as r');
//        $this->db->join($this->table2.' as o','o.codot = r.codot and o.codent = r.codent','left');
//        $this->db->join($this->table3.' as p','r.codserv = p.codpro and r.codent = p.codent','left');
//        $this->db->where($arrWhere);  
         $query = $this->db->query($cadena);
        $resultado = array();
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
