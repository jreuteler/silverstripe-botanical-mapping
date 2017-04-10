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
        'Title',
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

}
