<?php

class BotanicalMappingProjectController extends Page_Controller
{
    private static $allowed_actions = array(
        'CreateProjectForm'
    );

    public function init()
    {
        parent::init();
    }

    public function index(SS_HTTPRequest $request)
    {
        $action = $request->allParams()['Action'];

        $data = array(
            'Action' => ucfirst($action)
        );

        return $this->customise($data)->renderWith(array('BotanicalMappingProject', 'Page'));
    }


    public function CreateProjectForm()
    {
        return new CreateProjectForm($this, 'CreateProjectForm');
    }

    public function Link($action = null)
    {
        return Controller::join_links('saveProject', $action);
    }


}