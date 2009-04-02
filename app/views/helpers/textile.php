<?php
    require_once('classTextile.php');

    class TextileHelper extends AppHelper
    {
        function text($text)
        {
            $textile = new Textile();
            return $textile->TextileRestricted($text, '');
        }
    }
?>
