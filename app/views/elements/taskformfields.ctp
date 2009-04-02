<fieldset>
    	<legend>Add a task</legend>
    	<?php echo $form->input('Task/subject', array('id' => 'subject-field')) ?><br>
    	<label>When's it due?</label><br>
    	<div class="calendar">
    		<div class="cal-header">
    			<a class="date-down" href="#"><img src="/img/calendar-icons/minus.gif" alt="minus"></a>
    			<span class="calendar-title">April 2007</span>
    			<a class="date-up" href="#"><img src="/img/calendar-icons/plus.gif" alt="plus"></a>
    		</div>
                <table class="calendar">
                    <tr><th>M</th><th>T</th><th>W</th>
                    <th>T</th><th>F</th><th>S</th><th>S</th></tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                </table>
            <label>Add a time?</label><br>
            <input type="checkbox" name="time_check" class="time-check" <?php if(!empty($hours)) echo 'checked' ?> >
            <?php $hrs = array('0' => '00',
                    '1' => '01', '2' => '02', '3' => '03', '4' => '04',
                    '5' => '05', '6' => '06', '7' => '07', '8' => '08',
                    '9' => '09', '10' => '10', '11' => '11', '12' => '12',
                    '13' => '13', '14' => '14', '15' => '15', '16' => '16',
                    '17' => '17', '18' => '18', '19' => '19', '20' => '20',
                    '21' => '21', '22' => '22', '23' => '23'); ?>
            <select <?php if(empty($hours)) echo 'disabled' ?> class="task-hours" name="hours">
            <?php foreach($hrs as $hr) { ?>
                    <option <?php if(!empty($hours) && $hours == $hr) echo 'selected="selected"' ?>value="<?php echo $hr ?>"><?php echo $hr ?></option>
            <?php } ?>
            </select>
            &nbsp;:&nbsp;
            <?php $mins = array('0' => '00', '15' => '15',
                    '30' => '30', '45' => '45'); ?>
            <select <?php if(empty($hours)) echo 'disabled' ?> class="task-minutes" name="minutes">
            <?php foreach($mins as $hr) { ?>
                    <option <?php if(!empty($minutes) && $minutes == $hr) echo 'selected="selected"' ?>value="<?php echo $hr ?>"><?php echo $hr ?></option>
            <?php } ?>
            </select>
            <br>
            <input type="hidden" class="due_date" name="due_date" value="<?php if(!empty($due_date)) { echo $due_date; } ?>">
            <input type="hidden" class="display_date" name="display_date">
    	</div>
        <input class="submit" type="submit" value="Add this task">
        <?php if(empty($stand_alone)) { ?>
        &nbsp;or&nbsp;<a href="#" class="task-cancel">Cancel</a>
        <?php if(!empty($association)) { ?>
        <input type="hidden" name="associated_with_id" value="<?php echo $association ?>">
        <input type="hidden" name="association_type" value="<?php echo $type ?>">
        <?php } ?>
        <?php } else { ?>
        <?php echo $html->image('spinner.gif', array('alt' => 'busy',
          'style' => 'display:none', 'id' => 'task-spinner')) ?>
        <?php } ?>
</fieldset>
