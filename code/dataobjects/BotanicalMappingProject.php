<?php

class BotanicalMappingProject extends DataObject
{

    public static $db = array(
        'Title' => 'Varchar',
    );

    private static $indexes = array(
        'uniqueConstraint' => 'unique("Title")'
    );

    private static $has_many = array(
        'Surveys' => 'BotanicalSurvey',
    );

    private static $summary_fields = array(
        'Title',
    );


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }


    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
    }


}
