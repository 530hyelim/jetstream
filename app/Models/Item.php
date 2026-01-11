<?php
// php artisan make:model Item -mf
// m : migration (테이블 생성), f : factory (데이터 입력) 파일 생성

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $fillable = ['name', 'price', 'status'];

    public function user() {
        return $this->belongsTo(User::class); // 관계형 데이터베이스 관계맺기!!
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }
}
