<div class="d-flex justify-content-around">
    @isset($showRoles)
    @role($showRoles)
    <button type="button" class="button ajax-start show-btn"
            data-url="{{route($resource.'.show',$resourceId)}}"
            data-id="{{$resourceId}}">
        {{--        <i class="fas fa-eye" ></i>--}}
        <span class="button__text">Show</span>
    </button>
    @endrole
    @endisset

    @isset($editRoles)
    @role($editRoles)
    <button type="button" class="button ajax-start edit-btn"
            data-url="{{route($resource.'.edit',$resourceId)}}"
            data-id="{{$resourceId}}">
        {{--        <i class="fas fa-pen"></i>--}}
        <span class="button__text">Edit</span>
    </button>
    @endrole
    @endisset

    @isset($deleteRoles)
    @role($deleteRoles)
    <button type="button" class="button delete-btn"
            data-url="{{route($resource.'.destroy',$resourceId)}}"
            data-id="{{$resourceId}}">
        {{--        <i class="fas fa-trash"></i>--}}
        <span class="button__text">Delete</span>
    </button>
    @endrole
    @endisset

    @isset($banRoles)
        @role($banRoles)
        <button type="button" class="button {{$isBanned ? 'unban-btn' : 'ban-btn'}} toggle-status-btn"
                data-url="{{route($resource.'.destroy',$resourceId)}}"
                data-id="{{$resourceId}}">
            <span class="button__text">{{$isBanned ? 'Unban' : 'Ban'}}</span>
        </button>
        @endrole
    @endisset

    @isset($attendRoles)
        @role($attendRoles)
        <button type="button" class="button attend-btn"
                data-url="{{route($resource.'.attend',$resourceId)}}"
                data-id="{{$resourceId}}">
            <span class="button__text">Attend</span>
        </button>
        @endrole
    @endisset


</div>
