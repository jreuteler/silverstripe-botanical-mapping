<?php

class BotanicalMappingProject extends DataObject
{

    public static $db = array(
        'Title' => 'Varchar',
    );

    private static $indexes = array(
        'uniqueConstraint' => 'unique("Title")'
    );

    private static $has_many = array(
        'Surveys' => 'BotanicalSurvey',
    );

    private static $summary_fields = array(
        'Title'
    );

    public static $is_breadcrumb_root = true;


    public function getCMSFields()
    {
        $conf = GridFieldConfig_RelationEditor::create();
        $fields = FieldList::create(
            TextField::create('Title', 'Project'),
            new GridField('Surveys', 'Surveys', $this->Surveys(), $conf)
        );

        return $fields;
    }

    public function getBreadcrumbParent()
    {
        return false;
    }


    public function Link()
    {
        return $this->ID;
    }

    public function EditLink()
    {
        return BotanicalMappingController::$controllerPath.'/'.$this->RecordClassName . '/edit/'.$this->ID;
    }

    public function ShowListLink()
    {
        return BotanicalMappingController::$controllerPath.'/'.$this->RecordClassName . '/showlist';
    }

}
