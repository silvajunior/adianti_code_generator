<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

use Adianti\Widget\Datagrid\TDatagridTables;

class **DETALHE_CLASS_NAME** extends TPage
{

    private $form;
    private $datagrid;

    public function __construct()
    {

        parent::__construct();

        $this->form = new TQuickForm('detalhe_**TABLE_NAME**');
        $this->form->class = 'detalhe_**TABLE_NAME**';
        $this->form->setFormTitle('<font color="red" size="3" face="Arial"><b>Detalhe **DETALHE_LABEL**</b></font>');

**FORM_FIELD_CREATION_LINE**
**FIELD_SIZE_LINE**
**FIELD_VALIDATION_LINE**
        $titulo = new TLabel('<div style="position:floatval; width: 200px;"> <b>* Campos obrigat&oacute;rios</b></div>');
        $titulo->setFontFace('Arial');
        $titulo->setFontColor('red');
        $titulo->setFontSize(10);

        $action1 = new TAction(array($this, 'onSave'));
        $action1->setParameter('fk', '' . filter_input(INPUT_GET, 'fk') . '');
        $action1->setParameter('key', '' . filter_input(INPUT_GET, 'key') . '');

**FORM_FIELD_ADD_LINE**
        $this->form->addQuickAction('Salvar', $action1, 'ico_save.png')->class = 'btn btn-info';
        $this->form->addQuickAction('Voltar', new TAction(array(**CLASSE_PAI_NOME**, 'onReload')), 'ico_datagrid.gif');

        $this->datagrid = new TDatagridTables;

**DATA_GRID_ITEMS_LINE**
        $actionEdit = new TDataGridAction(array($this, 'onEdit'));
        $actionEdit->setLabel('Editar');
        $actionEdit->setImage('ico_edit.png');
        $actionEdit->setField('id');
        $actionEdit->setFk('**FK_NAME**');

        $actionDelete = new TDataGridAction(array($this, 'onDelete'));
        $actionDelete->setLabel('Deletar');
        $actionDelete->setImage('ico_delete.png');
        $actionDelete->setField('id');
        $actionDelete->setFk('**FK_NAME**');

        $this->datagrid->addAction($actionEdit);
        $this->datagrid->addAction($actionDelete);

        $this->datagrid->createModel();

        $panel = new TPanelForm(700, 500);
        $panel->put($this->form, 0, 0);
        $panel->put($this->datagrid, 150, 165);

        parent::add($panel);

    }

    function onReload()
    {

        TTransaction::open('**DB_CONFIG_FILE**');

        $repository = new TRepository('**RECORD_NAME**');

        $criteria = new TCriteria;

        $criteria->add(new TFilter('**FK_NAME**', '=', filter_input(INPUT_GET, 'fk')));

        $cadastros = $repository->load($criteria);

        $this->datagrid->clear();

        if ($cadastros) {

            foreach ($cadastros as $cadastro) {

                $this->datagrid->addItem($cadastro);

            }

        }

        TTransaction::close();

        $this->loaded = true;

    }

    function onDelete($param)
    {

        $action = new TAction(array($this, 'Delete'));

        $action->setParameter('key', $param['key']);
        $action->setParameter('fk', $param['fk']);

        new TQuestion('Deseja realmente excluir o registro?', $action);

    }

    function Delete($param)
    {

        $key = $param['key'];

        TTransaction::open('**DB_CONFIG_FILE**');

        $obj = new **RECORD_NAME**($key);

        try {

            $obj->delete();

            new TMessage("info", "Registro deletado com sucesso!");

            TTransaction::close();

        } catch (Exception $e) {

            new TMessage('error', $e->getMessage());

            TTransaction::rollback();

        }

        $this->onReload();

    }


    function onSave()
    {

        try {

            TTransaction::open('**DB_CONFIG_FILE**');

            $cadastro = $this->form->getData('**RECORD_NAME**');

            $cadastro->usuarioalteracao = $_SESSION['usuario'];
            $cadastro->dataalteracao = date("d/m/Y H:i:s");

            $this->form->validate();

            $cadastro->store();

            TTransaction::close();

            $param = array();
            $param ['fk'] = $cadastro->**FK_NAME**;

            new TMessage("info", "Registro salvo com sucesso!");

            TApplication::gotoPage('**DETALHE_CLASS_NAME**', 'onReload', $param);

        } catch (Exception $e) {

            new TMessage('error', $e->getMessage());
            TTransaction::rollback();

            $this->form->setData($cadastro);

        }

    }

    function onEdit($param)
    {

        try {

            if (isset($param['key'])) {

                $key = $param['key'];

                TTransaction::open('**DB_CONFIG_FILE**');

                $obj = new **RECORD_NAME**($key);

                $this->form->setData($obj);

                TTransaction::close();

            }

        } catch (Exception $e) {

            new TMessage('error', '<b>Error</b> ' . $e->getMessage());

            TTransaction::rollback();

        }

    }

    function show()
    {

        parent::show();

        $this->onReload();

    }

}