<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ParameterTestSpec extends ObjectBehavior
{
    function it_is_ok_on_first_case()
    {
        $this->getContent("http://localhost/symfony/web/app_dev.php/?parameter=1")->shouldReturn('1');
    }
    function it_is_ok_on_second_case()
    {
        $this->getContent("http://localhost/symfony/web/app_dev.php/some_controller_action?parameter=2")->shouldReturn('2');
    }
    function it_is_ok_on_third_case()
    {
        $this->getContent("http://localhost/symfony/web/app_dev.php/some_other_controller_action?other_parameter=1")->shouldReturn('');
    }
}
