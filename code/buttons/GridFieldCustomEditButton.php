<?php


class GridFieldCustomEditButton extends GridFieldEditButton
{
    public function getColumnContent($gridField, $record, $columnName)
    {

        $data = new ArrayData(array(
            'Link' => $record->EditLink()
        ));

        return $data->renderWith('GridFieldEditButton');
    }

}