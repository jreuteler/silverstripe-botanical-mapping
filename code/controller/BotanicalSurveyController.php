<?php

class BotanicalSurveyController extends Page_Controller
{
    private static $allowed_actions = array(
        'CreateProjectForm'
    );

    public function init()
    {
        parent::init();

        //Requirements::css();
        //Requirements::javascript();
    }

    public function index(SS_HTTPRequest $request)
    {
        //        $action = $request->allParams()['Action'];
        return $this->customise(array())->renderWith(array('BotanicalMappingProject', 'Page'));
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