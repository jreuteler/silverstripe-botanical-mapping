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

    public function getCMSFields()
    {
        $conf = GridFieldConfig_RelationEditor::create();
        $fields = FieldList::create(
            TextField::create('Title', 'Project'),
            new GridField('Surveys', 'Surveys', $this->Surveys(), $conf)
        );

        return $fields;
    }

    public function getFrontEndFields($params = NULL)
    {

        $fields = parent::getFrontEndFields($params);

        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldButtonRow('before'));
        $config->addComponent(new GridFieldEditableColumns());

        $config->addComponent(new GridFieldAddNewInlineButton());
        $config->addComponent(new GridFieldDeleteAction());
        $config->addComponent(new GridFieldExternalLink());

        $gridField = GridField::create('Surveys', 'Surveys', $this->Surveys(), $config);
        $fields->add($gridField);

        return $fields;
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
        return false;
    }

}
