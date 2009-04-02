<ul class="contact-info">
    <?php foreach ($phones as $detail): ?>
    <?php if(!empty($detail['ContactMethod']['detail'])) { ?>
    <li>
        <?php echo $html->image('contact-icons/mobile.gif', array('alt' => 'Phone')) ?>
        &nbsp;
        <?php echo $detail['ContactMethod']['detail']; ?>&nbsp;
        <small><?php echo $detail['ContactMethod']['name']; ?></small>
    </li>
    <?php } ?>
<?php endforeach; ?>

<?php foreach ($emails as $detail): ?>
<?php if(!empty($detail['ContactMethod']['detail'])) { ?>
    <li>
        <?php echo $html->image('contact-icons/mail.gif', array('alt' => 'Email')) ?>
        &nbsp;
        <a href="mailto:<?php echo $detail['ContactMethod']['detail']; ?>"><?php echo $detail['ContactMethod']['detail']; ?></a>&nbsp;
    <small><?php echo $detail['ContactMethod']['name']; ?></small></li>
    <?php } ?>
<?php endforeach; ?>

<?php foreach ($websites as $detail): ?>
<?php if(!empty($detail['ContactMethod']['detail'])) { ?>
    <li>
        <?php echo $html->image('contact-icons/globe.gif', array('alt' => 'Website')) ?>
        &nbsp;
        <a href="<?php echo $detail['ContactMethod']['detail']; ?>"><?php echo $detail['ContactMethod']['detail']; ?></a>&nbsp;
    <small><?php echo $detail['ContactMethod']['name']; ?></small></li>
    <?php } ?>
<?php endforeach; ?>

<?php foreach ($addresses as $detail): ?>
<?php if(!empty($detail['Address']['street'])) { ?>
    <li><img src="/img/contact-icons/home.gif" alt="Address" />&nbsp;
        <?php echo $detail['Address']['street']; ?>&nbsp;
        <small><?php echo $detail['Address']['name']; ?></small>
        <?php if(!empty($detail['Address']['city'])) {
                echo '<br />' . $detail['Address']['city']; } ?>
        <?php if(!empty($detail['Address']['state'])) {
                echo '<br />' . $detail['Address']['state']; } ?>
        <?php if(!empty($detail['Address']['zip'])) {
                echo '<br />' . $detail['Address']['zip']; } ?>
        <?php if(!empty($detail['Address']['country'])) {
                echo '<br />' . $detail['Address']['country']; } ?>
        <br />
    </li>
    <?php } ?>
<?php endforeach; ?>
</ul>
