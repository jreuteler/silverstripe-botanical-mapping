<?php

class ImportTreeSpeciesController extends Controller
{

    private static $allowed_actions = array(
        'Form'
    );

    protected $template = 'BlankPage';

    public function Link($action = null)
    {
        return Controller::join_links('importTreeSpecies', $action);
    }

    public function Form()
    {
        $form = new Form(
            $this,
            'Form',
            new FieldList(
                new FileField('CsvFile', false)
            ),
            new FieldList(
                new FormAction('doUpload', 'Upload')
            ),
            new RequiredFields()
        );
        return $form;
    }

    public function doUpload($data, $form)
    {
        $loader = new CsvBulkLoader('TreeSpecies');
        $results = $loader->load($_FILES['CsvFile']['tmp_name']);
        $messages = array();

        if ($results->CreatedCount()) {
            $messages[] = sprintf('Imported %d items', $results->CreatedCount());
        }

        if ($results->UpdatedCount()) {
            $messages[] = sprintf('Updated %d items', $results->UpdatedCount());
        }

        if ($results->DeletedCount()) {
            $messages[] = sprintf('Deleted %d items', $results->DeletedCount());
        }

        if (!$messages) {
            $messages[] = 'No changes';
        }

        $form->sessionMessage(implode(', ', $messages), 'good');

        return $this->redirectBack();
    }
}