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

    private static $has_many = array(
        'Specimens' => 'TreeSpecimen',
    );

    private static $summary_fields = array(
        'Title',
    );

    public function getCMSFields()
    {
        $conf = GridFieldConfig_RelationEditor::create();
        $fields = FieldList::create(
            TextField::create('Title', 'Survey'),
            new GridField('Specimens', 'Specimens', $this->Specimens(), $conf)
        );

        return $fields;
    }


    public function Link()
    {
        return $this->ID;
    }


    public function SpecimenCount()
    {
        if ($this->Specimens()) {
            return $this->Specimens()->Count();
        }

        return 0;
    }

    public function getSpecimenPositionsJSON()
    {
        $positions = array();

        foreach ($this->Specimens() as $specimen) {
            $geoLocationArray = explode(',', $specimen->GeoLocation);
            $positions[] = array(
                'ID' => $specimen->ID,
                'Title' => $specimen->getTitle(),
                'GeoLocation' => $specimen->GeoLocation,
                'Latitude' => @$geoLocationArray[0],
                'Longitude' => @$geoLocationArray[1],
                'Accuracy' => @$geoLocationArray[2]
            );
        }

        return json_encode($positions);
    }

}
