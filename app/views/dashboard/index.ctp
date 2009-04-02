<div class="yui-ge">
    <div class="yui-g first"><div class="shadow">

            <div class="header">
                <h6>Recent History</h6>
            </div>

            <div id="history">
                <table>
                    <?php foreach ($tasks_notes as $entry): ?>
                    <?php if(! empty($entry['Task'])) { ?>
                    <tr>
                        <td><?php echo $html->image('task.gif', array('alt' => 'Task')) ?></td>
                        <td>
                            <p class="name">
                                <?php if(! empty($entry['Task']['due_date']) && ! empty($show_time)) { ?>
                                <strong><?php echo $entry['Task']['due_date'] ?></strong>
                                <?php } ?>
                            <?php if(! empty($entry['Task']['due_time'])) { ?>
                                <?php if(empty($show_time)) { ?><strong><?php } ?>
                                <?php echo $entry['Task']['due_time'] ?>
                                <?php if(empty($show_time)) { ?></strong><?php } ?>
                                <?php } ?>
                                Task Completed
                                <?php if(! empty($entry['Task']['associated_url'])) { ?>
                                <span class="association">
                                    &nbsp;(Re&nbsp;:&nbsp;<a href="<?php echo $entry['Task']['associated_url'] ?>"><?php echo $entry['Task']['associated_name'] ?></a>)
                                </span>
                                <?php } ?>
                            </p>
                            <p class="text"><?php echo $entry['Task']['subject'] ?></p>
                        </td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td>
                            <?php if(! empty($entry['Note']['image_id'])) { ?>
                            <a href="<?php echo $html->url('/notes/view/') . $entry['Note']['id'] ?>" class="image">
                              <img width="55" height="55" src="<?php echo $html->url('/notes/download/') . $entry['Note']['image_id'] ?>" alt="Picture" /></a>
                            <?php } else if($entry['Note']['association_type'] == 0 or
                            $entry['Note']['association_type'] == 100) { ?>
                            <a href="<?php echo $html->url('/notes/view/') . $entry['Note']['id'] ?>" class="image">
                              <?php echo $html->image('person.gif', array('alt' => 'Person')) ?></a>
                            <?php } else if($entry['Note']['association_type'] == 1 or
                            $entry['Note']['association_type'] == 101) { ?>
                            <a href="<?php echo $html->url('/notes/view/') . $entry['Note']['id'] ?>" class="image">
                              <?php echo $html->image('company.gif', array('alt' => 'Company')) ?></a>
                            <?php } else if($entry['Note']['association_type'] == 2) { ?>
                            <a href="<?php echo $html->url('/notes/view/') . $entry['Note']['id'] ?>" class="image">
                              <?php echo $html->image('case.gif', array('alt' => 'Case')) ?></a>
                            <?php } ?>
                        </td>

                        <td>
                            <p class="name">
                                <strong><a href="<?php echo $entry['Note']['associated_url'] ?>">
                                <?php echo $entry['Note']['associated_name'] ?></a></strong>
                                <a href="<?php echo $html->url('/notes/view/') . $entry['Note']['id'] ?>">
                                <?php echo $entry['Note']['subject']; ?></a>
                                <span class="localtime"><?php echo $entry['Note']['created']; ?></span>
                            </p>
                            <?php echo $textile->text($entry['Note']['short_note']); ?>
                            <?php if(!empty($entry['Note']['shortened'])) { ?>
                            <p class="more">
                                <a href="<?php echo $html->url('/notes/view/') . $entry['Note']['id'] ?>">Read More...</a>
                            </p>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                <?php endforeach; ?>
                </table>
            </div>

    </div></div>
    <div class="yui-g sidebar">
        <div id="people-search">
            <h2>Find someone by typing their name</h2>
            <form id="main-search" action="<?php echo $html->url('/dashboard/partial/') ?>" method="post">
                <input name="search_field" autocomplete="off" id="search_field" type="text" />
        <?php echo $html->image('spinner.gif', array('id' => 'spinner', 'style' => 'display:none')) ?>
            </form>
            <div id="results">
            </div>
        </div>

        <p class="button"><?php echo $html->image('buttons/add.png', array('alt' => 'Add')) ?>
        <a href="<?php echo $html->url('/people/add/') ?>"><span>Add a new person</span></a></p>

        <?php echo $this->renderElement('taskbutton', array('standalone' => 'true')); ?>

        <h3>Your upcoming tasks</h3>
        <div id="tasks">
            <?php echo $this->renderElement('tasklist'); ?>
        </div>
    </div>
</div>