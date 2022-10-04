<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{
    use HasFactory;
    protected $table="commit_data";
    protected $fillable = ['commit_id', 'committer_name', 'committer_email', 'committer_date', 'commit_message'];
}