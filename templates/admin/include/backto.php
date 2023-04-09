<?php
//This code can be used from ARTICLE, it will send to the Category page on the place where is located Article, to be able to continue by browsing for example.

//CATEGORY INFORMATION 
?>
<h3 class="text-left">
    <span class="backToName">
        <?php if ($results['category']) { ?>
        <a href="./?action=viewCategory&amp;idCategory=<?=  $results['category']->id ?>">
        Späť do kategórie 
        <?=  htmlspecialchars($results['category']->name) ?></a>
    
    <?php }else{
        // IF there is no category
    } ?>
    </span>
</h3>
