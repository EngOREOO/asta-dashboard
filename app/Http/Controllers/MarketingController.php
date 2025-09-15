<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarketingController extends Controller
{
    public function index()
    {
        $totalStudents = User::role('student')->count();
        $totalInstructors = User::role('instructor')->count();
        $totalUsers = User::count();
        
        // Get recent campaigns (if we had a campaigns table)
        $recentCampaigns = collect(); // Placeholder for future campaigns table
        
        return view('marketing.index', compact(
            'totalStudents',
            'totalInstructors', 
            'totalUsers',
            'recentCampaigns'
        ));
    }

    public function create()
    {
        $students = User::role('student')->select('id', 'name', 'email')->get();
        $instructors = User::role('instructor')->select('id', 'name', 'email')->get();
        
        return view('marketing.create', compact('students', 'instructors'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'required|in:students,instructors,all',
            'content_type' => 'required|in:text,html',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max per file
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get recipients based on selection
            $recipients = $this->getRecipients($request->recipients);
            
            // Handle attachments
            $attachments = $this->handleAttachments($request);
            
            // Send emails
            $sentCount = $this->sendEmails($request, $recipients, $attachments);
            
            return redirect()->route('marketing.index')
                ->with('success', "تم إرسال {$sentCount} رسالة بنجاح");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إرسال الرسائل: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function preview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'content_type' => 'required|in:text,html',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'البيانات غير صحيحة'], 400);
        }

        return view('marketing.preview', [
            'subject' => $request->subject,
            'content' => $request->content,
            'content_type' => $request->content_type,
        ]);
    }

    public function templates()
    {
        $templates = [
            'welcome' => [
                'name' => 'رسالة ترحيب',
                'subject' => 'مرحباً بك في منصة أستا التعليمية',
                'content' => $this->getWelcomeTemplate()
            ],
            'course_announcement' => [
                'name' => 'إعلان دورة جديدة',
                'subject' => 'دورة جديدة متاحة الآن!',
                'content' => $this->getCourseAnnouncementTemplate()
            ],
            'newsletter' => [
                'name' => 'النشرة الإخبارية',
                'subject' => 'النشرة الإخبارية الشهرية',
                'content' => $this->getNewsletterTemplate()
            ],
            'promotion' => [
                'name' => 'عرض ترويجي',
                'subject' => 'عرض خاص - خصم 50%',
                'content' => $this->getPromotionTemplate()
            ]
        ];

        return view('marketing.templates', compact('templates'));
    }

    public function analytics()
    {
        // Placeholder for email analytics
        $stats = [
            'total_sent' => 0,
            'total_delivered' => 0,
            'total_opened' => 0,
            'total_clicked' => 0,
            'open_rate' => 0,
            'click_rate' => 0,
        ];

        return view('marketing.analytics', compact('stats'));
    }

    private function getRecipients(array $recipientTypes)
    {
        $recipients = collect();
        
        foreach ($recipientTypes as $type) {
            switch ($type) {
                case 'students':
                    $recipients = $recipients->merge(User::role('student')->get());
                    break;
                case 'instructors':
                    $recipients = $recipients->merge(User::role('instructor')->get());
                    break;
                case 'all':
                    $recipients = $recipients->merge(User::all());
                    break;
            }
        }
        
        return $recipients->unique('id');
    }

    private function handleAttachments(Request $request)
    {
        $attachments = [];
        
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('marketing/attachments', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType()
                ];
            }
        }
        
        return $attachments;
    }

    private function sendEmails(Request $request, $recipients, $attachments)
    {
        $sentCount = 0;
        
        foreach ($recipients as $recipient) {
            try {
                Mail::send([], [], function ($message) use ($request, $recipient, $attachments) {
                    $message->to($recipient->email, $recipient->name)
                        ->subject($request->subject);
                    
                    if ($request->content_type === 'html') {
                        $message->html($request->content);
                    } else {
                        $message->text($request->content);
                    }
                    
                    // Add attachments
                    foreach ($attachments as $attachment) {
                        $message->attach(Storage::disk('public')->path($attachment['path']), [
                            'as' => $attachment['name'],
                            'mime' => $attachment['mime']
                        ]);
                    }
                });
                
                $sentCount++;
                
                // Add small delay to prevent overwhelming the mail server
                usleep(100000); // 0.1 second delay
                
            } catch (\Exception $e) {
                \Log::error("Failed to send email to {$recipient->email}: " . $e->getMessage());
            }
        }
        
        return $sentCount;
    }

    private function getWelcomeTemplate()
    {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: #2563eb;">مرحباً بك في منصة أستا التعليمية</h1>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <p style="font-size: 16px; line-height: 1.6; color: #374151;">
                    نرحب بك في منصة أستا التعليمية! نحن سعداء لانضمامك إلى مجتمعنا التعليمي.
                </p>
                
                <p style="font-size: 16px; line-height: 1.6; color: #374151;">
                    يمكنك الآن الاستفادة من:
                </p>
                
                <ul style="color: #374151;">
                    <li>دورات تعليمية متنوعة</li>
                    <li>شهادات معتمدة</li>
                    <li>محتوى تفاعلي</li>
                    <li>دعم فني متواصل</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#" style="background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
                    ابدأ رحلتك التعليمية
                </a>
            </div>
        </div>';
    }

    private function getCourseAnnouncementTemplate()
    {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: #2563eb;">دورة جديدة متاحة الآن!</h1>
            </div>
            
            <div style="background: #f0f9ff; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <h2 style="color: #1e40af; margin-bottom: 15px;">عنوان الدورة</h2>
                <p style="font-size: 16px; line-height: 1.6; color: #374151;">
                    وصف الدورة الجديدة ومحتوياتها التعليمية...
                </p>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#" style="background: #059669; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
                    سجل الآن
                </a>
            </div>
        </div>';
    }

    private function getNewsletterTemplate()
    {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: #2563eb;">النشرة الإخبارية الشهرية</h1>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <h2 style="color: #1e40af;">أهم الأخبار هذا الشهر</h2>
                <p style="font-size: 16px; line-height: 1.6; color: #374151;">
                    محتوى النشرة الإخبارية...
                </p>
            </div>
        </div>';
    }

    private function getPromotionTemplate()
    {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: #dc2626;">عرض خاص - خصم 50%</h1>
            </div>
            
            <div style="background: #fef2f2; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <p style="font-size: 18px; line-height: 1.6; color: #374151; text-align: center;">
                    استفد من خصم 50% على جميع الدورات لفترة محدودة!
                </p>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#" style="background: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
                    احصل على الخصم الآن
                </a>
            </div>
        </div>';
    }
}