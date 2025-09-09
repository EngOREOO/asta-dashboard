<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentQuestion;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\FileUpload;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
{
    /**
     * Get instructor's courses with status
     */
    public function myCourses()
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        $courses = Course::where('instructor_id', $user->id)
            ->with(['category', 'degree', 'students'])
            ->withCount(['materials', 'students', 'reviews'])
            ->latest()
            ->get();

        $formattedCourses = $courses->map(function ($course) {
            // Determine status based on course status and completion
            $status = $this->getCourseStatus($course);

            return [
                'status' => $status,
                'company' => 'Microsoft Corp.', // You can make this dynamic based on instructor's company
                'course_name_ar' => $course->title,
                'rating' => $course->average_rating ?? 0,
                'price' => $course->price ?? 0,
                'course_id' => $course->id,
                'total_students' => $course->students_count,
                'total_materials' => $course->materials_count,
                'total_reviews' => $course->reviews_count,
                'created_at' => $course->created_at->format('Y-m-d'),
                'updated_at' => $course->updated_at->format('Y-m-d'),
                // Add course image and all course data
                'thumbnail' => $course->thumbnail_url,
                'description' => $course->description,
                'difficulty_level' => $course->difficulty_level,
                'language' => $course->language,
                'slug' => $course->slug,
                'status' => $course->status,
                'rejection_reason' => $course->rejection_reason,
                'category' => $course->category ? [
                    'id' => $course->category->id,
                    'name' => $course->category->name,
                    'slug' => $course->category->slug,
                ] : null,
                'degree' => $course->degree ? [
                    'id' => $course->degree->id,
                    'name' => $course->degree->name,
                    'slug' => $course->degree->slug,
                ] : null,
            ];
        });

        return response()->json($formattedCourses);
    }

    /**
     * Get instructor's course statistics
     */
    public function courseStats()
    {
        $user = auth('sanctum')->user();

        $totalCourses = Course::where('instructor_id', $user->id)->count();
        $publishedCourses = Course::where('instructor_id', $user->id)->where('status', 'approved')->count();
        $pendingCourses = Course::where('instructor_id', $user->id)->where('status', 'pending')->count();
        $totalStudents = Course::where('instructor_id', $user->id)
            ->withCount('students')
            ->get()
            ->sum('students_count');

        $totalRevenue = Course::where('instructor_id', $user->id)
            ->withCount('students')
            ->get()
            ->sum(function ($course) {
                return $course->price * $course->students_count;
            });

        $averageRating = Course::where('instructor_id', $user->id)
            ->whereNotNull('average_rating')
            ->avg('average_rating') ?? 0;

        return response()->json([
            'total_courses' => $totalCourses,
            'published_courses' => $totalCourses,
            'pending_courses' => $pendingCourses,
            'total_students' => $totalStudents,
            'total_revenue' => $totalRevenue,
            'average_rating' => round($averageRating, 2),
        ]);
    }

    /**
     * Get course analytics
     */
    public function courseAnalytics(Course $course)
    {
        $user = auth('sanctum')->user();

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only view analytics for courses that you created. This course belongs to another instructor.',
                'error' => 'course_access_denied',
                'course_id' => $course->id,
                'course_title' => $course->title,
                'instructor_id' => $course->instructor_id,
                'your_id' => $user->id,
            ], 403);
        }

        // Load course with relationships
        $course->load(['students', 'materials', 'reviews']);

        // Calculate enrollment trends
        $enrollmentTrends = DB::table('course_user')
            ->selectRaw('DATE(enrolled_at) as date, COUNT(*) as enrollments')
            ->where('course_id', $course->id)
            ->groupBy(DB::raw('DATE(enrolled_at)'))
            ->orderBy('date')
            ->get();

        // Calculate material completion rates
        $materialCompletionRates = [];
        foreach ($course->materials as $material) {
            $totalStudents = $course->students()->count();
            $completedStudents = \App\Models\CourseMaterialProgress::where('course_material_id', $material->id)
                ->whereNotNull('completed_at')
                ->count();

            $completionRate = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100, 2) : 0;

            $materialCompletionRates[] = [
                'material_id' => $material->id,
                'title' => $material->title,
                'total_students' => $totalStudents,
                'completed_students' => $completedStudents,
                'completion_rate' => $completionRate,
            ];
        }

        // Calculate assessment statistics
        $assessments = Assessment::where('course_id', $course->id)->with('attempts')->get();
        $assessmentStats = [];

        foreach ($assessments as $assessment) {
            $totalAttempts = $assessment->attempts()->count();
            $completedAttempts = $assessment->attempts()->where('status', 'completed')->count();
            $averageScore = $assessment->attempts()->where('status', 'completed')->avg('score') ?? 0;

            $assessmentStats[] = [
                'assessment_id' => $assessment->id,
                'title' => $assessment->title,
                'total_attempts' => $totalAttempts,
                'completed_attempts' => $completedAttempts,
                'average_score' => round($averageScore, 2),
            ];
        }

        return response()->json([
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'total_students' => $course->students()->count(),
                'total_materials' => $course->materials()->count(),
                'average_rating' => $course->average_rating ?? 0,
                'total_reviews' => $course->reviews()->count(),
            ],
            'enrollment_trends' => $enrollmentTrends,
            'material_completion_rates' => $materialCompletionRates,
            'assessment_statistics' => $assessmentStats,
            'recent_reviews' => $course->reviews()->with('user:id,name')->latest()->take(5)->get(),
        ]);
    }

    /**
     * Get student progress for a course
     */
    public function studentProgress(Course $course)
    {
        $user = auth('sanctum')->user();

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only view student progress for courses that you created. This course belongs to another instructor.',
                'error' => 'course_access_denied',
                // 'course_id' => $course->id,
                // 'course_title' => $course->title,
                // 'instructor_id' => $course->instructor_id,
                // 'your_id' => $user->id
            ], 403);
        }

        $students = $course->students()
            ->with(['materialCompletions' => function ($query) use ($course) {
                $query->whereIn('course_material_id', $course->materials()->pluck('id'));
            }])
            ->get()
            ->map(function ($student) use ($course) {
                $totalMaterials = $course->materials()->count();
                $completedMaterials = $student->materialCompletions()
                    ->whereIn('course_material_id', $course->materials()->pluck('id'))
                    ->whereNotNull('completed_at')
                    ->count();

                $progress = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100, 2) : 0;

                return [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'student_email' => $student->email,
                    'enrolled_at' => $student->pivot->enrolled_at,
                    'total_materials' => $totalMaterials,
                    'completed_materials' => $completedMaterials,
                    'progress_percentage' => $progress,
                    'last_activity' => $student->materialCompletions()
                        ->whereIn('course_material_id', $course->materials()->pluck('id'))
                        ->latest('completed_at')
                        ->value('completed_at'),
                ];
            })
            ->sortByDesc('progress_percentage');

        return response()->json([
            'course_id' => $course->id,
            'course_title' => $course->title,
            'total_students' => $students->count(),
            'students' => $students->values(),
        ]);
    }

    /**
     * Get instructor main dashboard data
     */
    public function dashboard()
    {
        $instructor = auth('sanctum')->user();

        // Get instructor's courses
        $courses = $instructor->courses()->with(['students', 'reviews'])->get();

        // Calculate quick stats
        $totalStudents = $courses->sum(function ($course) {
            return $course->students()->count();
        });

        $coursesAdded = $courses->count();
        $coursesUnderReview = $courses->where('status', 'pending')->count();

        // New students this month
        $newStudentsThisMonth = $courses->sum(function ($course) {
            return $course->students()
                ->where('course_user.created_at', '>=', now()->startOfMonth())
                ->count();
        });

        // Calculate ratings
        $instructorRating = $instructor->instructorRatings()->avg('rating') ?? 0;
        $coursesRating = $courses->avg('average_rating') ?? 0;

        // Calculate earnings (assuming 70% instructor share)
        $totalEarnings = $courses->sum(function ($course) {
            return $course->students()->count() * $course->price * 0.7;
        });

        $withdrawableBalance = $totalEarnings * 0.8; // 80% of total earnings
        $withdrawalUnderReview = $totalEarnings * 0.2; // 20% under review

        // Get earnings by year for growth chart
        $earningsByYear = $this->getEarningsByYear($instructor);
        $highestEarningYear = $earningsByYear->sortByDesc('earnings')->first();

        // Get completion rate data
        $completionData = $this->getCompletionRateData($courses);

        // Get timeline events (courses with dates)
        $timelineEvents = $this->getTimelineEvents($courses);

        // Return the new format exactly as requested
        return response()->json([
            'user' => [
                'name' => $instructor->name ?? 'د. محمد إبراهيم',
            ],
            'courses' => [
                'under_review' => $coursesUnderReview,
                'added' => $coursesAdded,
                'students_count' => $totalStudents,
                'new_students_this_month' => $newStudentsThisMonth,
            ],
            'ratings' => [
                'courses_rating' => round($coursesRating, 1),
                'instructor_rating' => round($instructorRating, 1),
            ],
            'profits' => [
                'growth_chart' => [
                    'unit' => 'Yearly',
                    'years' => $earningsByYear->pluck('year')->values()->toArray(),
                    'earnings' => $earningsByYear->map(function ($yearData) {
                        return [
                            'year' => $yearData['year'],
                            'earnings' => $yearData['earnings'],
                        ];
                    })->values()->toArray(),
                ],
                'highest_year' => [
                    'year' => $highestEarningYear ? $highestEarningYear['year'] : date('Y'),
                    'sales' => $highestEarningYear ? number_format($highestEarningYear['earnings'] / 1000, 0).'K' : '0K',
                ],
                'highest_month' => [
                    'month' => $this->getHighestEarningMonth($instructor),
                    'year' => date('Y'),
                ],
                'pending_withdrawal' => round($withdrawalUnderReview, 0),
                'available_for_withdrawal' => round($withdrawableBalance, 0),
                'total_profits' => round($totalEarnings, 0),
                'currency' => 'ريال سعودي',
            ],
            'students' => [
                'completed_courses' => $completionData['completion_rate'].'%',
                'not_completed_courses' => (100 - $completionData['completion_rate']).'%',
                'total_completed' => $completionData['completed'],
            ],
            'timeline' => $timelineEvents->map(function ($event) {
                return [
                    'course_name' => $event['eventName'],
                    'date' => $event['publishDate'] ? $event['publishDate'] : $event['startDate'].' - '.$event['endDate'],
                    'status' => $event['status'],
                ];
            })->toArray(),
        ]);
    }

    /**
     * Get instructor main dashboard data - NEW FORMAT
     */
    public function dashboardNew()
    {
        $instructor = auth('sanctum')->user();

        // Get instructor's courses
        $courses = $instructor->courses()->with(['students', 'reviews'])->get();

        // Calculate quick stats
        $totalStudents = $courses->sum(function ($course) {
            return $course->students()->count();
        });

        $coursesAdded = $courses->count();
        $coursesUnderReview = $courses->where('status', 'pending')->count();

        // New students this month
        $newStudentsThisMonth = $courses->sum(function ($course) {
            return $course->students()
                ->where('course_user.created_at', '>=', now()->startOfMonth())
                ->count();
        });

        // Calculate ratings
        $instructorRating = $instructor->instructorRatings()->avg('rating') ?? 0;
        $coursesRating = $courses->avg('average_rating') ?? 0;

        // Calculate earnings (assuming 70% instructor share)
        $totalEarnings = $courses->sum(function ($course) {
            return $course->students()->count() * $course->price * 0.7;
        });

        $withdrawableBalance = $totalEarnings * 0.8; // 80% of total earnings
        $withdrawalUnderReview = $totalEarnings * 0.2; // 20% under review

        // Get earnings by year for growth chart
        $earningsByYear = $this->getEarningsByYear($instructor);
        $highestEarningYear = $earningsByYear->sortByDesc('earnings')->first();

        // Get completion rate data
        $completionData = $this->getCompletionRateData($courses);

        // Get timeline events (courses with dates)
        $timelineEvents = $this->getTimelineEvents($courses);

        // Return the new format exactly as requested
        return response()->json([
            'user' => [
                'name' => $instructor->name ?? 'د. محمد إبراهيم',
            ],
            'courses' => [
                'under_review' => $coursesUnderReview,
                'added' => $coursesAdded,
                'students_count' => $totalStudents,
                'new_students_this_month' => $newStudentsThisMonth,
            ],
            'ratings' => [
                'courses_rating' => round($coursesRating, 1),
                'instructor_rating' => round($instructorRating, 1),
            ],
            'profits' => [
                'growth_chart' => [
                    'unit' => 'Yearly',
                    'years' => $earningsByYear->pluck('year')->values()->toArray(),
                ],
                'highest_year' => [
                    'year' => $highestEarningYear ? $highestEarningYear['year'] : date('Y'),
                    'sales' => $highestEarningYear ? number_format($highestEarningYear['earnings'] / 1000, 0).'K' : '0K',
                ],
                'highest_month' => [
                    'month' => $this->getHighestEarningMonth($instructor),
                    'year' => date('Y'),
                ],
                'pending_withdrawal' => round($withdrawalUnderReview, 0),
                'available_for_withdrawal' => round($withdrawableBalance, 0),
                'total_profits' => round($totalEarnings, 0),
                'currency' => 'ريال سعودي',
            ],
            'students' => [
                'completed_courses' => $completionData['completion_rate'].'%',
                'not_completed_courses' => (100 - $completionData['completion_rate']).'%',
                'total_completed' => $completionData['completed'],
            ],
            'timeline' => $timelineEvents->map(function ($event) {
                return [
                    'course_name' => $event['eventName'],
                    'date' => $event['publishDate'] ? $event['publishDate'] : $event['startDate'].' - '.$event['endDate'],
                    'status' => $event['status'],
                ];
            })->toArray(),
        ]);
    }

    /**
     * Get student details for instructor
     */
    public function getStudentDetails($studentId)
    {
        $instructor = auth('sanctum')->user();

        // Get student enrolled in instructor's courses
        $student = User::whereHas('enrolledCourses', function ($query) use ($instructor) {
            $query->where('instructor_id', $instructor->id);
        })->with([
            'enrolledCourses' => function ($query) use ($instructor) {
                $query->where('instructor_id', $instructor->id)
                    ->with(['materials', 'quizzes']);
            },
            'courseMaterialProgress' => function ($query) use ($instructor) {
                $query->whereHas('material', function ($subQuery) use ($instructor) {
                    $subQuery->whereHas('course', function ($courseQuery) use ($instructor) {
                        $courseQuery->where('instructor_id', $instructor->id);
                    });
                });
            },
        ])->find($studentId);

        if (! $student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // Calculate course progress and grades
        $enrolledCourses = $student->enrolledCourses->map(function ($course) use ($student) {
            $totalMaterials = $course->materials()->count();
            $completedMaterials = $course->materials()
                ->whereHas('progress', function ($query) use ($student) {
                    $query->where('user_id', $student->id)
                        ->whereNotNull('completed_at');
                })->count();

            $progress = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100) : 0;

            // Calculate grade based on quizzes
            $quizzes = $course->quizzes;
            $totalScore = 0;
            $totalAttempts = 0;

            foreach ($quizzes as $quiz) {
                $attempt = $quiz->attempts()
                    ->where('user_id', $student->id)
                    ->latest()
                    ->first();

                if ($attempt) {
                    $totalScore += $attempt->score;
                    $totalAttempts++;
                }
            }

            $averageScore = $totalAttempts > 0 ? $totalScore / $totalAttempts : 0;
            $grade = $this->calculateGrade($averageScore);

            return [
                'courseId' => $course->id,
                'courseName' => $course->title,
                'progress' => $progress,
                'status' => $progress >= 100 ? 'Completed' : 'In Progress',
                'grade' => $grade,
            ];
        });

        // Calculate total watch time (in hours) - Note: watch_time column doesn't exist
        $totalWatchTime = 0; // Placeholder since watch_time column is not available

        // Get instructor rating from student
        $instructorRating = $instructor->instructorRatings()
            ->where('user_id', $student->id)
            ->first();

        return response()->json([
            'studentId' => $student->id,
            'name' => $student->name,
            'profileImageUrl' => $student->profile_photo_path,
            'country' => $student->country ?? 'مصر',
            'enrollmentDate' => $student->enrolledCourses->first()->pivot->created_at ?? $student->created_at,
            'enrollmentDateFormatted' => ($student->enrolledCourses->first()->pivot->created_at ?? $student->created_at)->format('j/n/Y'),
            'lastActivity' => $student->courseMaterialProgress()
                ->whereHas('material', function ($query) use ($instructor) {
                    $query->whereHas('course', function ($courseQuery) use ($instructor) {
                        $courseQuery->where('instructor_id', $instructor->id);
                    });
                })
                ->latest()
                ->first()->updated_at ?? $student->updated_at,
            'details' => [
                'coursesCount' => $enrolledCourses->count(),
                'birthDate' => $student->birth_date ?? '2004-01-25',
                'ratingForInstructor' => $instructorRating ? $instructorRating->rating : 5,
                'totalWatchTimeHours' => round($totalWatchTime, 1),
            ],
            'enrolledCourses' => $enrolledCourses->map(function ($course) use ($instructor) {
                return [
                    'courseId' => $course['courseId'],
                    'courseName' => $course['courseName'],
                    'instructorName' => $instructor->name ?? 'اسم المدرب',
                    'courseImage' => $course->thumbnail ?? null,
                    'progress' => $course['progress'],
                    'status' => $course['status'],
                    'grade' => $course['grade'],
                    'isLinkedToCurrentInstructor' => true,
                ];
            }),
        ]);
    }

    /**
     * Get students list for instructor
     */
    public function getStudentsList(Request $request)
    {
        $instructor = auth('sanctum')->user();

        $query = User::whereHas('enrolledCourses', function ($query) use ($instructor) {
            $query->where('instructor_id', $instructor->id);
        })->with([
            'enrolledCourses' => function ($query) use ($instructor) {
                $query->where('instructor_id', $instructor->id);
            },
            'courseMaterialProgress' => function ($query) use ($instructor) {
                $query->whereHas('material', function ($subQuery) use ($instructor) {
                    $subQuery->whereHas('course', function ($courseQuery) use ($instructor) {
                        $courseQuery->where('instructor_id', $instructor->id);
                    });
                });
            },
        ]);

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('course_id')) {
            $query->whereHas('enrolledCourses', function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        $students = $query->paginate(20);

        // Transform the data
        $students->getCollection()->transform(function ($student) use ($instructor) {
            $enrolledCourses = $student->enrolledCourses;
            $totalProgress = 0;
            $totalRating = 0;
            $courseCount = $enrolledCourses->count();

            foreach ($enrolledCourses as $course) {
                $totalMaterials = $course->materials()->count();
                $completedMaterials = $course->materials()
                    ->whereHas('progress', function ($query) use ($student) {
                        $query->where('user_id', $student->id)
                            ->whereNotNull('completed_at');
                    })->count();

                $progress = $totalMaterials > 0 ? ($completedMaterials / $totalMaterials) * 100 : 0;
                $totalProgress += $progress;

                // Get course rating from student
                $courseRating = $course->ratings()
                    ->where('user_id', $student->id)
                    ->first();

                if ($courseRating) {
                    $totalRating += $courseRating->rating;
                }
            }

            $averageProgress = $courseCount > 0 ? round($totalProgress / $courseCount) : 0;
            $averageRating = $courseCount > 0 ? round($totalRating / $courseCount, 1) : 0;

            // Get instructor rating from student
            $instructorRating = $instructor->instructorRatings()
                ->where('user_id', $student->id)
                ->first();

            return [
                'student_id' => $student->id,
                'studentName' => $student->name,
                'studentAvatar' => $student->profile_photo_path,
                'courseName' => $enrolledCourses->first()->title ?? 'Multiple Courses',
                'courseProgress' => $averageProgress,
                'courseRating' => $averageRating,
                'instructorRating' => $instructorRating ? $instructorRating->rating : null,
            ];
        });

        return response()->json([
            'totalStudents' => $students->total(),
            'studentsList' => $students,
        ]);
    }

    /**
     * Get instructor revenue data
     */
    public function getRevenue(Request $request)
    {
        $instructor = auth('sanctum')->user();

        // Get filter parameters
        $filterType = $request->get('filter', 'yearly'); // yearly, monthly, custom
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Get instructor's courses with students, materials, and reviews
        $courses = $instructor->instructorCourses()->with(['students', 'materials', 'reviews'])->get();

        // Calculate total earnings (assuming 70% instructor share)
        $totalEarnings = $courses->sum(function ($course) {
            return $course->students()->count() * $course->price * 0.7;
        });

        // Calculate current year earnings
        $currentYearEarnings = $courses->sum(function ($course) {
            return $course->students()
                ->where('course_user.created_at', '>=', now()->startOfYear())
                ->count() * $course->price * 0.7;
        });

        // Get earnings by year for growth chart
        $earningsByYear = $this->getEarningsByYear($instructor);
        $highestEarningYear = $earningsByYear->sortByDesc('earnings')->first();

        // Get highest earning month
        $highestEarningMonth = $this->getHighestEarningMonth($instructor);

        // Calculate course-specific earnings and statistics
        $coursesEarningsList = $courses->map(function ($course) use ($year, $month, $startDate, $endDate) {
            $totalStudents = $course->students()->count();

            // Calculate current period earnings based on filter
            $currentPeriodStudents = $course->students();

            if ($month) {
                $currentPeriodStudents = $currentPeriodStudents
                    ->whereYear('course_user.created_at', $year)
                    ->whereMonth('course_user.created_at', $month);
            } elseif ($startDate && $endDate) {
                $currentPeriodStudents = $currentPeriodStudents
                    ->whereBetween('course_user.created_at', [$startDate, $endDate]);
            } else {
                $currentPeriodStudents = $currentPeriodStudents
                    ->whereYear('course_user.created_at', $year);
            }

            $currentPeriodCount = $currentPeriodStudents->count();

            return [
                'courseId' => $course->id,
                'courseName' => $course->title,
                'courseImage' => $course->thumbnail ?? '',
                'totalEarningsForCourse' => round($totalStudents * $course->price * 0.7, 2),
                'currentYearEarningsForCourse' => round($currentPeriodCount * $course->price * 0.7, 2),
            ];
        });

        // Get course statistics for each course
        $coursesStatistics = $courses->map(function ($course) {
            $totalStudents = $course->students()->count();
            $totalEarnings = $totalStudents * $course->price * 0.7;

            // Get course materials count
            $materialsCount = $course->materials()->count();

            // Get course reviews count and average rating
            $reviewsCount = $course->reviews()->count();
            $averageRating = $course->average_rating ?? 0;

            // Get completion rate
            $totalMaterials = $course->materials()->count();
            $completedStudents = 0;

            if ($totalMaterials > 0) {
                $completedStudents = $course->students()
                    ->whereHas('materialCompletions', function ($query) use ($course) {
                        $query->whereIn('course_material_id', $course->materials()->pluck('id'))
                            ->whereNotNull('completed_at');
                    })
                    ->count();
            }

            $completionRate = $totalMaterials > 0 ? round(($completedStudents / $totalStudents) * 100, 2) : 0;

            return [
                'course_id' => $course->id,
                'name' => $course->title,
                'image' => $course->thumbnail ?? '',
                'total_earnings' => round($totalEarnings, 2),
                'total_students' => $totalStudents,
                'materials_count' => $materialsCount,
                'reviews_count' => $reviewsCount,
                'average_rating' => round($averageRating, 1),
                'completion_rate' => $completionRate,
                'price' => $course->price ?? 0,
                'status' => $course->status,
                'created_at' => $course->created_at->format('Y-m-d'),
            ];
        });

        // Prepare growth chart data based on filter
        $growthChartData = $this->getGrowthChartData($instructor, $filterType, $year, $month, $startDate, $endDate);

        return response()->json([
            'currency' => 'ر.س',
            'earningsOverview' => [
                'totalEarnings' => round($totalEarnings, 2),
                'currentYearEarnings' => round($currentYearEarnings, 2),
                'highestEarningMonth' => [
                    'month' => $highestEarningMonth,
                    'year' => 2019,
                ],
                'highestEarningYear' => [
                    'year' => $highestEarningYear ? $highestEarningYear['year'] : 2023,
                    'sales' => $highestEarningYear ? number_format($highestEarningYear['earnings'] / 1000, 0).'K' : '96K',
                ],
                'growthChart' => $growthChartData,
            ],
            'coursesEarnings' => [
                'list' => $coursesEarningsList->values(),
            ],
            'coursesStatistics' => $coursesStatistics->values(),
        ]);
    }

    /**
     * Get growth chart data based on filter type
     */
    private function getGrowthChartData($instructor, $filterType, $year, $month, $startDate, $endDate)
    {
        switch ($filterType) {
            case 'monthly':
                return $this->getMonthlyGrowthData($instructor, $year);
            case 'custom':
                return $this->getCustomPeriodGrowthData($instructor, $startDate, $endDate);
            case 'yearly':
            default:
                return $this->getYearlyGrowthData($instructor);
        }
    }

    /**
     * Get earnings by year for growth chart
     */
    private function getEarningsByYear($instructor)
    {
        $earnings = collect();
        $currentYear = date('Y');

        for ($year = $currentYear - 7; $year <= $currentYear; $year++) {
            $yearEarnings = $instructor->instructorCourses()
                ->whereHas('students', function ($query) use ($year) {
                    $query->whereYear('course_user.created_at', $year);
                })
                ->get()
                ->sum(function ($course) use ($year) {
                    return $course->students()
                        ->whereYear('course_user.created_at', $year)
                        ->count() * $course->price * 0.7;
                });

            $earnings->push([
                'year' => $year,
                'earnings' => round($yearEarnings, 2),
            ]);
        }

        // If no earnings data, return sample data
        if ($earnings->sum('earnings') == 0) {
            $earnings = collect([
                ['year' => 2023, 'earnings' => 96000],
                ['year' => 2022, 'earnings' => 85000],
                ['year' => 2021, 'earnings' => 72000],
                ['year' => 2020, 'earnings' => 68000],
                ['year' => 2019, 'earnings' => 75000],
                ['year' => 2018, 'earnings' => 62000],
                ['year' => 2017, 'earnings' => 58000],
                ['year' => 2016, 'earnings' => 52000],
            ]);
        }

        return $earnings;
    }

    /**
     * Get yearly growth data
     */
    private function getYearlyGrowthData($instructor)
    {
        $earnings = collect();
        $currentYear = date('Y');

        for ($year = $currentYear - 7; $year <= $currentYear; $year++) {
            $yearEarnings = $instructor->instructorCourses()
                ->whereHas('students', function ($query) use ($year) {
                    $query->whereYear('course_user.created_at', $year);
                })
                ->get()
                ->sum(function ($course) use ($year) {
                    return $course->students()
                        ->whereYear('course_user.created_at', $year)
                        ->count() * $course->price * 0.7;
                });

            $earnings->push([
                'year' => $year,
                'earnings' => round($yearEarnings, 2),
            ]);
        }

        // If no earnings data, return sample data
        if ($earnings->sum('earnings') == 0) {
            $earnings = collect([
                ['year' => 2023, 'earnings' => 0],
                ['year' => 2022, 'earnings' => 0],
                ['year' => 2021, 'earnings' => 0],
                ['year' => 2020, 'earnings' => 0],
                ['year' => 2019, 'earnings' => 0],
                ['year' => 2018, 'earnings' => 0],
                ['year' => 2017, 'earnings' => 0],
                ['year' => 2016, 'earnings' => 0],
            ]);
        }

        return [
            'unit' => 'Yearly',
            'data' => $earnings->values()->toArray(),
        ];
    }

    /**
     * Get monthly growth data for a specific year
     */
    private function getMonthlyGrowthData($instructor, $year)
    {
        $earnings = collect();
        $monthNames = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
        ];

        for ($month = 1; $month <= 12; $month++) {
            $monthEarnings = $instructor->instructorCourses()
                ->whereHas('students', function ($query) use ($year, $month) {
                    $query->whereYear('course_user.created_at', $year)
                        ->whereMonth('course_user.created_at', $month);
                })
                ->get()
                ->sum(function ($course) use ($year, $month) {
                    return $course->students()
                        ->whereYear('course_user.created_at', $year)
                        ->whereMonth('course_user.created_at', $month)
                        ->count() * $course->price * 0.7;
                });

            $earnings->push([
                'month' => $monthNames[$month],
                'earnings' => round($monthEarnings, 2),
            ]);
        }

        return [
            'unit' => 'Monthly',
            'data' => $earnings->values()->toArray(),
        ];
    }

    /**
     * Get custom period growth data
     */
    private function getCustomPeriodGrowthData($instructor, $startDate, $endDate)
    {
        if (! $startDate || ! $endDate) {
            return [
                'unit' => 'Custom',
                'data' => [],
            ];
        }

        // Get daily earnings for the custom period
        $earnings = collect();
        $currentDate = \Carbon\Carbon::parse($startDate);
        $endDateCarbon = \Carbon\Carbon::parse($endDate);

        while ($currentDate->lte($endDateCarbon)) {
            $dayEarnings = $instructor->instructorCourses()
                ->whereHas('students', function ($query) use ($currentDate) {
                    $query->whereDate('course_user.created_at', $currentDate->format('Y-m-d'));
                })
                ->get()
                ->sum(function ($course) use ($currentDate) {
                    return $course->students()
                        ->whereDate('course_user.created_at', $currentDate->format('Y-m-d'))
                        ->count() * $course->price * 0.7;
                });

            $earnings->push([
                'date' => $currentDate->format('Y-m-d'),
                'earnings' => round($dayEarnings, 2),
            ]);

            $currentDate->addDay();
        }

        return [
            'unit' => 'Daily',
            'data' => $earnings->values()->toArray(),
        ];
    }

    /**
     * Get highest earning month
     */
    private function getHighestEarningMonth($instructor)
    {
        $monthlyEarnings = collect();

        for ($month = 1; $month <= 12; $month++) {
            $monthEarnings = $instructor->instructorCourses()
                ->whereHas('students', function ($query) use ($month) {
                    $query->whereMonth('course_user.created_at', $month);
                })
                ->get()
                ->sum(function ($course) use ($month) {
                    return $course->students()
                        ->whereMonth('course_user.created_at', $month)
                        ->count() * $course->price * 0.7;
                });

            $monthlyEarnings->push([
                'month' => $month,
                'earnings' => round($monthEarnings, 2),
            ]);
        }

        $highestEarningMonth = $monthlyEarnings->sortByDesc('earnings')->first();

        if ($highestEarningMonth) {
            $monthNames = [
                1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
                5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
                9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
            ];

            return $monthNames[$highestEarningMonth['month']];
        }

        return 'نوفمبر';
    }

    /**
     * Get completion rate data
     */
    private function getCompletionRateData($courses)
    {
        $totalStudents = 0;
        $completedStudents = 0;

        foreach ($courses as $course) {
            $courseStudents = $course->students;
            $totalStudents += $courseStudents->count();

            foreach ($courseStudents as $student) {
                $totalMaterials = $course->materials()->count();
                $completedMaterials = $course->materials()
                    ->whereHas('progress', function ($query) use ($student) {
                        $query->where('user_id', $student->id)
                            ->whereNotNull('completed_at');
                    })->count();

                if ($totalMaterials > 0 && ($completedMaterials / $totalMaterials) >= 0.8) {
                    $completedStudents++;
                }
            }
        }

        $completionRate = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100) : 0;

        // If no data, return sample data
        if ($totalStudents == 0) {
            return [
                'completed' => 1543,
                'completion_rate' => 15,
            ];
        }

        return [
            'completed' => $completedStudents,
            'completion_rate' => $completionRate,
        ];
    }

    /**
     * Get timeline events for schedule
     */
    private function getTimelineEvents($courses)
    {
        $events = collect();

        foreach ($courses as $course) {
            if ($course->created_at && $course->updated_at) {
                // Course duration event
                $events->push([
                    'eventName' => $course->title,
                    'eventType' => 'Course Duration',
                    'status' => $this->getStatusInArabic($course->status),
                    'startDate' => $course->created_at->format('M j'),
                    'endDate' => $course->created_at->addDays(30)->format('M j'),
                    'publishDate' => null,
                ]);

                // Publish date event
                $events->push([
                    'eventName' => $course->title.' المستوى المتقدم',
                    'eventType' => 'Publish Date',
                    'status' => 'تاريخ النشر',
                    'startDate' => null,
                    'endDate' => null,
                    'publishDate' => $course->created_at->addDays(45)->format('M j'),
                ]);
            }
        }

        // If no courses, return sample data
        if ($events->isEmpty()) {
            $events->push([
                'eventName' => 'دورة الذكاء الاصطناعي للمبتدئين',
                'eventType' => 'Course Duration',
                'status' => 'تحت المراجعة',
                'startDate' => 'Oct 9',
                'endDate' => 'Nov 2',
                'publishDate' => null,
            ]);

            $events->push([
                'eventName' => 'دورة الذكاء الاصطناعي المستوى المتقدم',
                'eventType' => 'Publish Date',
                'status' => 'تاريخ النشر',
                'startDate' => null,
                'endDate' => null,
                'publishDate' => 'Nov 16',
            ]);
        }

        return $events->take(2); // Return only 2 events to match the design
    }

    /**
     * Get status in Arabic
     */
    private function getStatusInArabic($status)
    {
        return match ($status) {
            'pending' => 'تحت المراجعة',
            'approved' => 'تمت الموافقة',
            'rejected' => 'مرفوض',
            'draft' => 'مسودة',
            'published' => 'منشور',
            'scheduled' => 'مجدول للنشر',
            default => $status,
        };
    }

    /**
     * Calculate grade based on score
     */
    private function calculateGrade($score)
    {
        if ($score >= 95) {
            return 'A+';
        }
        if ($score >= 90) {
            return 'A';
        }
        if ($score >= 85) {
            return 'B+';
        }
        if ($score >= 80) {
            return 'B';
        }
        if ($score >= 75) {
            return 'C+';
        }
        if ($score >= 70) {
            return 'C';
        }
        if ($score >= 65) {
            return 'D+';
        }
        if ($score >= 60) {
            return 'D';
        }

        return 'F';
    }

    /**
     * Get all instructors (public endpoint - no auth required)
     */
    public function getAllInstructors(Request $request)
    {
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'instructor');
        })->with(['instructorRatings', 'courses' => function ($q) {
            $q->where('status', 'approved');
        }]);

        // Apply filters
        if ($request->has('field')) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('field', $request->field);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'rating':
                    $query->orderBy('average_rating', 'desc');
                    break;
                case 'courses':
                    $query->withCount('courses')->orderBy('courses_count', 'desc');
                    break;
                case 'students':
                    $query->withCount('students')->orderBy('students_count', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $instructors = $query->paginate(20);

        // Transform the data to include instructor statistics
        $instructors->getCollection()->transform(function ($instructor) {
            $totalCourses = $instructor->courses->count();
            $totalStudents = $instructor->courses->sum(function ($course) {
                return $course->students()->count();
            });
            $averageRating = $instructor->instructorRatings->avg('rating') ?? 0;
            $totalReviews = $instructor->instructorRatings->count();

            return [
                'id' => $instructor->id,
                'name' => $instructor->name,
                'email' => $instructor->email,
                'bio' => $instructor->bio,
                'profile_photo' => $instructor->profile_photo_path,
                'joined_at' => $instructor->created_at,
                'stats' => [
                    'total_courses' => $totalCourses,
                    'total_students' => $totalStudents,
                    'average_rating' => round($averageRating, 1),
                    'total_reviews' => $totalReviews,
                ],
                'top_courses' => $instructor->courses->take(3)->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'thumbnail' => $course->thumbnail,
                        'price' => $course->price,
                        'average_rating' => $course->average_rating ?? 0,
                        'total_students' => $course->students()->count(),
                    ];
                }),
                'fields' => $instructor->courses->pluck('difficulty_level')->unique()->values(),
            ];
        });

        return response()->json([
            'instructors' => $instructors,
            'stats' => [
                'total_instructors' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'instructor');
                })->count(),
                'total_courses' => Course::where('status', 'approved')->count(),
                'total_students' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'student');
                })->count(),
            ],
        ]);
    }

    /**
     * Get instructor details (public endpoint - no auth required)
     */
    public function getInstructorDetails($id)
    {
        $instructor = User::whereHas('roles', function ($q) {
            $q->where('name', 'instructor');
        })->with([
            'instructorRatings' => function ($q) {
                $q->with('user:id,name,profile_photo_path');
            },
            'courses' => function ($q) {
                $q->where('status', 'approved')
                    ->with(['category', 'degree'])
                    ->withCount(['students', 'reviews']);
            },
        ])->find($id);

        if (! $instructor) {
            return response()->json(['message' => 'Instructor not found'], 404);
        }

        // Calculate instructor statistics
        $totalCourses = $instructor->courses->count();
        $totalStudents = $instructor->courses->sum('students_count');
        $averageRating = $instructor->instructorRatings->avg('rating') ?? 0;
        $totalReviews = $instructor->instructorRatings->count();
        $totalRevenue = $instructor->courses->sum(function ($course) {
            return $course->price * $course->students_count;
        });

        // Get recent reviews
        $recentReviews = $instructor->instructorRatings()
            ->with('user:id,name,profile_photo_path')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'instructor' => [
                'id' => $instructor->id,
                'name' => $instructor->name,
                'email' => $instructor->email,
                'bio' => $instructor->bio,
                'profile_photo' => $instructor->profile_photo_path,
                'joined_at' => $instructor->created_at,
                'stats' => [
                    'total_courses' => $totalCourses,
                    'total_students' => $totalStudents,
                    'average_rating' => round($averageRating, 1),
                    'total_reviews' => $totalReviews,
                    'total_revenue' => $totalRevenue,
                ],
                'fields' => $instructor->courses->pluck('difficulty_level')->unique()->values(),
            ],
            'courses' => $instructor->courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description,
                    'thumbnail' => $course->thumbnail,
                    'price' => $course->price,
                    'average_rating' => $course->average_rating ?? 0,
                    'total_ratings' => $course->total_ratings ?? 0,
                    'total_students' => $course->students_count,
                    'category' => $course->category,
                    'degree' => $course->degree,
                    'created_at' => $course->created_at,
                ];
            }),
            'recent_reviews' => $recentReviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at,
                    'user' => [
                        'id' => $review->user->id,
                        'name' => $review->user->name,
                        'profile_photo' => $review->user->profile_photo_path,
                    ],
                ];
            }),
        ]);
    }

    /**
     * Get instructor account settings
     */
    public function getAccountSettings()
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        return response()->json([
            'profileImage' => $user->profile_photo_path ? url($user->profile_photo_path) : null,
            'fullName' => $user->name,
            'teachingField' => $user->teaching_field,
            'jobTitle' => $user->job_title,
            'phoneNumber' => $user->phone,
            'address' => [
                'streetAddress' => $user->street,
                'country' => 'Egypt', // Default country, can be made configurable
                'city' => $user->city,
            ],
            'email' => $user->email,
            'description' => $user->bio,
        ]);
    }

    /**
     * Update instructor account settings
     */
    public function updateAccountSettings(Request $request)
    {
        // Debug: Log the incoming request data
        \Log::info('Instructor account settings update request:', [
            'all_data' => $request->all(),
            'has_file' => $request->hasFile('profileImage'),
            'address_field' => $request->get('address'),
            'address_type' => gettype($request->get('address')),
            'flat_fields' => [
                'streetAddress' => $request->get('streetAddress'),
                'country' => $request->get('country'),
                'city' => $request->get('city'),
            ],
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method(),
            'is_json' => $request->isJson(),
        ]);

        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // ✅ Validation rules
        $rules = [
            'fullName' => 'sometimes|string|max:255',
            'teachingField' => 'sometimes|string|max:255',
            'jobTitle' => 'sometimes|string|max:255',
            'phoneNumber' => 'sometimes|string|max:20',
            'streetAddress' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'address' => 'sometimes', // ممكن تبقى array أو string
            'address.streetAddress' => 'sometimes|string|max:255',
            'address.country' => 'sometimes|string|max:255',
            'address.city' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'description' => 'sometimes|string|max:1000',
            'profileImage' => 'sometimes', // ممكن تبقى فايل أو سترينج
        ];

        $validated = $request->validate($rules);

        // ✅ Update basic info
        $map = [
            'fullName' => 'name',
            'teachingField' => 'teaching_field',
            'jobTitle' => 'job_title',
            'phoneNumber' => 'phone',
            'email' => 'email',
            'description' => 'bio',
        ];

        foreach ($map as $input => $column) {
            if ($request->filled($input)) {
                $user->{$column} = $request->input($input);
            }
        }

        // ✅ Address handling (يدعم JSON أو form-data)
        $addressData = null;

        if ($request->has('address')) {
            if (is_array($request->address)) {
                $addressData = $request->address;
            } elseif (is_string($request->address)) {
                // Handle "[object Object]" case
                if ($request->address === '[object Object]') {
                    // Extract from individual fields instead
                    $addressData = [
                        'streetAddress' => $request->input('streetAddress'),
                        'country' => $request->input('country'),
                        'city' => $request->input('city'),
                    ];
                } else {
                    $addressData = json_decode($request->address, true);
                }
            }
        } else {
            // flat fields
            $addressData = [
                'streetAddress' => $request->input('streetAddress'),
                'country' => $request->input('country'),
                'city' => $request->input('city'),
            ];
        }

        if (is_array($addressData)) {
            $user->street = $addressData['streetAddress'] ?? $user->street;
            $user->district = $addressData['country'] ?? $user->district;
            $user->city = $addressData['city'] ?? $user->city;
        }

        // Log the values that will be saved
        \Log::info('Values to be saved:', [
            'name' => $user->name,
            'teaching_field' => $user->teaching_field,
            'job_title' => $user->job_title,
            'phone' => $user->phone,
            'street' => $user->street,
            'district' => $user->district,
            'city' => $user->city,
            'bio' => $user->bio,
        ]);

        // ✅ Profile image (فايل أو سترينج)
        if ($request->hasFile('profileImage')) {
            try {
                $image = $request->file('profileImage');
                $imageName = time().'_'.$image->getClientOriginalName();
                $imagePath = $image->storeAs('profile-photos', $imageName, 'public');
                $user->profile_photo_path = $imagePath;
            } catch (\Exception $e) {
                \Log::error('Failed to upload profile image', ['error' => $e->getMessage()]);

                return response()->json([
                    'message' => 'فشل في رفع الصورة الشخصية',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } elseif (is_string($request->input('profileImage'))) {
            // في حالة لو بعت اسم فايل أو لينك
            $user->profile_photo_path = $request->input('profileImage');
        }

        // ✅ Save changes
        try {
            $user->save();
            \Log::info('User profile updated successfully', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to save user profile', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'فشل في حفظ البيانات',
                'error' => $e->getMessage(),
            ], 500);
        }

        // ✅ Response
        return response()->json([
            'message' => 'تم تحديث البيانات بنجاح',
            'profileImage' => $user->profile_photo_path
                ? (str_starts_with($user->profile_photo_path, 'http')
                    ? $user->profile_photo_path
                    : url('storage/'.$user->profile_photo_path))
                : null,
            'fullName' => $user->name,
            'teachingField' => $user->teaching_field,
            'jobTitle' => $user->job_title,
            'phoneNumber' => $user->phone,
            'address' => [
                'streetAddress' => $user->street,
                'country' => $user->district,
                'city' => $user->city,
            ],
            'email' => $user->email,
            'description' => $user->bio,
        ]);
    }

    /**
     * Get instructor timeline with focus on pending courses
     */
    public function getTimeline()
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Get instructor's courses with detailed information
        $courses = $user->instructorCourses()
            ->with(['category', 'degree', 'materials', 'students'])
            ->withCount(['materials', 'students', 'reviews'])
            ->get();

        // Check if instructor has courses
        if ($courses->isEmpty()) {
            return response()->json([
                'message' => 'لا توجد دورات لهذا المدرب',
                'summary' => [
                    'total_courses' => 0,
                    'pending_courses' => 0,
                    'approved_courses' => 0,
                    'draft_courses' => 0,
                ],
                'timeline' => [],
                'pending_courses_highlight' => 'لا توجد دورات بعد',
            ]);
        }

        $timeline = $courses->map(function ($course) {
            $events = [];

            // Always show course creation event
            if ($course->created_at) {
                $events[] = [
                    'courseId' => $course->id,
                    'courseName' => $course->title,
                    'startDate' => $course->created_at->format('Y-m-d'),
                    'endDate' => $course->created_at->addDays(30)->format('Y-m-d'),
                    'status' => $this->getStatusInArabic($course->status),
                    'statusCode' => $course->status,
                    'isClickable' => true,
                    'courseDetails' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'description' => $course->description,
                        'price' => $course->price,
                        'difficulty_level' => $course->difficulty_level,
                        'language' => $course->language,
                        'thumbnail' => $course->thumbnail,
                        'category' => $course->category ? $course->category->name : null,
                        'degree' => $course->degree ? $course->degree->name : null,
                        'materials_count' => $course->materials_count,
                        'students_count' => $course->students_count,
                        'reviews_count' => $course->reviews_count,
                        'created_at' => $course->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $course->updated_at->format('Y-m-d H:i:s'),
                        'status' => $course->status,
                        'status_arabic' => $this->getStatusInArabic($course->status),
                        'rejection_reason' => $course->rejection_reason,
                    ],
                ];
            }

            // Show approval event if approved
            if ($course->status === 'approved' && $course->updated_at) {
                $events[] = [
                    'courseId' => $course->id,
                    'courseName' => $course->title.' - تمت الموافقة',
                    'publishDate' => $course->updated_at->format('Y-m-d'),
                    'status' => 'تمت الموافقة',
                    'statusCode' => 'approved',
                    'isClickable' => true,
                    'courseDetails' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'description' => $course->description,
                        'price' => $course->price,
                        'difficulty_level' => $course->difficulty_level,
                        'language' => $course->language,
                        'thumbnail' => $course->thumbnail,
                        'category' => $course->category ? $course->category->name : null,
                        'degree' => $course->degree ? $course->degree->name : null,
                        'materials_count' => $course->materials_count,
                        'students_count' => $course->students_count,
                        'reviews_count' => $course->reviews_count,
                        'created_at' => $course->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $course->updated_at->format('Y-m-d H:i:s'),
                        'status' => $course->status,
                        'status_arabic' => $this->getStatusInArabic($course->status),
                        'rejection_reason' => $course->rejection_reason,
                    ],
                ];
            }

            // Show completion event if has materials
            if ($course->materials()->count() > 0 && $course->updated_at) {
                $events[] = [
                    'courseId' => $course->id,
                    'courseName' => $course->title.' - اكتمل المحتوى',
                    'publishDate' => $course->updated_at->format('Y-m-d'),
                    'status' => 'مكتمل',
                    'statusCode' => 'completed',
                    'isClickable' => true,
                    'courseDetails' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'description' => $course->description,
                        'price' => $course->price,
                        'difficulty_level' => $course->difficulty_level,
                        'language' => $course->language,
                        'thumbnail' => $course->thumbnail,
                        'category' => $course->category ? $course->category->name : null,
                        'degree' => $course->degree ? $course->degree->name : null,
                        'materials_count' => $course->materials_count,
                        'students_count' => $course->students_count,
                        'reviews_count' => $course->reviews_count,
                        'created_at' => $course->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $course->updated_at->format('Y-m-d H:i:s'),
                        'status' => $course->status,
                        'status_arabic' => 'مكتمل',
                        'rejection_reason' => $course->rejection_reason,
                    ],
                ];
            }

            return $events;
        })->flatten(1)->sortByDesc(function ($event) {
            // Sort by date (publishDate takes priority, then startDate)
            return $event['publishDate'] ?? $event['startDate'] ?? '1970-01-01';
        })->take(15); // Increased limit to show more events

        // If no courses, return sample data
        if ($timeline->isEmpty()) {
            $timeline = collect([
                [
                    'courseId' => null,
                    'courseName' => 'دورة الذكاء الاصطناعي للمبتدئين',
                    'startDate' => '2023-10-09',
                    'endDate' => '2023-11-02',
                    'status' => 'تحت المراجعة',
                    'statusCode' => 'pending',
                    'isClickable' => false,
                    'courseDetails' => null,
                ],
                [
                    'courseId' => null,
                    'courseName' => 'دورة الذكاء الاصطناعي المستوى المتقدم',
                    'publishDate' => '2023-11-16',
                    'status' => 'تاريخ النشر',
                    'statusCode' => 'published',
                    'isClickable' => false,
                    'courseDetails' => null,
                ],
            ]);
        }

        // Separate pending courses for emphasis
        $pendingCourses = $courses->where('status', 'pending')->count();
        $approvedCourses = $courses->where('status', 'approved')->count();
        $draftCourses = $courses->where('status', 'draft')->count();

        return response()->json([
            'summary' => [
                'total_courses' => $courses->count(),
                'pending_courses' => $pendingCourses,
                'approved_courses' => $approvedCourses,
                'draft_courses' => $draftCourses,
            ],
            'timeline' => $timeline->values(),
            'pending_courses_highlight' => $pendingCourses > 0 ? 'لديك '.$pendingCourses.' دورة في انتظار الموافقة' : 'جميع دوراتك تمت الموافقة عليها',
        ]);
    }

    /**
     * Get instructor's single course details
     */
    public function showCourse(Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only view courses that you created. This course belongs to another instructor.',
                'error' => 'course_access_denied',
                'course_id' => $course->id,
                'course_title' => $course->title,
                'instructor_id' => $course->instructor_id,
                'your_id' => $user->id,
            ], 403);
        }

        // Load course with relationships
        $course->load(['category', 'degree', 'students', 'materials', 'quizzes', 'reviews']);

        // Calculate course statistics
        $totalStudents = $course->students()->count();
        $totalWatchTimeHours = 0; // Placeholder since watch_time column doesn't exist
        $averageWatchTimeHours = $totalStudents > 0 ? $totalWatchTimeHours / $totalStudents : 0;
        $studentRating = $course->average_rating ?? 0;

        // Calculate earnings
        $totalEarnings = $totalStudents * $course->price * 0.7; // 70% instructor share
        $earningsThisYear = $course->students()
            ->whereYear('course_user.created_at', date('Y'))
            ->count() * $course->price * 0.7;

        // Count materials and assessments
        $lessonsCount = $course->materials()->count();
        $quizzesCount = $course->quizzes()->count();
        $assignmentsCount = 0; // Placeholder since assignments table might not exist

        return response()->json([
            'course' => [
                'courseId' => $course->id,
                'name' => $course->title,
                'imageUrl' => $course->thumbnail ?? '',
                'thumbnail' => $course->thumbnail,
                'category' => $course->category ? [
                    'id' => $course->category->id,
                    'name' => $course->category->name,
                    'slug' => $course->category->slug,
                ] : null,
                'level' => $course->difficulty_level ?? 'مبتدئ',
                'difficulty_level' => $course->difficulty_level,
                'language' => $course->language ?? 'العربية',
                'price' => $course->price ?? 0,
                'currency' => 'ريال سعودي',
                'description' => $course->description ?? '',
                'slug' => $course->slug,
                'status' => $course->status,
                'rejection_reason' => $course->rejection_reason,
                'created_at' => $course->created_at,
                'updated_at' => $course->updated_at,
                'degree' => $course->degree ? [
                    'id' => $course->degree->id,
                    'name' => $course->degree->name,
                    'slug' => $course->degree->slug,
                ] : null,
            ],
            'courseStatistics' => [
                'totalWatchTimeHours' => $totalWatchTimeHours,
                'averageWatchTimeHours' => round($averageWatchTimeHours, 2),
                'studentsCount' => $totalStudents,
                'studentRating' => round($studentRating, 1),
                'earningsThisYear' => round($earningsThisYear, 2),
                'totalEarnings' => round($totalEarnings, 2),
                'lessonsCount' => $lessonsCount,
                'quizzesCount' => $quizzesCount,
                'assignmentsCount' => $assignmentsCount,
            ],
        ]);
    }

    /**
     * Create a new course with basic content
     */
    public function createCourse(Request $request)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'imageUrl' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:255',
            'level' => 'nullable|string|in:مبتدئ,متوسط,متقدم',
            'language' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:50',
            'description' => 'required|string|max:1000',
        ]);

        try {
            // Handle category
            $category = null;
            if (! empty($validated['category'])) {
                $category = \App\Models\Category::firstOrCreate(
                    ['name' => $validated['category']],
                    [
                        'slug' => \Illuminate\Support\Str::slug($validated['category']),
                        'description' => null,
                        'image_url' => null,
                    ]
                );
            }

            // Create the course
            $course = Course::create([
                'instructor_id' => $user->id,
                'category_id' => $category ? $category->id : null,
                'title' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'difficulty_level' => $validated['level'] ?? 'متوسط',
                'language' => $validated['language'] ?? 'العربية',
                'thumbnail' => $validated['imageUrl'] ?? null,
                'status' => 'draft',
            ]);

            // Load the course with relationships
            $course->load(['category', 'degree', 'students', 'materials', 'quizzes', 'reviews']);

            return response()->json([
                'message' => 'تم إنشاء الدورة بنجاح',
                'course' => [
                    'courseId' => $course->id,
                    'name' => $course->title,
                    'imageUrl' => $course->thumbnail ?? '',
                    'category' => $course->category->name ?? '',
                    'level' => $course->difficulty_level ?? 'متوسط',
                    'language' => $course->language ?? 'العربية',
                    'price' => $course->price,
                    'currency' => $validated['currency'] ?? 'ريال سعودي',
                    'description' => $course->description,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء إنشاء الدورة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add content to an existing course
     */
    public function addCourseContent(Request $request, Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only add content to courses that you created. This course belongs to another instructor.',
                'error' => 'course_access_denied',
                'course_id' => $course->id,
                'course_title' => $course->title,
                'instructor_id' => $course->instructor_id,
                'your_id' => $user->id,
            ], 403);
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'imageUrl' => 'sometimes|string|max:500',
            'category' => 'sometimes|string|max:255',
            'level' => 'sometimes|string|in:مبتدئ,متوسط,متقدم',
            'language' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|string|max:50',
            'description' => 'sometimes|string|max:1000',
        ]);

        try {
            // Update course with new content
            if (isset($validated['name'])) {
                $course->title = $validated['name'];
            }

            if (isset($validated['imageUrl'])) {
                $course->thumbnail = $validated['imageUrl'];
            }

            if (isset($validated['category'])) {
                // Find or create category with slug
                $category = \App\Models\Category::firstOrCreate(
                    ['name' => $validated['category']],
                    [
                        'slug' => \Illuminate\Support\Str::slug($validated['category']),
                        'description' => null,
                        'image_url' => null,
                    ]
                );
                $course->category_id = $category->id;
            }

            if (isset($validated['level'])) {
                $course->difficulty_level = $validated['level'];
            }

            if (isset($validated['language'])) {
                $course->language = $validated['language'];
            }

            if (isset($validated['price'])) {
                $course->price = $validated['price'];
            }

            if (isset($validated['description'])) {
                $course->description = $validated['description'];
            }

            $course->save();

            // Load the updated course with relationships
            $course->load(['category', 'degree', 'students', 'materials', 'quizzes', 'reviews']);

            return response()->json([
                'message' => 'تم تحديث محتوى الدورة بنجاح',
                'course' => [
                    'courseId' => $course->id,
                    'name' => $course->title,
                    'imageUrl' => $course->thumbnail ?? '',
                    'category' => $course->category->name ?? '',
                    'level' => $course->difficulty_level ?? 'مبتدئ',
                    'language' => $course->language ?? 'العربية',
                    'price' => $course->price ?? 0,
                    'currency' => 'ريال سعودي',
                    'description' => $course->description ?? '',
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء تحديث محتوى الدورة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get course status based on course data
     */
    private function getCourseStatus($course)
    {
        // Check if course has materials (is completed)
        $hasMaterials = $course->materials_count > 0;

        // Check course status from database
        $courseStatus = $course->status ?? 'draft';

        if (! $hasMaterials) {
            return 'Not Completed';
        }

        switch ($courseStatus) {
            case 'approved':
            case 'published':
                return 'Active';
            case 'pending':
            case 'draft':
                return 'Under Review';
            case 'rejected':
                return 'Rejected';
            default:
                return 'Under Review';
        }
    }

    /**
     * Add a new lesson to a course
     */
    public function addLesson(Request $request, Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only add lessons to courses that you created.',
            ], 403);
        }

        // Validate the request
        $validated = $request->validate([
            'lesson.level' => 'required|string|max:255',
            'lesson.title' => 'required|string|max:255',
            'lesson.type' => 'required|string|in:فيديو,امتحان,ملفات',
            'lesson.fields.lesson_title' => 'required|string|max:255',
            'lesson.fields.description' => 'nullable|string',
            'video.preview_placeholder' => 'nullable|string',
            'video.uploads' => 'nullable|array',
            'video.uploads.*.file_name' => 'nullable|string',
            'video.uploads.*.status' => 'nullable|string',
            'video.uploads.*.uploader' => 'nullable|string',
            'quizzes' => 'nullable|array',
            'quizzes.*.quiz_title' => 'nullable|string|max:255',
            'quizzes.*.added_by' => 'nullable|string|max:255',
            'quizzes.*.timestamp' => 'nullable|string',
            'quizzes.*.questions_count' => 'nullable|integer',
            'files.list' => 'nullable|array',
            'files.list.*.file_name' => 'nullable|string',
            'files.list.*.status' => 'nullable|string',
            'files.list.*.uploader' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create or find the level
            $level = $course->levels()->firstOrCreate(
                ['level_name' => $validated['lesson']['level']],
                ['order' => $course->levels()->count() + 1]
            );

            // Create the lesson/material
            $materialType = $this->mapLessonTypeToMaterialType($validated['lesson']['type']);

            $material = $course->materials()->create([
                'level_id' => $level->id,
                'title' => $validated['lesson']['fields']['lesson_title'],
                'description' => $validated['lesson']['fields']['description'] ?? '',
                'type' => $materialType,
                'order' => $course->materials()->where('level_id', $level->id)->count() + 1,
                'is_free' => false,
            ]);

            // Handle video content
            if ($validated['lesson']['type'] === 'فيديو' && isset($validated['video']['uploads'])) {
                foreach ($validated['video']['uploads'] as $upload) {
                    if ($upload['status'] === 'مكتمل') {
                        // Create file upload record
                        $fileUpload = FileUpload::create([
                            'course_id' => $course->id,
                            'material_id' => $material->id,
                            'uploaded_by' => $user->id,
                            'file_name' => $upload['file_name'],
                            'original_name' => $upload['file_name'],
                            'file_path' => 'videos/'.$upload['file_name'],
                            'file_type' => 'video',
                            'mime_type' => 'video/mp4', // Default, can be updated
                            'file_size' => 0, // Will be updated when upload completes
                            'status' => 'completed',
                            'progress' => 100,
                        ]);

                        // Update material with file upload reference
                        $material->update([
                            'file_path' => $fileUpload->file_path,
                            'duration' => 0, // You can calculate this from the actual video
                        ]);
                    }
                }
            }

            // Handle file content
            if ($validated['lesson']['type'] === 'ملفات' && isset($validated['files']['list'])) {
                $filePaths = [];
                foreach ($validated['files']['list'] as $file) {
                    if ($file['status'] === 'مكتمل') {
                        // Create file upload record
                        $fileUpload = FileUpload::create([
                            'course_id' => $course->id,
                            'material_id' => $material->id,
                            'uploaded_by' => $user->id,
                            'file_name' => $file['file_name'],
                            'original_name' => $file['file_name'],
                            'file_path' => 'files/'.$file['file_name'],
                            'file_type' => 'pdf', // Default, can be updated
                            'mime_type' => 'application/pdf', // Default, can be updated
                            'file_size' => 0, // Will be updated when upload completes
                            'status' => 'completed',
                            'progress' => 100,
                        ]);

                        $filePaths[] = $fileUpload->file_path;
                    }
                }
                if (! empty($filePaths)) {
                    $material->update(['file_path' => json_encode($filePaths)]);
                }
            }

            // Handle in-video quizzes
            if (isset($validated['quizzes']) && is_array($validated['quizzes'])) {
                foreach ($validated['quizzes'] as $quizData) {
                    $material->inVideoQuizzes()->create([
                        'quiz_name' => $quizData['quiz_title'] ?? 'Quiz',
                        'timestamp' => $quizData['timestamp'] ?? '00:00:00',
                        'questions_count' => $quizData['questions_count'] ?? 0,
                        'questions' => [], // You can expand this to store actual questions
                        'order' => $material->inVideoQuizzes()->count() + 1,
                    ]);
                }
            }

            // Handle exam/quiz content
            if ($validated['lesson']['type'] === 'امتحان') {
                $assessment = Assessment::create([
                    'course_id' => $course->id,
                    'created_by' => $user->id,
                    'title' => $validated['lesson']['fields']['lesson_title'],
                    'description' => $validated['lesson']['fields']['description'] ?? '',
                    'type' => 'exam',
                ]);

                // Link assessment to material
                $material->update(['assessment_id' => $assessment->id]);
            }

            DB::commit();

            return response()->json([
                'message' => 'تم إضافة الدرس بنجاح',
                'lesson' => [
                    'id' => $material->id,
                    'level' => $level->level_name,
                    'title' => $material->title,
                    'type' => $validated['lesson']['type'],
                    'fields' => [
                        'lesson_title' => $material->title,
                        'description' => $material->description,
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'حدث خطأ أثناء إضافة الدرس',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add questions to a quiz/exam lesson
     */
    public function addQuizQuestions(Request $request, Course $course, $materialId)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only add questions to courses that you created.',
            ], 403);
        }

        // Validate the request
        $validated = $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.question_number' => 'required|integer|min:1',
            'questions.*.question_text' => 'required|string|max:1000',
            'questions.*.media_upload' => 'nullable|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.answer_text' => 'required|string|max:500',
            'questions.*.answers.*.media_upload' => 'nullable|string',
            'questions.*.answers.*.is_correct' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $material = $course->materials()->findOrFail($materialId);

            if (! $material->assessment_id) {
                return response()->json([
                    'message' => 'This material is not an assessment.',
                ], 400);
            }

            $assessment = Assessment::findOrFail($material->assessment_id);

            // Create questions
            foreach ($validated['questions'] as $questionData) {
                $question = $assessment->questions()->create([
                    'question' => $questionData['question_text'],
                    'type' => 'mcq',
                    'options' => collect($questionData['answers'])->pluck('answer_text')->toArray(),
                    'correct_answer' => collect($questionData['answers'])
                        ->where('is_correct', true)
                        ->first()['answer_text'] ?? '',
                    'points' => 10, // Default points
                    'order' => $questionData['question_number'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'تم إضافة الأسئلة بنجاح',
                'questions_count' => count($validated['questions']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'حدث خطأ أثناء إضافة الأسئلة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get course lessons and content structure
     */
    public function getCourseContent(Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only view content of courses that you created.',
            ], 403);
        }

        try {
            $course->load([
                'levels.materials' => function ($query) {
                    $query->orderBy('order');
                },
                'levels.materials.inVideoQuizzes' => function ($query) {
                    $query->orderBy('order');
                },
                'levels.materials.assessment.questions',
            ]);

            $content = $course->levels->map(function ($level) {
                return [
                    'level_name' => $level->level_name,
                    'lessons' => $level->materials->map(function ($material) {
                        $lessonData = [
                            'id' => $material->id,
                            'title' => $material->title,
                            'type' => $this->mapMaterialTypeToLessonType($material->type),
                            'fields' => [
                                'lesson_title' => $material->title,
                                'description' => $material->description,
                            ],
                        ];

                        // Add video-specific data
                        if ($material->type === 'video') {
                            $lessonData['video'] = [
                                'preview_placeholder' => 'Video Preview',
                                'uploads' => $material->file_path ? [
                                    [
                                        'file_name' => basename($material->file_path),
                                        'status' => 'مكتمل',
                                        'uploader' => 'Instructor',
                                    ],
                                ] : [],
                            ];
                        }

                        // Add file-specific data
                        if ($material->type === 'document') {
                            $lessonData['files'] = [
                                'list' => $material->file_path ? json_decode($material->file_path, true) : [],
                            ];
                        }

                        // Add quiz-specific data
                        if ($material->type === 'quiz' && $material->assessment_id) {
                            $lessonData['questions'] = $material->assessment->questions->map(function ($question) {
                                return [
                                    'question_number' => $question->order,
                                    'question_text' => $question->question,
                                    'answers' => collect($question->options)->map(function ($option, $index) use ($question) {
                                        return [
                                            'answer_text' => $option,
                                            'is_correct' => $option === $question->correct_answer,
                                        ];
                                    })->toArray(),
                                ];
                            });
                        }

                        // Add in-video quizzes
                        if ($material->inVideoQuizzes->isNotEmpty()) {
                            $lessonData['quizzes'] = $material->inVideoQuizzes->map(function ($quiz) {
                                return [
                                    'quiz_title' => $quiz->quiz_name,
                                    'added_by' => 'Instructor',
                                    'timestamp' => $quiz->timestamp,
                                    'questions_count' => $quiz->questions_count,
                                ];
                            });
                        }

                        return $lessonData;
                    }),
                ];
            });

            return response()->json([
                'course_id' => $course->id,
                'course_name' => $course->title,
                'content' => $content,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء جلب محتوى الدورة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper method to map lesson type to material type
     */
    private function mapLessonTypeToMaterialType($lessonType)
    {
        return match ($lessonType) {
            'فيديو' => 'video',
            'امتحان' => 'quiz',
            'ملفات' => 'document',
            default => 'document',
        };
    }

    /**
     * Helper method to map material type to lesson type
     */
    private function mapMaterialTypeToLessonType($materialType)
    {
        return match ($materialType) {
            'video' => 'فيديو',
            'quiz' => 'امتحان',
            'document' => 'ملفات',
            default => 'ملفات',
        };
    }

    /**
     * Create course structure with topics and lessons
     */
    public function createCourseStructure(Request $request, Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only modify courses that you created.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'topics' => 'required|array|min:1',
            'topics.*.title' => 'required|string|max:255',
            'topics.*.description' => 'nullable|string',
            'topics.*.order' => 'required|integer|min:0',
            'topics.*.lessons' => 'required|array|min:1',
            'topics.*.lessons.*.title' => 'required|string|max:255',
            'topics.*.lessons.*.type' => 'required|string|in:video,quiz,files,document',
            'topics.*.lessons.*.order' => 'required|integer|min:0',
            'topics.*.lessons.*.fields' => 'nullable|array',
            'topics.*.lessons.*.fields.lesson_title' => 'nullable|string',
            'topics.*.lessons.*.fields.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->topics as $topicData) {
                // Create topic
                $topic = Topic::create([
                    'course_id' => $course->id,
                    'title' => $topicData['title'],
                    'description' => $topicData['description'] ?? null,
                    'order' => $topicData['order'],
                    'is_active' => true,
                ]);

                // Create lessons for this topic
                foreach ($topicData['lessons'] as $lessonData) {
                    $material = CourseMaterial::create([
                        'course_id' => $course->id,
                        'topic_id' => $topic->id,
                        'title' => $lessonData['title'],
                        'type' => $lessonData['type'],
                        'order' => $lessonData['order'],
                        'is_active' => true,
                    ]);

                    // Handle different lesson types
                    if ($lessonData['type'] === 'video' && isset($lessonData['video']['uploads'])) {
                        foreach ($lessonData['video']['uploads'] as $upload) {
                            if ($upload['status'] === 'مكتمل') {
                                FileUpload::create([
                                    'course_id' => $course->id,
                                    'material_id' => $material->id,
                                    'uploaded_by' => $user->id,
                                    'file_name' => $upload['file_name'],
                                    'original_name' => $upload['file_name'],
                                    'file_path' => 'videos/'.$upload['file_name'],
                                    'file_type' => 'video',
                                    'mime_type' => 'video/mp4',
                                    'file_size' => 0,
                                    'status' => 'completed',
                                    'progress' => 100,
                                ]);

                                $material->update([
                                    'file_path' => 'videos/'.$upload['file_name'],
                                    'duration' => 0,
                                ]);
                            }
                        }
                    }

                    if ($lessonData['type'] === 'files' && isset($lessonData['files']['list'])) {
                        $filePaths = [];
                        foreach ($lessonData['files']['list'] as $file) {
                            if ($file['status'] === 'مكتمل') {
                                FileUpload::create([
                                    'course_id' => $course->id,
                                    'material_id' => $material->id,
                                    'uploaded_by' => $user->id,
                                    'file_name' => $file['file_name'],
                                    'original_name' => $file['file_name'],
                                    'file_path' => 'files/'.$file['file_name'],
                                    'file_type' => 'pdf',
                                    'mime_type' => 'application/pdf',
                                    'file_size' => 0,
                                    'status' => 'completed',
                                    'progress' => 100,
                                ]);

                                $filePaths[] = 'files/'.$file['file_name'];
                            }
                        }
                        if (! empty($filePaths)) {
                            $material->update(['file_path' => json_encode($filePaths)]);
                        }
                    }

                    if ($lessonData['type'] === 'quiz' && isset($lessonData['questions'])) {
                        // Create assessment for quiz
                        $assessment = Assessment::create([
                            'title' => $lessonData['title'],
                            'description' => $lessonData['fields']['description'] ?? null,
                            'type' => 'quiz',
                            'passing_score' => 70,
                            'time_limit' => null,
                            'is_active' => true,
                        ]);

                        $material->update(['assessment_id' => $assessment->id]);

                        // Add questions to assessment
                        foreach ($lessonData['questions'] as $questionData) {
                            $question = AssessmentQuestion::create([
                                'assessment_id' => $assessment->id,
                                'question_text' => $questionData['question_text'],
                                'question_type' => 'multiple_choice',
                                'order' => $questionData['question_number'],
                            ]);

                            // Add answers
                            foreach ($questionData['answers'] as $answerData) {
                                AssessmentAnswer::create([
                                    'question_id' => $question->id,
                                    'answer_text' => $answerData['answer_text'],
                                    'is_correct' => $answerData['is_correct'],
                                    'order' => 0,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'تم إنشاء هيكل الدورة بنجاح',
                'course_id' => $course->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'حدث خطأ أثناء إنشاء هيكل الدورة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get course structure with topics and lessons
     */
    public function getCourseStructure(Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only view courses that you created.',
            ], 403);
        }

        $courseStructure = $course->load([
            'topics' => function ($query) {
                $query->ordered()->with([
                    'lessons' => function ($q) {
                        $q->orderBy('order')->with(['assessment.questions.answers', 'fileUploads']);
                    },
                ]);
            },
        ]);

        return response()->json([
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'instructor' => $course->instructor->name,
            ],
            'topics' => $courseStructure->topics->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'description' => $topic->description,
                    'order' => $topic->order,
                    'is_active' => $topic->is_active,
                    'lessons' => $topic->lessons->map(function ($lesson) {
                        $lessonData = [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'type' => $lesson->type,
                            'order' => $lesson->order,
                            'is_active' => $lesson->is_active,
                            'duration' => $lesson->duration,
                        ];

                        if ($lesson->type === 'video') {
                            $lessonData['file_path'] = $lesson->file_path;
                        }

                        if ($lesson->type === 'files') {
                            $lessonData['files'] = json_decode($lesson->file_path, true);
                        }

                        if ($lesson->type === 'quiz' && $lesson->assessment) {
                            $lessonData['assessment'] = [
                                'id' => $lesson->assessment->id,
                                'title' => $lesson->assessment->title,
                                'questions' => $lesson->assessment->questions->map(function ($question) {
                                    return [
                                        'id' => $question->id,
                                        'question_text' => $question->question_text,
                                        'answers' => $question->answers->map(function ($answer) {
                                            return [
                                                'id' => $answer->id,
                                                'answer_text' => $answer->answer_text,
                                                'is_correct' => $answer->is_correct,
                                            ];
                                        }),
                                    ];
                                }),
                            ];
                        }

                        return $lessonData;
                    }),
                ];
            }),
        ]);
    }
}
