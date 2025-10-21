# Public API Usage Guide

## Quick Start

The Public API provides access to events, news, grants, and vacancies without authentication.

### Base URLs
- **Local Development**: `http://localhost:8000/api/public`
- **Production**: `https://yourdomain.com/api/public`

### Available Endpoints

| Resource | List | Single Item |
|----------|------|-------------|
| Events | `GET /events` | `GET /events/{id}` |
| News | `GET /news` | `GET /news/{id}` |
| Grants | `GET /grants` | `GET /grants/{id}` |
| Vacancies | `GET /vacancies` | `GET /vacancies/{id}` |

### Common Query Parameters

All list endpoints support:
- `per_page` (1-100, default: 15) - Items per page
- `page` (default: 1) - Page number  
- `search` - Search in title/description
- `category` - Filter by category

### Examples

```bash
# Get first 10 events
curl "http://localhost:8000/api/public/events?per_page=10"

# Search for news about sports
curl "http://localhost:8000/api/public/news?search=спорт"

# Get IT vacancies
curl "http://localhost:8000/api/public/vacancies?category=IT"

# Get specific grant
curl "http://localhost:8000/api/public/grants/1"
```

### Response Format

All endpoints return JSON with this structure:

```json
{
    "status": "success",
    "data": [...],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 67
    }
}
```

### Error Responses

```json
{
    "status": "error", 
    "message": "Resource not found"
}
```

For detailed documentation see `API_DOCUMENTATION.md`.

## Testing

Run the test script:
```bash
php test_public_api.php
```

Make sure your Laravel application is running on `http://localhost:8000`.
