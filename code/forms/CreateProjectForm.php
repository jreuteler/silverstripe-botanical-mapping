<?php

class CreateProjectForm extends Form
{
    public function __construct($controller, $name)
    {
        $sessionData = Session::get('RegisterFormData');

        $fields = new FieldList(
            TextField::create('Name')->setValue(@$sessionData['Name']),
            TextareaField::create('Comment')
        );

        $actions = new FieldList(
            new FormAction('doSaveProject', 'saveProject')
        );

        $validator = new RequiredFields(
            'Name',
            'Email'
        );

        parent::__construct($controller, $name, $fields, $actions, $validator);

        $this->disableSecurityToken();

    }

    public function doSaveProject($data, $form)
    {
    }
}