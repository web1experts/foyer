<div class="modal fade insert_modal" id="insertbookmartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= lang('addbookmark') ?></h5>
                <button type="button" class="close" id="close_assign_modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="tabs_data"/>
                <input type="hidden" class="subtab_status" value="no" />
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-sm-4">
                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Add from Library</a>
                            <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Create New</a>
                            <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Request Admin</a>
                        </div>
                    </div>
                    
                    <div class="col-xl-9 col-lg-8 col-sm-8">
                        <div class="tab-content" id="vert-tabs-tabContent">
                            <div class="tab-pane text-center fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                <form class="searchForm" id="bookmark_form" method="post">
                                    <div class="form-group">
                                        <label for="title">Search</label>
                                        <div class="form-input">
                                            <input type="text" name="search" class="form-control search_field">
                                        </div>
                                        <input type="hidden" class="tabs_data"/>
                                        <input type="hidden" name="search_type" value="bookmark"/>
                                    </div>
                                </form>
                                <div class="bookmarkList" id="bookmark_form_data">
                                    <input type="hidden" class="tabs_type" name="data_type" />
                                    <div class="row">                       
                                        <?php
                                        $bookmark_dt = media_bookmarks();
                                        
                                        /*echo "<pre>";
                                        print_r($bookmark_dt);*/

                                        if (isset($bookmark_dt['bookmarkData']) && !empty($bookmark_dt['bookmarkData'])) {                                           $total_graphics=$bookmark_dt['total_data'];
                                            foreach ($bookmark_dt['bookmarkData'] as $bookmark) { 

                                                $graphic_url= getGraphicsThumb($bookmark->graphic_id);        
                                                ?>
                                                <div class="filtr-item">
                                                    <div class="tooltip"><p><?= $bookmark->name; ?></p></div>
                                                    <a class="assign_bookmark_scheme" href="javascript:void(0)" data-id="<?= $bookmark->id; ?>">
                                                        <img class="card-img-top img-fluid mb-2" src="<?= $graphic_url; ?>" alt="icon"/>
                                                        <span class="media_title"><?= $bookmark->name; ?></span>
                                                    </a>
                                                </div>
                                                <?php
                                            }
                                            if ($total_graphics > 12) {
                                                echo '<button type="button" data-page="1"  class="load_more_data" data-limit="12" data-offset="12" data-type="bookmark">Load More</button>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                <form class="createNew" method="post">
                                    <div class="form-group">
                                        <label>Label</label>
                                        <div class="form-input">

                                            <input type="text" name="label" class="form-control" placeholder="Label" required="">

                                        </div>
                                        <input type="hidden" class="tabs_data"/>
                                        <input type="hidden" class="tabs_type" name="data_type" />
                                        
                                        <!-- Media Type -->
                                        <input type="hidden" class="media_image_new" name="media_image"/>
                                        <input type="hidden" class="media_thumb_new" name="media_thumb"/>
                                        <input type="hidden" class="media_id" name="media_id"/>
                                        <!-- End Media Type -->
                                        
                                        <input type="submit" value="Create" class="btn btn-primary" />
                                    </div>
                                    <div class="form-group">
                                        <label>URL</label>
                                        <div class="form-input">

                                            <input type="text" name="url" class="form-control url_testing" placeholder="http:// or https://" required="">
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-primary btn_test">Test</a>

                                    </div>
                                    <div class="form-group">
                                        <label>Graphic</label>
                                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Choose from Library</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Upload New</a>
                                            </li>
                                        </ul>

                                        <div id="selected_images"><!-- Display Selected Image --></div>
                                    </div>
                                </form>

                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <form class="searchForm" method="post" id="media_form">
                                            <div class="form-group">
                                                <label for="search">Search</label>
                                                <div class="form-input">
                                                    <input type="text" name="search" class="form-control search_field">
                                                </div>
                                                <input type="hidden" name="search_type" value="media"/>
                                            </div>
                                        </form>
                                        <div class="bookmarkList" id="media_form_data">
                                            <div class="row">
                                                <?php
                                                $media = media_library();
                                                if (isset($media['graphic']) && !empty($media['graphic'])) {

                                                    $total_graphics=$media['total_data'];

                                                    foreach ($media['graphic'] as $graphics) {
                                                        ?>
                                                        <div  class="filtr-item"  onclick="selecticon(this)" data-path="<?= $graphics->path ?>" data-thumb="<?= $graphics->thumb ?>" id="icon-<?= $graphics->id; ?>" data-id="<?= $graphics->id; ?>">
                                                            <img class="card-img-top img-fluid mb-2" src="<?= $graphics->thumb; ?>" alt="icon"/>
                                                            <span class="media_title"><?= $graphics->name; ?></span>
                                                        </div>
                                                        <?php
                                                    }
                                                    if ($total_graphics > 12) {
                                                        echo '<button type="button" data-page="1"  class="load_more_data" data-limit="12" data-offset="12" data-type="media">Load More</button>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                        <form class="browseFile" enctype="multipart/form-data" method="post">
                                            <div class="form-group">
                                                <label>Icon Name</label>
                                                <div class="form-input">
                                                    <input type="text" name="icon_name" placeholder="Icon name" class="form-control" required/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Browse for File</label>
                                                <div class="form-input">
                                                    <input type="file" name="graphic" class="form-control" required />
                                                </div>
                                                <button type="submit" class="btn btn-primary">Upload</button>
                                            </div>
                                        </form>
                                        <div class="requireList">
                                            <h3>File Upload Requirements</h3>
                                            <ul>
                                                <li>1. Must be in .PNG or .JPG format</li>
                                                <li>2. Must be no longer than 1024 pixels on the longest side</li>
                                                <li>3. Must be no small than 256 pixels on the shortest side</li>
                                                <li>4. Should be cropped as tightly as possible</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
                                <form class="requestForm p-4" method="post">
                                    <div class="form-group">
                                        <label>Label</label>
                                        <input type="text" name="bookmark_label" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>URL</label>
                                        <input type="text" name="bookmark_url" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Describe the type of Graphic you would like</label>
                                        <textarea rows="5" class="form-control" name="bookmark_comments" required></textarea>
                                    </div>
                                    <div class="form-group text-right">
                                        <input type="hidden" class="tabs_data"/>
                                        <input type="hidden" class="tabs_type" name="data_type" />
                                        <input type="submit" class="btn btn-primary py-2 px-4" value="Submit Request" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
.modal-backdrop.show{
    opacity:  0.8;
}
</style>