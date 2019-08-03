<div class="row">
    <?php foreach ($articles as $article): ?>
        <div class="col-xs-12 col-md-6 col-lg-4">
            <div class="card mb-4">
                <img src="<?php echo $article['path'];?>" class="card-img-top" alt="<?php echo $article['path'];?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $article['title'];?></h5>
                    <p class="card-text"><?php echo $article['content'];?></p>
                    <p>
                        <span class="mr-2"><?php echo $article['name']?></span>
                        <span><small class="text-muted"><?php echo $article['date'];?></small></span>
                    </p>
                    <?php if ($article['readmore']):?>
                        <a href="index.php?act=ArticleView&id=<?php echo $article['id_article']?>" class="btn btn-primary">Read more</a>
                    <?php endif;?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php echo $navigator;?>
