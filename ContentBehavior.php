<?php

namespace Creonit\PropelContentBehavior;

use Propel\Generator\Model\Behavior;
use Propel\Generator\Model\ForeignKey;

class ContentBehavior extends Behavior
{
    protected $parameters = [
        'parameter' => 'content_id',
    ];
    
    protected function addContentColumn($columnName){
        $table = $this->getTable();

        $table->addColumn([
            'name' => $columnName,
            'type' => 'integer'
        ]);

        $fk = new ForeignKey();
        $fk->setForeignTableCommonName('content');
        $fk->setForeignSchemaName($table->getSchema());
        $fk->setDefaultJoin('LEFT JOIN');
        $fk->setOnDelete(ForeignKey::SETNULL);
        $fk->setOnUpdate(ForeignKey::CASCADE);
        $fk->addReference($columnName, 'id');
        $table->addForeignKey($fk);
    }

    public function modifyTable()
    {
        $columns = explode(',', $this->getParameter('parameter'));
        foreach ($columns as $column){
            $this->addContentColumn(trim($column));
        }
    }

    public function objectMethods($builder)
    {
        return $this->renderTemplate('objectMethods', ['table' => $this->getTable(), 'columns' => explode(',', $this->getParameter('parameter'))]);
    }
}