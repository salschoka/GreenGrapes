<?php

/**
 * 随机文章
 * @throws Typecho_Db_Exception
 * @throws Typecho_Widget_Exception
 */
function theme_random_posts(){
    $defaults = array(
        'number' => 10,
        'before' => '<ul class="list-group">',
        'after' => '</ul>',
        'xformat' => '<li class="list-group-item clearfix"><a href="{permalink}" title="{title}">{title}</a></li>'
    );
    $db = Typecho_Db::get();
    $rand = "RAND()";
    if (stripos($db->getAdapterName(), 'sqlite') !== false) {
        $rand = "RANDOM()";
    }

    $sql = $db->select()->from('table.contents')
        ->where('status = ?','publish')
        ->where('type = ?', 'post')
        ->where('created <= ' . Helper::options()->gmtTime, 'post') //添加这一句避免未达到时间的文章提前曝光
        ->limit($defaults['number'])
        ->order($rand);
    $result = $db->fetchAll($sql);
    echo $defaults['before'];
    foreach($result as $val){
        $val = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($val);
        echo str_replace(array('{permalink}', '{title}'),array($val['permalink'], $val['title']), $defaults['xformat']);
    }
    echo $defaults['after'];
}

function get_theme_color_array() {
    $arr = array(
        'red' => _t('赤'),
        'orange' => _t('橙'),
        'yellow' => _t('黄'),
        'green' => _t('绿'),
        'cyan' => _t('青'),
        'blue' => _t('藍'),
        'purple' => _t('紫'),
        'gray' => _t('灰')
    );
    return $arr;
}

/*
 * 返回主题颜色配置
 * return string
 */
function get_theme_color() {
    $key = 'greengrapes_color';
    $options = Typecho_Widget::widget('Widget_Options');

    if ($options->allow_user_change_color && isset($_COOKIE[$key])
        && array_key_exists($_COOKIE[$key], get_theme_color_array())) {
        return $_COOKIE[$key];
    }
    $color = $options->themeColor;
    return $color;
}




function themeConfig($form) {
    $options = Typecho_Widget::widget('Widget_Options');
    $bgImg = new Typecho_Widget_Helper_Form_Element_Text('bgImg', null, $options->themeUrl('img/bg.jpg', 'GreenGrapes'), _t('ホームページの背景画像アドレス'), _t('ここに画像のURLアドレスを入力します。ホームページの背景画像として、imgの下のデフォルトのheader.pngが使用されます'));
    $form->addInput($bgImg);

    $headIcon = new Typecho_Widget_Helper_Form_Element_Text('headerIcon', null, $options->themeUrl('img/head.jpg', 'GreenGrapes'), _t('アイコン画像のアドレス'), _t('ここに画像のURLアドレスを入力します。ホームページのアバターとして、画像の下にあるデフォルトのhead.pngが使用されます'));
    $form->addInput($headIcon);

    $siteIcon = new Typecho_Widget_Helper_Form_Element_Text('sideName', null, null, _t('サイドバーのユーザー名'), _t('ここの左側に表示されているユーザー名を入力、デフォルトでは表示されません'));
    $form->addInput($siteIcon);

    $themeColor = new Typecho_Widget_Helper_Form_Element_Select('themeColor', get_theme_color_array(), 'green', _t('テーマカラー'), _t('ラベルの色と各記事の色を含めます'));
    $form->addInput($themeColor);

    $allow_user_change_color = new Typecho_Widget_Helper_Form_Element_Radio('allow_user_change_color',
        array(0=>_t('拒绝'),1=>_t('允许'),), '1', _t('ユーザーがテーマの色を切り替えられるようにするかどうか'),_t('視聴者は右側でテーマの色を切り替えることができます（この訪問者にのみ有効）'));
    $form->addInput($allow_user_change_color);

    $showBlock = new Typecho_Widget_Helper_Form_Element_Checkbox('ShowBlock', array(
        'ShowPostBottomBar' => _t('記事ページには、前の記事と次の記事が表示されます'),
        'SidebarHiddenInDetail' => _t('記事ページのサイドバーを非表示'),
        'HeaderHiddenInDetail' => _t('文章页隐藏顶部头像'),
        'ShowCategory' => _t('サイドバーの表示カテゴリ'),
        'ShowArchive' => _t('サイドバーにドキュメントを表示する'),
        ),
        array('ShowPostBottomBar'), _t('設定を表示'));
    $form->addInput($showBlock->multiMode());
}

/**
 * 重写评论显示函数
 */
function threadedComments($comments, $options){
    $singleCommentOptions = $options;
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }

    $commentLevelClass = $comments->levels > 0 ? ' comment-child' : ' comment-parent';

    ?>
<li itemscope itemtype="http://schema.org/UserComments" id="<?php $comments->theId(); ?>" class="comment-li<?php
if ($comments->levels > 0) {
    echo ' comment-child';
    $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
} else {
    echo ' comment-parent';
}
$comments->alt(' comment-odd', ' comment-even');
echo $commentClass;
?>">

    <div class="comment-author" itemprop="creator" itemscope itemtype="http://schema.org/Person">
        <span itemprop="image"><?php $comments->gravatar($singleCommentOptions->avatarSize, $singleCommentOptions->defaultAvatar); ?></span>

    </div>
    <div class="comment-body">
        <cite class="fn" itemprop="name"><?php $singleCommentOptions->beforeAuthor();
            $comments->author();
            $singleCommentOptions->afterAuthor(); ?></cite>
        <div class="comment-content" itemprop="commentText">
            <?php $comments->content(); ?>
        </div>
        <div class="comment-footer">
            <time itemprop="commentTime" datetime="<?php $comments->date('c'); ?>"><?php $singleCommentOptions->beforeDate();
                $comments->date($singleCommentOptions->dateFormat);
                    $singleCommentOptions->afterDate(); ?></time>
            <?php $comments->reply($singleCommentOptions->replyWord); ?>
        </div>
    </div>
    <?php if ($comments->children) { ?>
        <div class="comment-children" itemprop="discusses">
            <?php $comments->threadedComments(); ?>
        </div>
    <?php } ?>
    
    
</li>
<?php

}