<?php
class g1_utils {
    static function g1_hash($text)
    {
        return hash('sha256', $text);
    }
}
?>