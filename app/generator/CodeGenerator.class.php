<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('RecordGenerator.class.php');
require_once('DetalheGenerator.class.php');
require_once('ListFormGenerator.class.php');
require_once('../util/Util.class.php');

class CodeGenerator
{

    private $tableName;

    private $recordName;

    private $type;

    private $listName;
    private $formName;
    private $detalheName;

    function __construct()
    {

        $itemsPost = Util::getItemsFromPOST($_POST);

        $this->tableName = $_POST['tableName'];
        $this->type = $_POST['type'];

        if (mkdir('../files/' . $this->tableName, 0777, true)) {

            Util::successMsg('> Folder ' . $this->tableName . ' created with success.');

            if (isset($_POST['record'])) {

                $this->recordName = $_POST['recordName'];

                $recordGenerator = new RecordGenerator($this->tableName, $this->recordName);

                if ($recordGenerator->generate()) {

                    Util::successMsg('> ' . $this->recordName . ' created with success.');

                } else {

                    Util::errorMsg('> Error creating ' . $this->recordName . '.');

                }

            }

            if ($this->type == 'list_form') {

                $this->listName = $_POST['listName'];
                $this->formName = $_POST['formName'];

                $listFormGenerator = new ListFormGenerator($this->listName, $this->formName, $this->recordName, $this->tableName, $itemsPost);

                $listFormGenerator->generate();

            } else if ($this->type == 'detalhe') {

                $this->detalheName = $_POST['detalheName'];

                $detalheGenerator = new DetalheGenerator($this->detalheName, $this->recordName, $this->tableName, $itemsPost);

                if ($detalheGenerator->generate()) {

                    Util::successMsg('> ' . $this->detalheName . ' created with success.');

                } else {

                    Util::errorMsg('> Error creating ' . $this->detalheName . '.');

                }

            }

        } else {

            Util::errorMsg('> Error creating folder ' . $this->tableName . '.');

        }

        echo '<form action="../../index.html"><input type="submit" value="Back to index"></form>';

    }

}

new CodeGenerator();

?>