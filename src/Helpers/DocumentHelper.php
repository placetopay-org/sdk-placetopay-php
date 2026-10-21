<?php

namespace PlacetoPay\PlacetoPaySdk\Helpers;

use PlacetoPay\PlacetoPaySdk\Enums\DocumentType;

class DocumentHelper
{
    /**
     * @return string[]
     */
    public static function documentTypes(array $exclude = []): array
    {
        return array_diff(array_column(DocumentType::cases(), 'value'), $exclude);
    }

    public static function isValidType(string $type): bool
    {
        return DocumentType::tryFrom($type) !== null;
    }

    public static function isValidDocument(string $type, string $document): bool
    {
        return DocumentType::tryFrom($type)?->isValid($document) ?? false;
    }

    /**
     * Checks if a document is a business one or returns an array of business documents.
     * @return bool|string[]
     */
    public static function businessDocument(string $document = ''): bool|array
    {
        if ($document) {
            return DocumentType::tryFrom($document)?->isBusiness() ?? false;
        }

        return array_values(array_map(
            fn (DocumentType $type) => $type->value,
            array_filter(DocumentType::cases(), fn (DocumentType $type) => $type->isBusiness())
        ));
    }
}
