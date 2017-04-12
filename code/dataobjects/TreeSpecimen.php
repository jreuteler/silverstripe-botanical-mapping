<?php

class TreeSpecimen extends DataObject
{
    public static $db = array(
        'Key' => 'Varchar',
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
        'Key',
        'SpeciesTitle',
        'LastRecordedTotalHeight',
        'LastRecordedCrownHeight',
        'LastRecordedDiameter',
        'GeoLocation'
    );

    private static $indexes = array(
        'uniqueConstraint' => 'unique("Key", "SurveyID")'
    );

    public static $allow_frontend_access = true;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('SurveyID');
        $fields->replaceField('GeoLocation', GeoLocationField::create('GeoLocation'));
        $fields->removeByName('SpeciesID');

        $autocomplete = AutoCompleteField::create('SpeciesID', 'Species', '', null, null, 'TreeSpecies', array('ScientificName', 'CommonName'));
        $fields->insertBefore('GeoLocation', $autocomplete);

        $conf = GridFieldConfig_RelationEditor::create();
        $fields->insertAfter('Comment', new GridField('SpecimenStatus', 'SpecimenStatus', $this->Statuses(), $conf));
        $fields->removeByName('Statuses');

        return $fields;
    }

    public function getFrontEndFields($params = NULL)
    {
        $fields = parent::getFrontEndFields($params);
        $fields->removeByName('SurveyID');
        $fields->removeByName('SpeciesID');

        $autocomplete = AutoCompleteField::create('SpeciesID', 'Species', '', null, null, 'TreeSpecies', array('ScientificName', 'CommonName'));
        $autocomplete->setSuggestURL(BotanicalSuggestController::$controllerPath . '/TreeSpecies/ScientificName,CommonName');
        $fields->insertBefore('GeoLocation', $autocomplete);

        $fields->add(LabelField::create('Statuses')->addExtraClass('left'));
        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldButtonRow('before'));
        $config->addComponent(new GridFieldEditableColumns());
        $config->addComponent(new GridFieldAddNewInlineButton());
        $config->addComponent(new GridFieldCustomEditButton());

        $gridField = GridField::create('Statuses', 'Statuses', $this->Statuses(), $config);
        $fields->add($gridField);

        return $fields;
    }

    public function SpeciesTitle()
    {
        if ($this->Species()) {
            return $this->Species()->getTitle();
        }

        return ' - ';
    }

    // TODO: add additional information to distinguish between various tree of the same species
    public function getTitle()
    {
        return $this->SpeciesTitle();
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
