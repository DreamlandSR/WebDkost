<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    protected $table = 'review';
    protected $primaryKey = 'id_review';
    public $timestamps = false;
    protected $fillable = ['id_user','id_kamar','rating','komentar','tgl_review'];

    public function user()  { return $this->belongsTo(User::class,  'id_user',  'id_user'); }
    public function kamar() { return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar'); }
}