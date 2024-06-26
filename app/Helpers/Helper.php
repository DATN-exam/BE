<?php

use App\Enums\Exam\ExamStatus;
use App\Models\Image;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Str;

if (!function_exists('getToday')) {
    function getToday()
    {
        return Carbon::now()->format(config('define.date_format'));
    }
}

if (!function_exists('remainingTime')) {
    function remainingTime($startTime, $workingTime)
    {
        $startTime = Carbon::parse($startTime);
        list($hours, $minutes, $seconds) = sscanf($workingTime, "%d:%d:%d");
        $workingInterval = CarbonInterval::hours($hours)
            ->minutes($minutes)
            ->seconds($seconds);
        $endTime = $startTime->add($workingInterval);
        $now = Carbon::now();
        if ($endTime->lt($now)) {
            return 0;
        }
        return $endTime->diffInSeconds($now);
    }
}

if (!function_exists('calculateTimeAgo')) {
    function calculateTimeAgo($startTime)
    {
        $parsedDate = Carbon::parse($startTime);
        $currentDate = Carbon::now();
        $diffInSeconds = $parsedDate->diffInSeconds($currentDate);
        if ($diffInSeconds < 60) {
            return "$diffInSeconds giây trước";
        } elseif ($diffInSeconds < 3600) {
            $diffInMinutes = floor($diffInSeconds / 60);
            return "$diffInMinutes phút trước";
        } elseif ($diffInSeconds < 86400) {
            $diffInHours = floor($diffInSeconds / 3600);
            return "$diffInHours giờ trước";
        } else {
            $diffInDays = floor($diffInSeconds / 86400);
            return "$diffInDays ngày trước";
        }
    }
}

if (!function_exists('getTimeSubmit')) {
    function getTimeSubmit($startTime, $workingTime)
    {
        $startTime = Carbon::parse($startTime);
        list($hours, $minutes, $seconds) = sscanf($workingTime, "%d:%d:%d");
        $workingInterval = CarbonInterval::hours($hours)
            ->minutes($minutes)
            ->seconds($seconds);
        $endTime = $startTime->add($workingInterval);
        $now = Carbon::now();
        if ($endTime->lt($now)) {
            return $endTime;
        }
        return $now;
    }
}


if (!function_exists('checkStatusExam')) {
    function checkStatusExam($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $now = Carbon::now();
        if ($now->lt($startDate)) {
            return ExamStatus::UPCOMING;
        } elseif ($now->gt($endDate)) {
            return ExamStatus::HAPPENED;
        } else {
            return ExamStatus::HAPPENING;
        }
    }
}

if (!function_exists('baseToFile')) {
    function baseToFile($base64String)
    {
        return base64_decode($base64String);
    }
}

if (!function_exists('generatePathFile')) {
    function generatePathFile($path, $id, $extension): string
    {
        return $path . '/' . $id . '/' . $id . '-' . time() . Str::random(5) . '.' . $extension;
    }
}

if (!function_exists('getImagesQuestion')) {
    function getImagesQuestion($html)
    {
        $regex = "~<img[^>]*\bdata-key\s*=\s*[\"'][^\"']+[\"'][^>]*>~i";
        $modifiedHtml = $html;
        $images = [];
        preg_match_all($regex, $html, $matches);
        foreach ($matches[0] as $imgTag) {
            $key = Str::betweenFirst($imgTag, 'key="', '"');

            $src = Str::betweenFirst($imgTag, 'src="', '"');
            $modifiedHtml = Str::replaceFirst("src=\"{$src}\"", "src=\"{$key}\"", $modifiedHtml);
            $images[] = Str::between($key, '{{', '}}');
        }

        return [
            'html' => $modifiedHtml,
            'images' => $images,
        ];
    }
}

if (!function_exists('replaceImgTagsWithKey')) {
    function replaceImgTagsWithKey($html)
    {
        $regex = "~<img(?![^>]*\bdata-key\s*=\s*[\"'][^\"']+[\"'])[^>]*>~i";
        $matches = [];
        $mapping = [];
        $modifiedHtml = $html;
        preg_match_all($regex, $html, $matches);
        foreach ($matches[0] as $imgTag) {
            $uuid = Str::uuid();
            $key = "IMAGE_KEY_{$uuid}";
            $modifiedHtml = Str::replaceFirst(
                $imgTag,
                Str::replaceFirst("<img ", "<img data-key=\"{{{$key}}}\" ", $imgTag),
                $modifiedHtml
            );
            $src = Str::betweenFirst($imgTag, 'src="', '"');
            $mapping[$key] = $src;
            $modifiedHtml = Str::replaceFirst($src, "{{{$key}}}", $modifiedHtml);
        }
        return [
            'html' => $modifiedHtml,
            'mapping' => $mapping,
        ];
    }
}

if (!function_exists('replaceBase64ImagesWithSpace')) {
    function replaceBase64ImagesWithSpace($inputString)
    {
        $regex = '/data:image\/\w+;base64,/';
        return preg_replace($regex, ' ', $inputString);
    }
}

if (!function_exists('getQuestionHtml')) {
    function getQuestionHtml($questionHtml)
    {
        $regex = '/<img[^>]*\bdata-key\s*=\s*["\']([^"\']+)[\'"][^>]*>/i';
        preg_match_all($regex, $questionHtml, $matches);
        foreach ($matches[1] as $img) {
            $id = Str::between($img, "{{", "}}");
            $image =  Image::find($id);
            if (!$image) {
                continue;
            }
            $questionHtml = Str::replace("src=\"{$img}\"", "src=\"{$image->url}\"", $questionHtml);
        }
        return $questionHtml;
    }
}

if (!function_exists('getQuestionWord')) {
    function getQuestionWord($questionHtml)
    {
        $regex = '/<img[^>]*\bdata-key\s*=\s*["\']([^"\']+)[\'"][^>]*>/i';
        preg_match_all($regex, $questionHtml, $matches);
        foreach ($matches[0] as $key => $imgTag) {
            $dataKey = $matches[1][$key];
            $id = Str::between($dataKey, "{{", "}}");
            $image = Image::find($id);
            if (!$image) {
                continue;
            }
            $newImgTag = $image->url;
            $questionHtml = str_replace($imgTag, $newImgTag, $questionHtml);
        }
        return $questionHtml;
    }
}
