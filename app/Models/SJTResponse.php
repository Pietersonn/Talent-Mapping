<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SJTResponse extends Model
{
    protected $table = 'sjt_responses';

    protected $fillable = [
        'session_id',
        'question_id',
        'question_version_id',
        'page_number',
        'selected_option',
        'response_time'
    ];

    public function testSession()
    {
        return $this->belongsTo(TestSession::class, 'session_id');
    }

    public function sjtQuestion()
    {
        return $this->belongsTo(SJTQuestion::class, 'question_id');
    }

    public function questionVersion()
    {
        return $this->belongsTo(QuestionVersion::class, 'question_version_id');
    }
}
