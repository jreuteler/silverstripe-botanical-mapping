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

    public function save(SS_HTTPRequest $request)
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
    }


}