@php($title = 'إدارة التعليقات')
@extends('layouts.dash')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">إدارة التعليقات</h1>
      <p class="text-gray-600">مراجعة وإدارة تعليقات المستخدمين على الدورات</p>
    </div>
    <x-admin.button variant="primary" href="{{ route('comments.create') }}">
      <i class="ti ti-plus mr-2"></i>
      تعليق جديد
    </x-admin.button>
  </div>

  <!-- Comments List -->
  <x-admin.card title="التعليقات" subtitle="جميع التعليقات" icon="message-circle" iconColor="blue">
    @if($comments->count() > 0)
      <div class="space-y-4">
        @foreach($comments as $comment)
          <div class="flex items-start space-x-4 rtl:space-x-reverse p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
              <i class="ti ti-user text-blue-600"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-gray-900">{{ optional($comment->user)->name ?? 'مستخدم غير معروف' }}</h4>
                <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
              </div>
              <p class="text-sm text-gray-600 mb-2">{{ $comment->content }}</p>
              <p class="text-xs text-gray-500">على دورة: {{ optional($comment->course)->title ?? 'دورة غير معروفة' }}</p>
            </div>
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
              <x-admin.button variant="outline" size="sm" href="{{ route('comments.show', $comment) }}">
                عرض
              </x-admin.button>
              <x-admin.button variant="outline" size="sm" href="{{ route('comments.edit', $comment) }}">
                تعديل
              </x-admin.button>
              <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <x-admin.button variant="outline" size="sm" type="submit" onclick="return confirm('هل أنت متأكد من حذف هذا التعليق؟')">
                  حذف
                </x-admin.button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
      
      <!-- Pagination -->
      <div class="mt-6">
        {{ $comments->links() }}
      </div>
    @else
      <div class="text-center py-12">
        <i class="ti ti-message-circle text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد تعليقات</h3>
        <p class="text-gray-600">لم يتم إضافة أي تعليقات بعد</p>
      </div>
    @endif
  </x-admin.card>
</div>
@endsection
