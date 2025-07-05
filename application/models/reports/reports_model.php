<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reports_model extends CI_Model{
    
    var $arr_datatype;
    
    public function __construct(){
        parent::__construct();      
        $this->load->library('excel');
        
        $this->arr_datatype = array(
            'STRING2'       => PHPExcel_Cell_DataType::TYPE_STRING2,
            'STRING'        => PHPExcel_Cell_DataType::TYPE_STRING,
            'FORMULA'       => PHPExcel_Cell_DataType::TYPE_FORMULA,
            'NUMERIC'       => PHPExcel_Cell_DataType::TYPE_NUMERIC,
            'BOOL'          => PHPExcel_Cell_DataType::TYPE_BOOL,
            'NULL'          => PHPExcel_Cell_DataType::TYPE_NULL,
            'TYPE_INLINE'   => PHPExcel_Cell_DataType::TYPE_INLINE,
            'TYPE_ERROR'    => PHPExcel_Cell_DataType::TYPE_ERROR,
            'DATE'          => "DATE",
            'DECIMAL'       => "DECIMAL"
        ); 
 
    }
    
    /*
     * Function "rpt_general"
     * 
     * var $rpt_name    -> (str) Nombre del archivo a devolver.
     * var $rpt_title   -> (str) Título del reporte.
     * var $arr_columns -> (arr) Nombre de columnas.
     * var $arr_data    -> (arr) Registros a cargar
     * 
     * [optional]
     * var $arr_grouping_header -> (arr) Columnas agrupadas
     * 
     * [return]
     * Devuelve documento excel
     * 
     * 
     */
    
    public function rpt_general($rpt_name = '' , $rpt_title = '' , $arr_columns = array() , $arr_data = array() , $arr_grouping_header = array()){
        
    
        $rpt_name   = strtolower(trim($rpt_name));
        $rpt_title  = strtoupper(trim($rpt_title));
        $rpt_user   = strtoupper(trim($this->session->userdata('nomper')));
        
        $var_row_start = 6;
        
        $this->excel->setActiveSheetIndex(0);
        $worksheet = $this->excel->getActiveSheet();
        $worksheet->setTitle($rpt_name);
        

        $worksheet->setCellValue('A1', 'REPORTE : '.$rpt_title);
        $worksheet->setCellValue('A2', 'USUARIO : '.$rpt_user);
        $worksheet->setCellValue('A3', 'FECHA   : '.date('d/m/Y H:i:s'));
        $worksheet->mergeCells("A1:F1");
        $worksheet->mergeCells("A2:F2");
        $worksheet->mergeCells("A3:F3");
        $worksheet->freezePane('A7');
        
        $arr_col_type = array();

        foreach ($arr_columns as $var_col_key => $var_col_values) {   
            foreach ($var_col_values as $var_col_type => $var_col_value) {
                $arr_col_type[$var_col_key] = $this->arr_datatype[$var_col_type];
                $worksheet->setCellValueByColumnAndRow($var_col_key, $var_row_start, $var_col_value);
            }
        }
        
        PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder()); //Para el formato de fecha.
        foreach ($arr_data as $var_row_key => $var_row_value) {
            foreach ($var_row_value as $col => $val) {
                $row = $var_row_key+$var_row_start+1;
                
                
                switch ($arr_col_type[$col]) {
                    case "DATE":
                        $worksheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($col).$row, $val); 
                        $worksheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
                        break;
                    
                    case "n":
                        
                        $worksheet->setCellValueExplicitByColumnAndRow($col,$row,$val,$arr_col_type[$col]);
                        $worksheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).$row)->getNumberFormat()->applyFromArray(
                            array('code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1)
                        );

                        break;
                    
                    case "f":
                        
                        $worksheet->setCellValueExplicitByColumnAndRow($col,$row,$val,$arr_col_type[$col]);
                        $worksheet->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).$row)->getNumberFormat()->applyFromArray(
                            array('code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1)
                        );

                        break;
                    
                    default:
                        $worksheet->setCellValueExplicitByColumnAndRow($col,$row,$val,$arr_col_type[$col]);
                        break;

            
                }
                //$worksheet->setCellValueExplicitByColumnAndRow($col,$row,$arr_col_type[$col],$arr_col_type[$col] );
                
                
            }
        }

        $style_header = array(
            'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array(
                'name' => 'Arial',
                'size' => '10',
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => '4F81BD',
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '4F81BD'),
		)
            )
        );
        
        $var_num_columns        = PHPExcel_Cell::stringFromColumnIndex(count($arr_columns)-1);
        
        $var_table_row_end      = count($arr_data)+$var_row_start;
        $var_table_row_start    = $var_row_start+1;

        $var_header_range       = 'A'.$var_row_start.':'.$var_num_columns.$var_row_start;
        $var_table_range        = 'A'.$var_table_row_start.':'.$var_num_columns.$var_table_row_end;
        
       
        
        
        
        
        $worksheet->getStyle($var_header_range)->applyFromArray($style_header);
        $worksheet->getRowDimension($var_row_start)->setRowHeight(20);
        
        $style_table = array(
            'borders' => array(
                'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb' => 'C6C6C6'),
		),
                'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb' => '4F81BD'),
		)
            )
        );
        
        $worksheet->getStyle($var_table_range)->applyFromArray($style_table);
        
        foreach ($arr_col_type as $key => $value) {
            $worksheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($key))->setAutoSize(true);
        }
        
        
        
        $style_grouping_header = array(
            'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array(
                'name' => 'Arial',
                'size' => '10',
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => '6D97C9',
                )
            ),
            'borders' => array(
                'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THICK,
			'color' => array('rgb' => '4F81BD'),
		)
            )
        );
        
        
        
        foreach($arr_grouping_header as $range => $value){
            $var_cell = explode(':',$range);
            $worksheet->setCellValue($var_cell[0], $value);
            $worksheet->mergeCells($range);
            $worksheet->getStyle($range)->applyFromArray($style_grouping_header);
        }
        
        
        
            
        //$var_dimension = $worksheet->calculateWorksheetDimension();
        //$worksheet->getStyle($var_dimension)->applyFromArray($style_table);
        //$worksheet->setAutoFilter($var_dimension);
        
        $worksheet->getSheetView()->setZoomScale(75);
        $worksheet->setAutoFilter($var_header_range); 
        $worksheet->setSelectedCell('A1');
        
        /*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$rpt_name.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');*/
  
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$rpt_name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
        
    }
    
    /* FUNCTION get_headers
     * 
     * Devulve cabeceras de tabla.
     */
    
    public function get_headers($param) {
        $var_data ="";
        
        if(count($param)>0){
            foreach ($param as $var_col_keys => $var_col_values) {   
                foreach ($var_col_values as $var_col_type => $var_col_value) {
                    $var_data .= "<th>".$var_col_value."</th>";
                }
            }
        }
        return $var_data;
    }
    
}
