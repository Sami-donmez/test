<?php

namespace App\Models;

use App\Traits\MultiTenant;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use MultiTenant;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contacts';

	public function group()
    {
        return $this->belongsTo('App\Models\ContactGroup', 'group_id')->withDefault(['name' => '']);
    }

	public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }
}
