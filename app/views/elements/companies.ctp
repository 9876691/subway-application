<table>
    <?php foreach ($companies as $company): ?>
    <tr>
        <td class="picture">
            <?php if ($company['Company']['image_id'] != null) { ?>
              <a href="<?php echo $html->url('/companies/view/') . $company['Company']['id'] ?>" class="image">
              <img width="55" height="55"
              src="<?php echo $html->url('/notes/download/') . $company['Company']['image_id'] ?>"
              alt="Picture" /></a>
            <?php } else { ?>
              <a href="<?php echo $html->url('/companies/view/') . $company['Company']['id'] ?>" class="image">
              <?php echo $html->image('company.gif', array('alt' => 'Company')) ?></a>
            <?php } ?>
        </td>
        <td>
            <p class="name">
                <a href="<?php echo $html->url('/companies/view/') . $company['Company']['id'] ?>">
                <?php echo $company['Company']['name']; ?></a><br>
            </p>
        </td>
        <td class="last">
            <ul class="contact_info">
                <?php if(!empty($company['Company']['phone']))
                    { echo '<li>' . $html->image('contact-icons/mobile.gif', array('alt' => 'Phone')) .'&nbsp;'
                        . $company['Company']['phone'] . '</li>'; } ?>
                <?php if(!empty($company['Company']['email']))
                    { echo '<li>' . $html->image('contact-icons/mail.gif', array('alt' => 'Email')) .'&nbsp;'
                        . '<a href="mailto:' . $company['Company']['email'] . '">'
                        . $company['Company']['email'] . '</a></li>'; } ?>
            </ul>
        </td>
    </tr>
    <?php endforeach; ?>
</table>