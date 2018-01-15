<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require_once('RecordGenerator.class.php');
require_once('DetalheGenerator.class.php');
require_once('ListFormGenerator.class.php');
require_once('../util/Util.class.php');


include("../../content.php");

headerContent("../../");

class CodeGenerator
{

    private $tableName;

    private $recordName;

    private $type;

    private $listName;
    private $listTitle;

    private $formName;
    private $formTitle;

    private $detalheName;
    private $detalheTitle;
    private $detalheFather;

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
                $this->listTitle = $_POST['listTitle'];

                $this->formName = $_POST['formName'];
                $this->formTitle = $_POST['formTitle'];

                $listFormGenerator = new ListFormGenerator($this->listName, $this->listTitle, $this->formName, $this->formTitle, $this->recordName, $this->tableName, $itemsPost);

                $listFormGenerator->generate();

            } else if ($this->type == 'detalhe') {

                $this->detalheName = $_POST['detalheName'];
                $this->detalheTitle = $_POST['detalheTitle'];
                $this->detalheFather = $_POST['detalheFather'];

                $detalheGenerator = new DetalheGenerator($this->detalheName, $this->detalheTitle, $this->recordName, $this->tableName, $this->detalheFather, $itemsPost);

                if ($detalheGenerator->generate()) {

                    Util::successMsg('> ' . $this->detalheName . ' created with success.');

                } else {

                    Util::errorMsg('> Error creating ' . $this->detalheName . '.');

                }

            }

        } else {

            Util::errorMsg('> Error creating folder ' . $this->tableName . '.');

        }

        echo '<form action="../../index.php"><input type="submit" value="Back to index"></form>';

    }

}

new CodeGenerator();


footerContent("../../");
