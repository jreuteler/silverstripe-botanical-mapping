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

    public function getFrontEndFields($params = NULL)
    {
        $fields = parent::getFrontEndFields($params);

        $fields->removeByName('SpecimenID');
        $fields->insertBefore('Date', ReadonlyField::create('Species')->setValue($this->Specimen()->getTitle()));

        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldButtonRow('before'));
        $config->addComponent(new GridFieldEditableColumns());
        $config->addComponent(new GridFieldDeleteAction());
        $config->addComponent(new GridFieldExternalLink());

        return $fields;
    }

    public function getTitle()
    {
        return $this->Date;
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

    public function getBreadcrumbParent()
    {
        return $this->Specimen();
    }

}
