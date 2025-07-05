<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Soliocompra_model extends CI_Model{
    var $entidad;
    var $table;

    public function __construct(){
        parent::__construct();
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table     = utf8_decode("Reposición");
        $this->table_det = utf8_decode("Reposición_det");
        $this->table_dbf = "soli_oc";
    }

    public function listar_detalle($filter,$filter_not,$order_by="",$number_items='',$offset=''){
        if($this->entidad=='01'){
            $filtrofechai = "";
            $filtrofechaf = "";
            $filtrofecha  = "";
            $filtroserie  = "";
            $filtronumero = "";
            $filtrotipo   = "";
            $filtrocodot  = "";
            $filtrorequis = "";
            if(isset($filter->fechai) && $filter->fechai!='')              $filtrofechai = "AND fecemi>=CTOD('".date_dbf($filter->fechai)."')";
            if(isset($filter->fechaf) && $filter->fechaf!='')              $filtrofechaf = "AND fecemi<=CTOD('".date_dbf($filter->fechaf)."')";
            if(isset($filter->fecha) && $filter->fecha!='')                $filtrofecha  = "AND fecemi=CTOD('".date_dbf($filter->fecha)."')";
            if(isset($filter->serie) && $filter->serie!='')                $filtroserie  = "AND gserie='".$filter->serie."'";
            if(isset($filter->numero) && $filter->numero!='')              $filtronumero = "AND gnumero='".$filter->numero."'";
            if(isset($filter->tipo) && $filter->tipo!='')                  $filtrotipo   = "AND tipot='".$filter->tipo."'";
            if(isset($filter->codot) && $filter->codot!='')                $filtrocodot  = "AND codot='".$filter->codot."'";
            if(isset($filter->requis) && $filter->requis!='')              $filtrorequis = "AND gnumreq='".$filter->requis."'";
            $cadena = "
                select
                tipo,
                gserie,
                gnumero,
                fecemi,
                gobs,
                gcodpro,
                gcantidad,
                gsolicita,
                gnumreq,gmoneda,gprecio,gcoddpto,gdepa,gcantidads,modo,gcodprov,numord,chk,
                afecto,
                codres,
                tipot,
                got,
                codot
                from soli_oc
                where codot!=' '
                ".$filtrofechai."
                ".$filtrofechaf."
                ".$filtrofecha."
                ".$filtronumero."
                ".$filtroserie."
                ".$filtrotipo."
                ".$filtrocodot."
                ".$filtrorequis."
            ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            $where = array("c.TipDoc"=>"SC",'c.CodEnt'=>$this->entidad);
            if(isset($filter->fechai) && $filter->fechai!="") $where = array_merge($where,array("c.FecRep>="=>$filter->fechai));
            if(isset($filter->fechaf) && $filter->fechaf!="") $where = array_merge($where,array("c.FecRep<="=>$filter->fechaf));
            if(isset($filter->fecha) && $filter->fecha!="")   $where = array_merge($where,array("c.FecRep"=>$filter->fecha));
            if(isset($filter->serie) && $filter->serie!="")   $where = array_merge($where,array("c.SerieDoc"=>$filter->serie));
            if(isset($filter->numero) && $filter->numero!="") $where = array_merge($where,array("c.NroDoc"=>$filter->numero));
            if(isset($filter->codot) && $filter->codot!="")   $where = array_merge($where,array("d.CodOt"=>$filter->codot));
            if(isset($filter->requis) && $filter->requis!="") $where = array_merge($where,array("d.NroDRef"=>$filter->requis));
            if(isset($filter->estado) && $filter->estado!="") $where = array_merge($where,array("c.EstRep"=>$filter->estado));
            $this->db->select("c.SerieDoc as gserie,c.NroDoc as gnumero,c.Ruccli as ruccli,convert(varchar(10),c.FecRep,120) as fecemi,c.Mo as mo,c.EstRep,c.ObsRep as gobs,d.CodPro as gcodpro,d.CantSolRep as gcantidad,d.CantSol as gsolicita,d.NroDRef as gnumreq,d.SerieDocRef as gserreq,d.MO as gmoneda,d.CodModo as modo,d.NroDocRef as numord,d.chk as chk");
            $this->db->from($this->table_det." as d",$number_items,$offset);
            $this->db->join($this->table." as c","c.SerieDoc=d.SerieDoc and c.NroDoc=d.NroDoc and c.CodEnt=d.CodEnt and c.TipDoc=d.TipDoc");
            $this->db->where($where);		
            $this->db->order_by('c.NroDoc','desc');
            $query = $this->db->get();
            $resultado = array();
            if($query->num_rows>0){
                $resultado = $query->result();
            }   
        }
        return $resultado;
    }
    
}
?>
