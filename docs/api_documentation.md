# ASTA Learning Platform API Documentation

## Overview

The ASTA Learning Platform API provides comprehensive endpoints for managing an e-learning system. This API supports user authentication, course management, assessments, reviews, and more.

## Base URL

- Development: `http://localhost:8000/api`
- Production: `https://api.asta.com`

## Authentication

Most endpoints require authentication using Bearer tokens. Include the token in the Authorization header:

```
Authorization: Bearer <your-token>
```

## Endpoints

### Authentication

#### Register User
- **POST** `/register`
- **Description**: Register a new user account
- **Request Body**:
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }
  ```
- **Response**:
  ```json
  {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "token": "1|abc123..."
  }
  ```

#### Login
- **POST** `/login`
- **Description**: Authenticate user and get access token
- **Request Body**:
  ```json
  {
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Response**:
  ```json
  {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "1|abc123..."
  }
  ```

#### Google Login
- **POST** `/login/google`
- **Description**: Authenticate using Google OAuth
- **Request Body**:
  ```json
  {
    "token": "google-oauth-token"
  }
  ```
- **Response**: Same as regular login

### Courses

#### Get All Courses
- **GET** `/courses`
- **Description**: Get paginated list of courses with filtering options
- **Query Parameters**:
  - `degree_id` (integer): Filter by degree
  - `category_id` (integer): Filter by category
  - `is_free` (boolean): Filter free courses
  - `sort` (string): Sort by (newest, top_rated, most_popular, price_low_high, price_high_low)
  - `per_page` (integer): Items per page (default: 10)
- **Response**:
  ```json
  {
    "data": [
      {
        "id": 1,
        "title": "Introduction to Programming",
        "description": "Learn programming basics",
        "price": 99.99,
        "instructor": {...},
        "category": {...},
        "average_rating": 4.5,
        "total_ratings": 120
      }
    ],
    "links": {...},
    "meta": {...}
  }
  ```

#### Create Course
- **POST** `/courses`
- **Description**: Create a new course (requires instructor/admin role)
- **Request Body** (multipart/form-data):
  ```
  title: "Course Title"
  description: "Course description"
  price: 99.99
  category_id: 1
  degree_id: 1 (optional)
  thumbnail: [file upload]
  ```
- **Response**: Course object with 201 status

#### Get Course Details
- **GET** `/courses/{course}`
- **Description**: Get comprehensive course details for single course page
- **Response**:
  ```json
  {
    "course": {
      "id": 1,
      "title": "دورة تحليل البيانات",
      "slug": "data-analysis-course",
      "description": "Course description",
      "thumbnail": "course-thumbnails/image.jpg",
      "price": 99.99,
      "status": "published",
      "average_rating": 4.5,
      "total_ratings": 120,
      "total_enrollments": 50,
      "total_materials": 10,
      "total_duration": 480,
      "category": {...},
      "degree": {...},
      "instructor": {
        "id": 1,
        "name": "Instructor Name",
        "email": "instructor@example.com",
        "bio": "Instructor bio",
        "profile_photo": "profile-photos/instructor.jpg",
        "total_courses": 5,
        "total_students": 200
      }
    },
    "user_status": {
      "is_enrolled": true,
      "progress": {
        "overall_progress": 65.5,
        "completed_materials": 6,
        "total_materials": 10,
        "material_progress": [
          {
            "material_id": 1,
            "title": "الدرس الأول",
            "is_completed": true,
            "progress_percentage": 100
          }
        ]
      },
      "certificate_status": {
        "has_certificate": false,
        "can_generate": false,
        "certificate": null
      }
    },
    "navigation_sections": {
      "overview": {
        "title": "نظرة عامة",
        "content": "Course description",
        "active": true
      },
      "materials": {
        "title": "الموضوع",
        "content": [...],
        "active": false
      },
      "reviews": {
        "title": "التقييمات",
        "content": [...],
        "active": false
      },
      "certificate": {
        "title": "الشهادة",
        "content": {...},
        "active": false
      }
    },
    "materials": [
      {
        "id": 1,
        "title": "الدرس الأول",
        "description": "Lesson description",
        "type": "video",
        "file_path": "course-materials/video1.mp4",
        "is_free": true,
        "order": 1,
        "duration": 45,
        "file_size": 10485760,
        "user_progress": {
          "is_completed": true,
          "completed_at": "2024-01-01T00:00:00.000000Z"
        }
      }
    ],
    "reviews": {
      "data": [...],
      "total": 120,
      "average": 4.5
    },
    "course_overview": {
      "title": "اسم أو عنوان الدورة",
      "rating": 4.6,
      "total_reviews": 2815,
      "last_updated": "مارس 2015",
      "language": "العربية",
      "has_translation": false,
      "level": "للمبتدئين",
      "enrolled_students": 2815,
      "total_lectures": 244,
      "total_hours": 53.5,
      "description": "النص الطويل هنا...",
      "skills_covered": [
        "بناء تطبيقات Flutter",
        "التعامل مع قواعد البيانات",
        "إدارة الحالة"
      ],
      "career_opportunities": [
        "مطور تطبيقات Flutter",
        "مطور واجهات تفاعلية"
      ],
      "overview": "تفاصيل إضافية عن الدورة",
      "prerequisites": "المتطلبات المسبقة",
      "learning_objectives": "أهداف التعلم",
      "target_audience": "الجمهور المستهدف",
      "estimated_duration": 3210
    },
    "course_stats": {
      "total_lessons": 10,
      "total_hours": 8.0,
      "difficulty_level": "intermediate",
      "language": "Arabic",
      "last_updated": "2024-01-01"
    }
  }
  ```

#### Update Course
- **PUT** `/courses/{course}`
- **Description**: Update course details
- **Request Body** (multipart/form-data):
  ```
  title: "Updated Title"
  description: "Updated description"
  price: 89.99
  thumbnail: [file upload] (optional)
  ```

#### Delete Course
- **DELETE** `/courses/{course}`
- **Description**: Delete a course
- **Response**: 204 No Content

#### Enroll in Course
- **POST** `/courses/{course}/enroll`
- **Description**: Enroll current user in a course
- **Response**:
  ```json
  {
    "message": "Enrolled successfully"
  }
  ```

#### Get Course Progress
- **GET** `/courses/{course}/progress`
- **Description**: Get user's progress in a course
- **Response**:
  ```json
  {
    "total_materials": 10,
    "completed": 6,
    "percent": 60.0
  }
  ```

#### Get My Courses
- **GET** `/my-courses`
- **Description**: Get courses enrolled by current user
- **Response**: Array of course objects

### Student-Specific Endpoints

#### Get Enrolled Courses
- **GET** `/student/enrolled-courses`
- **Description**: Get all courses the student is enrolled in
- **Response**: Array of course objects with instructor, category, and degree

#### Get Completed Courses
- **GET** `/student/completed-courses`
- **Description**: Get courses the student has completed (100% progress)
- **Response**: Array of completed course objects

#### Get Under Studying Courses
- **GET** `/student/under-studying-courses`
- **Description**: Get courses the student is currently studying (in progress)
- **Response**: Array of in-progress course objects

#### Get Wishlist
- **GET** `/student/wishlist`
- **Description**: Get student's wishlist courses
- **Response**: Array of wishlist course objects

#### Add to Wishlist
- **POST** `/student/wishlist/{course}`
- **Description**: Add a course to student's wishlist
- **Response**:
  ```json
  {
    "message": "Course added to wishlist"
  }
  ```

#### Remove from Wishlist
- **DELETE** `/student/wishlist/{course}`
- **Description**: Remove a course from student's wishlist
- **Response**:
  ```json
  {
    "message": "Course removed from wishlist"
  }
  ```

#### Get Course Suggestions
- **GET** `/student/suggestions`
- **Description**: Get personalized course suggestions based on student's behavior
- **Query Parameters**:
  - `search` (string): Search term for filtering suggestions
- **Response**:
  ```json
  {
    "suggestions": [
      {
        "id": 1,
        "title": "Advanced Programming",
        "description": "Advanced programming concepts",
        "instructor": {...},
        "category": {...},
        "degree": {...},
        "average_rating": 4.5,
        "total_ratings": 120
      }
    ],
    "search": "programming",
    "enrolled_categories": [1, 2],
    "completed_categories": [1]
  }
  ```

#### Get Dashboard Statistics
- **GET** `/student/dashboard-stats`
- **Description**: Get student's dashboard statistics
- **Response**:
  ```json
  {
    "enrolled_courses": 5,
    "completed_courses": 2,
    "under_studying_courses": 3,
    "wishlist_count": 8,
    "average_progress": 65.5,
    "total_courses": 5
  }
  ```

### Certificate Endpoints

#### Get All Certificates
- **GET** `/certificates`
- **Description**: Get all certificates for the authenticated student
- **Response**: Array of certificate objects with course details

#### Get Certificate Details
- **GET** `/certificates/{certificate}`
- **Description**: Get specific certificate details
- **Response**: Certificate object with course and instructor details

#### Generate Certificate
- **POST** `/certificates/courses/{course}/generate`
- **Description**: Generate certificate for a completed course
- **Response**:
  ```json
  {
    "message": "Certificate generated successfully",
    "certificate": {
      "id": 1,
      "user_id": 1,
      "course_id": 1,
      "issued_at": "2024-01-01T00:00:00.000000Z",
      "certificate_url": "certificates/certificate_1.pdf",
      "course": {...}
    }
  }
  ```

#### Download Certificate
- **GET** `/certificates/{certificate}/download`
- **Description**: Get download URL for certificate PDF
- **Response**:
  ```json
  {
    "download_url": "http://example.com/storage/certificates/certificate_1.pdf",
    "filename": "certificate_Introduction_to_Programming.pdf"
  }
  ```

#### Get Certificate Status
- **GET** `/certificates/courses/{course}/status`
- **Description**: Check certificate status and eligibility for a course
- **Response**:
  ```json
  {
    "course_id": 1,
    "course_title": "Introduction to Programming",
    "total_materials": 10,
    "completed_materials": 8,
    "progress_percentage": 80.0,
    "is_completed": false,
    "certificate_exists": false,
    "certificate": null,
    "can_generate": false
  }
  ```

#### Get Certificate Statistics
- **GET** `/certificates/stats`
- **Description**: Get certificate statistics for the student
- **Response**:
  ```json
  {
    "total_certificates": 5,
    "recent_certificates": [
      {
        "id": 1,
        "issued_at": "2024-01-01T00:00:00.000000Z",
        "course": {...}
      }
    ]
  }
  ```

### Instructor Dashboard Endpoints

#### Get Main Dashboard
- **GET** `/instructor/dashboard`
- **Description**: Get comprehensive instructor dashboard data with stats, earnings, and schedule
- **Response**:
  ```json
  {
    "instructorProfile": {
      "name": "Dr. Mohamed Ibrahim",
      "email": "instructor@example.com",
      "profile_photo": "profile-photos/instructor.jpg",
      "bio": "Experienced instructor..."
    },
    "quickStats": {
      "totalStudents": 150,
      "coursesAdded": 5,
      "coursesUnderReview": 2,
      "newStudentsThisMonth": 25,
      "instructorRating": 4.8,
      "coursesRating": 4.9
    },
    "earningsOverview": {
      "currency": "SAR",
      "totalEarnings": 2500.00,
      "withdrawableBalance": 2000.00,
      "highestEarningMonth": "نوفمبر 2019",
      "highestEarningYear": {
        "year": 2023,
        "salesAmount": 96000
      },
      "growthChartData": [
        { "year": 2017, "earnings": 15000 },
        { "year": 2018, "earnings": 25000 },
        { "year": 2019, "earnings": 85000 },
        { "year": 2020, "earnings": 40000 },
        { "year": 2021, "earnings": 55000 },
        { "year": 2022, "earnings": 70000 },
        { "year": 2023, "earnings": 96000 }
      ]
    },
    "studentsOverview": {
      "totalStudentsWhoCompleted": 1543,
      "completionRateData": [
        { "status": "Completed", "percentage": 85 },
        { "status": "Not Completed", "percentage": 15 }
      ]
    },
    "schedule": {
      "timelineEvents": [
        {
          "eventName": "دورة الذكاء الاصطناعي للمبتدئين",
          "eventType": "Course Duration",
          "status": "تحت المراجعة",
          "startDate": "2025-10-09",
          "endDate": "2025-11-02",
          "publishDate": null
        },
        {
          "eventName": "دورة الذكاء الاصطناعي المستوى المتقدم",
          "eventType": "Publish Date",
          "status": "مجدول للنشر",
          "startDate": null,
          "endDate": null,
          "publishDate": "2025-11-16"
        }
      ]
    }
  }
  ```

#### Get Students List
- **GET** `/instructor/students`
- **Description**: Get list of all students enrolled in instructor's courses
- **Query Parameters**:
  - `search`: Search by student name
  - `course_id`: Filter by specific course
- **Response**:
  ```json
  {
    "totalStudents": 952,
    "studentsList": {
      "data": [
        {
          "studentName": "محمد ابراهيم فتحي",
          "studentAvatar": "path/to/avatar1.png",
          "courseName": "دورة الذكاء الاصطناعي",
          "courseProgress": 90,
          "courseRating": 5,
          "instructorRating": 5
        },
        {
          "studentName": "طالب آخر",
          "studentAvatar": "path/to/avatar2.png",
          "courseName": "دورة الذكاء الاصطناعي",
          "courseProgress": 85,
          "courseRating": 4,
          "instructorRating": 5
        }
      ],
      "links": {...},
      "meta": {...}
    }
  }
  ```

#### Get Student Details
- **GET** `/instructor/students/{studentId}`
- **Description**: Get detailed information about a specific student
- **Response**:
  ```json
  {
    "studentId": "student-12345",
    "name": "محمد ابراهيم فتحي عبدالحافظ",
    "profileImageUrl": "path/to/image.png",
    "enrollmentDate": "2025-03-02",
    "lastActivity": "2025-07-25",
    "enrolledCourses": [
      {
        "courseId": "ai-101",
        "courseName": "دورة الذكاء الاصطناعي للمبتدئين",
        "progress": 75,
        "status": "In Progress",
        "grade": "B+"
      },
      {
        "courseId": "py-101",
        "courseName": "مقدمة في بايثون",
        "progress": 100,
        "status": "Completed",
        "grade": "A+"
      }
    ],
    "details": {
      "coursesCount": 2,
      "birthDate": "2004-01-25",
      "ratingForInstructor": 4,
      "totalWatchTime": 53.5
    }
  }
  ```

#### Get Revenue Data
- **GET** `/instructor/revenue`
- **Description**: Get instructor revenue and earnings data
- **Response**:
  ```json
  {
    "currency": "ر.س",
    "earningsOverview": {
      "totalEarnings": 2200.00,
      "currentYearEarnings": 2200.00,
      "highestEarningMonth": "نوفمبر 2019",
      "highestEarningYear": {
        "year": 2023,
        "sales": "96K"
      },
      "growthChartData": [
        { "year": 2016, "earnings": 5000 },
        { "year": 2017, "earnings": 40000 },
        { "year": 2018, "earnings": 50000 },
        { "year": 2019, "earnings": 60000 },
        { "year": 2020, "earnings": 10000 },
        { "year": 2021, "earnings": 20000 },
        { "year": 2022, "earnings": 80000 },
        { "year": 2023, "earnings": 96000 }
      ]
    },
    "coursesEarningsList": [
      {
        "courseName": "دورة علوم البيانات المستوى الاول",
        "courseThumbnail": "path/to/thumbnail.png",
        "courseTotalEarnings": 1220.00,
        "courseCurrentYearEarnings": 220.00
      },
      {
        "courseName": "دورة الذكاء الاصطناعي المتقدمة",
        "courseThumbnail": "path/to/thumbnail2.png",
        "courseTotalEarnings": 980.00,
        "courseCurrentYearEarnings": 180.00
      }
    ]
  }
  ```

#### Get Instructor's Courses with Status
- **GET** `/instructor/courses`
- **Description**: Get all courses created by the instructor with status information
- **Response**:
  ```json
  [
    {
      "status": "Active",
      "company": "Microsoft Corp.",
      "course_name_ar": "تحليل البيانات",
      "rating": 4.2,
      "price": 250,
      "course_id": 1,
      "total_students": 15,
      "total_materials": 8,
      "total_reviews": 5,
      "created_at": "2025-07-30",
      "updated_at": "2025-07-30"
    },
    {
      "status": "Under Review",
      "company": "Microsoft Corp.",
      "course_name_ar": "تعلم Flutter",
      "rating": 0,
      "price": 300,
      "course_id": 2,
      "total_students": 0,
      "total_materials": 0,
      "total_reviews": 0,
      "created_at": "2025-07-30",
      "updated_at": "2025-07-30"
    },
    {
      "status": "Not Completed",
      "company": "Microsoft Corp.",
      "course_name_ar": "React Native",
      "rating": 0,
      "price": 200,
      "course_id": 3,
      "total_students": 0,
      "total_materials": 0,
      "total_reviews": 0,
      "created_at": "2025-07-30",
      "updated_at": "2025-07-30"
    }
  ]
  ```

**Status Values:**
- `Active`: Course is approved/published and has materials
- `Under Review`: Course is pending/draft and has materials
- `Not Completed`: Course has no materials yet
- `Rejected`: Course was rejected by admin

#### Get Instructor Statistics
- **GET** `/instructor/stats`
- **Description**: Get instructor's course statistics
- **Response**:
  ```json
  {
    "total_courses": 5,
    "published_courses": 3,
    "pending_courses": 2,
    "total_students": 150,
    "total_revenue": 15000.00,
    "average_rating": 4.5
  }
  ```

#### Get Course Analytics
- **GET** `/instructor/courses/{course}/analytics`
- **Description**: Get detailed analytics for a specific course
- **Response**:
  ```json
  {
    "course": {
      "id": 1,
      "title": "Course Title",
      "total_students": 50,
      "total_materials": 10,
      "average_rating": 4.5,
      "total_reviews": 25
    },
    "enrollment_trends": [
      {
        "date": "2024-01-01",
        "enrollments": 5
      }
    ],
    "material_completion_rates": [
      {
        "material_id": 1,
        "title": "Lesson Title",
        "total_students": 50,
        "completed_students": 35,
        "completion_rate": 70.0
      }
    ],
    "assessment_statistics": [
      {
        "assessment_id": 1,
        "title": "Final Exam",
        "total_attempts": 30,
        "completed_attempts": 25,
        "average_score": 85.5
      }
    ],
    "recent_reviews": [...]
  }
  ```

#### Get Student Progress
- **GET** `/instructor/courses/{course}/student-progress`
- **Description**: Get student progress for a specific course
- **Response**:
  ```json
  {
    "course_id": 1,
    "course_title": "Course Title",
    "total_students": 50,
    "students": [
      {
        "student_id": 1,
        "student_name": "Student Name",
        "student_email": "student@example.com",
        "enrolled_at": "2024-01-01T00:00:00.000000Z",
        "total_materials": 10,
        "completed_materials": 8,
        "progress_percentage": 80.0,
        "last_activity": "2024-01-15T00:00:00.000000Z"
      }
    ]
  }
  ```

### Public Course Search Endpoint (No Auth Required)

#### Super Search for Courses
- **GET** `/courses/search`
- **Description**: Advanced course search with multiple filters, sorting, and search capabilities
- **Query Parameters**:
  - `q`: Search term (searches in title, description, overview, prerequisites, learning objectives, target audience, language, instructor name/bio, category, degree, materials)
  - `category_id`: Filter by category
  - `degree_id`: Filter by degree
  - `instructor_id`: Filter by instructor
  - `min_price` / `max_price`: Price range filter
  - `min_rating`: Minimum rating filter
  - `difficulty_level`: Filter by difficulty (beginner, intermediate, advanced)
  - `language`: Filter by language
  - `min_duration` / `max_duration`: Duration range filter (in minutes)
  - `created_after` / `created_before`: Date range filter
  - `is_new`: Filter for courses created in last 30 days
  - `is_popular`: Filter for courses with >10 students
  - `is_featured`: Filter for featured courses
  - `price_type`: Filter by price type (free, paid)
  - `search_in`: Advanced search in specific fields (title,description,instructor,materials)
  - `sort_by`: Sort by (relevance, price_low, price_high, rating, reviews, students, newest, oldest, duration)
  - `sort_order`: Sort order (asc, desc)
  - `per_page`: Results per page (default: 20)
- **Response**:
  ```json
  {
    "courses": {
      "data": [
        {
          "id": 1,
          "title": "دورة تطوير الويب المتقدمة",
          "description": "تعلم تطوير الويب من الصفر...",
          "thumbnail": "course-thumbnails/web-dev.jpg",
          "price": 99.99,
          "is_free": false,
          "instructor": {
            "id": 1,
            "name": "أحمد محمد",
            "profile_photo": "profile-photos/ahmed.jpg"
          },
          "category": {
            "id": 1,
            "name": "تطوير الويب"
          },
          "degree": {
            "id": 1,
            "name": "بكالوريوس"
          },
          "stats": {
            "total_students": 150,
            "average_rating": 4.5,
            "total_reviews": 25,
            "total_materials": 20,
            "total_duration": 1200,
            "total_hours": 20.0
          },
          "settings": {
            "difficulty_level": "intermediate",
            "language": "Arabic",
            "estimated_duration": 1200,
            "allow_comments": true,
            "allow_notes": true,
            "allow_ratings": true
          },
          "overview": {
            "overview": "نظرة عامة على الدورة...",
            "prerequisites": "معرفة أساسية بـ HTML و CSS",
            "learning_objectives": "تعلم React و Node.js",
            "target_audience": "المطورون المبتدئون"
          },
          "created_at": "2024-01-01T00:00:00.000000Z",
          "updated_at": "2024-01-01T00:00:00.000000Z"
        }
      ],
      "links": {...},
      "meta": {...}
    },
    "search_stats": {
      "total_results": 150,
      "current_page": 1,
      "per_page": 20,
      "last_page": 8,
      "filters_applied": {
        "search_term": "تطوير الويب",
        "category": 1,
        "min_rating": 4.0,
        "sort_by": "relevance"
      }
    },
    "suggestions": {
      "similar_titles": ["تطوير الويب للمبتدئين", "React.js المتقدم"],
      "popular_categories": ["تطوير الويب", "تطوير التطبيقات", "الذكاء الاصطناعي"],
      "trending_terms": ["Web Development", "Mobile Apps", "Data Science", "AI", "Machine Learning"]
    },
    "filters": {
      "categories": [
        {"id": 1, "name": "تطوير الويب"},
        {"id": 2, "name": "تطوير التطبيقات"}
      ],
      "degrees": [
        {"id": 1, "name": "بكالوريوس"},
        {"id": 2, "name": "ماجستير"}
      ],
      "difficulty_levels": [
        {"value": "beginner", "label": "للمبتدئين"},
        {"value": "intermediate", "label": "متوسط"},
        {"value": "advanced", "label": "متقدم"}
      ],
      "languages": [
        {"value": "Arabic", "label": "العربية"},
        {"value": "English", "label": "English"},
        {"value": "French", "label": "Français"}
      ],
      "price_types": [
        {"value": "free", "label": "مجاني"},
        {"value": "paid", "label": "مدفوع"}
      ],
      "sort_options": [
        {"value": "relevance", "label": "الأكثر صلة"},
        {"value": "newest", "label": "الأحدث"},
        {"value": "rating", "label": "الأعلى تقييماً"},
        {"value": "students", "label": "الأكثر طلاباً"},
        {"value": "price_low", "label": "السعر: من الأقل"},
        {"value": "price_high", "label": "السعر: من الأعلى"}
      ]
    }
  }
  ```

### Public Instructor Endpoints (No Auth Required)

#### Get All Instructors
- **GET** `/instructors`
- **Description**: Get all instructors with statistics and courses
- **Query Parameters**:
  - `field`: Filter by field (Web Development, Mobile Development, etc.)
  - `search`: Search by instructor name or bio
  - `sort`: Sort by (rating, courses, students, name)
- **Response**:
  ```json
  {
    "instructors": {
      "data": [
        {
          "id": 1,
          "name": "Instructor Name",
          "email": "instructor@example.com",
          "bio": "Experienced instructor...",
          "profile_photo": "profile-photos/instructor.jpg",
          "joined_at": "2024-01-01T00:00:00.000000Z",
          "stats": {
            "total_courses": 5,
            "total_students": 150,
            "average_rating": 4.5,
            "total_reviews": 25
          },
          "top_courses": [
            {
              "id": 1,
              "title": "Course Title",
              "thumbnail": "course-thumbnails/image.jpg",
              "price": 99.99,
              "average_rating": 4.5,
              "total_students": 50
            }
          ],
          "fields": ["Web Development", "Mobile Development"]
        }
      ],
      "links": {...},
      "meta": {...}
    },
    "stats": {
      "total_instructors": 25,
      "total_courses": 150,
      "total_students": 1000
    }
  }
  ```

#### Get Instructor Details
- **GET** `/instructors/{id}`
- **Description**: Get detailed instructor information with courses and reviews
- **Response**:
  ```json
  {
    "instructor": {
      "id": 1,
      "name": "Instructor Name",
      "email": "instructor@example.com",
      "bio": "Experienced instructor...",
      "profile_photo": "profile-photos/instructor.jpg",
      "joined_at": "2024-01-01T00:00:00.000000Z",
      "stats": {
        "total_courses": 5,
        "total_students": 150,
        "average_rating": 4.5,
        "total_reviews": 25,
        "total_revenue": 15000.00
      },
      "fields": ["Web Development", "Mobile Development"]
    },
    "courses": [
      {
        "id": 1,
        "title": "Course Title",
        "description": "Course description",
        "thumbnail": "course-thumbnails/image.jpg",
        "price": 99.99,
        "average_rating": 4.5,
        "total_ratings": 25,
        "total_students": 50,
        "category": {...},
        "degree": {...},
        "created_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "recent_reviews": [
      {
        "id": 1,
        "rating": 5,
        "comment": "Great instructor!",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "user": {
          "id": 1,
          "name": "Student Name",
          "profile_photo": "profile-photos/student.jpg"
        }
      }
    ]
  }
  ```

### Instructor Application Endpoints

#### Submit Instructor Application (Student)
- **POST** `/instructor-applications`
- **Description**: Submit application to become an instructor
- **Headers**: `Authorization: Bearer <student_token>`
- **Request Body**:
  ```json
  {
    "field": "Web Development",
    "job_title": "Senior Flutter Dev",
    "phone": "+966501234567",
    "bio": "I teach coding since...",
    "cv_file": "file" // Optional PDF/DOC upload
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "application_id": "65f4a2b8...",
    "message": "Application submitted successfully",
    "next_steps": "Admin review within 48h"
  }
  ```

#### Get My Application Status (Student)
- **GET** `/instructor-applications/my-application`
- **Description**: Get current application status
- **Response**:
  ```json
  {
    "application": {
      "id": 1,
      "status": "pending",
      "field": "Web Development",
      "job_title": "Senior Flutter Dev",
      "phone": "+966501234567",
      "bio": "I teach coding since...",
      "cv_url": "uploads/cv.pdf",
      "admin_feedback": null,
      "submitted_at": "2024-01-01T00:00:00.000000Z",
      "user": {
        "id": 1,
        "name": "Student Name",
        "email": "student@example.com"
      }
    },
    "can_reapply": false,
    "next_reapply_date": null
  }
  ```

#### Get Available Fields
- **GET** `/instructor-applications/available-fields`
- **Description**: Get list of available fields for applications
- **Response**:
  ```json
  {
    "fields": [
      "Web Development",
      "Mobile Development",
      "Data Science",
      "Machine Learning",
      "Artificial Intelligence",
      "Cybersecurity",
      "Cloud Computing",
      "DevOps",
      "UI/UX Design",
      "Digital Marketing"
    ]
  }
  ```

#### Admin: Get All Applications
- **GET** `/admin/instructor-applications`
- **Description**: Get all applications with filters (Admin only)
- **Query Parameters**:
  - `status`: pending, approved, rejected
  - `field`: Web Development, Mobile Development, etc.
  - `search`: Search by user name or email
- **Response**:
  ```json
  {
    "applications": {
      "data": [
        {
          "id": 1,
          "status": "pending",
          "field": "Web Development",
          "job_title": "Senior Flutter Dev",
          "phone": "+966501234567",
          "bio": "I teach coding since...",
          "cv_url": "uploads/cv.pdf",
          "submitted_at": "2024-01-01T00:00:00.000000Z",
          "user": {
            "id": 1,
            "name": "Student Name",
            "email": "student@example.com"
          },
          "reviewer": null
        }
      ],
      "links": {...},
      "meta": {...}
    },
    "stats": {
      "pending": 5,
      "approved": 10,
      "rejected": 3
    }
  }
  ```

#### Admin: Review Application
- **PATCH** `/admin/instructor-applications/{id}/review`
- **Description**: Approve or reject application (Admin only)
- **Request Body**:
  ```json
  {
    "status": "approved", // or "rejected"
    "feedback": "Need more experience" // Optional
  }
  ```
- **Response (Approval)**:
  ```json
  {
    "message": "Application approved successfully",
    "user_role_updated": true,
    "email_sent": true
  }
  ```
- **Response (Rejection)**:
  ```json
  {
    "status": "rejected",
    "feedback": "Please gain 2 more years of experience",
    "can_reapply_after": "2024-09-01",
    "email_sent": true
  }
  ```

#### Admin: Get Application Details
- **GET** `/admin/instructor-applications/{id}`
- **Description**: Get detailed application information (Admin only)
- **Response**:
  ```json
  {
    "application": {
      "id": 1,
      "status": "pending",
      "field": "Web Development",
      "job_title": "Senior Flutter Dev",
      "phone": "+966501234567",
      "bio": "I teach coding since...",
      "cv_url": "uploads/cv.pdf",
      "admin_feedback": null,
      "submitted_at": "2024-01-01T00:00:00.000000Z",
      "reviewed_at": null,
      "user": {
        "id": 1,
        "name": "Student Name",
        "email": "student@example.com",
        "created_at": "2024-01-01T00:00:00.000000Z"
      },
      "reviewer": null
    }
  }
  ```

### Course Filtering Endpoints

#### Recent Courses
- **GET** `/courses/recent`
- **Description**: Get recently added courses
- **Response**: Array of 10 most recent courses

#### Top Rated Courses
- **GET** `/courses/top-rated`
- **Description**: Get courses with high ratings
- **Response**: Array of top-rated courses

#### Popular Courses
- **GET** `/courses/popular`
- **Description**: Get most popular courses by enrollment
- **Response**: Array of popular courses

#### Free Courses
- **GET** `/courses/free`
- **Description**: Get free courses
- **Response**: Array of free courses

### Course Materials

#### Get Course Materials
- **GET** `/courses/{course}/materials`
- **Description**: Get all materials for a course
- **Response**: Array of material objects

#### Add Course Material
- **POST** `/courses/{course}/materials`
- **Description**: Add material to a course
- **Request Body** (multipart/form-data):
  ```
  title: "Material Title"
  description: "Material description"
  type: "video" (video, pdf, image, other)
  file: [file upload]
  is_free: true (optional)
  ```
- **Response**: Material object with 201 status

#### Get Material Signed URL
- **GET** `/courses/{course}/materials/{material}/signed-url`
- **Description**: Get secure URL for material download
- **Response**:
  ```json
  {
    "url": "https://example.com/signed-url"
  }
  ```

#### Mark Material Complete
- **POST** `/courses/{course}/materials/{material}/complete`
- **Description**: Mark material as completed by user
- **Response**:
  ```json
  {
    "message": "Material marked as completed"
  }
  ```

### Reviews

#### Get Course Reviews
- **GET** `/courses/{course}/reviews`
- **Description**: Get reviews for a course
- **Response**: Paginated reviews

#### Create Review
- **POST** `/courses/{course}/reviews`
- **Description**: Create a review for a course (requires enrollment)
- **Request Body**:
  ```json
  {
    "rating": 5,
    "message": "Great course! Highly recommended."
  }
  ```
- **Response**: Review object

#### Update Review
- **PUT** `/reviews/{review}`
- **Description**: Update a review
- **Request Body**:
  ```json
  {
    "rating": 4,
    "message": "Updated review message"
  }
  ```

#### Delete Review
- **DELETE** `/reviews/{review}`
- **Description**: Delete a review
- **Response**:
  ```json
  {
    "message": "Review deleted successfully"
  }
  ```

### Assessments

#### Get All Assessments
- **GET** `/assessments`
- **Description**: Get assessments (filtered by user role)
- **Response**: Paginated assessments

#### Create Assessment
- **POST** `/assessments`
- **Description**: Create a new assessment
- **Request Body**:
  ```json
  {
    "course_id": 1,
    "title": "Final Exam",
    "description": "Comprehensive final exam",
    "type": "exam"
  }
  ```
- **Response**: Assessment object with 201 status

#### Get Assessment Details
- **GET** `/assessments/{assessment}`
- **Description**: Get assessment with questions
- **Response**: Assessment object with questions

#### Update Assessment
- **PUT** `/assessments/{assessment}`
- **Description**: Update assessment details
- **Request Body**:
  ```json
  {
    "title": "Updated Title",
    "description": "Updated description",
    "type": "quiz"
  }
  ```

#### Delete Assessment
- **DELETE** `/assessments/{assessment}`
- **Description**: Delete an assessment
- **Response**: 204 No Content

#### Assign Assessment
- **POST** `/assessments/{assessment}/assign`
- **Description**: Assign assessment to users
- **Request Body**:
  ```json
  {
    "user_ids": [1, 2, 3]
  }
  ```
- **Response**:
  ```json
  {
    "message": "Assessment assigned successfully"
  }
  ```

### Assessment Attempts

#### Get Attempts
- **GET** `/attempts`
- **Description**: Get assessment attempts (filtered by user role)
- **Response**: Paginated attempts

#### Start Attempt
- **POST** `/assessments/{assessment}/attempts`
- **Description**: Start a new assessment attempt
- **Response**: Attempt object with 201 status

#### Get Attempt Details
- **GET** `/attempts/{attempt}`
- **Description**: Get attempt with answers
- **Response**: Attempt object with answers

#### Submit Attempt
- **POST** `/attempts/{attempt}/submit`
- **Description**: Submit assessment attempt
- **Request Body**:
  ```json
  {
    "answers": [
      {
        "question_id": 1,
        "answer": "A"
      },
      {
        "question_id": 2,
        "answer": "B"
      }
    ]
  }
  ```
- **Response**: Attempt object with score

#### Grade Attempt
- **POST** `/attempts/{attempt}/grade`
- **Description**: Grade assessment attempt (instructor only)
- **Request Body**:
  ```json
  {
    "answers": [
      {
        "id": 1,
        "points_earned": 10
      },
      {
        "id": 2,
        "points_earned": 5
      }
    ]
  }
  ```
- **Response**: Attempt object with updated score

### Categories

#### Get All Categories
- **GET** `/categories`
- **Description**: Get all categories
- **Response**: Array of category objects

#### Create Category
- **POST** `/categories`
- **Description**: Create a new category
- **Request Body**:
  ```json
  {
    "name": "Programming",
    "description": "Programming courses",
    "image_url": "https://example.com/image.jpg"
  }
  ```
- **Response**: Category object with 201 status

#### Get Category Details
- **GET** `/categories/{category}`
- **Description**: Get category by ID or slug
- **Response**: Category object

#### Update Category
- **PUT** `/categories/{category}`
- **Description**: Update category
- **Request Body**:
  ```json
  {
    "name": "Updated Name",
    "description": "Updated description"
  }
  ```

#### Delete Category
- **DELETE** `/categories/{category}`
- **Description**: Delete category (if no courses associated)
- **Response**: 204 No Content

#### Get Category Courses
- **GET** `/categories/{category}/courses`
- **Description**: Get courses in a category
- **Response**: Category with paginated courses

### Degrees (Admin Only)

#### Get All Degrees
- **GET** `/degrees`
- **Description**: Get all active degrees
- **Response**: Array of degree objects

#### Create Degree
- **POST** `/degrees`
- **Description**: Create a new degree
- **Request Body**:
  ```json
  {
    "name": "Bachelor's Degree",
    "name_ar": "بكالوريوس",
    "level": 1,
    "description": "Undergraduate degree",
    "is_active": true,
    "sort_order": 1
  }
  ```
- **Response**: Degree object with 201 status

#### Get Degree Details
- **GET** `/degrees/{degree}`
- **Description**: Get specific degree
- **Response**: Degree object

#### Update Degree
- **PUT** `/degrees/{degree}`
- **Description**: Update degree
- **Request Body**:
  ```json
  {
    "name": "Updated Name",
    "name_ar": "اسم محدث",
    "level": 2,
    "is_active": false
  }
  ```

#### Delete Degree
- **DELETE** `/degrees/{degree}`
- **Description**: Delete degree (if no courses associated)
- **Response**:
  ```json
  {
    "message": "Degree deleted successfully"
  }
  ```

### Partners

#### Get All Partners
- **GET** `/partners`
- **Description**: Get all active partners
- **Response**: Array of partner objects

#### Create Partner
- **POST** `/partners`
- **Description**: Create a new partner
- **Request Body** (multipart/form-data):
  ```
  name: "Partner Name"
  image: [file upload]
  website: "https://partner.com" (optional)
  description: "Partner description" (optional)
  is_active: true (optional)
  sort_order: 1 (optional)
  ```
- **Response**: Partner object with 201 status

#### Get Partner Details
- **GET** `/partners/{partner}`
- **Description**: Get specific partner
- **Response**: Partner object

#### Update Partner
- **PUT** `/partners/{partner}`
- **Description**: Update partner
- **Request Body** (multipart/form-data):
  ```
  name: "Updated Name"
  image: [file upload] (optional)
  website: "https://updated.com"
  description: "Updated description"
  is_active: false
  sort_order: 2
  ```

#### Delete Partner
- **DELETE** `/partners/{partner}`
- **Description**: Delete partner
- **Response**: 204 No Content

### Utility Endpoints

#### Hello
- **GET** `/hello`
- **Description**: Simple hello endpoint
- **Response**:
  ```json
  {
    "message": "Hello from API!"
  }
  ```

#### Ping
- **GET** `/ping`
- **Description**: Health check endpoint
- **Response**:
  ```json
  {
    "message": "pong"
  }
  ```

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "You are not authorized to perform this action."
}
```

### Not Found (404)
```json
{
  "message": "Resource not found."
}
```

## Data Models

### User
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "email_verified_at": "2024-01-01T00:00:00.000000Z",
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### Course
```json
{
  "id": 1,
  "instructor_id": 1,
  "category_id": 1,
  "degree_id": 1,
  "title": "Introduction to Programming",
  "slug": "introduction-to-programming",
  "description": "Learn programming basics",
  "thumbnail": "course-thumbnails/image.jpg",
  "price": 99.99,
  "status": "published",
  "average_rating": 4.5,
  "total_ratings": 120,
  "instructor": {...},
  "category": {...},
  "degree": {...}
}
```

### Assessment
```json
{
  "id": 1,
  "course_id": 1,
  "created_by": 1,
  "title": "Final Exam",
  "description": "Comprehensive final exam",
  "type": "exam",
  "course": {...},
  "creator": {...}
}
```

### Review
```json
{
  "id": 1,
  "course_id": 1,
  "user_id": 1,
  "rating": 5,
  "message": "Great course!",
  "is_approved": true,
  "user": {...}
}
```

## Rate Limiting

The API implements rate limiting to prevent abuse:
- Authentication endpoints: 5 requests per minute
- Other endpoints: 60 requests per minute

## Pagination

Paginated responses include:
- `data`: Array of items
- `links`: Navigation links
- `meta`: Pagination metadata

Example:
```json
{
  "data": [...],
  "links": {
    "first": "http://api.asta.com/courses?page=1",
    "last": "http://api.asta.com/courses?page=5",
    "prev": null,
    "next": "http://api.asta.com/courses?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 10,
    "to": 10,
    "total": 50
  }
}
```

## File Uploads

For file uploads, use `multipart/form-data` content type:
- Maximum file size: 100MB for course materials
- Supported formats: jpeg, png, jpg, gif, svg for images
- Files are stored in the public disk

## Security

- All sensitive endpoints require authentication
- File uploads are validated for type and size
- SQL injection protection through Laravel's query builder
- XSS protection through proper output encoding
- CSRF protection for web routes (not applicable to API) 

## Instructor Account Settings

### Get Instructor Account Settings

**Endpoint:** `GET /api/instructor/account-settings`

**Description:** Retrieve instructor account settings including profile information and security settings.

**Authentication:** Required (Bearer Token)

**Response:**
```json
{
  "user_profile": {
    "full_name": "محمد أبواقيام فندي",
    "teaching_field": {
      "value": "Web Development",
      "placeholder": "اختر المجال"
    },
    "job_title": {
      "value": "Senior Flutter Developer",
      "placeholder": "المسمى الوظيفي"
    },
    "owner_number": {
      "value": "+966501234567",
      "placeholder": "رقم المالك"
    },
    "address": {
      "district": {
        "value": "الحلوان",
        "placeholder": "الحلوان"
      },
      "street": {
        "value": "شارع الملك فهد",
        "placeholder": "البند"
      },
      "city": {
        "value": "الرياض",
        "placeholder": "المدينة"
      }
    },
    "email": {
      "value": "instructor@example.com",
      "placeholder": "البريد الالكتروني"
    },
    "description": {
      "value": "مدرب محترف في تطوير الويب",
      "placeholder": "وصف تصويفي عنك"
    }
  },
  "security_settings": {
    "current_password": {
      "value": "",
      "field_type": "password"
    },
    "new_password": {
      "value": "",
      "field_type": "password"
    }
  },
  "actions": {
    "edit_profile": "تعديل",
    "change_password": "تغيير كلمة المرور"
  },
  "metadata": {
    "language": "ar",
    "direction": "rtl",
    "form_type": "user_profile_management"
  }
}
```

### Update Instructor Account Settings

**Endpoint:** `PUT /api/instructor/account-settings`

**Description:** Update instructor profile information and security settings.

**Authentication:** Required (Bearer Token)

**Request Body:**
```json
{
  "full_name": "محمد أبواقيام فندي",
  "teaching_field": "Web Development",
  "job_title": "Senior Flutter Developer",
  "owner_number": "+966501234567",
  "district": "الحلوان",
  "street": "شارع الملك فهد",
  "city": "الرياض",
  "email": "instructor@example.com",
  "description": "مدرب محترف في تطوير الويب",
  "current_password": "currentPassword123",
  "new_password": "newPassword123",
  "new_password_confirmation": "newPassword123"
}
```

**Response:**
```json
{
  "message": "تم تحديث البيانات بنجاح",
  "user_profile": {
    "full_name": "محمد أبواقيام فندي",
    "teaching_field": {
      "value": "Web Development",
      "placeholder": "اختر المجال"
    },
    "job_title": {
      "value": "Senior Flutter Developer",
      "placeholder": "المسمى الوظيفي"
    },
    "owner_number": {
      "value": "+966501234567",
      "placeholder": "رقم المالك"
    },
    "address": {
      "district": {
        "value": "الحلوان",
        "placeholder": "الحلوان"
      },
      "street": {
        "value": "شارع الملك فهد",
        "placeholder": "البند"
      },
      "city": {
        "value": "الرياض",
        "placeholder": "المدينة"
      }
    },
    "email": {
      "value": "instructor@example.com",
      "placeholder": "البريد الالكتروني"
    },
    "description": {
      "value": "مدرب محترف في تطوير الويب",
      "placeholder": "وصف تصويفي عنك"
    }
  }
}
```

**Error Responses:**
- `401 Unauthorized`: Invalid or missing authentication token
- `403 Forbidden`: User is not an instructor
- `422 Validation Error`: Invalid input data

## Course Edit Details

### Get Course Edit Details

**Endpoint:** `GET /api/courses/{course}/edit-details`

**Description:** Get course details with comprehensive statistics for instructor editing.

**Authentication:** Required (Bearer Token)

**Parameters:**
- `course` (path): Course ID

**Response:**
```json
{
  "course_name": "دورة تحليل البيانات",
  "course_image_limitations": "JPG, PNG, Max 2MB",
  "type": "Data Analysis",
  "level": "متقدم",
  "language": "العربية",
  "price": 200.00,
  "description": "دورة شاملة في تحليل البيانات",
  "statistics": {
    "total_watch_hours": 500.00,
    "course_hours_total": 52.3,
    "total_participants": 230,
    "attraction_rating": 4.9,
    "days_this_year": 230,
    "price_per_rial_saudi": 200.00,
    "lessons_count": 65,
    "levels_count": 10,
    "tests_count": 26
  }
}
```

**Error Responses:**
- `401 Unauthorized`: Invalid or missing authentication token
- `403 Forbidden`: User is not the course instructor
- `404 Not Found`: Course not found

### Update Course Details

**Endpoint:** `PUT /api/courses/{course}/edit-details`

**Description:** Update course details including name, type, level, language, price, and description.

**Authentication:** Required (Bearer Token)

**Parameters:**
- `course` (path): Course ID

**Request Body:**
```json
{
  "course_name": "دورة تحليل البيانات",
  "type": "Data Analysis",
  "level": "متقدم",
  "language": "العربية",
  "price": 200.00,
  "description": "دورة شاملة في تحليل البيانات",
  "thumbnail": "file_upload"
}
```

**Response:**
```json
{
  "message": "تم تحديث تفاصيل الدورة بنجاح",
  "course": {
    "id": 1,
    "title": "دورة تحليل البيانات",
    "description": "دورة شاملة في تحليل البيانات",
    "price": 200.00,
    "difficulty_level": "متقدم",
    "language": "العربية",
    "category": {
      "id": 1,
      "name": "Data Analysis"
    }
  }
}
```

**Error Responses:**
- `401 Unauthorized`: Invalid or missing authentication token
- `403 Forbidden`: User is not the course instructor
- `404 Not Found`: Course not found
- `422 Validation Error`: Invalid input data 