<div class="yui-ge">
    <div class="yui-g first"><div class="shadow">

            <form method="post" id="people-edit" class="extra-contact"
                  action="<?php echo $html->url('/people/edit/') . $this->data['Person']['id']; ?>">

                <div class="header clearfix">
                    <?php echo $form->input('Person/first_name', array('size' => '40'))?>
                    <?php echo $form->input('Person/surname', array('size' => '40'))?>
                    <?php echo $form->input('Person/title', array('size' => '25',
                      'label' => array('text' => 'Title (e.g. Director)')))?>
                    <?php echo $form->input('Company/name',
                            array('size' => '40', 'id' => 'company-search',
                            'autocomplete' => 'off' ))?>
                    <div style="clear:both" id="company-search-result" class="autocompleter"></div>
                </div>

                <?php echo $this->renderElement('contactfields'); ?>

                <div class="hr"></div>

                <fieldset class="submit">
                    <?php echo $form->submit('Save this person') ?>
                    &nbsp;or&nbsp;
                    <?php echo $html->link("cancel", "/people/"); ?>
                </fieldset>
            </form>
    </div></div>
    <div class="yui-g sidebar">
        <h2>Other ways to add people</h2>
        <p><?php echo $html->link("Upload a vCard file", "/people/add"); ?></p>
    </div>
</div>
