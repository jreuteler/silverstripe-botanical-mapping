<?php

class TreeSurveyPage extends Page
{

}


class TreeSurveyPage_Controller extends Page_Controller
{

    private static $allowed_actions = array(
        'GeoForm',
    );

    public function init()
    {
        parent::init();
    }

    public function index(SS_HTTPRequest $request)
    {
        $data = array();
        return $this->customise(array())->renderWith(array('TreeSurveyPage', 'Page'));
    }


    public function GeoForm()
    {

        // http://stackoverflow.com/questions/34999353/silverstripe-3-2-how-to-add-and-update-dataobjects-in-a-frontend-form-dynamical
        /**
         * // Create GeoData Upload field.
         * $upload = GeoDataUploadField::create('Image', 'Image');
         *
         * // Set options to prevent selection of existing or access to the filesystem as per Silverstripe docs.
         * $upload->setCanAttachExisting(false);
         * $upload->setCanPreviewFolder(false);
         **/

        /**
         * $config = GridFieldConfig::create();
         * $config->addComponent(new GridFieldButtonRow('before'));
         * $config->addComponent(new GridFieldEditableColumns());
         * $config->addComponent(new GridFieldAddNewInlineButton());
         * //$gridField = GridField::create('Qualifications', 'Qualifications', Qualification::get()->filter(array('MemberID' => Member::currentUserID()))),$config);
         *
         *
         * $gridField = GridField::create('Qualifications', 'Qualifications',$config);
         **/


        $fields = new FieldList(

            GeoLocationField::create('GPSPosition', 'GPS Location'),
            // $upload,
            AutoCompleteField::create('TreeSpecies', 'Tree species', '', null, null, 'TreeSpecies', array('ScientificName', 'CommonName')),
            NumericField::create('TotalHeight', 'Total height'),
            NumericField::create('CrownHeight', 'Crown height'),
            NumericField::create('Diameter', 'Diameter'),
            TextareaField::create('Comment', 'Comment')

        );


        $actions = new FieldList(
            FormAction::create('myAction', 'save')
        //FormAction::create('myOtherAction','save and next')
        );

        $form = new Form($this, __FUNCTION__, $fields, $actions);
        //$form->loadDataFrom(Member::get()->byID(Member::currentUserID()));
        //

        return $form;
    }


    public function myAction($data, $form)
    {

        /*
        $member = Member::get()->byId(Member::currentUserID());
        $form->saveInto($member);
        $member->write();
        **/
    }


}