<div id="saveModal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm"> 
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add my Availibility</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>   
        <div class="modal-body">
          <div class="form-group">
            <label for="usr">Title:</label>
            <?php $user_data = get_userdata(get_current_user_id());?>
            <input type="text" class="form-control" id="save_title" required value="<?php echo $user_data->display_name.'- all day';?>">
            <input type="hidden" class="form-control" id="event_date">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="save">Save</button>
        </div>
      </div>
    </div>
</div>