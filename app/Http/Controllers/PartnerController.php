<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('sort_order')->latest()->paginate(20);

        return view('partners.index', compact('partners'));
    }

    public function create()
    {
        return view('partners.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'website' => 'nullable|url|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0',
            ], [
                'name.required' => 'اسم الشريك مطلوب',
                'name.max' => 'اسم الشريك يجب أن يكون أقل من 255 حرف',
                'website.url' => 'رابط الموقع الإلكتروني غير صحيح - يجب أن يبدأ بـ http:// أو https://',
                'website.max' => 'رابط الموقع الإلكتروني طويل جداً',
                'image.image' => 'يجب أن يكون الملف صورة',
                'image.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif',
                'image.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
                'sort_order.integer' => 'ترتيب العرض يجب أن يكون رقم',
                'sort_order.min' => 'ترتيب العرض يجب أن يكون أكبر من أو يساوي 0',
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'partner_'.time().'_'.Str::random(10).'.'.$image->getClientOriginalExtension();
                $imagePath = 'images/partners/';

                // Create directory if it doesn't exist
                if (! File::exists(public_path($imagePath))) {
                    File::makeDirectory(public_path($imagePath), 0755, true);
                }

                $image->move(public_path($imagePath), $imageName);
                $validated['image'] = $imagePath.$imageName;
            }

            $validated['is_active'] = $request->boolean('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            $partner = Partner::create($validated);

            return redirect()->route('partners.show', $partner)
                ->with('success', 'تم إضافة الشريك بنجاح!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'يرجى تصحيح الأخطاء أدناه');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الشريك: '.$e->getMessage());
        }
    }

    public function show(Partner $partner)
    {
        return view('partners.show', compact('partner'));
    }

    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'website' => 'nullable|url|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0',
            ], [
                'name.required' => 'اسم الشريك مطلوب',
                'name.max' => 'اسم الشريك يجب أن يكون أقل من 255 حرف',
                'website.url' => 'رابط الموقع الإلكتروني غير صحيح - يجب أن يبدأ بـ http:// أو https://',
                'website.max' => 'رابط الموقع الإلكتروني طويل جداً',
                'image.image' => 'يجب أن يكون الملف صورة',
                'image.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif',
                'image.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
                'sort_order.integer' => 'ترتيب العرض يجب أن يكون رقم',
                'sort_order.min' => 'ترتيب العرض يجب أن يكون أكبر من أو يساوي 0',
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($partner->image && File::exists(public_path($partner->image))) {
                    File::delete(public_path($partner->image));
                }

                $image = $request->file('image');
                $imageName = 'partner_'.time().'_'.Str::random(10).'.'.$image->getClientOriginalExtension();
                $imagePath = 'images/partners/';

                // Create directory if it doesn't exist
                if (! File::exists(public_path($imagePath))) {
                    File::makeDirectory(public_path($imagePath), 0755, true);
                }

                $image->move(public_path($imagePath), $imageName);
                $validated['image'] = $imagePath.$imageName;
            }

            $validated['is_active'] = $request->boolean('is_active');

            $partner->update($validated);

            return redirect()->route('partners.show', $partner)
                ->with('success', 'تم تحديث الشريك بنجاح!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'يرجى تصحيح الأخطاء أدناه');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الشريك: '.$e->getMessage());
        }
    }

    public function destroy(Partner $partner)
    {
        try {
            // Delete image if exists
            if ($partner->image && File::exists(public_path($partner->image))) {
                File::delete(public_path($partner->image));
            }

            $partner->delete();

            return redirect()->route('partners.index')
                ->with('success', 'تم حذف الشريك بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الشريك: '.$e->getMessage());
        }
    }
}
