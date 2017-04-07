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
        'BotanicalSurveys' => 'BotanicalSurvey',
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
        $this->Title = strtolower($this->Title);
        parent::onBeforeWrite();
    }


}
