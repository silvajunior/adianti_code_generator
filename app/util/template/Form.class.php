<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

class **FORM_CLASS_NAME** extends TPage
{

    private $form;

    public function __construct()
    {

        parent::__construct();

        $this->form = new TQuickForm;
        $this->form->class = 'form_**TABLE_NAME**';

        $this->form->setFormTitle('<font color="red" size="3" face="Arial"><b>Formul&aacute;rio de **FORM_LABEL**</b></font>');
        **MUDAR_O_NOME_DO_LABEL**

**FORM_FIELD_CREATION_LINE**
**FIELD_SIZE_LINE**
**FIELD_VALIDATION_LINE**
        $titulo = new TLabel('<div style="position: floatval; width: 200px;"> <b>* Campos obrigat&oacute;rios</b></div>');
        $titulo->setFontFace('Arial');
        $titulo->setFontColor('red');
        $titulo->setFontSize(10);

**FORM_FIELD_ADD_LINE**
        $this->form->addQuickAction('Salvar', new TAction(array($this, 'onSave')), 'ico_save.png')->class = 'btn btn-info';
        $this->form->addQuickAction('Voltar', new TAction(array('**LIST_NAME**', 'onReload')), 'ico_datagrid.gif');

        parent::add($this->form);

    }

    function onSave()
    {

        try {

            $this->form->validate();

            TTransaction::open('**DB_CONFIG_FILE**');

            $cadastro = $this->form->getData('**RECORD_NAME**');

            $cadastro->usuarioalteracao = $_SESSION['usuario'];
            $cadastro->dataalteracao = date("d/m/Y H:i:s");

            $cadastro->store();

            TTransaction::close();

            new TMessage("info", "Registro salvo com sucesso!");
            TApplication::gotoPage('**LIST_NAME**', 'onReload');

        } catch (Exception $e) {

            new TMessage('error', $e->getMessage());

            TTransaction::rollback();

        }

    }

    function onEdit($param)
    {

        try {

            if (isset($param['key'])) {

                $key = $param['key'];

                TTransaction::open('**DB_CONFIG_FILE**');

                $object = new **RECORD_NAME**($key);

                $this->form->setData($object);

                TTransaction::close();

            }

        } catch (Exception $e) {


            new TMessage('error', '<b>Error</b> ' . $e->getMessage() . "<br/>");

            TTransaction::rollback();

        }

    }

}

?>