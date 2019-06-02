<div class="row justify-content-center">
    <div class="col-8">
        <div class="card mb-4">
            <img src="<?php echo $article['path'];?>" class="card-img-top" alt="<?php echo $article['path'];?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo $article['title'];?></h5>
                <p class="card-text"><?php echo $article['content'];?></p>
                <p>
                    <span class="mr-2"><?php echo $article['name']?></span>
                    <span class="mr-2"><?php echo $article['email']?></span>
                    <span><small class="text-muted"><?php echo $article['date'];?></small></span>
                </p>
            </div>
        </div>
        </div>
</div>