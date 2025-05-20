<?php 
  $list_title=$searchlist_title=$search_listdesc=$searchlist_btntext=$createlist_title=$create_listsdesc=$createlist_btntext="";
  
  if($result_page->meta_value!=""){
    $page_meta=json_decode($result_page->meta_value);

    if($page_meta->list_title!=""){
      $list_title= $page_meta->list_title;
    }

    if($page_meta->searchlist_title!=""){
      $searchlist_title= $page_meta->searchlist_title;
    }

    if($page_meta->search_list!=""){
      $search_listdesc= $page_meta->search_list;
    }

    if($page_meta->searchlist_btntext!=""){
      $searchlist_btntext= $page_meta->searchlist_btntext;
    }



    if($page_meta->createlist_title!=""){
      $createlist_title= $page_meta->createlist_title;
    }

    if($page_meta->create_lists!=""){
      $create_listsdesc= $page_meta->create_lists;
    }

    if($page_meta->createlist_btntext!=""){
      $createlist_btntext= $page_meta->createlist_btntext;
    }
  }
  ?>

<div class="lists-wrapper">
  <div class="container">
    <h2 class="text-center secondary-heading"><?= $list_title; ?></h2>
    <div class="row list-box-row">
      <div class="col-md-6">
        <div class="list-box">
          <h4><?= $searchlist_title; ?></h4>
          <p><?= $search_listdesc; ?></p>
          <button type="button" class="btn btn-blue" data-toggle="modal" data-target="#search-list"><?= $searchlist_btntext; ?></button>
        </div>
      </div>
      <div class="col-md-6">
        <div class="list-box">
          <h4><?= $createlist_title; ?></h4>
          <p><?= $search_listdesc; ?></p>
          <button type="button" class="btn btn-blue" data-toggle="modal" data-target="#create-list"><?= $createlist_btntext; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>



<!--  Search a list Modal -->

<!-- Modal -->
<div class="modal fade" id="search-list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Search Lists</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="row">
            <div class="col">
              <input type="text" class="form-control" placeholder="Name">
            </div>
            <div class="col">
              <input type="text" class="form-control" placeholder="Surname">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-blue">Search</button>
      </div>
    </div>
  </div>
</div>

<!--  Create a list Modal -->

<!-- Modal -->
<div class="modal fade" id="create-list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registrate</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="text" class="form-control" placeholder="Name">
            </div>
            <div class="form-group col-md-6">
              <input type="text" class="form-control" placeholder="Surname">
            </div>
          </div>
          <div class="form-group">
            <select class="form-control">
              <option selected>Select Country</option>
              <option>Country 1</option>
              <option>Country 2</option>
              <option>Country 3</option>
            </select>
          </div>
          <div class="form-group">
            <input type="email" class="form-control" placeholder="Email">
          </div>
          <div class="form-group">
            <input type="password" class="form-control" placeholder="Password">
          </div>
          <div class="form-group">
            <input type="password" class="form-control" placeholder="Confirm Password">
          </div>
          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="gridCheck">
              <label class="form-check-label" for="gridCheck">Terms of use and privacy policy</label>
            </div>
          </div>
          <button type="submit" class="btn btn-blue">Send</button>
        </form>
      </div>
    </div>
  </div>
</div>