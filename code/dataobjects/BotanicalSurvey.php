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

    public static $allow_frontend_access = true;
    
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

        $fields->add(LiteralField::create('Show on map', '<a href="'.BotanicalMappingController::$controllerPath.'/'.$this->ClassName.'/map/'.$this->ID.'">Show on map</a>'));
        $fields->add(LabelField::create('Specimens')->addExtraClass('left'));
        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldButtonRow('before'));
        $config->addComponent(new GridFieldEditableColumns());
        $config->addComponent(new GridFieldAddNewInlineButton());
        $config->addComponent(new GridFieldCustomEditButton());

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

    public function DeleteLink()
    {
        return BotanicalMappingController::$controllerPath . '/' . $this->RecordClassName . '/delete/' . $this->ID;
    }

    public function ShowListLink()
    {
        return BotanicalMappingController::$controllerPath . '/' . $this->RecordClassName . '/showlist';
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

    // TODO: permissions
    public function canEdit($member = null)
    {
        return true;
    }

    public function canCreate($member = null)
    {
        return true;
    }

    public function canView($member = null)
    {
        return true;
    }

    public function canDelete($member = null)
    {
        return true;
    }
}
