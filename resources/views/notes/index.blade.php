@php($title = 'إدارة الملاحظات')
@extends('layouts.dash')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">إدارة الملاحظات</h1>
      <p class="text-gray-600">مراجعة وإدارة ملاحظات المستخدمين على الدورات</p>
    </div>
    <x-admin.button variant="primary" href="{{ route('notes.create') }}">
      <i class="ti ti-plus mr-2"></i>
      ملاحظة جديدة
    </x-admin.button>
  </div>

  <!-- Notes List -->
  <x-admin.card title="الملاحظات" subtitle="جميع الملاحظات" icon="sticky-note" iconColor="green">
    @if($notes->count() > 0)
      <div class="space-y-4">
        @foreach($notes as $note)
          <div class="flex items-start space-x-4 rtl:space-x-reverse p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
              <i class="ti ti-sticky-note text-green-600"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-gray-900">{{ $note->title }}</h4>
                <span class="text-sm text-gray-500">{{ $note->created_at->diffForHumans() }}</span>
              </div>
              <p class="text-sm text-gray-600 mb-2">{{ Str::limit($note->content, 150) }}</p>
              <p class="text-xs text-gray-500">بواسطة: {{ optional($note->user)->name ?? 'مستخدم غير معروف' }} | على دورة: {{ optional($note->course)->title ?? 'دورة غير معروفة' }}</p>
            </div>
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
              <x-admin.button variant="outline" size="sm" href="{{ route('notes.show', $note) }}">
                عرض
              </x-admin.button>
              <x-admin.button variant="outline" size="sm" href="{{ route('notes.edit', $note) }}">
                تعديل
              </x-admin.button>
              <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <x-admin.button variant="outline" size="sm" type="submit" onclick="return confirm('هل أنت متأكد من حذف هذه الملاحظة؟')">
                  حذف
                </x-admin.button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
      
      <!-- Pagination -->
      <div class="mt-6">
        {{ $notes->links() }}
      </div>
    @else
      <div class="text-center py-12">
        <i class="ti ti-sticky-note text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد ملاحظات</h3>
        <p class="text-gray-600">لم يتم إضافة أي ملاحظات بعد</p>
      </div>
    @endif
  </x-admin.card>
</div>
@endsection
