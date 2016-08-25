<?php

namespace App\Models;

use App\Utilities\UniqueCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['unique_id'];

    /**
     * Add a new visitor.
     *
     * @return string
     */
    public static function add()
    {
        $unique_id = (string) (new UniqueCode())->generate();

        if (!self::byUniqueId($unique_id)) {
            (new self())->create([
                'unique_id' => $unique_id,
            ]);

            return $unique_id;
        }

        return self::add();
    }

    /**
     * Get Visitor by it's unique id.
     *
     * @param string $uniqueId
     *
     * @return Visitor|null
     */
    public static function byUniqueId($uniqueId)
    {
        return self::whereUniqueId($uniqueId)->first();
    }
}
