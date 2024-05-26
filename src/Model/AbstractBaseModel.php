<?php

namespace App\Model;

use App\SnakeCaseToCamelCaseConverter;
use Pimcore\Model\AbstractModel;

class AbstractBaseModel extends AbstractModel
{
    /**
     * Patch for setValue that automatically transforms setters such as "settrainer_id" to "setTrainerId" instead.
     */
    public function setValue(string $key, mixed $value, bool $ignoreEmptyValues = false): static
    {
        // PATCH START
        $method = 'set' . SnakeCaseToCamelCaseConverter::convert($key, true);
        // PATCH END

        if (strcasecmp($method, __FUNCTION__) !== 0
            && (isset($value) || !$ignoreEmptyValues)
        ) {
            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (method_exists($this, 'set' . preg_replace('/^o_/', '', $key))) {
                // compatibility mode for objects (they do not have any set_oXyz() methods anymore)
                $this->$method($value);
            }
        }

        return $this;
    }
}
