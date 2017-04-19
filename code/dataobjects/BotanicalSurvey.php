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
        'SpecimenCount'
    );

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels(true);
        $labels['Title'] = _t('BotanicalSurvey.Title', 'Title');
        $labels['SpecimenCount'] = _t('BotanicalSurvey.SpecimenCount', '#');
        $labels['Project'] = _t('BotanicalMapping.Project', 'Project');

        return $labels;
    }


    public static $allow_frontend_access = true;

    public function getCMSFields()
    {
        $title = TextField::create('Title', _t('BotanicalSurvey.Title', 'Title'));

        $conf = GridFieldConfig_RelationEditor::create();
        $specimens = new GridField('Specimens', _t('BotanicalMapping.Specimens', 'Specimens'), $this->Specimens(), $conf);

        $conf = GridFieldConfig_RelationEditor::create();
        $fields = FieldList::create(
            $title,
            $specimens
        );

        return $fields;
    }

    public function getFrontEndFields($params = NULL)
    {
        $fields = parent::getFrontEndFields($params);

        $fields->removeByName('ProjectID');

        $fields->add(LabelField::create(_t('BotanicalMapping.Specimens', 'Specimens'))->addExtraClass('left'));
        $fields->add(LiteralField::create('Show on map', '<a class="btn float-none" href="' . BotanicalMappingController::$controllerPath . '/' . $this->ClassName . '/map/' . $this->ID . '">' . _t('BotanicalSurvey.ShowOnMap', 'Show all specimens of this survey on map') . '</a>'));


        $autocomplete = AutoCompleteField::create('TreeSpecimen|ID', _t('BotanicalMapping.SearchSpecimen', 'Search'), '', null, null, 'TreeSpecimen', array('Number', 'Comment', 'Species'));
        $autocomplete->setSuggestURL(BotanicalSuggestController::$controllerPath . '/TreeSpecimen/Number,Comment,Species-CommonName,Species-ScientificName/SurveyID/'.$this->ID);
        $autocomplete->addExtraClass('autocomplete-edit');
        $autocomplete->setDescription( _t('BotanicalSurvey.SearchSpecimen', 'Search for specimen in this survey and jump to the edit page'));

        $fields->add($autocomplete);
        $fields->add(LabelField::create(_t('BotanicalSurvey.SpecimenList', 'List'))->addExtraClass('left'));

        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldButtonRow('before'));
        $gridField = new GridField(
            'SpecimensID',
            _t('BotanicalMapping.Specimens', 'Specimens'),
            $this->Specimens(),
            GridFieldConfig::create()
                ->addComponent(new GridFieldButtonRow('before'))
                ->addComponent(new GridFieldTitleHeader())
                ->addComponent(new GridFieldEditableColumns())
                ->addComponent(new GridFieldCustomEditButton())
                ->addComponent(new GridFieldAddNewInlineButton())
        );
        $fields->add($gridField);


        return $fields;
    }

    public function SpecimenCount()
    {
        if ($this->Specimens()) {
            return $this->Specimens()->Count();
        }

        return 0;
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
