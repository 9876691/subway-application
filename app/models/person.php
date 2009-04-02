<?php
    class Person extends AppModel
    {
        var $name = 'Person';

        var $validate = array(

        'first_name'  => VALID_NOT_EMPTY,
        'surname'   => VALID_NOT_EMPTY

        );

        var $belongsTo = array('Company' =>
            array('className'    => 'Company',
                  'conditions'   => '',
                  'order'        => '',
                  'dependent'    =>  true,
                  'foreignKey'   => 'company_id'));
    }
?>