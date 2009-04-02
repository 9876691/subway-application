<?php
    class Company extends AppModel
    {
        var $name = 'Company';

        var $validate = array(
          'name'  => VALID_NOT_EMPTY,
        );
    }
?>