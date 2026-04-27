<?php

declare(strict_types=1);
trait T
{
    public string $prop = '';
}
class C
{
    use T;

    public string $prop = '';
}
$c = new C;
echo $c->prop;
