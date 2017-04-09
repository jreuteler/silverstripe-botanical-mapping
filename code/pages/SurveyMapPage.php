<?php

class SurveyMapPage extends Page
{

}


class SurveyMapPage_Controller extends Page_Controller
{

    public function init()
    {
        Requirements::css(BOTANICALMAPPING_DIR . '/vendor/openlayers/v4.0.1-dist/ol.css');
        Requirements::javascript(BOTANICALMAPPING_DIR . '/vendor/openlayers/v4.0.1-dist/ol.js');
        Requirements::javascript(BOTANICALMAPPING_DIR . '/code/javascript/geolocation.js');

        parent::init();
    }


    public function index(SS_HTTPRequest $request)
    {

        $surveys = BotanicalSurvey::get()->filter(array('ID' => $request->getVar('Surveys')));

        $data = array(
            'Surveys' => $surveys,
        );

        return $data;
    }


    public function SurveySearchForm()
    {
        $surveys = BotanicalSurvey::get();

        $form = Form::create(
            $this,
            'SurveySearchForm',
            FieldList::create(

                CheckboxSetField::create('Surveys')
                    ->setSource($surveys->map('ID', 'Title'))
                    ->addExtraClass('form-control')
            ),
            FieldList::create(
                FormAction::create('doSurveySearch', 'Search')
                    ->addExtraClass('btn-lg btn-fullcolor')
            )
        );

        $form->setFormMethod('GET')
            ->setFormAction($this->Link())
            ->disableSecurityToken()
            ->loadDataFrom($this->request->getVars());

        return $form;
    }
}