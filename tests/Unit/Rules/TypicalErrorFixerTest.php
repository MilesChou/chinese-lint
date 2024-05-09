<?php

use App\Rules\TypicalErrorFixer;
use App\Markers\TerminalMarker;

test('Should not alert when no wrong words', function () {
    $target = new TypicalErrorFixer('錯的', '對的');
    $this->assertFalse($target->lint('這是對的文字'));
});

test('Should alert when has wrong words', function () {
    $target = new TypicalErrorFixer('錯的', '對的');
    $this->assertTrue($target->lint('這是錯的文字'));
});

test('Should fixed words to correct when error word', function () {
    $target = new TypicalErrorFixer('錯的', '對的');
    $this->assertSame('這是對的文字', $target->fix('這是錯的文字'));
});

test('Should fixed words to correct', function () {
    $target = new TypicalErrorFixer('錯的', '對的');
    $this->assertSame('自己騙自己 騙別人 別人騙你', $target->fix('自己騙自己 騙別人 別人騙你'));
});

test('Should mark wrong words', function () {
    $target = new TypicalErrorFixer('錯的', '對的');
    $marker = new class() extends TerminalMarker {
        public function wrapSource(string $str): string
        {
            return '<source>' . $str . '</source>';
        }
    };

    $this->assertSame('這是<source>錯的</source>文字', $target->markLint('這是錯的文字', $marker));
});

test('Should fixed and mark correct words', function () {
    $target = new TypicalErrorFixer('錯的', '對的');
    $marker = new class() extends TerminalMarker {
        public function wrapCorrect(string $str): string
        {
            return '<correct>' . $str . '</correct>';
        }
    };

    $this->assertSame('這是<correct>對的</correct>文字', $target->markFixed('這是錯的文字', $marker));
});
