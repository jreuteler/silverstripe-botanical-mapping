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
