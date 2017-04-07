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

    private static $many_many = array(
        'Trees' => 'TreeSpecies',
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }


}
