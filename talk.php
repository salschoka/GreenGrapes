<?php
/**
 * 说说
 *
 * @package custom
 */
$this->need('header.php');
?>
    <style>
        .tk-timeline {
            position: relative;
            margin: 2.5rem 0 0;
            padding: 0;
            list-style: none
        }

        .tk-timeline .line {
            position: relative;
            min-height: 5rem;
        }

        .tk-timeline .author-gravatar {
            border-radius: 50%;
        }

        .tk-timeline .line:last-child {
            min-height: 0
        }

        .tk-timeline:before {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 15%;
            margin-left: -0.625rem;
            width: 0.625rem;
            background: #DDDDDD;
            content: ''
        }

        .tk-timeline > .line .tk-time {
            position: absolute;
            display: block;
            padding-right: 6.25rem;
            width: 20%
        }

        .tk-timeline > .line .tk-time {
            display: block;
            text-align: right;
            font-size: 1.1rem;
        }

        .tk-timeline > .line .tk-time-lg {
            font-size: 1.8rem
        }

        .tk-timeline > .line .tk-label {
            position: relative;
            margin: 0 0 15px 20%;
            padding: 1rem 1rem 2rem;
            border-radius: 4px;
            background: #fff;
            font-weight: 300;
            font-size: 16px;
            line-height: 1.4;
            border: 1px solid #e9ecef;
        }

        .tk-timeline .box {
            margin: 5px 0 15px 20%
        }

        .tk-timeline > .line .tk-label:after {
            position: absolute;
            top: 0.625rem;
            right: 100%;
            width: 0;
            height: 0;
            border-style: solid;
            border-color: transparent;
            border-width: 0.625rem;
            content: " ";
            pointer-events: none;
            border-right-color: #fff;
        }

        .tk-timeline > .line .tk-icon {
            position: absolute;
            top: 0;
            left: 15%;
            margin: 0 0 0 -25px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #BBBBBB;
            box-shadow: 0 0 0 8px #DDDDDD;
            color: #fff;
            text-align: center;
            text-transform: none;
            font-weight: 400;
            font-style: normal;
            font-variant: normal;
            font-size: 1.4rem;
            speak: none;
            -webkit-font-smoothing: antialiased
        }
        .tk-timeline > .line .fa-icon {
            line-height: 40px;
        }
        .tk-timeline .page-nav {
            margin: 5px 0 15px 20%;
        }
    </style>
    <div id="m-container" class="container">
        <div class="row ml-0 mr-0">
            <div class="col-md-8 pl-1 pr-1">
                <?php
                    $page = (int) $this->request->get('page', 1);
                    $page_size = 20;
                    $total = MicroTalk_Plugin::totalShowCount();
                    $talks = MicroTalk_Plugin::talkPosts($page, $page_size);
                ?>
                <ul class="tk-timeline">
                    <?php $year = ''; ?>
                    <?php foreach ($talks as $talk) : ?>
                    <li class="line">
                        <time class="tk-time" datetime="">
                            <?php if ($year !== date('Y', $talk['created'])) :?>
                            <span class="tk-time-lg"><?php _e(date('Y', $talk['created'])); ?></span>
                            <?php endif; ?>
                            <span><?php _e(date('m-d', $talk['created'])); ?></span>
                        </time>
                        <div class="tk-icon">
                            <img class="author-gravatar" src="<?php _e(Typecho_Common::gravatarUrl($talk['mail'], 40, 'X', 'mm', $this->request->isSecure())); ?>" alt="<?php _e($talk['author']); ?>" />
                        </div>
                        <div class="tk-label">
                            <div class="index-text post-content">
                                <?php _e($talk['content']); ?>
                            </div>
                        </div>
                    </li>
                    <?php $year = date('Y', $talk['created']); ?>
                    <?php endforeach; ?>
                    <li class="line">
                        <div class="tk-icon fa-icon fa fa-ellipsis-h"></div>
                        <div class="page-nav">
                            <nav>
                            <?php
                                //分页
                                $query = $this->request->makeUriByRequest('page={page}');
                                $pange_nav = new Typecho_Widget_Helper_PageNavigator_Box($total, $page, $page_size,$query);
                                echo '<ul class="pagination">';
                                $pange_nav->render('&laquo;', '&raquo;', 3, '...', array(
                                    'itemTag'       =>  'li',
                                    'textTag'       =>  'span',
                                    'currentClass'  =>  'page-item disabled',
                                    'prevClass'     =>  'prev',
                                    'nextClass'     =>  'next',
                                ));
                                echo '</ul>';
                            ?>
                            </nav>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <?php $this->need('sidebar.php'); ?>
            </div>
        </div>

    </div>
<?php $this->need('footer.php');
