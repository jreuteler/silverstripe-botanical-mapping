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

    public function getFrontEndFields($params = NULL)
    {
        $fields = parent::getFrontEndFields($params);

        $fields->add(LabelField::create('Specimens')->addExtraClass('left'));
        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldButtonRow('before'));
        $config->addComponent(new GridFieldEditableColumns());
        $config->addComponent(new GridFieldAddNewInlineButton());
        $config->addComponent(new GridFieldDeleteAction());
        $config->addComponent(new GridFieldExternalLink());


        $fields->removeByName('SpecimensID');
        $gridField = GridField::create('SpecimensID', 'Specimens', $this->Specimens(), $config);
        $fields->add($gridField);


        return $fields;
    }


    public function getBreadcrumbParent()
    {
        return $this->Project();
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
