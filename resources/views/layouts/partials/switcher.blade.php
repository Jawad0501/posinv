<div class="switcher-wrapper">
    <div class="switcher-btn">
        <i class="fa-solid fa-gear fa-spin"></i>
    </div>
    <div class="switcher-body">

        <div class="d-flex align-items-center">
            <p class="fs-16 mb-0 text-uppercase fw-bolder">Theme Customizer</p>
            <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
        </div>
        <hr />

        <p class="fs-16 fw-bolder">Theme Styles</p>
        <div class="d-flex align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="changeTheme" id="lightmode" value="light" checked>
                <label class="form-check-label" for="lightmode">Light</label>
            </div>
            <div class="form-check ms-3">
                <input class="form-check-input" type="radio" name="changeTheme" id="darkmode" value="dark">
                <label class="form-check-label" for="darkmode">Dark</label>
            </div>
        </div>
        <br />

        @if (Request::is('staff/pos'))
            <p class="fs-16 fw-bolder">Product View</p>
            <div class="d-flex align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="productView" id="list" value="list" />
                    <label class="form-check-label" for="list">List</label>
                </div>
                <div class="form-check ms-3">
                    <input class="form-check-input" type="radio" name="productView" id="grid" value="grid"
                        checked />
                    <label class="form-check-label" for="grid">Grid</label>
                </div>
            </div>
        @endif
    </div>
</div>
