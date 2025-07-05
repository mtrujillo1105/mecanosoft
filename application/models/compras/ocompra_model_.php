<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ocompra_model extends CI_Model{
    var $entidad;
    var $table;
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('date');
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "Reposición";
    }
	
    public function seleccionar($tipOt,$default=""){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array(''=>$nombre_defecto);
        foreach($this->listar($tipOt) as $indice=>$valor)
        {
            $indice1   = $valor->NroDoc;
            $valor1    = $valor->NroDoc;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
	
    public function listar($number_items='',$offset=''){
        $where = array("TipDoc"=>"OC",'CodEnt',$this->entidad);
        $this->db->select('*');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);
        $this->db->where_not_in('EstRep','A');		
        $this->db->order_by('NroDoc','desc');
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
        
    public function obtener($serie,$numero){
        if($this->entidad=='01'){
            
        }
        elseif($this->entidad=='02'){
            $where = array("SerieDoc"=>$serie,"NroDoc"=>$numero,"CodEnt"=>$this->entidad,"TipDoc"=>"OC");
            $query = $this->db->where($where)->get($this->table);
            $resultado = new stdClass();
            if($query->num_rows>1) exit('Existe mas de 1 resultado');
            if($query->num_rows==1){
                $resultado = $query->row();
            }            
        }
        return $resultado;
    }
    
    public function obtener2($numero,$codpro){
        if($this->entidad=='01'){
            $cadena = "SELECT gserguia,gnumguia,gCantidad,codot,Fecemi,Got FROM Ordenc WHERE user='X' AND Gnumreq='".$numero."' and Gcodpro='".$codpro."'"; 
            $query     = $this->dbase->query($cadena);
            $resultado = $query->result();
        }
        elseif($this->entidad=='02'){
            $where = array("SerieDoc"=>"0004","NroDoc"=>$numero,"CodEnt"=>$this->entidad);
            $query = $this->db->order_by('nroOt')->where($where)->get($this->table);
            $resultado = new stdClass();
            if($query->num_rows>1) exit('Existe mas de 1 resultado');
            if($query->num_rows==1){
                $resultado = $query->row();
            }            
        }
        return $resultado;
    }    

    public function insertar(stdClass $filter = null){
        $this->db->insert($this->table,(array)$filter);
    }
	
    public function modificar($id,$filter){
        $where = array("SerieDoc"=>"0004","NroDoc"=>$numero,"CodEnt"=>$this->entidad);
        $this->db->where($where);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($id){
        //$this->db->delete($this->table,array('codot' => $id));
    }
	
    public function buscar($filter,$number_items='',$offset=''){
        $this->db->select('*');
        $this->db->from($this->table,$number_items='',$offset='');
        $this->db->where('CodEnt',$this->entidad);
        $this->db->where_not_in('Estado','A');	
        if(isset($filter->CodEnt) && $filter->CodEnt!="")
            $this->db->like('CodEnt',$filter->CodEnt);
        if(isset($filter->Estado) && $filter->Estado!="")
            $this->db->like('Estado',$filter->Estado);
        $query = $this->db->get();
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                    $data[] = $fila;
            }
            return $data;
        }
    }
    
    
    
    public function indicadores(){
     //$this->load->view(compras."consulta_requis.php");
    }
    
    
    
    public function rpt_control_compras($fInicio,$fFin){
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
                AND ordenc.Fecemi between CTOD('".date_dbf($fInicio)."') and CTOD('".date_dbf($fFin)."')
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
                p.codpro as codpro,
                p.despro as producto
                from reposición_det as r_det 
                inner join Reposición as r on (r.CodEnt=r_det.CodEnt and r.TipDoc=r_det.TipDoc and r.SerieDoc=r_det.SerieDoc and r.NroDoc=r_det.NroDoc and r.RucCli=r_det.RucCli)
                inner join producto p on (r_det.codpro=p.codpro and r_det.CodEnt=p.CodEnt)
                where r.codent='".$this->entidad."' 
                and r.tipdoc='OC' 
                and r.FecRep >= '".$fInicio."' 
                and r.FecRep<='".$fFin."' 
                and r.estrep='P'
                order by r_det.nrodocref desc
            ";
            $query = $this->db->query(utf8_decode($sql)); 
        }
        $resultado = $query->result();
        return $resultado;   
    }
}
?>