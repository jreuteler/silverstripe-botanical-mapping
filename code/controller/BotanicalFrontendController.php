<?php


class BotanicalFrontendController extends Page_Controller
{

    private static $allowed_actions = array(
        'view',
        'create',
        'edit',
        'delete',
        'save',
        'showlist',
        'CreateForm',
        'EditForm',
    );

    private static $url_handlers = array(
        '$DataObjectName/$Action/$ID/$OtherID' => 'handleAction',
    );

    protected $dataObject;
    protected $f; // JSONDataFormatter

    public function init()
    {
        $this->f = new JSONDataFormatter();
        parent::init();
    }


    public function handleAction($request, $action)
    {
        $ID = (int)$request->param('ID');
        $dataObjectName = $request->param('DataObjectName');

        // TODO: add/check permissions

        try {
            if (!$ID || $action == 'create') {
                $dataObject = $dataObjectName::create();
            } else {
                $dataObject = $dataObjectName::get()->byID($ID);
            }

            $this->dataObject = $dataObject;

            return $this->$action($request);
        } catch (Exception $e) {

        }
    }

    public function view(SS_HTTPRequest $request)
    {
        if ($request->isAjax()) {
            $data = array('JSON' => $this->f->convertDataObject($this->dataObject));
            return $this->customise($data)
                ->renderWith('AjaxData');
        }

        $data = array(
            'ClassName' => $this->dataObject->RecordClassName,
            'Entry' => $this->dataObject,
        );

        return $this->customise($data)->renderWith(array($this->dataObject->RecordClassName, 'Page'));
    }

    public function edit(SS_HTTPRequest $request)
    {
        if ($this->request->isAjax()) {
            return $this->Form()->forAjaxTemplate();
        } else {
            return $this->renderWith(array(
                $this->dataObject->RecordClassName . '_edit', 'FrontendRecord_edit', 'Page'
            ));
        }
    }

    public function delete(SS_HTTPRequest $request)
    {
        $delete = $this->dataObject->delete();

        if ($request->isAjax()) {
            return $this->customise($this->f->convertDataObject($delete))
                ->renderWith('AjaxData');
        }

        return $delete;
    }

    public function save($data, Form $form, SS_HTTPRequest $request)
    {
    }

    public function showlist(SS_HTTPRequest $request)
    {
    }

    public function Form()
    {
        return $this->EditForm();
    }

    public function EditForm()
    {
        $object = $this->dataObject;
        $fields = $object->getFrontEndFields();

        $actions = new FieldList(
            $button = new FormAction('save', _t('Dashboards.SAVE', 'Save'))
        );
        $button->addExtraClass('button');

        $validator = new RequiredFields('Title');

        $form = new Form($this, 'EditForm', $fields, $actions, $validator);


        if ($this->dataObject->isInDb()) {
            $form->Fields()->push(new HiddenField('ID', '', $this->dataObject->ID));
        }

        $form->loadDataFrom($this->dataObject);

        return $form;
    }


    public function Link($action = NULL)
    {
        $request = Controller::curr()->getRequest();
        $dataObjectName = $request->param('DataObjectName');

        return Controller::join_links(Director::baseURL(), 'botanical-frontend', $dataObjectName);
    }


}