<?php

namespace PlacetoPay\PlacetoPaySdk\Enums;

enum DocumentType: string
{
    // Colombia
    case CC = 'CC';
    case CE = 'CE';
    case TI = 'TI';
    case NIT = 'NIT';
    case RUT = 'RUT';

    // Generic
    case PPN = 'PPN';
    case TAX = 'TAX';
    case LIC = 'LIC';

    // USA
    case SSN = 'SSN';

    // Panama
    case CIP = 'CIP';

    // Brazil
    case CPF = 'CPF';

    // Ecuador
    case CI = 'CI';
    case RUC = 'RUC';

    // Peru
    case DNI = 'DNI';

    // Costa Rica
    case CRCPF = 'CRCPF'; // Cédula personal física
    case CPJ = 'CPJ'; // Cédula personal jurídica
    case DIMEX = 'DIMEX'; // Documento de identificación de Migración y Extranjería
    case DIDI = 'DIDI'; // Documento de identificación de diplomáticos

    // Chile
    case CLRUT = 'CLRUT'; // Rol Único Tributario

    public function pattern(): string
    {
        return match ($this) {
            self::CC => '/^[1-9][0-9]{3,9}$/',
            self::CE => '/^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/',
            self::TI => '/^[1-9][0-9]{4,11}$/',
            self::NIT, self::RUT => '/^[1-9]\d{6,9}$/',
            self::PPN, self::TAX, self::LIC => '/^[a-zA-Z0-9_]{4,16}$/',
            self::SSN => '/^\d{3}\d{2,3}\d{4}$/',
            self::CIP => '/^(PE|E|N|[23456789](?:AV|PI)?|1[0123]?(?:AV|PI)?)-?(\d{1,4})-?(\d{1,6})$/',
            self::CPF => '/^\d{10,11}$/',
            self::CI => '/^\d{10}$/',
            self::RUC => '/^\d{13}$/',
            self::DNI => '/^\d{8}$/',
            self::CRCPF => '/^[1-9][0-9]{8}$/',
            self::CPJ => '/^[1-9][0-9]{9}$/',
            self::DIMEX, self::DIDI => '/^[1-9][0-9]{10,11}$/',
            self::CLRUT => '/^(\d{1,2}(?:\.?\d{1,3}){2}-[\dkK])$/',
        };
    }

    public function isBusiness(): bool
    {
        return match ($this) {
            self::NIT, self::RUT, self::RUC, self::CLRUT => true,
            default => false,
        };
    }

    public function isValid(string $document): bool
    {
        return (bool)preg_match($this->pattern(), $document);
    }
}
