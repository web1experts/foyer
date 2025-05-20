<?php if(isset($graphics) && !empty($graphics)){ ?>
<div id="card-group" class="card-group desktop_icon"> 
    <div class="filter-container p-0 row">        
        <?php foreach ($graphics as $graphics) { ?>
            <div  class="filtr-item"  onclick="selecticon(this)" data-path="<?= $graphics->path?>" data-thumb="<?= $graphics->thumb?>" id="icon-<?= $graphics->id;?>" data-id="<?= $graphics->id; ?>">
                <img class="card-img-top img-fluid mb-4 rrrr" src="<?= $graphics->thumb; ?>" alt="icon"/>
                <div class="text-center"><span ><?= $graphics->name;?></span></div>
                
            </div>
        <?php }  ?>                 
    </div> 
</div>

<div class="row">
    <div class="custom_userpagination"><h3 ><?php echo $paginate['links']; ?></h3></div>
</div>

<?php } else { ?>
<div class="row">
    <p>No media found on the server</p>
</div>
<?php } ?>