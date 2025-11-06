<?php 

namespace App\Services;


Class HelperServices
{
    public function option($data, $value, $label, $selected = null)
    {
        $html = '';
        foreach ($data as $item) {
            // Ambil value nested pakai dot notation
            $labelValue = data_get($item, $label);
            $valueKey = data_get($item, $value);

            $isSelected = ($selected !== null && $valueKey == $selected) ? ' selected' : '';
            $html .= '<option value="' . $valueKey . '"' . $isSelected . '>' . $labelValue . '</option>';
        }
        return $html;
    }
}
?>