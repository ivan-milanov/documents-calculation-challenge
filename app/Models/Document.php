<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Document extends Model
{
    use HasFactory;

    public $fillable = [
        'customer',
        'vat_number',
        'document_number',
        'type',
        'parent_document',
        'currency',
        'total',
    ];
}
