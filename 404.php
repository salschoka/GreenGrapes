<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit(0);
$this->need('header.php');
?>

    <div id="m-container" class="container pl-0 pr-0">
        <div class="error-page">
            <h2 class="post-title">404 - <?php _e('NOT FOUND'); ?></h2>
            <p><?php _e('あなたの探していたページは移動したか削除されました, 探してみませんか: '); ?></p>
            <form method="post">
                <p><input type="text" name="s" class="text" autofocus /></p>
                <p><button type="submit" class="submit"><?php _e('探す'); ?></button></p>
            </form>
        </div>

    </div><!-- end #content-->
<?php $this->need('footer.php'); ?>