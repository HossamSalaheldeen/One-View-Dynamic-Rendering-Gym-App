<!-- Modal -->
<div class="modal fade" id="show-{{$resource}}-modal" data-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    @foreach($attributes as $attribute)
                        <div class="col-6 mb-3">
                            @switch($attribute)
                                @case('avatar')
                                @case('cover')
                                <div class="image"><img src="{{$resourceObject->$attribute}}"
                                                        class="img-circle elevation-2"
                                                        width="200" height="200" alt="User Avatar"></div>
                                @break
                                @default
                                <div>
                                    <p>{{Str::ucfirst(Str::replace('_',' ',$attribute))}} :
                                @if ($resourceObject->$attribute)
                                    @if (is_string($resourceObject->$attribute) || is_int($resourceObject->$attribute))
                                            <span>{{$resourceObject->$attribute}}</span>
                                    @else
                                        @foreach($resourceObject->$attribute as $item)
                                                    <span>{{$item}}</span>@if(!$loop->last) ,@endif
                                        @endforeach
                                    @endif

                                @endif
                                    </p>
                                </div>

                            @endswitch
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="button ok-btn" data-dismiss="modal" aria-label="Close">
                        <span class="button__text">Ok</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
