<div id="updateModal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm"> 
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Event Settings</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>   
        <div class="modal-body">
          <div class="form-group">
            <label for="usr">Title:</label>
            <input type="text" class="form-control" id="update_title">
            <input type="hidden" class="form-control" id="event_date">
            <input type="hidden" class="form-control" id="event_id">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="update">update</button>
          <button type="submit" class="btn btn-danger" id="delete">delete</button>
        </div>
      </div>
    </div>
</div>
