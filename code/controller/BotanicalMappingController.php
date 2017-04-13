<?php


class BotanicalMappingController extends Page_Controller
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
        '$DataObjectName/$Action/$ID/$ForeignKeyFieldName' => 'handleAction',
    );

    public static $controllerPath = 'botanical-frontend';
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


        // redirect if none or no valid dataobject name given
        if( !BotanicalMappingHelper::isValidDataObjectName($dataObjectName, true) ) {
            return $this->redirect(self::$controllerPath.'/BotanicalMappingProject/showlist');
        }

        // TODO: add/check permissions

        try {
            // TODO: check for wrong/non-existing IDS
            if (!$ID) {
                $dataObject = $dataObjectName::create();
            } else {
                $dataObject = $dataObjectName::get()->byID($ID);
            }

            $this->dataObject = $dataObject;

            return $this->$action($request);
        } catch (Exception $e) {
            DBLogger::log($e, __METHOD__, SS_LOG_ERROR);
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
                $this->dataObject->RecordClassName . '_edit', 'BreadCrumbEdit', 'Page'
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
        if (isset($data['ID'])) {
            $this->dataObject->ID = $data['ID'];
        }

        $form->saveInto($this->dataObject);
        $this->dataObject->write();

        if ($this->request->isAjax()) {

            // TODO: Test
            $this->response->addHeader('Content-type', 'application/json');
            return json_encode(array(
                'message' => 'success',
                'class' => $this->dataObject->RecordClassName,
                'id' => $this->dataObject->ID,
                'form' => $this->EditForm()->forTemplate()->raw()
            ));
        } else {
            $this->redirect($this->dataObject->EditLink());
        }


    }

    public function showlist(SS_HTTPRequest $request)
    {
        $ID = $request->param('ID');
        $dataObjectName = $request->param('DataObjectName');
        $foreignKeyFieldName = $request->param('ForeignKeyFieldName');

        $list = ArrayList::create();
        // when ParentField is set the given ID is assumed as value for that field
        if ($foreignKeyFieldName) {
            $list = $dataObjectName::get()->filter(array($foreignKeyFieldName => $ID));
        } else if (!$ID) {
            $list = $dataObjectName::get();
        } else {
            $list->push($this->dataObject);
        }

        if ($request->isAjax()) {
            $data = array('JSON' => $this->f->convertDataObjectSet($list));
            return $this->customise($data)
                ->renderWith('AjaxData');
        }

        $data = array(
            'ControllerPath' => self::$controllerPath . '/' . $this->dataObject->RecordClassName . '/edit',
            'Records' => $list,
        );

        return $this->customise($data)->renderWith(array($this->dataObject->RecordClassName . 'List', 'Page'));

    }

    public function Breadcrumb()
    {
        $breadcrumpList = ArrayList::create();
        $inRoot = false;
        $currentDataObject = $this->dataObject;

        while (!$inRoot) {

            $breadcrump = array(
                'Title' => $currentDataObject->Title,
                'Link' => $currentDataObject->EditLink(),
                'Class' => $currentDataObject->RecordClassName
            );

            $breadcrumpList->push(new ArrayData($breadcrump));

            $currentDataObject = $currentDataObject->getBreadcrumbParent();
            if (!$currentDataObject) {
                $inRoot = true;
            }
        }

        return $breadcrumpList;
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


    public function Link($action = null)
    {
        $request = Controller::curr()->getRequest();
        $dataObjectName = $request->param('DataObjectName');

        if ($action) {
            return Controller::join_links(Director::baseURL(), 'botanical-frontend', $dataObjectName, $action);
        } else {
            return Controller::join_links(Director::baseURL(), 'botanical-frontend', $dataObjectName);
        }
    }


}