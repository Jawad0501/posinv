@props(['status' => true])
<div class="d-flex justify-content-start">
    <span>Status:</span>
    <div class="form-check mx-3">
        <input class="form-check-input" type="radio" name="status" id="status1" value="1" @checked($status ? true : false)>
        <label class="form-check-label" for="status1">
          Active
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" id="status2" value="0" @checked(!$status ? true : false)>
        <label class="form-check-label" for="status2">
          Disable
        </label>
    </div>
</div>