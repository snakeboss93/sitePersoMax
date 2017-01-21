<?php

namespace maxime\model\Ligne;

use maxime\model\traits\IdTrait;
use maxime\model\traits\JsonSerializeTrait;
use JsonSerializable;

/**
 * @Entity
 */
class Ligne implements JsonSerializable
{
    use IdTrait;


    use JsonSerializeTrait;
}
