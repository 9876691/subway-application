<?php  if(!empty($stand_alone)) {
        echo '<form id="task-form" action="' . $html->url('/tasks/addtask/') .'" method="post">';
        echo $this->renderElement('taskformfields');
    } else {
        echo '<form id="task-form-embedded" action="' . $html->url('/tasks/addtask/') .'" method="post">';
        if(!empty($associated_with_id))
        {
            echo $this->renderElement('taskformfields',
                array('association' => $associated_with_id,
                'type' => $association_type));
        } else {
            echo $this->renderElement('taskformfields');
        }
    }
?>
</form>
