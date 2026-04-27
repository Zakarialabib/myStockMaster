<?php
trait T {
    public string $prop = '';
}
class C {
    use T;
    public string $prop = '';
}
$c = new C();
echo $c->prop;
