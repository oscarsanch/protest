<?php
class View
{
    public function render($template, $data = null)
    {
        include ("views/$template.php");
    }
}