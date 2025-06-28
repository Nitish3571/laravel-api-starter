<?php

namespace App\Models;

use App\Enum\BaseStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="Column",description="Column array description", properties={
 *     @OA\Property(property="id", type="integer", description="Id of column", example=1),
 *     @OA\Property(property="name", type="string", description="Name of Column", example="test"),
 * })
 */
class Base extends Model
{

    const ID                     = 'id';

    const CREATED_BY             = 'created_by';

    const CREATED_AT             = 'created_at';

    const UPDATED_BY             = 'updated_by';

    const UPDATED_AT             = 'updated_at';

    const DELETED_BY             = 'deleted_by';

    const DELETED_AT             = 'deleted_at';

    const DATETIME               = 'datetime';

    const STATUS                 = 'status';

    const STATUS_NAME            = 'status_name';


    const STATUSES = [
        '1' => [
            'name'  => 'active',
            'color' => 'primary',
        ],
        '2' => [
            'name'  => 'inactive',
            'color' => 'light',
        ],
    ];



    public function createdBy()
    {
        return $this->belongsTo(User::class, Base::CREATED_BY);
    }
}
