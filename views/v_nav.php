<?php
$disable = "disabled";
$active = "active";
$number = 6;
?>
<nav aria-label="...">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1):?>
            <li class="page-item"><a class="page-link" href="index.php?page=<?php echo ($page - 1);?>">&laquo;</a></li>
        <?php else:?>
            <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
        <?php endif;?>
        <?php if ($pages <= $number):?>
            <?php for ($i = 1; $i <= $pages; $i++):?>
                <li class="page-item <?php if ($i == $page) echo $active;?>"><a class="page-link" href="index.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>
            <?php endfor;?>
        <?php else:?>
            <?php if ($page == 1): ?>
                <?php for ($i = $page; $i <= $page + 1; $i++):?>
                    <li class="page-item <?php if ($i == $page) echo $active;?>"><a class="page-link" href="index.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>
                <?php endfor;?>
                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                <li class="page-item"><a class="page-link" href="index.php?page=<?php echo ($pages);?>"><?php echo ($pages);?></a></li>

            <?php elseif ($page == 2): ?>
                <?php for ($i = $page - 1; $i <= $page + 1; $i++):?>
                    <li class="page-item <?php if ($i == $page) echo $active;?>"><a class="page-link" href="index.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>
                <?php endfor;?>
                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                <li class="page-item"><a class="page-link" href="index.php?page=<?php echo ($pages);?>"><?php echo ($pages);?></a></li>

            <?php elseif ($page == 3): ?>
                <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                <?php for ($i = $page - 1; $i <= $page + 1; $i++):?>
                    <li class="page-item <?php if ($i == $page) echo $active;?>"><a class="page-link" href="index.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>
                <?php endfor;?>
                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                <li class="page-item"><a class="page-link" href="index.php?page=<?php echo ($pages);?>"><?php echo ($pages);?></a></li>

            <?php elseif ($page == $pages - 2): ?>
                <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                <?php for ($i = $pages - 3; $i <= $pages - 1; $i++):?>
                    <li class="page-item <?php if ($i == $page) echo $active;?>"><a class="page-link" href="index.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>
                <?php endfor;?>
                <li class="page-item"><a class="page-link" href="index.php?page=<?php echo ($pages);?>"><?php echo ($pages);?></a></li>

            <?php elseif ($page == $pages - 1): ?>
                <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                <?php for ($i = $pages - 2; $i <= $pages; $i++):?>
                    <li class="page-item <?php if ($i == $page) echo $active;?>"><a class="page-link" href="index.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>
                <?php endfor;?>

            <?php elseif ($page == $pages): ?>
                <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                <?php for ($i = $pages - 1; $i <= $pages; $i++):?>
                    <li class="page-item <?php if ($i == $page) echo $active;?>"><a class="page-link" href="index.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>
                <?php endfor;?>

            <?php else:?>
                <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                <?php for ($i = $page - 1; $i <= $page + 1; $i++):?>
                    <li class="page-item <?php if ($i == $page) echo $active;?>"><a class="page-link" href="index.php?page=<?php echo $i;?>"><?php echo $i;?></a></li>
                <?php endfor;?>
                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                <li class="page-item"><a class="page-link" href="index.php?page=<?php echo ($pages);?>"><?php echo ($pages);?></a></li>

            <?php endif;?>
        <?php endif;?>
        <?php if ($page < $pages):?>
            <li class="page-item"><a class="page-link" href="index.php?page=<?php echo ($page + 1);?>">&raquo;</a></li>
        <?php else:?>
            <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
        <?php endif;?>
    </ul>
</nav>

<!--<nav aria-label="...">-->
<!--    <ul class="pagination">-->
<!--        <li class="page-item disabled">-->
<!--            <a class="page-link" href="#">Previous</a>-->
<!--        </li>-->
<!--        <li class="page-item"><a class="page-link" href="#">1</a></li>-->
<!--        <li class="page-item active">-->
<!--            <a class="page-link" href="#">2</a>-->
<!--        </li>-->
<!--        <li class="page-item"><a class="page-link" href="#">3</a></li>-->
<!--        <li class="page-item">-->
<!--            <a class="page-link" href="#">Next</a>-->
<!--        </li>-->
<!--    </ul>-->
<!--</nav>-->