<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Asistencia_registro_model extends CI_Model {
    
    public $entidad;
    public $table;
    
    public function __construct() {
        parent::__construct();
        $this->scire    = $this->load->database('scire',TRUE);
        $this->entidad  = $this->session->userdata('entidad');
        $this->table    = "Asistencia_Registro";
    }
    
    public function getHorasLaboradas($filter,$filternot = "",$order_by="") {
        $where = array("p.Categoria2_id" => $this->entidad);
        if(isset($filter->tipo_trabajador) && $filter->tipo_trabajador != '') $where = array_merge($where, array("p.Tipo_Trabajador_Id" => $filter->tipo_trabajador));
        if(isset($filter->ccosto) && $filter->ccosto != '')                   $where = array_merge($where, array("p.Ccosto_Id" => $filter->ccosto));
        if(isset($filter->ccosto_conta) && $filter->ccosto_conta != '')       $where = array_merge($where, array("c.Codigo_Auxiliar2" => $filter->ccosto_conta));
        if(isset($filter->fechaini) && $filter->fechaini != '')               $where = array_merge($where, array("r.Fecha >=" => $filter->fechaini));
        if(isset($filter->fechafin) && $filter->fechafin != '')               $where = array_merge($where, array("r.Fecha <=" => $filter->fechafin));
        if(isset($filter->fecha) && $filter->fecha!= '')                      $where = array_merge($where, array("r.Fecha" => $filter->fecha));
        if(isset($filter->proyecto_id) && $filter->proyecto_id!= '')          $where = array_merge($where, array("p.Proyecto_Id" => $filter->proyecto_id));
        if(isset($filter->personal_id) && $filter->personal_id != '')         $where = array_merge($where, array("p.Personal_Id" => trim($filter->personal_id)));        
        if(isset($filter->estado) && $filter->estado != '')                   $where = array_merge($where, array("r.estado =" => $filter->estado));                
        $sql = "p.Personal_Id,p.Apellido_Paterno + ' ' + p.Apellido_Materno + ' ' + p.Nombres as Nombres,p.Proyecto_Id,";
        $sql .= "c.Descripcion as Ccosto,r.Horas as HorasLab,r.Fecha,p.Planilla_Id,p.Afp_Id,r.Hora_Ingreso,r.Hora_Salida,p.Tipo_Doc_Id,p.Nro_Doc,p.Tipo_Trabajador_Id,p.Cargo_Id,p.Personal_Id,r.Horas_Extra,r.Hingreso,r.Hsalida,r.Valor1 as Tardanza,r.Estado,'' as asi_ref_start,'' as asi_ref_end,c.Ccosto_Id";
        $this->scire->select($sql);
        $this->scire->from('Asistencia_Registro as r');
        $this->scire->join('personal as p','r.Personal_Id = p.Personal_Id','left');
        $this->scire->join('Ccosto as c','c.ccosto_id = p.Ccosto_Id','left');
        $this->scire->where($where);
        if(isset($order_by) && count($order_by)>0 && $order_by!=""){
            foreach($order_by as $indice=>$value){
                $this->scire->order_by($indice,$value);
            }
        }                 
        $query = $this->scire->get();        
        
        //// otra vez lo mismo    
        //echo 'xxxx'.$this->scire->last_query();
        //die;
        
        
        return $query->result();
    }
    
    public function getTurnoByDay($id,$fecha){
        $this->scire->select("*");
        $this->scire->from($this->table.' as p');
        $where = array("p.Personal_Id" => $id , "fecha"=>$fecha);
        $this->scire->where($where);
        $query = $this->scire->get();
        $resultado = $query->row();
        return $resultado;
    }

    public function getHorasLaboradasTotal($filter,$filternot="",$group_by) {
        $where  = array("p.Categoria2_id" => $this->entidad);
        $campos = implode(",",$group_by);
        if(isset($filter->tipo_trabajador) && $filter->tipo_trabajador != '') $where = array_merge($where, array("p.Tipo_Trabajador_Id" => $filter->tipo_trabajador));
        if(isset($filter->ccosto) && $filter->ccosto != '')                   $where = array_merge($where, array("p.Ccosto_Id" => $filter->ccosto));
        if(isset($filter->ccosto_conta) && $filter->ccosto_conta != '')       $where = array_merge($where, array("c.Codigo_Auxiliar2" => $filter->ccosto_conta));
        if(isset($filter->proyecto_id) && $filter->proyecto_id!= '')          $where = array_merge($where, array("p.Proyecto_Id" => $filter->proyecto_id));
        if(isset($filter->fechaini) && $filter->fechaini != '')               $where = array_merge($where, array("r.Fecha >=" => $filter->fechaini));
        if(isset($filter->fechafin) && $filter->fechafin != '')               $where = array_merge($where, array("r.Fecha <=" => $filter->fechafin));
        $sql  = "".$campos.",";
        $sql .= "sum(r.Horas) as HorasLab, sum(r.Horas_Extra) as Horas_Extra,sum(r.Valor1) as Tardanza";
        $this->scire->select($sql);
        $this->scire->from('Asistencia_Registro as r');
        $this->scire->join('personal as p','r.Personal_Id = p.Personal_Id','left');
        $this->scire->join('Ccosto as c','c.ccosto_id = p.Ccosto_Id','left');
        $this->scire->where($where);
        $this->scire->group_by($group_by); 
        $this->scire->order_by("p.Apellido_Paterno","asc");
        $query = $this->scire->get();            
        return $query->result();
    }
    
    public function getFechasLaboradas($filter,$filternot = "") {
        $where = array('p.Estado_Id' => '01');
        if(isset($filter->tipo_trabajador) && $filter->tipo_trabajador != '') $where = array_merge($where, array("p.Tipo_Trabajador_Id" => $filter->tipo_trabajador));
        if(isset($filter->ccosto) && $filter->ccosto != '') $where = array_merge($where, array("p.Ccosto_Id" => $filter->ccosto));
        if(isset($filter->fechaini) && $filter->fechaini != '') $where = array_merge($where, array("r.Fecha >=" => $filter->fechaini));
        if(isset($filter->fechafin) && $filter->fechafin != '') $where = array_merge($where, array("r.Fecha <=" => $filter->fechafin));
        $sql = "distinct(r.Fecha)";
        $this->scire->select($sql);
        $this->scire->from('Asistencia_Registro as r');
        $this->scire->join('personal as p','r.Personal_Id = p.Personal_Id','left');
        $this->scire->join('Ccosto as c','c.ccosto_id = p.Ccosto_Id','left');
        $this->scire->where($where);
        $this->scire->order_by("r.Fecha","asc");
        $query = $this->scire->get();
        return $query->result();
    }
    
    public function getPersonalHoras($filter,$filternot = "") {
        $where = array(
            "p.Categoria2_id" => $this->entidad,
            'p.Estado_Id' => '01'
        );
        if(isset($filter->tipo_trabajador) && $filter->tipo_trabajador != '') $where = array_merge($where, array("p.Tipo_Trabajador_Id" => $filter->tipo_trabajador));
        if(isset($filter->ccosto) && $filter->ccosto != '') $where = array_merge($where, array("p.Ccosto_Id" => $filter->ccosto));
        if(isset($filter->fechaini) && $filter->fechaini != '') $where = array_merge($where, array("r.Fecha >=" => $filter->fechaini));
        if(isset($filter->fechafin) && $filter->fechafin != '') $where = array_merge($where, array("r.Fecha <=" => $filter->fechafin));
        $sql = "distinct(p.Personal_Id),p.Apellido_Paterno + ' ' + p.Apellido_Materno + ' ' + p.Nombres as Nombres,";
        $sql .= "p.Ccosto_Id,c.Descripcion,p.Tipo_Trabajador_Id,p.Nro_Doc";
        $this->scire->select($sql);
        $this->scire->from('Asistencia_Registro as r');
        $this->scire->join('personal as p','r.Personal_Id = p.Personal_Id','left');
        $this->scire->join('Ccosto as c','c.ccosto_id = p.Ccosto_Id','left');
        $this->scire->where($where);
        $query = $this->scire->get();
        return $query->result();
    }
 
//    public function insert($campos,$filter){
//        $data = array(
//            'Asistencia_Id' => '',
//            'Turno_Id' => '01',
//            'Tipo_Suspension_RL_Id' => '00',
//            'Tipo_Dia_Id' => '00',
//            'Fecha' => $campos['Fecha'],
//            'Personal_Id' => $campos['Personal_Id'],
//            'Hingreso' => $filter->Hingreso,
//            'Hsalida' => $filter->Hsalida
//        );
//        $this->scire->insert('Asistencia_registro',$data);
//    }

    public function insert(stdClass $filter = null)
    {
        $this->scire->insert($this->table,(array)$filter);
    }
	
    public function edit($campos,$filter){
        foreach($campos as $nombre => $valor){
            $this->scire->where($nombre,$valor);    
        }
        $this->scire->update($this->table,(array)$filter);
    }
	
    public function delete(){

    }    
}
?>
