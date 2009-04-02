<?php
    class Note extends AppModel
    {
        var $name = 'Note';

        var $belongsTo = array('Person' =>
            array('className'    => 'Person',
                  'conditions'   => '',
                  'order'        => '',
                  'dependent'    =>  true,
                  'foreignKey'   => 'creator_id'));
    }
?>