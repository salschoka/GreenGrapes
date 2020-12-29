<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit(0);
$this->comments()->to($comments);
?>


<?php if(!$this->allow('comment')): ?>
    <div class="comments-block">
        <p class="ui ribbon badge <?php $this->options->singleColor() ?>"><?php _e('楼主残忍的关闭了评论'); ?></p>
    </div>
<?php else: ?>
<div id="comments">
    <div class="comments-block">
        <p class="ui <?php $this->options->singleColor() ?> ribbon badge comments"><?php $this->commentsNum(_t('还不快抢沙发'), _t('只有地板了'), _t('<span class="comment-highlight">%d</span> 件
		のコメント')); ?></p>
        <?php $comments->listComments(); ?>

        <?php $comments->pageNav('&laquo; 1ページ前へ', '1ページ後へ &raquo;'); ?>
    </div>
    <div class="comments-block new-comment" id="<?php $this->respondId(); ?>">
        <div>
            <?php $comments->cancelReply(); ?>
        </div>
        <p class="ui ribbon badge <?php $this->options->singleColor() ?>"><?php _e('コメントを追加'); ?></p>
        <form method="post" action="<?php $this->commentUrl() ?>" class="ui fluid form">
            <?php if($this->user->hasLogin()): ?>
                <div class="comments-field"><?php _e('アカウントにログイン：'); ?><a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>. <a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('ログアウト'); ?> &raquo;</a></div>
            <?php else: ?>
                <div class="two fields">
                    <div class="comments-field">
                        <input type="text" name="author" placeholder="<?php _e('名前'); ?><?php _e(' (必須)') ?>" value="<?php $this->remember('author'); ?>" />
                    </div>
                    <div class="comments-field">
                        <input type="email" name="mail" placeholder="<?php _e('メールアドレス'); ?><?php if ($this->options->commentsRequireMail): ?><?php _e(' (必須)') ?><?php endif; ?>" value="<?php $this->remember('mail'); ?>" />
                    </div>
                </div>
                <div class="comments-field">
                    <input type="url" name="url" placeholder="<?php _e('個人ページ'); ?><?php if ($this->options->commentsRequireURL): ?><?php _e(' (必須)') ?><?php endif; ?>" value="<?php $this->remember('url'); ?>" />
                </div>
            <?php endif; ?>

            <div class="comments-field">
                <textarea rows="8" cols="50" id="comment-content" placeholder="<?php _e('リプライの内容'); ?><?php _e(' (必須)')?>" name="text"><?php $this->remember('text'); ?></textarea>
            </div>
            <button type="submit" id="comment-submit" class="btn btn-skin"><?php _e('コメントを投稿'); ?></button>
        </form>
    </div>
</div>
<?php endif; ?>

