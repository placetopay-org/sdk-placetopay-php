<?php

namespace PlacetoPay\PlacetoPaySdk\Tests\Helpers;

use PlacetoPay\PlacetoPaySdk\Enums\DocumentType;
use PlacetoPay\PlacetoPaySdk\Helpers\DocumentHelper;
use PlacetoPay\PlacetoPaySdk\Tests\BaseTestCase;

class DocumentHelperTest extends BaseTestCase
{
    public function testItExcludesTheGivenDocumentTypes()
    {
        $documentTypes = DocumentHelper::documentTypes();
        $this->assertTrue(in_array(DocumentType::CI->value, $documentTypes), 'Document CI Exists');
        $this->assertTrue(in_array(DocumentType::CC->value, $documentTypes), 'Document CC Exists');
        $this->assertTrue(in_array(DocumentType::NIT->value, $documentTypes), 'Document NIT Exists');

        $exclude = [DocumentType::CI->value, DocumentType::CC->value];
        $documentTypes = DocumentHelper::documentTypes($exclude);
        $this->assertFalse(in_array(DocumentType::CI->value, $documentTypes), 'Document CI Excluded');
        $this->assertFalse(in_array(DocumentType::CC->value, $documentTypes), 'Document CC Excluded');
        $this->assertTrue(in_array(DocumentType::NIT->value, $documentTypes), 'Document NIT Exists after');
    }

    public function testItHandlesBusinessDocuments()
    {
        $this->assertIsArray(DocumentHelper::businessDocument());
        $this->assertFalse(DocumentHelper::businessDocument(DocumentType::CC->value));

        $this->assertFalse(DocumentHelper::isValidDocument('NOT_EXISTS', '1234'));
    }

    public function testItValidatesCorrectlyTheCI()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::CI->value, '1002606430'));
    }

    public function testItValidatesCorrectlyTheRUC()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::RUC->value, '1798288377001'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::RUC->value, '1798288377'));
    }

    public function testItValidatesCorrectlyTheNIT()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::NIT->value, '860000038'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::NIT->value, '86000003'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::NIT->value, '8600000384'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::NIT->value, '8600000384-42'));
    }

    public function testItValidatesCorrectlyDNI()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::DNI->value, '12345678'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::DNI->value, '1234859'));
    }

    public function testItValidatesCorrectlyCRCPF()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::CRCPF->value, '123485989'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::CRCPF->value, '12348598'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::CRCPF->value, '02348598'));
    }

    public function testItValidatesCorrectlyCPJ()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::CPJ->value, '1234567894'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::CPJ->value, '123456789'));
    }

    public function testItValidatesCorrectlyDimex()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::DIMEX->value, '12345678949'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::DIMEX->value, '123456789491'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::DIMEX->value, '1234567894911'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::DIMEX->value, '1234567894'));
    }

    public function testItValidatesCorrectlyDIDI()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::DIDI->value, '12345678949'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::DIDI->value, '123456789491'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::DIDI->value, '1234567894911'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::DIDI->value, '1234567894'));
    }

    public function testItValidatesCorrectlyTheChileanRUT()
    {
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::CLRUT->value, '12.345.678-5'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::CLRUT->value, '30.686.957-4'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::CLRUT->value, '13.342.430-K'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::CLRUT->value, '13.342.430-L'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::CLRUT->value, '15127983-k'));
        $this->assertTrue(DocumentHelper::isValidDocument(DocumentType::CLRUT->value, '14096336-4'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::CLRUT->value, '1798288377'));
        $this->assertFalse(DocumentHelper::isValidDocument(DocumentType::CLRUT->value, 'Gdsdfgdfghfg'));
    }
}
