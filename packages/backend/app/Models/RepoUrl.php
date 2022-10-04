<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepoUrl extends Model
{
    use HasFactory;
    protected $table="repo_urls";
    protected $fillable = ['id', 'repo_url'];
}
