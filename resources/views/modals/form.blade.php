<!-- Modal -->
<div class="modal fade" data-id="{{isset($resourceObject) ? $resourceObject->id : 0}}"
     id="{{$isEdit ? Str::of('edit-'.$resource.'-modal') : Str::of('create-'.$resource.'-modal')}}"
     data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form class="form-data" data-id="{{isset($resourceObject) ? $resourceObject->id : 0}}"
                      id="{{$isEdit ? Str::of('edit-'.$resource.'-form') : Str::of('create-'.$resource.'-form')}}"
                      action="{{$isEdit ? route($resource.'.update',$resourceObject->id) : route($resource.'.store')}}"
                      method="POST" enctype="multipart/form-data">
                    @if ($isEdit)
                        @method('PUT')
                    @endif
                    <div class="row">
                        @include('modals.fields')
                    </div>
                    <div class="buttons-container">
                        <button type="submit" class="button ajax-start save-btn">
                            <span class="button__text">{{$isEdit ? Str::of('Update') : Str::of('Add')}}</span>
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

{{--<script type="text/javascript">--}}
{{--    const resourceId = "{{$resourceObject->id}}";--}}
{{--</script>--}}
