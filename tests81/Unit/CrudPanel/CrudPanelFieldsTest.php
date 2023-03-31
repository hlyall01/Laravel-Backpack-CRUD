<?php

namespace hlyall01\Backpack\CRUD\Tests81\Unit\CrudPanel;

use hlyall01\Backpack\CRUD\Tests\Unit\CrudPanel\BaseDBCrudPanelTest;

/**
 * @covers Backpack\CRUD\app\Library\CrudPanel\Traits\Fields
 * @covers Backpack\CRUD\app\Library\CrudPanel\Traits\FieldsProtectedMethods
 */
class CrudPanelFieldsTest extends BaseDBCrudPanelTest
{
    public function testCheckReturnTypesForWhenInferingRelation()
    {
        $this->crudPanel->setModel(\hlyall01\Backpack\CRUD\Tests81\Unit\Models\UserWithReturnTypes::class);
        $this->crudPanel->addField('isAnAttribute');
        $this->crudPanel->addField('isARelation');

        $this->assertEquals(false, $this->crudPanel->fields()['isAnAttribute']['entity']);
        $this->assertEquals('isARelation', $this->crudPanel->fields()['isARelation']['entity']);
    }
}
