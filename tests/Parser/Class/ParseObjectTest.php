<?php

namespace Tests\Parser\Class;

use Exception;
use Oida\AST\Class\ClassNode;
use Oida\AST\Class\ObjectNode;
use Oida\Environment\ClassInstance;
use Oida\Environment\Environment;
use Oida\Parser\Class\ParseClass;
use Oida\Parser\Class\ParseInitializeObject;
use Tests\Parser\ParserTestCase;

class ParseObjectTest extends ParserTestCase
{


    /**
     * @throws Exception
     */

    public function test_initialize_object()
    {
        $tokens = $this->tokenize('neu Klasse()');
        $object = new ParseInitializeObject($tokens);

        $instance = $object->parse(0)[0];

        $this->assertInstanceOf(ObjectNode::class, $instance);
    }


}