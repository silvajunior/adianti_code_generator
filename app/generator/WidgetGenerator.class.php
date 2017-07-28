<?php

class WidgetGenerator
{

    private static $i;

    public static function fieldWidget( $columnName, $dataType, $length, $i )
    {

        WidgetGenerator::$i = $i;

        if ($columnName == 'id' || $columnName == 'dataalteracao' || $columnName == 'usuarioalteracao') {

            $fieldType = Adianti::HIDDEN;

        } else if ($dataType == 'character varying') {

            if ($length < 150) {

                $fieldType = Adianti::ENTRY;

            } else {

                $fieldType = Adianti::TEXT;

            }

        } else if ($dataType == 'timestamp without time zone') {

            $fieldType = Adianti::DATE;

        } else if ($dataType == 'text') {

            $fieldType = Adianti::TEXT;

        } else if ( strpos( $columnName, "_id" ) ) {

            $fieldType = Adianti::COMBO;

        } else {

            $fieldType = Adianti::ENTRY;

        }

        return WidgetGenerator::createWidgetsCombo($fieldType);

    }

    private static function createWidgetsCombo( $selected )
    {

        $combo = '<select name="item_widget_' . WidgetGenerator::$i . '">';

        foreach (Adianti::WIDGETS_ARRAY as $widget) {

            if($widget == $selected) {

                $combo .= "<option selected=\"selected\" value=\"" . $widget . "\">" . $widget ."</option>";

            }else {

                $combo .= "<option value=\"" . $widget . "\">" . $widget ."</option>";

            }

        }

        $combo .= '</select>';

        return $combo;

    }

}