<?php


class BotanicalFrontendController extends Page_Controller
{

    private static $allowed_actions = array(
        'view',
        'edit',
        'delete',
        'save',
        'showlist',
        'CreateForm',
        'EditForm',
    );

    private static $url_handlers = array(
        '$DataObject/$Action/$ID/$OtherID' => 'handleAction',
    );


    public function handleAction($request, $action)
    {
    }

    public function view()
    {
    }

    public function edit()
    {
    }

    public function delete()
    {
    }

    public function save()
    {
    }

    public function showlist()
    {
    }

    public function Form()
    {
        return $this->EditForm();
    }

    public function EditForm()
    {

        // placeholder
        $fields = array();

        $actions = new FieldList(
            $button = new FormAction('save', _t('Dashboards.SAVE', 'Save'))
        );
        $button->addExtraClass('button');

        $validator = new RequiredFields('Title');

        $form = new Form($this, 'EditForm', $fields, $actions, $validator);

        if ($this->record) {
            $form->Fields()->push(new HiddenField('ID', '', $this->record->ID));
            $form->loadDataFrom($this->record);
        }

        return $form;
    }


    public function Link($action = '')
    {
        $record = null;
        $args = func_get_args();
        if (count($args) == 2) {
            $record = $args[1];
        }

        return Controller::join_links(Director::baseURL(), 'botanical-frontend');
        //, 'model', strtolower($record->ClassName), $action, $record->ID);

    }


}