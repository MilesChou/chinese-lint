<?php

use App\Rules\PunctuationErrorFixer;
use App\Markers\TerminalMarker;

test('Should not alert when no wrong words', function () {
    $target = new PunctuationErrorFixer();
    $this->assertFalse($target->lint('這是「對」的文字'));
});

test('Should alert when has wrong words', function () {
    $target = new PunctuationErrorFixer();
    $this->assertTrue($target->lint('這是"錯"的文字'));
});

test('Should fixed \'quotation marks\' to correct when error word', function () {
    $target = new PunctuationErrorFixer();
    $this->assertSame('這是「測試」的文字', $target->fix('這是\'測試\'的文字'));
});

test('Should fixed "double quotation marks"" to correct when error word', function () {
    $target = new PunctuationErrorFixer();
    $this->assertSame('這是「測試」的文字', $target->fix('這是"測試"的文字'));
});

test('Should fixed ‘apostrophe’ to correct when error word', function () {
    $target = new PunctuationErrorFixer();
    $this->assertSame('這是「測試」的文字', $target->fix('這是‘測試’的文字'));
});

test('Should mark wrong words', function () {
    $target = new PunctuationErrorFixer();
    $marker = new class() extends TerminalMarker {
        public function wrapSource(string $str): string
        {
            return '<source>' . $str . '</source>';
        }
    };
    $this->assertSame('這是<source>"錯的"</source>文字', $target->markLint('這是"錯的"文字', $marker));
});

test('Should fixed and mark correct words', function () {
    $target = new PunctuationErrorFixer();
    $marker = new class() extends TerminalMarker {
        public function wrapCorrect(string $str): string
        {
            return '<correct>' . $str . '</correct>';
        }
    };
    $this->assertSame('這是<correct>「測試」</correct>的文字', $target->markFixed('這是"測試"的文字', $marker));
});
