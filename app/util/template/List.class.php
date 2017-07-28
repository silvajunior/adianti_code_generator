<?php

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

use Adianti\Database\TFilter1;
use Adianti\Widget\Datagrid\TDatagridTables;

class **LIST_CLASS_NAME** extends TPage
{

    private $form;
    private $datagrid;

    public function __construct()
    {

        parent::__construct();

        $this->form = new TForm('list_**TABLE_NAME**');

        $panel = new TPanelForm(900, 100);

        $this->form->add($panel);

        $titulo = new TLabel('Listagens de **LIST_LABEL**');
        $titulo->setFontFace('Arial');
        $titulo->setFontColor('red');
        $titulo->setFontSize(12);

        $panel->put($titulo, $panel->getColuna(), $panel->getLinha());

        $opcao = new TCombo('opcao');

        $items = array();
        $items['**SEARCH_ITEM_VALUE**'] = '**SEARCH_ITEM_LABEL**';

        $opcao->addItems($items);

        $opcao->setValue('**SEARCH_ITEM_VALUE**');
        $opcao->setSize(30);

        $nome = new TEntry('nome');
        $nome->setSize(30);

        $find_button = new TButton('busca');
        $new_button = new TButton('novo');

        $find_button->setAction(new TAction(array($this, 'onSearch')), 'Buscar');

        $new_button->setAction(new TAction(array('**FORM_NAME**', 'onEdit')), 'Novo');

        $panel->putCampo(null, 'Selecione o campo:', 0, 0);
        $panel->put($opcao, $panel->getColuna(), $panel->getLinha());
        $panel->put(new TLabel('Buscar:'), $panel->getColuna(), $panel->getLinha());
        $panel->put($nome, $panel->getColuna(), $panel->getLinha());
        $panel->put($find_button, $panel->getColuna(), $panel->getLinha());
        $panel->put($new_button, $panel->getColuna(), $panel->getLinha());

        $this->form->setFields(array($opcao, $nome, $find_button, $new_button));

        $this->datagrid = new TDatagridTables;

**DATA_GRID_ITEMS_LINE**
        $actionEdit = new TDataGridAction(array('**FORM_NAME**', 'onEdit'));
        $actionEdit->setLabel('Editar');
        $actionEdit->setImage('ico_edit.png');
        $actionEdit->setField('id');

        $actionDelete = new TDataGridAction(array($this, 'onDelete'));
        $actionDelete->setLabel('Deletar');
        $actionDelete->setImage('ico_delete.png');
        $actionDelete->setField('id');

        $this->datagrid->addAction($actionEdit);
        $this->datagrid->addAction($actionDelete);

        $this->datagrid->createModel();

        $panel = new TPanelForm(700, 500);
        $panel->put($this->form, 0, 0);
        $panel->put($this->datagrid, 150, 115);

        parent::add($panel);

    }

    function onReload()
    {

        TTransaction::open('**DB_CONFIG_FILE**');

        $repository = new TRepository('**RECORD_NAME**');

        $criteria = new TCriteria;

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

    function onSearch()
    {

        $data = $this->form->getData();

        $campo = $data->opcao;
        $dados = $data->nome;

        TTransaction::open('**DB_CONFIG_FILE**');

        $repository = new TRepository('**RECORD_NAME**');

        $criteria = new TCriteria;

        if ($dados) {

            if (is_numeric($dados)) {

                $criteria->add(new TFilter($campo, 'like', '%' . $dados . '%'));

            } else {

                $criteria->add(new TFilter1('special_like(' . $campo . ",'" . $dados . "')"));

            }

        }

        $objects = $repository->load($criteria);

        $this->datagrid->clear();
        if ($objects) {

            foreach ($objects as $object) {

                $this->datagrid->addItem($object);

            }

        }

        TTransaction::close();

        $this->loaded = true;

    }

    function onDelete($param)
    {

        $key = $param['key'];

        $action = new TAction(array($this, 'Delete'));

        $action->setParameter('key', $key);

        new TQuestion('Deseja realmente excluir o registro ?', $action);

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

    function show()
    {

        parent::show();

        $this->onReload();

    }

}

?>