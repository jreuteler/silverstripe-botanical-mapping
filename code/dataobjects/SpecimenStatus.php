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
        'TotalHeight',
        'CrownHeight',
        'Diameter',
        'Comment',
    );

    public static $allow_frontend_access = true;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $datefield = DateField::create('Date')
            ->setConfig('showcalendar', true)
            ->setConfig('dateformat', 'dd-MM-yyyy');

        $fields->replaceField('Date', $datefield);
        $fields->removeByName('SpecimenID');



        return $fields;
    }

    public function getFrontEndFields($params = NULL)
    {
        $fields = parent::getFrontEndFields($params);

        $fields->removeByName('SpecimenID');
        $datefield = DateField::create('Date')
            ->setConfig('showcalendar', true)
            ->setConfig('dateformat', 'dd-MM-yyyy');

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
