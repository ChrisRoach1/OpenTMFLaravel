<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['protocol_title', 'start_date', 'end_date', 'database_lock_date'])]
class Study extends Model
{
    //
}
