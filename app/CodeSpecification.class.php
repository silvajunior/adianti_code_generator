<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('database/TableReader.class.php');
require_once('adianti/Adianti.class.php');
require_once('generator/LabelGenerator.class.php');
require_once('generator/WidgetGenerator.class.php');
require_once('generator/IsNullableGenerator.class.php');

class CodeSpecification
{

    function __construct()
    {

        if (isset($_GET['tableName'])) {

            $data = TableReader::getInfo($_GET['tableName']);

            if ($data) {

                $className = LabelGenerator::className($_GET['tableName']);

                echo '<h3>FORM INFO</h3>
                  <form action="generator/CodeGenerator.class.php" method="POST">    
                    
                    <input type="hidden" name="tableName" value="' . $_GET['tableName'] . '">
                    
                    <input type="checkbox" name="record" >
                    Record <br>
                    <input type="text" name="recordName" value="' . $className . 'Record">
                    
                    <br><br>

                    <input type="radio" name="type" value="list_form">
                    List/Form <br/>
                    
                    <input type="text" name="listName" value="' . $className . 'List"> /
                    <input type="text" name="formName" value="' . $className . 'Form">
                    
                    <br><br>
                    
                    <input type="radio" name="type" value="detalhe">
                    Detalhe <br/>
                    
                    <input type="text" name="detalheName" value="' . $className . 'Detalhe">
                    
                    <br><br>
                
                    <input type="submit" value="Create">
                    
                    <h3>TABLE INFO</h3>' .

                    $this->createList($data)

                    . '</form>';

                $this->createList($data);

            } else {

                echo 'Table does not exists.';
                echo '<form action="../index.html"><input type="submit" value="Back to index"></form>';

            }

        }

    }

    private function createList($data)
    {

        $table = '<table style="border-spacing: 10px; width: 100%;" >';
        $table .= '<tr align="left">
                      <th>Grid?</th>
                      <th>Form?</th>
                      <th>Column</th>
                      <th>Label</th>
                      <th>Adianti Widget</th> 
                      <th>Is Nullable?</th>
                      <th>Database Info</th>
                    </tr>';

        $i = 0;

        foreach ($data as $item) {

            $table .= $this->listItem($item, $i);

            $i++;

        }

        $table .= '</table>';

        return $table;

    }

    private function listItem($item, $i)
    {

        $databaseInfo = '<span style="color:blue;">' . $item['column_name'] . '</span>';
        $databaseInfo .= '<span style="color:green;"> ' . $item['data_type'];
        $databaseInfo .= $item['length'] > 0 ? '(' . $item['length'] . ')' : '';
        $databaseInfo .= '</span>';
        $databaseInfo .= $item['is_nullable'] == 'NO' ? ' <b>not null</b>' : '';

        return '<tr>
                 <td><input type="checkbox" name="item_grid_' . $i . '"></td>
                 <td><input type="checkbox" name="item_form_' . $i . '" checked="true"></td>
                 <td><input type="text" style="border: 0px;" name="item_column_' . $i . '" value="' . $item['column_name'] . '" readonly></td>
                 <td>' . LabelGenerator::label($item['column_name'], $i) . '</td>
                 <td>' . WidgetGenerator::fieldWidget($item['column_name'], $item['data_type'], $item['length'], $i) . '</td> 
                 <td>' . IsNullableGenerator::getInfo($item['is_nullable'], $i) . '</td>
                 <td>' . $databaseInfo . '</td>
                 <td><input type="hidden" name="item_length_' . $i . '" value="' . $item['length'] . '"></td>
              </tr>';

    }

}

new CodeSpecification();

?>