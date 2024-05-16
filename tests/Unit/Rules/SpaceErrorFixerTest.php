<?php

use App\Markers\TerminalMarker;
use App\Rules\SpaceErrorFixer;

test('Should not alert when no wrong words', function () {
    $target = new SpaceErrorFixer();
    $this->assertFalse($target->lint('這是 test 的文字'));
});

test('Should alert when has wrong words', function () {
    $target = new SpaceErrorFixer();
    $this->assertTrue($target->lint('這是test的文字'));
});

test('Should alert when has wrong number', function () {
    $target = new SpaceErrorFixer();
    $this->assertTrue($target->lint('這是123的文字'));
});

test('Should fixed words to correct when error word', function () {
    $target = new SpaceErrorFixer();
    $this->assertSame('這是 test 的文字', $target->fix('這是test的文字'));
});

test('Should fixed words to correct when error number', function () {
    $target = new SpaceErrorFixer();
    $this->assertSame('這是 123 的文字', $target->fix('這是123的文字'));
});

test('Should fixed words to correct', function () {
    $target = new SpaceErrorFixer();
    $this->assertSame('這是 test 的文字', $target->fix('這是 test 的文字'));
});

test('Should mark wrong words', function () {
    $target = new SpaceErrorFixer();
    $marker = new class() extends TerminalMarker {
        public function wrapSource(string $str): string
        {
            return '<source>' . $str . '</source>';
        }
    };
    $this->assertSame('這是<source> test </source>的文字', $target->markLint('這是test的文字', $marker));
});

test('Should fixed and mark correct words', function () {
    $target = new SpaceErrorFixer();
    $marker = new class() extends TerminalMarker {
        public function wrapCorrect(string $str): string
        {
            return '<correct>' . $str . '</correct>';
        }
    };
    $this->assertSame('這是<correct> test </correct>的文字', $target->markFixed('這是test的文字', $marker));
});
