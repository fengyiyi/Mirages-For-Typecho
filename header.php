<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
    define("THEME_MIRAGES", 0);
    define("THEME_MIRAGES_WHITE", 1);
    define("THEME_MIRAGES_DARK", 2);
    if (strlen($this->options->staticPath) > 0){
        define("STATIC_PATH", rtrim($this->options->staticPath,'/').'/');
    } else {
        define("STATIC_PATH", $this->options->rootUrl."/usr/themes/Mirages/");
    }
    if ((!empty($this->options->otherOptions) && in_array('enablePjax', $this->options->otherOptions))) {
        define("PJAX_ENABLED", true);
    } else {
        define("PJAX_ENABLED", false);
    }

    if ($this->options->baseTheme == THEME_MIRAGES) {
        define("THEME_CLASS", "");
    } elseif ($this->options->baseTheme == THEME_MIRAGES_WHITE) {
        define("THEME_CLASS", "theme-white");
    } elseif ($this->options->baseTheme == THEME_MIRAGES_DARK) {
        define("THEME_CLASS", "theme-dark");
    }
    if(isHexColor($this->options->themeColor)) {
        $colorClass = "color-custom";
    } else {
        $colorClass = "color-default";
    }
    $this->ddb = $this->db;
    @$if_https = $_SERVER['HTTPS'];	//这样就不会有错误提示
    if ($if_https) {	//如果是使用 https 访问的话就添加 https
        define('IS_HTTPS', true);
    } else {
        define('IS_HTTPS', false);
    }
?>
<?php if(!isPjax() || !PJAX_ENABLED):?>
<!DOCTYPE HTML>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<?php
    $this->need('head.php');
    $this->need('headfix.php');
?>
</head>
<body class="<?=THEME_CLASS." ".$colorClass?>">
<?php if($this->options->disableAutoNightTheme <= 0 && !hasValue($this->options->disqusShortName) && THEME_CLASS != "theme-dark"):?>
    <script>
        if (USE_MIRAGES_DARK) {
            $('body').removeClass("theme-white").addClass("theme-dark");
        }
    </script>
<?php endif?>
<!--[if lt IE 9]>
<div class="browse-happy" role="dialog"><?php _e('当前网页 <strong>不支持</strong> 你正在使用的浏览器. 为了正常的访问, 请 <a href="http://browsehappy.com/">升级你的浏览器</a>'); ?>.</div>
<![endif]-->
<script type="text/javascript" class="n-progress">NProgress.inc(0.1);</script>
<span id="backtop" class="waves-effect waves-button"><i class="fa fa-angle-up"></i></span>
<div id="wrap">
    <?php $this->need('side_menu.php');?>

    <script type="text/javascript" class="n-progress">NProgress.inc(0.2);</script>
<?php else:?>
    <title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>
<?php endif?>
    <div id="body">
        <?php $this->need('headfix_pages.php');?>
        <script type="text/javascript">
            var bg = "<?php
                if($this->is("index")){
                    $this->banner = Helper::options()->defaultBg;
                } else {
                    $this->banner = loadArchiveBanner($this);
                }
                echo $this->banner;
                ?>";
            var getBgHeight = function(windowHeight){
                windowHeight = windowHeight || 560;
                if (windowHeight > window.screen.availHeight) {
                    windowHeight = window.screen.availHeight;
                }
                <?php if(isset($this->fields->bannerHeight)):?>
                var bgHeightP = "<?=$this->fields->bannerHeight?>";
                <?php else:?>
                var bgHeightP = "<?=$this->options->defaultBgHeight?>";
                <?php endif?>
                bgHeightP = bgHeightP.trim();
                bgHeightP = parseFloat(bgHeightP);
                bgHeightP =  windowHeight * bgHeightP / 100;
                return bgHeightP;
            };
            <?php if((!empty($this->options->otherOptions) && in_array('useQiniuImageResize', $this->options->otherOptions))):?>
            var addon = getImageAddon(width, height);
            bg = bg.trim();
            bg += addon;
            <?php endif?>
        </script>
        <?php $showBanner = (strlen($this->banner) > 5);?>
        <?php if($showBanner):?>
        <header id="masthead" class="blog-background overlay align-center align-middle animated from-bottom animation-on" style="
        <?php if($this->is("page","about")):?>
            background-color: #2a2b2c;
        <?php endif?>
            height:
        <?php $this->options->defaultBgHeight();?>;" itemscope="" itemtype="http://schema.org/Organization">
            <script type="text/javascript">
                var head = document.getElementById("masthead");
                head.style.backgroundImage = "url("+bg+")";
                var bgHeight = getBgHeight(window.innerHeight);
                head.style.height = bgHeight+"px";
            </script>
            <div class="inner">
                <div class="container">
                    <?php if($this->is('page','about')):?>
                        <div id="about-avatar">
                            <img class="rotate" src="<?php $this->options->sideMenuAvatar(); ?>" alt="Avatar" width="200" height="200"/>
                        </div>
                        <h1 class="blog-title light" itemprop="name"><?php $this->author(); ?>
                            <?php if($this->user->hasLogin()):?>
                                <a class="superscript" href="<?php Helper::options()->adminUrl()?>write-page.php?cid=<?=$this->cid?>" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <?php endif?>
                        </h1>
                        <h2 class="blog-description light bordered bordered-top" itemprop="description"><?=$this->fields->description?></h2>
                    <?php elseif($this->is('page','links')):?>
                        <h1 class="blog-title light" itemprop="name"><?php $this->title() ?>
                            <?php if($this->user->hasLogin()):?>
                                <a class="superscript" href="<?php Helper::options()->adminUrl()?>write-page.php?cid=<?=$this->cid?>" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <?php endif?>
                        </h1>

                    <?php elseif($this->is('index')):?>
                    <?php else: ?>
                        <h1 class="blog-title light" style="<?php if (isset($this->fields->mastheadTitleColor)) echo "color: ".$this->fields->mastheadTitleColor.";" ?>" itemprop="name"><?php if (isset($this->fields->mastheadTitle)) echo $this->fields->mastheadTitle ?></h1>
                        <h2 class="blog-description light bordered bordered-top" style="<?php if (isset($this->fields->mastheadTitleColor)) echo "color: ".$this->fields->mastheadTitleColor.";" ?>" itemprop="description"><?php if (isset($this->fields->mastheadSubtitle)) echo $this->fields->mastheadSubtitle ?></h2>
                    <?php endif ?>
                </div>
            </div>
        </header>
    <?php endif?>
        <?php if(!isPjax() || !PJAX_ENABLED):?>
        <script type="text/javascript" class="n-progress">NProgress.inc(0.25);</script>
        <?php endif?>
        <div class="container">
            <div class="row">

    
    
