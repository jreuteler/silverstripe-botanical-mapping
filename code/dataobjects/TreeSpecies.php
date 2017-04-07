<?php

class TreeSpecies extends DataObject
{

    public static $db = array(
        'ScientificName' => 'Varchar',
        'CommonName' => 'Varchar',
    );

    private static $indexes = array(
        'uniqueConstraint' => 'unique("ScientificName")'
    );

    private static $summary_fields = array(
        'CommonName',
        'ScientificName'
    );

    private static $many_many = array(
        'Surveys' => 'BotanicalSurvey',
    );


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }

    public function GetTitle()
    {
        return $this->CommonName . ' (' . ($this->ScientificName) . ')';
    }


    public function onBeforeWrite()
    {
        $this->ScientificName = strtolower($this->ScientificName);
        parent::onBeforeWrite();
    }


}
