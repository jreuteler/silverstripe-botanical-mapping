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
        'Date',
        'TotalHeight',
        'CrownHeight',
        'Diameter',
        'Comment',
    );

    private static $default_sort = 'Date DESC';

    public static $allow_frontend_access = true;


    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels(true);
        $labels['Date'] = _t('SpecimenStatus.Date', 'Date');
        $labels['TotalHeight'] = _t('SpecimenStatus.TotalHeight', 'Total height');
        $labels['CrownHeight'] = _t('SpecimenStatus.CrownHeight', 'Crown height');
        $labels['Diameter'] = _t('SpecimenStatus.Diameter', 'Diameter');
        $labels['Comment'] = _t('SpecimenStatus.Comment', 'Comment');
        $labels['Image'] = _t('SpecimenStatus.Image', 'Image');
        return $labels;
    }


    /**
     * Sets the Date field to the current date.
     */
    public function populateDefaults()
    {
        parent::populateDefaults();

        $this->Date = date('d-M-Y');
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $datefield = DateField::create('Date')
            ->setConfig('showcalendar', true);
        $fields->replaceField('Date', $datefield);
        $fields->removeByName('SpecimenID');

        return $fields;
    }

    public function getFrontEndFields($params = NULL)
    {
        $fields = parent::getFrontEndFields($params);
        $fields->removeByName('Image');
        $fields->removeByName('SpecimenID');
        $datefield = DateField::create('Date')
            ->setConfig('showcalendar', true);

        $fields->replaceField('Date', $datefield);
        $fields->insertBefore('Date', ReadonlyField::create('Species')->setValue($this->Specimen()->getTitle()));
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

    public function getBreadcrumbParent()
    {
        return $this->Specimen();
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
