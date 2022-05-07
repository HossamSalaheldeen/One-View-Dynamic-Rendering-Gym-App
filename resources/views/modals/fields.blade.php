@foreach($fields as $field)
    @switch($field['element'])
        @case('select')
        <div class="col col-6 mb-3">
            <label for="{{$field['name']}}-selector-{{isset($resourceObject) ? $resourceObject->id : 0}}"
                   class="form-label">
                @if ($field['type'] == 'multiple')
                    {{Str::ucfirst(Str::replace('-',' ',Str::kebab(Str::plural($field['name']))))}}
                @else
                    {{Str::ucfirst($field['name'])}}
                @endif
                @if ($isEdit)
                    @if (Str::contains($field['required'], 'edit'))
                        <span class="text-danger">*</span>
                    @endif
                @else
                    @if (Str::contains($field['required'], 'create'))
                        <span class="text-danger">*</span>
                    @endif
                @endif
            </label>
            <div class="input-container">
                <select id="{{$field['name']}}-selector-{{isset($resourceObject) ? $resourceObject->id : 0}}"
                        data-name="{{$field['name']}}"
                        data-plural="{{Str::kebab(Str::plural($field['name']))}}"
                        class="form-control selectors" aria-label="Default select"
                        @if ($field['type'] == 'multiple')
                        multiple
                        name="{{Str::plural($field['name'])}}[]"
                        data-placeholder="{{Str::ucfirst(Str::replace('-',' ',Str::kebab(Str::plural($field['name']))))}}"
                        @else
                        name="{{$field['name']}}_id"
                        data-placeholder="{{Str::ucfirst($field['name'])}}"
                        @endif
                        data-width="100%">
                    @if (isset($resourceObject))
                        @if ($field['type'] == 'multiple')
                            @foreach($resourceObject->{Str::plural($field['name'])} as $option)
                                <option value="{{$option->id}}" selected>{{$option->name}}</option>
                            @endforeach
                        @else
                            <option value="{{ $resourceObject->{$field['name']}->id }}">
                                {{ $resourceObject->{$field['name']}->name }}
                            </option>
                        @endif
                    @endif

                </select>
            </div>
        </div>
        @break

        @default
        <div class="col col-6 mb-3">
            <label
                for="{{$field['name']}}_{{isset($resourceObject) ? $resourceObject->id : 0}}">{{Str::ucfirst($field['name'])}}
                @if ($isEdit)
                    @if (Str::contains($field['required'], 'edit'))
                        <span class="text-danger">*</span>
                    @endif
                @else
                    @if (Str::contains($field['required'], 'create'))
                        <span class="text-danger">*</span>
                    @endif
                @endif
            </label>
            <div class="input-container">
                @switch($field['type'])
                    @case('file')
                    <input type="file" class="dropify" name="{{$field['name']}}"
                           id="{{$field['name']}}-{{isset($resourceObject) ? $resourceObject->id : 0}}"
                            value="{{isset($resourceObject) ? $resourceObject->{$field['name']} : ''}}"
                           data-default-file="{{  isset($resourceObject)
                                            ? isset($resourceObject->{$field['name']})
                                            ? $resourceObject->{$field['name']}
                                            : asset($field['default'])
                                            : asset($field['default'])}}"
                    />
                    @break

                    @case('radio')
                    @if (array_key_exists('options', $field))
                        @foreach($field['options'] as $option)
                            <div class="form-check">
                                <input type="{{$field['type']}}"
                                       id="{{$option['value']}}-{{isset($resourceObject) ? $resourceObject->id : 0}}"
                                       name="{{$field['name']}}[]" value="{{$option['value']}}" class="form-check-input"
                                    {{isset($resourceObject) ? $resourceObject->gender == $option['value'] ? 'checked' : '' : ''}}>
                                <label for="{{$option['value']}}-{{isset($resourceObject) ? $resourceObject->id : 0}}"
                                       class="form-check-label">{{Str::ucfirst($option['value'])}}</label>
                            </div>
                        @endforeach
                    @endif
                    @break
                    @default
                    <input
                        type="{{$field['type']}}" class="form-control" name="{{$field['name']}}"
                        id="{{$field['name']}}-{{isset($resourceObject) ? $resourceObject->id : 0}}"
                        placeholder="{{Str::ucfirst($field['name'])}}" minlength="1" maxlength="100"
                        data-width="100%"
                        value="{{isset($resourceObject->{$field['name']}) ? $resourceObject->{$field['name']} : null }}"
                        @isset($resourceModel)
                        @if(array_key_exists('authorization', $field))
                        @cannot('change', $resourceModel)
                        disabled
                        @endcannot

                        @endif
                        @endisset
                    />
                @endswitch
            </div>
        </div>
    @endswitch
@endforeach



