<?php

class DetalheGenerator
{

    private $detalheName;

    private $recordName;
    private $tableName;

    private $dataGridData;
    private $formData;

    private $filePath;

    function __construct($detalheName, $recordName, $tableName, $itemsPost)
    {

        $this->detalheName = $detalheName;

        $this->recordName = $recordName;
        $this->tableName = $tableName;

        $this->filePath = '../files/' . $this->tableName . '/' . $this->detalheName . '.class.php';

        $this->dataGridData = ListFormGenerator::getAllDatagridItems($itemsPost);
        $this->formData = ListFormGenerator::getAllFormItems($itemsPost);

    }

    public function generate()
    {

        $createdSuccess = false;

        if (Util::createFile(Util::DETALHE_TEMPLATE_PATH, $this->filePath)) {

            if ($this->writeDetalhe()) {

                $createdSuccess = true;

            }

        }

        return $createdSuccess;

    }

    private function writeDetalhe()
    {

        $codeWritte = false;

        $code = file_get_contents($this->filePath);

        $code = str_replace( "**DETALHE_CLASS_NAME**", $this->detalheName, $code );

        $code = str_replace( "**TABLE_NAME**", $this->tableName, $code );
        $code = str_replace( "**DB_CONFIG_FILE**", Util::getConfigFileDatabaseName(), $code );
        $code = str_replace( "**RECORD_NAME**", $this->recordName, $code );

        $gridItems = ListGenerator::createDatagridItems($this->dataGridData);

        $code = str_replace("**DATA_GRID_ITEMS_LINE**", $gridItems, $code);

        $code = str_replace("**FORM_FIELD_CREATION_LINE**", FormGenerator::createFormFields($this->formData), $code);
        $code = str_replace("**FIELD_SIZE_LINE**", FormGenerator::createFieldsSizes($this->formData), $code);
        $code = str_replace("**FIELD_VALIDATION_LINE**", FormGenerator::createFieldsValidations($this->formData), $code);
        $code = str_replace("**FORM_FIELD_ADD_LINE**", FormGenerator::createAddFormFields($this->formData), $code);

        if (file_put_contents($this->filePath, $code))
            $codeWritte = true;

        return $codeWritte;

    }

}

?>