<?php
trait T {
    public string $prop = '';
}
class C {
    use T;
    public string $prop = 'id';
}
$c = new C();
echo $c->prop;
