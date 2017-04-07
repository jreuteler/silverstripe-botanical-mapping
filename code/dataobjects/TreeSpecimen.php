<?php

class TreeSpecimen extends DataObject
{
    public static $db = array(
        'GeoLocation' => 'Varchar',
        'TotalHeight' => 'Int',
        'CrownHeight' => 'Int',
        'Diameter' => 'Int',
        'Comment' => 'Text',
    );

    private static $has_one = array(
        'Survey' => 'BotanicalSurvey',
        'Species' => 'TreeSpecies',
        'Image' => 'Image',
    );

    private static $summary_fields = array(
        'Species.Title',
        'TotalHeight',
        'CrownHeight',
        'Diameter',
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
        return $fields;
    }
    

}
