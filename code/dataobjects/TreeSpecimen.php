<?php

class TreeSpecimen extends DataObject
{
    public static $db = array(
        'Number' => 'Int',
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
        'Number',
        'SpeciesTitle',
        'LastRecordedTotalHeight',
        'LastRecordedCrownHeight',
        'LastRecordedDiameter',
        'GeoLocation'
    );


    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels(true);
        $labels['Number'] = _t('TreeSpecimen.Number', '#');
        $labels['SpeciesTitle'] = _t('TreeSpecimen.SpeciesTitle', 'Species');
        $labels['Comment'] = _t('TreeSpecimen.Comment', 'Comment');
        $labels['SpeciesID'] = _t('BotanicalMapping.Species', 'Species');
        $labels['LastRecordedTotalHeight'] = _t('TreeSpecimen.LastRecordedTotalHeight', 'LastRecordedTotalHeight');
        $labels['LastRecordedCrownHeight'] = _t('TreeSpecimen.LastRecordedCrownHeight', 'LastRecordedCrownHeight');
        $labels['LastRecordedDiameter'] = _t('TreeSpecimen.LastRecordedDiameter', 'LastRecordedDiameter');
        $labels['GeoLocation'] = _t('TreeSpecimen.GeoLocation', 'GeoLocation');
        return $labels;
    }


    private static $indexes = array(//'uniqueConstraint' => 'unique("Number", "SurveyID")'
    );

    static $defaults = array('Number' => 1);

    private static $default_sort = 'ID DESC';

    public static $allow_frontend_access = true;

    public function getCMSFields()
    {
        $number = TextField::create('Number', _t('TreeSpecimen.Number', '#'));
        $species = AutoCompleteField::create('SpeciesID', _t('BotanicalMapping.Species', 'Species'), '', null, null, 'TreeSpecies', array('ScientificName', 'CommonName'));
        $geolocation = GeoLocationField::create('GeoLocation', _t('TreeSpecimen.GeoLocation', 'Geo location'));
        $comment = TextareaField::create('Comment', _t('TreeSpecimen.Comment', 'Comment'));

        $conf = GridFieldConfig_RelationEditor::create();
        $specimenStatus = new GridField('SpecimenStatus', _t('TreeSpecimen.SpecimenStatus', 'SpecimenStatus'), $this->Statuses(), $conf);


        $fields = FieldList::create(
            $number,
            $species,
            $geolocation,
            $comment,
            $specimenStatus
        );

        return $fields;
        
    }

    public function getFrontEndFields($params = NULL)
    {
        $fields = parent::getFrontEndFields($params);
        $fields->removeByName('SurveyID');
        $fields->removeByName('SpeciesID');

        $autocomplete = AutoCompleteField::create('SpeciesID', _t('BotanicalMapping.Species', 'Species'), '', null, null, 'TreeSpecies', array('ScientificName', 'CommonName'));
        $autocomplete->setSuggestURL(BotanicalSuggestController::$controllerPath . '/TreeSpecies/ScientificName,CommonName');
        $fields->insertBefore('GeoLocation', $autocomplete);


        $fields->insertBefore('Comment', LiteralField::create('Show on map', '<a class="btn float-none" href="' . BotanicalMappingController::$controllerPath . '/' . $this->ClassName . '/map/' . $this->ID . '">' . _t('TreeSpecimen.ShowOnMap', 'Show this specimen on map') . '</a>'));
        $fields->add(LabelField::create('Statuses')->addExtraClass('left'));
        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldButtonRow('before'));
        $gridField = new GridField(
            'Statuses',
            'Statuses',
            $this->Statuses(),
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

    public function SpeciesTitle()
    {
        if ($this->Species()) {
            return $this->Species()->getTitle();
        }

        return ' - ';
    }

    public function getTitle()
    {
        return ($this->Number ? '#' . $this->Number . ' - ' : '') . $this->SpeciesTitle();
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
