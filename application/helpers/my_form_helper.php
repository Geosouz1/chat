<?php

if (!function_exists('form_input')) {

    /**
     * Text Input Field
     *
     * @param	mixed
     * @param	string
     * @param	mixed
     * @return	string
     */
    function form_input($data = '', $value = '', $extra = '', $autocomplete = 'off') {
        $defaults = array(
            'type' => 'text',
            'name' => is_array($data) ? '' : $data,
            'value' => $value
        );

        return '<input ' . _parse_form_attributes($data, $defaults) . _attributes_to_string($extra) . " autocomplete=\"{$autocomplete}\" />\n";
    }

}

if (!function_exists('form_textarea')) {

    /**
     * Textarea field
     *
     * @param	mixed	$data
     * @param	string	$value
     * @param	mixed	$extra
     * @param	mixed	$rows
     * @param	mixed	$col
     * @return	string
     */
    function form_textarea($data = '', $value = '', $extra = '', $rows = '10', $col = '40') {
        $defaults = array(
            'name' => is_array($data) ? '' : $data,
            'cols' => $col,
            'rows' => $rows
        );

        if (!is_array($data) OR ! isset($data['value'])) {
            $val = $value;
        } else {
            $val = $data['value'];
            unset($data['value']); // textareas don't use the value attribute
        }

        return '<textarea ' . _parse_form_attributes($data, $defaults) . _attributes_to_string($extra) . '>'
                . html_escape($val)
                . "</textarea>\n";
    }

}

if (!function_exists('form_hidden')) {

    /**
     * Hidden Input Field
     *
     * Generates hidden fields. You can pass a simple key/value string or
     * an associative array with multiple values.
     *
     * @param	mixed	$name		Field name
     * @param	string	$value		Field value
     * @param	bool	$recursing
     * @return	string
     */
    function form_hidden($name, $value = '', $recursing = FALSE, $attributes = []) {
        static $form;

        $attributes = _attributes_to_string($attributes);

        if ($recursing === FALSE) {
            $form = "\n";
        }

        if (is_array($name)) {
            foreach ($name as $key => $val) {
                form_hidden($key, $val, TRUE, $attributes);
            }

            return $form;
        }

        if (!is_array($value)) {
            $form .= '<input type="hidden" name="' . $name . '" value="' . html_escape($value) . "\" " . $attributes . " />\n";
        } else {
            foreach ($value as $k => $v) {
                $k = is_int($k) ? '' : $k;
                form_hidden($name . '[' . $k . ']', $v, TRUE, $attributes);
            }
        }

        return $form;
    }

}