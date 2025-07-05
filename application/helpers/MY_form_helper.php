<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function form_hidden($name, $value = '', $recursing = FALSE){
    static $form;
    if ($recursing === FALSE){
        $form = "\n";
    }

    if (is_array($name)){
        foreach ($name as $key => $val){
            form_hidden($key, $val, TRUE);
        }
        return $form;
    }

    if ( ! is_array($value)){
        $form .= '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.form_prep($value, $name).'" />'."\n";
    }
    else
    {
        foreach ($value as $k => $v){
            $k = (is_int($k)) ? '' : $k;
            form_hidden($name.'['.$k.']', $v, TRUE);
        }
    }
    return $form;
}

function form_checkbox($data = '', $value = '', $checked = FALSE, $extra = '')
{
        $defaults = array('type' => 'checkbox', 'name' => (( ! is_array($data)) ? $data : ''),'id' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
        if (is_array($data) AND array_key_exists('checked', $data))
        {
                $checked = $data['checked'];
                if ($checked == FALSE)
                {
                        unset($data['checked']);
                }
                else
                {
                        $data['checked'] = 'checked';
                }
        }
        if ($checked == TRUE)
        {
                $defaults['checked'] = 'checked';
        }
        else
        {
                unset($defaults['checked']);
        }
        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
}
?>
