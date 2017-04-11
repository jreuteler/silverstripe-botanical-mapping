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

    private static $has_many = array(
        'Specimens' => 'TreeSpecimen',
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }


    public function getTitle()
    {
        return $this->CommonName . ' (' . ($this->ScientificName) . ')';
    }

    public function Link()
    {
        return $this->ID;
    }

    public function EditLink()
    {
        return BotanicalMappingController::$controllerPath . '/' . $this->RecordClassName . '/edit/' . $this->ID;
    }

    public function ShowListLink()
    {
        return BotanicalMappingController::$controllerPath . '/' . $this->RecordClassName . '/showlist';
    }

    public function getExternalLink()
    {
        return $this->EditLink();
    }

    public function getExternalLinkText()
    {
        return 'Edit';
    }

    public function onBeforeWrite()
    {
        $this->ScientificName = strtolower($this->ScientificName);
        parent::onBeforeWrite();
    }


}
