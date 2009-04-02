<div class="yui-ge">
    <div class="yui-g first"><div class="shadow">

            <div class="header">
                <h6>Add a new case</h6>
            </div>

            <form class="normal" method="post" action="<?php echo $html->url('/kases/add')?>">
                <fieldset>
                    <?php echo $form->input('Kase/name', array('size' => '40'))?>
                </fieldset>

                <fieldset>
                    <input type="submit" value="Add this case" ?>
                           &nbsp;or&nbsp;
                           <?php echo $html->link("cancel", "/kases/"); ?>
                       </fieldset>
            </form>
    </div></div>
    <div class="yui-g sidebar">
    </div>
</div>

