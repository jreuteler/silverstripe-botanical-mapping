<?php

class BotanicalSurvey extends DataObject
{
    public static $db = array(
        'Title' => 'Varchar',
    );

    private static $indexes = array(
        'uniqueConstraint' => 'unique("Title")'
    );

    private static $has_one = array(
        'Project' => 'BotanicalMappingProject',
    );

    private static $has_many = array(
        'Specimens' => 'TreeSpecimen',
    );

    private static $summary_fields = array(
        'Title',
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }


}
