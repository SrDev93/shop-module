@if($category->children->count() > 0)
    <ol class="dd-list">
        @foreach($category->children as $category)
            <li class="dd-item" data-id="{{ $category->id }}">
                <div class="dd-handle">{{ $category->name }}</div>
                <div class="btn-inline">
{{--                    <a class="btn btn-primary" href="{{ route('NewsCategory.edit', $category->id) }}" title="ویرایش"><i class="fa fa-edit"></i></a>--}}
{{--                    <form action="{{ route('NewsCategory.destroy', $category->id) }}" method="post" class="delete-form">--}}
{{--                        <button class="delete btn btn-danger" onclick="return confirm('برای حذف اطمینان دارید؟')"><i class="fa fa-trash"></i></button>--}}
{{--                        @csrf--}}
{{--                        @method('DELETE')--}}
{{--                    </form>--}}
                </div>
                @include('blogs::category.each', $category)
            </li>
        @endforeach
    </ol>
@endif
