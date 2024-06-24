<?php

namespace App\Services;

use App\Enums\Question\QuestionType;
use App\Models\SetQuestion;
use Stevebauman\Hypertext\Transformer;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;

class WordService
{
    public function __construct()
    {
        //
    }

    public function exportQuestions($questions, SetQuestion $setQuestion)
    {
        // Create a new PhpWord object
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('listStyle', array('name' => 'Arial', 'size' => 14, 'color' => '000000'));

        // Define multilevel numbering style
        $phpWord->addNumberingStyle(
            'multilevel',
            array(
                'type' => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => 'Câu %1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360,),
                    array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720, "restart" => true),
                )
            )
        );

        // Add a section
        $section = $phpWord->addSection();

        // Add a title to the document
        $title = $setQuestion->title;
        $phpWord->addTitleStyle(1, array('name' => 'Arial', 'size' => 20, 'bold' => true, 'color' => '1F4E79'), array('align' => 'center', 'spaceAfter' => 240));
        $section->addTitle("Bộ câu hỏi {$title}", 1);

        // Define title style

        // Add page numbering in the footer
        $footer = $section->addFooter();
        $footer->addPreserveText('Trang {PAGE}', array('align' => 'center'));

        // Add questions and answers with multilevel list and unified style
        $questions->each(function ($question) use ($section) {
            $q = (new Transformer)->toText(getQuestionWord($question->question));
            if ($question->type === QuestionType::ESSAY) {
                $section->addListItem("{$q} (Tự luận)", 0, 'listStyle', 'multilevel');
            } else {
                $section->addListItem($q, 0, 'listStyle', 'multilevel');
                $question->answers->each(function ($answer) use ($section) {
                    $section->addListItem($answer->answer, 1, 'listStyle', 'multilevel');
                });
            }
        });

        // Save the document as a Word file
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
        $objWriter->save($tempFile);

        // Generate the file name and download the document
        $nameFile = Str::slug($setQuestion->title, '-') . "_" . Str::slug(now(), '-') . ".docx";
        return response()->download($tempFile, $nameFile)->deleteFileAfterSend(true);
    }
}
