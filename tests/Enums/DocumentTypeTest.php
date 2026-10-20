<?php

namespace PlacetoPay\PlacetoPaySdk\Tests\Enums;

use PlacetoPay\PlacetoPaySdk\Entities\Person;
use PlacetoPay\PlacetoPaySdk\Enums\DocumentType;
use PlacetoPay\PlacetoPaySdk\Helpers\DocumentHelper;
use PlacetoPay\PlacetoPaySdk\Tests\BaseTestCase;

class DocumentTypeTest extends BaseTestCase
{
    public function testItValidatesDocuments()
    {
        $this->assertTrue(DocumentType::CC->isValid('1040035000'));
        $this->assertFalse(DocumentType::CC->isValid('0123'));
        $this->assertTrue(DocumentType::CLRUT->isValid('13.342.430-K'));
    }

    public function testItKnowsTheBusinessDocuments()
    {
        $this->assertTrue(DocumentType::NIT->isBusiness());
        $this->assertFalse(DocumentType::CC->isBusiness());
        $this->assertSame(['NIT', 'RUT', 'RUC', 'CLRUT'], DocumentHelper::businessDocument());
        $this->assertTrue((new Person(['documentType' => 'RUC']))->isBusiness());
        $this->assertFalse((new Person(['documentType' => 'NOT_EXISTS']))->isBusiness());
    }
}
