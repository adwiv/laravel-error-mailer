<?php

namespace Adwiv\Laravel\ErrorMailer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'url', 'inputs', 'content', 'repeats'];
    protected $casts = ['inputs' => 'array'];

    public static function createLog(string $message, string $content, ?string $url, ?array $inputs): ErrorLog
    {
        return self::create([
            'url' => $url,
            'inputs' => $inputs,
            'message' => $message,
            'content' => $content,
        ]);
    }

    public function reportByMail()
    {
        ErrorReportingJob::dispatch($this->message, $this->content, $this->url, $this->inputs);
    }

    public static function withRecentMessage($message, $withinSeconds = 60)
    {
        $timestamp = now()->subSeconds($withinSeconds);
        return self::whereMessage($message)->where('created_at', '>', $timestamp)->first(['id', 'repeats']);
    }

    public static function recentCount($withinSeconds = 900)
    {
        $timestamp = now()->subSeconds($withinSeconds);
        return self::where('created_at', '>', $timestamp)->count();
    }
}
