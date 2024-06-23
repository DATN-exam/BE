<?php

namespace App\Services;

use App\Models\SetQuestion;
use Stevebauman\Hypertext\Transformer;
use Illuminate\Support\Str;

class WordService
{
    public function __construct()
    {
        //
    }

    public function exportQuestions($questions, SetQuestion $setQuestion)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->addNumberingStyle(
            'multilevel',
            array(
                'type' => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720, "restart" => true),
                )
            )
        );
        $section = $phpWord->addSection();
        $questions->each(function ($question) use ($section) {
            $q = (new Transformer)->toText($question->question);
            $section->addListItem($q, 0, null, 'multilevel');
            $question->answers->each(function ($answer) use ($section) {
                $section->addListItem($answer->answer, 1, null, 'multilevel');
            });
        });
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
        $objWriter->save($tempFile);
        $nameFile = Str::slug($setQuestion->title, '-') . "_" . Str::slug(now(), '-') . ".docx";
        return response()->download($tempFile, $nameFile)->deleteFileAfterSend(true);
    }
}
