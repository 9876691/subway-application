<div class="yui-ge">
    <div class="yui-g first"><div class="shadow">

            <div class="header">
                <h2><?php echo $group['Group']['name'] ?></h2>
            </div>

            <div id="result">
                <?php echo $this->renderElement('userlist'); ?>
            </div>

    </div></div>
    <div class="yui-g sidebar">

      <form method="post"
        action="<?php echo $html->url('/admin/moveuser/') . $group['Group']['id']; ?>">

        <fieldset>
            <legend>Move a user into this group</legend>
            <?php echo $form->select('Person/id', $users)?>
            <br />

            <input type="submit" value="Move">
            <img id="spinner" style="display: none;" src="/img/spinner.gif" />
        </fieldset>
        </form>

    </div>
</div>