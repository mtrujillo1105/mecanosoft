<?php
class Scire extends CI_Controller {
    var $entidad;
    var $login;
    var $arr_usr_types = array(
            '01' => 'EMPLEADO',
            '05' => 'OBRERO',
            '04' => 'RPH'
        );
    var $arr_planilla = array(
            '01' => 'EMP',
            '02' => 'OBR',
            '04' => 'RPH'
        );
    
    var $arr_tipo_trabajador = array(
            '21' => 'EMP',
            '20' => 'OBR',
            '00' => 'RPH',
            '98' => 'INDEFINIDO'
        );
    
    public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['login'])) die("Sesion terminada. <a href='".  base_url()."'>Registrarse e ingresar.</a> ");
        $this->load->model(scire . 'planillas_model');        
        $this->load->model(scire . 'dvariables_model');
        $this->load->model(scire . 'dfijos_model');
        $this->load->model(scire . 'periodo_model');
        $this->load->model(scire . 'personal_model');
        $this->load->model(scire . 'planillas_model');
        $this->load->model(scire . 'planilla_model');
        $this->load->model(scire . 'procesos_model');
        $this->load->model(scire . 'mes_model');
        $this->load->model(scire . 'ccosto_model');
        $this->load->model(scire . 'ccosto_conta_model');
        $this->load->model(scire . 'ctecorriente_model');
        $this->load->model(scire . 'asistencia_registro_model');
        $this->load->model(scire . 'tipo_trabajador_model');
        $this->load->model(scire . 'cargo_model');
        $this->load->model(scire . 'ejercicio_model');
        $this->load->model(scire . 'conceptos_model');
        $this->load->model(scire . 'proyecto_model');
        $this->load->model(scire . 'personalactivo_model');
        $this->load->model(produccion . 'tareo_model');
        $this->load->model(personal . 'reloj_model');
        $this->load->model(personal . 'responsable_model');
        $this->load->model(siddex . 'parte_model');
    }
    
    public function costos_resumen(){
        $var_anio   = $this->input->get_post('cbo_anio');
        $var_mes    = $this->input->get_post('cbo_mes');
        $var_group  = $this->input->get_post('cbo_group');
        $var_area   = $this->input->get_post('cbo_area');
        $var_chk_type   = $this->input->get_post('chk_type');
        
        $var_txt_report   = $this->input->get_post('txt_report');
        
        $var_checks = "";
        foreach ($this->arr_usr_types as $key => $value) {
            $checked = "";
            if (@in_array($key, $var_chk_type)) {
                $checked = "checked";
            }
            $var_checks .= "<label><input type='checkbox' id='chk_".$key."' name='chk_type[]' value='".$key."' ".$checked." />".$value."</label>";
        }
        

        if($var_anio==""){
            $var_anio = date('Y');
        }
 
        $filter  = new stdClass();
        $filter->anio = $var_anio;
        $filter->mesi = "000043";
        
        
        $cbo_anio         = form_dropdown('cbo_anio',$this->ejercicio_model->select(new stdClass(),"SELECCIONAR",""),$var_anio," size='1' id='cbo_anio' style='width:200px;' class='comboPeque' onchange='$(\"#buscar\").click()' ");               
        $cbo_mes          = form_dropdown('cbo_mes',$this->mes_model->select($filter,"SELECCIONAR",""),$var_mes," size='1' id='cbo_mes' class='comboPeque' style='width:200px;'  ");
        

        
        if($query = $this->db->query("SP_SEL_SCIRE_PAGO_RESUMEN '".$var_mes."' , '".$var_area."' , '".$var_group."','','02'")){
            
            
            $this->db->where('per_code', $var_mes);
            $this->db->from('SCIRE_DATOS');
            $conf = $this->db->get()->row();
            $var_carga_social = @$conf->per_provision;
            

            
            
            $arr_cco    = array(); // AREA
            $arr_cco_group = array(); // CC
            $arr_users  = array(); // PERSONAL
            $arr_conceptos = array(); //CONCEPTOS
            $arr_values = array();
            $arr_values_obr = array();
            $arr_values_obr_otros = array();
            
            // ARR CONCEPTOS - REPORTE CONCEPTOS 
            $arr_montos_afp = array();
            $arr_montos_essalud = array();
            $arr_montos_desc_adicional = array();
            
            foreach ($query->result() as $key => $value) {
               if (!@in_array($value->planilla_id, $var_chk_type)) {
                   continue; 
               }
               
               $arr_cco[$value->cco_code] = $value->cco_descripcion;
               $arr_cco_group[$value->cco_cont_code] = $value->cco_group;
               //$arr_conceptos[$value->concepto_id] = 0;
               $arr_users
                       [$value->usr_code]
                       [$value->planilla_id]
                       [$value->cco_code] = array('cco_cont_code'=>$value->cco_cont_code ,'cco_group'=>$value->cco_group, 'planilla_id' => $value->planilla_id ,'afp_id' => $value->afp_id ,'usr_type' => $this->arr_planilla[$value->planilla_id] ,  'usr_name' => $value->usr_name,'fecha_ini_contrato' => $value->fecha_ini_contrato,'fecha_fin_contrato' => $value->fecha_fin_contrato,'usr_cargo' => $value->usr_cargo,'cco_descripcion' => $value->cco_descripcion , 'cco_code'=>  $value->cco_code);
               
               $arr_values[$value->cco_code][$value->usr_code][$value->concepto_id] = $value->valor;
               if($value->planilla_id=='02'){
                   if($value->afp_id=='99'){
                       $arr_values_obr_otros[$value->cco_code][$value->usr_code][$value->concepto_id][$value->periodo_id] = $value->valor;
                   }else{
                       $arr_values_obr[$value->cco_code][$value->usr_code][$value->concepto_id][$value->periodo_id] = $value->valor;
                       
                   }
                   
               }
               
                // AGRUPANDO PAGOS PARA REPORTE POR CONCEPTOS
                       
                        if($value->valor > 0){
                            switch ($value->concepto_id) {
                                case '001222':
                                    $arr_montos_afp[$value->afp]['COMISION MIXTA'] = @$arr_montos_afp[$value->afp_id]['COMISION MIXTA'] + $value->valor;
                                    break;
                                case '000085':
                                    $arr_montos_afp[$value->afp]['AFP FONDO'] = @$arr_montos_afp[$value->afp_id]['AFP FONDO'] + $value->valor;
                                    break;
                                case '000084':
                                    $arr_montos_afp[$value->afp]['AFP SEGURO'] = @$arr_montos_afp[$value->afp_id]['AFP SEGURO'] + $value->valor;
                                    break;
                                case '000083':
                                    $arr_montos_afp[$value->afp]['AFP COMISION'] = @$arr_montos_afp[$value->afp_id]['AFP COMISION'] + $value->valor;
                                    break;
                                
                                case '000075':
                                    $arr_montos_essalud['ESSALUD'][$value->cco_cont_code.' '.$value->cco_group] = @$arr_montos_essalud['ESSALUD'][$value->cco_group]  + $value->valor;
                                    break;
                            }

                        }
               
            }

            asort($arr_cco_group);
            $arr_cco_group[''] = "TODOS";
            foreach ($arr_cco_group as $key => $value) {
                $arr_cco_group[$key] = $key.' '.$value;
            }
            
            $cbo_group = form_dropdown('cbo_group',$arr_cco_group,$var_group," size='1' id='cbo_group' class='comboPeque' style='width:200px;' onchange='$(\"#buscar\").click()'");
            unset($arr_cco_group['']);
            
            asort($arr_cco);
            $arr_cco[''] = "TODOS";
            $cbo_area = form_dropdown('cbo_area',$arr_cco,$var_area," size='1' id='cbo_area' class='comboPeque' style='width:200px;' ");
            unset($arr_cco['']);
            
            
            
            
            
            
            
            
            
            
            
           // DETALLE
 
           $var_usr_num  = 1;
           $body_detalle = '';
           
           $arr_cc_consolidado_data = array();
           $arr_area_consolidado_data = array();
           $arr_concepto_consolidado_data = array();
           
           $arr_export_detalle = array();
    
           $arr_columns_detalle = array();
           $arr_columns_detalle[]['STRING']  = 'NRO';
           $arr_columns_detalle[]['STRING']  = 'TIPO';
           $arr_columns_detalle[]['STRING']  = 'PERSONAL';
           $arr_columns_detalle[]['STRING']  = 'CC COD.';
           $arr_columns_detalle[]['STRING']  = 'CC';
           $arr_columns_detalle[]['STRING']  = 'AREA COD.';
           $arr_columns_detalle[]['STRING']  = 'AREA';
           
           
           $arr_columns_detalle[]['NUMERIC'] = 'BASICO';
           $arr_columns_detalle[]['NUMERIC'] = 'ASIG. FAM.';
           $arr_columns_detalle[]['NUMERIC'] = 'OTROS';
           $arr_columns_detalle[]['NUMERIC'] = '4TA';
           $arr_columns_detalle[]['NUMERIC'] = 'HE';
           $arr_columns_detalle[]['NUMERIC'] = 'HE DOB';
           $arr_columns_detalle[]['NUMERIC'] = 'DESCANSO SEM';
           $arr_columns_detalle[]['NUMERIC'] = 'PATERNIDAD';
           $arr_columns_detalle[]['NUMERIC'] = 'COMEDOR';
           
           $arr_columns_detalle[]['NUMERIC'] = 'ONP';
           $arr_columns_detalle[]['NUMERIC'] = 'AFP FONDO';
           $arr_columns_detalle[]['NUMERIC'] = 'AFP SEGURO';
           $arr_columns_detalle[]['NUMERIC'] = 'AFP COMISION';
           
           $arr_columns_detalle[]['NUMERIC'] = 'RET. 4TA';
           $arr_columns_detalle[]['NUMERIC'] = 'RET. 5TA';
           
           $arr_columns_detalle[]['NUMERIC'] = 'BONIF EXTRAOD';
           $arr_columns_detalle[]['NUMERIC'] = 'ESSALUD';
           $arr_columns_detalle[]['NUMERIC'] = 'SENATI';
           $arr_columns_detalle[]['NUMERIC'] = 'SCTR SALUD';
           $arr_columns_detalle[]['NUMERIC'] = 'SCTR PENSION';
           $arr_columns_detalle[]['NUMERIC'] = 'TARDANZA';
           
           $arr_columns_detalle[]['NUMERIC'] = 'MOVILIDAD';
           $arr_columns_detalle[]['NUMERIC'] = 'VIATICOS';
           
           $arr_columns_detalle[]['NUMERIC'] = 'REINTEGRO INAFECTO';
           $arr_columns_detalle[]['NUMERIC'] = 'GRATI SEMESTRAL';
           $arr_columns_detalle[]['NUMERIC'] = 'DESC PRESTAMO';
           $arr_columns_detalle[]['NUMERIC'] = 'DESC ADICIONAL';
           $arr_columns_detalle[]['NUMERIC'] = 'DESC 4TA';
           
           $arr_columns_detalle[]['NUMERIC'] = 'VACACIONES';
           $arr_columns_detalle[]['NUMERIC'] = 'AFP COMISION MIXTA';
           
           foreach ($arr_users as $usr_code => $arr_planillas) {
               foreach ($arr_planillas as $pla_code => $arr_areas) {
                   foreach ($arr_areas as $are_code => $det_data) {
                       
                       $arr_data = array();
                       
                       $var_planilla = 0;
                       $var_asig_fam = 0;
                       $var_otros    = 0;
                       $var_rph      = 0; 
                       
                       $var_ext      = 0;
                       $var_ext_dob  = 0;
                       $var_descanso_sem = 0;
                       $var_paternidad = 0;
                       $var_comedor  = 0;
                       
                       $var_onp      = 0;
                       $var_afp_fondo= 0;
                       $var_afp_segur= 0;
                       $var_afp_comis= 0;
                       
                       $var_cuarta   = 0;
                       $var_quinta   = 0;
                       
                       $var_bonif    = 0;
                       $var_essalud  = 0;
                       $var_senati   = 0;
                       $var_sctr_sal = 0;
                       $var_sctr_pen = 0;
                       $var_tardanza = 0;
                       
                       $var_movilidad = 0;
                       $var_viaticos = 0;
                       
                       $var_reintegro_inafecto = 0;
                       $var_gratif_semestral = 0;
                       
                       $var_desc_prestamo = 0;
                       $var_desc_adicional = 0;
                       $var_desc_4ta = 0;
                       
                       
                       $var_pagos_no_planilla = 0;
                       
                       
                       $var_vacaciones = 0;
                       $var_afp_comis_mixta= 0;
                       /*
                       if($sql_vacacaciones = $this->db->query("SP_SEL_SCIRE_VACACIONES '".$var_mes."' , '' , '' , '".$usr_code."'")){
                           foreach ($sql_vacacaciones->result() as $k => $v) {
                               //$var_vacaciones = $var_vacaciones + $v->importe;
                               //$arr_concepto_consolidado_data[1]['VACACIONES'] =  $arr_concepto_consolidado_data[1]['VACACIONES'] + $var_vacaciones;
                           }
                       }*/
                       
                       
                       
                        if($det_data["planilla_id"]=='02'){ // OBRERO
                            
                            $var_planilla = @array_sum($arr_values_obr[$are_code][$usr_code]["000052"]);
                            $var_asig_fam = @array_sum($arr_values_obr[$are_code][$usr_code]["000056"]);
                            $var_ext      = @array_sum($arr_values_obr[$are_code][$usr_code]["001169"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001169"]);
                            $var_ext_dob  = @array_sum($arr_values_obr[$are_code][$usr_code]["001173"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001173"]);
                            $var_descanso_sem   = @array_sum($arr_values_obr[$are_code][$usr_code]["000935"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000935"]);
                            $var_paternidad     = @array_sum($arr_values_obr[$are_code][$usr_code]["001099"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001099"]);
                            $var_comedor  = @array_sum($arr_values_obr[$are_code][$usr_code]["001166"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001166"]);
                            $var_onp      = @array_sum($arr_values_obr[$are_code][$usr_code]["000079"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000079"]);
                            $var_afp_fondo= @array_sum($arr_values_obr[$are_code][$usr_code]["000085"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000085"]);
                            $var_afp_segur= @array_sum($arr_values_obr[$are_code][$usr_code]["000084"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000084"]);
                            $var_afp_comis= @array_sum($arr_values_obr[$are_code][$usr_code]["000083"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000083"]);
                            $var_bonif    = @array_sum($arr_values_obr[$are_code][$usr_code]["000538"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000538"]);
                            $var_essalud  = @array_sum($arr_values_obr[$are_code][$usr_code]["000075"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000075"]);
                            $var_senati   = @array_sum($arr_values_obr[$are_code][$usr_code]["000592"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000592"]);
                            $var_sctr_sal = @array_sum($arr_values_obr[$are_code][$usr_code]["000593"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000593"]);
                            $var_sctr_pen = @array_sum($arr_values_obr[$are_code][$usr_code]["000837"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000837"]);
                            $var_tardanza = @array_sum($arr_values_obr[$are_code][$usr_code]["000945"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000945"]);
                            $var_movilidad= @array_sum($arr_values_obr[$are_code][$usr_code]["000093"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000093"]);
                            $var_viaticos = @array_sum($arr_values_obr[$are_code][$usr_code]["001161"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001161"]);
                            $var_vacaciones=@array_sum($arr_values_obr[$are_code][$usr_code]["000354"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000354"]);
                            $var_afp_comis_mixta=@array_sum($arr_values_obr[$are_code][$usr_code]["001222"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001222"]);
                            
                            $var_reintegro_inafecto = @array_sum($arr_values_obr[$are_code][$usr_code]["001176"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001176"]);
                            $var_gratif_semestral   = @array_sum($arr_values_obr[$are_code][$usr_code]["000872"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000872"]);
                            
                            
                            
                            $var_desc_prestamo = @array_sum($arr_values_obr[$are_code][$usr_code]["000514"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000514"])+
                                    @array_sum($arr_values_obr[$are_code][$usr_code]["000513"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000513"])+
                                    @array_sum($arr_values_obr[$are_code][$usr_code]["000517"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000517"])+
                                    @array_sum($arr_values_obr[$are_code][$usr_code]["001195"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001195"])
                                    ;
                            
                            $var_desc_adicional = @array_sum($arr_values_obr[$are_code][$usr_code]["001111"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001111"]);
                            $var_otros      = @array_sum($arr_values_obr[$are_code][$usr_code]["001168"]);
                            $var_pagos_no_planilla = @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000052"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["000056"]) + @array_sum($arr_values_obr_otros[$are_code][$usr_code]["001168"]);
                            $var_otros      = $var_otros + $var_pagos_no_planilla;
                        }else{ 
                            // EMPLEADO Y 4TA
                            $var_planilla = $var_planilla
                                    + @$arr_values[$are_code][$usr_code]["000217"]          //BASICO 1RA QUINCENA
                                    + ((@$arr_values[$are_code][$usr_code]["000052"]>0)?@$arr_values[$are_code][$usr_code]["000052"] - @$arr_values[$are_code][$usr_code]["000217"] : 0)   //BASICO 2DA QUINCENA
                            ;
                            $var_asig_fam = @$arr_values[$are_code][$usr_code]["000056"];
                            
                            $var_otros = $var_otros
                                    + @$arr_values[$are_code][$usr_code]['001159'] //OTROS 1RA QUINCENA
                                    + @$arr_values[$are_code][$usr_code]['001168']; //OTROS 2DA QUINCENA
                          
                            $var_rph      = @$arr_values[$are_code][$usr_code]['001192'];
                            $var_comedor  = @$arr_values[$are_code][$usr_code]["001166"];
                            $var_onp      = @$arr_values[$are_code][$usr_code]["000079"];
                            $var_afp_fondo= @$arr_values[$are_code][$usr_code]["000085"];
                            $var_afp_segur= @$arr_values[$are_code][$usr_code]["000084"];
                            $var_afp_comis= @$arr_values[$are_code][$usr_code]["000083"];
                            $var_cuarta   = @$arr_values[$are_code][$usr_code]["001196"];
                            $var_quinta   = @$arr_values[$are_code][$usr_code]["000131"];
                            $var_bonif    = @$arr_values[$are_code][$usr_code]["000538"];
                            $var_essalud  = @$arr_values[$are_code][$usr_code]["000075"];
                            $var_senati   = @$arr_values[$are_code][$usr_code]["000592"];
                            $var_sctr_sal = @$arr_values[$are_code][$usr_code]["000593"];
                            $var_sctr_pen = @$arr_values[$are_code][$usr_code]["000837"];
                            $var_tardanza = @$arr_values[$are_code][$usr_code]["000945"];
                            $var_movilidad= @$arr_values[$are_code][$usr_code]["000093"];
                            $var_viaticos = @$arr_values[$are_code][$usr_code]["001161"];
                            $var_reintegro_inafecto = @$arr_values[$are_code][$usr_code]["001176"];
                            $var_gratif_semestral   = @$arr_values[$are_code][$usr_code]["000872"];
                            $var_desc_prestamo      = ((@$arr_values[$are_code][$usr_code]["000514"]>0)?@$arr_values[$are_code][$usr_code]["000514"] - @$arr_values[$are_code][$usr_code]["000513"] + @$arr_values[$are_code][$usr_code]["000513"] : @$arr_values[$are_code][$usr_code]["000513"]);
                            $var_desc_adicional     = @$arr_values[$are_code][$usr_code]["001111"];
                            $var_desc_4ta           = ((@$arr_values[$are_code][$usr_code]["001229"]>0)?@$arr_values[$are_code][$usr_code]["001229"] - @$arr_values[$are_code][$usr_code]["001238"] + @$arr_values[$are_code][$usr_code]["001238"] : @$arr_values[$are_code][$usr_code]["001238"]);
                            $var_vacaciones         = @$arr_values[$are_code][$usr_code]["000354"];
                            $var_afp_comis_mixta         = @$arr_values[$are_code][$usr_code]["001222"];
                            
                        }
                        
                        

                        $arr_cc_consolidado_data[$det_data['cco_cont_code']][$det_data["planilla_id"]]['PLA'] = 
                                @$arr_cc_consolidado_data[$det_data['cco_cont_code']][$det_data["planilla_id"]]['PLA'] 
                                + $var_planilla + $var_rph 
                                + $var_asig_fam 
                                + $var_descanso_sem
                                ;
                        
                        $arr_cc_consolidado_data[$det_data['cco_cont_code']][$det_data["planilla_id"]]['OTR'] = 
                                @$arr_cc_consolidado_data[$det_data['cco_cont_code']][$det_data["planilla_id"]]['OTR'] 
                                + $var_otros
                                + $var_ext
                                + $var_ext_dob;
                        
                        $arr_area_consolidado_data[$det_data['cco_code']][$det_data["planilla_id"]]['PLA'] = 
                                @$arr_area_consolidado_data[$det_data['cco_code']][$det_data["planilla_id"]]['PLA'] 
                                + $var_planilla + $var_rph 
                                + $var_asig_fam 
                                + $var_descanso_sem
                                ;
                        
                        $arr_area_consolidado_data[$det_data['cco_code']][$det_data["planilla_id"]]['OTR'] = 
                                @$arr_area_consolidado_data[$det_data['cco_code']][$det_data["planilla_id"]]['OTR'] 
                                + $var_otros
                                + $var_ext
                                + $var_ext_dob;
                        
                        
                        
                        
              
                        
                        $body_detalle .= '<tr>';
                        $body_detalle .= "<td style='width:10px'>".$var_usr_num."</td>";
                        $arr_data[] = $var_usr_num;
                        $body_detalle .= "<td style='width:10px'>".$det_data['usr_type']."</td>";
                        $arr_data[] = $det_data['usr_type'];
                        $body_detalle .= "<td class='left' style='width:250px;'>".$det_data['usr_name']."</td>";
                        $arr_data[] = utf8_encode($det_data['usr_name']);
                        $body_detalle .= "<td class='left'>".$det_data['cco_cont_code'].'<br/>'.$det_data['cco_group']."</td>";
                        $arr_data[] = utf8_encode(substr($det_data['cco_cont_code'],0,11));
                        $arr_data[] = utf8_encode($det_data['cco_group']);
                        $body_detalle .= "<td class='left' style='width:250px;'>".$det_data['cco_descripcion']."</td>";
                        $arr_data[] = utf8_encode(substr($det_data['cco_descripcion'],0,11));
                        $arr_data[] = utf8_encode(substr($det_data['cco_descripcion'],12));
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_planilla,2)."</td>";
                        $arr_data[] = $var_planilla;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_asig_fam,2)."</td>";
                        $arr_data[] = $var_asig_fam;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_otros,2)."</td>";
                        $arr_data[] = $var_otros;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_rph,2)."</td>";
                        $arr_data[] = $var_rph;
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_ext,2)."</td>";
                        $arr_data[] = $var_ext;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_ext_dob,2)."</td>";
                        $arr_data[] = $var_ext_dob;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_descanso_sem,2)."</td>";
                        $arr_data[] = $var_descanso_sem;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_paternidad,2)."</td>";
                        $arr_data[] = $var_paternidad;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_comedor,2)."</td>";
                        $arr_data[] = $var_comedor;
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_onp,2)."</td>";
                        $arr_data[] = $var_onp;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_afp_fondo,2)."</td>";
                        $arr_data[] = $var_afp_fondo;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_afp_segur,2)."</td>";
                        $arr_data[] = $var_afp_segur;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_afp_comis,2)."</td>";
                        $arr_data[] = $var_afp_comis;
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_cuarta,2)."</td>";
                        $arr_data[] = $var_cuarta;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_quinta,2)."</td>";
                        $arr_data[] = $var_quinta;
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_bonif,2)."</td>";
                        $arr_data[] = $var_bonif;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_essalud,2)."</td>";
                        $arr_data[] = $var_essalud;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_senati,2)."</td>";
                        $arr_data[] = $var_senati;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_sctr_sal,2)."</td>";
                        $arr_data[] = $var_sctr_sal;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_sctr_pen,2)."</td>";
                        $arr_data[] = $var_sctr_pen;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_tardanza,2)."</td>";
                        $arr_data[] = $var_tardanza;
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_movilidad,2)."</td>";
                        $arr_data[] = $var_movilidad;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_viaticos,2)."</td>";
                        $arr_data[] = $var_viaticos;
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_reintegro_inafecto,2)."</td>";
                        $arr_data[] = $var_reintegro_inafecto;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_gratif_semestral,2)."</td>";
                        $arr_data[] = $var_gratif_semestral;
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_desc_prestamo,2)."</td>";
                        $arr_data[] = $var_desc_prestamo;
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_desc_adicional,2)."</td>";
                        $arr_data[] = $var_desc_adicional;
                        
                        if($var_desc_adicional>0){
                            $arr_montos_desc_adicional[$det_data['usr_name']] = $var_desc_adicional;
                        //$arr_concepto_consolidado_data[27]['DESC_ADICIONAL'][$det_data['usr_name']] = array('type'=>'H','val'=>$var_desc_adicional);
                        }
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_desc_4ta,2)."</td>";
                        $arr_data[] = $var_desc_4ta;
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_vacaciones,2)."</td>";
                        $arr_data[] = $var_vacaciones;
                        
                        $body_detalle .= "<td class='right' style='width:250px;'>".number_format($var_afp_comis_mixta,2)."</td>";
                        $arr_data[] = $var_afp_comis_mixta;
                        
                        
                        $body_detalle .= '</tr>';

                        $var_usr_num++;
                        
                        
                        array_push($arr_export_detalle,$arr_data);
            
                   }
               }  
           }
            
           $var_export = array('columns' => $arr_columns_detalle, 'rows' => $arr_export_detalle);
           $this->session->set_userdata('data_consolidado_detalle', $var_export);

           
          
           /*
            * TOTALIZAR DETALLE 
            */
           
          $var_num_cols = 30; 
          
           for($i=1;$i<=$var_num_cols;$i++){
               $var = 'det_'.$i;
               $$var= 0;
           }
           
           $var_left = 6;
           
           if(count($arr_export_detalle)>0){
               foreach ($arr_export_detalle as $k => $v) {
                   for($i=1;$i<=$var_num_cols;$i++){
                       $var = 'det_'.$i;
                       $$var= $$var + $v[$var_left+$i];
                   }
               }
           }
           
           $var_total_detalle = '';
           $var_total_detalle .= '<tr>';
           $var_total_detalle .= "<td class='val_shadow right'colspan='5' >TOTALES</td>";
           
           for($i=1;$i<=$var_num_cols;$i++){
                $var = 'det_'.$i;
                $var_total_detalle .= "<td class='val_shadow right' >".number_format($$var,2)."</td>";
           }

           $var_total_detalle .= '</tr>';
           
           
           
           
           
     
           
               
           // CARGANDO DATOS PARA PAGO POR CONCEPTOS
           
           foreach ($arr_cco_group as $key => $value){
               $var_con = 0;
               $var_con = @$arr_cc_consolidado_data[$key]['01']['PLA']+@$arr_cc_consolidado_data[$key]['02']['PLA']+@$arr_cc_consolidado_data[$key]['04']['PLA'];
               $arr_concepto_consolidado_data[1]['SUELDO'][$value] = array('type'=>'D','val'=>$var_con);

           }
           
           foreach ($arr_montos_essalud as $key => $value_type_essalud){
               foreach ($value_type_essalud as $key_essalud => $value_essalud) {
                   $arr_concepto_consolidado_data[17][$key][$key_essalud] = array('type'=>'D','val'=>$value_essalud);
               }
           }
           
            foreach ($arr_montos_desc_adicional as $key_desc_adicional => $value_desc_adicional) {
                $arr_concepto_consolidado_data[27]['DESC_ADICIONAL'][$key_desc_adicional] = array('type'=>'H','val'=>$value_desc_adicional);
            }
            
           foreach ($arr_montos_afp as $key => $value_type_afp){
               foreach ($value_type_afp as $key_afp => $value_afp) {
                   $arr_concepto_consolidado_data[30][$key][$key_afp] = array('type'=>'H','val'=>$value_afp);
               }
           }
           

           
           $arr_concepto_consolidado_data[29]['VACACIONES'][''] = array('type'=>'D','val'=>$det_29);
           $arr_concepto_consolidado_data[10]['ONP'][''] = array('type'=>'H','val'=>$det_10);
           $arr_concepto_consolidado_data[15]['RENTA_5TA'][''] = array('type'=>'H','val'=>$det_15);
           $arr_concepto_consolidado_data[18]['SENATI'][''] = array('type'=>'H','val'=>$det_18);
           $arr_concepto_consolidado_data[17]['ESSALUD'][''] = array('type'=>'H','val'=>$det_17);
           $arr_concepto_consolidado_data[9]['ALIMENTACION'][''] = array('type'=>'H','val'=>$det_9);
           $arr_concepto_consolidado_data[21]['TARDANZA'][''] = array('type'=>'H','val'=>$det_21);
           
           
           
           
           
           
           
           
           
           /*
           $arr_concepto_consolidado_data[100]['HABITAT']['AP_OBLIG'] = array('type'=>'H','val'=>$det_15);
           $arr_concepto_consolidado_data[100]['HABITAT']['COMISION'] = array('type'=>'H','val'=>$det_15);
           $arr_concepto_consolidado_data[100]['HABITAT']['PRIMA'] = array('type'=>'H','val'=>$det_15);
           */
           
           
           
           
           $body_detalle = $var_total_detalle.$body_detalle.$var_total_detalle;
            
            
           
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            

            // CONSOLIDADO POR CENTRO DE COSTO
            
            $arr_export_cc = array();
            $arr_costo_cc = array();
            $arr_columns_cc = array();
            $arr_columns_cc[]['STRING']  = 'CC';
            $arr_columns_cc[]['NUMERIC'] = 'EMP PLANILLA';
            $arr_columns_cc[]['NUMERIC'] = 'EMP OTROS';
            $arr_columns_cc[]['NUMERIC'] = 'EMP CARGA SOCIAL';
            $arr_columns_cc[]['NUMERIC'] = 'TOTAL';
            $arr_columns_cc[]['NUMERIC'] = 'OBR PLANILLA';
            $arr_columns_cc[]['NUMERIC'] = 'OBR OTROS';
            $arr_columns_cc[]['NUMERIC'] = 'OBR CARGA SOCIAL';
            $arr_columns_cc[]['NUMERIC'] = 'TOTAL';
            $arr_columns_cc[]['NUMERIC'] = '4TA PLANILLA';
            $arr_columns_cc[]['NUMERIC'] = 'TOTAL';
            $arr_columns_cc[]['NUMERIC'] = 'TOTAL GENERAL';
            
            $body_cc = "";
            foreach ($arr_cco_group as $key => $value) {
                $arr_data = array();
                
                $var_cc_emp_planilla    = @$arr_cc_consolidado_data[$key]['01']['PLA'];
                $var_cc_emp_otros       = @$arr_cc_consolidado_data[$key]['01']['OTR'];
                $var_cc_emp_cargasocial = $var_cc_emp_planilla * $var_carga_social;
                $var_cc_emp_total       = $var_cc_emp_planilla + $var_cc_emp_otros + $var_cc_emp_cargasocial;
                
                $var_cc_obr_planilla    = @$arr_cc_consolidado_data[$key]['02']['PLA'];
                $var_cc_obr_otros       = @$arr_cc_consolidado_data[$key]['02']['OTR'];
                $var_cc_obr_cargasocial = $var_cc_obr_planilla * $var_carga_social;
                $var_cc_obr_total       = $var_cc_obr_planilla + $var_cc_obr_otros + $var_cc_obr_cargasocial;
                
                $var_cc_4ta_planilla    = @$arr_cc_consolidado_data[$key]['04']['PLA'];
                $var_cc_4ta_total       = $var_cc_4ta_planilla;
                
                $var_cc_total           = $var_cc_emp_total + $var_cc_obr_total + $var_cc_4ta_total;
                
                
                $body_cc .= '<tr>';
                $body_cc .= '<td>'.$value.'</td>';
                $arr_data[] = $value;
 
                //EMPLEADOS
                $body_cc .= '<td class="right">'.  number_format($var_cc_emp_planilla,2).'</td>'; //PLANILLA
                $arr_data[] = $var_cc_emp_planilla;
                $body_cc .= '<td class="right">'.  number_format($var_cc_emp_otros,2).'</td>'; //OTROS
                $arr_data[] = $var_cc_emp_otros;
                $body_cc .= '<td class="right">'.  number_format($var_cc_emp_cargasocial,2).'</td>'; //CARGA SOCIAL
                $arr_data[] = $var_cc_emp_cargasocial;
                $body_cc .= '<td class="val_subtotal">'.  number_format($var_cc_emp_total ,2).'</td>';  //TOTAL
                $arr_data[] = $var_cc_emp_total;
                
                //OBREROS
                $body_cc .= '<td class="right">'.  number_format($var_cc_obr_planilla,2).'</td>'; //PLANILLA
                $arr_data[] = $var_cc_obr_planilla;
                $body_cc .= '<td class="right">'.  number_format($var_cc_obr_otros,2).'</td>'; //OTROS
                $arr_data[] = $var_cc_obr_otros;
                $body_cc .= '<td class="right">'.  number_format($var_cc_obr_cargasocial,2).'</td>'; //CARGA SOCIAL
                $arr_data[] = $var_cc_obr_cargasocial;
                $body_cc .= '<td class="val_subtotal">'.  number_format($var_cc_obr_total ,2).'</td>'; //TOTAL
                $arr_data[] = $var_cc_obr_total;
                
                //4TA
                $body_cc .= '<td class="right">'.  number_format($var_cc_4ta_planilla,2).'</td>'; //PLANILLA
                $arr_data[] = $var_cc_4ta_planilla;
                $body_cc .= '<td class="val_subtotal">'.  number_format($var_cc_4ta_total,2).'</td>'; //TOTAL
                $arr_data[] = $var_cc_4ta_total;
                
                
                $body_cc .= '<td class="val_shadow right">'.  number_format($var_cc_total,2).'</td>'; //TOTAL GENERAL
                $arr_data[] = $var_cc_total;
                
                $body_cc .= '</tr>';
                
                
                $arr_costo_cc[$key] = array(
                    $var_cc_emp_planilla,$var_cc_emp_otros,$var_cc_emp_cargasocial,$var_cc_emp_total,
                    $var_cc_obr_planilla,$var_cc_obr_otros,$var_cc_obr_cargasocial,$var_cc_obr_total,
                    $var_cc_4ta_planilla,$var_cc_4ta_total,
                    $var_cc_total);
                
                array_push($arr_export_cc,$arr_data);
            }
            
            $var_export = array('columns' => $arr_columns_cc , 'rows' => $arr_export_cc);
            $this->session->set_userdata('data_consolidado_cc', $var_export);
            
            $cc_01 = 0; $cc_05 = 0; $cc_09 = 0;
            $cc_02 = 0; $cc_06 = 0; $cc_10 = 0;
            $cc_03 = 0; $cc_07 = 0; $cc_11 = 0;
            $cc_04 = 0; $cc_08 = 0;
            
            if(count($arr_costo_cc)>0){
                foreach ($arr_costo_cc as $k => $v) {
                    $cc_01 = $cc_01 + $v[0]; $cc_05 = $cc_05 + $v[4]; $cc_09 = $cc_09 + $v[8];
                    $cc_02 = $cc_02 + $v[1]; $cc_06 = $cc_06 + $v[5]; $cc_10 = $cc_10 + $v[9];
                    $cc_03 = $cc_03 + $v[2]; $cc_07 = $cc_07 + $v[6]; $cc_11 = $cc_11 + $v[10];
                    $cc_04 = $cc_04 + $v[3]; $cc_08 = $cc_08 + $v[7];
                }
            }
                   
            $var_total_cc ='<tr>';
            $var_total_cc .= '<td class="val_shadow right">TOTALES</td>';
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_01,2).'</td>';
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_02,2).'</td>';
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_03,2).'</td>';
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_04,2).'</td>';
            
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_05,2).'</td>';
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_06,2).'</td>';
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_07,2).'</td>';
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_08,2).'</td>';
            
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_09,2).'</td>';
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_10,2).'</td>';
            
            $var_total_cc .= '<td class="val_shadow right">'.  number_format($cc_11,2).'</td>';
            $var_total_cc .='</tr>';
            
            $body_cc = $var_total_cc.$body_cc.$var_total_cc;
            
            
            
            
            
            
            
            
            
            
            
            // CONSOLIDADO POR AREA
            
            $arr_export_area = array();
            $arr_costo_area = array();
            $arr_columns_area = array();
            $arr_columns_area[]['STRING']  = 'AREA';
            $arr_columns_area[]['NUMERIC'] = 'EMP PLANILLA';
            $arr_columns_area[]['NUMERIC'] = 'EMP OTROS';
            $arr_columns_area[]['NUMERIC'] = 'EMP CARGA SOCIAL';
            $arr_columns_area[]['NUMERIC'] = 'TOTAL';
            $arr_columns_area[]['NUMERIC'] = 'OBR PLANILLA';
            $arr_columns_area[]['NUMERIC'] = 'OBR OTROS';
            $arr_columns_area[]['NUMERIC'] = 'OBR CARGA SOCIAL';
            $arr_columns_area[]['NUMERIC'] = 'TOTAL';
            $arr_columns_area[]['NUMERIC'] = '4TA PLANILLA';
            $arr_columns_area[]['NUMERIC'] = 'TOTAL';
            $arr_columns_area[]['NUMERIC'] = 'TOTAL GENERAL';
            
            $body_area = "";
            foreach ($arr_cco as $key => $value) {
                
                $arr_data = array();
                
                $var_area_emp_planilla    = @$arr_area_consolidado_data[$key]['01']['PLA'];
                $var_area_emp_otros       = @$arr_area_consolidado_data[$key]['01']['OTR'];
                $var_area_emp_cargasocial = $var_area_emp_planilla * $var_carga_social;
                $var_area_emp_total       = $var_area_emp_planilla + $var_area_emp_otros + $var_area_emp_cargasocial;
                
                $var_area_obr_planilla    = @$arr_area_consolidado_data[$key]['02']['PLA'];
                $var_area_obr_otros       = @$arr_area_consolidado_data[$key]['02']['OTR'];
                $var_area_obr_cargasocial = $var_area_obr_planilla * $var_carga_social;
                $var_area_obr_total       = $var_area_obr_planilla + $var_area_obr_otros + $var_area_obr_cargasocial;
                
                $var_area_4ta_planilla    = @$arr_area_consolidado_data[$key]['04']['PLA'];
                $var_area_4ta_total       = @$var_area_4ta_planilla;
                
                $var_area_total           = $var_area_emp_total + $var_area_obr_total + $var_area_4ta_total;
                
                
                $body_area .= '<tr>';
                $body_area .= '<td>'.$value.'</td>';
                $arr_data[] = utf8_encode($value);
                $body_area .= '<td class="right">'.  number_format($var_area_emp_planilla,2).'</td>';
                $arr_data[] = $var_area_emp_planilla;
                $body_area .= '<td class="right">'.  number_format($var_area_emp_otros,2).'</td>';
                $arr_data[] = $var_area_emp_otros;
                $body_area .= '<td class="right">'.  number_format($var_area_emp_cargasocial,2).'</td>';
                $arr_data[] = $var_area_emp_cargasocial;
                $body_area .= '<td class="val_subtotal">'.  number_format($var_area_emp_total,2).'</td>';
                $arr_data[] = $var_area_emp_total;
                
                $body_area .= '<td class="right">'.  number_format($var_area_obr_planilla,2).'</td>';
                $arr_data[] = $var_area_obr_planilla;
                $body_area .= '<td class="right">'.  number_format($var_area_obr_otros,2).'</td>';
                $arr_data[] = $var_area_obr_otros;
                $body_area .= '<td class="right">'.  number_format($var_area_obr_cargasocial,2).'</td>';
                $arr_data[] = $var_area_obr_cargasocial;
                $body_area .= '<td class="val_subtotal">'.  number_format($var_area_obr_total,2).'</td>';
                $arr_data[] = $var_area_obr_total;
                
                $body_area .= '<td class="right">'.  number_format($var_area_4ta_planilla,2).'</td>';
                $arr_data[] = $var_area_4ta_planilla;
                $body_area .= '<td class="val_subtotal">'.  number_format($var_area_4ta_total,2).'</td>';
                $arr_data[] = $var_area_4ta_total;
                
                $body_area .= '<td class="val_shadow right">'.  number_format($var_area_total,2).'</td>';
                $arr_data[] = $var_area_total;
                
                $body_area .= '</tr>';
                
                $arr_costo_area[$key] = array(
                    $var_area_emp_planilla,$var_area_emp_otros,$var_area_emp_cargasocial,$var_area_emp_total,
                    $var_area_obr_planilla,$var_area_obr_otros,$var_area_obr_cargasocial,$var_area_obr_total,
                    $var_area_4ta_planilla,$var_area_4ta_total,
                    $var_area_total);
                
                array_push($arr_export_area,$arr_data);
                
            }
            
            $var_export = array('columns' => $arr_columns_area , 'rows' => $arr_export_area);
            $this->session->set_userdata('data_consolidado_area', $var_export);
            
            $area_01 = 0; $area_05 = 0; $area_09 = 0;
            $area_02 = 0; $area_06 = 0; $area_10 = 0;
            $area_03 = 0; $area_07 = 0; $area_11 = 0;
            $area_04 = 0; $area_08 = 0;
            
            if(count($arr_costo_area)>0){
                foreach ($arr_costo_area as $k => $v) {
                    $area_01 = $area_01 + $v[0]; $area_05 = $area_05 + $v[4]; $area_09 = $area_09 + $v[8];
                    $area_02 = $area_02 + $v[1]; $area_06 = $area_06 + $v[5]; $area_10 = $area_10 + $v[9];
                    $area_03 = $area_03 + $v[2]; $area_07 = $area_07 + $v[6]; $area_11 = $area_11 + $v[10];
                    $area_04 = $area_04 + $v[3]; $area_08 = $area_08 + $v[7];
                }
            }
            
            $var_total_area ='<tr>';
            $var_total_area .= '<td class="val_shadow right">TOTALES</td>';
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_01,2).'</td>';
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_02,2).'</td>';
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_03,2).'</td>';
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_04,2).'</td>';
            
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_05,2).'</td>';
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_06,2).'</td>';
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_07,2).'</td>';
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_08,2).'</td>';
            
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_09,2).'</td>';
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_10,2).'</td>';
            
            $var_total_area .= '<td class="val_shadow right">'.  number_format($area_11,2).'</td>';
            $var_total_area .='</tr>';
            
            $body_area = $var_total_area.$body_area.$var_total_area;
            
            
            
            
            
            
            
            
            
            
            
            
            
            // CONSOLIDADO POR CONCEPTO
            $arr_export_concepto = array();
            $arr_columns_concepto = array();
            $arr_columns_concepto[]['STRING'] = 'CONCEPTO';
            $arr_columns_concepto[]['STRING'] = 'DESCRIPCION';
            $arr_columns_concepto[]['NUMERIC'] = 'DEBE';
            $arr_columns_concepto[]['NUMERIC'] = 'HABER';

            
            $var_deb_total = 0;
            $var_hab_total = 0;
            
            $body_concepto = "";
            foreach ($arr_concepto_consolidado_data as $key => $data) {
                foreach ($data as $k => $v) {
                    foreach ($v as $y => $z) {
                        
                        if($z['val']==0){
                            continue; 
                        }
                    
                        
                        
                    $arr_data = array();
                    
                    $body_concepto .= '<tr style="border-bottom:1px solid red">';
                    $body_concepto .= '<td>'.$k.'</td>';
                    $arr_data[] = $k;
                    
                    $body_concepto .= '<td class="left">'.  $y.'</td>';
                    $arr_data[] = utf8_encode($y);
                    

                    $var_deb = ($z['type']=='D')?$z['val']:0;
                    $var_hab = ($z['type']=='H')?$z['val']:0;
                    
                    $var_deb_total = $var_deb_total + $var_deb;
                    $var_hab_total = $var_hab_total + $var_hab;
                    
                    if($var_deb>0){
                        $body_concepto .= '<td class="val_shadow right">'.number_format($var_deb,2).'</td>';
                    }else{
                        $body_concepto .= '<td class="val_shadow center">-</td>';
                    }
                    $arr_data[] = $var_deb;
                    
                    
                    if($var_hab>0){
                        $body_concepto .= '<td class="val_shadow right">'.number_format($var_hab,2).'</td>';
                    }else{
                        $body_concepto .= '<td class="val_shadow center">-</td>';
                    }
                    $arr_data[] = $var_hab;
                    
                    $body_concepto .= '</tr>';
                    
                    array_push($arr_export_concepto,$arr_data);
                    
                    }
                }
            }
            $var_export = array('columns' => $arr_columns_concepto, 'rows' => $arr_export_concepto);
            $this->session->set_userdata('data_consolidado_concepto', $var_export);
            $body_concepto .='<tr>';
            $body_concepto .= '<td class="val_shadow right" colspan="2">TOTALES</td>';
            $body_concepto .= '<td class="val_shadow right">'.  number_format($var_deb_total,2).'</td>';
            $body_concepto .= '<td class="val_shadow right">'.  number_format($var_hab_total,2).'</td>';
            $body_concepto .='</tr>'; 
        }

        $view_export = 0 + count($arr_cco);
        $data['view_export']   = $view_export;
        $data['body_cc']       = $body_cc;
        $data['body_area']     = $body_area;
        $data['body_concepto'] = $body_concepto;
        $data['body_detalle']  = $body_detalle;
  
        $data['cbo_anio']   = $cbo_anio;
        $data['cbo_mes']    = $cbo_mes;
        $data['cbo_area']   = $cbo_area;
        $data['cbo_group']  = $cbo_group;
        $data['txt_report'] = $var_txt_report;
        $data['checks']     = $var_checks;
     
        $data['var_carga_social']     = $var_carga_social;        
        $this->load->view(scire . "scire_costos_resumen", $data);
    }
    
    public function costos_area(){
        $var_anio   = $this->input->get_post('cbo_anio');
        $var_mes    = $this->input->get_post('cbo_mes');
        $var_group  = $this->input->get_post('cbo_group');
        $var_area   = $this->input->get_post('cbo_area');
        $var_chk_type   = $this->input->get_post('chk_type');
        $var_checks = "";
        foreach ($this->arr_usr_types as $key => $value) {
            $checked = "";
            if (@in_array($key, $var_chk_type)) {
                $checked = "checked";
            }
            $var_checks .= "<label><input type='checkbox' id='chk_".$key."' name='chk_type[]' value='".$key."' ".$checked." />".$value."</label>";
        }
        //@$usr_type_work = "'".implode("','",$var_chk_type)."'";

        if($var_anio==""){    
            $var_anio    = date('Y');
        }
        
       /* if($var_group==""){    
            $var_area    = '';
        }
        */
        $filter  = new stdClass();
        $filter->anio = $var_anio;
        $filter->mesi = "000043";
        $cbo_anio         = form_dropdown('cbo_anio',$this->ejercicio_model->select(new stdClass(),"SELECCIONAR",""),$var_anio," size='1' id='cbo_anio' style='width:200px;' class='comboPeque' onchange='$(\"#buscar\").click()' ");               
        $cbo_mes          = form_dropdown('cbo_mes',$this->mes_model->select($filter,"SELECCIONAR",""),$var_mes," size='1' id='cbo_mes' class='comboPeque' style='width:200px;'  ");
        $arr_columns = array();
        $arr_columns[]['STRING'] = 'NRO';
        $arr_columns[]['STRING'] = 'PERSONAL';
        $arr_columns[]['STRING'] = 'TIPO';
        $arr_columns[]['STRING'] = 'NUMERO CC';
        $arr_columns[]['STRING'] = 'CENTRO DE COSTO';
        $arr_columns[]['STRING'] = 'NUMERO AREA';
        $arr_columns[]['STRING'] = 'AREA';
        $arr_columns[]['DATE'] = 'F. INGRESO';
        $arr_columns[]['DATE'] = 'T. CONTRATO';
        $arr_columns[]['STRING'] = 'CARGO';
        
        // PRESUPUESTADO
        $arr_columns[]['NUMERIC'] = 'PLANILLA';
        $arr_columns[]['NUMERIC'] = 'OTROS';
        $arr_columns[]['NUMERIC'] = 'RPH';
        $arr_columns[]['FORMULA'] = 'SUBTOTAL';
        $arr_columns[]['FORMULA'] = 'COSTA LAB';
        $arr_columns[]['FORMULA'] = 'TOTAL';
        $arr_columns[]['NUMERIC'] = 'H.N.';
        $arr_columns[]['NUMERIC'] = 'H.E.';
        $arr_columns[]['NUMERIC'] = 'H.D.';
        
        // EFECTIVO
        /*
        $arr_columns[]['NUMERIC'] = 'PLANILLA';
        $arr_columns[]['NUMERIC'] = 'OTROS';
        $arr_columns[]['NUMERIC'] = 'RPH';
        $arr_columns[]['FORMULA'] = 'SUBTOTAL';
        $arr_columns[]['FORMULA'] = 'COSTA LAB';
        $arr_columns[]['FORMULA'] = 'TOTAL';
        */
        $var_headers = '';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:2%">NRO</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:22%">PERSONAL</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:2%">TIPO</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:8%">F. INGRESO</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:8%">T. CONTRATO</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:10%">CARGO</th>';
        
        /*
         * PRESUPUESTADO
         */
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">PLANILLA</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">OTROS</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">RPH</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">SUBTOTAL</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">COSTA LAB</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">TOTAL</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">H.N.</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">H.E.</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">H.D.</th>';
        
        /*
         * EFECTIVO
         */
        
        /*
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">PLANILLA</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">OTROS</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">RPH</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">SUBTOTAL</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">COSTA LAB</th>';
        $var_headers .= '<th style="font-weight:bold;text-align:center;width:5%">TOTAL</th>';
        */
        
        $var_data = "";
        $body ="";
            $this->db->query('SET ANSI_WARNINGS ON');
            $this->db->query('SET ANSI_NULLS ON');
        if($query = $this->db->query("SP_SEL_SCIRE_PAGO_RESUMEN '".$var_mes."' , '".$var_area."' , '".$var_group."','',''")){
            $this->db->where('per_code', $var_mes);
            $this->db->from('SCIRE_DATOS');
            $conf = $this->db->get()->row();
            $var_carga_social = @$conf->per_provision;
            print_r($var_carga_social);//carga social
            $arr_cco    = array();
            $arr_cco_group = array();
            $arr_users  = array();
            $arr_values = array();
            $arr_values_obr = array();
            $arr_values_obr_otros = array();
            foreach ($query->result() as $key => $value) {
               if (!@in_array($value->planilla_id, $var_chk_type)) {
                   continue; 
               }
               $var_type = "";
               switch ($value->planilla_id) {
                   case '01':
                       $var_type = "EMP";
                       break;
                   case '05':
                       $var_type = "OBR";
                       break;
                   case '04':
                       $var_type = "4TA";
                       break;
               }
               $arr_cco[$value->cco_code] = $value->cco_descripcion;
               $arr_cco_group[$value->cco_cont_code] = $value->cco_group;
               $arr_users[$value->cco_code][$value->usr_code] = array('cco_cont_code'=>$value->cco_cont_code ,'cco_group'=>$value->cco_group, 'planilla_id' => $value->planilla_id ,'usr_type' => $var_type ,  'usr_name' => $value->usr_name,'fecha_ini_contrato' => $value->fecha_ini_contrato,'fecha_fin_contrato' => $value->fecha_fin_contrato,'usr_cargo' => $value->usr_cargo,'cco_descripcion' => $value->cco_descripcion);
               $arr_values[$value->cco_code][$value->usr_code][$value->concepto_id] = $value->valor;
               if($value->planilla_id=='05'){
                   if($value->afp_id=='99'){
                       $arr_values_obr_otros[$value->cco_code][$value->usr_code][$value->concepto_id][$value->periodo_id] = $value->valor;
                   }else{
                       $arr_values_obr[$value->cco_code][$value->usr_code][$value->concepto_id][$value->periodo_id] = $value->valor;
                   }
               }
            }
            asort($arr_cco_group);
            $arr_cco_group[''] = "TODOS";
            foreach ($arr_cco_group as $key => $value) {
                $arr_cco_group[$key] = $key.' '.$value;
            }
            $cbo_group = form_dropdown('cbo_group',$arr_cco_group,$var_group," size='1' id='cbo_group' class='comboPeque' style='width:200px;' onchange='$(\"#buscar\").click()'");
            unset($arr_cco_group['']);
            asort($arr_cco);
            $arr_cco[''] = "TODOS";
            $cbo_area = form_dropdown('cbo_area',$arr_cco,$var_area," size='1' id='cbo_area' class='comboPeque' style='width:200px;' ");
            unset($arr_cco['']);
            if(count($arr_users)>0){
                //$var_headers = $this->reports_model->get_headers($arr_columns);
               $arr_code_planilla  = array('000056');
                $arr_code_otros     = array('004');
                $arr_code_rph       = array('001192');
                $var_num = 0;
                $arr_excel = array();
                $arr_costo = array();
                foreach ($arr_cco as $cco_code => $cco_description) {
                    $var_data .= "<table border='1' style='width:100%'>";
                    $var_data .= "<tr>
                        <td colspan='6' style='width:300px;text-align:left;font-weight:bold; background-color:rgb(242,242,242)'>".$cco_description."</td>
                            <td colspan='6' style='width:300px;text-align:left;font-weight:bold; background-color:rgb(242,242,242)'>PRESUPUESTADO</td>
                                <!--td colspan='6' style='width:300px;text-align:left;font-weight:bold; background-color:rgb(242,242,242)'>EFECTIVO</td-->
                            
                        </tr>";
                    $var_data .= $var_headers;
                    $var_pre_sub_planilla = 0;
                    $var_pre_sub_otros = 0;
                    $var_pre_sub_rph = 0;
                    $var_pre_sub_subtotal = 0;
                    $var_pre_sub_costo_lab = 0;
                    $var_pre_sub_total = 0;
                    $var_sub_planilla = 0;
                    $var_sub_otros = 0;
                    $var_sub_rph = 0;
                    $var_sub_subtotal = 0;
                    $var_sub_costo_lab = 0;
                    $var_sub_total = 0;
                    $var_cantidad = count($arr_users[$cco_code]);
                    foreach ($arr_users[$cco_code] as $usr_code => $usr_data) {
                        $arr_data = array();
                        $var_num++;
                        $var_pre_planilla = 0;
                        $var_pre_otros = 0;
                        $var_pre_rph = 0;
                        $var_planilla = 0;
                        $var_otros = 0;
                        $var_rph = 0;
                       /* foreach ($arr_code_planilla as $con_code) {
                            $var_planilla = $var_planilla + @$arr_values[$cco_code][$usr_code][$con_code];
                        }*/
                        $query_ext = $this->db->query("SP_SEL_SCIRE_PAGO_FIJO '".$usr_code."' , '".$var_mes."' , '".$usr_data["planilla_id"]."'")->row();
                        switch ($usr_data["planilla_id"]) {
                            case '01':
                                $var_pre_planilla = @$query_ext->planilla + @$query_ext->asig_fam;
                                $var_pre_otros = @$query_ext->otros;
                                break;
                            case '05':
//                                $var_pre_planilla = (@$query_ext->planilla * 7 * @$query_ext->semanas) + @$query_ext->asig_fam;
//                                $var_pre_otros = @$query_ext->otros * ( 7 * @$query_ext->semanas);
                                $var_pre_planilla = @$query_ext->planilla + @$query_ext->asig_fam;
                                $var_pre_otros = @$query_ext->otrosob;
                                break;
                            case '04':
                                $var_pre_rph = @$query_ext->planilla + @$query_ext->asig_fam + @$query_ext->otros;
                                break;
                        }

                        if($usr_data["planilla_id"]=='05'){
                            $var_planilla = $var_planilla   
                                        + @array_sum($arr_values_obr[$cco_code][$usr_code]["000052"])
                                        + @array_sum($arr_values_obr[$cco_code][$usr_code]["000056"])
                                        + @array_sum($arr_values_obr[$cco_code][$usr_code]["000935"]) //DESCANDO SEMANAL
                                ;
                            $var_otros = $var_otros
                                        + @array_sum($arr_values_obr[$cco_code][$usr_code]["001168"])
                                        + @array_sum($arr_values_obr[$cco_code][$usr_code]["001159"])
                                        + @array_sum($arr_values_obr_otros[$cco_code][$usr_code]["001168"])
                                        + @array_sum($arr_values_obr_otros[$cco_code][$usr_code]["001159"])
                                        + @array_sum($arr_values_obr_otros[$cco_code][$usr_code]["000052"])
                                        + @array_sum($arr_values_obr_otros[$cco_code][$usr_code]["000056"])
                                        + @array_sum($arr_values_obr_otros[$cco_code][$usr_code]["000935"]) // DESCANSO SEMANAL
                                ;
                        }else{
                                $var_planilla = $var_planilla
                                        + @$arr_values[$cco_code][$usr_code]["000056"]          //ASIGNACION FAMILIAR
                                        + @$arr_values[$cco_code][$usr_code]["000217"]          //BASICO 1RA QUINCENA
                                        + ((@$arr_values[$cco_code][$usr_code]["000052"]>0)?@$arr_values[$cco_code][$usr_code]["000052"] - @$arr_values[$cco_code][$usr_code]["000217"] : 0)   //BASICO 2DA QUINCENA
                                ;
                                foreach ($arr_code_otros as $con_code) {
                                    $var_otros = $var_otros + @$arr_values[$cco_code][$usr_code][$con_code];
                                }

                                foreach ($arr_code_rph as $con_code) {
                                    $var_rph = $var_rph + @$arr_values[$cco_code][$usr_code][$con_code];
                                }
                        }
                        $var_pre_subtotal   = $var_pre_planilla + $var_pre_otros + $var_pre_rph;
                        $var_pre_costo_lab  = $var_pre_planilla * $var_carga_social;
                        $var_pre_total      = $var_pre_subtotal + $var_pre_costo_lab;
                        $var_subtotal   = $var_planilla + $var_otros + $var_rph;
                        $var_costo_lab  = $var_planilla * $var_carga_social;
                        $var_total      = $var_subtotal + $var_costo_lab;
                        $rpt_usr_name           = $usr_data["usr_name"];
                        $rpt_usr_type           = $usr_data["usr_type"];
                        $rpt_fecha_ini_contrato = ($usr_data["fecha_ini_contrato"]=='01/01/1900')?'--':$usr_data["fecha_ini_contrato"];
                        $rpt_fecha_fin_contrato = ($usr_data["fecha_fin_contrato"]=='01/01/1900')?'--':$usr_data["fecha_fin_contrato"];
                        $rpt_cargo              = $usr_data["usr_cargo"];
                        $rpt_cco_cont_code      = $usr_data["cco_cont_code"];
                        $rpt_cco_group          = $usr_data["cco_group"];
                        
                        /*
                         * PRESUPUESTADO
                         */
                        $rpt_pre_planilla           = $var_pre_planilla;
                        $var_pre_sub_planilla = $var_pre_sub_planilla + $var_pre_planilla;
                        $rpt_pre_otros              = $var_pre_otros;
                        $var_pre_sub_otros = $var_pre_sub_otros + $var_pre_otros;
                        $rpt_pre_rph                = $var_pre_rph;
                        $var_pre_sub_rph = $var_pre_sub_rph + $var_pre_rph;
                        $rpt_pre_subtotal           = $var_pre_subtotal;
                        $var_pre_sub_subtotal = $var_pre_sub_subtotal + $var_pre_subtotal;
                        $rpt_pre_costo_lab          =$var_pre_costo_lab;
                        $var_pre_sub_costo_lab = $var_pre_sub_costo_lab + $var_pre_costo_lab;
                        $rpt_pre_total              = $var_pre_total;
                        $var_pre_sub_total = $var_pre_sub_total + $var_pre_total;
                        
                        /*
                         * EFECTIVO
                         */
                        /*
                        $rpt_planilla           = $var_planilla;
                        $var_sub_planilla = $var_sub_planilla + $var_planilla;
                        $rpt_otros              = $var_otros;
                        $var_sub_otros = $var_sub_otros + $var_otros;
                        $rpt_rph                = $var_rph;
                        $var_sub_rph = $var_sub_rph + $var_rph;
                        $rpt_subtotal           = $var_subtotal;
                        $var_sub_subtotal = $var_sub_subtotal + $var_subtotal;
                        $rpt_costo_lab          =$var_costo_lab;
                        $var_sub_costo_lab = $var_sub_costo_lab + $var_costo_lab;
                        $rpt_total              = $var_total;
                        $var_sub_total = $var_sub_total + $var_total;
                         
                         */

                        $var_data .= "<tr>";
                        $var_data .= "<td>". $var_num."</td>";
                        $arr_data[] = $var_num;
                        $var_data .= "<td style='text-align:left;'>".$rpt_usr_name."</td>";
                        $arr_data[] = utf8_encode($rpt_usr_name);
                        $var_data .= "<td style='text-align:center;'>".$rpt_usr_type."</td>";
                        $arr_data[] = utf8_encode($rpt_usr_type);
                        $arr_data[] = $rpt_cco_cont_code;
                        $arr_data[] = $rpt_cco_group;
                        $arr_data[] = substr($cco_description,0,11);
                        $arr_data[] = utf8_encode(substr($cco_description,12));
                        $var_data .= "<td style='text-align:center;'>".$rpt_fecha_ini_contrato ."</td>";
                        $arr_data[] = $rpt_fecha_ini_contrato ;
                        $var_data .= "<td style='text-align:center;'>". $rpt_fecha_fin_contrato."</td>";
                        $arr_data[] = $rpt_fecha_fin_contrato;
                        $var_data .= "<td style='text-align:left;'>".$rpt_cargo ."</td>";
                        $arr_data[] = utf8_encode($rpt_cargo);

                        /*
                         * PRESUPUESTADO
                         */

                        $var_data .= "<td style='text-align:right;'>". number_format($rpt_pre_planilla,2) ."</td>";
                        $arr_data[] = $rpt_pre_planilla ;

                        $var_data .= "<td style='text-align:right;'>". number_format($rpt_pre_otros,2)."</td>";
                        $arr_data[] = $rpt_pre_otros;

                        $var_data .= "<td style='text-align:right;'>".number_format($rpt_pre_rph,2)."</td>";
                        $arr_data[] = $rpt_pre_rph;
                        
                        $var_row_excel = $var_num+ 6;
                        
                        $var_data .= "<td style='text-align:right;'>". number_format($rpt_pre_subtotal,2)."</td>";
                        $arr_data[] = "=K".$var_row_excel."+L".$var_row_excel."+M".$var_row_excel;

                        $var_data .= "<td style='text-align:right;'>".number_format($rpt_pre_costo_lab,2) ."</td>";
                        $arr_data[] = "=K".$var_row_excel."*".$var_carga_social;

                        $var_data .= "<td style='text-align:right;'>".number_format($rpt_pre_total,2)."</td>";
                        $arr_data[] = "=N".$var_row_excel."+O".$var_row_excel;
                        
                        $var_data .= "<td style='text-align:right;'>".number_format($rpt_pre_total/240,2)."</td>";
                        $arr_data[] = $rpt_pre_total/240;    
                        
                        $var_data .= "<td style='text-align:right;'>".number_format($rpt_pre_subtotal/240,2)."</td>";
                        $arr_data[] = $rpt_pre_subtotal/240;           
                        
                        $var_data .= "<td style='text-align:right;'>".number_format($rpt_pre_subtotal/120,2)."</td>";
                        $arr_data[] = $rpt_pre_subtotal/120;                                 

                        /*
                         * EFECTIVO
                         */
                        
                        /*
                        $var_data .= "<td style='text-align:right;'>". number_format($rpt_planilla,2) ."</td>";
                        $arr_data[] = $rpt_planilla ;

                        $var_data .= "<td style='text-align:right;'>". number_format($rpt_otros,2)."</td>";
                        $arr_data[] = $rpt_otros;

                        $var_data .= "<td style='text-align:right;'>".number_format($rpt_rph,2)."</td>";
                        $arr_data[] = $rpt_rph;

                        
                        $var_data .= "<td style='text-align:right;'>". number_format($rpt_subtotal,2)."</td>";
                        $arr_data[] = "=Q".$var_row_excel."+R".$var_row_excel."+S".$var_row_excel;

                        $var_data .= "<td style='text-align:right;'>".number_format($rpt_costo_lab,2) ."</td>";
                        $arr_data[] = "=Q".$var_row_excel."*".$var_carga_social;

                        $var_data .= "<td style='text-align:right;'>".number_format($rpt_total,2)."</td>";
                        $arr_data[] = "=T".$var_row_excel."+U".$var_row_excel;
                        
                        */
                        $var_data .= "</tr>";      
                        array_push($arr_excel,$arr_data);
                    }
                    $var_export = array('columns' => $arr_columns , 'rows' => $arr_excel);
                    $this->session->set_userdata('data_scire_costos_area', $var_export);
                    
                    /*
                     * PRESUPUESTADO
                     */
                    $var_data .= "<tr style='background-color:rgb(242,242,242);'>";
                    $var_data .= "<td style='font-weight:bold;' colspan='6' align='right'>TOTALES </td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_pre_sub_planilla , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_pre_sub_otros , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_pre_sub_rph , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_pre_sub_subtotal , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_pre_sub_costo_lab , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_pre_sub_total , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_pre_sub_total/(240*$var_cantidad) , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_pre_sub_subtotal/(240*$var_cantidad) , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_pre_sub_subtotal/(120*$var_cantidad) , 2)."</td>";
                    /*
                     * EFECTIVO
                     */
                    
             
                  /*
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_sub_planilla , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_sub_otros , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_sub_rph , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_sub_subtotal , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_sub_costo_lab , 2)."</td>";
                    $var_data .= "<td style='font-weight:bold;text-align:right;'>". number_format($var_sub_total , 2)."</td>";
                    
                    
                    */
                    $var_data .= "</tr>";
                    $var_data .= "</table>";
                    $var_data .= "<br/>";
                    $arr_costo[$cco_code] = array($var_pre_sub_subtotal,$var_pre_sub_costo_lab,$var_pre_sub_total,$var_sub_subtotal,$var_sub_costo_lab,$var_sub_total,$var_sub_total/240,$var_pre_sub_subtotal/240,$var_pre_sub_subtotal/120);
                }
        $rpt_pre_subtotal = 0;
        $rpt_pre_costo_lab = 0;
        $rpt_pre_total = 0;
        $rpt_subtotal = 0;
        $rpt_costo_lab = 0;
        $rpt_total = 0;
        if(count($arr_costo)>0){
        foreach ($arr_costo as $key => $value) {
            $rpt_pre_subtotal   = $rpt_pre_subtotal + $value[0];
            $rpt_pre_costo_lab  = $rpt_pre_costo_lab + $value[1];
            $rpt_pre_total      = $rpt_pre_total + $value[2];
            $rpt_subtotal   = $rpt_subtotal + $value[3];
            $rpt_costo_lab  = $rpt_costo_lab + $value[4];
            $rpt_total      = $rpt_total + $value[5];
            
        }
        }
        $var_total = "  
            
                        <table border='1' align='right'>
                            <tr style='background-color:rgb(242,242,242);'>
                                <th style='width:85%;background-color:rgb(242,242,242);font-weight:bold'></th>
                                <th>SUBTOTAL</th>
                                <th>COSTO LAB</th>
                                <th>TOTAL</th>
                                <th>H.N.</th>
                                <th>H.E.</th>
                                <th>H.D.</th>
                            </tr>
                            <tr style='background-color:rgb(242,242,242);'>
                                <td style='text-align:right;width:85%;font-weight:bold;'>PRESUPUESTADO</td>
                                <td style='text-align:right;width:5%'>".number_format($rpt_pre_subtotal,2)."</td>
                                <td style='text-align:right;width:5%'>".number_format($rpt_pre_costo_lab,2)."</td>
                                <td style='text-align:right;width:5%'>".number_format($rpt_pre_total,2)."</td>
                                <td style='text-align:right;width:5%'>".number_format($rpt_pre_total/(240*$var_num),2)."</td>
                                <td style='text-align:right;width:5%'>".number_format($rpt_pre_subtotal/(240*$var_num),2)."</td>
                                <td style='text-align:right;width:5%'>".number_format($rpt_pre_subtotal/(120*$var_num),2)."</td>
                            </tr>
                            <!--tr style='background-color:rgb(242,242,242);'>
                                <td style='text-align:right;width:85%;font-weight:bold;'>EFECTIVO</td>
                                <td style='text-align:right;width:5%'>".number_format($rpt_subtotal,2)."</td>
                                <td style='text-align:right;width:5%'>".number_format($rpt_costo_lab,2)."</td>
                                <td style='text-align:right;width:5%'>".number_format($rpt_total,2)."</td>
                            </tr-->
                        </table>";
        //echo number_format($rpt_subtotal,2)." ".number_format($rpt_costo_lab,2)." ".number_format($rpt_total,2);
        $body=  $var_total."<br/><br/>".$var_data.'<br/><br/>'.$var_total;
            }    
        }else{
            show_error('Error!');
            echo "Error: Ocurrió un error en la consulta.";
        }
        $view_export = 0 + count($arr_cco);
        $data['view_export']     = $view_export;
        $data['body']       = $body;
        $data['cbo_anio']   = $cbo_anio;
        $data['cbo_mes']    = $cbo_mes;
        $data['cbo_area']   = $cbo_area;
        $data['cbo_group']   = $cbo_group;
        $data['checks']   = $var_checks;
        $data['var_carga_social'] = @$var_carga_social;     
        $this->load->view(scire . "scire_costos_area", $data);
    }
    
    public function asistencia_excel($tipo) {
        if($tipo == 'C') {
            $arr_columns = array();
            $arr_columns[0]['STRING'] = 'TIPO';
            $arr_columns[1]['STRING'] = 'CCOSTO';
            $arr_columns[2]['STRING'] = 'DNI';
            $arr_columns[3]['STRING'] = 'NOMBRE';
            $arr_columns[4]['STRING'] = 'CARGO';
            $arr_columns[5]['STRING'] = 'TARDANZA';
            $arr_columns[6]['STRING'] = 'HORAST';
            $arr_columns[7]['STRING'] = 'HORASE';
            $arr_data = $this->session->userdata('data_consolidado');
            $this->reports_model->rpt_general('rpt_asistencia', 'Reporte de Horas Trabajadas', $arr_columns, $arr_data);
        }
        if($tipo == 'D') {
            $arr_columns = array();
            $arr_columns[0]['STRING'] = 'TIPO';
            $arr_columns[1]['STRING'] = 'DNI';
            $arr_columns[2]['STRING'] = 'NOMBRE';
            $arr_columns[3]['STRING'] = 'CARGO';
            $arr_columns[4]['STRING'] = 'HORARIO';
            $arr_columns[5]['STRING'] = 'AREA';
            $arr_columns[6]['STRING'] = 'CCOSTO';
            $arr_columns[7]['STRING'] = 'FECHA';
            $arr_columns[8]['STRING'] = 'TARDANZA';
            $arr_columns[9]['STRING'] = 'HORAST';
            $arr_columns[10]['STRING'] = 'HORASE';
            $arr_columns[11]['STRING'] = 'HORA INGRESO';
            $arr_columns[12]['STRING'] = 'HORA SALIDA';
            $arr_columns[13]['STRING'] = 'SALIDA REF';
            $arr_columns[14]['STRING'] = 'INGRESO REF';
            $arr_data = $this->session->userdata('data_detallado');
            $this->reports_model->rpt_general('rpt_asistencia', 'Reporte de Horas Trabajadas', $arr_columns, $arr_data);
        }
    }
    
    public function rpt_asistencia(){
        $registros = 0;
        $dataexcel = array();
        date_default_timezone_set('America/Lima');  
        $ccosto      = $this->input->get_post('ccosto');
        $ccosto_conta = $this->input->get_post('ccosto_conta');
        $fFin        = $this->input->get_post('fFin');
        $fInicio     = $this->input->get_post('fInicio');
        $proyecto    = $this->input->get_post('proyecto');
        $yesterday = date('Y-m-d',strtotime(date('Y-m-d')) - (24*60*60));
        $alerta  = "";
        if($fFin == "") {
            $exp = explode("-", $yesterday);
            $fFin = $exp[2] . "/" . $exp[1] . "/" . $exp[0];
        }
        if($fInicio == "") {
            $exp = explode("-", $yesterday);
            $fInicio = $exp[2] . "/" . $exp[1] . "/" . $exp[0];
        }
        $trabajador  = $this->input->get_post('trabajador');
        $tipodetalle = $this->input->get_post('tipodetalle');
        if($tipodetalle=="")     $tipodetalle = "D";
        if($fInicio=="")   $fInicio = date("d/m/Y",time()); 
        if($fFin=="")      $fFin    = date("d/m/Y",time());         
        $filter          = new stdClass();
        $filter2         = new stdClass();
        $filter3         = new stdClass(); 
        $filter4         = new stdClass(); 
        $filter5         = new stdClass(); 
        $filter2->tipo   = array("20","21","00");
        $filter3->estado = "01";
        $filter4->estado = "01";
        if($ccosto_conta!="")  $filter3->ccosto_conta = $ccosto_conta;
        $selccosto  = form_dropdown('ccosto',$this->ccosto_model->select($filter3,"::Todos:::",""),$ccosto," size='1' id='ccosto' class='comboMedio'");               
        $seltrabajador = form_dropdown('trabajador',$this->tipo_trabajador_model->select($filter2,"::Todos:::",""),$trabajador," size='1' id='trabajador' class='comboPeque'");
        $selccosto_conta = form_dropdown('ccosto_conta',$this->ccosto_conta_model->select($filter4,"::Todos:::",""),$ccosto_conta," size='1' id='ccosto_conta' class='comboMedio'");
        $selproyecto = form_dropdown('proyecto',$this->proyecto_model->select($filter5,"::Todos:::",""),$proyecto," size='1' id='proyecto' class='comboMedio'");
        $filacompuesta    = "";
        $personal_id_ant = "";
        //$filter     = "";
        $filter_not = new stdClass();
        //$filter->tipo_trabajador = $trabajador;
        if($ccosto!="") $filter->ccosto = $ccosto;
        $filter->fechaini = $fInicio;
        $filter->fechafin = $fFin;
        //ESTADO 1= activo, 2 = inactivo 
        $filter->estado = '1';
        if($proyecto!='')     $filter->proyecto_id = $proyecto;
        if($ccosto_conta!="") $filter->ccosto_conta = $ccosto_conta;
        if($trabajador!="")   $filter->tipo_trabajador = $trabajador;
        if($tipodetalle=="C"){
            $group_by = array("p.Apellido_Paterno","p.Personal_Id");          
            $horasresult = $this->asistencia_registro_model->getHorasLaboradasTotal($filter,$filter_not,$group_by); 
            $registros = count($horasresult);
            if($registros>0){
                foreach($horasresult as $key => $value) {
                    $datafila = array();
                    $filtro  = new stdClass();
                    $filtro->personal_id = $value->Personal_Id;
                    $personal = $this->personal_model->get($filtro);
                    $filter2 = new stdClass();
                    $filter2->cargo = $personal->Cargo_Id;
                    $cargos  = $this->cargo_model->get($filter2);
                    $filter3 = new stdClass();
                    $filter3->ccosto = $personal->Ccosto_Id;
                    $ccosto  = $this->ccosto_model->get($filter3);
                    $ccosto_conta = isset($ccosto->cco_group)?$ccosto->cco_group:"-";                
                    $cargo_desc   = $cargos->Descripcion;
                    $tipo_desc   = $this->arr_tipo_trabajador[$personal->Tipo_Trabajador_Id];
                    $ccosto_desc  = (isset($ccosto->Descripcion)?$ccosto->Descripcion:'');
                    $nro_doc      = $personal->Nro_Doc;                
                    /*Obtengo horas trabajadas, hextra y tardanza entre fechas*/
                    $filtro = $filter;
                    $filtro->personal_id = $value->Personal_Id;
                    $horaslabper = $this->asistencia_registro_model->getHorasLaboradas($filter);  
                    $tardanza   = 0;
                    $horasdia   = 0;
                    $horasextra = 0;
                    foreach($horaslabper as $key2 => $value2) {
                        $calculo_horas = $this->calculoHoras($value2);
                        $tardanza   = $tardanza + $calculo_horas->tardanza;
                        $horasdia   = $horasdia + $calculo_horas->horasdia;
                        $horasextra = $horasextra + $calculo_horas->horasextra;
                    }
                    $filacompuesta .= "<tr>";
                    $filacompuesta .= "<td align='center' width='4%'>".$tipo_desc."</td>";
                    $filacompuesta .= "<td align='center' width='6%'>".$nro_doc."</td>";
                    $filacompuesta .= "<td align='left' width='17%'>" . strtoupper($personal->Nombres) . "</td>";
                    $filacompuesta .= "<td align='left' width='20%'>" . utf8_encode($cargo_desc) . "</td>";
                    $filacompuesta .= "<td align='left' width='26%'>" . $ccosto_conta . "</td>";
                    $filacompuesta .= "<td align='left' width='26%'>" . $ccosto_desc . "</td>";
                    $filacompuesta .= "<td align='center' >".number_format($tardanza,2)."</td>";
                    $filacompuesta .= "<td align='center' >".number_format($horasdia,2)."</td>";
                    $filacompuesta .= "<td align='center'>".number_format($horasextra,2)."</td>";
                    $filacompuesta .= "</tr>";
                    $datafila[0] = $tipo_desc;
                    $datafila[1] = $nro_doc;
                    $datafila[2] = utf8_encode($personal->Nombres);
                    $datafila[3] = utf8_encode($cargo_desc);
                    $datafila[4] = utf8_encode($ccosto_desc);
                    $datafila[5] = number_format($tardanza,2);
                    $datafila[6] = number_format($horasdia,2);
                    $datafila[7] = number_format($horasextra,2);
                    array_push($dataexcel, $datafila);
                }
            }
            $this->session->set_userdata('data_consolidado', $dataexcel);
            $dataexcel = array();
        }
        elseif($tipodetalle=="D"){
            $order_by = array("p.Apellido_Paterno"=>"asc","r.Fecha"=>"asc");    
            $horasresult = $this->asistencia_registro_model->getHorasLaboradas($filter,$filter_not="",$order_by);    
            $registros = count($horasresult);
            if($registros>0){
                foreach($horasresult as $key => $value) {
                    $calculo_horas = $this->calculoHoras($value);
                    $datafila = array();
                    $filter2 = new stdClass();
                    $filter2->cargo = $value->Cargo_Id;
                    $cargos = $this->cargo_model->get($filter2);
                    $cargo_desc  = $personal_id_ant!=$value->Personal_Id?$cargos->Descripcion:"";
                    $filter3 = new stdClass();
                    $filter3->ccosto = $value->Ccosto_Id;
                    $ccosto      = $this->ccosto_model->get($filter3);
                    $ccosto_conta = isset($ccosto->cco_group)?$ccosto->cco_group:"-";
                    $tipo_desc   = $this->arr_tipo_trabajador[trim($value->Tipo_Trabajador_Id)];
                    $ccosto_desc = $personal_id_ant!=$value->Personal_Id?$value->Ccosto:"";
                    $nro_doc     = $personal_id_ant!=$value->Personal_Id?$value->Nro_Doc:"";
                    $nombres     = $personal_id_ant!=$value->Personal_Id?$value->Nombres:"";
                    $filter4   = new stdClass();
                    $filter4->proyecto = $value->Proyecto_Id;
                    $proyectos = $this->proyecto_model->get($filter4);
                    $color     = "#fff";
                    if(!($value->Tipo_Trabajador_Id=='20' || $value->Tipo_Trabajador_Id=='21' || $value->Tipo_Trabajador_Id=='99' || $value->Tipo_Trabajador_Id=='00')){
                        $alerta ="<font color='#ff0000'>Existen personas que no tienen el Tipo de trabajador correctamente asignados</font>";
                        $color  = "#ff0000";
                    }                    
                    $filacompuesta .= "<tr bgcolor='".$color."'>";
                    $filacompuesta .= "<td align='center' width='4%'>".$tipo_desc."</td>";
                    $filacompuesta .= "<td align='center' width='6%'>".$value->Nro_Doc."</td>";
                    $filacompuesta .= "<td align='left' width='19%'>" . strtoupper($value->Nombres) . "</td>";
                    $filacompuesta .= "<td align='left' width='20%'>" . utf8_encode($cargos->Descripcion) . "</td>";
                    $filacompuesta .= "<td align='left' width='19%'>" . $proyectos->Descripcion . "</td>";
                    $filacompuesta .= "<td align='left' width='24%'>" . $ccosto_conta . "</td>";    
                    $filacompuesta .= "<td align='left' width='24%'>" . $value->Ccosto . "</td>";                    
                    $filacompuesta .= "<td align='center' width='7%'>" . date('d/m/Y',  strtotime($value->Fecha)) . "</td>";
                    $filacompuesta .= "<td align='center' >".number_format($calculo_horas->tardanza,2)."</td>";
                    $filacompuesta .= "<td align='center' >".number_format($calculo_horas->horasdia,2)."</td>";
                    $filacompuesta .= "<td align='center'>".number_format($calculo_horas->horasextra,2)."</td>";
                    $filacompuesta .= "<td align='center'>".$value->Hingreso."</td>";
                    $filacompuesta .= "<td align='center'>".$value->Hsalida."</td>";
                    $filacompuesta .= "<td align='center'>".$value->asi_ref_start."</td>";
                    $filacompuesta .= "<td align='center'>".$value->asi_ref_end."</td>";
                    $filacompuesta .= "</tr>";
                    $personal_id_ant=$value->Personal_Id;
                    $datafila[0] = $tipo_desc;
                    $datafila[1] = $value->Nro_Doc;
                    $datafila[2] = utf8_encode(strtoupper($value->Nombres));
                    $datafila[3] = utf8_encode($cargos->Descripcion);
                    $datafila[4] = utf8_encode($proyectos->Descripcion);
                    $datafila[5] = utf8_encode($ccosto_conta);   
                    $datafila[6] = utf8_encode($value->Ccosto);                    
                    $datafila[7] = date('d/m/Y',  strtotime($value->Fecha));
                    $datafila[8] = number_format($calculo_horas->tardanza,2);
                    $datafila[9] = number_format($calculo_horas->horasdia,2);
                    $datafila[10] = number_format($calculo_horas->horasextra,2);
                    $datafila[11] = $value->Hingreso;
                    $datafila[12] = $value->Hsalida;
                    $datafila[13] = $value->asi_ref_start;
                    $datafila[14] = $value->asi_ref_end;
                    array_push($dataexcel, $datafila);
                }
                $this->session->set_userdata('data_detallado', $dataexcel);
                $dataexcel = array();
            }          
        }
        $data['filacompuesta'] = $filacompuesta; 
        $data['fFin']          = $fFin;
        $data['fInicio']       = $fInicio;
        $data['selccosto']     = $selccosto;
        $data['selccosto_conta'] = $selccosto_conta;
        $data['seltrabajador'] = $seltrabajador;
        $data['selproyecto']   = $selproyecto;
        $data['tipodetalle']   = $tipodetalle;
        $data['registros']     = $registros;
        $data['alerta']        = $alerta;
        $this->load->view(personal."rpt_asistencia",$data);        
    }
    
    public function calculoHoras($filter){
        $rpta = new stdClass();
        $hora_entrada = '';
        $hora_salida = '';
        $horas_dia = '';
        $horas_extra = '';
        $horas_refrigerio = '';
        $flag_amanecida = false;
        if(strlen($filter->Hingreso) == 5) $filter->Hingreso = $filter->Hingreso . ":00";
        if(strlen($filter->Hsalida) == 5)  $filter->Hsalida = $filter->Hsalida . ":00";
        $filter->Hingreso = ($filter->Hingreso == '') ? '00:00:00' : $filter->Hingreso;
        $filter->Hsalida = ($filter->Hsalida == '') ? '00:00:00' : $filter->Hsalida;        
        $personal = $this->personal_model->getPersonalById($filter->Personal_Id);
        $asistencia_registro = $this->asistencia_registro_model->getTurnoByDay($filter->Personal_Id,date('d/m/Y',strtotime($filter->Fecha)));
        /*Calculo de asistencia para obreros*/
        if($personal->Tipo_Trabajador_Id == '20') {
            //Metales
            if($personal->Categoria2_Id == '01' || $personal->Categoria2_Id == '00') {
                 if(trim($asistencia_registro->Turno_Id) == '01') {//Turno diia
                    if(date('w') == 6) {//Si es sabado
                        $hora_entrada = '08:00:00';
                        $hora_salida = '13:30:00';
                        $horas_dia = 5.5;
                        $horas_refrigerio = 0;
                    }
                    else {
                        $hora_entrada = '08:00:00';
                        $hora_salida = '18:30:00';
                        $horas_dia = 9.5;
                        $horas_refrigerio = (trim($filter->Hsalida)!="" && trim($filter->Hsalida)!="")?0.75:0;
                    }
                }
                elseif(trim($asistencia_registro->Turno_Id) == '03') {//Turno Noche
                    $hora_entrada = '20:00:00';
                    $hora_salida = '05:30:00';
                    $horas_dia = 9.5;
                    $horas_refrigerio = (trim($filter->Hsalida)!="" && trim($filter->Hsalida)!="")?1:0;
                    $flag_amanecida = true;
                }
                else {
                    $hora_entrada = '08:00:00';
                    $hora_salida = '18:30:00';
                    $horas_dia = 9.5;
                    $horas_refrigerio = (trim($filter->Hsalida)!="" && trim($filter->Hsalida)!="")?1:0;
                }
            }
            //Galvanizado
            if($personal->Categoria2_Id == '02') {
                if(trim($asistencia_registro->Turno_Id) == '02') {//Turno dia
                    $hora_entrada = '08:00:00';
                    $hora_salida = '17:00:00';
                    $horas_dia = 9;
                    $horas_refrigerio = (trim($filter->Hsalida)!="" && trim($filter->Hsalida)!="")?0.75:0;
                }
                elseif(trim($asistencia_registro->Turno_Id) == '04') {//Turno noche
                    $hora_entrada = '20:00:00';
                    $hora_salida = '05:00:00';
                    $horas_dia = 9;
                    $horas_refrigerio = (trim($filter->Hsalida)!="" && trim($filter->Hsalida)!="")?1:0;
                    $flag_amanecida = true;
                }
                else {
                    $hora_entrada = '08:00:00';
                    $hora_salida = '17:00:00';
                    $horas_dia = 9;
                    $horas_refrigerio = (trim($filter->Hsalida)!="" && trim($filter->Hsalida)!="")?0.75:0;
                }
            }
            if($flag_amanecida) {
                //Calculo de minutos de tardanza
                if(strtotime($filter->Hingreso) > strtotime($hora_entrada) && trim($filter->Hingreso)!="") {
                    $rpta->tardanza = (strtotime($filter->Hingreso) - strtotime($hora_entrada))/60;
                }
                else {
                    $rpta->tardanza = 0;
                }
                //Calculo de horas trabajadas y extras
                if(strtotime($filter->Hsalida) > strtotime($hora_salida)) {
                    $rpta->horasdia = $horas_dia - $horas_refrigerio;
                    $rpta->horasextra = (strtotime($filter->Hsalida) - strtotime($hora_salida)) / 3600;
                    if($rpta->horasdia < 0) {
                        $rpta->horasdia = 0;
                        $filter->Hsalida = '';
                    }
                }
                else {  
//                    $fecha_hoy = str_replace("/","-",$campos['Fecha']);
//                    $fecha_prox = date('d-m-Y',strtotime($fecha_hoy) + 24*60*60);
                    $rpta->horasdia = (strtotime($filter->Hsalida) - strtotime($hora_entrada)) - $horas_refrigerio;
                    $rpta->horasextra = 0;
                    if($rpta->horasdia < 0) {
                        $rpta->horasdia = 0;
                        $filter->Hsalida = '';
                    }
                }
                return $rpta;
            }
            else {
                //Calculo de minutos de tardanza
                if(strtotime($filter->Hingreso) > strtotime($hora_entrada) && trim($filter->Hingreso)!="") {
                    $rpta->tardanza = (strtotime($filter->Hingreso) - strtotime($hora_entrada))/60;
                }
                else {
                    $rpta->tardanza = 0;
                }
                //Calculo de horas trabajadas y extras
                if (strtotime($filter->Hsalida) > strtotime($hora_salida)) {
                    $rpta->horasdia = $horas_dia - $horas_refrigerio;
                    $rpta->horasextra = (strtotime($filter->Hsalida) - strtotime($hora_salida))/3600;
                    if($rpta->horasdia < 0) {
                        $rpta->horasdia = 0;
                        $filter->Hsalida = '00:00:00';
                    }
                    return $rpta;
                } else {
                    $horas_dia = (strtotime($filter->Hsalida) - strtotime($filter->Hingreso)) / 3600 - $horas_refrigerio;
                    $rpta->horasdia = $horas_dia;
                    $rpta->horasextra = 0;
                    if($rpta->horasdia < 0) {
                        $rpta->horasdia = 0;
                        $filter->Hsalida = '00:00:00';
                    }
                    return $rpta;
                }
            }
        }
        /*Calculo de asistencia para empleados*/
        elseif($personal->Tipo_Trabajador_Id == '21' || $personal->Tipo_Trabajador_Id == '00') {
           if(trim($personal->Proyecto_Id) == '08') {
                $hora_entrada = '09:00:00';
                $hora_salida  = '19:15:00';
                $horas_dia    = 10.15;
                $horas_refrigerio = (trim($filter->Hsalida)!="" && trim($filter->Hsalida)!="")?0.75:0;
                if(strtotime($filter->Hingreso) > strtotime($hora_entrada) && trim($filter->Hingreso)!="") {
                    $rpta->tardanza = (strtotime($filter->Hingreso) - strtotime($hora_entrada))/60;
                }
                else {
                    $rpta->tardanza = 0;
                }
                if(strtotime($filter->Hsalida) > strtotime($hora_salida)) {
                    $rpta->horasdia = $horas_dia - $horas_refrigerio;
                    $rpta->horasextra = 0;
                    return $rpta;
                }
                else {
                    $horas_dia = (strtotime($filter->Hsalida) - strtotime($filter->Hingreso))/3600 - $horas_refrigerio;
                    $rpta->horasdia = $horas_dia;
                    $rpta->horasextra = 0;
                    return $rpta;
                }
            }    
            else {
                $hora_entrada = '08:20:00';
                $hora_salida  = '18:30:00';
                $horas_dia    = 10.25;
                $horas_refrigerio = (trim($filter->Hsalida)!="" && trim($filter->Hsalida)!="")?0.75:0;
                if(strtotime($filter->Hingreso) > strtotime($hora_entrada) && trim($filter->Hingreso)!="") {
                    $rpta->tardanza = (strtotime($filter->Hingreso) - strtotime($hora_entrada))/60;
                }
                else {
                    $rpta->tardanza = 0;
                }
                if(strtotime($filter->Hsalida) > strtotime($hora_salida)) {
                    $rpta->horasdia = $horas_dia - $horas_refrigerio;
                    $rpta->horasextra = 0;
                    return $rpta;
                }
                else {
                    $horas_dia = (strtotime($filter->Hsalida) - strtotime($filter->Hingreso))/3600 - $horas_refrigerio;
                    $rpta->horasdia = $horas_dia;
                    $rpta->horasextra = 0;
                    return $rpta;
                }
            }
        }  
        else{
            $rpta->tardanza   = 0;
            $rpta->horasdia   = 0;
            $rpta->horasextra = 0;
            return $rpta;
        }
    }
    
    public function export_bank_val(){
        
        $type = $this->input->get_post('var_bank');
        
        if($this->session->userdata('data_interf_'.$type)){
            $result = $this->session->userdata('data_interf_'.$type);
            
            switch ($type) {
                case 'bbva':
                    $data['title'] = "Exportar Pagos";
                    if($this->input->get_post('txt_cuenta')){
                        
                        $var_cuenta = $this->input->get_post('txt_cuenta');
                        
                        
                        $var_validate = $this->input->get_post('var_validate');
                        $txt_moneda = $this->input->get_post('txt_moneda');
                        $txt_proceso = $this->input->get_post('txt_proceso');
                        $txt_fecha = $this->input->get_post('txt_fecha');
                        $txt_horario = $this->input->get_post('txt_horario');
                        $txt_referencia= $this->input->get_post('txt_referencia');
                       
                        $vowels = array(".", ",");
                        $var_total  = str_pad(str_replace($vowels, "", $result['header']['total']),15,"0",STR_PAD_LEFT);
                        $var_rows   = str_pad($result['header']['rows'],6,"0",STR_PAD_LEFT);
         
            
                        $var_header = '700'.$var_cuenta.$txt_moneda.$var_total.$txt_proceso.str_pad($txt_fecha,8," ",STR_PAD_RIGHT).$txt_horario.str_pad($txt_referencia,25," ",STR_PAD_RIGHT).str_pad($var_rows.$var_validate."000000000000000000",75," ",STR_PAD_RIGHT).chr(10); 
                        $var_detail = '';
                        $var_rows_n = count($result['detail']);
                        $i = 1 ;
                        
                        foreach ($result['detail'] as $value) {

                            $var_sep = ($var_rows_n == $i)?'':chr(10);
//                            $var_detail .= str_pad($value->col_1,16,' ').str_pad($value->col_2,61,' ').str_pad(str_pad(str_replace($vowels, "", $value->pago),19,"0",STR_PAD_LEFT),156,' ').$var_sep;
                            $var_detail .= str_pad($value->col_1,16,' ').str_pad($value->col_2,61,' ').str_pad(str_replace($vowels, "", trim($value->pago)),15,"0",STR_PAD_LEFT).str_repeat(chr(32), 141).$var_sep;
                            $i++;
                        }
                        
                        
                        
               

                        $result = $var_header.$var_detail;
                        $var_name = $txt_fecha.'-'.$txt_referencia;
                        $data['export'] = true;
                        $data['var_name'] = $var_name;
                        $data['data'] = $result;
                    }
                    
                    
                    $this->load->view(scire."export_bank_val",$data); 
                break;    
            }
            
        }else{
            echo "<div style='color:rgb(150,150,150);padding:10px;width:560px;height:160px;border:1px solid rgb(210,210,210);'>
                No hay datos para exportar
                </div>";
        }
    }
    
    public function export_bank($type){
        $data["title"] = "Exportar Bancos";
        $data["var_bank"] = $type;
        $result = $this->session->userdata('data_interf_'.$type);
        
   
        $var_type = $result['header']['type']; 
        $entidad = $this->session->userdata('entidad');
        
        switch ($entidad) {
            case '01':
                $data["var_referencia"] = $var_type.'-METAL';
                $data["var_cuenta"] = '00110143540100001983';
                break;
            case '02':
                $data["var_referencia"] = $var_type.'-GALVA';
                $data["var_cuenta"] = '00110307630100004835';
                break;

            default:
                $data["var_referencia"] = '00110143540100001983';
                $data["var_entidad"] = $var_type.'-METAL';
                break;
        }
        
        
        $this->load->view(scire."export_bank",$data); 
    }
    
    
    public function export_excel($type) {
        if($this->session->userdata('data_'.$type)){
            $result = $this->session->userdata('data_'.$type);
            switch ($type) {
                case 'consolidado_cc':
                    $arr_group = array('B5:E5'=>'EMPLEADO','F5:I5'=>'OBRERO','J5:K5'=>'4TA');
                    $this->reports_model->rpt_general('rpt_'.$type, 'CONSOLIDADO POR CENTRO DE COSTO', $result["columns"], $result["rows"],$arr_group);
                    break;
                case 'consolidado_area':
                    $this->reports_model->rpt_general('rpt_'.$type, 'CONSOLIDADO POR AREA', $result["columns"], $result["rows"]);
                    break;
                case 'consolidado_concepto':
                    $this->reports_model->rpt_general('rpt_'.$type, 'CONSOLIDADO POR CONCEPTO', $result["columns"], $result["rows"]);
                    break;
                case 'consolidado_detalle':
                    $this->reports_model->rpt_general('rpt_'.$type, 'DETALLE DE PAGOS', $result["columns"], $result["rows"]);
                    break;
                
                /* PAGOS EMPLEADOS - OBREROS */
                case 'per_nopla':
                    $this->reports_model->rpt_general('rpt_'.$type, 'RPH - NO PLANILLA', $result["columns"], $result["rows"]);
                    break;
                case 'per_planilla':
                    $this->reports_model->rpt_general('rpt_'.$type, 'PAGO PLANILLA', $result["columns"], $result["rows"]);
                    break;
                case 'per_difpla':
                    $this->reports_model->rpt_general('rpt_'.$type, 'DIFERENCIA DE PLANILLA', $result["columns"], $result["rows"]);
                    break;
                case 'per_efectivo':
                    $this->reports_model->rpt_general('rpt_'.$type, 'PAGO EN EFECTIVO', $result["columns"], $result["rows"]);
                    break;
                case 'per_detalle':
                    
                    $this->reports_model->rpt_general('rpt_'.$type, 'DETALLE', $result["columns"], $result["rows"],$result["group"]);
                    break;
                case 'scire_costos_area':
                    $arr_group = array('K5:P5'=>'PRESUPUESTADO');
                    $this->reports_model->rpt_general('rpt_'.$type, 'Reporte de Costos por Area', $result["columns"], $result["rows"],$arr_group);
                    break;
                case 'pago_concepto_con':
                    $arr_columns = array();
                    $arr_columns[0]['STRING'] = 'CONCEPTOS';
                    $arr_columns[1]['STRING'] = 'C.COSTOS';
                    $arr_columns[2]['STRING'] = 'AREA';
                    $arr_columns[3]['STRING'] = 'TIPO';
                    $arr_columns[4]['STRING'] = 'MONTO S/.';
                    $this->reports_model->rpt_general('rpt_gastos_concepto_consolidado', 'Reporte de Gastos por Concepto Consolidado', $arr_columns, $result);
                    break;
                case 'pago_concepto_det':
                    $arr_columns = array();
                    $arr_columns[0]['STRING'] = 'CONCEPTOS';
                    $arr_columns[1]['STRING'] = 'C.COSTOS';
                    $arr_columns[2]['STRING'] = 'AREA';
                    $arr_columns[3]['STRING'] = 'PERSONAL';
                    $arr_columns[4]['STRING'] = 'TIPO';
                    $arr_columns[5]['STRING'] = 'VALOR';
                    $this->reports_model->rpt_general('rpt_gastos_concepto_detalle', 'Reporte de Gastos por Concepto Detallado', $arr_columns, $result);
                    break;
                case 'horastrabajadas':
                    $arr_columns = array();
                    $arr_columns[]['STRING']  = 'DNI';
                    $arr_columns[]['STRING']  = 'PERSONA';
                    $arr_columns[]['STRING']  = 'C.COSTOS';
                    $arr_columns[]['STRING']  = 'CONCEPTOS';
                    $arr_columns[]['NUMERIC'] = 'HORAS';
                    $arr_columns[]['NUMERIC'] = 'MONTO S/.';
                    $arr_columns[]['NUMERIC'] = 'HORAS';
                    $arr_columns[]['NUMERIC'] = 'MONTO S/.';       
                    $arr_group = array('E5:F5'=>'SCIRE','G5:H5'=>'SIDDEX');
                    $this->reports_model->rpt_general('rpt_horastrabajadas', 'Reporte de Horas Trabajadas', $arr_columns, $result['rows'],$arr_group);
                    break;
            }
        }else{
            echo "<div style='color:rgb(150,150,150);padding:10px;width:560px;height:160px;border:1px solid rgb(210,210,210);'>
                No hay datos para exportar
                </div>";
        }
        /*switch ($type) {
            
            case 'scire_costos_area':
                if($this->session->userdata('data_scire_costos_area')){
                    $result = $this->session->userdata('data_scire_costos_area'); 
                    $this->reports_model->rpt_general('rpt_gastos_concepto_consolidado', 'Reporte de Gastos por Concepto Consolidado', $result["columns"], $result["rows"]);
                }else{
                    echo "No hay datos para exportar.";
                }
                break;
                
            case 'pago_concepto_con':
                if($this->session->userdata('data_pago_concepto_con')){
                    $result = $this->session->userdata('data_pago_concepto_con');
                    $arr_columns = array();
                    $arr_columns[0]['STRING'] = 'CONCEPTOS';
                    $arr_columns[1]['STRING'] = 'C.COSTOS';
                    $arr_columns[2]['STRING'] = 'AREA';
                    $arr_columns[3]['STRING'] = 'TIPO';
                    $arr_columns[4]['STRING'] = 'MONTO S/.';
                    $this->reports_model->rpt_general('rpt_gastos_concepto_consolidado', 'Reporte de Gastos por Concepto Consolidado', $arr_columns, $result);
                }else{
                    echo "No hay datos para exportar.";
                }
                break;
            case 'pago_concepto_det':
                if($this->session->userdata('data_pago_concepto_det')){
                    $result = $this->session->userdata('data_pago_concepto_det');
                    $arr_columns = array();
                    $arr_columns[0]['STRING'] = 'CONCEPTOS';
                    $arr_columns[1]['STRING'] = 'C.COSTOS';
                    $arr_columns[2]['STRING'] = 'AREA';
                    $arr_columns[3]['STRING'] = 'PERSONAL';
                    $arr_columns[4]['STRING'] = 'TIPO';
                    $arr_columns[5]['STRING'] = 'VALOR';
                    $this->reports_model->rpt_general('rpt_gastos_concepto_detalle', 'Reporte de Gastos por Concepto Detallado', $arr_columns, $result);
                }else{
                    echo "No hay datos para exportar.";
                }
                break;
        }*/
    }
    
    public function excel_gastos_area_con() {
        
        if($this->session->userdata('data_consolidado_gastos')){
            $result = $this->session->userdata('data_consolidado_gastos');
            $arr_columns = array();
            $arr_columns[0]['STRING'] = 'CCOSTO';
            //$arr_columns[1]['STRING'] = 'AREA';
            $arr_columns[1]['STRING'] = 'CONCEPTOS';
            $arr_columns[2]['NUMERIC'] = 'MONTO S/.';
            $this->reports_model->rpt_general('rpt_gastos_area_consolidado', 'Reporte de Gastos por Area Consolidado', $arr_columns, $result);
        }else{
            echo "No hay datos para exportar.";
        }
        
    }
    
    public function excel_gastos_area_det() {
    if($this->session->userdata('data_detalle_gastos')){
        $result = $this->session->userdata('data_detalle_gastos');
        $arr_columns = array();
        $arr_columns[0]['STRING'] = 'NRO';
        $arr_columns[1]['STRING'] = 'TIPO';
        $arr_columns[2]['STRING'] = 'PERSONAL';
        $arr_columns[3]['STRING'] = 'CCOSTO';
        $arr_columns[4]['NUMERIC'] = 'BASICO';
        $arr_columns[5]['NUMERIC'] = 'DESC. SEMANAL';
        $arr_columns[6]['NUMERIC'] = 'REINTEGRO';
        $arr_columns[7]['NUMERIC'] = 'BASICO NO TRIB';
        $arr_columns[8]['NUMERIC'] = 'ASIG. FAMILIAR';
        $arr_columns[9]['NUMERIC'] = 'BONIFICACION';
        $arr_columns[10]['NUMERIC'] = 'H. EXTRA';
        $arr_columns[11]['NUMERIC'] = 'H.DOBLE';
        $arr_columns[12]['NUMERIC'] = 'TOTAL S/.';
        $arr_columns[13]['STRING'] = 'TARDANZA';
        $arr_columns[14]['STRING'] = 'ONP';
        $arr_columns[15]['STRING'] = 'AFP FONDO';
        $arr_columns[16]['STRING'] = 'AFP COMISION';
        $arr_columns[17]['STRING'] = 'AFP SEGURO';
        $arr_columns[18]['STRING'] = 'RETENCION';
        $arr_columns[19]['STRING'] = 'ADELANTO QUINCENA';
        $arr_columns[20]['STRING'] = 'PRESTAMO';
        $arr_columns[21]['STRING'] = 'DSCTO COMEDOR';
        $arr_columns[22]['STRING'] = 'TOTAL S/.';
        $arr_columns[23]['STRING'] = 'ESSALUD';
        $arr_columns[24]['STRING'] = 'SENATI';
        $arr_columns[25]['STRING'] = 'SCTR SALUD';
        $arr_columns[26]['STRING'] = 'SCTR PENSION';
        $arr_columns[27]['STRING'] = 'TOTAL S/.';
        $arr_columns[28]['STRING'] = 'NETO REMUN S/.';
        $arr_columns[29]['STRING'] = 'NETO FUERA S/.';
        $arr_columns[30]['STRING'] = 'FUERA + REMUN S/.';
        $this->reports_model->rpt_general('rpt_gastos_area', 'Reporte de Gastos por Area', $arr_columns, $result);
    }else{
        echo "No hay datos para exportar.";
    }
    
    }
    
    public function excel_regulariza_asistencia() {
        $result = $this->session->userdata('regulariza_asistencia');
        $resultado = array();
        foreach ($result as $key => $value) {
            $result[$key] = (array)$value;
        }
//        echo "<pre>";
//        print_r($result);
//        echo "<pre/>";
//        exit;
        foreach ($result as $key => $value) {
            $arrdata = array();
            $filter = new stdClass();
            $filter->cargo = $value['Cargo_Id'];
            $cargos = $this->cargo_model->get($filter);
//            foreach($value as $k => $v) {
//                $arrdata[] = $v;
//            }
            $arrdata[] = $value['Tipo_Trabajador_Id'] == 21 ?'E':'O';
            $arrdata[] = $value['Nro_Doc'];
            $arrdata[] = utf8_encode($value['Nombres']);
            $arrdata[] = utf8_encode($cargos->Descripcion);
            $arrdata[] = utf8_encode($value['Ccosto']);
            $arrdata[] = (isset($value['Fecha'])) ? utf8_encode(date('d-m-Y',  strtotime($value['Fecha']))) : '-';
            $arrdata[] = (isset($value['Tardanza']) ? $value['Tardanza'] : 0);
            $arrdata[] = (isset($value['HorasLab']) ? $value['HorasLab'] : 0);
            $arrdata[] = (isset($value['Horas_Extra']) ? $value['Horas_Extra'] : 0);
            $arrdata[] = (isset($value['Hingreso'])) ? utf8_encode($value['Hingreso']) : '-';
            $arrdata[] = (isset($value['Hsalida'])) ? utf8_encode($value['Hsalida']) : '-';
            $arrdata[] = (isset($value['asi_ref_start'])) ? utf8_encode($value['asi_ref_start']) : '-';
            $arrdata[] = (isset($value['asi_ref_end'])) ? utf8_encode($value['asi_ref_end']) : '-';
            $resultado[] = $arrdata;
        }
        $arr_columns = array();
        $arr_columns[0]['STRING'] = 'TIPO';
        $arr_columns[1]['STRING'] = 'DNI';
        $arr_columns[2]['STRING'] = 'APELLIDOS Y NOMBRES';
        $arr_columns[3]['STRING'] = 'CARGO';
        $arr_columns[4]['STRING'] = 'CENTRO COSTO';
        $arr_columns[5]['STRING'] = 'FECHA';
        $arr_columns[6]['STRING'] = 'MIN TARD';
        $arr_columns[7]['STRING'] = 'HORAS TRAB';
        $arr_columns[8]['STRING'] = 'HORAS EXTRA';
        $arr_columns[9]['STRING'] = 'HINGRESO';
        $arr_columns[10]['STRING'] = 'HSALIDA';
        $arr_columns[11]['STRING'] = 'SALIDA REF';
        $arr_columns[12]['STRING'] = 'INGRESO REF';
        $this->reports_model->rpt_general('rpt_asistencia', 'Reporte de Horas Trabajadas', $arr_columns, $resultado);
    }
    
    public function regulariza_asistencia() {
        $dataexcel = array();
        $ccosto      = $this->input->get_post('ccosto');
        $fecha       = $this->input->get_post('fInicio');
        $trabajador  = $this->input->get_post('trabajador');
        $personal    = $this->input->get_post('personal');
        $hingreso    = $this->input->get_post('hingreso');
        $hsalida     = $this->input->get_post('hsalida');
        $asi_ref_start  = $this->input->get_post('asi_ref_start');
        $asi_ref_end    = $this->input->get_post('asi_ref_end');
        $accion      = $this->input->get_post('accion');
        $modo        = $this->input->get_post('modo');
        if($accion=="G"){
          foreach($personal as $indice => $value){
              $campos  = array("Fecha"=>$fecha,"Personal_Id"=>$value);
              $ingreso = "";
              $salida  = "";
              $ri = "";
              $rs = "";
              $filter  = new stdClass();    
              if(strlen(trim($hingreso[$indice])) == 5) {
                    $hingreso[$indice] = trim($hingreso[$indice]) . ":00";
              }
              if(strlen(trim($hsalida[$indice])) == 5) {
                    $hsalida[$indice] = trim($hsalida[$indice]) . ":00";
              }

              if(strlen(trim($asi_ref_start[$indice])) == 5) {
                  $asi_ref_start[$indice] = trim($asi_ref_start[$indice]). ":00";
              }
            
              if(strlen(trim($asi_ref_end[$indice])) == 5) {
                  $asi_ref_end[$indice] = trim($asi_ref_end[$indice]). ":00";
              }
               
//              echo "Hingreso : " . $hingreso[$indice] . "----------" . "Hsalida : " . $hsalida[$indice] . "<br>";
              if(trim($hingreso[$indice])!=""){
                $arringreso = explode(":",$hingreso[$indice]);
                $ingreso    = trim(str_pad($arringreso[0],2,"0",STR_PAD_LEFT)).":".trim(str_pad($arringreso[1],2,"0",STR_PAD_LEFT)).":".trim(str_pad($arringreso[2],2,"0",STR_PAD_LEFT));
              }
              if(trim($hsalida[$indice])!=""){
                $arrsalida = explode(":",$hsalida[$indice]);  
                $salida    = trim(str_pad($arrsalida[0],2,"0",STR_PAD_LEFT)).":".trim(str_pad($arrsalida[1],2,"0",STR_PAD_LEFT)).":".trim(str_pad($arrsalida[2],2,"0",STR_PAD_LEFT));
              }
              
               if(trim($asi_ref_end[$indice])!=""){
                  if(trim($asi_ref_end[$indice])!="-"){
                      $arr_asi_ref_end = explode(":",$asi_ref_end[$indice]);
                      $var_asi_ref_end = trim(str_pad($arr_asi_ref_end[0],2,"0",STR_PAD_LEFT)).":".trim(str_pad($arr_asi_ref_end[1],2,"0",STR_PAD_LEFT)).":".trim(str_pad($arr_asi_ref_end[2],2,"0",STR_PAD_LEFT));
                  }
                  
                  if(trim($asi_ref_end[$indice])=="-"){
                      $var_asi_ref_end    = '-';
                  }
                }else{
                    $var_asi_ref_end ='';
                }
              if(trim($asi_ref_start[$indice])!=""){
                  if(trim($asi_ref_start[$indice])!="-"){
                      $arr_asi_ref_start = explode(":",$asi_ref_start[$indice]);
                      $var_asi_ref_start = trim(str_pad($arr_asi_ref_start[0],2,"0",STR_PAD_LEFT)).":".trim(str_pad($arr_asi_ref_start[1],2,"0",STR_PAD_LEFT)).":".trim(str_pad($arr_asi_ref_start[2],2,"0",STR_PAD_LEFT));
                  }
                  if(trim($asi_ref_start[$indice])=="-"){
                      $var_asi_ref_start = '-';
                  }
                }else{
                    $var_asi_ref_start ='';
                }
              if($ingreso == "" && $salida == "" 
                      /*&& $var_asi_ref_end == "" && $var_asi_ref_start == ""*/) {
                  continue;
              }
              
              /*Actualizo los datos en la tabla Asistencia Registro*/
              $filter->Hingreso     = $ingreso;//08:50:26
              $filter->Hsalida      = $salida; 
              $filter->Hora_Ingreso = $ingreso!=""?number_format(($arringreso[0]+($arringreso[1]/60)+($arringreso[2]/3600)),2):0;
              $filter->Hora_Salida  = $salida!=""?number_format(($arrsalida[0]+($arrsalida[1]/60)+($arrsalida[2]/3600)),2):0;
              if($modo[$indice]=="e"){
                $filter->Fecha       = $fecha; 
                $filter->Personal_Id = $value; 
                $personal = $this->personal_model->getPersonalById($value);
//              $personal_mimco = $this->responsable_model->getPersonalByNroDoc($personal->Nro_Doc, $personal->Categoria2_Id);
                $horas = $this->calculoHoras($filter);
                $filter->Horas = $horas->horasdia;
//                $filter->Horas_Extra = $horas->horasextra;
//                $filter->Valor1 = $horas->tardanza;
                $filter->Horas_Extra = 0;
                $filter->Valor1      = 0;
                $filter->asi_ref_start = $var_asi_ref_start;
                $filter->asi_ref_end = $var_asi_ref_end;
                
                $this->asistencia_registro_model->edit($campos,$filter);
              }
              /*Por mientras*/
//              elseif($modo[$indice]=="n"){
//               $filter->personal_id = $value; 
//                  $personal = $this->personal_model->getPersonalById($value);
//                  $personal_mimco = $this->responsable_model->getPersonalByNroDoc($personal->Nro_Doc, $personal->Categoria2_Id);
//                  if(trim($hingreso[$indice]) != "" && trim($hsalida[$indice]) != "") {
//                      $horas = $this->calculoHoras($filter);
//                      $filter->Fecha         = $fecha;
//                      $filter->Turno_Id      = $personal->Proyecto_Id == "0000000000" ? "01" : $personal->Proyecto_Id;
//                      $filter->Personal_Id   = $value;
//                      $filter->Tipo_Dia_Id   = "00";
//                      $filter->Asistencia_Id = "";
//                      $filter->Tipo_Suspension_RL_Id = "00";
//                      $filter->Horas = $horas->horasdia;
////                      $filter->Horas_Extra = $horas->horasextra;
////                      $filter->Valor1 = $horas->tardanza;
//                      $filter->Horas_Extra = 0;
//                      $filter->Valor1      = 0;
//                      //$filter->asi_ref_start =$ri; 
//                      //$filter->asi_ref_end = $rs;
//                      $this->asistencia_registro_model->insert($filter);
//                  }
//                  elseif(trim($hingreso[$indice]) != "") {
//                      $horas = $this->calculoHoras($value, $filter, $campos);
//                      $filter->Fecha         = $fecha;
//                      $filter->Turno_Id      = "01";
//                      $filter->Personal_Id   = $value;
//                      $filter->Tipo_Dia_Id   = "00";
//                      $filter->Asistencia_Id = "";
//                      $filter->Tipo_Suspension_RL_Id = "00";   
//                      //$filter->Valor1 = $horas->tardanza;
//                      $filter->Valor1 = 0;
//                      $filter->asi_ref_start =$ri; 
//                      $filter->asi_ref_end = $rs;
//                      $this->asistencia_registro_model->insert($filter);
//                  }
//              }
          }
          echo "<script>";
          echo "alert('Se grabaron los cambios correctamente.');";
          echo "window.close();";
          echo "window.opener.document.getElementById('html').click();";
          echo "</script>";
        }
        elseif($accion=="C"){
          $campos  = array("Fecha"=>$fecha);
          $filter->Estado = 2;
          $this->asistencia_registro_model->edit($campos,$filter); 
          echo "<script>window.close();</script>";          
        }
        $estado = "";
        $filter      = new stdClass();
        $filter2     = new stdClass();
        $filter2->tipo = array("20","21");   

        $selccosto   = form_dropdown('ccostoffer',$this->ccosto_model->select($filter,"::Todos:::",""),$ccosto," size='1' id='ccosto' class='comboMedio'");               
        $seltrabajador = form_dropdown('trabajador',$this->tipo_trabajador_model->select($filter2,"::Todos:::",""),$trabajador," size='1' id='trabajador' class='comboPeque' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');\" ");        
        $fila        = "";
        $filter      = new stdClass();
        $filter_not  = new stdClass();
        $arrPersona  = array();
        if($ccosto!="") $filter->ccosto = $ccosto;
        $filter->fecha = $fecha;
        if($trabajador!="")  $filter->tipo_trabajador = $trabajador;
        $order_by = array("p.Apellido_Paterno"=>"asc","r.Fecha"=>"asc");   
        $horasresult = $this->asistencia_registro_model->getHorasLaboradas($filter,$filter_not="",$order_by);          
        if(count($horasresult)>0){
            foreach($horasresult as $key => $value) {
                $arrPersona[] = $value->Personal_Id;
                $filter2 = new stdClass();
                $filter2->cargo = $value->Cargo_Id;
                $cargos = $this->cargo_model->get($filter2);
                $cargo_desc  = $cargos->Descripcion;
                $tipo_desc   = ($value->Tipo_Trabajador_Id==21?'E':'O');
                $ccosto_desc = $value->Ccosto;
                $nro_doc     = $value->Nro_Doc;
                $nombres     = $value->Nombres;
                $estado      = $value->Estado;
                $color       = $estado==2?"#FF0000":"#000";
                $disabled    = $estado==2?"disabled='disabled'":"";
                $fecha_fila  = $value->Fecha;
                $fila .= "<tr>";
                $fila .= "<td align='center' width='7%' style='color:".$color."'>".$tipo_desc."</td>";
                $fila .= "<td align='center' width='10%' style='color:".$color."'>".$nro_doc."</td>";
                $fila .= "<td align='left' width='20%' style='color:".$color."'>" . strtoupper($nombres) . "</td>";
                $fila .= "<td align='left' width='20%' style='color:".$color."'>" . $ccosto_desc . "</td>";
                $fila .= "<td align='center' width='10%' style='color:".$color."'>" . date('d/m/Y',  strtotime($fecha_fila)) . "</td>";
                $fila .= "<td align='center' width='8%'><input ".$disabled." style='color:".$color."' maxlength='8' type='text' onkeydown=\"return onlyNumbers(event, this)\" onblur='validarCampo(this); setTrueRecord(this)' name='hingreso[]' id='i".$value->Personal_Id."' class='cajaPequena' value='".trim($value->Hingreso)."'></td>";
                $fila .= "<td align='center' width='8%'><input ".$disabled." style='color:".$color."' maxlength='8' type='text' onkeydown=\" return onlyNumbers(event, this)\" onblur='validarCampo(this); setTrueRecord(this)' name='hsalida[]' id='s".$value->Personal_Id."' class='cajaPequena' value='".trim($value->Hsalida)."'></td>";
                $fila .= "<td align='center' width='8%'><input " . $disabled . " style='color:" . $color . "' maxlength='8' type='text' onkeydown=\"return onlyNumbers(event, this)\" onblur='validarCampo(this); setTrueRecord(this)' name='asi_ref_start[]' id='a" . $value->Personal_Id . "' class='cajaPequena' value='".trim($value->asi_ref_start)."'></td>";
                $fila .= "<td align='center' width='8%'><input " . $disabled . " style='color:" . $color . "' maxlength='8' type='text' onkeydown=\"return onlyNumbers(event, this)\" onblur='validarCampo(this); setTrueRecord(this)' name='asi_ref_end[]' id='b" . $value->Personal_Id . "' class='cajaPequena' value='".trim($value->asi_ref_end)."'></td>";
                $fila .= "<input type='hidden' name='personal[]' id='personal[]' value='".$value->Personal_Id."'>";
                $fila .= "<input type='hidden' name='modo[]' id='modo".$value->Personal_Id."' value=''>";
                $fila .= "</tr>";
            }
        } 
        unset($filter->fecha);
        $filter->order_by = array("p.Nombres"=>"asc");  
        $filter->estado      = "01";
        $horasresult2 = $this->personal_model->get($filter);
        $sumarrays = array();
        if(is_array($horasresult2)) {
            //$sumarrays = array_merge($horasresult, $horasresult2);
            $sumarrays = array_merge($horasresult, $horasresult2);
        }
        else {
            $sumarrays = $horasresult;
        }
        $this->session->set_userdata('regulariza_asistencia', $sumarrays);
        if(is_array($horasresult2)) {
            if (count($horasresult2) > 0) {
                foreach ($horasresult2 as $key => $value) {
                    $valida = true;
                    foreach ($arrPersona as $id => $person_id) {
                        if ($value->Personal_Id == $person_id) {
                            $valida = false;
                        }
                    }
                    if ($valida) {
                        $tipo_desc = ($value->Tipo_Trabajador_Id == 21 ? 'E' : 'O');
                        $fila .= "<tr bgcolor='#A4A4A4'>";
                        $fila .= "<td align='center' width='7%' style='color:" . $color . "'>" . $tipo_desc . "</td>";
                        $fila .= "<td align='center' width='10%' style='color:" . $color . "'>" . $value->Nro_Doc . "</td>";
                        $fila .= "<td align='left' width='20%' style='color:" . $color . "'>" . strtoupper($value->Nombres) . "</td>";
                        $fila .= "<td align='left' width='20%' style='color:" . $color . "'>" . $value->Ccosto . "</td>";
                        $fila .= "<td align='center' width='10%' style='color:" . $color . "'>" . date('d/m/Y', strtotime($fecha_fila)) . "</td>";
                        $fila .= "<td align='center' width='8%'><input " . $disabled . " style='color:" . $color . "' maxlength='8' type='text' onkeydown=\"return onlyNumbers(event, this)\" onblur='validarCampo(this); setTrueRecord(this)' name='hingreso[]' id='i" . $value->Personal_Id . "' class='cajaPequena' value=''></td>";
                        $fila .= "<td align='center' width='8%'><input " . $disabled . " style='color:" . $color . "' maxlength='8' type='text' onkeydown=\"return onlyNumbers(event, this)\" onblur='validarCampo(this); setTrueRecord(this)' name='hsalida[]' id='s" . $value->Personal_Id . "' class='cajaPequena' value=''></td>";
                        $fila .= "<td align='center' width='8%'><input " . $disabled . " style='color:" . $color . "' maxlength='8' type='text' onkeydown=\"return onlyNumbers(event, this)\" onblur='validarCampo(this); setTrueRecord(this)' name='asi_ref_start[]' id='a" . $value->Personal_Id . "' class='cajaPequena' value=''></td>";
                        $fila .= "<td align='center' width='8%'><input " . $disabled . " style='color:" . $color . "' maxlength='8' type='text' onkeydown=\"return onlyNumbers(event, this)\" onblur='validarCampo(this); setTrueRecord(this)' name='asi_ref_end[]' id='b" . $value->Personal_Id . "' class='cajaPequena' value=''></td>";
                        $fila .= "<input type='hidden' name='personal[]' id='personal[]' value='" . $value->Personal_Id . "'>";
                        $fila .= "<input type='hidden' name='modo[]' id='modo[]' value='n'>";
                        $fila .= "</tr>";
                    }
                }
            }
        }
        $data['fInicio']       = $fecha;
        $data['selccosto']     = $selccosto;
        $data['seltrabajador'] = $seltrabajador;        
        $data['fila']          = $fila;
        $data['estado']        = $estado;
        $this->load->view(scire."scire_regulariza_asistencia",$data); 
    }
    
    public function listar($param){
        $var_type = "";
        $anio       = $this->input->get_post('anio');
        $mes        = $this->input->get_post('mes');
        $periodo    = $this->input->get_post('periodo');
        $planilla   = $this->input->get_post('planilla');
        $ccosto_conta = $this->input->get_post('ccosto_conta');
        $proceso    = $this->input->get_post('proceso');
        $ccosto     = $this->input->get_post('ccosto');
        $tipoexcel  = $this->input->get_post('tipoexcel');
        $entidad    = $this->session->userdata('entidad');
        if($periodo=="") $periodo  = "000";
        if($proceso=="") $proceso  = "01";
        if($anio=="")    $anio     = date('Y',time());
        if($mes=="")     $mes      = "000000";
        if($planilla==""){if($param=='o'){$planilla = "05";}elseif($param=='e'){$planilla='01';}}
        $filter1 = new stdClass();
        $filter2 = new stdClass();
        $filter3 = new stdClass();
        $filter4 = new stdClass();
        $filter5 = new stdClass();
        $filter1->anioi   = "2013";
        $filter2->planilla= $planilla;
        $filter2->anio    = $anio;
        $filter3->estado  = '01';
        if($ccosto_conta !="")  $filter3->ccosto_conta = $ccosto_conta;
        $filter4->proceso = array('01','02');      
        if($param == 'o') {
            $filter5->planilla = array('05');
            $var_type = "OBREROS";
            $var_type_interf = "OBRE";            
        }
        elseif($param == 'e') {
            $filter5->planilla = array('01');
            $var_type = "EMPLEADOS";
            $var_type_interf = "EMPL";            
        }
        if($periodo!=0){
            $arr_periodo = $this->periodo_model->search($periodo);
            $var_periodo = $arr_periodo[0]->Descripcion;
        }
        if($entidad=='01')
            $arrccosto_conta = array(""=>":::Todos:::","CD-103"=>"INFRAESTRUCTURA","CD-110"=>"OPERACIONES","CD-111"=>"INGENIERIA","CD-112"=>"MANTENIMIENTO","CD-200"=>"ADMINISTRACION","CD-202"=>"ALMACEN","CD-222"=>"GERENCIA GENERAL","CD-300"=>"COMERCIAL","CD-105"=>"CALIDAD","CD-102"=>"PROYECTOS","CD-700"=>"YURIMAGUAS");
        elseif($entidad=='02')
            $arrccosto_conta = array(""=>"::Seleccione","CD-120"=>"GALVANIZADO","CD-301"=>"COMERCIAL","CD-105"=>"CALIDAD");            
        asort($arrccosto_conta);
        $selanio     = form_dropdown('anio',$this->ejercicio_model->select($filter1,":::Seleccione:::",""),$anio," size='1' id='anio' class='comboPeque' onchange=\"$('#periodo').val('0000');$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#frmPlanilla').submit();\" ");               
        $selperiodo  = form_dropdown('periodo',$this->periodo_model->select($filter2,":::Seleccione:::","000"),$periodo," size='1' id='periodo' class='comboMedio'");               
        $selplanilla = form_dropdown('planilla',$this->planilla_model->select($filter5,":::Seleccione:::","000"),$planilla," size='1' id='planilla' class='comboPeque' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#periodo').val('');$('#proceso').val('');$('#frmPlanilla').submit();\" ");                       
        $selccosto   = form_dropdown('ccosto',$this->ccosto_model->select($filter3,":::Todos:::",""),$ccosto," size='1' id='ccosto' class='comboMedio'");               
        $selproceso  = form_dropdown('proceso',$this->procesos_model->select($filter4,":::Seleccione:::","00"),$proceso," size='1' id='proceso' class='comboMedio'");               
        $selccosto_conta = form_dropdown('ccosto_conta',$arrccosto_conta,$ccosto_conta," size='1' id='ccosto_conta' class='comboMedio' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#ccosto').val('');submit();\" ");               
        $fila = "";
        $fila2 = "";
        $fila3 = "";
        $fila4 = "";
        $fila5 = "";
        $fila6 = "";
        $filter = new stdClass();
        $filter_not       = new stdClass();
        $filter->periodo  = $periodo;
        $filter->ccosto   = $ccosto;
        $filter->estado   = '01';
        $filter->proceso  = $proceso;
        $filter->planilla = $planilla;
        if($ccosto_conta!="")  $filter->ccosto_conta = $ccosto_conta;
        $conAFP = array();
        $sinAFP = array();
        $planillas_sin_cuenta_tmp = array();
        $consolidado_cheque = 0;
        $consolidado_planilla = 0;
        $consolidado_abono_dif_planilla = 0;
        $consolidado_abono_no_planilla = 0;
        $consolidado_dolar = 0;
        $warning       = "";
        $planillas_con_cuenta = array();
        $planillas_sin_cuenta = array();
        if($proceso != "00" && $periodo!="000"){
            $planillas = $this->planillas_model->getDetallePlanilla($filter, $filter_not);
            if($planilla=='01'){/*Uno con planilla de recibo de honorarios rhp*/
                $obj = new stdClass();
                $obj->periodo  = $periodo;
                $periodo_rhp   = $this->periodo_model->listar($obj);
                $mes_rph       = isset($periodo_rhp->Mes_Id) ? $periodo_rhp->Mes_Id : "";
                $obj = new stdClass();
                $obj->mes      = $mes_rph;
                $obj->planilla = "04";
                $periodo_rhp   = $this->periodo_model->listar($obj);
                $periodo2      = isset($periodo_rhp->Periodo_Id) ? $periodo_rhp->Periodo_Id : "";
                $filter->periodo  = $periodo2;
                $filter->planilla = "04";
                $planillas2 = $this->planillas_model->getDetallePlanilla($filter, $filter_not); 
                $planillas  = array_merge($planillas,$planillas2);
            }      
            
            /*Con_cuenta*/
            foreach($planillas as $item => $val){
                if(trim($val->Nro_cta) != ''){
                    $planillas_con_cuenta[]= $val;
                }
            }
            /*Sin_cuenta*/
            foreach($planillas as $item => $val){
                if(trim($val->Nro_cta) == ''){
                    $planillas_sin_cuenta[]= $val;
                }
            }            

            $k = 1;
            $basico        = 0;
            $dsemanal      = 0;
            $reintegro     = 0;
            $reintegro_afecto     = 0;
            $reintegro_inafecto     = 0;
            $hdoble        = 0;
            $asignacion    = 0;
            $hora_extra    = "";
            $hora_doble    = "";
            $bonificacion  = 0;
            $no_tributario = 0;
            $hextra        = 0;
            $ing_4TA       = 0;
            $gratificacion = 0;
            $bono_extra_ley = 0;
            $t_ingresos    = 0;
            $permiso_sin_goce = 0;
            $tardanza      = 0;
            $onp_fondo     = 0;
            $afp_fondo     = 0;
            $afp_com       = 0;
            $afp_pri       = 0;
            $retencion     = 0;
            $adelanto      = 0;
            $prestamos     = 0;
            $comedor       = 0;
            $dscto_adicional = 0;
            $dscto_eps     = 0;
            $dscto_4TA = 0;
            $t_descuentos  = 0;
            $essalud       = 0;
            $senati        = 0;
            $sctr_salud    = 0;
            $sctr_pension  = 0;
            $t_aportes     = 0;
            $t_neto        = 0; 
            $t_fuera       = 0;
            $t_total       = 0;
            $movilidad     = 0;
            $viaticos      = 0;
            $vale_alimento = 0;
            $afp_com_mixta = 0;
            
            $var_export    = array();
            $arr_export_detalle  = array();
            $arr_columns_detalle = array();
            $arr_columns_detalle[]['STRING']  = 'NRO';
            $arr_columns_detalle[]['STRING']  = 'PERSONAL';
            $arr_columns_detalle[]['STRING']  = 'AREA';
            $arr_columns_detalle[]['NUMERIC'] = 'BASICO';
            $arr_columns_detalle[]['NUMERIC'] = 'ASIG FAM.';
            $arr_columns_detalle[]['NUMERIC'] = 'REINTEGRO';
            $arr_columns_detalle[]['NUMERIC'] = 'REINTEGRO INAF.';
            $arr_columns_detalle[]['NUMERIC'] = 'BONIF. EXTRA.';
            $arr_columns_detalle[]['NUMERIC'] = 'BAS. NO TRIB.';
            $arr_columns_detalle[]['NUMERIC'] = 'ING. 4TA';
            $arr_columns_detalle[]['NUMERIC'] = 'MOVILIDAD';
            $arr_columns_detalle[]['NUMERIC'] = 'VIATICOS';
            $arr_columns_detalle[]['NUMERIC'] = 'VALE ALIM.';
            $arr_columns_detalle[]['NUMERIC'] = 'H. EXT S/.';
            $arr_columns_detalle[]['NUMERIC'] = 'H. EXT DOB S/.';
            $arr_columns_detalle[]['NUMERIC'] = 'GRATI SEMESTRAL';
            $arr_columns_detalle[]['NUMERIC'] = 'BONIF. EXT. L29351 ';            
            $arr_columns_detalle[]['NUMERIC'] = 'SUBTOTAL';
            $arr_columns_detalle[]['NUMERIC'] = 'TARDANZA';
            $arr_columns_detalle[]['NUMERIC'] = 'ONP';
            $arr_columns_detalle[]['NUMERIC'] = 'AFP FONDO';
            $arr_columns_detalle[]['NUMERIC'] = 'AFP COM VARIA';
            $arr_columns_detalle[]['NUMERIC'] = 'AFP COM MIXTA';
            $arr_columns_detalle[]['NUMERIC'] = 'AFP SEGURO';
            $arr_columns_detalle[]['NUMERIC'] = 'RETEN 5TA/4TA';
            $arr_columns_detalle[]['NUMERIC'] = 'ADELANTO QUIN';
            $arr_columns_detalle[]['NUMERIC'] = 'PRESTAMO PERS';
            $arr_columns_detalle[]['NUMERIC'] = 'COMEDOR';
            $arr_columns_detalle[]['NUMERIC'] = 'DESC 4TA';
            $arr_columns_detalle[]['NUMERIC'] = 'DESC ADICIONAL';
            $arr_columns_detalle[]['NUMERIC'] = 'DESC EPS';
            $arr_columns_detalle[]['NUMERIC'] = 'SUBTOTAL';
            $arr_columns_detalle[]['NUMERIC'] = 'ESSALUD';
            $arr_columns_detalle[]['NUMERIC'] = 'SENATI';
            $arr_columns_detalle[]['NUMERIC'] = 'SCTR SALUD';
            $arr_columns_detalle[]['NUMERIC'] = 'SCTR PENSION';
            $arr_columns_detalle[]['NUMERIC'] = 'SUBTOTAL';
            $arr_columns_detalle[]['NUMERIC'] = 'NETO REMUNERACION';
            $arr_columns_detalle[]['NUMERIC'] = 'NETO OTROS';
            $arr_columns_detalle[]['NUMERIC'] = 'TOTAL';
            foreach ($planillas as $key => $value) {
                if(trim($value->Codigo_Auxiliar)=="")  $warning = "Existe personas que tienen no tiene centro de costo o esta mal asignado, favor verificar el detalle";
                $color = "#0000FF";
                if($value->Afp_Id == '99'){
                    array_push ($sinAFP, $value);
                }
                elseif($value->Afp_Id!='99'){
                    array_push ($conAFP, $value);
                }
                $total_ingresos = $value->basico_diario + $value->asignacion + $value->reintegro + $value->reintegro_inafecto + $value->bonificacion + $value->no_tributario+ $value->ing_4TA+ $value->movilidad+ $value->viaticos + $value->montoh_extras + $value->hdoble + $value->gratificacion + $value->bono_extra_ley;
                $total_descuentos = $value->tardanza + $value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_com_mixta + $value->afp_pri_seg + $value->retencion + $value->adelanto + $value->prestamos + $value->dscto_comedor + $value->dscto_4TA+ $value->dscto_adicional + $value->dscto_eps;
                $total_aportes = $value->essalud + $value->senati + $value->sctr_salud + $value->sctr_pension;
                $total_neto = $value->basico_diario + $value->asignacion + $value->reintegro  + $value->reintegro_inafecto  + $value->bonificacion + @$value->viaticos+ @$value->movilidad + $value->gratificacion + $value->bono_extra_ley - $total_descuentos;
                $total_fuera = $value->no_tributario + $value->montoh_extras + $value->hdoble;                    
                if($total_neto < 0) {
                    $color = "#FF0000";
                }
                //if($total_neto == 0 && $total_fuera == 0 && $value->basico_diario == 0) {
               if($total_neto < 0)   continue;
                $arr_data = array();

                $fila .= "<tr>";
                $fila .= "<td align='left'>" . $k . "</td>";
                $arr_data[] = $k;

                $fila .= "<td align='left'>" . utf8_encode ($value->Apellido_Paterno) . " " . utf8_encode ($value->Apellido_Materno) . " " . utf8_encode ($value->Nombres) ." - ".$value->Personal_Id. "</td>";
                $arr_data[] = utf8_encode(trim($value->Apellido_Paterno)) . " " . utf8_encode(trim($value->Apellido_Materno)) . ", " . utf8_encode(strtoupper(trim($value->Nombres)));

                $fila .= "<td align='center'>" . $value->Codigo_Auxiliar. "</td>";
                $arr_data[] = $value->Codigo_Auxiliar;

                $fila .= "<td align='right'>" . number_format ($value->basico_diario, 2) . "</td>";
                $arr_data[] = $value->basico_diario;

                $fila .= "<td align='right'>" . number_format ($value->asignacion, 2) . "</td>";
                $arr_data[] = $value->asignacion;

                $fila .= "<td align='right'>" . number_format ($value->reintegro, 2) . "</td>";
                $arr_data[] = $value->reintegro;

                $fila .= "<td align='right'>" . number_format ($value->reintegro_inafecto, 2) . "</td>";
                $arr_data[] = $value->reintegro_inafecto;

                $fila .= "<td align='right'>" . number_format ($value->bonificacion, 2) . "</td>";
                $arr_data[] = $value->bonificacion;

                $fila .= "<td align='right'>" . number_format ($value->no_tributario, 2) . "</td>";
                $arr_data[] = $value->no_tributario;

                $fila .= "<td align='right'>" . number_format ($value->ing_4TA, 2) . "</td>";
                $arr_data[] = $value->ing_4TA;

                $fila .= "<td align='right'>" . number_format ($value->movilidad, 2) . "</td>";
                $arr_data[] = $value->movilidad;

                $fila .= "<td align='right'>" . number_format ($value->viaticos, 2) . "</td>";
                $arr_data[] = $value->viaticos;

                $fila .= "<td align='right'>" . number_format ($value->vale_alimento, 2) . "</td>";
                $arr_data[] = $value->vale_alimento;

                $fila .= "<td align='right'>" . number_format ($value->montoh_extras, 2) . "</td>";
                $arr_data[] = $value->montoh_extras;

                $fila .= "<td align='right'>" . number_format ($value->hdoble, 2) . "</td>";
                $arr_data[] = $value->hdoble;            
                
                $fila .= "<td align='right'>" . number_format ($value->gratificacion, 2) . "</td>";
                $arr_data[] = $value->gratificacion; 
                
                $fila .= "<td align='right'>" . number_format ($value->bono_extra_ley, 2) . "</td>";
                $arr_data[] = $value->bono_extra_ley;                 

                $fila .= "<td align='right' style='background-color: #B0FAB2'>" . number_format ($total_ingresos, 2) . "</td>";
                $arr_data[] = $total_ingresos;

                $fila .= "<td align='right'>" . number_format ($value->tardanza, 2) . "</td>";
                $arr_data[] = $value->tardanza;

                $fila .= "<td align='right'>" . number_format ($value->onp_fondo, 2) . "</td>";
                $arr_data[] = $value->onp_fondo;

                $fila .= "<td align='right'>" . number_format ($value->afp_fondo, 2) . "</td>";
                $arr_data[] = $value->afp_fondo;

                $fila .= "<td align='right'>" . number_format ($value->afp_com_var, 2) . "</td>";
                $arr_data[] = $value->afp_com_var;

                $fila .= "<td align='right'>" . number_format ($value->afp_com_mixta, 2) . "</td>";
                $arr_data[] = $value->afp_com_mixta;

                $fila .= "<td align='right'>" . number_format ($value->afp_pri_seg, 2) . "</td>";
                $arr_data[] = $value->afp_pri_seg;

                $fila .= "<td align='right'>" . number_format ($value->retencion, 2) . "</td>";
                $arr_data[] = $value->retencion;

                $fila .= "<td align='right'>" . number_format ($value->adelanto, 2) . "</td>";
                $arr_data[] = $value->adelanto;

                $fila .= "<td align='right'>" . number_format ($value->prestamos, 2) . "</td>";
                $arr_data[] = $value->prestamos;

                $fila .= "<td align='right'>" . number_format ($value->dscto_comedor, 2) . "</td>";
                $arr_data[] = $value->dscto_comedor;

                $fila .= "<td align='right'>" . number_format ($value->dscto_4TA, 2) . "</td>";
                $arr_data[] = $value->dscto_4TA;

                $fila .= "<td align='right'>" . number_format ($value->dscto_adicional, 2) . "</td>";
                $arr_data[] = $value->dscto_adicional;

                $fila .= "<td align='right'>" . number_format ($value->dscto_eps, 2) . "</td>";
                $arr_data[] = $value->dscto_eps;                    

                $fila .= "<td align='right' style='background-color: #B0FAB2'>" . number_format ($total_descuentos, 2) . "</td>";
                $arr_data[] = $total_descuentos;

                $fila .= "<td align='right'>" . number_format ($value->essalud, 2) . "</td>";
                $arr_data[] = $value->essalud;

                $fila .= "<td align='right'>" . number_format ($value->senati, 2) . "</td>";
                $arr_data[] = $value->senati;

                $fila .= "<td align='right'>" . number_format ($value->sctr_salud, 2) . "</td>";
                $arr_data[] = $value->sctr_salud;

                $fila .= "<td align='right'>" . number_format ($value->sctr_pension, 2) . "</td>";
                $arr_data[] = $value->sctr_pension;

                $fila .= "<td align='right' style='background-color: #B0FAB2'>" . number_format ($total_aportes, 2) . "</td>";
                $arr_data[] = $total_aportes;

                $fila .= "<td align='right' style='background-color: #CEF6F5; font-weight: bold; color: " . $color . "'>" . number_format ($total_neto, 2) . "</td>";
                $arr_data[] = $total_neto;

                $fila .= "<td align='right' style='background-color: #CEF6F5; font-weight: bold; color: " . $color . "'>" . number_format ($total_fuera, 2) . "</td>";
                $arr_data[] = $total_fuera;

                $fila .= "<td align='right' style='background-color: #CEF6F5; font-weight: bold;'>" . number_format ($total_fuera + $total_neto, 2) . "</td>";                    
                $arr_data[] = $total_fuera + $total_neto;

                $fila .= "</tr>";
//                print("<pre>");
//                PRINT_R($arr_data);
//                print("</pre>");
                array_push($arr_export_detalle,$arr_data);
                $k++;
                $basico        = $basico + ($value->basico_diario);
                $asignacion    = $asignacion + ($value->asignacion);
                $reintegro     = $reintegro + ($value->reintegro);
                $reintegro_inafecto  = $reintegro_inafecto + ($value->reintegro_inafecto);
                $bonificacion  = $bonificacion + ($value->bonificacion);
                $no_tributario = $no_tributario + ($value->no_tributario);
                $ing_4TA       = $ing_4TA + ($value->ing_4TA);
                $movilidad     = $movilidad + ($value->movilidad);
                $viaticos      = $viaticos + ($value->viaticos);
                $vale_alimento = $vale_alimento + ($value->vale_alimento);
                $hextra        = $hextra + ($value->montoh_extras);
                $hdoble        = $hdoble + ($value->hdoble);
                $gratificacion = $gratificacion + ($value->gratificacion);
                $bono_extra_ley = $bono_extra_ley + ($value->bono_extra_ley);
                $t_ingresos    = $t_ingresos + ($total_ingresos);
                $tardanza      = $tardanza + ($value->tardanza);
                $onp_fondo     = $onp_fondo + ($value->onp_fondo);
                $afp_fondo     = $afp_fondo + ($value->afp_fondo);
                $afp_com       = $afp_com + ($value->afp_com_var);
                $afp_com_mixta = $afp_com_mixta + ($value->afp_com_mixta);
                $afp_pri       = $afp_pri + ($value->afp_pri_seg);
                $retencion     = $retencion + ($value->retencion);
                $adelanto      = $adelanto + ($value->adelanto);
                $prestamos     = $prestamos + ($value->prestamos);
                $comedor       = $comedor + ($value->dscto_comedor);
                $dscto_4TA = $dscto_4TA + ($value->dscto_4TA);
                $dscto_adicional = $dscto_adicional + ($value->dscto_adicional);
                $dscto_eps     = $dscto_eps + ($value->dscto_eps);
                $t_descuentos  = $t_descuentos + ($total_descuentos);
                $essalud       = $essalud + ($value->essalud);
                $senati        = $senati + ($value->senati);
                $sctr_salud    = $sctr_salud + ($value->sctr_salud);
                $sctr_pension  = $sctr_pension + ($value->sctr_pension);
                $t_aportes     = $t_aportes + ($total_aportes);
                $t_neto        = $t_neto + $total_neto;
                $t_fuera       = $t_fuera + $total_fuera;
                $t_total       = $t_total + $total_neto + $total_fuera;
                $value->t_fuera = $t_fuera;
                $value->t_total = $t_total;                    
            }
            $var_export = array(
                'columns' => $arr_columns_detalle, 
                'rows'    => $arr_export_detalle , 
                'group'   => array('D5:M5'=>'REMUNERACIONES','O5:Z5'=>'DESCUENTOS','AB5:AE5'=>'APORTES','AG5:AI5'=> 'TOTALES'));

            $this->session->set_userdata('data_per_detalle', $var_export);
            $fila .= "<tr>";
            $fila .= "<td align='left' colspan='3'></td>";
            $fila .= "<td align='right'>" . number_format($basico, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($asignacion, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($reintegro, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($reintegro_inafecto, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($bonificacion, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($no_tributario, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($ing_4TA, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($movilidad, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($viaticos, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($vale_alimento, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($hextra, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($hdoble, 2) . "</td>";                
            $fila .= "<td align='right'>" . number_format($gratificacion, 2) . "</td>"; 
            $fila .= "<td align='right'>" . number_format($bono_extra_ley, 2) . "</td>"; 
            $fila .= "<td align='right'>" . number_format($t_ingresos, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($tardanza, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($onp_fondo, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($afp_fondo, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($afp_com, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($afp_com_mixta, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($afp_pri, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($retencion, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($adelanto, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($prestamos, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($comedor, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($dscto_4TA, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($dscto_adicional, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($dscto_eps, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($t_descuentos, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($essalud, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($senati, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($sctr_salud, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($sctr_pension, 2) . "</td>";
            $fila .= "<td align='right'>" . number_format($t_aportes, 2) . "</td>";
            $fila .= "<td align='right' style='font-weight: bold;'>" . number_format($t_neto, 2) . "</td>";
            $fila .= "<td align='right' style='font-weight: bold;'>" . number_format($t_fuera, 2) . "</td>";
            $fila .= "<td align='right' style='font-weight: bold;'>" . number_format($t_total, 2) . "</td>";                
            $fila .= "</tr>";

            /* NO PLANILLA */
            $var_export = array();
            $arr_export_detalle  = array();
            $arr_columns_detalle = array();
            $arr_columns_detalle[]['STRING']  = 'NRO';
            $arr_columns_detalle[]['STRING']  = 'DNI';
            $arr_columns_detalle[]['STRING']  = 'PERSONAL';
            $arr_columns_detalle[]['NUMERIC'] = 'IMPORTE';
            $arr_columns_detalle[]['STRING']  = 'CUENTA';
            $j = 1;
            foreach ($sinAFP as $key => $value) {
                if(trim($value->Nro_cta) != ""){
                    $tot = $value->basico_diario 
                            + $value->dsemanal 
                            + $value->asignacion 
                            + $value->reintegro 
                            + $value->no_tributario 
                            + $value->montoh_extras 
                            + $value->hdoble 
                            + @$value->ing_4TA 
                            + @$value->movilidad 
                            + @$value->viaticos 
                            + $value->gratificacion 
                            + $value->bono_extra_ley 
                            - ($value->tardanza 
                                    + $value->onp_fondo 
                                    + $value->afp_fondo 
                                    + $value->afp_com_var 
                                    + $value->afp_pri_seg 
                                    + $value->prestamos 
                                    + $value->dscto_comedor 
                                    + $value->retencion 
                                    + $value->adelanto  
                                    + @$value->dscto_4TA 
                                    + $value->dscto_adicional 
                                    + $value->permiso_sin_goce);
                    if($tot < 0) continue;
                    $arr_data = array();
                    
                    $fila2 .= "<tr>";
                    $fila2 .= "<td align='left'>" . $j . "</td>";
                    $arr_data[] = $j;
                    
                    $fila2 .= "<td align='right'>".$value->Nro_Doc."</td>";
                    $arr_data[] = $value->Nro_Doc;
                    
                    $fila2 .= "<td align='left'>" . utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)) . "</td>";
                    $arr_data[] = utf8_encode(trim($value->Apellido_Paterno)) . " " . utf8_encode(trim($value->Apellido_Materno)) . ", " . utf8_encode(strtoupper(trim($value->Nombres)));
                    
                    $fila2 .= "<td  align='right'>" . number_format($tot, 2) . "</td>";
                    $arr_data[] = $tot;
                            
                    $fila2 .= "<td>" . $value->Nro_cta . "</td>";
                    $arr_data[] = $value->Nro_cta;
                    
                    $fila2 .= "</tr>";
                    $consolidado_abono_no_planilla = $consolidado_abono_no_planilla + $tot;
                    $j++;

                    array_push($arr_export_detalle,$arr_data); 
                }
            }
            $var_export = array('columns' => $arr_columns_detalle, 'rows' => $arr_export_detalle);
            $this->session->set_userdata('data_per_nopla', $var_export);
            $fila2 .= "<tr>";
            $fila2 .= "<td align='left'></td>";
            $fila2 .= "<td></td>";
            $fila2 .= "<td align='center'><strong>TOTAL</strong></td>";
            $fila2 .= "<td  align='right'><strong>" . number_format($consolidado_abono_no_planilla, 2) . "</strong></td>";
            $fila2 .= "<td align='right'></td>";
            $fila2 .= "</tr>";
            $i = 1;

            /* PLANILLA */
            $var_export = array();
            $arr_export_detalle  = array();
            $arr_columns_detalle = array();
            $arr_columns_detalle[]['STRING']  = 'NRO';
            $arr_columns_detalle[]['STRING']  = 'DNI';
            $arr_columns_detalle[]['STRING']  = 'PERSONAL';
            $arr_columns_detalle[]['NUMERIC'] = 'IMPORTE';
            $arr_columns_detalle[]['STRING']  = 'CUENTA';
            $var_pla_row = 0 ;
            $var_pla_detail = array();
            $var_bbva_pago = 0;
            foreach ($conAFP as $key => $value) {
                if(trim($value->Nro_cta) != ""){ 
                    $tot = $value->basico_diario
                            + $value->bonificacion 
                            + $value->dsemanal 
                            + $value->asignacion 
                            + $value->reintegro 
                            + $value->reintegro_afecto
                            + $value->reintegro_inafecto
                            + @$value->movilidad 
                            + @$value->viaticos 
                            + $value->gratificacion
                            + $value->bono_extra_ley
                            - ($value->onp_fondo 
                                    + $value->afp_fondo 
                                    + $value->afp_com_var 
                                    + $value->afp_pri_seg 
                                    + $value->prestamos 
                                    + $value->dscto_comedor 
                                    + $value->dscto_adicional  
                                    + $value->dscto_eps
                                    + $value->tardanza 
                                    + $value->retencion 
                                    + $value->adelanto 
                                    + $value->permiso_sin_goce
                                    + $value->afp_com_mixta);  
                    
                    if($tot <= 0)  continue;
                    $arr_data = array();
                    
                    $fila3 .= "<tr>";
                    $fila3 .= "<td align='left'>" . $i . "</td>";
                    $arr_data[] = $i;
                    
                    $fila3 .= "<td align='right'>".$value->Nro_Doc."</td>";
                    $arr_data[] = $value->Nro_Doc;
                    
                    $fila3 .= "<td align='left'>" . utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)) . "</td>";
                    $arr_data[] = utf8_encode(trim($value->Apellido_Paterno)) . " " . utf8_encode(trim($value->Apellido_Materno)) . ", " . utf8_encode(strtoupper(trim($value->Nombres)));
                    
                    $fila3 .= "<td  align='right'>" . number_format($tot, 2) . "</td>";
                    $arr_data[] = $tot;
                            
                    $fila3 .= "<td>" . $value->Nro_cta . "</td>";
                    $arr_data[] = $value->Nro_cta;

                    $query = $this->db->query("SP_SEL_SCIRE_INTERF_BANK '".$value->Personal_Id."' , '".number_format($tot,2)."' ");
                    
                    if($query->row()->cta_lenght == 20){
                        $var_pla_row++;
                        $var_bbva_pago = $var_bbva_pago + $tot; 
                        $var_pla_detail[] = $query->row();
                    }
                    $fila3 .= "</tr>";
                    $consolidado_planilla = $consolidado_planilla + $tot;
                    $i++;
                    array_push($arr_export_detalle,$arr_data);
                }
            }
            $data_header_bbva = array('total'=> number_format($var_bbva_pago,2) , 'rows' => $var_pla_row , 'type' => $var_type_interf);
            $arr_interf_bbva = array('header' => $data_header_bbva , 'detail' => $var_pla_detail );
            $this->session->set_userdata('data_interf_bbva', $arr_interf_bbva);
            $var_export = array('columns' => $arr_columns_detalle, 'rows' => $arr_export_detalle);
            $this->session->set_userdata('data_per_planilla', $var_export);
            $fila3 .= "<tr>";
            $fila3 .= "<td></td>";
            $fila3 .= "<td></td>";
            $fila3 .= "<td><strong>TOTAL</strong></td>";
            $fila3 .= "<td><strong>" . number_format($consolidado_planilla,2) . "</strong></td>";
            $fila3 .= "<td></td>";
            $fila3 .= "</tr>";
            
            /* DIFERENCIA PLANILLA */
            $var_export = array();
            $arr_export_detalle  = array();
            $arr_columns_detalle = array();
            $arr_columns_detalle[]['STRING']  = 'NRO';
            $arr_columns_detalle[]['STRING']  = 'DNI';
            $arr_columns_detalle[]['STRING']  = 'PERSONAL';
            $arr_columns_detalle[]['NUMERIC'] = 'IMPORTE';
            $arr_columns_detalle[]['STRING']  = 'CUENTA';
            $n = 1;
            foreach ($conAFP as $key => $value) {
                if(trim($value->Nro_cta) != ""){
                    $tot = $value->no_tributario 
                            + $value->montoh_extras 
                            + $value->hdoble;
                    $total_descuentos = $value->tardanza + $value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_com_mixta + $value->afp_pri_seg + $value->retencion + $value->adelanto + $value->prestamos + $value->dscto_comedor + $value->dscto_4TA+ $value->dscto_adicional + $value->dscto_eps;
                    $total_neto = $value->basico_diario + $value->asignacion + $value->reintegro  + $value->reintegro_inafecto  + $value->bonificacion + @$value->viaticos+ @$value->movilidad + $value->gratificacion + $value->bono_extra_ley - $total_descuentos;
                    if($total_neto < 0) continue;
                    $arr_data = array();
                    $fila4 .= "<tr>";
                    $fila4 .= "<td align='left'>" . $n . "</td>";
                    $arr_data[] = $n;
                    
                    $fila4 .= "<td align='right'>" . $value->Nro_Doc . "</td>";
                    $arr_data[] = $value->Nro_Doc;
                    
                    $fila4 .= "<td align='left'>" . utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)) . "</td>";
                    $arr_data[] = utf8_encode(trim($value->Apellido_Paterno)) . " " . utf8_encode(trim($value->Apellido_Materno)) . ", " . utf8_encode(strtoupper(trim($value->Nombres)));
                    
                    $fila4 .= "<td  align='right'>" . number_format($tot, 2) . "</td>";
                    $arr_data[] = $tot;
                    
                    $fila4 .= "<td>" . $value->Nro_cta . "</td>";
                    $arr_data[] = $value->Nro_cta;
                    
                    $fila4 .= "</tr>";
                    $consolidado_abono_dif_planilla = $consolidado_abono_dif_planilla + $tot;
                    $n++;
                    array_push($arr_export_detalle,$arr_data);
                }
            }
            $var_export = array('columns' => $arr_columns_detalle, 'rows' => $arr_export_detalle);
            $this->session->set_userdata('data_per_difpla', $var_export);
            $fila4 .= "<tr>";
            $fila4 .= "<td></td>";
            $fila4 .= "<td></td>";
            $fila4 .= "<td><strong>TOTAL</strong></td>";
            $fila4 .= "<td align='rigth'><strong>" . number_format($consolidado_abono_dif_planilla,2) . "</strong></td>";
            $fila4 .= "<td></td>";
            $fila4 .= "</tr>";

            /* PAGO EN EFECTIVO */
            $var_export = array();
            $arr_export_detalle  = array();
            $arr_columns_detalle = array();
            $arr_columns_detalle[]['STRING']  = 'NRO';
            $arr_columns_detalle[]['STRING']  = 'DNI';
            $arr_columns_detalle[]['STRING']  = 'PERSONAL';
            $arr_columns_detalle[]['NUMERIC'] = 'IMPORTE';
            $arr_columns_detalle[]['STRING']  = 'TIPO';
            $arr_columns_detalle[]['STRING']  = 'FIRMA';
            $l = 1;
            foreach ($planillas_sin_cuenta as $key => $value) {
                $tot = $value->basico_diario 
                        + $value->dsemanal 
                        + $value->no_tributario 
                        + $value->montoh_extras 
                        + $value->asignacion 
                        + $value->bonificacion 
                        + $value->reintegro 
                        + @$value->reintegro_afecto
                        + @$value->reintegro_inafecto 
                        + $value->hdoble 
                        + $value->movilidad 
                        + $value->viaticos 
                        + $value->gratificacion
                        + $value->bono_extra_ley
                        - ($value->onp_fondo 
                                + $value->afp_fondo 
                                + $value->afp_com_var 
                                + $value->afp_pri_seg 
                                + $value->prestamos 
                                + $value->dscto_comedor 
                                + $value->dscto_adicional 
                                + $value->tardanza 
                                + $value->retencion 
                                + $value->adelanto 
                                + $value->permiso_sin_goce 
                                + $value->afp_com_mixta);
                if($tot < 0)    continue;
                $arr_data = array();
                $fila5 .= "<tr>";
                $fila5 .= "<td align='left'>" . $l . "</td>";
                $arr_data[] = $l;
                $fila5 .= "<td align='right'>" . $value->Nro_Doc . "</td>";
                $arr_data[] = $value->Nro_Doc;
                $fila5 .= "<td align='left'>" . utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)) . "</td>";
                $arr_data[] = utf8_encode(trim($value->Apellido_Paterno)) . " " . utf8_encode(trim($value->Apellido_Materno)) . ", " . utf8_encode(strtoupper(trim($value->Nombres)));
                $fila5 .= "<td  align='right'>" . number_format($tot, 2) . "</td>";
                $arr_data[] = $tot;
                switch ($value->Planilla_Id) {
                    case '01':
                        $var_type_planilla = "EMPLEADO";
                        break;
                    case '02':
                        $var_type_planilla = "OBREROS";
                        break;
                    case '04':
                        $var_type_planilla = "4TA CATEG.";
                        break;
                    default:
                        $var_type_planilla = "OBREROS";
                        break;
                }
                $fila5 .= "<td>" . $var_type_planilla. "</td>";
                $arr_data[] = $var_type_planilla;
                $arr_data[] = "                                                 ";
                $fila5 .= "</tr>";
                $consolidado_cheque = $consolidado_cheque + $tot;
                $l++;
                array_push($arr_export_detalle,$arr_data);
            }                
            $var_export = array('columns' => $arr_columns_detalle, 'rows' => $arr_export_detalle);
            $this->session->set_userdata('data_per_efectivo', $var_export);
            $fila5 .= "<tr>";
            $fila5 .= "<td></td>";
            $fila5 .= "<td></td>";
            $fila5 .= "<td><strong>TOTAL</strong></td>";
            $fila5 .= "<td><strong>" . number_format($consolidado_cheque,2) . "</strong></td>";
            $fila5 .= "</tr>";
        }
        
        /*Reportes en excel*/
        if($tipoexcel != "") {
            switch ($tipoexcel){
                case "1":
                    $arr_columns = array();
                    $arr_data = array();
                    $index = 0;
                    $arr_columns[0]['STRING'] = 'NRO';
                    $arr_columns[1]['STRING'] = 'DNI';
                    $arr_columns[2]['STRING'] = 'PERSONAL';
                    $arr_columns[3]['STRING'] = 'IMPORTE S/.';
                    $arr_columns[4]['STRING'] = 'NRO CUENTA';
                    
                    $monto_total = 0;
                    foreach($sinAFP as $key => $value) {
                        if(trim($value->Nro_cta) != "") {
                            $tot = $value->basico_diario + $value->dsemanal + $value->no_tributario + $value->hdoble + $value->montoh_extras + $value->asignacion + $value->reintegro + $value->reintegro_afecto + $value->bonificacion+@$value->movilidad + @$value->viaticos - ($value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_pri_seg + $value->prestamos + $value->dscto_comedor + $value->dscto_adicional + $value->tardanza + $value->retencion  +  $value->adelanto + @$value->dscto_4TA + $value->permiso_sin_goce) ;
                            if($tot == 0) {
                                continue;
                            }
                            $arr_data[$index] = array(
                                $index + 1,
                                $value->Nro_Doc,
                                utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)),
                                number_format($tot, 2),
                                $value->Nro_cta
                                
                            );
                            $monto_total = $monto_total + $tot;
                            $index++;
                        }
                    }
                    $arr_data[$index] = array(
                            '',
                            '',
                            'Total',
                            number_format($monto_total, 2),
                            ''
                    );                    
                    $this->reports_model->rpt_general('4TA categoria', '4TA categoria '.$var_periodo, $arr_columns, $arr_data);
                    break;
                case "2":
                    $arr_columns = array();
                    $arr_data = array();
                    $index = 0;
                    $arr_columns[0]['STRING'] = 'NRO';
                    $arr_columns[1]['STRING'] = 'DNI';
                    $arr_columns[2]['STRING'] = 'PERSONAL';
                    $arr_columns[3]['STRING'] = 'IMPORTE S/.';
                    $arr_columns[4]['STRING'] = 'NRO CUENTA';
                    $monto_total = 0;
                    foreach($conAFP as $key => $value) {
                        if(trim($value->Nro_cta) != "") {
                            if($param == "e") {
                                $tot = $value->basico_diario + $value->bonificacion + $value->asignacion + $value->reintegro + $value->reintegro_inafecto +$value->movilidad + $value->viaticos- ($value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_pri_seg + $value->prestamos + $value->dscto_comedor + $value->tardanza + $value->retencion + $value->adelanto+ $value->dscto_adicional + @$value->permiso_sin_goce );
                            }
                            elseif($param == "o") {
                                $tot = $value->basico_diario + $value->dsemanal + $value->asignacion + $value->reintegro + $value->reintegro_afecto - ($value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_pri_seg + $value->prestamos + $value->dscto_comedor + $value->dscto_adicional  + $value->tardanza + $value->retencion + $value->adelanto + @$value->permiso_sin_goce);
                            }
                            $arr_data[$index] = array(
                                $index + 1,
                                $value->Nro_Doc,
                                utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)),
                                number_format($tot, 2),
                                $value->Nro_cta
                            );
                            $monto_total = $monto_total + $tot;
                            $index++;
                        }
                    }
                    $arr_data[$index] = array(
                            '',
                            '',
                            'Total',
                            number_format($monto_total, 2),
                            ''
                        );
                    $this->reports_model->rpt_general('Planilla - '.$var_type, 'Reporte de Planillas - '.$var_type.' - '.$var_periodo, $arr_columns, $arr_data);
                    break;
                    
                /*Planilla completa*/
                case "3":
                    if($param == "o"){
                        $arr_headers = $this->prepareColumnsDetalleObrero();
                        $arr_data = $this->prepareDataDetalleObrero($planillas);
                    }
                    elseif($param == "e") {
                        $arr_headers = $this->prepareColumnsDetalleEmpleado();
                        $arr_data = $this->prepareDataDetalleEmpleado($planillas);
                    }
                    $this->reports_model->rpt_general('Detalle '.$var_type, 'Detalle de Planillas - '.$var_type.' - '.$var_periodo, $arr_headers, $arr_data);
                    break;
                case "4":
                    //Diferencia de planilla                    
                    $arr_columns = array();
                    $arr_data = array();
                    $index = 0;
                    $arr_columns[0]['STRING'] = 'NRO';
                    $arr_columns[1]['STRING'] = 'DNI';
                    $arr_columns[2]['STRING'] = 'PERSONAL';
                    $arr_columns[3]['STRING'] = 'IMPORTE S/.';
                    $arr_columns[4]['STRING'] = 'NRO CUENTA';
                    $monto_total = 0;
                    foreach($conAFP as $key => $value) {
                        if(trim($value->Nro_cta) != "") {                         
                            $tot = $value->no_tributario + $value->montoh_extras + $value->bonificacion + $value->hdoble;
                            if($tot == 0) {
                                continue;
                            }
                            $arr_data[$index] = array(
                                $index + 1,
                                $value->Nro_Doc,
                                utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)),
                                number_format($tot, 2),
                                $value->Nro_cta
                                
                            );
                            $monto_total = $monto_total + $tot;
                            $index++;
                        }
                    }
                    //No planilla
                    foreach($sinAFP as $key => $value) {
                        if(trim($value->Nro_cta) != "") {
                            //$tot = $value->basico_diario + $value->dsemanal + $value->no_tributario + $value->montoh_extras + $value->asignacion + $value->bonificacion - ($value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_pri_seg + $value->prestamos + $value->dscto_comedor + $value->dscto_adicional + $value->tardanza + $value->retencion + $value->adelanto);
                            $tot = $value->basico_diario + $value->dsemanal + $value->asignacion + $value->reintegro + $value->bonificacion + $value->no_tributario + $value->montoh_extras + $value->hdoble +$value->movilidad + $value->viaticos- ($value->tardanza + $value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_pri_seg + $value->prestamos + $value->dscto_comedor + $value->retencion + $value->adelanto+ @$value->permiso_sin_goce);
                            if($tot == 0) {
                                continue;
                            }
                            $arr_data[$index] = array(
                                $index + 1,
                                $value->Nro_Doc,
                                utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)),
                                number_format($tot, 2),
                                $value->Nro_cta
                                
                            );
                            $monto_total = $monto_total + $tot;
                            $index++;
                        }
                    }
                    $arr_data[$index] = array(
                            '',
                            '',
                            'Total',
                            number_format($monto_total, 2),
                            ''
                    );
                    $this->reports_model->rpt_general('rpt_planilla ', 'Reporte de Planillas - '.$var_type.' - '.$var_periodo, $arr_columns, $arr_data);
                    break;
                case "5":
                    $arr_columns = array();
                    $arr_data = array();
                    $index = 0;
                    $arr_columns[0]['STRING'] = 'NRO';
                    $arr_columns[1]['STRING'] = 'DNI';
                    $arr_columns[2]['STRING'] = 'PERSONAL';
                    $arr_columns[3]['STRING'] = 'IMPORTE S/.';
                    $arr_columns[4]['STRING'] = 'TIPO';
                    $arr_columns[5]['STRING'] = 'FIRMA';
                    
                    $monto_total = 0;
                    foreach ($planillas_sin_cuenta as $key => $value) {
                        if($param == "e") {
                            $tot = $value->basico_diario + $value->asignacion + $value->reintegro + $value->reintegro_inafecto+ $value->bonificacion + $value->no_tributario+$value->movilidad + $value->viaticos - ($value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_pri_seg + $value->prestamos + $value->dscto_comedor + $value->tardanza + $value->retencion + $value->adelanto+ @$value->dscto_4TA+ $value->dscto_adicional + @$value->permiso_sin_goce);
                        }
                        if($param == "o") { //txt_efect
                            $tot = $value->basico_diario + $value->dsemanal + $value->no_tributario + $value->montoh_extras + $value->asignacion + $value->bonificacion + $value->reintegro + @$value->reintegro_afecto+ @$value->reintegro_inafecto + $value->hdoble - ($value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_pri_seg + $value->prestamos + $value->dscto_comedor + $value->dscto_adicional + $value->tardanza + $value->retencion + $value->adelanto+ @$value->permiso_sin_goce);
                        }
                        if($tot == 0) {
                            continue;
                        }
                        switch ($value->Planilla_Id) {
                            case '01':
                                $var_type_planilla = "EMPLEADO";
                                break;
                            case '02':
                                $var_type_planilla = "OBREROS";
                                break;
                            case '04':
                                $var_type_planilla = "4TA CATEG.";
                                break;
                            default:
                                $var_type_planilla = "OBREROS";
                                break;
                        }
                        $arr_data[$index] = array(
                            $index + 1,
                            $value->Nro_Doc,
                            utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)),
                            number_format($tot,2),
                            $var_type_planilla,
                            "                                   "
                        );
                        $monto_total = $monto_total + $tot;
                        $index++;
                    }
                    $arr_data[$index] = array(
                            '',
                            '',
                            'Total',
                            number_format($monto_total, 2),
                            ''
                    );
                    
                    $this->reports_model->rpt_general('Efectivo - '.$var_type, 'Efectivo - '.$var_type.' - '.$var_periodo, $arr_columns, $arr_data);
                    break;
                case "9":
                    $arr_columns = array();
                    $arr_data = array();
                    $index = 0;
                    $arr_columns[0]['STRING'] = 'NRO';
                    $arr_columns[1]['STRING'] = 'DNI';
                    $arr_columns[2]['STRING'] = 'PERSONAL';
                    $arr_columns[3]['STRING'] = 'IMPORTE S/.';
                    $arr_columns[4]['STRING'] = 'NRO CUENTA';
                    $monto_total = 0;
                    foreach($conAFP as $key => $value) {
                        if(trim($value->Nro_cta) != "") {
                            if($param == "e"){
                                $tot = $value->no_tributario;
                            }
                            elseif($param == "o"){
                                $tot = $value->no_tributario + $value->montoh_extras + $value->bonificacion + $value->hdoble;
                            }
                            if($tot == 0) {
                                continue;
                            }
                            $arr_data[$index] = array(
                                $index + 1,
                                $value->Nro_Doc,
                                utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . ", " . utf8_encode(strtoupper($value->Nombres)),
                                number_format($tot, 2),
                                $value->Nro_cta
                            );
                            $monto_total = $monto_total + $tot;
                            $index++;
                        }
                    }
                    $arr_data[$index] = array(
                            '',
                            '',
                            'Total',
                            number_format($monto_total, 2),
                            ''
                    );
                    $this->reports_model->rpt_general('diferencia planilla - '.$var_type, 'diferencia planilla - '.$var_type.' - '.$var_periodo, $arr_columns, $arr_data);
                    break;
                default:
                    break;
            }
        }
        $data['fila']        = $fila;
        $data['fila2']       = $fila2;
        $data['fila3']       = $fila3;
        $data['fila4']       = $fila4;
        $data['fila5']       = $fila5;
        $data['fila6']       = $fila6;
        $data['warning']     = $warning;
        $data['view_export'] = count(@$planillas);
        $data['consolidado_abono_dif_planilla'] = $consolidado_abono_dif_planilla;
        $data['consolidado_abono_no_planilla'] = $consolidado_abono_no_planilla;
        $data['abono']       = $consolidado_abono_dif_planilla + $consolidado_abono_no_planilla;
        $data['selperiodo']  = $selperiodo;
        $data['totalplanilla'] = $consolidado_abono_dif_planilla + $consolidado_abono_no_planilla + $consolidado_cheque + $consolidado_planilla;
        $data['selanio']     = $selanio;
        $data['selccosto']   = $selccosto;
        $data['selccosto_conta'] = $selccosto_conta;
        $data['selproceso']   = $selproceso;
        $data['selplanilla']  = $selplanilla;
        $data['param']        = $param;
        $data['cheque']       = $consolidado_cheque;
        $data['online']       = $consolidado_planilla;
        $data['dolar']        = $consolidado_dolar;
        $this->load->view(scire . "scire_listar", $data);
    }
    
    public function gastos_area(){
        $anio          = $this->input->get_post('anio');
        $mes           = $this->input->get_post('mes');
        $periodo       = $this->input->get_post('periodo');
        $proceso       = $this->input->get_post('proceso');
        $ccosto        = $this->input->get_post('ccosto');
        $ccosto_conta  = $this->input->get_post('ccosto_conta');
        $tipoexcel     = $this->input->get_post('tipoexcel');
        $planilla      = $this->input->get_post('planilla');
        $arrexcel      = array();
        $conexcel      = array();
        if($periodo=="")  $periodo = "000";
        if($proceso=="")  $proceso = "00";
        if($anio=="")     $anio    = "2013";
        $filter  = new stdClass();
        $filter->anio = $anio;
        $filter->mesi = "000042";
        $filter2 = new stdClass();
        $filter2->tipo = array("00","20","21");   
        $filter3 = new stdClass();
        $filter3->estado = '01';
        $filter3->entidad = '';
        if($ccosto_conta!="")  $filter3->ccosto_conta = $ccosto_conta;
//        $filter4 = new stdClass();
//        $filter4->proceso = array('01','02');  
//        $entidad = $this->session->userdata('entidad');
//        if($entidad=='01')
//            $arrccosto_conta = array(""=>":::Todos:::","CD-103"=>"INFRAESTRUCTURA","CD-110"=>"OPERACIONES","CD-111"=>"INGENIERIA","CD-112"=>"MANTENIMIENTO","CD-200"=>"ADMINISTRACION","CD-202"=>"ALMACEN","CD-222"=>"GERENCIA GENERAL","CD-300"=>"COMERCIAL","CD-105"=>"CALIDAD","CD-102"=>"PROYECTOS","CD-700"=>"YURIMAGUAS");
//        elseif($entidad=='02')
//            $arrccosto_conta = array(""=>"::Seleccione","CD-120"=>"GALVANIZADO","CD-301"=>"COMERCIAL GALV","CD-105"=>"CALIDAD");            
        $arrccosto_conta = array(""=>":::Todos:::","CD-103"=>"INFRAESTRUCTURA","CD-110"=>"OPERACIONES","CD-111"=>"INGENIERIA","CD-112"=>"MANTENIMIENTO","CD-200"=>"ADMINISTRACION","CD-202"=>"ALMACEN","CD-222"=>"GERENCIA GENERAL","CD-300"=>"COMERCIAL","CD-105"=>"CALIDAD","CD-102"=>"PROYECTOS","CD-700"=>"YURIMAGUAS","CD-120"=>"GALVANIZADO","CD-301"=>"COMERCIAL GALVANIZADO");
        asort($arrccosto_conta);
        $arrplanilla   = array(""=>"::Seleccione","02"=>"OBRERO","01"=>"EMPLEADO","04"=>"4TA CATEG.");  
        $selanio         = form_dropdown('anio',$this->ejercicio_model->select(new stdClass(),":::Seleccione:::",""),$anio," size='1' id='anio' class='comboPeque' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#frmPlanilla').submit();\" ");               
        $selmes          = form_dropdown('mes',$this->mes_model->select($filter,":::Seleccione:::",""),$mes," size='1' id='mes' class='comboPeque' style='width:200px;' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');\" ");
        $seltrabajador   = form_dropdown('planilla[]',$arrplanilla,$planilla," id='planilla[]' class='comboPequeMulti' multiple='multiple' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');\" ");
        $selccosto       = form_dropdown('ccosto',$this->ccosto_model->select($filter3,":::Todos:::",""),$ccosto," size='1' id='ccosto' class='comboMedio' style='width:200px;'");               
        $selccosto_conta = form_dropdown('ccosto_conta',$arrccosto_conta,$ccosto_conta," size='1' id='ccosto_conta' class='comboMedio' style='width:200px;' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#ccosto').val('');submit();\" ");               
       // $selproceso      = form_dropdown('proceso',$this->procesos_model->select($filter4,"::Seleccione:::","00"),$proceso," size='1' id='ccosto' class='comboMedio'");               
        $fila  = "";
        $fila2 = "";
        $fila3 = "";
        $arrPersonas = array();
        if($mes != "" && $anio != ""){
            $filter     = new stdClass();
            $filter_not = new stdClass();
            $filter_not->persona   = "000029";            
            $filter->mes           = $mes;
            $filter->proceso       = "01"; 
            $filter->entidad       = "";
            if($planilla!="")      $filter->planilla  = $planilla;
            if($ccosto!="")        $filter->ccosto = $ccosto;
            if($ccosto_conta!="")  $filter->ccosto_conta = $ccosto_conta;
            $detalle = $this->planillas_model->get($filter,$filter_not);
            $filter->proceso       = "02"; 
            $filter->concepto      = "000217";
            $detalle2 = $this->planillas_model->get($filter,$filter_not);
            $detalle  = array_merge($detalle,$detalle2);
            /*Ordenamos el array por campo Ccosto_conta*/
            $codigo_aux2 = array();
            foreach($detalle as $indice => $value){
                $codigo_aux2[] = $value->Codigo_Auxiliar2;
                $tipo_trab2[]  = $value->Tipo_Trabajador_Id;
                $paterno2[]    = $value->Apellido_Paterno;
                $planilla2[]   = $value->Planilla_Id;
            }
            array_multisort($planilla2, SORT_ASC,$paterno2,SORT_ASC,$detalle);
            $personal_id_ant = 0;
            foreach($detalle as $item => $value){
                $personal_id = $value->Personal_Id;
                $concepto_id = $value->Concepto_Id;
                $arrTipo[$personal_id]     = $value->Tipo_Trabajador_Id;
                $arrCcosto[$personal_id]   = $value->Ccosto_Id;
                $arrCcostoCont[$personal_id]   = $value->Codigo_Auxiliar2;
                $arrPlanilla[$personal_id] = $value->Planilla_Id;
                $arrPersonas[$personal_id][$concepto_id] = $value->valor;
            }
            $t_basico = 0;
            $t_dsemanal         = 0;
            $t_reintegro        = 0;
            $t_reintegro_ina    = 0;
            $t_no_tributario    = 0;   
            $t_asignacion       = 0;
            $t_bonificacion     = 0;
            $t_hextra           = 0;
            $t_hdoble           = 0;
            $t_ing4ta           = 0;
            $t_total_ingresos   = 0;
            $t_tardanza         = 0;
            $t_onp              = 0;
            $t_afp_fondo        = 0;
            $t_afp_comision     = 0;
            $t_afp_seguro       = 0;
            $t_adelanto         = 0;
            $t_retencion        = 0;
            $t_prestamo         = 0;
            $t_comedor          = 0;
            $t_dscto_4ta        = 0;
            $t_dscto_adic       = 0;
            $t_total_descuentos = 0;
            $t_essalud          = 0;
            $t_senati           = 0;
            $t_sctr_salud       = 0;
            $t_sctr_pension     = 0;
            $t_total_aportes    = 0;  
            $t_rem              = 0;
            $t_neto             = 0;
            $t_fuera            = 0;
            $t_total            = 0;
            $consolidado        = array();
            $consolidado_conta  = array();
            if(count($arrPersonas)>0){
                $data_pie = array();
                $jj = 1;
                foreach($arrPersonas as $personal_id => $conceptos){
                    $data = array();
                    $ccosto_id = $arrCcosto[$personal_id];
                    $ccosto_conta_id = $arrCcostoCont[$personal_id];
                    $tipo_id   = $arrTipo[$personal_id];
                    $planilla_id = $arrPlanilla[$personal_id];
                    $color     = "#0000FF";
                    $basico     = 0;
                    $dsemanal   = 0;
                    $reintegro  = 0;
                    $reintegro_ina  = 0;
                    $retencion  = 0;
                    $tardanza   = 0;
                    $adelanto   = 0;
                    $basico_notrib = 0;
                    $asignacion    = 0;
                    $bonificacion  = 0;
                    $hextra        = 0;
                    $hdoble        = 0;
                    $ing4ta        = 0;
                    $essalud       = 0;
                    $senati        = 0;
                    $sctr_salud    = 0;
                    $sctr_pension  = 0;
                    $onp           = 0;
                    $afp_fondo     = 0;
                    $afp_comision  = 0;
                    $afp_seguro    = 0;
                    $prestamo      = 0;
                    $comedor       = 0;
                    $dscto_4ta     = 0;
                    $dscto_adic    = 0;
                    $essalud       = 0;
                    $senati        = 0;
                    $sctr_salud    = 0;
                    $sctr_pension  = 0;
                    /*Definicion de codigo de conceptos*/
                    if($planilla_id=='01' || $planilla_id == '02'){
                        /*Ingresos*/
                        $cod_basico        = "000052";
                        $cod_dsemanal      = "000935";
                        $cod_reintegro     = "000581";
                        $cod_reintegro_ina = "001176";
                        $cod_basico_notrib = "001168";
                        $cod_asignacion    = "000056";
                        $cod_bonificacion  = "001126";
                        $cod_hextra        = "001169";
                        $cod_hdoble        = "001173";
                        $cod_ing4ta        = "";
                        /*Descuentos*/
                        $cod_tardanza      = "000945";
                        $cod_retencion     = "000131";
                        $cod_adelanto      = "000217";
                        $cod_onp           = "000079";
                        $cod_afp_fondo     = "000085";
                        $cod_afp_comision  = "000083";
                        $cod_afp_seguro    = "000084";
                        $cod_prestamo      = "000514";
                        $cod_comedor       = "001166";
                        $cod_dscto4ta      = "";
                        $cod_dsctoadic     = "001111";
                        /*Aportes*/
                        $cod_essalud       = "000075";
                        $cod_senati        = "000592";
                        $cod_sctr_salud    = "000593";
                        $cod_sctr_pension  = "000837";
                    }
                    elseif($planilla_id=='04'){
                        /*Ingresos*/
                        $cod_basico        = "001192";
                        $cod_dsemanal      = "";
                        $cod_reintegro     = "";
                        $cod_reintegro_ina = "";
                        $cod_basico_notrib = "";
                        $cod_asignacion    = "";
                        $cod_bonificacion  = "";
                        $cod_hextra        = "";
                        $cod_hdoble        = "";
                        $cod_ing4ta        = "";
                        /*Descuentos*/
                        $cod_tardanza      = "";
                        $cod_retencion     = "001196";
                        $cod_adelanto      = "001193";
                        $cod_onp           = "";
                        $cod_afp_fondo     = "";
                        $cod_afp_comision  = "";
                        $cod_afp_seguro    = "";
                        $cod_prestamo      = "001195";
                        $cod_comedor       = "";
                        $cod_dscto4ta      = "001229";
                        $cod_dsctoadic     = "";
                        /*Aportes*/
                        $cod_essalud       = "";
                        $cod_senati        = "";
                        $cod_sctr_salud    = "";
                        $cod_sctr_pension  = "";
                    }
                    foreach($conceptos as $it => $value){
                        /*Ingresos*/
                        if($it == $cod_basico)        $basico        = $value;
                        if($it == $cod_dsemanal)      $dsemanal      = $value;
                        if($it == $cod_reintegro)     $reintegro     = $value;
                        if($it == $cod_reintegro_ina) $reintegro_ina = $value;
                        if($it == $cod_basico_notrib) $basico_notrib = $value; 
                        if($it == $cod_asignacion)    $asignacion    = $value;
                        if($it == $cod_bonificacion)  $bonificacion  = $value;
                        if($it == $cod_hextra)        $hextra        = $value;
                        if($it == $cod_hdoble)        $hdoble        = $value;
                        if($it == $cod_ing4ta)        $ing4ta        = $value;
                        /*Descuentos*/
                        if($it == $cod_tardanza)      $tardanza     = $value;
                        if($it == $cod_retencion)     $retencion    = $value;
                        if($it == $cod_adelanto)      $adelanto     = $value;                    
                        if($it == $cod_onp)           $onp          = $value;  
                        if($it == $cod_afp_fondo)     $afp_fondo    = $value;  
                        if($it == $cod_afp_comision)  $afp_comision = $value;  
                        if($it == $cod_afp_seguro)    $afp_seguro   = $value;  
                        if($it == $cod_prestamo)      $prestamo     = $value;  
                        if($it == $cod_comedor)       $comedor      = $value;  
                        if($it == $cod_dscto4ta)      $dscto_4ta    = $value;  
                        if($it == $cod_dsctoadic)     $dscto_adic   = $value;  
                        /*Aportes*/
                        if($it == $cod_essalud)       $essalud      = $value;  
                        if($it == $cod_senati)        $senati       = $value;  
                        if($it == $cod_sctr_salud)    $sctr_salud   = $value;  
                        if($it == $cod_sctr_pension)  $sctr_pension = $value;  
                    }
                    $total_ingresos   = $basico + $dsemanal + $reintegro + $reintegro_ina + $basico_notrib + $asignacion + $bonificacion + $hextra + $hdoble + $ing4ta;
                    $total_descuentos = $tardanza + $onp + $afp_fondo + $afp_comision + $afp_seguro + $retencion + $adelanto + $prestamo + $comedor + $dscto_4ta + $dscto_adic;
                    $total_aportes    = $essalud + $senati + $sctr_salud + $sctr_pension;
                    $total_neto       = $basico + $dsemanal + $reintegro + $reintegro_ina + $asignacion + $bonificacion - $total_descuentos;
                    $total_fuera      = $basico_notrib + $hextra + $hdoble; 
                    $total_remun      = $basico + $dsemanal + $reintegro + $reintegro_ina + $asignacion + $bonificacion + $ing4ta;
                    $total_cargasoc   = ($planilla_id!='04')?$basico*0.4441:0;
                    /*Genero los consolidados*/
                    $consolidado[$ccosto_id][$planilla_id]['01'] = (isset($consolidado[$ccosto_id][$planilla_id]['01'])?$consolidado[$ccosto_id][$planilla_id]['01']:0) + $total_remun;
                    $consolidado[$ccosto_id][$planilla_id]['02']  = (isset($consolidado[$ccosto_id][$planilla_id]['02'])?$consolidado[$ccosto_id][$planilla_id]['02']:0) + $total_cargasoc;
                    $consolidado[$ccosto_id][$planilla_id]['03']    = (isset($consolidado[$ccosto_id][$planilla_id]['03'])?$consolidado[$ccosto_id][$planilla_id]['03']:0) + $total_fuera;
                    $consolidado_conta[$ccosto_conta_id][$planilla_id]['01'] = (isset($consolidado_conta[$ccosto_conta_id][$planilla_id]['01'])?$consolidado_conta[$ccosto_conta_id][$planilla_id]['01']:0) + $total_remun;
                    $consolidado_conta[$ccosto_conta_id][$planilla_id]['02'] = (isset($consolidado_conta[$ccosto_conta_id][$planilla_id]['02'])?$consolidado_conta[$ccosto_conta_id][$planilla_id]['02']:0) + $total_cargasoc;
                    $consolidado_conta[$ccosto_conta_id][$planilla_id]['03'] = (isset($consolidado_conta[$ccosto_conta_id][$planilla_id]['03'])?$consolidado_conta[$ccosto_conta_id][$planilla_id]['03']:0) + $total_fuera;
                    /**/
                    if($total_neto < 0) {
                        $color = "#FF0000";
                    }  
                    if($total_neto == 0 && $total_fuera == 0) {
                        continue;
                    }    
                    $filter    = new stdClass();
                    $filter->personal_id = $personal_id;
                    $filter->entidad     = "";
                    $personas  = $this->personal_model->get($filter);
                    $filter2   = new stdClass();
                    $filter2->ccosto  = $ccosto_id;
                    $filter2->entidad = "";
                    $costos    = $this->ccosto_model->get($filter2);
                    $fila2.="<tr>";
                    $fila2.="<td>".$jj."</td>";
                    $data[] = $jj;
                    $fila2.="<td align='left'>".($planilla_id=='02'?'OBREROS':($planilla_id=='01'?'EMPLEADOS':'4TA CATEG.'))."</td>";
                    $data[] = ($planilla_id=='02'?'OBREROS':($planilla_id=='01'?'EMPLEADOS':'4TA CATEG.'));
                    $fila2.="<td align='left'>".(isset($personas->Nombres)?utf8_encode(strtoupper($personas->Nombres)):'')."</td>";
                    $data[] = isset($personas->Nombres) ? utf8_encode($personas->Nombres) : '';
                    $fila2.="<td align='left'>".(isset($costos->Descripcion) ? utf8_encode($costos->Descripcion) : '')."</td>";
                    $data[] = isset($costos->Descripcion) ? utf8_encode($costos->Descripcion) : '';                    
                    $fila2.="<td align='right'>".number_format($basico,2)."</td>";  
                    $data[] = $basico;
                    $fila2.="<td align='right'>".number_format($dsemanal,2)."</td>"; 
                    $data[] = number_format($dsemanal,2);
                    $fila2.="<td align='right'>".number_format($reintegro,2)."</td>"; 
                    $data[] = number_format($reintegro,2);
                    $fila2.="<td align='right'>".number_format($reintegro_ina,2)."</td>"; 
                    $data[] = number_format($reintegro_ina,2);                    
                    $fila2.="<td align='right'>".number_format($basico_notrib,2)."</td>"; 
                    $data[] = number_format($basico_notrib,2);
                    $fila2.="<td align='right'>".number_format($asignacion,2)."</td>";  
                    $data[] = number_format($asignacion,2);
                    $fila2.="<td align='right'>".number_format($bonificacion,2)."</td>";
                    $data[] = number_format($bonificacion,2);
                    $fila2.="<td align='right'>".number_format($hextra,2)."</td>";
                    $data[] = number_format($hextra,2);
                    $fila2.="<td align='right'>".number_format($hdoble,2)."</td>"; 
                    $data[] = number_format($hdoble,2);
                    $fila2.="<td align='right'>".number_format($ing4ta,2)."</td>"; 
                    $data[] = number_format($ing4ta,2);                    
                    $fila2.="<td align='right' style='background-color: #B0FAB2'>".number_format($total_ingresos,2)."</td>"; 
                    $data[] = $total_ingresos;
                    $fila2.="<td align='right'>".number_format($tardanza,2)."</td>"; 
                    $data[] = number_format($tardanza,2);
                    $fila2.="<td align='right'>".number_format($onp,2)."</td>";  
                    $data[] = number_format($onp,2);
                    $fila2.="<td align='right'>".number_format($afp_fondo,2)."</td>";  
                    $data[] = number_format($afp_fondo,2);
                    $fila2.="<td align='right'>".number_format($afp_comision,2)."</td>";  
                    $data[] = number_format($afp_comision,2);
                    $fila2.="<td align='right'>".number_format($afp_seguro,2)."</td>";  
                    $data[] = number_format($afp_seguro,2);
                    $fila2.="<td align='right'>".number_format($retencion,2)."</td>"; 
                    $data[] = number_format($retencion,2);
                    $fila2.="<td align='right'>".number_format($adelanto,2)."</td>"; 
                    $data[] = number_format($adelanto,2);
                    $fila2.="<td align='right'>".number_format($prestamo,2)."</td>";  
                    $data[] = number_format($prestamo,2);
                    $fila2.="<td align='right'>".number_format($comedor,2)."</td>";  
                    $data[] = number_format($comedor,2);
                    $fila2.="<td align='right'>".number_format($dscto_4ta,2)."</td>";  
                    $data[] = number_format($dscto_4ta,2);
                    $fila2.="<td align='right'>".number_format($dscto_adic,2)."</td>";  
                    $data[] = number_format($dscto_adic,2);
                    $fila2.="<td align='right' style='background-color: #B0FAB2'>".number_format($total_descuentos,2)."</td>"; 
                    $data[] = number_format($total_descuentos,2);
                    $fila2.="<td align='right'>".number_format($essalud,2)."</td>";  
                    $data[] = number_format($essalud,2);
                    $fila2.="<td align='right'>".number_format($senati,2)."</td>";
                    $data[] = number_format($senati,2);
                    $fila2.="<td align='right'>".number_format($sctr_salud,2)."</td>";  
                    $data[] = number_format($sctr_salud,2);
                    $fila2.="<td align='right'>".number_format($sctr_pension,2)."</td>";   
                    $data[] = number_format($sctr_pension,2);
                    $fila2.="<td align='right' style='background-color: #B0FAB2'>".number_format($total_aportes,2)."</td>";  
                    $data[] = number_format($total_aportes,2);
                    $fila2.= "<td align='right' style='background-color: #CEF6F5; font-weight: bold; color: " . $color . "'>" . number_format ($total_remun, 2) . "</td>";
                    $data[] = number_format($total_remun,2);
                    $fila2.= "<td align='right' style='background-color: #CEF6F5; font-weight: bold; color: " . $color . "'>" . number_format ($total_fuera, 2) . "</td>";
                    $data[] = number_format($total_fuera,2);                    
                    $fila2.= "<td align='right' style='background-color: #CEF6F5; font-weight: bold; color: " . $color . "'>" . number_format ($total_neto, 2) . "</td>";
                    $data[] = number_format($total_neto,2);
                    $fila2.= "<td align='right' style='background-color: #CEF6F5; font-weight: bold;'>" . number_format ($total_fuera + $total_neto, 2) . "</td>";                       
                    $data[] = number_format($total_fuera + $total_neto,2);
                    $fila2.="</tr>";
                    array_push($arrexcel,$data);
                    $t_basico           = $t_basico + $basico;
                    $t_dsemanal         = $t_dsemanal + $dsemanal;
                    $t_reintegro        = $t_reintegro + $reintegro;
                    $t_reintegro_ina    = $t_reintegro_ina + $reintegro_ina;
                    $t_no_tributario    = $t_no_tributario + $basico_notrib;   
                    $t_asignacion       = $t_asignacion + $asignacion;
                    $t_bonificacion     = $t_bonificacion + $bonificacion;
                    $t_hextra           = $t_hextra + $hextra;
                    $t_hdoble           = $t_hdoble + $hdoble;
                    $t_ing4ta           = $t_ing4ta + $ing4ta;
                    $t_total_ingresos   = $t_total_ingresos + $total_ingresos;
                    $t_tardanza         = $t_tardanza + $tardanza;
                    $t_onp              = $t_onp + $onp;
                    $t_afp_fondo        = $t_afp_fondo + $afp_fondo;
                    $t_afp_comision     = $t_afp_comision + $afp_comision;
                    $t_afp_seguro       = $t_afp_seguro + $afp_seguro;
                    $t_retencion        = $t_retencion + $retencion;
                    $t_adelanto         = $t_adelanto + $adelanto;
                    $t_prestamo         = $t_prestamo + $prestamo;
                    $t_comedor          = $t_comedor + $comedor;
                    $t_dscto_4ta        = $t_dscto_4ta + $dscto_4ta;
                    $t_dscto_adic       = $t_dscto_adic + $dscto_adic;
                    $t_total_descuentos = $t_total_descuentos + $total_descuentos;
                    $t_essalud          = $t_essalud + $essalud;
                    $t_senati           = $t_senati + $senati;
                    $t_sctr_salud       = $t_sctr_salud + $sctr_salud;
                    $t_sctr_pension     = $t_sctr_pension + $sctr_pension;
                    $t_total_aportes    = $t_total_aportes + $total_aportes;
                    $t_rem              = $t_rem + $total_remun;
                    $t_neto             = $t_neto + $total_neto;
                    $t_fuera            = $t_fuera + $total_fuera;
                    $t_total            = $t_total + $total_neto + $total_fuera;             
                    $jj++;
                }
                $fila2 .= "<tr>";
                $fila2 .= "<td align='left' colspan='4'></td>";
                $fila2 .= "<td align='right'>" . number_format($t_basico, 2) . "</td>";
                $data_pie[] = '';
                $data_pie[] = '';
                $data_pie[] = '';
                $data_pie[] = '';
                $data_pie[] = $t_basico;
                $fila2 .= "<td align='right'>" . number_format($t_dsemanal, 2) . "</td>";
                $data_pie[] = number_format($t_dsemanal,2);
                $fila2 .= "<td align='right'>" . number_format($t_reintegro, 2) . "</td>";
                $data_pie[] = number_format($t_reintegro,2);
                $fila2 .= "<td align='right'>" . number_format($t_reintegro_ina, 2) . "</td>";
                $data_pie[] = number_format($t_reintegro_ina,2);
                $fila2 .= "<td align='right'>" . number_format($t_no_tributario, 2) . "</td>";
                $data_pie[] = $t_no_tributario;
                $fila2 .= "<td align='right'>" . number_format($t_asignacion, 2) . "</td>";
                $data_pie[] = number_format($t_asignacion,2);
                $fila2 .= "<td align='right'>" . number_format($t_bonificacion, 2) . "</td>";
                $data_pie[] = number_format($t_bonificacion,2);
                $fila2 .= "<td align='right'>" . number_format($t_hextra, 2) . "</td>";
                $data_pie[] = number_format($t_hextra,2);
                $fila2 .= "<td align='right'>" . number_format($t_hdoble, 2) . "</td>";
                $data_pie[] = number_format($t_hdoble,2);
                $fila2 .= "<td align='right'>" . number_format($t_ing4ta, 2) . "</td>";
                $data_pie[] = number_format($t_ing4ta,2);                
                $fila2 .= "<td align='right'>" . number_format($t_total_ingresos, 2) . "</td>";
                $data_pie[] = $t_total_ingresos;
                $fila2 .= "<td align='right'>" . number_format($t_tardanza, 2) . "</td>";
                $data_pie[] = number_format($t_tardanza,2);
                $fila2 .= "<td align='right'>" . number_format($t_onp, 2) . "</td>";
                $data_pie[] = number_format($t_onp,2);
                $fila2 .= "<td align='right'>" . number_format($t_afp_fondo, 2) . "</td>";
                $data_pie[] = number_format($t_afp_fondo,2);
                $fila2 .= "<td align='right'>" . number_format($t_afp_comision, 2) . "</td>";
                $data_pie[] = number_format($t_afp_comision,2);
                $fila2 .= "<td align='right'>" . number_format($t_afp_seguro, 2) . "</td>";
                $data_pie[] = number_format($t_afp_seguro,2);
                $fila2 .= "<td align='right'>" . number_format($t_retencion, 2) . "</td>";
                $data_pie[] = number_format($t_retencion,2);
                $fila2 .= "<td align='right'>" . number_format($t_adelanto, 2) . "</td>";
                $data_pie[] = number_format($t_adelanto,2);
                $fila2 .= "<td align='right'>" . number_format($t_prestamo, 2) . "</td>";
                $data_pie[] = number_format($t_prestamo,2);
                $fila2 .= "<td align='right'>" . number_format($t_comedor, 2) . "</td>";
                $data_pie[] = number_format($t_comedor,2);
                $fila2 .= "<td align='right'>" . number_format($t_dscto_4ta, 2) . "</td>";
                $data_pie[] = number_format($t_dscto_4ta,2);
                $fila2 .= "<td align='right'>" . number_format($t_dscto_adic, 2) . "</td>";
                $data_pie[] = number_format($t_dscto_adic,2);                
                $fila2 .= "<td align='right'>" . number_format($t_total_descuentos, 2) . "</td>";
                $data_pie[] = $t_total_descuentos;
                $fila2 .= "<td align='right'>" . number_format($t_essalud, 2) . "</td>";
                $data_pie[] = number_format($t_essalud,2);
                $fila2 .= "<td align='right'>" . number_format($t_senati, 2) . "</td>";
                $data_pie[] = number_format($t_senati,2);
                $fila2 .= "<td align='right'>" . number_format($t_sctr_salud, 2) . "</td>";
                $data_pie[] = number_format($t_sctr_salud,2);
                $fila2 .= "<td align='right'>" . number_format($t_sctr_pension, 2) . "</td>";
                $data_pie[] = number_format($t_sctr_pension,2);
                $fila2 .= "<td align='right'>" . number_format($t_total_aportes, 2) . "</td>";
                $data_pie[] = $t_total_aportes;
                $fila2 .= "<td align='right' style='font-weight: bold;'>" . number_format($t_rem, 2) . "</td>";
                $data_pie[] = $t_rem;
                $fila2 .= "<td align='right' style='font-weight: bold;'>" . number_format($t_fuera, 2) . "</td>";
                $data_pie[] = $t_fuera;                
                $fila2 .= "<td align='right' style='font-weight: bold;'>" . number_format($t_neto, 2) . "</td>";
                $data_pie[] = $t_neto;
                $fila2 .= "<td align='right' style='font-weight: bold;'>" . number_format($t_total, 2) . "</td>";   
                $data_pie[] = $t_total;
                $fila2 .= "</tr>";
                array_push($arrexcel,$data_pie);
                $this->session->set_userdata('data_detalle_gastos', $arrexcel);
                /*Consolidado por areas*/
                if(count($consolidado)>0){
                    $c_total = 0;
                    foreach($consolidado as $c_ccosto => $c_value){
                        foreach($c_value as $c_tipo => $c_value2){
                            foreach($c_value2 as $c_concepto => $c_monto){
                                $data = array();
                                $filter4   = new stdClass();
                                $filter4->ccosto  = $c_ccosto;
                                $filter4->entidad = "";
                                $centrocostos  = $this->ccosto_model->get($filter4); 
                                if($c_tipo == '02'){
                                    if($c_concepto == '01')  $c_descripcion = "PLANILLA OBRERO";
                                    if($c_concepto == '02')  $c_descripcion = "CARGA SOCIAL OBRERO";
                                    if($c_concepto == '03')  $c_descripcion = "POR FUERA OBRERO";
                                }
                                if($c_tipo == '01'){
                                    if($c_concepto == '01')  $c_descripcion = "PLANILLA EMPLEADO";
                                    if($c_concepto == '02')  $c_descripcion = "CARGA SOCIAL EMPLEADO";
                                    if($c_concepto == '03')  $c_descripcion = "POR FUERA EMPLEADO";
                                }
                                if($c_tipo == '04'){
                                    if($c_concepto == '01')  $c_descripcion = "PLANILLA 4TA";
                                    if($c_concepto == '02')  $c_descripcion = "CARGA SOCIAL 4TA";
                                    if($c_concepto == '03')  $c_descripcion = "POR FUERA 4TA";
                                } 
                                if($c_monto != 0){
                                    $codigo_aux2 = isset($centrocostos->Codigo_Auxiliar2)?$centrocostos->Codigo_Auxiliar2:'';
                                    $fila .= "<tr>";
                                    $fila .= "<td>".(isset($centrocostos->Codigo_Auxiliar2)?$codigo_aux2.' '.$arrccosto_conta[$codigo_aux2]:'')."</td>";
                                    $data[] = isset($centrocostos->Codigo_Auxiliar2) ? utf8_encode($codigo_aux2 . ' '. $arrccosto_conta[$codigo_aux2]) : "-";
                                    $fila .= "<td>".(isset($centrocostos->Descripcion)?utf8_encode($centrocostos->Descripcion):'')."</td>";
                                    $data[] = isset($centrocostos->Descripcion) ? utf8_encode($centrocostos->Descripcion) : "-";
                                    $fila .= "<td>".$c_descripcion."</td>";
                                    $data[] = utf8_encode(utf8_encode($c_descripcion));
                                    $fila .= "<td align='right'>".number_format($c_monto,2)."</td>";
                                    $data[] = $c_monto;
                                    $fila .= "</tr>";  
                                    $c_total = $c_total + $c_monto;  
                               
                                    array_push($conexcel,$data);
                                }
                            }                         
                        }
                    }
                    $data_pie = array();
                    $fila .= "<tr>";
                    $fila .= "<td colspan='3' align='right'>Total</td>";
                    $fila .= "<td align='right'>".number_format($c_total,2)."</td>";
                    $data_pie[] = '';  $data_pie[] = 'Total'; $data_pie[] = $c_total;
                    array_push($conexcel, $data_pie);
                    $this->session->set_userdata('data_consolidado_gastos',$conexcel);
                    $fila .= "</tr>";                                                       
                }
                /*Consolidado por centro de costo*/
                if(count($consolidado_conta)>0){
                    $c_total2 = 0;
                    foreach($consolidado_conta as $c_ccosto_conta => $c_value){
                        foreach($c_value as $c_tipo => $c_value2){
                            foreach($c_value2 as $c_concepto2 => $c_monto2){
                                if($c_tipo == '02'){
                                    if($c_concepto2 == '01')  $c_descripcion = "PLANILLA OBRERO";
                                    if($c_concepto2 == '02')  $c_descripcion = "CARGA SOCIAL OBRERO";
                                    if($c_concepto2 == '03')  $c_descripcion = "POR FUERA OBRERO";
                                }
                                if($c_tipo == '01'){
                                    if($c_concepto2 == '01')  $c_descripcion = "PLANILLA EMPLEADO";
                                    if($c_concepto2 == '02')  $c_descripcion = "CARGA SOCIAL EMPLEADO";
                                    if($c_concepto2 == '03')  $c_descripcion = "POR FUERA EMPLEADO";
                                }
                                if($c_tipo == '04'){
                                    if($c_concepto2 == '01')  $c_descripcion = "PLANILLA 4TA";
                                    if($c_concepto2 == '02')  $c_descripcion = "CARGA SOCIAL 4TA";
                                    if($c_concepto2 == '03')  $c_descripcion = "POR FUERA 4TA";
                                } 
                                if($c_monto2 != 0){
                                    $fila3 .= "<tr>";
                                    $fila3 .= "<td>".$c_ccosto_conta.' '.$arrccosto_conta[$c_ccosto_conta]."</td>";
                                    $fila3 .= "<td>".$c_descripcion."</td>";
                                    $fila3 .= "<td align='right'>".number_format($c_monto2,2)."</td>";
                                    $fila3 .= "</tr>";  
                                    $c_total2 = $c_total2 + $c_monto2;  
                                }
                            }                         
                        }
                    }
                    $fila3 .= "<tr>";
                    $fila3 .= "<td colspan='2' align='right'>Total</td>";
                    $fila3 .= "<td align='right'>".number_format($c_total2,2)."</td>";
                    $fila3 .= "</tr>";  
                }
            }
        }
        $data['selanio']   = $selanio;
        $data['selccosto'] = $selccosto;
        $data['selccosto_conta'] = $selccosto_conta;
        $data['selproceso']= "";
        $data['seltrabajador']= $seltrabajador;
        $data['fila']      = $fila;
        $data['fila2']     = $fila2;
        $data['fila3']     = $fila3;
        $data['export']    = @count($detalle);
        $this->load->view(scire . "scire_gastos_area", $data);
    }
    
    public function gastos_concepto(){
        $anio         = $this->input->get_post('anio');
        $mes          = $this->input->get_post('mes'); 
        $ccosto       = $this->input->get_post('ccosto');
        $ccosto_conta = $this->input->get_post('ccosto_conta');  
        $trabajador   = $this->input->get_post('trabajador');
        $concepto     = $this->input->get_post('concepto');
        if($anio=="")    $anio    = "2013";
        $filter  = new stdClass();
        $filter->anio = $anio;
        $filter->mesi = "000043";
        $filter2 = new stdClass();
        $filter2->tipo = array("00","20","21");          
        $filter3 = new stdClass();
        if($ccosto_conta!="")  $filter3->ccosto_conta = $ccosto_conta;
        $filter3->estado = '01';
        $filter4 = new stdClass();
        $filter4->concepto = array('000052','000935','001176','001168','000056','001126','001169','001173','000945','000079','000085','000083','000084','000131','000217','000514','001166','000075','000592','000593','000837','001192','001195','001193','001196');
        $filter4->order_by = array("Detalle"=>"asc");
        $entidad = $this->session->userdata('entidad');
        if($entidad=='01')
            $arrccosto_conta = array(""=>":::Todos:::","CD-103"=>"INFRAESTRUCTURA","CD-110"=>"OPERACIONES","CD-111"=>"INGENIERIA","CD-112"=>"MANTENIMIENTO","CD-200"=>"ADMINISTRACION","CD-202"=>"ALMACEN","CD-222"=>"GERENCIA GENERAL","CD-300"=>"COMERCIAL","CD-105"=>"CALIDAD","CD-102"=>"PROYECTOS","CD-700"=>"YURIMAGUAS");
        elseif($entidad=='02')
            $arrccosto_conta = array(""=>"::Seleccione","CD-120"=>"GALVANIZADO","CD-301"=>"COMERCIAL","CD-105"=>"CALIDAD");            
        asort($arrccosto_conta);        
        $selanio         = form_dropdown('anio',$this->ejercicio_model->select(new stdClass(),"::Seleccione:::",""),$anio," size='1' id='anio' class='comboPeque' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#frmPlanilla').submit();\" ");               
        $selmes          = form_dropdown('mes',$this->mes_model->select($filter,":::Seleccione:::",""),$mes," size='1' id='mes' class='comboPeque' style='width:200px' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');\" ");
        $seltrabajador   = form_dropdown('trabajador',$this->tipo_trabajador_model->select($filter2,":::Todos:::",""),$trabajador," size='1' id='trabajador' class='comboPeque' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');\" ");        
        $selccosto       = form_dropdown('ccosto',$this->ccosto_model->select($filter3,":::Todos:::",""),$ccosto," size='1' id='ccosto' class='comboMedio' style='width:200px'");               
        $selccosto_conta = form_dropdown('ccosto_conta',$arrccosto_conta,$ccosto_conta," size='1' id='ccosto_conta' class='comboMedio' style='width:200px' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');submit();\" ");                       
        $selconcepto     = form_dropdown('concepto',$this->conceptos_model->select($filter4,":::Todos:::",""),$concepto," size='1' id='concepto' class='comboMedio' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');\" ");                       
        $fila    = "";
        $fila2   = "";
        if($mes != "" && $anio != ""){
            $arrdetalle       = array();
            $filter           = new stdClass();
            $filter->mes      = $mes;
            $filter->proceso  = "01"; 
            //$filter->planilla = "04";            
            if($ccosto!="")       $filter->ccosto       = $ccosto; 
            if($trabajador!="")   $filter->tipo         = $trabajador;
            if($ccosto_conta!="") $filter->ccosto_conta = $ccosto_conta;
            if($concepto!="")     $filter->concepto     = $concepto;
            $detalle = $this->planillas_model->getConcepto($filter);
            $filter->proceso       = "02"; 
            $filter->concepto      = "000217";
            $detallex = $this->planillas_model->getConcepto($filter);
            foreach($detallex as $ii => $val){
                $objeto = $val;
                $objeto->Boleta_Columna = "02";
                $arrdetalle[] = $objeto;
            }
            $detalle  = array_merge($detalle,$arrdetalle);           
            if(count($detalle)>0){
               /*Ordena el array por la 'columna' tipo de concepto*/
                foreach($detalle as $item => $value){
                    $filtro   = new stdClass();
                    $filtro->concepto = $value->Concepto_Id;
                    $conceptos   = $this->conceptos_model->get($filtro); 
                    $bol_columna = $conceptos->Boleta_Columna;
                    if($value->Concepto_Id == "001169" || $value->Concepto_Id == "001173")  $bol_columna = "01";
                    $value->Tipo_Concepto = $bol_columna;
                    $detalle2[] = $value;
                }
                foreach($detalle2 as $indice => $value){
                    $tipo_concepto[] = $value->Tipo_Concepto;
                }
                array_multisort($tipo_concepto, SORT_ASC, $detalle2);
                
                // DETALLE
                $t_monto2 = 0;
                $t_monto  = array();
                $arrexcel = array();
                foreach($detalle2 as $item => $value){
                    $data = array();
                    $concepto_id = $value->Concepto_Id;
                    $ccosto_id   = $value->Ccosto_id;
                    $tipo_id     = $value->Tipo_Trabajador_Id;
                    $codigo_aux  = $value->Codigo_Auxiliar2;
                    $monto       = $value->monto;
                    $filter2     = new stdClass();
                    $filter2->ccosto = trim($ccosto_id);
                    $costos      = $this->ccosto_model->get($filter2); 
                    $filter3     = new stdClass();
                    $filter3->concepto = $concepto_id;
                    $conceptos   = $this->conceptos_model->get($filter3); 
                    $nombres     = $value->Apellido_Paterno." ".$value->Apellido_Materno." ".$value->Nombres;
                    if($concepto_id == "000052" || $concepto_id == "000935" || $concepto_id == "001176" || $concepto_id == "001168" || $concepto_id == "000056" || $concepto_id == "001126" || $concepto_id == "001169" || $concepto_id == "001173" || $concepto_id == "000945" || $concepto_id == "000079" ||$concepto_id == "000085" || $concepto_id == "000083" || $concepto_id == "000084" || $concepto_id == "000131" ||  $concepto_id == "000217" || $concepto_id == "000514" || $concepto_id == "001166" || $concepto_id == "000075" || $concepto_id == "000592" || $concepto_id == "000593" || $concepto_id == "000837" || $concepto_id == "001192" || $concepto_id == "001195" || $concepto_id == "001193" || $concepto_id == "001196"){
                        $filter4 = new stdClass();
                        $filter4->tipo = $tipo_id;
                        $ttrabajador = $this->tipo_trabajador_model->get($filter4);
                        
                        $var_data_consolidado_1 = utf8_encode($conceptos->Detalle);
                        $var_data_consolidado_2 = utf8_encode($codigo_aux)." ".  utf8_encode($arrccosto_conta[$codigo_aux]);
                        $var_data_consolidado_3 = (isset($costos->Descripcion)?  utf8_encode($costos->Descripcion):'');
                        $var_data_consolidado_4 = utf8_encode($nombres);
                        $var_data_consolidado_5 = ($ttrabajador->Descripcion=='--'?'4TA CATEG.':$ttrabajador->Descripcion);
                        $var_data_consolidado_6 = number_format($monto,2);
                        
                        $fila2 .= "<tr>";
                        $fila2 .= "<td align='left'>".$var_data_consolidado_1."</td>";
                        $data[] = $var_data_consolidado_1;
                        $fila2 .= "<td align='left'>".$var_data_consolidado_2."</td>";
                        $data[] = $var_data_consolidado_2;
                        $fila2 .= "<td align='left'>".$var_data_consolidado_3."</td>";
                        $data[] = $var_data_consolidado_3;
                        $fila2 .= "<td align='left'>".$var_data_consolidado_4."</td>";
                        $data[] = $var_data_consolidado_4;
                        $fila2 .= "<td align='left'>".$var_data_consolidado_5."</td>";
                        $data[] = $var_data_consolidado_5;
                        $fila2 .= "<td align='right'>".$var_data_consolidado_6."</td>";
                        $data[] = $var_data_consolidado_6;
                        $fila2 .= "</tr>";
                        
                        array_push($arrexcel,$data);
                        
                        $t_monto2 = $t_monto2 + $monto;
                        $t_monto[$concepto_id][$codigo_aux][$ccosto_id][$tipo_id] = (isset($t_monto[$concepto_id][$codigo_aux][$ccosto_id][$tipo_id])?$t_monto[$concepto_id][$codigo_aux][$ccosto_id][$tipo_id]:0) + $monto;
                    }
                    $this->session->set_userdata('data_pago_concepto_det', $arrexcel);
                }
                $fila2 .= "<tr>";
                $fila2 .= "<td colspan='5'></td>";
                $fila2 .= "<td align='right'>".number_format($t_monto2,2)."</td>";
                $fila2 .= "</tr>";
                
                
                // CONSOLIDADO
                $c_monto = 0;
                $k       = 0;
                $arrexcel = array();
                if(count($t_monto)>0){
                    foreach($t_monto as $concepto_id => $value){
                        foreach($value as $codigo_aux => $value2){
                            foreach($value2 as $ccosto_id => $value3){
                                foreach($value3 as $tipo_id => $monto){
                                    $data = array();
                                    $filter2   = new stdClass();
                                    $filter2->ccosto = $ccosto_id;
                                    $costos    = $this->ccosto_model->get($filter2); 
                                    $filter3   = new stdClass();
                                    $filter3->concepto = $concepto_id;
                                    $conceptos = $this->conceptos_model->get($filter3); 
                                    $filter4 = new stdClass();
                                    $filter4->tipo = $tipo_id;
                                    $ttrabajador = $this->tipo_trabajador_model->get($filter4);
                                    
                                    $var_data_1 = utf8_encode($conceptos->Detalle);
                                    $var_data_2 = utf8_encode($codigo_aux)." ".  utf8_encode($arrccosto_conta[$codigo_aux]);
                                    $var_data_3 = (isset($costos->Descripcion) ? utf8_encode($costos->Descripcion) : '');
                                    $var_data_4 = ($ttrabajador->Descripcion=='--'?'4TA CATEG.':$ttrabajador->Descripcion);
                                    $var_data_5 = number_format($monto,2);
                                    
                                    $color     = $k%2==0?'#ffffff':'#A4A4A4';
                                    $fila .= "<tr bgcolor='".$color."'>";
                                    $fila .= "<td align='left'>".$var_data_1."</td>";
                                    $data[] = $var_data_1;
                                    $fila .= "<td align='left'>".$var_data_2."</td>";
                                    $data[] = $var_data_2;
                                    $fila .= "<td align='left'>".$var_data_3."</td>";
                                    $data[] = $var_data_3;
                                    $fila .= "<td align='left'>".$var_data_4."</td>";
                                    $data[] = $var_data_4;
                                    $fila .= "<td align='right'>".$var_data_5."</td>";
                                    $data[] = $var_data_5;
                                    $fila .= "</tr>";
                                    $c_monto = $c_monto + $monto; 
                                    
                                    array_push($arrexcel,$data);
                                }
                            }
                        }
                        $k++;
                    }
                    $this->session->set_userdata('data_pago_concepto_con', $arrexcel);
                }
                $fila .= "<tr>";
                $fila .= "<td colspan='4'></td>";
                $fila .= "<td align='right'>".number_format($c_monto,2)."</td>";
                $fila .= "</tr>";                   
            }         
        }
        $data['selanio']   = $selanio;
        $data['selmes']    = $selmes; 
        $data['selccosto'] = $selccosto;
        $data['selccosto_conta'] = $selccosto_conta;   
        $data['seltrabajador']   = $seltrabajador;
        $data['selconcepto']     = $selconcepto;
        $data['fila']        = $fila;
        $data['fila2']       = $fila2;
        $data['export']      = @count($detalle);
        $this->load->view(scire . "scire_gastos_concepto", $data);
    }
    
    public function convertFechas($fechaini, $fechafin){
        $fini = explode("/", $fechaini);
        $ffin = explode("/", $fechafin);
        
        $nfini = $fini[0] . "-" . $fini[1] . "-" . $fini[2];
        $nffin = $ffin[0] . "-" . $ffin[1] . "-" . $ffin[2];
        
        $arrfecha = array();
        
        $fechaaamostar = $nfini;
        while (strtotime($nffin) >= strtotime($nfini)) {
            if (strtotime($nffin) != strtotime($fechaaamostar)) {
                $arrfecha[] = $fechaaamostar;
                $fechaaamostar = date("d-m-Y", strtotime($fechaaamostar . " + 1 day"));
            } else {
                $arrfecha[] = $fechaaamostar;
                break;
            }
        }
        
        return $arrfecha;
    }
    
    public function horaslaboradas() {
        $cfechas       = array();
        $cfechas2      = array();
        $param         = $this->input->get_post('param');
        $fecha         = $this->input->get_post('fecha');
        $fechafin      = $this->input->get_post('fechafin');
        $trabajador    = $this->input->get_post('trabajador');
        $ccosto        = $this->input->get_post('ccosto');
        $planilla      = $this->input->get_post('planilla');
        $filter        = new stdClass();
        $filternot     = new stdClass();
        $filterccosto  = new stdClass();
        $filter->tipo  = array("20","21");
        $seltrabajador = form_dropdown('trabajador',$this->tipo_trabajador_model->select($filter,"::Seleccione:::",""),$trabajador," size='1' id='trabajador' class='comboPeque' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');\" ");
        $selccosto     = form_dropdown('ccosto',$this->ccosto_model->select($filterccosto,"::Seleccione:::",""),$ccosto," size='1' id='ccosto' class='comboMedio'");
        $fila = "";
        if(isset($param) && $param == '1') {
            $personas = array();
            $horas_persona = array();
            $filter->tipo_trabajador = $trabajador;
            $filter->ccosto = $ccosto;
            $filter->fechaini = $fecha;
            $filter->fechafin = $fechafin;
            $horaslaboradas = $this->asistencia_registro_model->getHorasLaboradas($filter,$filternot);
            $personalHoras = $this->asistencia_registro_model->getPersonalHoras($filter,$filternot);
            foreach($personalHoras as $key => $value) {
                $personas[$value->Personal_Id] = array(
                    'Personal_Id' => $value->Personal_Id,
                    'Nombres' => $value->Nombres,
                    'Ccosto' => $value->Descripcion,
                    'TipoTrabId' => $value->Tipo_Trabajador_Id,
                    'Dni' => $value->Nro_Doc
                );
            }
            foreach($horaslaboradas as $key => $value) {
                $f = date('d-m-Y',strtotime($value->Fecha));
                $horas_persona[$value->Personal_Id][$f] = ($value->HorasLab)+($value->Horas_Extra);
            }
            $array_fechas = array();
            $fini = strtotime($filter->fechaini);           
            $ffin = strtotime($filter->fechafin);
            for($i = $fini ; $i <= $ffin ; $i += 86400) {
                $array_fechas[] = date('d-m-Y', $i);
            }
            $cfechas = $this->convertFechas($filter->fechaini, $filter->fechafin);
            $g_total_horas        = 0;
            $g_total_horas_prod   = 0;
            $dif_valorizada_total = 0;
            foreach($personas as $key => $value) {
                $fila .= "<tr>";
                if($value['TipoTrabId'] == '20') {
                    $fila .= "<td>Obrero</td>";
                }
                elseif($value['TipoTrabId'] == '21') {
                    $fila .= "<td>Empleado</td>";
                }
                else {
                    $fila .= "<td>-</td>";
                }
                $fila .= "<td align='left'>" . utf8_encode(strtoupper($value['Nombres'])) . "</td>";
                $fila .= "<td align='center'>" . $value['Dni'] . "</td>";
                $fila .= "<td align='left'>" . utf8_encode($value['Ccosto']) . "</td>";
                $total_horas = 0;
                
                /*Obtengo horas de produccion*/
                $filtro         = new stdClass();
                $filtronot      = new stdClass();
                $filtro->dni    = $value['Dni'];
                $filtro->fechai = $filter->fechaini;
                $filtro->fechaf = $filter->fechafin;
                $filtro->group_by       = array("a.dni");
                $tareos         = $this->tareo_model->listar_totales($filtro,$filtronot);    
                $total_horas_prod = isset($tareos->horas)?$tareos->horas:0;
                $total_horas_val  = isset($tareos->simple)?$tareos->simple:0;
                $pago_x_hora      = ($total_horas_prod!=0)?($total_horas_val/$total_horas_prod):0;
                foreach ($cfechas as $k => $v) {
                    $cfechas2[$k] = str_replace('-','/',substr($v,0,5));
                    if (isset($horas_persona[$key][$v])) {
                        $fila .= "<td>" . $horas_persona[$key][$v] . "</td>";
                        $total_horas = $total_horas + $horas_persona[$key][$v];
                    } else {
                        $fila .= "<td>0</td>";
                        $total_horas = $total_horas + 0;
                    }
                }
                $dif_valorizada = ($total_horas-$total_horas_prod)*$pago_x_hora;
                $fila .= "<td>".number_format($total_horas,2)."</td>";
                if($ccosto=='000000000000028'){
                    $fila .= "<td>".number_format($total_horas_prod,2)."</td>";
                    $fila .= "<td>".number_format($dif_valorizada,2)."</td>";                    
                }
                $fila .= "</tr>";
                $g_total_horas        = $g_total_horas + $total_horas;
                $g_total_horas_prod   = $g_total_horas_prod + $total_horas_prod;
                $dif_valorizada_total = $dif_valorizada_total + $dif_valorizada;
            }
            $fila .= "<tr>";
            $fila .= "<td colspan='".(count($cfechas)+4)."'></td>";
            $fila .= "<td>".number_format($g_total_horas,2)."</td>";
            if($ccosto=='000000000000028'){
                $fila .= "<td>".number_format($g_total_horas_prod,2)."</td>";
                $fila .= "<td>".number_format($dif_valorizada_total,2)."</td>";                
            }
            $fila .= "</tr>";
            $_SESSION['personas_data']     = $personas;
            $_SESSION['cfecha_data']       = $cfechas;
            $_SESSION['horaspersona_data'] = $horas_persona;
        }
        $data['ccosto']        = $ccosto;
        $data['seltrabajador'] = $seltrabajador;
        $data['selccosto']     = $selccosto;
        $data['fecha']         = $fecha;
        $data['fechafin']      = $fechafin;
        $data['fila']          = $fila;
        $data['cont']          = $cfechas2;
        $data['cantreg']       = count($cfechas) ;
        $data['hora_actual']   = "";
        $this->load->view(scire . "scire_horaslaboradas",$data);
    }
    
    public function horastrabajadas(){
        $anio       = $this->input->get_post('anio');
        $periodo    = $this->input->get_post('periodo');
        $ccosto_conta = $this->input->get_post('ccosto_conta');
        $proceso    = $this->input->get_post('proceso');
        $ccosto     = $this->input->get_post('ccosto');
        $fInicio    = $this->input->get_post('fecha_ini');
        $fFin       = $this->input->get_post('fecha_fin');        
        $entidad    = $this->session->userdata('entidad');
        $tipoexport = $this->session->userdata('tipoexport');
        if($periodo=="")    $periodo  = "000";
        if($proceso=="")    $proceso  = "01";
        if($anio=="")       $anio     = date('Y',time());
        if($tipoexport=="") $tipoexport = "html";
        if($fInicio=="")    $fInicio = date("01/m/Y",time());
        if($fFin=="")       $fFin    = date("d/m/Y",time());
        $filter  = new stdClass();
        $filter->anioi   = "2013";
        $selanio     = form_dropdown('anio',$this->ejercicio_model->select($filter,":::Seleccione:::",""),$anio," size='1' id='anio' class='comboPeque' onchange=\"$('#periodo').val('0000');$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#frmPlanilla').submit();\" ");                        
        $filter  = new stdClass();
        $filter->planilla= "05";
        $filter->anio    = $anio;
        $selperiodo  = form_dropdown('periodo',$this->periodo_model->select($filter,":::Seleccione:::","000"),$periodo," size='1' id='periodo' class='comboMedio'");               
        $filter  = new stdClass();        
        $filter->estado  = '01';
        if($ccosto_conta !="")  $filter->ccosto_conta = $ccosto_conta;
        $selccosto   = form_dropdown('ccosto',$this->ccosto_model->select($filter,":::Todos:::",""),$ccosto," size='1' id='ccosto' class='comboMedio'");               
        $filter  = new stdClass();
        $filter->estado = "01";          
        $selccosto_conta = form_dropdown('ccosto_conta',$this->ccosto_conta_model->select($filter,"::Todos:::",""),$ccosto_conta," size='1' id='ccosto_conta' class='comboMedio' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#ccosto').val('');submit();\" ");               
        $fila = "";
        /*Obtengo Carga Social 0.4702*/
        $filter = new stdClass();
        $filter->planilla= "05";
        $filter->anio    = $anio;
        $filter->periodo = $periodo;
        $periodos = $this->periodo_model->listar($filter);
        $mes_id   = @$periodos->Mes_Id;
        $this->db->where('per_code', $mes_id);        
        $this->db->from('SCIRE_DATOS');
        $conf = $this->db->get()->row();
        $var_carga_social = @$conf->per_provision;

        /*Pagos SCIRE*/
        $filter = new stdClass();
        $filter->planilla = "05";
        $filter->periodo  = $periodo;
        $filter->proceso  = array("01","02");
        $filter->concepto = array("000052");
        $mto_basico = $this->planillas_model->listar_totales($filter);
        $filter = new stdClass();
        $filter->planilla = "05";
        $filter->periodo  = $periodo;
        $filter->proceso  = array("01","02");
        $filter->concepto = array("001168","001159");
        $mto_notrib = $this->planillas_model->listar_totales($filter);
        $filter = new stdClass();
        $filter->periodo  = $periodo;
        $filter->planilla = "05";
        $filter->concepto = array("000001");
        $mto_rem = $this->dfijos_model->listar_totales($filter); 
        $filter = new stdClass();
        $filter->periodo  = $periodo;
        $filter->planilla = "05";
        $filter->concepto = array("001157");
        $mto_rem_notrib = $this->dfijos_model->listar_totales($filter);         
        $filter = new stdClass();
        $filter->planilla = "05";
        $filter->periodo  = $periodo;
        $filter->proceso  = array("01","02");
        $filter->concepto = array("000056");
        $mto_asignacion = $this->planillas_model->listar_totales($filter);        
        $filter = new stdClass();
        $filter->planilla = "05";
        $filter->periodo  = $periodo;
        $filter->proceso  = array("01","02");
        $filter->concepto = array("001169","001244");
        $mto_hextra = $this->planillas_model->listar_totales($filter);
        $filter = new stdClass();
        $filter->planilla = "05";
        $filter->periodo  = $periodo;
        $filter->proceso  = array("01","02");
        $filter->concepto = array("001173","001245");
        $mto_hdoble = $this->planillas_model->listar_totales($filter);        
        
        /*Horas Hombre SCIRE */ 
        $filter5 = new stdClass();
        $filter5->periodo  = $periodo;
        $filter5->planilla = "05";
        $filter5->concepto = array("000036","001171","001248","000037","001172","000039","000040");
        $he_simple  = $this->dvariables_model->listar_totales($filter5);       
        $filter5 = new stdClass();
        $filter5->periodo  = $periodo;
        $filter5->planilla = "05";
        $filter5->concepto = array("000038","001174","001247");
        $he_doble = $this->dvariables_model->listar_totales($filter5);
        
        /*Horas Hombre y pagos SIDDEX*/
        $filter5 = new stdClass();
        $filter5->group_by = array("o.NumeroTarjeta","p.TipoHora");
        if($fInicio!="") $filter5->fechai = $fInicio;
        if($fFin!="")    $filter5->fechaf = $fFin;
        $partes = $this->parte_model->listar_totales2($filter5);
        
        /*Relacion de personal del periodo*/
        $filter = new stdClass();
        $filter_not       = new stdClass();
        $filter->periodo  = $periodo;
        $filter->planilla = "05";
        $filter->order_by = array("Apellido_Paterno"=>"asc","Apellido_Materno"=>"asc","Nombres"=>"asc");
        if($ccosto!="")        $filter->ccosto = $ccosto;
        if($ccosto_conta!="")  $filter->ccosto_conta = $ccosto_conta;
        $personas  = $this->personalactivo_model->listar($filter, $filter_not);
        $registros = count($personas);
        if($tipoexport=="html"){
            if(count($personas)>0){
                $tot_hnormal_scire    = 0;
                $tot_hextra_scire     = 0;
                $tot_hdoble_scire     = 0;
                $tot_hnormal_siddex   = 0;
                $tot_hextra_siddex    = 0;
                $tot_hdoble_siddex    = 0;
                $tot_v_hnormal_scire  = 0;
                $tot_v_hextra_scire   = 0;
                $tot_v_hdoble_scire   = 0;
                $tot_v_hnormal_siddex = 0;
                $tot_v_hextra_siddex  = 0;
                $tot_v_hdoble_siddex  = 0;
                $arr_export_detalle   = array();
                foreach ($personas as $key => $value) {
                    if(trim($value->Area)=="")  $warning = "Existe personas que tienen no tiene centro de costo o esta mal asignado, favor verificar el detalle";
                    $v_basico_scire = 0;
                    foreach($mto_basico as $item => $val){
                        if($val->Personal_Id == $value->Personal_Id){
                            $v_basico_scire = $val->Valor;
                            break;
                        }
                    }                      
                    $v_notrib_scire = 0;
                    foreach($mto_notrib as $item => $val){
                        if($val->Personal_Id == $value->Personal_Id){
                            $v_notrib_scire = $val->Valor;
                            break;
                        }
                    }  
                    $v_rem_scire = 0;
                    foreach($mto_rem as $item => $val){
                        if($val->Personal_Id == $value->Personal_Id){
                            $v_rem_scire = $val->Valor;
                            break;
                        }
                    }  
                    $v_rem_notrib_scire = 0;
                    foreach($mto_rem_notrib as $item => $val){
                        if($val->Personal_Id == $value->Personal_Id){
                            $v_rem_notrib_scire = $val->Valor;
                            break;
                        }
                    }                      
                    $v_asignacion_scire = 0;
                    foreach($mto_asignacion as $item => $val){
                        if($val->Personal_Id == $value->Personal_Id){
                            $v_asignacion_scire = $val->Valor;
                            break;
                        }
                    }    
                    $hnormal_scire = ($v_rem_scire!=0)?($v_basico_scire*240/$v_rem_scire - 32):($v_notrib_scire*240/$v_rem_notrib_scire - 32);
                    $hextra_scire = 0;
                    foreach($he_simple as $item => $val){
                        if($val->Personal_Id == $value->Personal_Id){
                            $hextra_scire = $val->Valor;
                            break;
                        }
                    }   
                    $hdoble_scire = 0;
                    foreach($he_doble as $item => $val){
                        if($val->Personal_Id == $value->Personal_Id){
                            $hdoble_scire = $val->Valor;
                            break;
                        }
                    }

                    $v_hnormal_scire = ($v_basico_scire + $v_notrib_scire + $v_asignacion_scire) + $var_carga_social*($v_basico_scire + $v_asignacion_scire);//Formula

                    $v_hextra_scire  = 0;
                    foreach($mto_hextra as $item => $val){
                        if($val->Personal_Id == $value->Personal_Id){
                            $v_hextra_scire = $val->Valor;
                            break;
                        }
                    }
                    $v_hdoble_scire = 0;
                    foreach($mto_hdoble as $item => $val){
                        if($val->Personal_Id == $value->Personal_Id){
                            $v_hdoble_scire = $val->Valor;
                            break;
                        }
                    }  
                    $he_siddex    = 0;
                    $hd_siddex    = 0;
                    $hn_siddex    = 0;
                    $hed_siddex   = 0;
                    $hf_siddex    = 0;
                    $hes_siddex   = 0;
                    $hnoc_siddex  = 0;
                    $henoc_siddex = 0;
                    $hnormal_siddex = 0;
                    $hextra_siddex  = 0;
                    $hdoble_siddex  = 0;                
                    $v_he_siddex  = 0;
                    $v_hd_siddex  = 0;
                    $v_hed_siddex = 0;
                    $v_hf_siddex  = 0;
                    $v_hes_siddex   = 0;   
                    $v_hnoc_siddex  = 0;
                    $v_henoc_siddex = 0;
                    $v_hnormal_siddex = 0;
                    $v_hextra_siddex  = 0;
                    $v_hdoble_siddex  = 0;
                    foreach($partes as $item => $val){
                        if(trim($val->NumeroTarjeta) == trim($value->Nro_Doc)){
                            if(trim($val->TipoHora) == "Normal"){
                                $hnormal_siddex   = $val->Horas;
                                $v_hnormal_siddex = $val->Monto;
                            }
                            if(trim($val->TipoHora) == "Extra"){
                                $he_siddex   = $val->Horas;
                                $v_he_siddex = $val->Monto;                          
                            }      
                            if($val->TipoHora == "Desplazamiento"){
                                $hd_siddex   = $val->Horas;
                                $v_hd_siddex = $val->Monto;                             
                            }  
                            if($val->TipoHora == "ExtraDesplazamiento"){
                                $hed_siddex   = $val->Horas;
                                $v_hed_siddex = $val->Monto;                             
                            }  
                            if($val->TipoHora == "Festiva"){
                                $hf_siddex   = $val->Horas;
                                $v_hf_siddex = $val->Monto;                             
                            }  
                            if($val->TipoHora == "Especial"){
                                $hes_siddex   = $val->Horas;
                                $v_hes_siddex = $val->Monto;                             
                            }         
                            if($val->TipoHora == "Nocturna"){
                                $hnoc_siddex   = $val->Horas;
                                $v_hnoc_siddex = $val->Monto;                             
                            }  
                            if($val->TipoHora == "ExtraNocturna"){
                                $henoc_siddex   = $val->Horas;
                                $v_henoc_siddex = $val->Monto;                             
                            }      
                            $hextra_siddex = $he_siddex + $hd_siddex + $hnoc_siddex;
                            $hdoble_siddex = $hed_siddex + $hf_siddex + $hes_siddex + $henoc_siddex;
                            $v_hextra_siddex = $v_he_siddex + $v_hd_siddex + $v_hnoc_siddex;
                            $v_hdoble_siddex = $v_hed_siddex + $v_hf_siddex + $v_hes_siddex + $v_henoc_siddex;   
                        }
                    }                  
                    $fila .= "<tr>";
                    $fila .= "<td align='left'>" . $value->Nro_Doc . "</td>";                
                    $fila .= "<td align='left'>" . utf8_encode ($value->Apellido_Paterno) . " " . utf8_encode ($value->Apellido_Materno) . " " . utf8_encode ($value->Nombres) ." - ".$value->Personal_Id. "</td>";                
                    $fila .= "<td align='center'>" . utf8_encode($value->Area). "</td>";
                    $fila .= "<td align='center'><ul><li>H.Normal</li><li>H.Extra</li><li>H.Doble</li></ul></td>";
                    $fila .= "<td align='right'><ul>";
                    $fila .= "<li>" . number_format ($hnormal_scire, 2) . "</li>";
                    $fila .= "<li>" . number_format ($hextra_scire, 2) . "</li>";
                    $fila .= "<li>" . number_format ($hdoble_scire, 2) . "</li>";
                    $fila .="</ul></td>";
                    $fila .= "<td align='right'><ul>";
                    $fila .= "<li>" . number_format ($v_hnormal_scire, 2) . "</li>";
                    $fila .= "<li>" . number_format ($v_hextra_scire, 2) . "</li>";
                    $fila .= "<li>" . number_format ($v_hdoble_scire, 2) . "</li>";
                    $fila .="</ul></td>";
                    $fila .= "<td align='right'><ul>";
                    $fila .= "<li>" . number_format ($hnormal_siddex, 2) . "</li>";
                    $fila .= "<li>" . number_format ($hextra_siddex, 2) . "</li>";
                    $fila .= "<li>" . number_format ($hdoble_siddex, 2) . "</li>";
                    $fila .="</ul></td>";
                    $fila .= "<td align='right'><ul>";
                    $fila .= "<li>" . number_format ($v_hnormal_siddex, 2) . "</li>";
                    $fila .= "<li>" . number_format ($v_hextra_siddex, 2) . "</li>";
                    $fila .= "<li>" . number_format ($v_hdoble_siddex, 2) . "</li>";
                    $fila .="</ul></td>";                
                    $fila .= "</tr>";  
                    for($i=0;$i<3;$i++){
                        $arr_data   = array();
                        $arr_data[] = $value->Nro_Doc;
                        $arr_data[] = utf8_encode(trim($value->Apellido_Paterno)) . " " . utf8_encode(trim($value->Apellido_Materno)) . ", " . utf8_encode(strtoupper(trim($value->Nombres)));
                        $arr_data[] = utf8_encode($value->Area);
                        if($i==0){
                            $arr_data[] = "H.Normal";
                            $arr_data[] = $hnormal_scire;
                            $arr_data[] = $v_hnormal_scire;
                            $arr_data[] = $hnormal_siddex;
                            $arr_data[] = $v_hnormal_siddex;
                        }
                        if($i==1){
                            $arr_data[] = "H.Extra";
                            $arr_data[] = $hextra_scire;
                            $arr_data[] = $v_hextra_scire;
                            $arr_data[] = $hextra_siddex;
                            $arr_data[] = $v_hextra_siddex;
                        }
                        if($i==2){
                            $arr_data[] = "H.Doble";
                            $arr_data[] = $hdoble_scire;
                            $arr_data[] = $v_hdoble_scire;
                            $arr_data[] = $hdoble_siddex;
                            $arr_data[] = $v_hdoble_siddex;
                        }                    
                        array_push($arr_export_detalle,$arr_data);
                    }
                    $tot_hnormal_scire    = $tot_hnormal_scire + $hnormal_scire;
                    $tot_hextra_scire     = $tot_hextra_scire + $hextra_scire;
                    $tot_hdoble_scire     = $tot_hdoble_scire + $hdoble_scire;
                    $tot_hnormal_siddex   = $tot_hnormal_siddex + $hnormal_siddex;
                    $tot_hextra_siddex    = $tot_hextra_siddex + $hextra_siddex;
                    $tot_hdoble_siddex    = $tot_hdoble_siddex + $hdoble_siddex;
                    $tot_v_hnormal_scire  = $tot_v_hnormal_scire + $v_hnormal_scire;
                    $tot_v_hextra_scire   = $tot_v_hextra_scire + $v_hextra_scire;
                    $tot_v_hdoble_scire   = $tot_v_hdoble_scire + $v_hdoble_scire;
                    $tot_v_hnormal_siddex = $tot_v_hnormal_siddex + $v_hnormal_siddex;
                    $tot_v_hextra_siddex  = $tot_v_hextra_siddex + $v_hextra_siddex;
                    $tot_v_hdoble_siddex  = $tot_v_hdoble_siddex + $v_hdoble_siddex;                
                } 
                $var_export = array('rows' => $arr_export_detalle);
                $this->session->set_userdata('data_horastrabajadas', $var_export);
                $fila .= "<tr>";  
                $fila .= "<td colspan='3'>&nbsp;</td>";  
                $fila .= "<td align='center'><ul><li>H.Normal</li><li>H.Extra</li><li>H.Doble</li></ul></td>";
                $fila .= "<td align='right'><ul>";
                $fila .= "<li>" . number_format ($tot_hnormal_scire, 2) . "</li>";
                $fila .= "<li>" . number_format ($tot_hextra_scire, 2) . "</li>";
                $fila .= "<li>" . number_format ($tot_hdoble_scire, 2) . "</li>";
                $fila .="</ul></td>";
                $fila .= "<td align='right'><ul>";
                $fila .= "<li>" . number_format ($tot_v_hnormal_scire, 2) . "</li>";
                $fila .= "<li>" . number_format ($tot_v_hextra_scire, 2) . "</li>";
                $fila .= "<li>" . number_format ($tot_v_hdoble_scire, 2) . "</li>";
                $fila .="</ul></td>";
                $fila .= "<td align='right'><ul>";
                $fila .= "<li>" . number_format ($tot_hnormal_siddex, 2) . "</li>";
                $fila .= "<li>" . number_format ($tot_hextra_siddex, 2) . "</li>";
                $fila .= "<li>" . number_format ($tot_hdoble_siddex, 2) . "</li>";
                $fila .="</ul></td>";
                $fila .= "<td align='right'><ul>";
                $fila .= "<li>" . number_format ($tot_v_hnormal_siddex, 2) . "</li>";
                $fila .= "<li>" . number_format ($tot_v_hextra_siddex, 2) . "</li>";
                $fila .= "<li>" . number_format ($tot_v_hdoble_siddex, 2) . "</li>";
                $fila .="</ul></td>";   
                $fila .= "</tr>";  
            }  
        }
        $data['hora_actual'] = "";
        $data['registros']   = $registros;
        $data['fila']        = $fila;
        $data['selperiodo']  = $selperiodo;
        $data['selanio']     = $selanio;
        $data['selccosto']   = $selccosto;
        $data['selccosto_conta'] = $selccosto_conta;
        $data['fInicio']     = $fInicio;
        $data['fFin']        = $fFin;
        $data['oculto']      = form_hidden(array('tipoexport'=>$tipoexport));
        $this->load->view(scire . "scire_horastrabajadas", $data);        
    }
    
    public function exportarExcel() {
        if((isset($_SESSION['personas_data']) && $_SESSION['personas_data'] != "") && (isset($_SESSION['cfecha_data']) && $_SESSION['cfecha_data'] != "") && (isset($_SESSION['horaspersona_data']) && $_SESSION['horaspersona_data'] != "")) {
            $personas_data = $_SESSION['personas_data'];
            $cfecha_data = $_SESSION['cfecha_data'];
            $horaspersona_data = $_SESSION['horaspersona_data'];
            $arr_columns = array();
            $arr_data = array();
            $index = 0;
            $arr_columns[0]['STRING'] = 'TIPO';
            $arr_columns[1]['STRING'] = 'PERSONA';
            $arr_columns[2]['STRING'] = 'DNI';
            $arr_columns[3]['STRING'] = 'CCOSTO';
            $i = 4;
            foreach($cfecha_data as $k => $v) {
                $arr_columns[$i]['STRING'] = $v;
                $i++;
            }
            $arr_columns[$i]['STRING'] = 'TOTAL';
            foreach ($personas_data as $key => $value) {
                $data = array();
//                $arr_data[$index] = array(
//                    $index + 1,
//                    utf8_encode($value->Apellido_Paterno) . " " . utf8_encode($value->Apellido_Materno) . " " . utf8_encode($value->Nombres),
//                    number_format($tot, 2),
//                    $value->Nro_cta,
//                    $value->Nro_Doc
//                );
                if($value['TipoTrabId'] == '20') {
                    $data[] = "Obrero";
                }
                elseif($value['TipoTrabId'] == '21') {
                    $data[] = "Empleado";
                }
                else {
                    $data[] = "-";
                }
                $data[] = utf8_encode($value['Nombres']);
                $data[] = $value['Dni'];
                $data[] = utf8_encode($value['Ccosto']);
                $total = 0;
                foreach($cfecha_data as $k => $v) {
                    if (isset($horaspersona_data[$key][$v]) && $horaspersona_data[$key][$v] != "") {
                        $data[] = $horaspersona_data[$key][$v];
                        $total = $total + $horaspersona_data[$key][$v];
                    } else {
                        $data[] = 0;
                        $total = $total + 0;
                    }
//                    $data[] = $horaspersona_data[$key][$v];
//                    $total = $total + $horaspersona_data[$key][$v];
                }
                $data[] = $total;
                $arr_data[$index] = $data;
                $index++;
            }
            $this->reports_model->rpt_general('rpt_horas_trab', 'Reporte de Horas Trabajadas', $arr_columns, $arr_data);
            unset($_SESSION['personas_data']);
            unset($_SESSION['cfecha_data']);
            unset($_SESSION['horaspersona_data']);
            
        }
        else {
            header('Location: http://nazca/mimco_planillas/index.php/scire/scire/horaslaboradas');
        }
    }
    
    public function ctecorriente(){
        $periodo    = $this->input->get_post('periodo'); 
        $anio       = $this->input->get_post('anio'); 
        $mes        = $this->input->get_post('mes');
        $ccosto     = $this->input->get_post('ccosto');
        $tipoexport = $this->input->get_post('tipoexcel');
        $numreg = 0;
        $filter  = new stdClass();
        $filter2 = new stdClass();
        $filter3 = new stdClass();
        $filter_not = new stdClass();
        $filter->anio = $anio;
        $filter2->mes = $mes;
        $selanio    = form_dropdown('anio',array(""=>"::Seleccione:::","2011"=>"2011","2012"=>"2012","2013"=>"2013"),$anio," size='1' id='anio' class='comboPeque' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#frmPlanilla').submit();\" ");               
        $selmes     = form_dropdown('mes',$this->mes_model->select($filter,"::Seleccione:::",""),$mes," size='1' id='mes' class='comboPeque' onchange=\"$('#frmPlanilla').attr('target','_self');$('#frmPlanilla').attr('action','');$('#frmPlanilla').submit();\" ");
        $selperiodo  = form_dropdown('periodo[]',$this->periodo_model->select($filter2,"::Seleccione:::","000"),$periodo," multiple size='4' id='periodo' class='comboPersonalizado'");               
        $selccosto  = form_dropdown('ccosto',$this->ccosto_model->select($filter3,"::Seleccione:::",""),$ccosto," size='1' id='ccosto' class='comboMedio'");
//        $filter->entidad = $this->entidad;
        $filter->periodo = $periodo;
        $fila = "";
        
        if($periodo != "") {
            $ctecorriente = $this->ctecorriente_model->getDetalleCteCorriente($filter, $filter_not);
            foreach($ctecorriente as $key=>$value) {
                $fila .= "<tr>";
                $fila .= "<td>" . utf8_encode($value->Nombres) . "</td>";
                $fila .= "<td>" . $value->FecMov . "</td>";
                $fila .= "<td>" . $value->Prestamo . "</td>";
                $fila .= "<td>" . $value->Nro_cuotas . "</td>";
                $fila .= "<td>" . $value->Cuota_desc . "</td>";
                $fila .= "<td>" . $value->Interes . "</td>";
                $fila .= "<td>" . $value->Monto . "</td>";
                $fila .= "<td>" . $value->Periodo . "</td>";
                $fila .= "<td>" . $value->Estado . "</td>";
                $fila .= "<td>" . $value->Motivo . "</td>";
                $fila .= "<td>" . $value->Operacion . "</td>";
                $fila .= "</tr>";
                $numreg++;
            }
            
            if ($tipoexport == "1") {
                $tipoexport = "";
                $arr_columns[0]['STRING'] = 'NOMBRES';
                $arr_columns[1]['STRING'] = 'FECMOV';
                $arr_columns[2]['STRING'] = 'MONTO';
                $arr_columns[3]['STRING'] = 'NROCUOTAS';
                $arr_columns[4]['STRING'] = 'DESCUOTA';
                $arr_columns[5]['STRING'] = 'INTERES';
                $arr_columns[6]['STRING'] = 'CUOTA';
                $arr_columns[7]['STRING'] = 'PERIODO';
                $arr_columns[8]['STRING'] = 'ESTADO';
                $arr_columns[9]['STRING'] = 'MOTIVO';
                $arr_columns[10]['STRING'] = 'OPERACION';
                
                $var_prd_n = 0;
                $var_row = 7;
                $arr_data = array();
                if (count($ctecorriente) > 0) {
                    foreach ($ctecorriente as $indice => $value) {
                        $nombres = trim($value->Nombres);
//                        $fecmov = trim($value->FecMov);
                        $fecmov = date('d-m-Y', strtotime($value->FecMov));
                        $prestamo = trim($value->Prestamo);
                        $nrocuotas = trim($value->Nro_cuotas);
                        $descuota = trim($value->Cuota_desc);
                        $interes = trim($value->Interes);
                        $monto = trim($value->Monto);
                        $periodo = trim($value->Periodo);
                        $estado = trim($value->Estado);
                        $motivo = trim($value->Motivo);
                        $operacion = trim($value->Operacion);
                        
                        $arr_data[$var_prd_n] = array(
                            utf8_encode($nombres),
                            $fecmov,
                            $prestamo,
                            $nrocuotas,
                            $descuota,
                            $interes,
                            $monto,
                            $periodo,
                            $estado,
                            $motivo,
                            $operacion
                        );
                        $var_prd_n++;
                        $var_row++;
                    }
                }
                
                $arr_grouping_header = array();
                $this->reports_model->rpt_general('rpt_cte_corriente', 'Prestamos en el Periodo: ' . $periodo, $arr_columns, $arr_data, $arr_grouping_header);
            }
        }
        
        $data['selperiodo'] = $selperiodo;
        $data['selanio']   = $selanio;
        $data['selmes']    = $selmes; 
        $data['selccosto'] = $selccosto;
        $data['fila'] = $fila;
        $data['numreg'] = $numreg;
        $this->load->view(scire . "scire_ctecorriente",$data);
    }
    
    public function prepareColumnsDetalleObrero() {
        $arr_columns = array();
        $arr_columns[0]['STRING'] = 'NRO';
        $arr_columns[1]['STRING'] = 'Datos del Personal';
        $arr_columns[2]['NUMERIC'] = '0007-BASIC';
        $arr_columns[3]['NUMERIC'] = '0010-DESCANSO SEMANAL';
        $arr_columns[4]['NUMERIC'] = 'III-REINTEGRO';
        $arr_columns[5]['NUMERIC'] = 'YYYY-REINTEGRO_INAFECTO';
        $arr_columns[6]['NUMERIC'] = '0147-BASICO NO TRIB';
        $arr_columns[7]['NUMERIC'] = '0028-ASIG FAMILIAR';
        $arr_columns[8]['NUMERIC'] = 'ZZZ-HORA_EXTRA HRS';
        $arr_columns[9]['NUMERIC'] = '0148-H EXTRAS S/.';
        $arr_columns[10]['NUMERIC'] = 'III-H DOBLE HRS';
        $arr_columns[11]['NUMERIC'] = 'XXX-H DOBLE S/.';
        $arr_columns[12]['NUMERIC'] = 'TOTAL';
        $arr_columns[13]['NUMERIC'] = '0053-ONP';
        $arr_columns[14]['NUMERIC'] = '0054-AFP FONDO';
        $arr_columns[15]['NUMERIC'] = '0055-AFP COMIS VAR';
        $arr_columns[16]['NUMERIC'] = '0056-AFP SEGURO';
        $arr_columns[17]['NUMERIC'] = '0127-PRESTAMO PERSONAL';
        $arr_columns[18]['NUMERIC'] = '0130-DESCT. COMEDOR';
        $arr_columns[19]['NUMERIC'] = 'CCCC-DESCT. ADICIONAL';
        $arr_columns[20]['NUMERIC'] = 'TOTAL';
        $arr_columns[21]['NUMERIC'] = '0140-ESSALUD';
        $arr_columns[22]['NUMERIC'] = '0141-SENATI';
        $arr_columns[23]['NUMERIC'] = '0142-SCTR SALUD';
        $arr_columns[24]['NUMERIC'] = '0143-SCTR PENSION';
        $arr_columns[25]['NUMERIC'] = 'TOTAL';
        $arr_columns[26]['NUMERIC'] = '0146-NETO REMUNIERACIONES';
        $arr_columns[27]['NUMERIC'] = '0000-TOTAL1';
        $arr_columns[28]['NUMERIC'] = '0000-TOTAL2';
        return $arr_columns;
    }
    
    public function prepareColumnsDetalleEmpleado() {
        $arr_columns = array();
        $arr_columns[]['STRING'] = 'NRO';
        $arr_columns[]['STRING'] = 'Datos del Personal';
        $arr_columns[]['NUMERIC'] = '0016-BASIC';
        $arr_columns[]['NUMERIC'] = '0028-ASIG FAMILIAR';
        $arr_columns[]['NUMERIC'] = 'YYYY-REINTEGRO';
        $arr_columns[]['NUMERIC'] = 'YYYY-REINTEGRO INAF.';
        $arr_columns[]['NUMERIC'] = '0056-BON. EXTRAORDINARIA';
        $arr_columns[]['NUMERIC'] = '0181-BASICO NO TRIB';
        $arr_columns[]['NUMERIC'] = '0037-MOVILIDAD';
        $arr_columns[]['NUMERIC'] = '0060-VIATICOS';
        $arr_columns[]['NUMERIC'] = 'TOTAL';
        $arr_columns[]['NUMERIC'] = '0064-TARDANZA';
        $arr_columns[]['NUMERIC'] = '0071-ONP';
        $arr_columns[]['NUMERIC'] = '0072-AFP FONDO';
        $arr_columns[]['NUMERIC'] = '0073-AFP COM VARIABLE';
        $arr_columns[]['NUMERIC'] = '0080-AFP SEGURO';
        $arr_columns[]['NUMERIC'] = '0147-RETENCION 5TA';
        $arr_columns[]['NUMERIC'] = '0151-ADEL QUINCENA';
        $arr_columns[]['NUMERIC'] = '0155-PRESTAMO PERSONAL';
        $arr_columns[]['NUMERIC'] = '0158-DESCT COMEDOR';
        $arr_columns[]['NUMERIC'] = 'XXXX-DESCT 4TA';
        $arr_columns[]['NUMERIC'] = '0130-DESCT ADICIONAL';
        $arr_columns[]['NUMERIC'] = 'TOTAL';
        $arr_columns[]['NUMERIC'] = '0167-ESSALUD';
        $arr_columns[]['NUMERIC'] = '0172-SENATI';
        $arr_columns[]['NUMERIC'] = '0173-SCTR SALUD';
        $arr_columns[]['NUMERIC'] = '0177-SCTR PENSION';
        $arr_columns[]['NUMERIC'] = 'TOTAL';
        $arr_columns[]['NUMERIC'] = '0180-NETO REMUN';
        $arr_columns[]['NUMERIC'] = '0000-TOTAL1';
        $arr_columns[]['NUMERIC'] = '0000-TOTAL2';
        return $arr_columns;
    }
    
    public function prepareColumns(){
        $arr_columns = array();
        $arr_columns[0]['STRING'] = 'NRO';
        $arr_columns[1]['NUMERIC'] = 'APELLIDOS Y NOMBRES';
        $arr_columns[2]['NUMERIC'] = '0010-DESCANSO SEMANAL';
        $arr_columns[3]['NUMERIC'] = 'IMPORTE S/.';
        $arr_columns[4]['NUMERIC'] = 'NUMERO DE CUENTA';
        $arr_columns[5]['NUMERIC'] = 'DNI';
        return $arr_columns;
    }
    
    public function prepareDataDetalleObrero($data){
        $var_item_n = 0;
        $var_reg = 1;
        $arr_data = array();
        $t1 = 0;
        $t2 = 0;
        $t3 = 0;
        foreach($data as $indice=>$value) {
            $nombres = utf8_encode ($value->Apellido_Paterno) . " " . utf8_encode ($value->Apellido_Materno) . " " . utf8_encode ($value->Nombres);
            $basico = $value->basico_diario;
            $dsemanal = $value->dsemanal;
            $reintegro = $value->reintegro;
            $reintegro_afecto = $value->reintegro_afecto;
            $hora_extra = $value->hora_extra;
            $hora_doble = $value->hora_extra_doble;
            $hdoble = $value->hdoble;
            $basico_no_tribut = $value->no_tributario;
            $hextras = $value->montoh_extras;
            $total_ingresos = $value->basico_diario + $value->dsemanal + $value->no_tributario + $value->montoh_extras;
            $onp = $value->onp_fondo;
            $afp_fondo = $value->afp_fondo;
            $afp_comis = $value->afp_com_var;
            $afp_seguro = $value->afp_pri_seg;
            $prestamo = $value->prestamos;
            $dscto_comedor = $value->dscto_comedor;
            $dscto_adicional = $value->dscto_adicional;
            $monto_tardanza = $value->monto_tardanza;
            $total_descuentos = $value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_pri_seg + $value->prestamos + $value->dscto_comedor + $value->dscto_adicional;
            $essalud = $value->essalud;
            $senati = $value->senati;
            $salud = $value->sctr_salud;
            $pension = $value->sctr_pension;
            $asignacion = $value->asignacion;
            $bonificacion = $value->bonificacion;
            $tardanza = $value->tardanza;
            $adelanto = $value->adelanto;
            $retencion = $value->retencion;
            $total_aportes = $value->essalud + $value->senati + $value->sctr_salud + $value->sctr_pension;
            $total_neto = $basico + $dsemanal + $asignacion + $reintegro + $reintegro_afecto - $total_descuentos;
            $t_fuera = $basico_no_tribut + $hextras + $hdoble;
            $t_total = $total_neto + $t_fuera;
            $t1 = $t1 + $total_neto;
            $t2 = $t2 + $t_fuera;
            $t3 = $t3 + $t_total;
            if($total_neto == 0 && $t_fuera == 0) {
                continue;
            }
            $arr_data[$var_item_n] = array($var_reg, $nombres, $basico, $dsemanal,$reintegro_afecto, $reintegro, $basico_no_tribut,$asignacion, $hora_extra, $hextras,
                $hora_doble, $hdoble, $total_ingresos, $onp, $afp_fondo, $afp_comis, $afp_seguro, $prestamo, $dscto_comedor, $dscto_adicional,
                $total_descuentos, $essalud, $senati, $salud, $pension, $total_aportes, $total_neto,
                $t_fuera, $t_total);
            $var_item_n++;
            $var_reg ++;
        }
        $arr_data[$var_item_n] = array('','','','','','','','','','','','','','','','','','','','','','','','','','',$t1,$t2,$t3);
        return $arr_data;
    }
    
    public function prepareDataDetalleEmpleado($data){
        $var_item_n = 0;
        $var_reg = 1;
        $t1 = 0;
        $t2 = 0;
        $t3 = 0;
        $arr_data = array();
        foreach($data as $indice=>$value) {
            $nombres = utf8_encode ($value->Apellido_Paterno) . " " . utf8_encode ($value->Apellido_Materno) . " " . utf8_encode ($value->Nombres);
            $basico = $value->basico_diario;
            $dsemanal = $value->dsemanal;
            $basico_no_tribut = $value->no_tributario;
            $hextras = $value->montoh_extras;            
            $movilidad = $value->movilidad;
            $viaticos = $value->viaticos;            
            $total_ingresos = $value->movilidad+$value->viaticos+$value->basico_diario + $value->asignacion + $value->no_tributario + $value->bonificacion + $value->reintegro+ $value->reintegro_inafecto;
            $onp = $value->onp_fondo;
            $afp_fondo = $value->afp_fondo;
            $afp_comis = $value->afp_com_var;
            $afp_seguro = $value->afp_pri_seg;
            $prestamo = $value->prestamos;
            $dscto_comedor = $value->dscto_comedor;
            $dscto_adicinal = $value->dscto_adicional;
            $monto_tardanza = $value->monto_tardanza;
            $dscto_4TA = $value->dscto_4TA;
            $total_descuentos = $value->onp_fondo + $value->afp_fondo + $value->afp_com_var + $value->afp_pri_seg + $value->prestamos + $value->dscto_comedor + $value->adelanto + $value->tardanza + $value->retencion + $value->dscto_adicional + $value->dscto_4TA;
            $essalud = $value->essalud;
            $senati = $value->senati;
            $salud = $value->sctr_salud;
            $pension = $value->sctr_pension;
            $asignacion = $value->asignacion;
            $reintegro = $value->reintegro;
            $reintegro_inafecto = $value->reintegro_inafecto;
            $bonificacion = $value->bonificacion;
            $tardanza = $value->tardanza;
            $adelanto = $value->adelanto;
            $retencion = $value->retencion;
            $total_neto = $value->basico_diario + $value->asignacion + $value->reintegro+ $value->reintegro_inafecto + $value->bonificacion+ $value->movilidad+ $value->viaticos - $total_descuentos;
            $t_fuera = $basico_no_tribut ;
            $t_total = $total_neto + $t_fuera;
            $total_aportes = $value->essalud + $value->senati + $value->sctr_salud + $value->sctr_pension;
            $total_fuera   = $value->no_tributario; 
            $t1 = $t1 + $total_neto;
            $t2 = $t2 + $t_fuera;
            $t3 = $t3 + $t_total;
            if($total_neto == 0 && $total_fuera == 0 && $value->basico_diario == 0) {
                continue;
            }
            $arr_data[$var_item_n] = array($var_reg, $nombres, $basico, $asignacion, $reintegro, $reintegro_inafecto, $bonificacion, $basico_no_tribut, $movilidad, $viaticos,
                $total_ingresos, $tardanza, $onp, $afp_fondo, $afp_comis, $afp_seguro, $retencion, $adelanto, $prestamo, $dscto_comedor,
                $dscto_4TA,$dscto_adicinal,$total_descuentos, $essalud, $senati, $salud, $pension, $total_aportes, $total_neto,$t_fuera,$t_total);
            $var_item_n++;
            $var_reg ++;
        }
        $arr_data[$var_item_n] = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','',$t1,$t2,$t3);
        return $arr_data;
    } 
}
?>