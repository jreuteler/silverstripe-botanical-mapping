<?php

class SpecimenStatus extends DataObject
{
    public static $db = array(
        'Date' => 'Date',
        'TotalHeight' => 'Int',
        'CrownHeight' => 'Int',
        'Diameter' => 'Int',
        'Comment' => 'Text',
    );

    private static $has_one = array(
        'Specimen' => 'TreeSpecimen',
        'Image' => 'Image',
    );

    private static $summary_fields = array(
        'TotalHeight',
        'CrownHeight',
        'Diameter',
        'Comment',
    );


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('SpecimenID');

        return $fields;
    }

}
