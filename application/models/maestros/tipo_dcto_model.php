<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tipo_dcto_model extends CI_Model{
    var $table;
    var $entidad;
    public function __construct(){
        parent::__construct();
        $this->dbase   = $this->load->database('dsn',TRUE);
        $this->entidad = $this->session->userdata('entidad');
        $this->table   = "view_tipoDocCaja";
    }
    
  
    /*Obtiene un tipo de producto, el resultado es único*/
    public function obtener($codigo)
    {
                    if($codigo==0)
                        $codigo='22';
            $cadena = "
                select 
                d.Doccod,
                d.docdescri
                
                from docums as d 
                                

                where d.Doccod='$codigo'
               
                   
                ";
            $query = $this->dbase->query($cadena);
            $resultado = $query->row();
  
         
            
        return $resultado;
        
        
    }
	
   
}
?>