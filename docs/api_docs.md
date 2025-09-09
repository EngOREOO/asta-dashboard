# API Documentation

## Degree Endpoints

- **GET /api/degrees**
  - Description: List all active degrees, ordered by sort_order
  - Response: Array of degree objects with id, name, name_ar, level, description, average_rating, price, etc.
  - Example Response:
    ```json
    [
      {
        "id": 1,
        "name": "Primary School",
        "name_ar": "المرحلة الابتدائية",
        "level": 1,
        "description": "Primary education (grades 1-6)",
        "is_active": true,
        "sort_order": 1,
        "courses_count": 15,
        "average_rating": 4.2,
        "price": 29.99
      },
      {
        "id": 2,
        "name": "Intermediate School",
        "name_ar": "المرحلة المتوسطة",
        "level": 2,
        "description": "Intermediate education (grades 7-9)",
        "is_active": true,
        "sort_order": 2,
        "courses_count": 12,
        "average_rating": 4.5,
        "price": 34.99
      }
    ]
    ```

- **POST /api/degrees** (Admin only)
  - Description: Create a new degree
  - Body:
    ```json
    {
      "name": "New Degree",
      "name_ar": "مسار جديدة",
      "level": 8,
      "description": "Description of the new degree",
      "is_active": true,
      "sort_order": 8
    }
    ```
  - Response: Created degree object

- **GET /api/degrees/{degree}**
  - Description: Get details of a specific degree
  - Response: Degree object with all details

- **PUT/PATCH /api/degrees/{degree}** (Admin only)
  - Description: Update a degree
  - Body: Same as POST, all fields are optional
  - Response: Updated degree object

- **DELETE /api/degrees/{degree}** (Admin only)
  - Description: Delete a degree (only if not used by any courses)
  - Response: Success message or error if degree is in use

## Authentication Endpoints

- **POST /api/register**
  - Description: Register a new user.
  - Body: `{ "name": "User Name", "email": "user@example.com", "password": "password" }`

- **POST /api/login**
  - Description: Log in a user and receive an authentication token.
  - Body: `{ "email": "user@example.com", "password": "password" }`
  - Response: `{ "user": { ... }, "token": "your-auth-token" }`

- **POST /api/login/google**
  - Description: Log in a user using Google authentication.
  - Body: `{ "token": "google-auth-token" }`
  - Response: `{ "user": { ... }, "token": "your-auth-token" }`

## Category Endpoints

- **GET /api/categories**
  - Description: List all available course categories.
  - Response: Array of category objects with `id`, `name`, `slug`, `description`, and `image_url`

- **POST /api/categories**
  - Description: Create a new category (Admin only).
  - Body: `{ "name": "Category Name", "description": "Category description", "image_url": "https://example.com/image.jpg" }`

- **GET /api/categories/{category}**
  - Description: Get details of a specific category.
  - Parameters: `category` can be either the category ID or slug

- **PUT/PATCH /api/categories/{category}**
  - Description: Update a category (Admin only).
  - Parameters: `category` can be either the category ID or slug
  - Body: `{ "name": "Updated Name", "description": "Updated description", "image_url": "https://example.com/new-image.jpg" }`

- **DELETE /api/categories/{category}**
  - Description: Delete a category (Admin only). Categories with associated courses cannot be deleted.
  - Parameters: `category` can be either the category ID or slug

- **GET /api/categories/{category}/courses**
  - Description: Get all courses in a specific category.
  - Parameters: `category` can be either the category ID or slug
  - Response: Paginated list of courses with their details

## Partner Endpoints

- **GET /api/partners**
  - Description: List all active partners.
  - Response: Array of partner objects with `id`, `name`, `image_url`, `website`, `description`, and `sort_order`
  - Example Response:
    ```json
    [
      {
        "id": 1,
        "name": "Partner Name",
        "image_url": "http://example.com/storage/partners/image.jpg",
        "website": "https://example.com",
        "description": "Partner description",
        "sort_order": 1
      }
    ]
    ```

- **POST /api/partners**
  - Description: Create a new partner (Admin only).
  - Authentication: Required (Bearer token)
  - Content-Type: `multipart/form-data`
  - Body:
    - `name`: string (required) - Name of the partner
    - `image`: file (required) - Partner logo/image (jpeg, png, jpg, gif, svg, max 2MB)
    - `website`: string (optional) - Partner website URL
    - `description`: string (optional) - Partner description
    - `is_active`: boolean (optional, default: true) - Whether the partner is active
    - `sort_order`: integer (optional, default: 0) - Sort order for display

- **GET /api/partners/{partner}**
  - Description: Get details of a specific partner.
  - Parameters: `partner` - Partner ID
  - Response: Partner object with all details
  - Example Response:
    ```json
    {
      "id": 1,
      "name": "Partner Name",
      "image_url": "http://example.com/storage/partners/image.jpg",
      "website": "https://example.com",
      "description": "Partner description",
      "is_active": true,
      "sort_order": 1,
      "created_at": "2025-06-10T18:00:00Z",
      "updated_at": "2025-06-10T18:00:00Z"
    }
    ```

- **PUT/PATCH /api/partners/{partner}**
  - Description: Update a partner (Admin only).
  - Authentication: Required (Bearer token)
  - Content-Type: `multipart/form-data`
  - Parameters: `partner` - Partner ID
  - Body:
    - `name`: string (optional) - Name of the partner
    - `image`: file (optional) - New partner logo/image (jpeg, png, jpg, gif, svg, max 2MB)
    - `website`: string (optional) - Partner website URL
    - `description`: string (optional) - Partner description
    - `is_active`: boolean (optional) - Whether the partner is active
    - `sort_order`: integer (optional) - Sort order for display

- **DELETE /api/partners/{partner}**
  - Description: Delete a partner (Admin only).
  - Authentication: Required (Bearer token)
  - Parameters: `partner` - Partner ID
  - Response: 204 No Content on success

## Course Review Endpoints

### Review Management

- **GET /api/courses/{course}/reviews**
  - Description: Get paginated reviews for a course (only approved reviews)
  - Response: Paginated list of reviews with user details
  - Example Response:
    ```json
    {
      "data": [
        {
          "id": 1,
          "rating": 5,
          "message": "Great course with excellent content!",
          "created_at": "2025-06-10T15:30:00+00:00",
          "updated_at": "2025-06-10T15:30:00+00:00",
          "user": {
            "id": 1,
            "name": "John Doe",
            "avatar": "https://example.com/storage/profiles/abc123.jpg"
          }
        }
      ],
      "links": {
        "first": "...",
        "last": "...",
        "prev": null,
        "next": null
      },
      "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "...",
        "per_page": 10,
        "to": 1,
        "total": 1
      }
    }
    ```

- **POST /api/courses/{course}/reviews**
  - Description: Create a new review for a course (must be enrolled)
  - Body:
    ```json
    {
      "rating": 5,
      "message": "Great course with excellent content!"
    }
    ```
  - Response: Created review object
  - Note: Requires authentication, user must be enrolled in the course

- **PUT /api/reviews/{review}**
  - Description: Update a review (only by owner or admin)
  - Body: Same as create, all fields optional
  - Response: Updated review object

- **DELETE /api/reviews/{review}**
  - Description: Delete a review (only by owner or admin)
  - Response: 204 No Content

## Course Endpoints

- **GET /api/courses/{id}**
  - Description: Get comprehensive course details with topics, lessons, progress, and all data
  - Authentication: Optional (returns more data if authenticated and enrolled)
  - Response: Comprehensive course object with the following structure:
    ```json
    {
      "course": {
        "id": "course_1",
        "title": "Course Title",
        "slug": "course-slug",
        "instructor": {
          "id": "inst_10",
          "name": "Instructor Name",
          "avatarUrl": "https://example.com/avatar.jpg",
          "ratingAvg": 4.5,
          "ratingCount": 128
        },
        "overview": {
          "description": "Course description",
          "level": "Intermediate",
          "estimatedHours": 24.5
        },
        "progress": {
          "overallPercent": 30,
          "completed": false,
          "lastLessonId": "lesson_2_1"
        },
        "stats": {
          "topicsCount": 3,
          "lessonsCount": 14,
          "materialsCount": 22,
          "quizzesCount": 4
        },
        "topics": [
          {
            "id": "topic_1",
            "title": "Topic Title",
            "order": 1,
            "progress": {
              "percent": 60,
              "completedLessons": 3,
              "totalLessons": 5,
              "completed": false
            },
            "lessons": [
              {
                "id": "lesson_1_1",
                "type": "video",
                "title": "Lesson Title",
                "order": 1,
                "durationSec": 900,
                "video": {
                  "url": "https://example.com/video.mp4",
                  "poster": "https://example.com/poster.jpg"
                },
                "materials": [
                  {
                    "id": "mat_101",
                    "type": "pdf",
                    "title": "Lesson Materials",
                    "url": "https://example.com/materials.pdf",
                    "sizeBytes": 1048576
                  }
                ],
                "progress": {
                  "percent": 100,
                  "completed": true
                },
                "allowComments": true
              }
            ]
          }
        ],
        "ratings": {
          "course": {
            "average": 4.6,
            "count": 320
          },
          "instructor": {
            "average": 4.7,
            "count": 280
          }
        },
        "comments": [
          {
            "id": "cmt_1",
            "userId": "u_55",
            "target": {
              "type": "course",
              "id": "course_1"
            },
            "text": "Great course!",
            "createdAt": "2025-08-20T18:00:00Z",
            "user": {
              "id": "u_55",
              "name": "User Name",
              "avatarUrl": "https://example.com/user-avatar.jpg"
            },
            "instructorReply": {
              "text": "Thank you!",
              "repliedAt": "2025-08-21T10:00:00Z",
              "instructor": {
                "id": "inst_10",
                "name": "Instructor Name",
                "avatarUrl": "https://example.com/instructor-avatar.jpg"
              }
            }
          }
        ],
        "filters": {
          "comments": {
            "byScope": ["course", "topic", "lesson"],
            "myCommentsOnly": false
          },
          "ratings": {
            "targets": ["course", "instructor"]
          }
        }
      }
    }
    ```

- **POST /api/courses/{course}/rate**
  - Description: Rate a course and instructor with optional comments
  - **Access**: Enrolled students and course instructors
  - Request Body:
    ```json
    {
      "course_rating": 5,
      "instructor_rating": 4,
      "course_comment": "Great course!",
      "instructor_comment": "Excellent instructor!"
    }
    ```
  - **Features**:
    - ✅ **Flexible Rating**: All fields are optional - you can rate just the course, just the instructor, or both
    - ✅ **Student Comments**: Enrolled students can add comments to courses
    - ✅ **Instructor Comments**: Instructors can add comments about their courses
    - ✅ **Auto-Approval**: Instructor comments are automatically approved
    - ✅ **Enrollment Check**: Only enrolled students can rate and comment
  - Response: Rating objects with success message

- **POST /api/courses/{course}/comments/{comment}/reply**
  - Description: Reply to a course comment (students and instructors can reply)
  - **Access**: Enrolled students and course instructors
  - Request Body:
    ```json
    {
      "reply": "Thank you for your feedback!"
    }
    ```
  - **Features**:
    - ✅ **Student Replies**: Enrolled students can reply to comments
    - ✅ **Instructor Replies**: Course instructors can reply to comments
    - ✅ **Real-time Updates**: Replies are immediately visible
    - ✅ **Role Identification**: Shows whether reply is from student or instructor
  - Response: Comment with reply details

## Course Management Endpoints

### Basic Course Operations

- **GET /api/courses**
  - Description: List courses with filtering and sorting options
  - Query Parameters:
    - `degree_id`: Filter by academic degree ID
    - `category_id`: Filter by category ID
    - `is_free`: Filter free courses (set to `true`)
    - `sort`: Sort order (`newest`, `top_rated`, `price_low_high`, `price_high_low`, `most_popular`)
    - `per_page`: Items per page (default: 10)
  - Response: Paginated list of courses with details including instructor, category, and degree
  - Example: `/api/courses?degree_id=3&sort=top_rated&per_page=15`

- **GET /api/courses/recent**
  - Description: Get recently added courses
  - Response: Array of 10 most recently added courses
  - Example Response:
    ```json
    [
      {
        "id": 1,
        "title": "Introduction to Programming",
        "description": "Learn programming basics",
        "price": 49.99,
        "average_rating": 4.5,
        "total_ratings": 120,
        "instructor": { "name": "John Doe" },
        "category": { "name": "Programming" },
        "degree": { "name": "Beginner", "level": 1 }
      }
    ]
    ```

- **GET /api/courses/top-rated**
  - Description: Get top rated courses (rating >= 4.0)
  - Response: Array of top rated courses, ordered by rating
  - Example Response: Same structure as recent courses

- **GET /api/courses/popular**
  - Description: Get most popular courses based on enrollment
  - Response: Array of courses ordered by number of students
  - Example Response: Same structure as recent courses

- **GET /api/courses/free**
  - Description: Get free courses
  - Response: Array of free courses (price = 0)
  - Example Response: Same structure as recent courses

- **POST /api/courses**
  - Description: Create a new course (Instructor only)
  - Body: 
    ```json
    {
      "title": "Course Title",
      "description": "Course description",
      "price": 99.99,
      "category_id": 1,
      "degree_id": 3,
      "thumbnail": "path/to/thumbnail.jpg"
    }
    ```
  - Response: Created course object with full details

- **GET /api/courses/{course}**
  - Description: View details of a specific course.
  - Parameters: `course` - Course ID
  - Response: Course object with all details, materials, and instructor information

- **PUT /api/courses/{course}**
  - Description: Update a course (Instructor/Admin only).
  - Parameters: `course` - Course ID
  - Body: 
    ```json
    {
      "title": "Updated Title",
      "description": "Updated Description",
      "category_id": 2,
      "price": 129.99,
      "thumbnail": "https://example.com/new-thumbnail.jpg"
    }
    ```

- **DELETE /api/courses/{course}**
  - Description: Delete a course (Instructor/Admin only).
  - Parameters: `course` - Course ID

### Course Status Management

- **POST /api/courses/{course}/submit-for-approval**
  - Description: Submit a course for admin approval (Instructor only).
  - Parameters: `course` - Course ID
  - Changes status from 'draft' to 'pending'

- **POST /api/courses/{course}/approve**
  - Description: Approve a pending course (Admin only).
  - Parameters: `course` - Course ID
  - Changes status from 'pending' to 'approved'

- **POST /api/courses/{course}/reject**
  - Description: Reject a pending course (Admin only).
  - Parameters: `course` - Course ID
  - Body: `{ "rejection_reason": "Insufficient content" }`
  - Changes status from 'pending' to 'rejected'

### Course Materials

- **POST /api/courses/{course}/materials**
  - Description: Add a new material to a course (Instructor only).
  - Parameters: `course` - Course ID
  - Body:
    ```json
    {
      "title": "Lecture 1: Introduction",
      "description": "Introduction to the course",
      "file_url": "https://example.com/lecture1.pdf",
      "type": "pdf",
      "duration_minutes": 45,
      "order": 1
    }
    ```

- **DELETE /api/courses/{course}/materials/{material}**
  - Description: Delete a material from a course (Instructor only).
  - Parameters:
    - `course` - Course ID
    - `material` - Material ID

- **GET /api/courses/{course}/materials/{material}/signed-url**
  - Description: Get a time-limited signed URL for accessing a material.
  - Parameters:
    - `course` - Course ID
    - `material` - Material ID
  - Response: `{ "url": "signed-url-here" }`

### Course Enrollment

- **POST /api/courses/{course}/enroll**
  - Description: Enroll the authenticated user in a course.
  - Parameters: `course` - Course ID
  - Response: Enrollment confirmation

- **GET /api/my-courses**
  - Description: List courses the authenticated user is enrolled in.
  - Response: Array of course objects with progress information

### Course Progress Tracking

- **POST /api/courses/{course}/materials/{material}/complete**
  - Description: Mark a material as completed for the authenticated user.
  - Parameters:
    - `course` - Course ID
    - `material` - Material ID

- **GET /api/courses/{course}/progress**
  - Description: Get the progress of a course for the authenticated user.
  - Parameters: `course` - Course ID
  - Response: 
    ```json
    {
      "course_id": 1,
      "total_materials": 10,
      "completed_materials": 3,
      "progress_percentage": 30,
      "last_accessed": "2025-06-10T15:30:00Z"
    }
    ```


## Assessment Management Endpoints

### Basic Assessment Operations

- **GET /api/assessments**
  - Description: List assessments based on user role.
    - Students see only assigned assessments.
    - Instructors see assessments they created.
    - Admins see all assessments.
  - Query Parameters:
    - `course_id`: Filter by course ID
    - `type`: Filter by assessment type (quiz, exam, assignment)
    - `status`: Filter by status (draft, published, archived)
  - Response: Paginated list of assessments with basic details

- **POST /api/assessments**
  - Description: Create a new assessment (Instructor only).
  - Body:
    ```json
    {
      "course_id": 1,
      "title": "Midterm Exam",
      "description": "Test your understanding of the course material",
      "type": "exam",
      "time_limit_minutes": 60,
      "due_date": "2025-06-30T23:59:59Z",
      "total_points": 100,
      "passing_score": 70,
      "is_published": false
    }
    ```
  - Response: Created assessment object with full details

- **GET /api/assessments/{assessment}**
  - Description: View details of a specific assessment.
  - Parameters: `assessment` - Assessment ID
  - Response: Assessment object with all details, including questions and answers (if applicable)

- **PUT /api/assessments/{assessment}**
  - Description: Update an assessment (Instructor/Admin only).
  - Parameters: `assessment` - Assessment ID
  - Body:
    ```json
    {
      "title": "Updated Exam Title",
      "description": "Updated description",
      "time_limit_minutes": 90,
      "due_date": "2025-07-15T23:59:59Z",
      "is_published": true
    }
    ```

- **DELETE /api/assessments/{assessment}**
  - Description: Delete an assessment (Instructor/Admin only).
  - Parameters: `assessment` - Assessment ID

### Assessment Assignment

- **POST /api/assessments/{assessment}/assign**
  - Description: Assign an assessment to students (Instructor only).
  - Parameters: `assessment` - Assessment ID
  - Body:
    ```json
    {
      "user_ids": [1, 2, 3],
      "due_date": "2025-07-15T23:59:59Z",
      "available_from": "2025-06-20T00:00:00Z",
      "available_until": "2025-07-15T23:59:59Z"
    }
    ```
  - Response: Confirmation of the assignment

### Question Management

- **GET /api/assessments/{assessment}/questions**
  - Description: List all questions for an assessment.
  - Parameters: `assessment` - Assessment ID
  - Response: Array of question objects

- **POST /api/assessments/{assessment}/questions**
  - Description: Add a new question to an assessment (Instructor only).
  - Parameters: `assessment` - Assessment ID
  - Body (for MCQ):
    ```json
    {
      "question_text": "What is 2+2?",
      "type": "mcq",
      "points": 10,
      "options": [
        {"text": "3", "is_correct": false},
        {"text": "4", "is_correct": true},
        {"text": "5", "is_correct": false}
      ],
      "explanation": "2+2 equals 4"
    }
    ```
  - Body (for Essay):
    ```json
    {
      "question_text": "Explain the main concepts of the course.",
      "type": "essay",
      "points": 20,
      "word_limit": 500
    }
    ```

- **GET /api/assessments/{assessment}/questions/{question}**
  - Description: Get details of a specific question.
  - Parameters:
    - `assessment` - Assessment ID
    - `question` - Question ID

- **PUT /api/assessments/{assessment}/questions/{question}**
  - Description: Update a question (Instructor only).
  - Parameters:
    - `assessment` - Assessment ID
    - `question` - Question ID
  - Body: Updated question data

- **DELETE /api/assessments/{assessment}/questions/{question}**
  - Description: Delete a question (Instructor only).
  - Parameters:
    - `assessment` - Assessment ID
    - `question` - Question ID

## Assessment Attempt Endpoints

### Attempt Management

- **GET /api/attempts**
  - Description: List assessment attempts based on user role.
    - Students see only their own attempts.
    - Instructors see attempts for their courses.
    - Admins see all attempts.
  - Query Parameters:
    - `assessment_id`: Filter by assessment ID
    - `user_id`: Filter by user ID (instructors/admins only)
    - `status`: Filter by status (in_progress, submitted, graded)
  - Response: Paginated list of attempts with summary information

- **POST /api/assessments/{assessment}/attempts**
  - Description: Start a new attempt for an assessment.
  - Parameters: `assessment` - Assessment ID
  - Response:
    ```json
    {
      "attempt_id": 1,
      "started_at": "2025-06-10T15:30:00Z",
      "time_remaining_seconds": 3600,
      "questions": [
        {
          "id": 1,
          "question_text": "What is 2+2?",
          "type": "mcq",
          "points": 10,
          "options": ["3", "4", "5"],
          "order": 1
        },
        {
          "id": 2,
          "question_text": "Explain the main concepts.",
          "type": "essay",
          "points": 20,
          "word_limit": 500,
          "order": 2
        }
      ]
    }
    ```

- **GET /api/attempts/{attempt}**
  - Description: View details of a specific attempt.
  - Parameters: `attempt` - Attempt ID
  - Response:
    ```json
    {
      "id": 1,
      "assessment_id": 1,
      "user_id": 1,
      "status": "in_progress",
      "started_at": "2025-06-10T15:30:00Z",
      "submitted_at": null,
      "graded_at": null,
      "time_remaining_seconds": 1800,
      "score": null,
      "max_score": 100,
      "answers": [
        {
          "id": 1,
          "question_id": 1,
          "answer": "4",
          "points_earned": null,
          "feedback": null
        },
        {
          "id": 2,
          "question_id": 2,
          "answer": "The main concepts are...",
          "points_earned": null,
          "feedback": null
        }
      ]
    }
    ```

### Attempt Submission and Grading

- **POST /api/attempts/{attempt}/submit**
  - Description: Submit answers for an attempt.
  - Parameters: `attempt` - Attempt ID
  - Body:
    ```json
    {
      "answers": [
        {
          "question_id": 1,
          "answer": "Option A"
        },
        {
          "question_id": 2,
          "answer": "This is an essay answer."
        }
      ]
    }
    ```
  - Response: Submission confirmation with attempt status

- **POST /api/attempts/{attempt}/grade**
  - Description: Grade an attempt (Instructor only).
  - Parameters: `attempt` - Attempt ID
  - Body:
    ```json
    {
      "answers": [
        {
          "id": 1,
          "points_earned": 10,
          "feedback": "Correct answer!"
        },
        {
          "id": 2,
          "points_earned": 15,
          "feedback": "Good explanation, but could be more detailed."
        }
      ],
      "overall_feedback": "Good job on the assessment!"
    }
    ```
  - Response: Grading confirmation with final score

- **GET /api/attempts/{attempt}/results**
  - Description: View detailed results of a graded attempt.
  - Parameters: `attempt` - Attempt ID
  - Response: Detailed results including correct answers, feedback, and scoring

### Assessment Attempts

- **GET /api/attempts**
  - Description: Get assessment attempts (filtered by user role)
  - Response: Paginated attempts

- **POST /api/assessments/{assessment}/attempts**
  - Description: Start a new assessment attempt
  - Response: Attempt object with 201 status

- **GET /api/attempts/{attempt}**
  - Description: Get attempt with answers
  - Response: Attempt object with answers

- **POST /api/attempts/{attempt}/submit**
  - Description: Submit assessment attempt
  - Request Body:
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
  - Response: Attempt object with score

- **POST /api/attempts/{attempt}/grade**
  - Description: Grade assessment attempt (instructor only)
  - Request Body:
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
  - Response: Attempt object with updated score

### Quiz Attempts

- **GET /api/quizzes/my-quizzes**
  - Description: Get all quizzes taken by the authenticated user with results
  - Response: Array of quiz attempts with detailed results and statistics
  - Example Response:
    ```json
    {
      "myQuizzes": [
        {
          "id": "attempt_1",
          "quiz": {
            "id": "quiz_1",
            "title": "اختبار HTML و CSS الأساسي",
            "description": "اختبار شامل لأساسيات HTML و CSS",
            "course": {
              "id": "course_1",
              "title": "دورة تطوير الويب الشاملة",
              "instructor": {
                "id": "inst_10",
                "name": "د. ليلى محمد عبدالله"
              }
            }
          },
          "attemptNumber": 1,
          "score": 85.5,
          "totalQuestions": 4,
          "correctAnswers": 3,
          "percentage": 85.5,
          "isPassed": true,
          "timeTaken": 12,
          "startedAt": "2025-08-22T10:00:00Z",
          "completedAt": "2025-08-22T10:12:00Z",
          "canReview": true
        }
      ],
      "stats": {
        "totalAttempts": 1,
        "passedAttempts": 1,
        "averageScore": 85.5,
        "totalQuizzes": 1
      }
    }
    ```

- **POST /api/quizzes/{quiz}/start**
  - Description: Start a new quiz attempt
  - Response: Attempt object with attempt details
  - Example Response:
    ```json
    {
      "message": "Quiz attempt started successfully",
      "attempt": {
        "id": "attempt_1",
        "quizId": "quiz_1",
        "attemptNumber": 1,
        "totalQuestions": 4,
        "startedAt": "2025-08-22T10:00:00Z",
        "timeLimit": 30,
        "maxAttempts": 3,
        "currentAttempts": 1
      }
    }
    ```

- **POST /api/quizzes/attempts/{attempt}/submit**
  - Description: Submit quiz answers and complete the attempt
  - Request Body:
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
        },
        {
          "question_id": 3,
          "answer": "C"
        },
        {
          "question_id": 4,
          "answer": "D"
        }
      ],
      "timeTaken": 12
    }
    ```
  - Response: Completed attempt with score and results
  - Example Response:
    ```json
    {
      "message": "Quiz submitted successfully",
      "attempt": {
        "id": "attempt_1",
        "score": 85.5,
        "percentage": 85.5,
        "totalQuestions": 4,
        "correctAnswers": 3,
        "isPassed": true,
        "timeTaken": 12,
        "completedAt": "2025-08-22T10:12:00Z",
        "showResults": true
      },
      "quiz": {
        "id": "quiz_1",
        "title": "اختبار HTML و CSS الأساسي",
        "passingScore": 70,
        "allowReview": true
      }
    }
    ```

- **GET /api/quizzes/attempts/{attempt}**
  - Description: View detailed quiz attempt results
  - Response: Complete attempt details with question-by-question results
  - Example Response:
    ```json
    {
      "attempt": {
        "id": "attempt_1",
        "quizId": "quiz_1",
        "attemptNumber": 1,
        "score": 85.5,
        "percentage": 85.5,
        "totalQuestions": 4,
        "correctAnswers": 3,
        "isPassed": true,
        "timeTaken": 12,
        "startedAt": "2025-08-22T10:00:00Z",
        "completedAt": "2025-08-22T10:12:00Z"
      },
      "quiz": {
        "id": "quiz_1",
        "title": "اختبار HTML و CSS الأساسي",
        "description": "اختبار شامل لأساسيات HTML و CSS",
        "passingScore": 70,
        "allowReview": true,
        "course": {
          "id": "course_1",
          "title": "دورة تطوير الويب الشاملة"
        }
      },
      "questions": [
        {
          "id": "q_1",
          "question": "أي من هذه العناصر يستخدم لإنشاء قائمة غير مرتبة؟",
          "type": "multiple_choice",
          "points": 25,
          "userAnswer": "A",
          "isCorrect": true,
          "correctAnswer": "A",
          "explanation": "العنصر <ul> يستخدم لإنشاء قوائم غير مرتبة"
        }
      ],
      "summary": {
        "totalPoints": 100,
        "earnedPoints": 85.5,
        "passingScore": 70,
        "status": "Passed"
      }
    }
    ```

- **GET /api/quizzes/{quiz}/current-attempt**
  - Description: Get current in-progress attempt for a quiz
  - Response: Current attempt details if exists
  - Example Response:
    ```json
    {
      "attempt": {
        "id": "attempt_1",
        "quizId": "quiz_1",
        "attemptNumber": 1,
        "totalQuestions": 4,
        "startedAt": "2025-08-22T10:00:00Z",
        "timeLimit": 30,
        "elapsedTime": 12
      }
    }
    ```

### Quiz Features

- **Automatic Scoring**: Quizzes are automatically graded upon submission
- **Attempt Limits**: Enforces maximum attempt limits set by instructors
- **Time Tracking**: Tracks time taken to complete quizzes
- **Pass/Fail Determination**: Automatically determines pass/fail based on passing score
- **Detailed Results**: Shows question-by-question results with explanations
- **Progress Tracking**: Tracks all quiz attempts and performance
- **Course Enrollment Verification**: Only enrolled students can take quizzes
- **In-Progress Protection**: Prevents multiple simultaneous attempts

## General Endpoints

- **GET /api/hello**
  - Description: Returns a greeting message.

- **GET /api/ping**
  - Description: Returns a pong message. 
