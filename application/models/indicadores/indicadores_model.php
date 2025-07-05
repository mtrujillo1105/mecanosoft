<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class indicadores_model extends CI_Model {
    
    var $entidad;
    var $table;
    var $reposicionTable;
    var $reposicionDetTable;
    var $productoTable;
    var $tabla_M_Detalle;
    var $almacenMovTable;
    var $almacenMovTableDet;
    
    public function __construct() {
        parent::__construct();
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table = "mim_area";
        $this->reposicionTable = "[" . utf8_decode('Reposición') . "]";
        $this->reposicionDetTable = "[" . utf8_decode('Reposición_det') . "]";
        $this->productoTable = "Producto";
        $this->tabla_M_Detalle = "Tabla_M_Detalle";
        $this->almacenMovTable = "Almacen_mov";
        $this->almacenMovTableDet = "Almacen_mov_det";
//        $this->entidad = $this->session->userdata('entidad');
        
    }
    
    public function getKPI() {
        
        $arrayWhere = array(
            'M.are_status' => 'A',
            'K.kpi_status' => 'A'
        );
        $this->db->select('*');
        $this->db->from($this->table . " as M");
        $this->db->join("mim_kpi as K", "M.are_code = K.kpi_are_code", "left");
        $this->db->join("mim_kpi_periods as P", "K.kpi_per_code = P.per_code", "left");
        $this->db->where($arrayWhere);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
        
    }
    
    public function getAreas() {
        
        $arrayWhere = array(
            'M.are_status' => 'A'
        );
        $this->db->select('*');
        $this->db->from($this->table . " as M");
        $this->db->where($arrayWhere);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
        
    }
    
    public function saveKPIDet($data, stdClass $filter = null) {
        $this->db->insert("mim_kpi_det",$data);
    }
    
    public function getOC($fini,$ffin){
        if($this->entidad=='01'){
            $sql = "
                SELECT 
                requis.gserguia as serie_requerimiento,
                requis.gnumguia as numero_requerimiento,
                requis.Fecemi as fecha_requerimiento,
                soli_oc.Gserie as serie_sc,
                soli_oc.gnumero as numero_sc,
                soli_oc.fecemi as fecha_sc,
                ordenc.gserguia as serie_oc,
                ordenc.gnumguia as numero_oc,
                ordenc.fecemi as fecha_oc,
                ordenc.fec_apro as fecha_aproboc,
                ''  as fregistro_oc,
                requis.gcodpro as codpro,
                '' as producto
                FROM ordenc 
                INNER JOIN soli_oc ON (soli_oc.gserie=ordenc.gsersol AND soli_oc.gnumero=ordenc.gnumsol) 
                inner join requis on (requis.gnumguia=soli_oc.gnumreq) 
                WHERE ordenc.Gestado='X' 
                AND ordenc.Fecemi between CTOD('".date_dbf(str_replace("-", "/", $fini))."') and CTOD('".date_dbf(str_replace("-", "/", $ffin))."')
            "; 
            $query = $this->dbase->query($sql); 
        }
        elseif($this->entidad=='02'){
           $sql = "
                select 
                r_det.tipdocref as requerimiento,
                r_det.seriedocref  as serie_requerimiento,
                r_det.nrodocref as numero_requerimiento,
                (SELECT convert(varchar,b.FecRep,103)
                FROM         Reposición as b
                WHERE     (b.CodEnt = '".$this->entidad."') AND (b.TipDoc = 'RQ') AND (b.NroDoc = r_det.nrodocref ))  fecha_requerimiento,
                r.tipdocref  as sc,
                r.seriedocref  as serie_sc,
                r.nrodocref  as numero_sc,
                (SELECT     convert(varchar,c.FecRep,103)
                FROM         Reposición as c
                WHERE     (c.CodEnt = '".$this->entidad."') AND (c.TipDoc = 'SC') AND (c.NroDoc = r.nrodocref ))  fecha_sc,
                r.tipdoc as oc, 
                r.seriedoc as serie_oc,
                r.nrodoc as numero_oc,
                convert(varchar,r.fecrep,103) as fecha_oc,
                convert(varchar,r.fecApro,103) as fecha_aproboc,
                convert(varchar,r.fec_reg,103) as fregistro_oc,
                r.RucCli,
                p.codpro as codpro,
                p.despro as producto
                from reposición_det as r_det 
                inner join Reposición as r on (r.CodEnt=r_det.CodEnt and r.TipDoc=r_det.TipDoc and r.SerieDoc=r_det.SerieDoc and r.NroDoc=r_det.NroDoc and r.RucCli=r_det.RucCli)
                inner join producto p on (r_det.codpro=p.codpro and r_det.CodEnt=p.CodEnt)
                where r.codent='".$this->entidad."' 
                and r.tipdoc='OC'
                and r.FecRep >= '$fini'
                and r.FecRep<= '$ffin'
                and r.estrep='P'
                order by fregistro_oc desc
            ";
//           order by r_det.nrodocref desc
            $query = $this->db->query(utf8_decode($sql)); 
        }
        $resultado = $query->result();
        return $resultado;   
    }
    
    public function getMovimientosEntrada() {
        if($this->entidad=='02') {
            $arrayWhere = array(
                'mov.TipDoc' => 'NE'
            );
            $this->db->select("mov.NroOC,mov.SerOc,movdet.CodPro,mov.TipMov,mov.TipDoc,mov.NroDoc as NroNEA,mov.SerieDoc as SerNEA,mov.Fec_Reg as FecNEA,movdet.CodPro");
            $this->db->from($this->almacenMovTable . " as mov");
            $this->db->join($this->almacenMovTableDet . " as movdet", "mov.NroDoc = movdet.NroDoc and mov.CodEnt = movdet.CodEnt and mov.SerieDoc = movdet.SerieDoc and mov.TipDoc = movdet.TipDoc and mov.TipMov = movdet.TipMov");
            $this->db->where($arrayWhere);
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
        elseif($this->entidad=='01') {
            
        }
    }
    
    public function getProductosFromDBF() {
        $productos = "";
        $query = $this->dbase->query($oc);
        $resultado = $query->result();
        return $resultado;
    }
    
    public function listarDetalle($serie_oc,$numero_oc,$codpro) {
        if($this->entidad == '02') {
            $where = array(
                "Almacen_mov_det.tipdoc" => "NE",
                'Almacen_mov_det.CodEnt' => $this->entidad,
                "Almacen_mov.SerOc" => $serie_oc,
                "Almacen_mov.NroOc" => $numero_oc,
                "Almacen_mov_det.CodPro" => $codpro
            );
            $this->db->select('Almacen_mov.SerieDoc as seriedoc,Almacen_mov.NroDoc as nrodoc,convert(varchar,Almacen_mov.Fec_Doc,103) as fec_doc,convert(varchar,Almacen_mov.FecMov,103) as fecmov,convert(varchar,Almacen_mov.Fec_Reg,103) as fec_reg');
            $this->db->from($this->almacenMovTable);
            $this->db->join($this->almacenMovTableDet, "Almacen_mov.NroDoc=Almacen_mov_det.NroDoc and Almacen_mov.codent=Almacen_mov_det.codent");
            $this->db->where($where);
            $query = $this->db->get();
            $resultado = array();
            if ($query->num_rows > 0) {
                $resultado = $query->result();
            }
            return $resultado;
        }
        elseif($this->entidad == '01'){
            $sql = "select Serie as seriedoc,Numero as nrodoc,Fecha as fec_doc,('') as fecmov,('') as fec_reg from kardex where tip_movmto = 'I' and Seroc = '$serie_oc' and Numoc = '$numero_oc' and Codigo = '$codpro'";
            $query = $this->dbase->query($sql);
            $resultado = $query->result();
            return $resultado;
        }
    }
    
}