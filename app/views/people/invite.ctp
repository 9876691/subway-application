<div class="yui-ge">
    <div class="yui-g first"><div class="shadow">

            <div class="header">
                <h6>Invite &quot;<?php echo $person['Person']['first_name']; ?>&nbsp;
                <?php echo $person['Person']['surname']; ?>&quot; to be a user</h6>
            </div>

            <form class="invite-form" method="post">
                <fieldset>

                <label>What group would you like this user to belong to ?</label>
                <?php echo $form->select('parent_id', $groups, null, null, false)?>

                <label>Recipient Email Address</label>
                <input class="full-input" type="text" name="email"
                  value="<?php echo $email; ?>" />

                <label>From Address</label>
                <input class="full-input" type="text" name="from"
                  value="<?php echo $from; ?>" />

                <label>Subject of the email sent to this user</label>
                <input class="full-input" type="text" name="subject"
                  value="<?php echo $subject; ?>" />
                <label>Text of the email sent to the user</label>
                <textarea rows="10" class="full-input" name="message"><?php echo $message; ?></textarea>

                </fieldset>

                <fieldset>
                    <input type="submit" value="Invite this user" />
                    &nbsp;or&nbsp;
                    <?php echo $html->link("cancel",
                    "/people/view/" . $person['Person']['id']); ?>
                    <input type="hidden" name="id" value="<?php echo $person['Person']['id']; ?>" />
                    <input type="hidden" name="invite" value="<?php echo $invite; ?>" />
                </fieldset>
            </form>

    </div></div>
    <div class="yui-g sidebar">

    </div>
</div>