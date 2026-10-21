<?php

namespace PlacetoPay\PlacetoPaySdk\Traits;

use PlacetoPay\PlacetoPaySdk\Entities\NameValuePair;

trait FieldsTrait
{
    /**
     * @var NameValuePair[]
     */
    protected array $fields = [];

    /**
     * @return NameValuePair[]
     */
    public function fields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fieldsData): static
    {
        $this->fields = [];
        foreach ($fieldsData['item'] ?? $fieldsData as $nvp) {
            $this->addField($nvp);
        }

        return $this;
    }

    public function fieldsToArray(): array
    {
        return array_map(fn (NameValuePair $field) => $field->toArray(), $this->fields());
    }

    /**
     * @param NameValuePair[]|null $nvps
     */
    public function fieldsToKeyValue(?array $nvps = null): array
    {
        $fields = [];
        foreach ($nvps ?: $this->fields() as $field) {
            $fields[$field->keyword()] = $field->value();
        }

        return $fields;
    }

    /**
     * @param NameValuePair|array $nvp anything else is ignored
     */
    public function addField(mixed $nvp): static
    {
        if (is_array($nvp)) {
            $nvp = new NameValuePair($nvp);
        }

        if ($nvp instanceof NameValuePair) {
            $this->fields[] = $nvp;
        }

        return $this;
    }
}
