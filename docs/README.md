# ASTA Learning Platform API Documentation

This directory contains comprehensive API documentation for the ASTA Learning Platform.

## Files Overview

### 1. `swagger_api_documentation.yaml`
Complete OpenAPI 3.0 specification for all API endpoints. This file contains:
- All endpoint definitions with HTTP methods
- Request/response schemas
- Authentication requirements
- Query parameters and request bodies
- Response examples
- Error codes and messages

### 2. `api_documentation.md`
Comprehensive Markdown documentation including:
- Detailed endpoint descriptions
- Request/response examples
- Authentication instructions
- Error handling
- Data models
- Rate limiting information

### 3. `swagger_viewer.html`
Interactive Swagger UI viewer for the API documentation. Open this file in a web browser to:
- View all endpoints in an interactive interface
- Test API calls directly from the browser
- See request/response schemas
- Understand authentication requirements

## How to Use

### Viewing the Documentation

1. **Interactive Swagger UI**:
   - Open `swagger_viewer.html` in your web browser
   - This provides an interactive interface to explore all endpoints
   - You can test API calls directly from the browser

2. **Markdown Documentation**:
   - Open `api_documentation.md` in any markdown viewer
   - Provides detailed explanations and examples
   - Good for understanding the overall API structure

3. **YAML Specification**:
   - Open `swagger_api_documentation.yaml` in any text editor
   - Machine-readable format for API tools
   - Can be imported into API testing tools like Postman

### API Endpoints Summary

#### Authentication
- `POST /register` - Register new user
- `POST /login` - User login
- `POST /login/google` - Google OAuth login

#### Courses
- `GET /courses` - Get all courses (with filtering)
- `POST /courses` - Create course
- `GET /courses/{id}` - Get course details
- `PUT /courses/{id}` - Update course
- `DELETE /courses/{id}` - Delete course
- `POST /courses/{id}/enroll` - Enroll in course
- `GET /my-courses` - Get user's enrolled courses
- `GET /courses/{id}/progress` - Get course progress

#### Course Filtering
- `GET /courses/recent` - Recent courses
- `GET /courses/top-rated` - Top rated courses
- `GET /courses/popular` - Popular courses
- `GET /courses/free` - Free courses

#### Course Materials
- `GET /courses/{id}/materials` - Get course materials
- `POST /courses/{id}/materials` - Add material
- `GET /courses/{id}/materials/{id}/signed-url` - Get download URL
- `POST /courses/{id}/materials/{id}/complete` - Mark as completed

#### Reviews
- `GET /courses/{id}/reviews` - Get course reviews
- `POST /courses/{id}/reviews` - Create review
- `PUT /reviews/{id}` - Update review
- `DELETE /reviews/{id}` - Delete review

#### Assessments
- `GET /assessments` - Get all assessments
- `POST /assessments` - Create assessment
- `GET /assessments/{id}` - Get assessment details
- `PUT /assessments/{id}` - Update assessment
- `DELETE /assessments/{id}` - Delete assessment
- `POST /assessments/{id}/assign` - Assign to users

#### Assessment Attempts
- `GET /attempts` - Get attempts
- `POST /assessments/{id}/attempts` - Start attempt
- `GET /attempts/{id}` - Get attempt details
- `POST /attempts/{id}/submit` - Submit attempt
- `POST /attempts/{id}/grade` - Grade attempt

#### Categories
- `GET /categories` - Get all categories
- `POST /categories` - Create category
- `GET /categories/{id}` - Get category details
- `PUT /categories/{id}` - Update category
- `DELETE /categories/{id}` - Delete category
- `GET /categories/{id}/courses` - Get category courses

#### Degrees (Admin Only)
- `GET /degrees` - Get all degrees
- `POST /degrees` - Create degree
- `GET /degrees/{id}` - Get degree details
- `PUT /degrees/{id}` - Update degree
- `DELETE /degrees/{id}` - Delete degree

#### Partners
- `GET /partners` - Get all partners
- `POST /partners` - Create partner
- `GET /partners/{id}` - Get partner details
- `PUT /partners/{id}` - Update partner
- `DELETE /partners/{id}` - Delete partner

#### Utility
- `GET /hello` - Hello endpoint
- `GET /ping` - Health check

## Authentication

Most endpoints require authentication using Bearer tokens. Include the token in the Authorization header:

```
Authorization: Bearer <your-token>
```

## Base URLs

- **Development**: `http://localhost:8000/api`
- **Production**: `https://api.asta.com`

## Common Response Codes

- `200` - Success
- `201` - Created
- `204` - No Content
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Rate Limiting

- Authentication endpoints: 5 requests per minute
- Other endpoints: 60 requests per minute

## File Uploads

For file uploads, use `multipart/form-data` content type:
- Maximum file size: 100MB for course materials
- Supported image formats: jpeg, png, jpg, gif, svg
- Files are stored in the public disk

## Testing the API

### Using Swagger UI
1. Open `swagger_viewer.html` in your browser
2. Click "Authorize" to add your Bearer token
3. Navigate to any endpoint
4. Click "Try it out" to test the endpoint
5. Fill in the required parameters
6. Click "Execute" to make the request

### Using Postman
1. Import the `swagger_api_documentation.yaml` file into Postman
2. Set up environment variables for base URL and token
3. Test endpoints using the imported collection

### Using curl
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Get courses (with token)
curl -X GET http://localhost:8000/api/courses \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Development Notes

- All endpoints are RESTful
- Pagination is implemented for list endpoints
- File uploads support multiple formats
- Authentication uses Laravel Sanctum
- Role-based access control is implemented
- Soft deletes are used for data integrity

## Support

For API support or questions, please contact the development team at support@asta.com 