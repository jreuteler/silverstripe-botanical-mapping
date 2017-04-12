<?php


class BotanicalSuggestController extends Page_Controller
{
    private static $allowed_actions = array(
        'suggest',
        'suggestData'
    );

    private static $url_handlers = array(
        '$DataObjectName/$FieldList' => 'suggestData',
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

        $fieldNames = explode(',', $fieldList);
        $data = array();

        // TODO: verify field names
        if (BotanicalMappingHelper::isValidDataObjectName($dataObjectName, true)) {

            $filter = $this->createFilterArray($fieldNames, $request->getVar('term'), 'PartialMatch');
            $results = $dataObjectName::get()->filterAny($filter);

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
