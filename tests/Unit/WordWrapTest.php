<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class WordWrapTest extends PHPUnit_Framework_TestCase
{
    protected function assertItBehavesLikeWordWrap($string, $length)
    {
        $wrapResult = wrap($string, $length);

        $wordWrapResult = wordwrap($string, $length, "\n", true);

        $this->assertEquals($wordWrapResult, $wrapResult, 'Results don\'t match');
    }

    public function test_it_has_function_wrap()
    {
        $this->assertTrue(function_exists('wrap'), 'Function wrap doesn\'t exist');
    }

    public function test_it_returns_a_string()
    {
        $this->assertTrue(is_string(wrap('String', 5)));
    }

    public function test_it_throws_an_exception_when_length_parameter_is_invalid()
    {
        $length = -1;

        $this->expectExceptionMessage("Can not wrap a string into chunks with length: {$length}");


        wrap('Example string', $length);
    }

    public function test_it_returns_the_same_string_if_the_given_string_is_shorter_than_given_length()
    {
        $string = 'Short String';

        $result = wrap($string, 15);

        $this->assertEquals($string, $result, 'Strings don\t match');

        $result = wrap($string, strlen($string));

        $this->assertEquals($string, $result, 'Strings don\t match');
    }

    public function test_it_preserves_leading_space_characters()
    {
        $length = 5;
        $string = '           String with 11 spaces at the beginning';
        $expected = implode("\n", [
            '     ',
            '     ',
            ' Stri',
            'ng',
            'with',
            '11',
            'space',
            's at',
            'the',
            'begin',
            'ning',
        ]);

        $this->assertEquals($expected, wrap($string, $length));
    }


    public function test_it_preserves_trailing_space_characters()
    {
        $length = 5;
        $string = '           String with 11 spaces at the beginning and 7 at the end       ';

        $expected = implode("\n", [
            '     ',
            '     ',
            ' Stri',
            'ng',
            'with',
            '11',
            'space',
            's at',
            'the',
            'begin',
            'ning',
            'and 7',
            'at',
            'the',
            'end  ',
            '     ',
        ]);



        $this->assertEquals($expected, wrap($string, $length));
    }

    public function test_it_splits_long_words()
    {
        $length = 5;

        $string = 'Veeeeeeryy long woooooord in a string';

        $expected = implode("\n", [
            'Veeee',
            'eeryy',
            'long',
            'woooo',
            'oord',
            'in a',
            'strin',
            'g',
        ]);

        $this->assertEquals($expected, wrap($string, $length));
    }

    public function test_it_behaves_like_php_wordwrap()
    {
        /*
         * This is true only in certain scenarios as php function wordwrap doesn't trim spaces between words
         *
         * And since both functions don't process multi-byte characters properly the tests pass for those as well :)
         */


        $this->assertItBehavesLikeWordWrap('I am so blue I\'m greener than purple.', 8);

        $this->assertItBehavesLikeWordWrap('I stepped on a Corn Flake, now I\'m a Cereal Killer', 6);

        $this->assertItBehavesLikeWordWrap('Llamas eat sexy paper clips', 4);

        $this->assertItBehavesLikeWordWrap('On a scale from one to ten what is your favourite colour of the alphabet.', 1);

        $this->assertItBehavesLikeWordWrap("Wednesday is hu\nmp day, but has anyone\nasked the camel if he’s happy about it?", 25);

        $this->assertItBehavesLikeWordWrap('I often see the time 11:11 or 12:34 on clocks.', 300);

        $this->assertItBehavesLikeWordWrap('This is the last random sentence I will be writing and I am going to stop mid-sent', 11);

        $this->assertItBehavesLikeWordWrap("The body may perhaps\ncompensates for the loss of a true metaphysics.", 8);

        $this->assertItBehavesLikeWordWrap('The sparkly lamp ate a pillow then punched Larry.', 7);

        $this->assertItBehavesLikeWordWrap('Veeeeeeryy long word in a string', 5);

        $this->assertItBehavesLikeWordWrap('Киррилистический текст', 5);

        $this->assertItBehavesLikeWordWrap('Text that has mācrons', 5);

        $this->assertItBehavesLikeWordWrap('Tdzћћ÷zzћzћћzћћћ ћћzћzћzz•∆љљњ…і', 5);

        $this->assertItBehavesLikeWordWrap('Veeeeeeryydfgsdjgsfdgs dfjghdsdfjgsdfgjsdfg sdjgsdfghsdfjghsdfgjsdkgsdkfmsd ', 69);

        $this->assertItBehavesLikeWordWrap('Veeeeeer yydfgsdjgsfdgs dfjghdsdfjgs dfgjsdfg sdjgsdfgh sdfjghsdf gjsdkgsdkfmsd ', 3);
    }
}
