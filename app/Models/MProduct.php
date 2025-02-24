<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MProduct extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    public function getDAttribute(){
        return json_decode($this->data);
    }
    public function getVAttribute(){
        return $this->visible;
    }
    protected $appends = ['d', 'v'];
}
