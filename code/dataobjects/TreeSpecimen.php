<?php

class TreeSpecimen extends DataObject
{
    public static $db = array(
        'GeoLocation' => 'SS_GeoLocation',
        'Comment' => 'Text',
    );

    private static $has_one = array(
        'Survey' => 'BotanicalSurvey',
        'Species' => 'TreeSpecies',
    );

    private static $has_many = array(
        'Statuses' => 'SpecimenStatus',
    );

    private static $summary_fields = array(
        'Species.Title',
        'LastRecordedTotalHeight',
        'LastRecordedCrownHeight',
        'LastRecordedDiameter',
        'Comment',
        'GeoLocation'
    );


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('SurveyID');
        $fields->replaceField('GeoLocation', GeoLocationField::create('GeoLocation'));
        $fields->removeByName('SpeciesID');
        $fields->insertBefore('GeoLocation', AutoCompleteField::create('SpeciesID', 'Species', '', null, null, 'TreeSpecies', array('ScientificName', 'CommonName')));

        $conf = GridFieldConfig_RelationEditor::create();
        $fields->insertAfter('Comment', new GridField('SpecimenStatus', 'SpecimenStatus', $this->Statuses(), $conf));
        $fields->removeByName('Statuses');

        return $fields;
    }

    public function getFrontEndFields($params = NULL)
    {
        $fields = parent::getFrontEndFields($params);

        $fields->replaceField("SpeciesID", DropdownField::create('SpeciesID', 'Species')->setSource(TreeSpecies::get()->map('ID', 'Title')));

        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldButtonRow('before'));
        $config->addComponent(new GridFieldEditableColumns());
        $config->addComponent(new GridFieldDeleteAction());
        $config->addComponent(new GridFieldExternalLink());

        $gridField = GridField::create('Statuses', 'Statuses', $this->Statuses(), $config);
        $fields->add($gridField);

        return $fields;
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
        return $this->Survey();
    }

    public function LastRecordedTotalHeight()
    {
        if ($this->Statuses() && $this->Statuses()->Count() > 0) {
            $lastStatus = $this->Statuses()->sort('Date', 'DESC')->first();
            return $lastStatus->TotalHeight;
        }
    }

    public function LastRecordedCrownHeight()
    {
        if ($this->Statuses() && $this->Statuses()->Count() > 0) {

            $lastStatus = $this->Statuses()->sort('Date', 'DESC')->first();
            return $lastStatus->CrownHeight;
        }
    }

    public function LastRecordedDiameter()
    {
        if ($this->Statuses() && $this->Statuses()->Count() > 0) {
            $lastStatus = $this->Statuses()->sort('Date', 'DESC')->first();
            return $lastStatus->Diameter;

        }
    }

}
