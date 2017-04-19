<?php


class BotanicalSuggestController extends Page_Controller
{
    private static $allowed_actions = array(
        'suggest',
        'suggestData'
    );

    private static $url_handlers = array(
        '$DataObjectName/$FieldList/$ParentField/$ParentID' => 'suggestData',
    );

    public static $controllerPath = 'botanical-suggest';
    protected $f; // JSONDataFormatter

    public function init()
    {
        $this->f = new JSONDataFormatter();
        parent::init();
    }

    public function suggestData($request)
    {
        $dataObjectName = $request->param('DataObjectName');
        $fieldList = $request->param('FieldList');
        $parentField = $request->param('ParentField');
        $parentID = $request->param('ParentID');

        $fieldList = str_replace('-', '.', $fieldList); // convert - to .
        $fieldNames = explode(',', $fieldList);
        $data = array();

        // TODO: verify field names
        if (BotanicalMappingHelper::isValidDataObjectName($dataObjectName, true)) {

            $filter = $this->createFilterArray($fieldNames, $request->getVar('term'), 'PartialMatch');
            $results = $dataObjectName::get()->filterAny($filter);

            if($parentField && $parentID) {
                $results = $results->filter(array($parentField => $parentID));
            }

            foreach ($results as $result) {

                $data[] = array(
                    'label' => $result->getTitle(),
                    'value' => $result->ID,
                    'stored' => $result->ID,
                );

            }

        }

        return json_encode(array_values($data)); //$this->f->convertDataObjectSet($data);

    }

    public function createFilterArray($fieldNameList, $value, $stringModifier = null)
    {
        $filterArray = array();
        foreach ($fieldNameList as $key) {
            $filterArray[$key . ($stringModifier ? ':' . $stringModifier : '')] = $value;
        }

        return $filterArray;
    }


}
