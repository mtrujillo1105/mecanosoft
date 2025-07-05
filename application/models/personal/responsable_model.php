<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Responsable_model extends CI_Model{
    var $entidad;
    var $table;
    public function __construct()
    {
        parent::__construct();
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "responsable";
    }
	
    public function seleccionar($filter,$filter_not,$order_by='',$default="",$value='')
    {
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        foreach($this->listarg($filter,$filter_not,$order_by) as $indice=>$valor)
        {
            $indice1   = $valor->CodRes;
            $valor1    = $valor->CodRes." - ".$valor->nomper;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
    }
                        
     public function listar_cabezera($filtroEmpleado, $filtrofecha1, $filtroEstado, $filtroCentroC){
        $cadena = "           
        SELECT dirot, nroot, codot FROM OT 
        WHERE  TIPOT='04'
        and Estado='P' 
        and Codent='".$this->entidad."'
        ".$filtroCentroC." 
        and CodOt 
        IN(
        SELECT 
        DISTINCT RES.CodOt
        FROM RELOJ as REJ
        INNER JOIN RESPONSABLE as RES 
        on (RES.codent=REJ.codent and RES.CodRes=REJ.CodRes ".$filtroEstado." ".$filtroEmpleado.")
        WHERE REJ.Codent='".$this->entidad."'
        ".$filtrofecha1."
        )
        ORDER BY DIROT
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }  
                
     public function listar_detalle($filtroEmpleado, $codot, $filtrofecha1, $filtroEstado){
        $cadena = "  
        SELECT 
        RES.dniper,
        RES.nomper,
        REJ.hora,
        REJ.salida,
        convert(char,REJ.fecha,106) AS fecha
        FROM RELOJ as REJ
        INNER JOIN RESPONSABLE as RES 
        on (RES.codent=REJ.codent  and RES.CodRes=REJ.CodRes ".$filtroEstado." ".$filtroEmpleado.")
        WHERE REJ.Codent='".$this->entidad."' and RES.CodOt='".$codot."'
        ".$filtrofecha1."
        ORDER BY RES.nomper, REJ.fecha ASC
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }  
    
        public function seleccionarArea($default="",$value='',$filtroEmpleado, $filtrofecha1, $filtroEstado){
        $nombre_defecto = $default==""?":: Seleccione ::":$default;
        $arreglo = array($value=>$nombre_defecto);
        //$fecha = '29/03/2012';
        foreach($this->listar0($filtroEmpleado, $filtrofecha1, $filtroEstado) as $indice=>$valor)
        {
            $indice1   = $valor->nroot;
            $valor1    = $valor->dirot;
            $arreglo[$indice1] = $valor1;
        }
        return $arreglo;
        }  
        
        
                    
    public function listar0($filtroEmpleado, $filtrofecha1, $filtroEstado){
        $cadena = "
        SELECT dirot, nroot FROM OT 
        WHERE  TIPOT='04'
        and Estado='P' 
        and Codent='".$this->entidad."'
        and CodOt 
        IN(
        SELECT 
        DISTINCT RES.CodOt
        FROM RELOJ as REJ
        INNER JOIN RESPONSABLE as RES 
        on (RES.codent=REJ.codent and RES.CodRes=REJ.CodRes ".$filtroEstado." ".$filtroEmpleado.")
        WHERE REJ.Codent='".$this->entidad."'
        ".$filtrofecha1."
        )
        ORDER BY dirot
        ";
        $query = $this->db->query($cadena);
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;  
    }    
        
    
     public function listarg($filter,$filter_not,$order_by='',$number_items='',$offset=''){
        $where = array("CodEnt"=>$this->entidad);
        if(isset($filter->codresponsable) && $filter->codresponsable!='')  $where = array_merge($where,array("CodRes"=>$filter->codresponsable));
        if(isset($filter->estado) && $filter->estado!='')                  $where = array_merge($where,array("Estper"=>$filter->estado));
        if(isset($filter->situacion) && $filter->situacion!='')            $where = array_merge($where,array("Codsituac"=>$filter->situacion));
        $this->db->select('*');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where($where);
       if(isset($order_by) && count($order_by)>0){
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
    
    
    
    
    
    
    public function listar($tipOt,$number_items='',$offset='')
    {
        $this->db->select('*');
        $this->db->from($this->table,$number_items,$offset);
        $this->db->where('CodEnt',$this->entidad);
        $this->db->where_in('TipOt',$tipOt);
        $this->db->where_not_in('Estado','A');		
        $this->db->order_by('nomper');
        $query = $this->db->get();
        $resultado = array();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
	
    public function obtener($filter,$filter_not)
    {
        $where = array("CodEnt"=>$this->entidad);
        if(isset($filter->codresponsable) && $filter->codresponsable!='')  $where = array_merge($where,array("CodRes"=>$filter->codresponsable));
        if(isset($filter->estado) && $filter->estado!='')                  $where = array_merge($where,array("Estper"=>$filter->estado));
        if(isset($filter->situacion) && $filter->situacion!='')            $where = array_merge($where,array("Codsituac"=>$filter->situacion));        
        $this->db->where($where);
        $this->db->from($this->table);
        $query = $this->db->get(); 
        //$query = $this->db->where($where)->get($this->table);
        $resultado = new stdClass();
       
        if($query->num_rows>1) exit('Existe mas de 1 resultado tabla '.$this->table);
        if($query->num_rows==1){
            $resultado = $query->row();
        }
        return $resultado;
    }

    public function obtener2($fecnac)
    {
        $where = array("codent"=>$this->entidad,"estper"=>'2',"codsituac"=>'2',"substring(convert(char(50),fec_nac,112),5,8)"=>$fecnac);
        $query = $this->db->order_by('nomper')->where($where)->get($this->table);
        $resultado = new stdClass();
        if($query->num_rows>0){
            $resultado = $query->result();
        }
        return $resultado;
    }
    
    public function insertar(stdClass $filter = null)
    {
        $this->db->insert($this->table,(array)$filter);
    }
    
    public function getPersonalByNroDoc($nrodoc, $codent) {
        $this->db->select("top 1 *");
        $this->db->from($this->table.' as p');
        $where = array("p.dniper" => $nrodoc, "p.estper" => '2', "p.codsituac" => '2', "p.CodEnt" => $codent);
        $this->db->where($where);
        $this->db->order_by("p.CodRes","desc");
        $query = $this->db->get();
        $resultado = $query->row();
        return $resultado;
    }
    
    public function modificar($id,$filter)
    {
        $this->db->where("CodOt",$id);
        $this->db->update($this->table,(array)$filter);
    }
	
    public function eliminar($id)
    {
        $this->db->delete($this->table,array('codot' => $id));
    }
	
    public function buscar($filter,$number_items='',$offset='')
    {
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
}
?>