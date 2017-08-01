<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('database/TableReader.class.php');
require_once('adianti/Adianti.class.php');
require_once('generator/LabelGenerator.class.php');
require_once('generator/WidgetGenerator.class.php');
require_once('generator/IsNullableGenerator.class.php');

include("../content.php");

headerContent("../");

class CodeSpecification
{

    function __construct()
    {

        if (isset($_GET['tableName'])) {

            $data = TableReader::getInfo($_GET['tableName']);

            if ($data) {

                $className = LabelGenerator::className($_GET['tableName']);

                echo '
                <div class="panel-heading"><b>FORM INFO</b></div>
                      <div class="panel-body">
                  <form  class="navbar-form navbar" action="generator/CodeGenerator.class.php" method="POST">    
                    
                    
                    <input type="hidden" name="tableName" value="' . $_GET['tableName'] . '">
                    
                    <div class="form-group">
                    <input type="checkbox" name="record" >
                        <label for="recordName">Record:</label>
                        <br>
                        <input id="recordName" type="text" name="recordName" class="form-control" placeholder="tb_usuario"   aria-describedby="basic-addon2">
                    </div>
                     <br>
                    <div class="form-group">
                        <input type="radio" name="type" class="form-control" value="list_form">
                        <label for="type">List/Form:</label>
                        <br>
                        <input type="text" name="listName" class="form-control" value="' . $className . 'List"> /
                        <input type="text" name="formName" class="form-control" value="' . $className . 'Form">
                    </div>
                    <br>
                    <div class="form-group">
                        <input type="radio" name="type" value="detalhe">
                        <label for="type">Detalhe:</label>
                        <br>
                        <input type="text" name="detalheName" class="form-control" value="' . $className . 'Detalhe">
                    </div>
                    <br>
                    <br>
                     <button type="submit" class="btn btn-success">Create</button>
                    <br>

                    <h3>TABLE INFO</h3>' .

                    $this->createList($data)

                    . '</form>
                    </div>
                </div>';

                $this->createList($data);

            } else {

                echo 'Table does not exists.';
                echo '
                <div class="panel-heading"><b>FORM INFO</b></div>
                      <div class="panel-body">
                         <form  class="navbar-form navbar" action="../index.php">
                            <button type="submit" class="btn btn-info">Back to index</button>
                         </form>
                       </div>
                 </div>';

            }

        }

    }

    private function createList($data)
    {

        $table = '<div class="table-responsive ">          
                    <table id="example" class="table table-hover" cellspacing="0" width="100%">';
        $table .= '<thead>
                                <tr align="left">
                                    
                                  <th>Grid?</th>
                                  <th>Form?  All<input type="checkbox" class="check" id="checkAll" /></th>
                                  <th>Column</th>
                                  <th>Label</th>
                                  <th>Adianti Widget</th> 
                                  <th>Is Nullable?</th>
                                  <th>Database Info</th>
                                </tr>
                              </thead>';
        $i = 0;

        foreach ($data as $item) {

            $table .= $this->listItem($item, $i);

            $i++;

        }

        $table .= '</table></div>';

        return $table;

    }

    private function listItem($item, $i)
    {
        $databaseInfo = '<span style="color:blue;">' . $item['column_name'] . '</span>';
        $databaseInfo .= '<span style="color:green;"> ' . $item['data_type'];
        $databaseInfo .= $item['length'] > 0 ? '(' . $item['length'] . ')' : '';
        $databaseInfo .= '</span>';
        $databaseInfo .= $item['is_nullable'] == 'NO' ? ' <b>not null</b>' : '';

        return '<tbody>
                    <tr>
                     <td><input type="checkbox" name="item_grid_' . $i . '"></td>
                     <td><input type="checkbox" class="check" name="item_form_' . $i . '" checked="true"></td>
                     <td><input type="text" style="border: 0px;" name="item_column_' . $i . '" value="' . $item['column_name'] . '" readonly></td>
                     <td>' . LabelGenerator::label($item['column_name'], $i) . '</td>
                     <td>' . WidgetGenerator::fieldWidget($item['column_name'], $item['data_type'], $item['length'], $i) . '</td> 
                     <td>' . IsNullableGenerator::getInfo($item['is_nullable'], $i) . '</td>
                     <td>' . $databaseInfo . '</td>
                     <td><input type="hidden" name="item_length_' . $i . '" value="' . $item['length'] . '"></td>
                  </tr>
              </tbody>';

    }

}

new CodeSpecification();

footerContent("../");

################# Check All itens form table #################
       echo '<script>
                    $("#checkAll").click(function () {
                        $(".check").prop(\'checked\', $(this).prop(\'checked\'));
                    });
         </script>';
################# Check All itens form table #################