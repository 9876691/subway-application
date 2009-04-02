<div class="yui-ge">
    <div class="yui-g first"><div class="shadow">

            <div class="header">
                <h6>Users</h6>
            </div>

            <div id="results"><table>
                    <?php foreach ($people as $person): ?>
                    <tr>
                        <td class="picture">
                                <?php if ($person['Person']['image_id'] != null) { ?>
                                <a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>" class="image"><img
                                  width="55" height="55"
                                  src="<?php echo $html->url('/notes/download/') . $person['Person']['image_id'] ?>"
                                  alt="Picture" /></a>
                                <?php } else { ?>

                                <a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>" class="image">
                                  <?php echo $html->image('person.gif', array('alt' => 'Picture')) ?>
                                </a>
                                <?php } ?>
                        </td>
                        <td>
                            <p class="name">
                                <a href="<?php echo $html->url('/people/view/') . $person['Person']['id'] ?>">
                                    <?php echo $person['Person']['first_name']; ?>&nbsp;
                                    <?php echo $person['Person']['surname']; ?></a><br />
                                <a href="<?php echo $html->url('/companies/view/') . $person['Company']['id'] ?>">
                                    <?php echo $person['Company']['name'] ?>
                                </a>
                        </p></td>
                        <td><p>Account Owner</p></td>
                        <td class="last"></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
    </div></div>
    <div class="yui-g sidebar">
    </div>
</div>
