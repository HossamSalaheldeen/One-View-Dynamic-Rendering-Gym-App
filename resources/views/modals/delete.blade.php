<!-- Modal -->
<div class="modal fade alert_modal" id="delete-modal" data-backdrop="static" tabindex="-1" aria-labelledby="delete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form action="" class="py-3 d-flex flex-column align-items-center justify-content-center">
                    <div class="mb-3">
{{--                        <img src="{{asset('/images/delete.svg')}}">--}}
                        <h5 class="fw-bold">Are you sure that you want to delete this ?</h5>
                    </div>
                    <div class="w-100 buttons-container">
                        <button type="button" class="button delete-btn ajax-start confirm-delete-btn" data-url="">
                            <span class="button__text">Delete</span>
                        </button>
                        <button type="button" class="button cancel-btn" data-dismiss="modal" aria-label="Close">
                            <span class="button__text">Cancel</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
